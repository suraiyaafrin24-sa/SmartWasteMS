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
            <h1 class="text-2xl font-black tracking-tight text-slate-800 mb-1">My Task Center</h1>
            <p class="text-[13px] font-medium text-slate-400">Manage your collection route and update pickup status.</p>
        </div>
        <div class="flex gap-3">
             <a href="{{ route('waste_collector.map') }}" class="bg-[#0D161F] text-white text-[13px] font-black py-2.5 px-6 rounded-xl hover:bg-black transition flex items-center gap-2 shadow-lg shadow-slate-900/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                Open Map View
            </a>
        </div>
    </div>

    <!-- Collector Status Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <!-- Assigned Today Card (Teal) -->
        <div class="bg-[#19817E] rounded-3xl p-6 shadow-xl shadow-teal-900/20 flex flex-col justify-between h-40 relative overflow-hidden group">
            <div class="absolute right-4 top-1/2 -translate-y-1/2 opacity-10 group-hover:scale-110 transition duration-500">
                <svg class="w-20 h-20 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
            </div>
            <div>
                <div class="text-teal-100 font-bold text-[13px] mb-1.5 flex items-center gap-1.5 leading-none">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Remaining Tasks
                </div>
                <div class="text-[32px] font-black text-white leading-none tracking-tight">{{ $activeRoute->count() }}</div>
            </div>
            <p class="text-[11px] text-white font-black bg-white/10 w-fit px-3 py-1.5 rounded-lg uppercase tracking-wider">{{ $assignedToday->count() }} Pickups Scheduled Today</p>
        </div>

        <!-- Completed Card (White) -->
        <div class="bg-white rounded-3xl p-6 border border-gray-50 shadow-sm flex flex-col justify-between h-40 relative overflow-hidden group hover:border-[#F8F9FA] transition-colors">
            <div class="absolute right-4 top-1/2 -translate-y-1/2 opacity-[0.03] group-hover:scale-110 transition duration-500">
                <svg class="w-20 h-20 text-emerald-500" fill="currentColor" viewBox="0 0 24 24"><path d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4L9 16.2z"/></svg>
            </div>
            <div>
                <div class="text-emerald-500 font-bold text-[13px] mb-1.5 flex items-center gap-1.5 leading-none">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Successful Collections
                </div>
                <div class="text-[32px] font-black text-slate-800 leading-none tracking-tight">{{ $completedCount }}</div>
            </div>
            <p class="text-[12px] text-emerald-500 font-bold leading-none uppercase tracking-widest">Lifetime performance</p>
        </div>

        <!-- Safety Alerts Card (Dark) -->
        <div class="bg-[#0D161F] rounded-3xl p-6 shadow-xl shadow-slate-900/20 flex flex-col justify-between h-40 relative overflow-hidden group">
             <div class="absolute right-4 top-1/2 -translate-y-1/2 opacity-10 group-hover:scale-110 transition duration-500">
                <svg class="w-20 h-20 text-slate-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
            </div>
            <div>
                <div class="text-slate-500 font-bold text-[13px] mb-1.5 flex items-center gap-1.5 leading-none">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Safety Alerts
                </div>
                <div class="text-[32px] font-black text-white leading-none tracking-tight">0</div>
            </div>
            <p class="text-[12px] text-slate-500 font-bold leading-none uppercase tracking-widest">All zones clear</p>
        </div>
    </div>

    <!-- Tasks Grid -->
    <div class="mb-10">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-xl font-black text-slate-800 tracking-tight leading-none">Active Duty Assignments</h2>
                <p class="text-[11px] font-bold text-[#19817E] uppercase tracking-widest mt-1.5">Directly dispatched by Command Center</p>
            </div>
            <div class="bg-[#DCF5F2] px-4 py-2 rounded-xl text-[#19817E] font-black text-[12px] border border-[#19817E]/10">
                {{ $activeRoute->count() }} Tasks
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($activeRoute as $index => $request)
            <div class="bg-white rounded-[40px] p-8 border {{ $index == 0 ? 'border-[#19817E]/20 ring-8 ring-[#19817E]/5' : 'border-gray-50' }} shadow-sm flex flex-col justify-between group hover:border-[#19817E]/10 transition-all duration-300">
                <div>
                    <div class="flex justify-between items-start mb-6">
                        <div class="flex flex-wrap gap-2">
                            <span class="px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest
                                @if($request->type == 'Organic') bg-emerald-50 text-emerald-600
                                @elseif($request->type == 'Recyclable') bg-blue-50 text-blue-600
                                @elseif($request->type == 'Hazardous') bg-red-50 text-red-600
                                @else bg-gray-50 text-gray-600 @endif">
                                {{ $request->type }}
                            </span>
                        </div>
                        <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Job #{{ $index + 1 }}</span>
                    </div>
                    <h4 class="text-base font-black text-slate-800 tracking-tight mb-1">{{ $request->road }}</h4>
                    <p class="text-[11px] text-slate-400 font-medium mb-4 line-clamp-1">{{ $request->address }} • {{ $request->sector }}</p>
                    
                    <div class="flex items-center gap-2 mb-6 p-2.5 bg-gray-50 rounded-2xl">
                        <div class="w-1.5 h-1.5 rounded-full bg-[#19817E] animate-pulse"></div>
                        <span class="text-[10px] font-black text-[#19817E] uppercase tracking-widest">{{ $request->shift }} Duty</span>
                    </div>
                </div>
                
                <div class="flex items-center gap-3 pt-5 border-t border-gray-50">
                    <a href="{{ route('waste_collector.map') }}" class="flex-1 text-center py-3 bg-gray-100 text-slate-400 text-[10px] font-black rounded-xl hover:bg-gray-200 transition uppercase tracking-[0.2em]">
                        Locate
                    </a>
                    <form action="{{ route('waste_collector.requests.complete', $request->id) }}" method="POST" class="flex-[1.5]">
                        @csrf
                        <button type="submit" class="w-full bg-[#19817E] text-white text-[10px] font-black py-3 px-4 rounded-xl hover:bg-teal-700 transition shadow-lg shadow-teal-700/10 uppercase tracking-[0.2em]">
                            Collected
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="col-span-full py-16 text-center">
                 <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">No active assignments for today</p>
            </div>
            @endforelse
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