@extends('layouts.waste_collector')

@section('title', 'Collector Dashboard')

@section('content')
    @if(session('success'))
        <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-600 px-4 py-3 rounded-xl flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span class="font-semibold text-sm">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
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
             <a href="{{ route('waste_collector.map') }}" class="bg-gray-900 text-white text-xs font-bold py-2.5 px-6 rounded-xl hover:bg-black transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                Open Map View
            </a>
        </div>
    </div>

    <!-- Collector Status Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-[#19817E] text-white rounded-3xl p-6 shadow-sm flex flex-col justify-between h-40">
            <div>
                <div class="text-teal-100 font-bold text-sm mb-1.5 flex items-center gap-1.5 uppercase tracking-widest text-[10px]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Remaining Tasks
                </div>
                <div class="text-[32px] font-black leading-none tracking-tight">{{ $activeRoute->count() }}</div>
            </div>
            <p class="text-[13px] text-teal-800 font-black bg-white/20 w-fit px-3 py-1.5 rounded-xl uppercase tracking-wider text-[9px]">{{ $assignedToday->count() }} Pickups Scheduled Today</p>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm flex flex-col justify-between h-40">
            <div>
                <div class="text-emerald-500 font-bold text-sm mb-1.5 flex items-center gap-1.5 uppercase tracking-widest text-[10px]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Successful Collections
                </div>
                <div class="text-[32px] font-black text-gray-800 leading-none tracking-tight">{{ $completedCount }}</div>
            </div>
            <p class="text-[11px] text-emerald-500 font-black uppercase tracking-widest">Lifetime performance</p>
        </div>

        <div class="bg-gray-900 text-white rounded-3xl p-6 shadow-sm flex flex-col justify-between h-40">
            <div>
                <div class="text-gray-400 font-bold text-sm mb-1.5 flex items-center gap-1.5 uppercase tracking-widest text-[10px]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
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
                <p class="text-[10px] font-bold text-teal-600 uppercase tracking-widest mt-0.5">Directly dispatched by Command Center</p>
            </div>
            <div class="bg-teal-50 px-3 py-1.5 rounded-lg text-teal-700 font-bold text-[10px] border border-teal-100">
                {{ $activeRoute->count() }} Tasks
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($activeRoute as $index => $request)
            <div class="bg-white rounded-2xl p-4 border {{ $index == 0 ? 'border-teal-200 ring-4 ring-teal-50' : 'border-gray-100' }} shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex flex-wrap gap-1.5">
                            <span class="px-2 py-0.5 rounded-md text-[9px] font-black uppercase tracking-wider
                                @if($request->type == 'Organic') bg-emerald-50 text-emerald-600
                                @elseif($request->type == 'Recyclable') bg-blue-50 text-blue-600
                                @elseif($request->type == 'Hazardous') bg-red-50 text-red-600
                                @else bg-gray-50 text-gray-600 @endif">
                                {{ $request->type }}
                            </span>
                        </div>
                        <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Job #{{ $index + 1 }}</span>
                    </div>

                    <h4 class="text-sm font-black text-gray-800 tracking-tight mb-0.5">{{ $request->road }}</h4>
                    <p class="text-[10px] text-gray-500 font-medium mb-3 line-clamp-1">{{ $request->address }} • {{ $request->sector }}</p>
                    
                    <div class="flex items-center gap-2 mb-4 p-2 bg-gray-50 rounded-lg">
                        <div class="w-2 h-2 rounded-full bg-teal-500 animate-pulse"></div>
                        <span class="text-[10px] font-bold text-teal-700 uppercase tracking-wider">{{ $request->shift }} Duty</span>
                    </div>
                </div>
                
                <div class="flex items-center gap-2 pt-3 border-t border-gray-50">
                    <a href="{{ route('waste_collector.map') }}" class="flex-1 text-center py-2 bg-gray-100 text-gray-700 text-[9px] font-black rounded-lg hover:bg-gray-200 transition uppercase tracking-widest">
                        Locate
                    </a>
                    <form action="{{ route('waste_collector.requests.complete', $request->id) }}" method="POST" class="flex-2">
                        @csrf
                        <button type="submit" class="w-full bg-emerald-500 text-white text-[9px] font-black py-2 px-4 rounded-lg hover:bg-emerald-600 transition shadow-md shadow-emerald-500/10 uppercase tracking-widest">
                            Collected
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-16 bg-gray-50/50 rounded-2xl border-2 border-dashed border-gray-100">
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">No active assignments</p>
            </div>
            @endforelse
        </div>
    </div>
@endsection
