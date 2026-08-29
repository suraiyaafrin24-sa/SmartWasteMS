<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LeaveRequest;
use Illuminate\Support\Facades\Auth;

class AdminLeaveRequestController extends Controller
{
    public function leaveRequests()
    {
        $leaveRequests = LeaveRequest::with('collector')
            ->orderByRaw("CASE WHEN status = 'Pending' THEN 0 ELSE 1 END")
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.leave_requests', compact('leaveRequests'));
    }

    public function approveLeaveRequest(LeaveRequest $leaveRequest)
    {
        $leaveRequest->update([
            'status' => 'Approved',
            'approved_at' => now(),
            'approved_by' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Leave request approved.');
    }

    public function rejectLeaveRequest(LeaveRequest $leaveRequest)
    {
        $leaveRequest->update([
            'status' => 'Rejected',
        ]);

        return redirect()->back()->with('success', 'Leave request rejected.');
    }
}
