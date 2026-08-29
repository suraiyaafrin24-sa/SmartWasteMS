<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LeaveRequest;
use App\Models\User;

class AdminDutyRosterController extends Controller
{
    public function showDutyRoster(Request $request)
    {
        $query = \App\Models\DutyRoster::with('collector');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('sector', 'LIKE', "%{$search}%")
                  ->orWhere('shift', 'LIKE', "%{$search}%")
                  ->orWhereHas('collector', function($qu) use ($search) {
                      $qu->where('name', 'LIKE', "%{$search}%");
                  });

                // Try to search by date
                try {
                    // Check if it's a date-like string
                    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $search) || strtotime($search)) {
                        $carbonDate = \Carbon\Carbon::parse($search);
                        
                        // If it's just a year (e.g., "2024")
                        if (preg_match('/^\d{4}$/', $search)) {
                            $q->orWhereYear('date', $search);
                        } 
                        // If it's a month and year (e.g., "May 2024" or "2024-05")
                        else if (preg_match('/^[a-zA-Z]+\s\d{4}$/', $search) || preg_match('/^\d{4}-\d{2}$/', $search)) {
                            $q->orWhereMonth('date', $carbonDate->month)
                              ->orWhereYear('date', $carbonDate->year);
                        }
                        // Default to full date search
                        else {
                            $q->orWhereDate('date', $carbonDate->format('Y-m-d'));
                        }
                    }
                } catch (\Exception $e) {}
            });
        }

        $rosters = $query->orderBy('date', 'desc')
            ->orderBy('shift', 'asc')
            ->paginate(15);
            
        $collectors = User::where('role', 'waste_collector')
            ->with(['dutyRosters' => function($q) {
                $q->whereDate('date', today());
            }, 'leaveRequests' => function($q) {
                $q->where('status', 'Approved')
                  ->whereDate('end_date', '>=', today());
            }])
            ->get();

        $prefill = [
            'sector' => $request->query('sector'),
            'date' => $request->query('date'),
            'shift' => $request->query('shift'),
        ];

        return view('admin.duty_roster', compact('rosters', 'collectors', 'prefill'));
    }

    public function storeDutyRoster(Request $request)
    {
        $request->validate([
            'collector_id' => 'required|exists:users,id',
            'sector' => 'required|string',
            'road' => 'nullable|string',
            'date' => 'required|date|after_or_equal:today',
            'end_date' => 'nullable|date|after_or_equal:date',
            'shift' => 'required|string|in:Morning Shift,Evening Shift',
        ]);

        $startDate = \Carbon\Carbon::parse($request->date);
        $endDate = $request->end_date ? \Carbon\Carbon::parse($request->end_date) : $startDate;

        // Limit range to 7 days
        if ($startDate->diffInDays($endDate) > 7) {
            return redirect()->back()->withInput()->with('error', 'You can only assign staff for up to 7 days at a time.');
        }

        if (LeaveRequest::where('collector_id', $request->collector_id)
            ->where('status', 'Approved')
            ->whereDate('start_date', '<=', $endDate->format('Y-m-d'))
            ->whereDate('end_date', '>=', $startDate->format('Y-m-d'))
            ->exists()) {
            return redirect()->back()->withInput()->with('error', 'This collector is on approved leave in the selected date range.');
        }

        $currentTime = date('H:i');
        $todayStr = date('Y-m-d');
        
        $createdCount = 0;
        $tempDate = $startDate->copy();

        while ($tempDate->lte($endDate)) {
            $currentLoopDate = $tempDate->format('Y-m-d');

            // Check if THIS collector is already assigned to this shift on this date
            $exists = \App\Models\DutyRoster::where('date', $currentLoopDate)
                ->where('shift', $request->shift)
                ->where('collector_id', $request->collector_id)
                ->exists();

            if (!$exists) {
                \App\Models\DutyRoster::create([
                    'collector_id' => $request->collector_id,
                    'sector' => $request->sector,
                    'road' => $request->road,
                    'date' => $currentLoopDate,
                    'shift' => $request->shift,
                    'status' => 'Active'
                ]);
                $createdCount++;
            }

            $tempDate->addDay();
        }

        if ($createdCount == 0) {
            return redirect()->back()->with('error', 'No new assignments were created (already assigned or shifts passed).');
        }

        return redirect()->back()->with('success', "Duty staff updated successfully ($createdCount assignments created).");
    }

    public function deleteDutyRoster($id)
    {
        $roster = \App\Models\DutyRoster::findOrFail($id);
        $roster->delete();
        return redirect()->back()->with('success', 'Staff entry removed successfully.');
    }

    public function dutySchedule()
    {
        $collectors = User::where('role', 'waste_collector')
            ->withCount(['collectionRequests as active_tasks' => function($query) {
                $query->where('status', 'Scheduled');
            }])
            ->withCount('collectionRequests')
            ->with(['collectionRequests' => function($query) {
                $query->orderBy('updated_at', 'desc')->take(3)->with('user');
            }])
            ->get();
        return view('admin.duty_schedule', compact('collectors'));
    }
}
