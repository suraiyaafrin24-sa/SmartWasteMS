@extends('layouts.user')

@section('title', 'My Dashboard')

@section('content')
    @if(session('success'))
        <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-600 px-4 py-3 rounded-xl flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span class="font-semibold text-sm">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <div class="mb-8 mt-2 flex justify-between items-end">
        <div>
            <h1 class="text-[28px] font-black tracking-tight text-gray-900 mb-1">My Dashboard</h1>
            <p class="text-sm font-medium text-gray-500">Track your waste collection requests and history.</p>
        </div>
        <div>
            <a href="{{ route('user.requests.create') }}" class="bg-[#19817E] text-white text-sm font-semibold py-2.5 px-6 rounded-xl hover:bg-teal-700 transition shadow-sm flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                New Collection Request
            </a>
        </div>
    </div>

    <!-- User Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm flex flex-col justify-between h-40">
            <div>
                <div class="text-blue-500 font-bold text-sm mb-1.5 flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    Total Requests
                </div>
                <div class="text-[32px] font-black text-gray-800 leading-none tracking-tight">{{ $totalRequests }}</div>
            </div>
            <p class="text-[13px] text-gray-500 font-medium">Since joining SmartBin</p>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm flex flex-col justify-between h-40">
            <div>
                <div class="text-amber-500 font-bold text-sm mb-1.5 flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Pending Pickup
                </div>
                <div class="text-[32px] font-black text-gray-800 leading-none tracking-tight">{{ $pendingPickups }}</div>
            </div>
            <p class="text-[13px] text-gray-500 font-medium tracking-tight">Assigned for tomorrow 10 AM</p>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm flex flex-col justify-between h-40">
            <div>
                <div class="text-emerald-500 font-bold text-sm mb-1.5 flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Completed
                </div>
                <div class="text-[32px] font-black text-gray-800 leading-none tracking-tight">{{ $completedRequestsCount }}</div>
            </div>
            <p class="text-[13px] text-gray-500 font-medium">Eco-contribution: {{ $ecoContribution ?? 0 }}kg saved</p>
        </div>
    </div>

    <!-- Latest Requests Table -->
    <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-900 tracking-tight">Recent Requests</h2>
            <a href="{{ route('user.history') }}" class="text-teal-600 text-sm font-bold hover:underline">View All History</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead>
                    <tr class="text-gray-400 font-bold border-b border-gray-100 uppercase text-[11px] tracking-wider">
                        <th class="pb-3 px-2">Type</th>
                        <th class="pb-3 px-2">Scheduled Date</th>
                        <th class="pb-3 px-2">Status</th>
                        <th class="pb-3 px-2">Collector</th>
                        <th class="pb-3 px-2 text-right">Details</th>
                    </tr>
                </thead>
                <tbody class="text-gray-800 font-semibold divide-y divide-gray-50">
                    @forelse($recentRequests as $request)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="py-4 px-2">{{ $request->type }}</td>
                        <td class="py-4 px-2">{{ $request->scheduled_date->format('M d, Y') }}</td>
                        <td class="py-4 px-2">
                            @if($request->status == 'Completed')
                                <span class="px-2.5 py-1 rounded-md text-[11px] font-black bg-emerald-100 text-emerald-600 tracking-wide uppercase">Completed</span>
                            @elseif($request->status == 'Scheduled')
                                <span class="px-2.5 py-1 rounded-md text-[11px] font-black bg-amber-100 text-amber-600 tracking-wide uppercase">Scheduled</span>
                            @else
                                <span class="px-2.5 py-1 rounded-md text-[11px] font-black bg-gray-100 text-gray-600 tracking-wide uppercase">{{ $request->status }}</span>
                            @endif
                        </td>
                        <td class="py-4 px-2 text-gray-500">{{ $request->collector ? $request->collector->name : 'Unassigned' }}</td>
                        <td class="py-4 px-2 text-right">
                            <button class="text-teal-600 hover:text-teal-700">View</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-4 px-2 text-center text-gray-500">No recent requests found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
