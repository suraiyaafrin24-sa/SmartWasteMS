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

class UserDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $totalRequests = CollectionRequest::where('user_id', $user->id)->count();
        $pendingPickups = CollectionRequest::where('user_id', $user->id)
            ->whereIn('status', ['Pending', 'Scheduled'])
            ->count();
            
        $completedRequestsCount = CollectionRequest::where('user_id', $user->id)
            ->where('status', 'Completed')
            ->count();
            
        $ecoContribution = CollectionRequest::where('user_id', $user->id)
            ->where('status', 'Completed')
            ->sum('weight');

        $recentRequests = CollectionRequest::where('user_id', $user->id)
            ->with('collector')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('user.dashboard', compact(
            'totalRequests', 
            'pendingPickups', 
            'completedRequestsCount', 
            'ecoContribution', 
            'recentRequests'
        ));
    }

    public function createRequest()
    {
        return view('user.create_request');
    }

    public function storeRequest(Request $request)
    {
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

        // Find assigned collector for this sector, date and shift
        $roster = DutyRoster::where('sector', $request->sector)
            ->where('date', $request->scheduled_date)
            ->where('shift', $request->shift)
            ->first();

        $collectorId = $roster ? $roster->collector_id : null;
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
            'status' => $status
        ]);

        // Notify Admins
        $admins = User::where('role', 'admin')->get();
        Notification::send($admins, new CollectionStatusNotification($collectionRequest, 'New waste collection request created.', 'new_request'));

        // Notify the assigned collector if exists
        if ($collectorId) {
            $collector = User::find($collectorId);
            $collector->notify(new CollectionStatusNotification($collectionRequest, 'A new collection request has been assigned to you in ' . $request->sector, 'new_request'));
        }

        return redirect()->route('user.dashboard')->with('success', 'Collection request created successfully!');
    }

    public function getCollector(Request $request)
    {
        $request->validate([
            'sector' => 'required|string',
            'date' => 'required|date',
        ]);

        $rosters = DutyRoster::where('sector', $request->sector)
            ->where('date', $request->date)
            ->with('collector')
            ->get();

        if ($rosters->count() > 0) {
            return response()->json([
                'success' => true,
                'rosters' => $rosters->map(function($r) {
                    return [
                        'shift' => $r->shift,
                        'collector' => [
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

        if ($request->status !== 'Pending' && $request->status !== 'Scheduled') {
            return redirect()->back()->with('error', 'Only pending or scheduled requests can be cancelled.');
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

    public function dutySchedule(Request $request)
    {
        $query = \App\Models\DutyRoster::with('collector');

        if ($request->has('sector') && $request->sector != '') {
            $query->where('sector', $request->sector);
        }

        $dutyRosters = $query->orderBy('date', 'desc')->paginate(15);
        $sectors = \App\Models\DutyRoster::select('sector')->distinct()->pluck('sector');

        return view('user.duty_schedule', compact('dutyRosters', 'sectors'));
    }
}
