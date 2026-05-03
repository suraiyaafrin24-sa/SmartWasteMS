<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\CollectionRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $search = request('search');

        // Base queries for counts
        $pendingQuery = CollectionRequest::where('status', 'Pending');
        $staffQuery = User::where('role', 'waste_collector');
        $userQuery = User::where('role', 'user');
        $todayQuery = CollectionRequest::whereDate('scheduled_date', date('Y-m-d'));
        $todayCompletedQuery = CollectionRequest::whereDate('scheduled_date', date('Y-m-d'))->where('status', 'Completed');
        
        // Base query for the list
        $requestQuery = CollectionRequest::with(['user', 'collector']);

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

        $todayRequestsCount = $todayQuery->count();
        $todayCompletedCount = $todayCompletedQuery->count();
        $clearedTodayPercent = $todayRequestsCount > 0 ? round(($todayCompletedCount / $todayRequestsCount) * 100) : 0;

        $latestRequests = $requestQuery->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
            
        $unassignedRequests = CollectionRequest::where('status', 'Pending')->with('user')->get();
        $collectors = User::where('role', 'waste_collector')
            ->with(['dutyRosters' => function($q) {
                $q->whereDate('date', today());
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
            'latestRequests',
            'activeStaff',
            'newUsersThisMonth',
            'onDutyCount',
            'unassignedRequests',
            'collectors'
        ));
    }

    public function createUser()
    {
        return view('admin.create_user');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => ['required', 'in:admin,user,waste_collector'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.users')->with('status', 'User created successfully');
    }

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
            }])
            ->get();
        return view('admin.duty_roster', compact('rosters', 'collectors'));
    }

    public function users(Request $request)
    {
        $query = User::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('role', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15);
        $totalCollectors = User::where('role', 'waste_collector')->count();
        $totalAdmins = User::where('role', 'admin')->count();
        $totalGeneralUsers = User::where('role', 'user')->count();
        
        return view('admin.users', compact('users', 'totalCollectors', 'totalAdmins', 'totalGeneralUsers'));
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.edit_user', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
            'role' => ['required', 'in:admin,user,waste_collector'],
        ]);

        $user->update($request->only(['name', 'email', 'phone', 'address', 'role']));

        return redirect()->route('admin.users')->with('status', 'User updated successfully');
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'You cannot delete yourself.');
        }

        $user->delete();

        return redirect()->route('admin.users')->with('status', 'User deleted successfully');
    }

    public function complaints()
    {
        $reports = \App\Models\Report::with('user')->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.reports.index', compact('reports'));
    }

    public function resolveComplaint($id)
    {
        $report = \App\Models\Report::findOrFail($id);
        $report->update(['status' => 'Resolved']);
        return redirect()->back()->with('success', 'Compliant marked as resolved successfully.');
    }

    public function reports()
    {
        $collectors = User::where('role', 'waste_collector')->get();
        $generalUsers = User::where('role', 'user')->get();
        return view('admin.reports.generate', compact('collectors', 'generalUsers'));
    }

    public function generateReport(Request $request)
    {
        $request->validate([
            'report_type' => 'required|in:collector,user',
            'user_id' => 'required',
            'start_date' => 'required|date',
            'days' => 'required|integer|min:1|max:365',
        ]);

        $actor = User::findOrFail($request->user_id);
        $startDate = \Carbon\Carbon::parse($request->start_date);
        $endDate = $startDate->copy()->addDays((int) $request->days);

        $query = CollectionRequest::whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()]);

        if ($request->report_type == 'collector') {
            $query->where('collector_id', $request->user_id);
            $activity = $query->with('user')->get();
        } else {
            $query->where('user_id', $request->user_id);
            $activity = $query->with('collector')->get();
        }

        return view('admin.reports.result', [
            'actor' => $actor,
            'activity' => $activity,
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
            'type' => $request->report_type,
            'days' => $request->days
        ]);
    }

    public function collections(Request $request)
    {
        $query = CollectionRequest::with(['user', 'collector']);

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

        $collections = $query->orderBy('created_at', 'desc')->paginate(15);
        $collectors = User::where('role', 'waste_collector')
            ->with(['dutyRosters' => function($q) {
                $q->whereDate('date', today());
            }])
            ->get();
        return view('admin.collections', compact('collections', 'collectors'));
    }

    public function showAssignDuty(Request $request)
    {
        $unassignedRequests = CollectionRequest::where('status', 'Pending')->with('user')->get();
        $collectors = User::where('role', 'waste_collector')
            ->with(['dutyRosters' => function($q) {
                $q->whereDate('date', today());
            }])
            ->get();
        
        // Map collectors to sectors based on active (Scheduled) tasks today
        $activeDeployments = CollectionRequest::where('status', 'Scheduled')
            ->whereDate('scheduled_date', today())
            ->with('collector')
            ->get()
            ->groupBy('sector');

        $selectedRequestId = $request->query('request_id');

        return view('admin.assign_duty', compact('unassignedRequests', 'collectors', 'activeDeployments', 'selectedRequestId'));
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

    public function settings()
    {
        return view('admin.settings');
    }

    public function profile()
    {
        return view('admin.profile');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'first_name' => 'required|string|max:120',
            'last_name' => 'nullable|string|max:120',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'profile_image' => 'nullable|file|mimes:jpeg,png,jpg,gif,avif,webp|max:5120',
        ]);

        $data = $request->only(['phone', 'address']);
        $data['name'] = trim($request->first_name . ' ' . $request->last_name);

        if ($request->hasFile('profile_image')) {
            Log::info('Admin profile image found in request', ['filename' => $request->file('profile_image')->getClientOriginalName()]);
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }
            
            $path = $request->file('profile_image')->store('profile_images', 'public');
            Log::info('Admin profile image stored', ['path' => $path]);
            $data['profile_image'] = $path;
        } else {
            Log::info('No admin profile image in request');
        }

        $user->fill($data);
        $user->save();

        return redirect()->back()->with('success', 'Admin profile updated successfully!');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Admin password updated successfully!');
    }

    public function deleteAvatar()
    {
        $user = Auth::user();
        if ($user->profile_image) {
            Storage::disk('public')->delete($user->profile_image);
            $user->update(['profile_image' => null]);
            return redirect()->back()->with('success', 'Avatar deleted successfully!');
        }
        return redirect()->back()->with('error', 'No avatar to delete.');
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

        $collectionRequest = CollectionRequest::findOrFail($id);
        
        $collectionRequest->update([
            'collector_id' => $request->collector_id,
            'scheduled_date' => $request->scheduled_date,
            'scheduled_time' => $request->scheduled_time,
            'zone' => $request->zone,
            'sector' => $request->sector,
            'road' => $request->road,
            'shift' => $request->shift,
            'status' => 'Scheduled'
        ]);

        return redirect()->back()->with('success', 'Task successfully assigned to Sector schedule.');
    }

    public function storeDutyRoster(Request $request)
    {
        $request->validate([
            'collector_id' => 'required|exists:users,id',
            'sector' => 'required|string',
            'road' => 'nullable|string',
            'date' => 'required|date|after_or_equal:today',
            'shift' => 'required|string|in:Morning Shift,Evening Shift',
        ]);

        \App\Models\DutyRoster::create($request->all());

        return redirect()->back()->with('success', 'Duty roster updated successfully!');
    }

    public function deleteDutyRoster($id)
    {
        $roster = \App\Models\DutyRoster::findOrFail($id);
        $roster->delete();
        return redirect()->back()->with('success', 'Roster entry removed successfully.');
    }
}
