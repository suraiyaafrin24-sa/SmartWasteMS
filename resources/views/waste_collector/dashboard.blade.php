@extends('layouts.waste_collector')

@section('title', 'Collector Dashboard')

@section('content')
    @if(session('success'))
        <div
            class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-600 px-4 py-3 rounded-xl flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span class="font-semibold text-sm">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="font-semibold text-sm">{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <div class="mb-8 mt-2 flex justify-between items-end">
        <div>
            <h1 class="text-[28px] font-black tracking-tight text-gray-900 mb-1">My Task Center</h1>
            <p class="text-sm font-medium text-gray-500">Manage your collection route and update pickup status.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('waste_collector.map') }}"
                class="bg-gray-900 text-white text-xs font-bold py-2.5 px-6 rounded-xl hover:bg-black transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                Open Map View
            </a>
        </div>
    </div>

    <!-- Collector Status Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-[#19817E] text-white rounded-3xl p-6 shadow-sm flex flex-col justify-between h-40">
            <div>
                <div
                    class="text-teal-100 font-bold text-sm mb-1.5 flex items-center gap-1.5 uppercase tracking-widest text-[10px]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Remaining Tasks
                </div>
                <div class="text-[32px] font-black leading-none tracking-tight">{{ $activeRoute->count() }}</div>
            </div>
            <p
                class="text-[13px] text-teal-800 font-black bg-white/20 w-fit px-3 py-1.5 rounded-xl uppercase tracking-wider text-[9px]">
                {{ $assignedToday->count() }} Pickups Scheduled Today</p>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm flex flex-col justify-between h-40">
            <div>
                <div
                    class="text-emerald-500 font-bold text-sm mb-1.5 flex items-center gap-1.5 uppercase tracking-widest text-[10px]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Successful Collections
                </div>
                <div class="text-[32px] font-black text-gray-800 leading-none tracking-tight">{{ $completedCount }}</div>
            </div>
            <p class="text-[11px] text-emerald-500 font-black uppercase tracking-widest">Lifetime performance</p>
        </div>

        <div class="bg-gray-900 text-white rounded-3xl p-6 shadow-sm flex flex-col justify-between h-40">
            <div>
                <div
                    class="text-gray-400 font-bold text-sm mb-1.5 flex items-center gap-1.5 uppercase tracking-widest text-[10px]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Safety Alerts
                </div>
                <div class="text-[32px] font-black leading-none tracking-tight">0</div>
            </div>
            <p class="text-[11px] text-gray-400 font-black uppercase tracking-widest">All zones clear</p>
        </div>
    </div>

    <!-- Tasks Grid -->
    <div class="mb-10">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-900 tracking-tight">Active Duty Assignments</h2>
                <p class="text-[10px] font-bold text-teal-600 uppercase tracking-widest mt-0.5">Directly dispatched by
                    Command Center</p>
            </div>
            <div class="bg-teal-50 px-3 py-1.5 rounded-lg text-teal-700 font-bold text-[10px] border border-teal-100">
                {{ $activeRoute->count() }} Tasks
            </div>
        </div>

        <div class="bg-white rounded-[32px] border border-gray-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="px-8 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                Job Type</th>
                            <th class="px-8 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                Location</th>
                            <th class="px-8 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                Date & Shift</th>
                            <th class="px-8 py-5 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($activeRoute as $index => $request)
                            <tr class="hover:bg-gray-50/50 transition-colors {{ $index == 0 ? 'bg-teal-50/30' : '' }}">
                                <td class="px-8 py-5">
                                    <span class="px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-wider
                                                @if($request->type == 'Organic') bg-emerald-50 text-emerald-600
                                                @elseif($request->type == 'Recyclable') bg-blue-50 text-blue-600
                                                @elseif($request->type == 'Hazardous') bg-red-50 text-red-600
                                                @else bg-gray-50 text-gray-600 @endif">
                                        {{ $request->type }}
                                    </span>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="text-sm font-black text-gray-800 tracking-tight leading-none mb-1">
                                        {{ $request->road }}</div>
                                    <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                        {{ $request->address }} • {{ $request->sector }}</div>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="text-xs font-bold text-gray-700 mb-1">
                                        {{ \Carbon\Carbon::parse($request->scheduled_date)->format('M d, Y') }}</div>
                                    <div class="text-[10px] font-black text-teal-600 uppercase tracking-widest">
                                        {{ $request->shift ?? 'Morning Shift' }}</div>
                                </td>
                                <td class="px-8 py-5 text-right flex items-center justify-end gap-2">
                                    <a href="{{ route('waste_collector.map') }}"
                                        class="text-[9px] font-black text-gray-400 hover:text-gray-900 uppercase tracking-widest px-3 py-1 bg-gray-100 rounded-lg transition">
                                        Map
                                    </a>
                                    <form action="{{ route('waste_collector.requests.complete', $request->id) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="bg-emerald-500 text-white text-[9px] font-black py-1 px-4 rounded-lg hover:bg-emerald-600 transition shadow-lg shadow-emerald-500/10 uppercase tracking-widest">
                                            Collected
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-8 py-16 text-center">
                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">No active
                                        assignments for today</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Available Community Requests -->
    <div class="mb-10">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-900 tracking-tight">Available Community Requests</h2>
                <p class="text-[10px] font-bold text-amber-600 uppercase tracking-widest mt-0.5">Pending Resident Requests -
                    Pickup for points</p>
            </div>
            <div class="bg-amber-50 px-3 py-1.5 rounded-lg text-amber-700 font-bold text-[10px] border border-amber-100">
                {{ $availableRequests->count() }} Available
            </div>
        </div>

        <div class="bg-white rounded-[32px] border border-gray-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="px-8 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                Request Type</th>
                            <th class="px-8 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                Location Details</th>
                            <th class="px-8 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                Date & Shift</th>
                            <th class="px-8 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                Details</th>
                            <th class="px-8 py-5 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($availableRequests as $request)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-8 py-5">
                                    <span class="px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-wider
                                                @if($request->type == 'Organic') bg-emerald-50 text-emerald-600
                                                @elseif($request->type == 'Recyclable') bg-blue-50 text-blue-600
                                                @elseif($request->type == 'Hazardous') bg-red-50 text-red-600
                                                @else bg-gray-50 text-gray-600 @endif">
                                        {{ $request->type }}
                                    </span>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="text-sm font-black text-gray-800 tracking-tight leading-none mb-1">
                                        {{ $request->road }}</div>
                                    <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                        {{ $request->address }} • {{ $request->sector }}</div>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="text-xs font-bold text-gray-700 mb-1">
                                        {{ \Carbon\Carbon::parse($request->scheduled_date)->format('M d, Y') }}</div>
                                    <div class="text-[10px] font-black text-teal-600 uppercase tracking-widest">
                                        @php
                                            $shift = $request->shift;
                                            if (!$shift && $request->scheduled_time && $request->scheduled_time != 'Anytime') {
                                                $hour = (int) \Carbon\Carbon::parse($request->scheduled_time)->format('H');
                                                $shift = ($hour < 12) ? 'Morning Shift' : 'Evening Shift';
                                            }
                                        @endphp
                                        {{ $shift ?? 'Morning Shift' }}
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    <button type="button" 
                                        data-user="{{ $request->user->name }}"
                                        data-phone="{{ $request->phone ?? $request->user->phone ?? 'N/A' }}"
                                        data-address="{{ $request->address }}"
                                        data-type="{{ $request->type }}"
                                        data-details="{{ $request->details ?? 'No special instructions provided.' }}"
                                        data-date="{{ \Carbon\Carbon::parse($request->scheduled_date)->format('M d, Y') }}"
                                        data-time="{{ $shift ?? 'Morning Shift' }}"
                                        onclick="openRequestModal(this)"
                                        class="text-teal-600 hover:text-teal-800 text-[10px] font-black uppercase tracking-widest">
                                        View
                                    </button>
                                </td>
                                <td class="px-4 py-5 text-right">
                                    <form action="{{ route('waste_collector.requests.accept', $request->id) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="bg-gray-900 text-white text-[9px] font-black py-1 px-4 rounded-lg hover:bg-black transition shadow-lg shadow-gray-900/10 uppercase tracking-widest active:scale-95">
                                            Accept
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-8 py-20 text-center">
                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">No community
                                        requests available at the moment</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Request Details Modal -->
    <div id="detailsModal" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-[9999] hidden flex items-center justify-center p-6">
        <div class="bg-white rounded-[24px] w-full max-w-md overflow-hidden shadow-2xl transform transition-all">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-base font-black text-gray-900 tracking-tight">Collection Details</h3>
                <button onclick="closeDetailsModal()" class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Resident Name</p>
                        <p id="modalUser" class="text-xs font-bold text-gray-800"></p>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Contact Number</p>
                        <p id="modalPhone" class="text-xs font-bold text-teal-600"></p>
                    </div>
                </div>
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Pickup Address</p>
                    <p id="modalAddress" class="text-xs font-medium text-gray-600 leading-tight"></p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Waste Category</p>
                        <p id="modalType" class="text-xs font-bold text-gray-800"></p>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Scheduled Time</p>
                        <p id="modalSchedule" class="text-xs font-bold text-gray-800"></p>
                    </div>
                </div>
                <div class="p-3 bg-amber-50/50 rounded-xl border border-amber-100/50">
                    <p class="text-[9px] font-black text-amber-600 uppercase tracking-widest mb-1">Special Instructions</p>
                    <p id="modalDetails" class="text-[11px] font-medium text-amber-800 leading-normal"></p>
                </div>
            </div>
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end">
                <button onclick="closeDetailsModal()" class="px-5 py-2 bg-gray-900 text-white text-[10px] font-black uppercase tracking-widest rounded-lg hover:bg-black transition">
                    Close
                </button>
            </div>
        </div>
    </div>

    <script>
        function openRequestModal(btn) {
            document.getElementById('modalUser').innerText = btn.getAttribute('data-user');
            document.getElementById('modalPhone').innerText = btn.getAttribute('data-phone');
            document.getElementById('modalAddress').innerText = btn.getAttribute('data-address');
            document.getElementById('modalType').innerText = btn.getAttribute('data-type');
            document.getElementById('modalSchedule').innerText = btn.getAttribute('data-date') + ' (' + btn.getAttribute('data-time') + ')';
            document.getElementById('modalDetails').innerText = btn.getAttribute('data-details');
            
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