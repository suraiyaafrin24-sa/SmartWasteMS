<?php

namespace App\Http\Controllers\WasteCollector;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\CollectionRequest;
use App\Models\LeaveRequest;
use App\Models\User;
use App\Notifications\CollectionStatusNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

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



    public function dutySchedule()
    {
        $collectorId = Auth::id();
        $schedule = \App\Models\DutyRoster::where('collector_id', $collectorId)
            ->orderBy('date', 'desc')
            ->orderBy('shift', 'asc')
            ->get();
            
        return view('waste_collector.duty_schedule', compact('schedule'));
    }

    public function paymentCollection()
    {
        $collectorId = Auth::id();
        $payments = CollectionRequest::where('collector_id', $collectorId)
            ->where('payment_status', 'Paid')
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return view('waste_collector.payment_collection', compact('payments'));
    }

    public function leaveRequests()
    {
        $collectorId = Auth::id();
        $leaveRequests = LeaveRequest::where('collector_id', $collectorId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Get all occupied leave dates (Approved or Pending)
        $occupiedLeaves = LeaveRequest::where('collector_id', $collectorId)
            ->whereIn('status', ['Approved', 'Pending'])
            ->get();

        $occupiedDates = [];
        foreach ($occupiedLeaves as $leave) {
            $start = \Carbon\Carbon::parse($leave->start_date);
            $end = \Carbon\Carbon::parse($leave->end_date);
            
            while ($start->lte($end)) {
                $occupiedDates[] = $start->format('Y-m-d');
                $start->addDay();
            }
        }
        $occupiedDates = array_values(array_unique($occupiedDates));

        return view('waste_collector.leave_requests', compact('leaveRequests', 'occupiedDates'));
    }

    public function storeLeaveRequest(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'reason' => 'required|string|max:1000',
        ]);

        $startDate = $request->start_date;
        $endDate = $request->end_date ?? $request->start_date;

        // Check if there is an overlapping leave request that is Approved or Pending
        $overlap = LeaveRequest::where('collector_id', Auth::id())
            ->whereIn('status', ['Approved', 'Pending'])
            ->where('start_date', '<=', $endDate)
            ->where('end_date', '>=', $startDate)
            ->exists();

        if ($overlap) {
            return redirect()->back()->with('error', 'You have already requested or taken leave during these dates.')->withInput();
        }

        LeaveRequest::create([
            'collector_id' => Auth::id(),
            'start_date' => $request->start_date,
            'end_date' => $endDate,
            'reason' => $request->reason,
            'status' => 'Pending',
        ]);

        return redirect()->back()->with('success', 'Leave request sent to admin.');
    }
}
