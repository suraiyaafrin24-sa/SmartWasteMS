<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CollectionRequest;
use App\Models\LeaveRequest;
use App\Models\User;

class AdminCollectionController extends Controller
{
    public function collections(Request $request)
    {
        $query = CollectionRequest::with(['user', 'collector'])->where('payment_status', 'Paid');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('type', 'LIKE', "%{$search}%")
                  ->orWhere('status', 'LIKE', "%{$search}%")
                  ->orWhere('sector', 'LIKE', "%{$search}%")
                  ->orWhere('address', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function($qu) use ($search) {
                      $qu->where('name', 'LIKE', "%{$search}%");
                  })
                  ->orWhereHas('collector', function($qc) use ($search) {
                      $qc->where('name', 'LIKE', "%{$search}%");
                  });

                // Try to search by date
                try {
                    // Check if it's a date-like string
                    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $search) || strtotime($search)) {
                        $carbonDate = \Carbon\Carbon::parse($search);
                        
                        // If it's just a year (e.g., "2024")
                        if (preg_match('/^\d{4}$/', $search)) {
                            $q->orWhereYear('created_at', $search);
                        } 
                        // If it's a month and year (e.g., "May 2024" or "2024-05")
                        else if (preg_match('/^[a-zA-Z]+\s\d{4}$/', $search) || preg_match('/^\d{4}-\d{2}$/', $search)) {
                            $q->orWhereMonth('created_at', $carbonDate->month)
                              ->orWhereYear('created_at', $carbonDate->year);
                        }
                        // Default to full date search
                        else {
                            $q->orWhereDate('created_at', $carbonDate->format('Y-m-d'))
                              ->orWhereDate('scheduled_date', $carbonDate->format('Y-m-d'));
                        }
                    }
                } catch (\Exception $e) {}
            });
        }
        
        if ($request->has('status') && $request->status != '') {
            if ($request->status == 'Pending') {
                $query->whereIn('status', ['Pending', 'Scheduled']);
            } elseif ($request->status == 'Completed') {
                $query->whereIn('status', ['Completed', 'Collected']);
            } elseif ($request->status == 'Today') {
                $query->whereDate('created_at', today());
            }
        }

        $collections = $query->orderBy('created_at', 'desc')->paginate(15);
        $collectors = User::where('role', 'waste_collector')
            ->with(['dutyRosters' => function($q) {
                $q->whereDate('date', today());
            }, 'leaveRequests' => function($q) {
                $q->where('status', 'Approved')
                    ->whereDate('end_date', '>=', today());
            }])
            ->get();
        return view('admin.collections', compact('collections', 'collectors'));
    }

    public function assignTask(Request $request, $id)
    {
        $request->validate([
            'collector_id' => 'required|exists:users,id',
            'scheduled_date' => 'required|date|after_or_equal:today',
            'zone' => 'nullable|string|max:100',
            'sector' => 'nullable|string|max:50',
            'road' => 'nullable|string|max:100',
            'shift' => 'nullable|string|max:20',
            'scheduled_time' => 'nullable|string',
        ]);

        if ($this->collectorHasApprovedLeave($request->collector_id, $request->scheduled_date)) {
            return redirect()->back()->withInput()->with('error', 'This collector is on approved leave for the selected date.');
        }

        $collectionRequest = CollectionRequest::findOrFail($id);
        
        $collectionRequest->update([
            'collector_id' => $request->collector_id,
            'scheduled_date' => $request->scheduled_date,
            'scheduled_time' => $request->scheduled_time ?? $collectionRequest->scheduled_time,
            'zone' => $request->zone ?? $collectionRequest->zone,
            'sector' => $request->sector ?? $collectionRequest->sector,
            'road' => $request->road ?? $collectionRequest->road,
            'shift' => $request->shift ?? $collectionRequest->shift,
            'status' => 'Scheduled'
        ]);

        return redirect()->back()->with('success', 'Task successfully assigned to Sector schedule.');
    }

    private function collectorHasApprovedLeave($collectorId, $date): bool
    {
        return LeaveRequest::where('collector_id', $collectorId)
            ->where('status', 'Approved')
            ->whereDate('start_date', '<=', $date)
            ->whereDate('end_date', '>=', $date)
            ->exists();
    }
}
