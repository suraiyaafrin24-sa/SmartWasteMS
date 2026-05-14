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
        $pendingQuery = CollectionRequest::whereIn('status', ['Pending', 'Scheduled']);
        $staffQuery = User::where('role', 'waste_collector');
        $userQuery = User::where('role', 'user');
        $todayQuery = CollectionRequest::whereDate('scheduled_date', date('Y-m-d'));
        $todayCompletedQuery = CollectionRequest::whereDate('scheduled_date', date('Y-m-d'))->whereIn('status', ['Completed', 'Collected']);
        
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

        $requestsCreatedTodayQuery = CollectionRequest::whereDate('created_at', date('Y-m-d'));
        $requestsCreatedToday = (clone $requestsCreatedTodayQuery)->count();
        $todayCompletedCount = (clone $requestsCreatedTodayQuery)->whereIn('status', ['Completed', 'Collected'])->count();
        
        $clearedTodayPercent = $requestsCreatedToday > 0 ? round(($todayCompletedCount / $requestsCreatedToday) * 100) : 0;

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
            'sector' => ['nullable', 'string', 'max:50'],
            'road' => ['nullable', 'string', 'max:100'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => ['required', 'in:admin,user,waste_collector'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'sector' => $request->sector,
            'road' => $request->road,
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

        $prefill = [
            'sector' => $request->query('sector'),
            'date' => $request->query('date'),
            'shift' => $request->query('shift'),
        ];

        return view('admin.duty_roster', compact('rosters', 'collectors', 'prefill'));
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
            'sector' => ['nullable', 'string', 'max:50'],
            'road' => ['nullable', 'string', 'max:100'],
            'role' => ['required', 'in:admin,user,waste_collector'],
        ]);

        $user->update($request->only(['name', 'email', 'phone', 'address', 'sector', 'road', 'role']));

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

    public function reports(Request $request)
    {
        $collectors = User::where('role', 'waste_collector')->get();
        $generalUsers = User::where('role', 'user')->get();

        $query = CollectionRequest::with(['user', 'collector'])->orderBy('created_at', 'desc');

        if ($request->filled('user_id') && $request->filled('report_type')) {
            if ($request->report_type == 'collector') {
                $query->where('collector_id', $request->user_id);
            } else {
                $query->where('user_id', $request->user_id);
            }
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = \Carbon\Carbon::parse($request->start_date)->startOfDay();
            $endDate = \Carbon\Carbon::parse($request->end_date)->endOfDay();
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $collections = $query->paginate(15)->appends($request->query());

        return view('admin.reports.generate', compact('collectors', 'generalUsers', 'collections'));
    }

    public function generateReport(Request $request)
    {
        $request->validate([
            'report_type' => 'required|in:collector,user',
            'user_id' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $actor = User::findOrFail($request->user_id);
        $startDate = \Carbon\Carbon::parse($request->start_date)->startOfDay();
        $endDate = \Carbon\Carbon::parse($request->end_date)->endOfDay();

        $query = CollectionRequest::whereBetween('created_at', [$startDate, $endDate]);

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
            'days' => null // No longer used
        ]);
    }

    public function downloadReport(Request $request)
    {
        $query = CollectionRequest::with(['user', 'collector'])->orderBy('created_at', 'desc');

        if ($request->filled('user_id') && $request->filled('report_type')) {
            if ($request->report_type == 'collector') {
                $query->where('collector_id', $request->user_id);
            } else {
                $query->where('user_id', $request->user_id);
            }
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = \Carbon\Carbon::parse($request->start_date)->startOfDay();
            $endDate = \Carbon\Carbon::parse($request->end_date)->endOfDay();
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $records = $query->get();
        $filename = 'collection_report_' . date('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($records) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Date', 'Time', 'User', 'Sector', 'Road', 'Waste Type', 'Shift', 'Assigned Collector', 'Scheduled Date', 'Status']);
            foreach ($records as $item) {
                fputcsv($handle, [
                    $item->created_at->format('M d, Y'),
                    $item->created_at->format('h:i A'),
                    $item->user->name ?? 'N/A',
                    $item->sector ?? 'N/A',
                    $item->road ?? 'N/A',
                    $item->type ?? 'N/A',
                    $item->shift ?? 'N/A',
                    $item->collector->name ?? 'Unassigned',
                    $item->scheduled_date ? \Carbon\Carbon::parse($item->scheduled_date)->format('M d, Y') : 'N/A',
                    $item->status ?? 'N/A',
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function downloadPdfReport(Request $request)
    {
        $actor = User::findOrFail($request->user_id);
        $startDate = \Carbon\Carbon::parse($request->start_date)->startOfDay();
        $endDate = \Carbon\Carbon::parse($request->end_date)->endOfDay();

        $query = CollectionRequest::whereBetween('created_at', [$startDate, $endDate]);

        if ($request->report_type == 'collector') {
            $query->where('collector_id', $request->user_id);
        } else {
            $query->where('user_id', $request->user_id);
        }

        $activity = $query->with(['user', 'collector'])->get();

        $data = [
            'actor' => $actor,
            'activity' => $activity,
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
            'type' => $request->report_type,
            'days' => null
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.reports.pdf', $data);
        return $pdf->download('activity_report_' . $actor->name . '_' . date('Y-m-d') . '.pdf');
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
            }])
            ->get();
        return view('admin.collections', compact('collections', 'collectors'));
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

        // Logic to prevent booking passed shifts for today
        $isToday = $request->scheduled_date == date('Y-m-d');
        $currentTime = date('H:i');
        
        if ($isToday && $request->shift) {
            if ($request->shift == 'Morning Shift' && $currentTime >= '10:00') {
                return redirect()->back()->withInput()->with('error', 'Morning shift is no longer available for today.');
            }
            if ($request->shift == 'Evening Shift' && $currentTime >= '18:00') {
                return redirect()->back()->withInput()->with('error', 'Evening shift is no longer available for today.');
            }
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

        $currentTime = date('H:i');
        $todayStr = date('Y-m-d');
        
        $createdCount = 0;
        $tempDate = $startDate->copy();

        while ($tempDate->lte($endDate)) {
            $currentLoopDate = $tempDate->format('Y-m-d');
            
            // Logic to prevent booking passed shifts for today
            if ($currentLoopDate == $todayStr) {
                if ($request->shift == 'Morning Shift' && $currentTime >= '10:00') {
                    $tempDate->addDay();
                    continue;
                }
                if ($request->shift == 'Evening Shift' && $currentTime >= '18:00') {
                    $tempDate->addDay();
                    continue;
                }
            }

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

        return redirect()->back()->with('success', "Duty roster updated successfully ($createdCount assignments created).");
    }

    public function deleteDutyRoster($id)
    {
        $roster = \App\Models\DutyRoster::findOrFail($id);
        $roster->delete();
        return redirect()->back()->with('success', 'Roster entry removed successfully.');
    }

    public function payments(Request $request)
    {
        $query = CollectionRequest::with(['user', 'collector']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('transaction_id', 'LIKE', "%{$search}%")
                    ->orWhere('payment_status', 'LIKE', "%{$search}%")
                    ->orWhereHas('user', function ($qu) use ($search) {
                        $qu->where('name', 'LIKE', "%{$search}%");
                    })
                    ->orWhereHas('collector', function ($qc) use ($search) {
                        $qc->where('name', 'LIKE', "%{$search}%");
                    });
            });
        }

        $payments = $query->orderBy('updated_at', 'desc')->paginate(15);
        return view('admin.payments', compact('payments'));
    }
}
