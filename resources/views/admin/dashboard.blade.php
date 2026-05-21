@extends('layouts.admin')

@section('title', 'Admin Dashboard Overview')

@section('content')

    <!-- Dashboard Header -->
    <div class="mb-8 mt-2 flex justify-between items-end">
        <div>
            <h1 class="text-2xl font-black tracking-tight text-slate-800 mb-1">Overview Dashboard</h1>
            <p class="text-[13px] font-medium text-slate-400">Monitor collections, active staff, and system performance.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.reports') }}"
                class="bg-gray-50 text-slate-600 text-[13px] font-bold py-2.5 px-5 rounded-xl border border-gray-100 hover:bg-gray-100 transition shadow-sm">Filter
                Report</a>
            <a href="{{ route('admin.duty_roster') }}"
                class="bg-[#19817E] text-white text-[13px] font-bold py-2.5 px-5 rounded-xl hover:bg-teal-700 transition shadow-lg shadow-teal-700/20">+
                Staff Schedule</a>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-6 mb-8">
        <!-- Collection Status Card -->
        <div
            class="bg-white rounded-3xl p-6 border border-gray-50 flex flex-col justify-between h-44 relative overflow-hidden group">
            <div
                class="absolute right-4 top-1/2 -translate-y-1/2 opacity-[0.03] group-hover:scale-110 transition duration-500">
                <svg class="w-24 h-24 text-amber-500" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm.31 16.19V19.5h-1.5v-1.31c-1.3-.16-2.5-1-2.5-2.5h1.71c.05.97.77 1.64 2.35 1.64 1.22 0 2.1-.62 2.1-1.48 0-.72-.57-1.35-2.34-1.77-2-.47-3.65-1.16-3.65-3.09 0-1.5 1.2-2.34 2.5-2.5V7.5h1.5v1.31c1.84.19 3.1 1.46 3.15 3.42h-1.71c-.04-.98-.56-1.64-1.94-1.64-1.31 0-2.1.59-2.1 1.43 0 .73.57 1.22 2.34 1.67 2 .48 3.65 1.16 3.65 3.04 0 1.54-1.03 2.42-2.5 2.61z" />
                </svg>
            </div>
            <div>
                <div class="text-amber-500 font-bold text-[13px] mb-1.5 flex items-center gap-1.5 leading-none">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Pending Collections
                </div>
                <div class="text-4xl font-black text-slate-800 leading-none tracking-tight">{{ $pendingCollections }}</div>
            </div>
            <p class="text-[12px] text-slate-400 font-medium leading-snug">Active collections in system</p>
        </div>

        <!-- Today's Requests Card -->
        <div
            class="bg-white rounded-3xl p-6 border border-gray-50 flex flex-col justify-between h-44 relative overflow-hidden group">
            <div
                class="absolute right-4 top-1/2 -translate-y-1/2 opacity-[0.03] group-hover:scale-110 transition duration-500">
                <svg class="w-24 h-24 text-indigo-500" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z" />
                </svg>
            </div>
            <div>
                <div class="text-indigo-500 font-bold text-[13px] mb-1.5 flex items-center gap-1.5 leading-none">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Today's Requests
                </div>
                <div class="text-4xl font-black text-slate-800 leading-none tracking-tight">{{ $requestsCreatedToday }}</div>
            </div>
            <p class="text-[12px] text-slate-400 font-medium leading-snug">New requests submitted today</p>
        </div>

        <!-- Cleared Today Card -->
        <div
            class="bg-white rounded-3xl p-6 border border-gray-50 flex flex-col justify-between h-44 relative overflow-hidden group">
            <div
                class="absolute right-4 top-1/2 -translate-y-1/2 opacity-[0.03] group-hover:scale-110 transition duration-500">
                <svg class="w-24 h-24 text-emerald-500" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4L9 16.2z" />
                </svg>
            </div>
            <div>
                <div class="text-emerald-500 font-bold text-[13px] mb-1.5 flex items-center gap-1.5 leading-none">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Cleared Today
                </div>
                <div class="text-4xl font-black text-slate-800 leading-none tracking-tight">{{ $todayCompletedCount }}</div>
            </div>
            <p class="text-[12px] text-slate-400 font-medium leading-snug">Collections completed today</p>
        </div>

        <!-- Active Collectors Card -->
        <div
            class="bg-white rounded-3xl p-6 border border-gray-50 flex flex-col justify-between h-44 relative overflow-hidden group">
            <div
                class="absolute right-4 top-1/2 -translate-y-1/2 opacity-[0.03] group-hover:scale-110 transition duration-500">
                <svg class="w-24 h-24 text-[#19817E]" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z" />
                </svg>
            </div>
            <div>
                <div class="text-[#19817E] font-bold text-[13px] mb-1.5 flex items-center gap-1.5 leading-none">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                    Active Duty Staff
                </div>
                <div class="text-4xl font-black text-slate-800 leading-none tracking-tight">{{ $activeStaffCount }}</div>
            </div>
            <div class="flex items-center gap-1.5 mt-2">
                <p class="text-[12px] text-slate-400 font-medium leading-snug">registered staff</p>
            </div>
        </div>

        <!-- System Users Card -->
        <div
            class="bg-white rounded-3xl p-6 border border-gray-50 flex flex-col justify-between h-44 relative overflow-hidden group">
            <div
                class="absolute right-4 top-1/2 -translate-y-1/2 opacity-[0.03] group-hover:scale-110 transition duration-500">
                <svg class="w-24 h-24 text-blue-500" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                </svg>
            </div>
            <div>
                <div class="text-blue-500 font-bold text-[13px] mb-1.5 flex items-center gap-1.5 leading-none">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                        </path>
                    </svg>
                    Total General Users
                </div>
                <div class="text-4xl font-black text-slate-800 leading-none tracking-tight">
                    {{ number_format($totalUsersCount) }}
                </div>
            </div>
            <div class="flex items-center gap-1.5 mt-2">
                <p class="text-[12px] text-slate-400 font-medium leading-snug">resident accounts</p>
            </div>
        </div>
    </div>

    <!-- Main Grid Section -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">

        <!-- Live Collection Status Table -->
        <div class="xl:col-span-3 bg-white rounded-3xl p-8 border border-gray-50 shadow-sm shadow-gray-100">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-xl font-black text-slate-800 tracking-tight">Collection Request Status</h2>
                    <p class="text-[12px] text-slate-400 font-medium mt-1">Status of waste collections</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.collections') }}"
                        class="text-[#19817E] text-[12px] font-bold py-2 px-5 rounded-full border border-[#19817E]/20 hover:bg-[#19817E] hover:text-white transition-all duration-300 uppercase tracking-wider leading-none text-center flex items-center">Review
                        All</a>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead>
                        <tr class="text-slate-300 font-bold text-[11px] uppercase tracking-widest border-b border-gray-50">
                            <th class="pb-4 px-2">User</th>
                            <th class="pb-4 px-2">Requested Date</th>
                            <th class="pb-4 px-2">Sector</th>
                            <th class="pb-4 px-2">Waste Type</th>
                            <th class="pb-4 px-2">Shift</th>
                            <th class="pb-4 px-2">Assigned Collector</th>
                            <th class="pb-4 px-2">Status</th>
                        </tr>
                    </thead>
                    <tbody class="text-slate-700 font-bold divide-y divide-gray-50/50">
                        @forelse($latestRequests as $request)
                            <tr class="hover:bg-gray-50/30 transition-all duration-300">
                                <td class="py-5 px-2">
                                    <div class="font-black text-slate-800">{{ $request->user->name }}</div>
                                </td>
                                <td class="py-5 px-2">
                                    <div class="text-[11px] font-black text-slate-800 leading-none mb-1">{{ $request->created_at->format('M d, Y') }}</div>
                                    <div class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">at {{ $request->created_at->format('h:i A') }}</div>
                                </td>
                                <td class="py-5 px-2">
                                    <span class="bg-teal-50 text-teal-600 border border-teal-100 px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest">
                                        {{ $request->sector ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="py-5 px-2">
                                    <span
                                        class="px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-wider
                                                                                                                                                                                            @if($request->type == 'Organic') bg-emerald-50 text-emerald-600
                                                                                                                                                                                            @elseif($request->type == 'Recyclable') bg-blue-50 text-blue-600
                                                                                                                                                                                            @elseif($request->type == 'Hazardous') bg-red-50 text-red-600
                                                                                                                                                                                            @else bg-gray-50 text-gray-600 @endif">
                                        {{ $request->type }}
                                    </span>
                                </td>
                                <td class="py-5 px-2">
                                    <span class="px-2 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest
                                        {{ $request->shift == 'Morning Shift' ? 'bg-orange-50 text-orange-600 border border-orange-100' : 'bg-indigo-50 text-indigo-600 border border-indigo-100' }}">
                                        {{ $request->shift ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="py-5 px-2">
                                    @if($request->collector)
                                        <div class="flex items-center gap-2">
                                            <div
                                                class="w-7 h-7 rounded-full bg-[#DCF5F2] text-[#19817E] flex items-center justify-center text-[10px] font-black border border-white shadow-sm">
                                                {{ substr($request->collector->name, 0, 1) }}
                                            </div>
                                            <span class="text-[13px]">{{ $request->collector->name }}</span>
                                        </div>
                                    @else
                                        <div class="text-[13px] text-slate-300 italic font-medium">Unassigned</div>
                                    @endif
                                </td>
                                <td class="py-5 px-2">
                                    @if($request->status == 'Completed' || $request->status == 'Collected')
                                        <span
                                            class="px-2.5 py-1 rounded-lg text-[9px] font-black bg-emerald-50 text-emerald-600 tracking-widest uppercase">Collected</span>
                                    @elseif($request->status == 'Scheduled')
                                        <span
                                            class="px-2.5 py-1 rounded-lg text-[9px] font-black bg-[#E2F0EC] text-[#19817E] tracking-widest uppercase">Scheduled</span>
                                    @elseif($request->status == 'Cancelled')
                                        <span
                                            class="px-2.5 py-1 rounded-lg text-[9px] font-black bg-red-50 text-red-600 tracking-widest uppercase">Cancelled</span>
                                    @else
                                        <span
                                            class="px-2.5 py-1 rounded-lg text-[9px] font-black bg-[#FEF3C7] text-[#D97706] tracking-widest uppercase">{{ $request->status }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-4 text-center text-gray-500">No requests found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Request Details Modal -->
        <div id="detailsModal"
            class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-[9999] hidden flex items-center justify-center p-6">
            <div class="bg-white rounded-[24px] w-full max-w-md overflow-hidden shadow-2xl transform transition-all">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex justify-between items-cente                <div class="px-8 py-6 bg-white border-b border-gray-50 flex justify-between items-center">
                    <div>
                        <h3 class="text-xl font-black text-slate-800 tracking-tight">Request Details</h3>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-[0.1em] mt-0.5">Comprehensive overview of collection task</p>
                    </div>
                    <button onclick="closeDetailsModal()" class="w-8 h-8 flex items-center justify-center rounded-xl bg-slate-50 text-slate-400 hover:bg-red-50 hover:text-red-500 transition-all duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="p-8 space-y-8 max-h-[70vh] overflow-y-auto custom-scrollbar">
                    <!-- User & Assignment -->
                    <div class="grid grid-cols-2 gap-6 pb-6 border-b border-slate-50">
                        <div class="space-y-1">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Resident Name</p>
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-xs font-black border border-blue-100">
                                    <span id="modalUserInitials"></span>
                                </div>
                                <p id="modalUser" class="text-sm font-black text-slate-800"></p>
                            </div>
                        </div>
                        <div class="text-right space-y-1">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Assigned Staff</p>
                            <p id="modalCollector" class="text-sm font-black text-[#19817E] bg-teal-50 inline-block px-3 py-1 rounded-lg"></p>
                        </div>
                    </div>

                    <!-- Location & Contact -->
                    <div class="bg-slate-50/50 rounded-3xl p-6 border border-slate-100/50 space-y-4">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-2xl bg-white shadow-sm border border-slate-100 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Pickup Address</p>
                                <p id="modalAddress" class="text-xs font-bold text-slate-700 leading-relaxed"></p>
                                <div class="flex items-center gap-2 mt-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-teal-400"></span>
                                    <p id="modalPhone" class="text-[11px] font-black text-slate-500"></p>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4 pt-4 border-t border-slate-100">
                            <div>
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.1em] mb-0.5">Sector</p>
                                <p id="modalSector" class="text-xs font-black text-slate-800"></p>
                            </div>
                            <div class="text-right">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.1em] mb-0.5">Road / Block</p>
                                <p id="modalRoad" class="text-xs font-black text-slate-800"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Schedule & Type -->
                    <div class="grid grid-cols-2 gap-x-8 gap-y-6">
                        <div class="space-y-4">
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Waste Category</p>
                                <p id="modalType" class="text-xs font-black text-slate-800 bg-white border border-slate-100 px-3 py-2 rounded-xl inline-block shadow-sm"></p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Preferred Shift</p>
                                <p id="modalTime" class="text-xs font-black text-slate-800"></p>
                            </div>
                        </div>
                        <div class="space-y-4 text-right">
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Task Status</p>
                                <span id="modalStatus" class="px-4 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest inline-block"></span>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Scheduled Date</p>
                                <div class="flex flex-col items-end">
                                    <p id="modalDate" class="text-xs font-black text-slate-800"></p>
                                    <p id="modalRequestedAt" class="text-[10px] font-bold text-slate-400 mt-0.5"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Details -->
                    <div class="p-6 bg-amber-50/30 rounded-3xl border border-amber-100/50">
                        <div class="flex items-center gap-2 mb-3">
                            <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <p class="text-[10px] font-black text-amber-600 uppercase tracking-widest">Resident's Instructions</p>
                        </div>
                        <p id="modalDetails" class="text-xs font-medium text-slate-600 leading-relaxed"></p>
                    </div>
                </div>

                <div class="px-8 py-6 bg-white border-t border-gray-50 flex justify-end">
                    <button onclick="closeDetailsModal()" class="px-8 py-3.5 bg-slate-900 text-white text-[11px] font-black uppercase tracking-widest rounded-2xl hover:bg-black transition-all duration-300 shadow-xl shadow-slate-900/10 active:scale-[0.98]">
                        Close Overview
                    </button>
                </div>
            </div>
        </div>

        <script>
            function openRequestModal(btn) {
                const name = btn.getAttribute('data-user');
                document.getElementById('modalUser').innerText = name;
                document.getElementById('modalUserInitials').innerText = name.charAt(0);
                document.getElementById('modalCollector').innerText = btn.getAttribute('data-collector');
                document.getElementById('modalAddress').innerText = btn.getAttribute('data-address');
                document.getElementById('modalPhone').innerText = btn.getAttribute('data-phone');
                document.getElementById('modalSector').innerText = btn.getAttribute('data-sector');
                document.getElementById('modalRoad').innerText = btn.getAttribute('data-road');
                document.getElementById('modalType').innerText = btn.getAttribute('data-type');
                document.getElementById('modalDate').innerText = btn.getAttribute('data-date');
                document.getElementById('modalRequestedAt').innerText = 'Requested ' + btn.getAttribute('data-requested-at');
                document.getElementById('modalTime').innerText = btn.getAttribute('data-time');
                document.getElementById('modalDetails').innerText = btn.getAttribute('data-details');
                const status = btn.getAttribute('data-status');
                const statusEl = document.getElementById('modalStatus');
                statusEl.innerText = status;

                // Status styling
                if (status === 'Collected' || status === 'Completed') {
                    statusEl.className = 'px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-widest bg-emerald-50 text-emerald-600';
                } else if (status === 'Scheduled') {
                    statusEl.className = 'px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-widest bg-blue-50 text-blue-600';
                } else if (status === 'Cancelled') {
                    statusEl.className = 'px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-widest bg-red-50 text-red-600';
                } else {
                    statusEl.className = 'px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-widest bg-amber-50 text-amber-600';
                }

                const modal = document.getElementById('detailsModal');
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeDetailsModal() {
                const modal = document.getElementById('detailsModal');
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }

            window.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') closeDetailsModal();
            });
        </script>
@endsection