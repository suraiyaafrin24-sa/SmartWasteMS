@extends('layouts.admin')

@section('title', 'Duty Schedule')

@section('content')
    <div class="mb-8 mt-2 flex justify-between items-end">
        <div>
            <h1 class="text-[28px] font-black tracking-tight text-gray-900 mb-1">Field Staff Monitoring</h1>
            <p class="text-sm font-medium text-gray-500">Track worker productivity and daily pickup assignments.</p>
        </div>
        <div class="flex gap-3">
             <button class="bg-gray-900 text-white text-[10px] font-black py-3 px-8 rounded-xl hover:bg-black transition shadow-lg shadow-gray-200 uppercase tracking-widest">
                Generate Reports
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($collectors as $collector)
        <div class="bg-white rounded-[32px] p-8 border border-gray-100 shadow-sm hover:shadow-xl transition-all group relative overflow-hidden">
            <div class="flex items-center gap-5 mb-8 relative z-10">
                <div class="relative">
                    <div class="w-16 h-16 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center font-black text-xl shadow-inner overflow-hidden border-2 border-white">
                        @if($collector->profile_image)
                            <img src="{{ asset('storage/' . $collector->profile_image) }}" class="w-full h-full object-cover">
                        @else
                            {{ substr($collector->name, 0, 1) }}
                        @endif
                    </div>
                    <span class="absolute -bottom-1 -right-1 block w-4 h-4 bg-emerald-500 rounded-full border-2 border-white"></span>
                </div>
                <div>
                    <h3 class="text-lg font-black text-gray-900 tracking-tight leading-none mb-1">{{ $collector->name }}</h3>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">ID: #COL-{{ str_pad($collector->id, 3, '0', STR_PAD_LEFT) }}</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-8">
                <div class="bg-gray-50/50 rounded-2xl p-4 text-center">
                    <div class="text-[22px] font-black text-gray-900 leading-none mb-1">{{ $collector->active_tasks }}</div>
                    <div class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Active Jobs</div>
                </div>
                <div class="bg-gray-50/50 rounded-2xl p-4 text-center">
                    <div class="text-[22px] font-black text-emerald-600 leading-none mb-1">{{ $collector->collection_requests_count }}</div>
                    <div class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Total Done</div>
                </div>
            </div>

            <div class="space-y-3 mb-8">
                <div class="flex items-center justify-between text-[11px] font-black uppercase tracking-widest text-gray-400">
                    <span>Availability</span>
                    @if($collector->active_tasks > 0)
                        <span class="text-emerald-500 flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            On Duty
                        </span>
                    @else
                        <span class="text-gray-300">Off Duty</span>
                    @endif
                </div>
                <div class="w-full bg-gray-100 h-1.5 rounded-full overflow-hidden">
                    <div class="bg-{{ $collector->active_tasks > 0 ? 'emerald-500' : 'gray-200' }} h-full w-[{{ $collector->active_tasks > 0 ? '100%' : '0%' }}] rounded-full shadow-[0_0_10px_rgba(16,185,129,0.2)]"></div>
                </div>
            </div>

            <!-- Activity Details -->
            <div class="border-t border-gray-50 pt-6 mt-6">
                <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-[2px] mb-4">Latest Field Activity</h4>
                <div class="space-y-3">
                    @forelse($collector->collectionRequests as $req)
                        <div class="flex items-start gap-3 p-3 rounded-2xl bg-gray-50/50 border border-gray-100/50">
                            <div class="w-8 h-8 rounded-xl bg-white shadow-sm flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                            </div>
                            <div class="min-w-0">
                                <div class="text-[11px] font-black text-gray-800 tracking-tight truncate">{{ $req->address }}</div>
                                <div class="text-[9px] font-bold text-gray-400 uppercase tracking-widest flex items-center gap-1.5 mt-0.5">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    {{ $req->user->name }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-[10px] font-black text-gray-300 uppercase tracking-widest italic">No recent activity</div>
                    @endforelse
                </div>
            </div>
            
            <div class="mt-8">
                <a href="{{ route('admin.collections') }}?collector={{ $collector->id }}" style="background-color: #2D5CFE !important; color: #FFFFFF !important;" class="w-full flex items-center justify-center gap-2 text-[10px] font-black py-4 rounded-2xl hover:opacity-90 transition shadow-lg shadow-blue-500/20 uppercase tracking-widest">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Performance Report
                </a>
            </div>
        </div>
        @endforeach
    </div>
@endsection
