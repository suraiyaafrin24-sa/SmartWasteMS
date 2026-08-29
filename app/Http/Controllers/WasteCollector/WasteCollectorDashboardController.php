<?php

namespace App\Http\Controllers\WasteCollector;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\CollectionRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class WasteCollectorDashboardController extends Controller
{
    public function index()
    {
        $collectorId = Auth::id();
        
        // Assigned Today
        $assignedToday = CollectionRequest::where('collector_id', $collectorId)
            ->where('scheduled_date', date('Y-m-d'))
            ->where('payment_status', 'Paid')
            ->get();
            
        $completedCount = CollectionRequest::where('collector_id', $collectorId)
            ->whereIn('status', ['Completed', 'Collected'])
            ->where('payment_status', 'Paid')
            ->count();
            
        // Current active route (Assigned to this collector and Scheduled)
        $activeRoute = CollectionRequest::where('collector_id', $collectorId)
            ->where('status', 'Scheduled')
            ->where('payment_status', 'Paid')
            ->with('user')
            ->orderBy('scheduled_date', 'asc')
            ->get();

        // Get all sectors this collector is ever assigned to in the duty roster
        $mySectors = \App\Models\DutyRoster::where('collector_id', $collectorId)
            ->distinct()
            ->pluck('sector');

        // Available requests for anyone to pick up - filtered by collector's assigned sectors
        $availableRequests = CollectionRequest::where('status', 'Pending')
            ->whereNull('collector_id')
            ->whereIn('sector', $mySectors)
            ->where('payment_status', 'Paid')
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        // Today's specific duty assignment from admin
        $todaysSchedules = \App\Models\DutyRoster::where('collector_id', $collectorId)
            ->where('date', date('Y-m-d'))
            ->get();

        return view('waste_collector.dashboard', compact(
            'assignedToday',
            'completedCount',
            'activeRoute',
            'availableRequests',
            'todaysSchedules'
        ));
    }
}
