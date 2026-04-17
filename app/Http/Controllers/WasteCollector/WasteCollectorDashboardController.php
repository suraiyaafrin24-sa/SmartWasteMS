<?php

namespace App\Http\Controllers\WasteCollector;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\CollectionRequest;
use Illuminate\Support\Facades\Auth;

class WasteCollectorDashboardController extends Controller
{
    public function index()
    {
        $collectorId = Auth::id();
        
        // Assigned Today
        $assignedToday = CollectionRequest::where('collector_id', $collectorId)
            ->where('scheduled_date', date('Y-m-d'))
            ->get();
            
        $completedCount = CollectionRequest::where('collector_id', $collectorId)
            ->where('status', 'Completed')
            ->count();
            
        // Current active route (Assigned to this collector and Scheduled)
        $activeRoute = CollectionRequest::where('collector_id', $collectorId)
            ->where('status', 'Scheduled')
            ->with('user')
            ->orderBy('scheduled_date', 'asc')
            ->get();

        return view('waste_collector.dashboard', compact(
            'assignedToday',
            'completedCount',
            'activeRoute'
        ));
    }

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

        $request->update([
            'status' => 'Completed',
            'weight' => rand(50, 500) / 10 // Dynamic mock weight logic
        ]);

        return redirect()->back()->with('success', 'Pickup for ' . $request->user->name . ' has been recorded as Completed!');
    }

    public function history()
    {
        $collectorId = Auth::id();
        $completedRequests = CollectionRequest::where('collector_id', $collectorId)
            ->where('status', 'Completed')
            ->with('user')
            ->orderBy('updated_at', 'desc')
            ->paginate(15);

        return view('waste_collector.history', compact('completedRequests'));
    }

    public function liveRoute()
    {
        $collectorId = Auth::id();
        $activeRoute = CollectionRequest::where('collector_id', $collectorId)
            ->where('status', 'Scheduled')
            ->with('user')
            ->orderBy('scheduled_date', 'asc')
            ->get();

        return view('waste_collector.live_route', compact('activeRoute'));
    }

    public function map()
    {
        $collectorId = Auth::id();
        $activeRoute = CollectionRequest::where('collector_id', $collectorId)
            ->where('status', 'Scheduled')
            ->with('user')
            ->get();

        return view('waste_collector.map', compact('activeRoute'));
    }

    public function dutySchedule()
    {
        $collectorId = Auth::id();
        $schedule = CollectionRequest::where('collector_id', $collectorId)
            ->where('status', 'Scheduled')
            ->with('user')
            ->orderBy('scheduled_date', 'asc')
            ->get();
            
        return view('waste_collector.duty_schedule', compact('schedule'));
    }
}
