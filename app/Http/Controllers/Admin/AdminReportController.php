<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CollectionRequest;
use App\Models\User;
use Illuminate\Support\Str;

class AdminReportController extends Controller
{
    public function reports(Request $request)
    {
        $collectors = User::where('role', 'waste_collector')->get();
        $generalUsers = User::where('role', 'user')->get();
        $sectors = collect(range(1, 18))->map(fn ($sector) => 'Sector ' . $sector);

        if ($request->report_type === 'subscription_sector') {
            $query = User::whereNotNull('last_transaction_id');

            if ($request->filled('sector')) {
                $query->where('sector', $request->sector);
            }

            if ($request->filled('start_date') && $request->filled('end_date')) {
                $startDate = \Carbon\Carbon::parse($request->start_date)->startOfDay();
                $endDate = \Carbon\Carbon::parse($request->end_date)->endOfDay();
                $query->whereBetween('updated_at', [$startDate, $endDate]);
            }

            $collections = $query->orderBy('updated_at', 'desc')->paginate(15)->appends($request->query());
        } else {
            $query = CollectionRequest::with(['user', 'collector'])->where('payment_status', 'Paid')->orderBy('created_at', 'desc');

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
        }

        return view('admin.reports.generate', compact('collectors', 'generalUsers', 'collections', 'sectors'));
    }

    public function generateReport(Request $request)
    {
        $request->validate([
            'report_type' => 'required|in:collector,user,subscription_sector',
            'user_id' => 'required_unless:report_type,subscription_sector',
            'sector' => 'required_if:report_type,subscription_sector',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = \Carbon\Carbon::parse($request->start_date)->startOfDay();
        $endDate = \Carbon\Carbon::parse($request->end_date)->endOfDay();

        if ($request->report_type === 'subscription_sector') {
            $subscriptions = User::where('role', 'user')
                ->where('sector', $request->sector)
                ->whereNotNull('last_transaction_id')
                ->whereBetween('updated_at', [$startDate, $endDate])
                ->get();

            $revenueSummary = $subscriptions
                ->groupBy(fn ($user) => $user->updated_at->format('F Y'))
                ->map(function ($users, $month) {
                    return [
                        'month' => $month,
                        'subscriptions' => $users->count(),
                        'amount' => $users->sum(function ($user) {
                            if (preg_match('/M(\d+)T/', $user->last_transaction_id, $matches)) {
                                return match ((int) $matches[1]) {
                                    6 => 1800,
                                    12 => 3600,
                                    default => 300,
                                };
                            }

                            return 300;
                        }),
                    ];
                })
                ->values();

            return view('admin.reports.result', [
                'actor' => (object) [
                    'id' => null,
                    'name' => $request->sector,
                    'role' => 'sector',
                    'email' => 'Subscription revenue report',
                ],
                'activity' => $subscriptions,
                'revenueSummary' => $revenueSummary,
                'start_date' => $startDate->format('Y-m-d'),
                'end_date' => $endDate->format('Y-m-d'),
                'type' => $request->report_type,
                'sector' => $request->sector,
                'days' => null,
            ]);
        }

        $actor = User::findOrFail($request->user_id);
        $query = CollectionRequest::whereBetween('created_at', [$startDate, $endDate])->where('payment_status', 'Paid');

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
        $query = CollectionRequest::with(['user', 'collector'])->where('payment_status', 'Paid')->orderBy('created_at', 'desc');

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
        $actorId = $request->user_id;
        $reportType = $request->report_type ?? 'all';
        
        $startDate = $request->filled('start_date') ? \Carbon\Carbon::parse($request->start_date)->startOfDay() : today()->startOfMonth();
        $endDate = $request->filled('end_date') ? \Carbon\Carbon::parse($request->end_date)->endOfDay() : today()->endOfDay();

        if ($reportType === 'subscription_sector') {
            $sector = $request->sector;
            $subscriptions = User::where('role', 'user')
                ->where('sector', $sector)
                ->whereNotNull('last_transaction_id')
                ->whereBetween('updated_at', [$startDate, $endDate])
                ->get();

            $revenueSummary = $subscriptions
                ->groupBy(fn ($user) => $user->updated_at->format('F Y'))
                ->map(function ($users, $month) {
                    return [
                        'month' => $month,
                        'subscriptions' => $users->count(),
                        'amount' => $users->sum(function ($user) {
                            if (preg_match('/M(\d+)T/', $user->last_transaction_id, $matches)) {
                                return match ((int) $matches[1]) {
                                    6 => 1800,
                                    12 => 3600,
                                    default => 300,
                                };
                            }
                            return 300;
                        }),
                    ];
                })
                ->values();

            $actor = (object)[
                'name' => $sector,
                'role' => 'sector',
                'email' => 'Subscription revenue report'
            ];

            $data = [
                'actor' => $actor,
                'activity' => collect(), // dummy since pdf view expects it
                'revenueSummary' => $revenueSummary,
                'start_date' => $startDate->format('M d, Y'),
                'end_date' => $endDate->format('M d, Y'),
                'type' => $reportType,
                'sector' => $sector,
                'days' => null
            ];
        } else {
            $query = CollectionRequest::whereBetween('created_at', [$startDate, $endDate])->where('payment_status', 'Paid');

            if ($actorId) {
                $actor = User::findOrFail($actorId);
                if ($reportType == 'collector') {
                    $query->where('collector_id', $actorId);
                } else {
                    $query->where('user_id', $actorId);
                }
            } else {
                // Placeholder actor for system-wide report
                $actor = (object)[
                    'name' => 'System Wide',
                    'role' => 'administrator',
                    'email' => 'admin@smartbin.com'
                ];
            }

            $activity = $query->with(['user', 'collector'])->orderBy('created_at', 'desc')->get();

            $data = [
                'actor' => $actor,
                'activity' => $activity,
                'start_date' => $startDate->format('M d, Y'),
                'end_date' => $endDate->format('M d, Y'),
                'type' => $reportType,
                'days' => null
            ];
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.reports.pdf', $data);
        $filename = 'activity_report_' . Str::slug($actor->name) . '_' . date('Y-m-d') . '.pdf';
        return $pdf->download($filename);
    }
}
