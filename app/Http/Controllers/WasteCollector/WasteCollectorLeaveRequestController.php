<?php

namespace App\Http\Controllers\WasteCollector;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LeaveRequest;
use Illuminate\Support\Facades\Auth;

class WasteCollectorLeaveRequestController extends Controller
{
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
