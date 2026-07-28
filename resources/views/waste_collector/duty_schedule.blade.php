@extends('layouts.waste_collector')

@section('title', 'My Duty Schedule')

@section('content')
    <div class="mb-8 mt-2">
        <h1 class="text-[28px] font-black tracking-tight text-gray-900 mb-1">Daily Duty Schedule</h1>
        <p class="text-sm font-medium text-gray-500">Your assigned pickup sequence for today.</p>
    </div>

    <div class="bg-white rounded-[32px] border border-gray-100 shadow-sm overflow-hidden mb-10">
        <div class="p-8 border-b border-gray-50 flex items-center justify-between bg-gray-50/30">
            <h3 class="text-lg font-black text-gray-800 tracking-tight">Assigned Duties</h3>
            <div class="px-4 py-2 bg-white border border-gray-100 rounded-xl text-[11px] font-bold text-gray-500 uppercase tracking-widest shadow-sm">
                Total assignments: {{ $schedule->count() }}
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100">Duty Date</th>
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100">Coverage Area</th>
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100">Operating Shift</th>
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($schedule as $roster)
                        @php
                            $today = now()->format('Y-m-d');
                            $currentTime = now()->format('H:i');
                            $isToday = $roster->date->format('Y-m-d') == $today;
                            $isFuture = $roster->date->format('Y-m-d') > $today;
                            $isPast = $roster->date->format('Y-m-d') < $today;
                            
                            $statusText = 'Completed';
                            $statusClass = 'bg-gray-100 text-gray-400';

                            if ($isToday) {
                                if ($roster->shift == 'Morning Shift') {
                                    if ($currentTime >= '07:00' && $currentTime <= '10:00') {
                                        $statusText = 'ACTIVE NOW';
                                        $statusClass = 'bg-emerald-50 text-emerald-600 animate-pulse';
                                    } elseif ($currentTime > '10:00') {
                                        $statusText = 'Duty Finished';
                                        $statusClass = 'bg-gray-100 text-gray-400';
                                    } else {
                                        $statusText = 'Upcoming';
                                        $statusClass = 'bg-amber-50 text-amber-600';
                                    }
                                } else { // Evening Shift
                                    if ($currentTime >= '16:00' && $currentTime <= '18:00') {
                                        $statusText = 'ACTIVE NOW';
                                        $statusClass = 'bg-emerald-50 text-emerald-600 animate-pulse';
                                    } elseif ($currentTime > '18:00') {
                                        $statusText = 'Duty Finished';
                                        $statusClass = 'bg-gray-100 text-gray-400';
                                    } else {
                                        $statusText = 'Upcoming';
                                        $statusClass = 'bg-amber-50 text-amber-600';
                                    }
                                }
                            } elseif ($isFuture) {
                                $statusText = 'Scheduled';
                                $statusClass = 'bg-blue-50 text-blue-600';
                            }
                        @endphp
                        <tr class="hover:bg-gray-50/50 transition group">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-gray-50 flex flex-col items-center justify-center border border-gray-100 shadow-sm">
                                        <span class="text-[11px] font-black text-gray-900 leading-none">{{ $roster->date->format('d') }}</span>
                                        <span class="text-[8px] font-bold text-gray-400 uppercase tracking-tighter">{{ $roster->date->format('M') }}</span>
                                    </div>
                                    <div>
                                        <div class="text-xs font-black text-gray-800 tracking-tight">{{ $roster->date->format('l') }}</div>
                                        <div class="text-[10px] font-medium text-gray-400">{{ $roster->date->format('Y') }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="text-sm font-black text-gray-800 tracking-tight leading-none mb-1">{{ $roster->sector }}</div>
                                <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $roster->road ?? 'Sector Main Roads' }}</div>
                            </td>
                            <td class="px-8 py-6">
                                <span class="px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider bg-white border border-gray-100 text-teal-600 shadow-sm inline-flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $roster->shift == 'Morning Shift' ? 'bg-orange-400' : 'bg-indigo-400' }}"></span>
                                    {{ $roster->shift }}
                                </span>
                            </td>
                            <td class="px-8 py-6">
                                <span class="px-3 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-widest {{ $statusClass }}">
                                    {{ $statusText }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-24 text-center">
                                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center text-gray-300 mx-auto mb-4">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <p class="text-[11px] text-gray-400 font-black uppercase tracking-[3px]">No administrative staff assigned</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
