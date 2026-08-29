<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\CollectionRequest;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $search = request('search');

        // Base queries for counts
        $pendingQuery = CollectionRequest::whereIn('status', ['Pending', 'Scheduled'])->where('payment_status', 'Paid');
        $staffQuery = User::where('role', 'waste_collector');
        $userQuery = User::where('role', 'user');
        $todayQuery = CollectionRequest::whereDate('scheduled_date', date('Y-m-d'))->where('payment_status', 'Paid');
        $todayCompletedQuery = CollectionRequest::whereDate('scheduled_date', date('Y-m-d'))
            ->whereIn('status', ['Completed', 'Collected'])
            ->where('payment_status', 'Paid');
        
        // Base query for the list
        $requestQuery = CollectionRequest::with(['user', 'collector'])->where('payment_status', 'Paid');

        if ($search) {
            $filter = function($q) use ($search) {
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
            };

            $pendingQuery->where($filter);
            $todayQuery->where($filter);
            $todayCompletedQuery->where($filter);
            $requestQuery->where($filter);

            // For staff and users, search by name or email
            $staffQuery->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")->orWhere('email', 'LIKE', "%{$search}%");
            });
            $userQuery->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        $pendingCollections = $pendingQuery->count();
        $activeStaffCount = $staffQuery->count();
        $totalUsersCount = $userQuery->count();
        
        $newUsersThisMonth = User::where('role', 'user')
            ->whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))
            ->count();
            
        $onDutyCount = User::where('role', 'waste_collector')
            ->whereHas('collectionRequests', function($query) {
                $query->where('status', 'Scheduled');
            })->count();

        $requestsCreatedTodayQuery = CollectionRequest::whereDate('created_at', date('Y-m-d'))->where('payment_status', 'Paid');
        $requestsCreatedToday = (clone $requestsCreatedTodayQuery)->count();
        $todayCompletedCount = (clone $requestsCreatedTodayQuery)->whereIn('status', ['Completed', 'Collected'])->count();
        
        $clearedTodayPercent = $requestsCreatedToday > 0 ? round(($todayCompletedCount / $requestsCreatedToday) * 100) : 0;

        $latestRequests = $requestQuery->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
            
        $unassignedRequests = CollectionRequest::where('status', 'Pending')->where('payment_status', 'Paid')->with('user')->get();
        $collectors = User::where('role', 'waste_collector')
            ->with(['dutyRosters' => function($q) {
                $q->whereDate('date', today());
            }, 'leaveRequests' => function($q) {
                $q->where('status', 'Approved')
                    ->whereDate('end_date', '>=', today());
            }])
            ->get();

        $activeStaff = User::where('role', 'waste_collector')
            ->withCount(['collectionRequests' => function($query) {
                $query->where('status', 'Scheduled');
            }])
            ->get();

        return view('admin.dashboard', compact(
            'pendingCollections',
            'activeStaffCount',
            'totalUsersCount',
            'clearedTodayPercent',
            'todayCompletedCount',
            'requestsCreatedToday',
            'latestRequests',
            'activeStaff',
            'newUsersThisMonth',
            'onDutyCount',
            'unassignedRequests',
            'collectors'
        ));
    }
}
