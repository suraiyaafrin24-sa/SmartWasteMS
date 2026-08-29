<?php

namespace App\Http\Controllers\WasteCollector;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CollectionRequest;
use App\Models\User;
use App\Notifications\CollectionStatusNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class WasteCollectorRequestController extends Controller
{
    public function acceptRequest($id)
    {
        $request = CollectionRequest::findOrFail($id);
        
        // Prevent multiple collectors from taking the same job
        if ($request->collector_id !== null) {
            return redirect()->back()->with('error', 'Sorry, this job has already been taken by another collector.');
        }

        $request->update([
            'collector_id' => Auth::id(),
            'status' => 'Scheduled'
        ]);

        return redirect()->back()->with('success', 'Job for ' . $request->user->name . ' accepted successfully!');
    }

    public function completeRequest($id)
    {
        $request = CollectionRequest::findOrFail($id);
        
        // Security check: only the assigned collector can complete the task
        if ($request->collector_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Unauthorized. You are not assigned to this collection.');
        }

        // Time check: prevent collecting future requests
        if (strtotime($request->scheduled_date) > strtotime(date('Y-m-d'))) {
            return redirect()->back()->with('error', 'You cannot mark a future request as collected. Please wait until ' . \Carbon\Carbon::parse($request->scheduled_date)->format('M d, Y') . '.');
        }

        $request->update([
            'status' => 'Collected',
            'weight' => rand(50, 500) / 10 // Dynamic mock weight logic
        ]);

        // Notify User
        $request->user->notify(new CollectionStatusNotification($request, 'Your waste has been Collected!', 'collected'));

        // Notify Admins
        $admins = User::where('role', 'admin')->get();
        Notification::send($admins, new CollectionStatusNotification($request, 'Waste collection completed for ' . $request->user->name, 'collected'));

        return redirect()->back()->with('success', 'Pickup for ' . $request->user->name . ' has been recorded as Collected!');
    }

    public function history()
    {
        $collectorId = Auth::id();
        $completedRequests = CollectionRequest::where('collector_id', $collectorId)
            ->whereIn('status', ['Completed', 'Collected'])
            ->where('payment_status', 'Paid')
            ->with('user')
            ->orderBy('updated_at', 'desc')
            ->paginate(15);

        return view('waste_collector.history', compact('completedRequests'));
    }
}
