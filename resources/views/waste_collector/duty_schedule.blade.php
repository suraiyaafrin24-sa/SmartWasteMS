@extends('layouts.waste_collector')

@section('title', 'My Duty Schedule')

@section('content')
    <div class="mb-8 mt-2">
        <h1 class="text-[28px] font-black tracking-tight text-gray-900 mb-1">Daily Duty Schedule</h1>
        <p class="text-sm font-medium text-gray-500">Your assigned pickup sequence for today.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Timeline Section -->
        <div class="lg:col-span-3">
            <div class="bg-white rounded-[32px] p-8 border border-gray-100 shadow-sm">
                <div class="space-y-12 relative">
                    <!-- Vertical Line -->
                    <div class="absolute left-6 top-8 bottom-8 w-1 bg-gray-50 rounded-full"></div>

                    @forelse($schedule as $task)
                    <div class="relative flex items-start gap-12 group">
                        <!-- Time Indicator -->
                        <div class="z-10 bg-[#E2F0EC] text-teal-700 w-12 h-12 rounded-2xl flex flex-col items-center justify-center shrink-0 border-4 border-white shadow-sm font-black text-[10px] uppercase leading-none">
                            <span>{{ $task->scheduled_date->format('d') }}</span>
                            <span class="text-[8px]">{{ $task->scheduled_date->format('M') }}</span>
                        </div>

                        <!-- Content Card -->
                        <div class="flex-1 bg-gray-50/50 rounded-3xl p-6 border border-gray-100 group-hover:bg-white group-hover:shadow-xl group-hover:border-teal-100 transition-all duration-300">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="text-lg font-black text-gray-900 tracking-tight leading-none mb-2">{{ $task->address }}</h3>
                                    <div class="flex items-center gap-3">
                                        <span class="px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-wider bg-white border border-gray-100 text-gray-500">{{ $task->type }}</span>
                                        <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{{ $task->user->name }}</span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-[11px] font-black text-teal-600 uppercase tracking-widest leading-none mb-1">Status</div>
                                    <div class="text-[10px] font-bold text-gray-400">Scheduled</div>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between pt-4 border-t border-gray-100 mt-4">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    <span class="text-[11px] font-bold text-gray-500 tracking-tight">Geo-tagged Location</span>
                                </div>
                                <div class="flex gap-2">
                                    <a href="{{ route('waste_collector.map') }}" class="text-[10px] font-black text-teal-600 hover:text-teal-700 transition uppercase tracking-widest">Navigator</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="py-20 text-center flex flex-col items-center gap-4">
                        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center text-gray-300">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <p class="text-[11px] text-gray-400 font-black uppercase tracking-[3px]">No scheduled duties for today</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Progress Summary -->
        <div class="space-y-6">
            <div class="bg-gray-900 rounded-[32px] p-8 text-white relative overflow-hidden">
                <div class="absolute -right-8 -top-8 opacity-20">
                     <svg class="w-32 h-32 text-emerald-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14H9V8h2v8zm4 0h-2V8h2v8z"/></svg>
                </div>
                <h3 class="text-sm font-black uppercase tracking-widest mb-6 relative z-10">Shift Progress</h3>
                <div class="mb-8 relative z-10">
                    <div class="text-[42px] font-black leading-none mb-1">85%</div>
                    <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Efficiency Target</div>
                </div>
                <div class="w-full bg-gray-800 h-2 rounded-full overflow-hidden relative z-10">
                    <div class="bg-emerald-500 h-full w-[85%] rounded-full shadow-[0_0_15px_rgba(16,185,129,0.5)]"></div>
                </div>
            </div>

            <div class="bg-white rounded-[32px] p-8 border border-gray-100 shadow-sm">
                <h3 class="text-[11px] font-black text-gray-400 uppercase tracking-widest mb-6">Route Summary</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-gray-500">Total Stops</span>
                        <span class="text-sm font-black text-gray-800">{{ $schedule->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-gray-500">Est. Weight</span>
                        <span class="text-sm font-black text-gray-800">{{ $schedule->count() * 12 }} kg</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-gray-500">Est. Time</span>
                        <span class="text-sm font-black text-gray-800">{{ $schedule->count() * 15 }} mins</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
