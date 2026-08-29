<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CollectionRequest;
use App\Models\DutyRoster;
use App\Models\User;
use App\Notifications\CollectionStatusNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class UserRequestController extends Controller
{
    public function createRequest()
    {
        $isSubscriptionActive = $this->hasActiveSubscription(Auth::user());

        return view('user.create_request', compact('isSubscriptionActive'));
    }

    public function storeRequest(Request $request)
    {
        if (! $this->hasActiveSubscription(Auth::user())) {
            return redirect()->route('user.subscription', ['subscription_required' => 1])
                ->with('error', 'Please buy a subscription package before creating a collection request.');
        }

        $request->validate([
            'type' => 'required|string',
            'scheduled_date' => 'required|date|after_or_equal:today',
            'shift' => 'required|string',
            'details' => 'nullable|string',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'sector' => 'required|string|max:50',
            'road' => 'required|string|max:100',
        ]);

        // Logic to prevent booking passed shifts for today
        $isToday = $request->scheduled_date == date('Y-m-d');
        $currentTime = date('H:i');
        
        if ($isToday) {
            if ($request->shift == 'Morning Shift' && $currentTime >= '10:00') {
                return redirect()->back()->withInput()->with('error', 'Morning shift is no longer available for today.');
            }
            if ($request->shift == 'Evening Shift' && $currentTime >= '18:00') {
                return redirect()->back()->withInput()->with('error', 'Evening shift is no longer available for today.');
            }
        }

        // Use manually selected collector if provided, otherwise find assigned collector
        $manualCollectorId = $request->collector_id;
        
        if ($manualCollectorId) {
            $collectorId = $manualCollectorId;
        } else {
            $roster = DutyRoster::where('sector', $request->sector)
                ->where('date', $request->scheduled_date)
                ->where('shift', $request->shift)
                ->first();
            $collectorId = $roster ? $roster->collector_id : null;
        }

        if ($collectorId) {
            $requestCount = CollectionRequest::where('collector_id', $collectorId)
                ->where('scheduled_date', $request->scheduled_date)
                ->where('shift', $request->shift)
                ->whereIn('status', ['Pending', 'Scheduled'])
                ->count();
            
            if ($requestCount >= 50) {
                return redirect()->back()->withInput()->with('error', 'The assigned collector has reached the maximum limit of 50 requests for this shift. Please choose another collector or shift.');
            }
        }

        $status = $collectorId ? 'Scheduled' : 'Pending';

        $collectionRequest = CollectionRequest::create([
            'user_id' => Auth::id(),
            'collector_id' => $collectorId,
            'type' => $request->type,
            'scheduled_date' => $request->scheduled_date,
            'shift' => $request->shift,
            'details' => $request->details,
            'address' => $request->address ?? Auth::user()->address,
            'phone' => $request->phone ?? Auth::user()->phone,
            'sector' => $request->sector,
            'road' => $request->road,
            'zone' => 'Uttara City Corporation Zone',
            'status' => $status,
            'payment_status' => 'Paid',
            'amount' => 0,
            'transaction_id' => Auth::user()->last_transaction_id,
        ]);

        // Notify Admins
        $admins = User::where('role', 'admin')->get();
        Notification::send($admins, new CollectionStatusNotification($collectionRequest, 'New subscribed collection request created.', 'new_request'));

        if ($collectorId) {
            $collector = User::find($collectorId);

            if ($collector) {
                $collector->notify(new CollectionStatusNotification($collectionRequest, 'A new subscribed collection request has been assigned to you.', 'new_request'));
            }
        }

        return redirect()->route('user.dashboard')->with('success', 'Collection request granted successfully.');
    }

    public function getCollector(Request $request)
    {
        $request->validate([
            'sector' => 'required|string',
            'date' => 'required|date',
            'shift' => 'required|string',
        ]);

        $rosters = DutyRoster::where('sector', $request->sector)
            ->where('date', $request->date)
            ->where('shift', $request->shift)
            ->with('collector')
            ->get();

        $isToday = $request->date == date('Y-m-d');
        $currentTime = date('H:i');

        if ($rosters->count() > 0) {
            return response()->json([
                'success' => true,
                'rosters' => $rosters->map(function ($r) use ($request, $isToday, $currentTime) {
                    $isPassed = false;
                    if ($isToday) {
                        if ($r->shift == 'Morning Shift' && $currentTime >= '10:00') {
                            $isPassed = true;
                        } elseif ($r->shift == 'Evening Shift' && $currentTime >= '18:00') {
                            $isPassed = true;
                        }
                    }

                    // Check if this collector has reached the maximum limit of 50 requests for this date AND shift
                    $requestCount = CollectionRequest::where('collector_id', $r->collector->id)
                        ->where('scheduled_date', $request->date)
                        ->where('shift', $r->shift)
                        ->whereIn('status', ['Pending', 'Scheduled'])
                        ->count();
                    $isBooked = $requestCount >= 50;

                    return [
                        'shift' => $r->shift,
                        'is_booked' => $isBooked,
                        'is_passed' => $isPassed,
                        'collector' => [
                            'id' => $r->collector->id,
                            'name' => $r->collector->name,
                            'phone' => $r->collector->phone ?? 'N/A',
                            'image' => $r->collector->profile_image ? asset('storage/' . $r->collector->profile_image) : null,
                            'initial' => strtoupper(substr($r->collector->name, 0, 1)),
                        ]
                    ];
                })
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No collector assigned for this sector on the selected date.'
        ]);
    }

    public function history()
    {
        $requests = CollectionRequest::where('user_id', Auth::id())
            ->with('collector')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user.history', compact('requests'));
    }

    public function cancelRequest(CollectionRequest $request)
    {
        if ($request->user_id !== Auth::id()) {
            abort(403);
        }

        if ($request->status !== 'Pending' && $request->status !== 'Scheduled' && $request->payment_status !== 'Unpaid' && $request->status !== 'Awaiting Payment') {
            return redirect()->back()->with('error', 'Only pending, scheduled, or unpaid requests can be cancelled.');
        }

        $shiftStartTime = $request->shift === 'Morning Shift' ? '07:00' : '16:00';
        $cancelDeadline = \Carbon\Carbon::parse($request->scheduled_date->format('Y-m-d') . ' ' . $shiftStartTime)->subHour();

        if (now()->greaterThanOrEqualTo($cancelDeadline)) {
            return redirect()->back()->with('error', 'You can cancel only at least 1 hour before the scheduled shift.');
        }

        $request->update(['status' => 'Cancelled']);

        // Notify Admins
        $admins = User::where('role', 'admin')->get();
        Notification::send($admins, new CollectionStatusNotification($request, 'A collection request has been cancelled by the user.', 'cancelled'));

        // Notify Collector if assigned
        if ($request->collector_id) {
            $collector = User::find($request->collector_id);
            $collector->notify(new CollectionStatusNotification($request, 'An assigned collection request has been cancelled.', 'cancelled'));
        }

        return redirect()->back()->with('success', 'Request cancelled successfully.');
    }

    public function receipt(CollectionRequest $request)
    {
        if ($request->user_id !== Auth::id()) {
            abort(403);
        }

        if ($request->payment_status !== 'Paid') {
            return redirect()->back()->with('error', 'Receipt is only available for paid requests.');
        }

        return view('user.receipt', compact('request'));
    }

    private function hasActiveSubscription($user): bool
    {
        if (! $user || $user->monthly_payment_status !== 'Paid' || ! $user->subscription_valid_until) {
            return false;
        }

        return \Carbon\Carbon::parse($user->subscription_valid_until)->endOfDay()->isFuture();
    }
}
