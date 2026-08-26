<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Activity Report - {{ $actor->name }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #1f2937;
            line-height: 1.5;
            padding: 20px;
        }
        .header {
            border-bottom: 2px solid #111827;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            color: #1e3a8a;
            font-size: 24px;
            text-transform: uppercase;
        }
        .header p {
            margin: 5px 0 0;
            color: #9ca3af;
            font-size: 10px;
            font-weight: bold;
            letter-spacing: 1px;
        }
        .meta-info {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }
        .meta-box {
            display: table-cell;
            width: 50%;
            padding: 15px;
            background: #f9fafb;
            border: 1px solid #f3f4f6;
            border-radius: 10px;
        }
        .meta-box.dark {
            background: #111827;
            color: #ffffff;
        }
        .meta-box h3 {
            margin: 0 0 10px;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #6b7280;
        }
        .meta-box.dark h3 {
            color: #9ca3af;
        }
        .actor-name {
            font-size: 18px;
            font-weight: bold;
            margin: 0;
        }
        .actor-role {
            font-size: 10px;
            color: #0d9488;
            text-transform: uppercase;
            font-weight: bold;
        }
        .stat-value {
            font-size: 24px;
            font-weight: bold;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .table th {
            text-align: left;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #9ca3af;
            padding: 10px 5px;
            border-bottom: 1px solid #f3f4f6;
        }
        .table td {
            padding: 12px 5px;
            font-size: 11px;
            border-bottom: 1px solid #f9fafb;
        }
        .date { font-weight: bold; }
        .time { color: #9ca3af; font-size: 9px; }
        .status-badge {
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-completed { background: #ecfdf5; color: #059669; }
        .status-pending { background: #fff7ed; color: #ea580c; }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 9px;
            color: #9ca3af;
            border-top: 1px solid #f3f4f6;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <table style="width: 100%;">
            <tr>
                <td>
                    <h1>Uttara Waste Management</h1>
                    <p>Strategic Operations Intelligence</p>
                </td>
                <td style="text-align: right;">
                    <div style="font-size: 12px; font-weight: bold;">Official Activity Report</div>
                    <div style="font-size: 10px; color: #9ca3af;">Generated: {{ date('F d, Y') }}</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="meta-info">
        <div class="meta-box">
            <h3>Target Information</h3>
            <div class="actor-name">{{ $actor->name }}</div>
            <div class="actor-role">{{ $actor->role == 'waste_collector' ? 'Waste Collector' : 'Resident' }}</div>
            <div style="font-size: 9px; color: #6b7280; margin-top: 5px;">{{ $actor->email }}</div>
        </div>
        <div style="display: table-cell; width: 20px;"></div>
        <div class="meta-box dark">
            <h3>Statistical Overview</h3>
            <div class="stat-value">{{ $activity->count() }}</div>
            <div style="font-size: 9px; color: #9ca3af; text-transform: uppercase;">Total Engagements</div>
            <div style="margin-top: 10px; font-size: 9px; color: #9ca3af;">
                Range: {{ $start_date }} to {{ $end_date }}
            </div>
        </div>
    </div>

    @if($type == 'subscription_sector')
        <h3 style="font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: #4b5563; margin-bottom: 10px;">Monthly Subscription Revenue</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Month</th>
                    <th>Sector</th>
                    <th style="text-align: center;">Subscriptions</th>
                    <th style="text-align: right;">Earned Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($revenueSummary as $row)
                    <tr>
                        <td style="font-weight: bold; font-size: 11px;">{{ $row['month'] }}</td>
                        <td>
                            <span style="background: #ecfdf5; color: #059669; padding: 3px 8px; border-radius: 5px; font-size: 9px; font-weight: bold; text-transform: uppercase;">{{ $sector }}</span>
                        </td>
                        <td style="text-align: center; font-weight: bold;">{{ $row['subscriptions'] }}</td>
                        <td style="text-align: right; color: #059669; font-weight: bold;">BDT {{ number_format($row['amount'], 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <h3 style="font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: #4b5563; margin-bottom: 10px;">Detailed Activity Ledger</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Date/Time</th>
                    <th>Area / Road</th>
                    <th>Waste Type</th>
                    <th>{{ $type == 'collector' ? 'Requested By' : 'Collected By' }}</th>
                    <th style="text-align: right;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($activity as $item)
                    <tr>
                        <td>
                            <div class="date">{{ \Carbon\Carbon::parse($item->created_at)->format('M d, Y') }}</div>
                            <div class="time">{{ \Carbon\Carbon::parse($item->created_at)->format('h:i A') }}</div>
                        </td>
                        <td>
                            <div style="font-weight: bold; font-size: 10px;">{{ $item->sector }}</div>
                            <div style="color: #6b7280;">{{ $item->road }}</div>
                        </td>
                        <td>{{ $item->type }}</td>
                        <td>{{ $type == 'collector' ? ($item->user->name ?? 'N/A') : ($item->collector->name ?? 'N/A') }}</td>
                        <td style="text-align: right;">
                            <span class="status-badge {{ $item->status == 'Completed' ? 'status-completed' : ($item->status == 'Pending' ? 'status-pending' : '') }}">
                                {{ $item->status }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="footer">
        This is a computer-generated official document from the Uttara City Waste Management Portal.
    </div>
</body>
</html>
