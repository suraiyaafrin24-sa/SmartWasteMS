@extends('layouts.user')

@section('title', 'Collector Schedule')

@section('content')
    <div class="mb-8 mt-2 flex justify-between items-end">
        <div>
            <h1 class="text-[28px] font-black tracking-tight text-gray-900 mb-1">Collector Duty Schedule</h1>
            <p class="text-sm font-medium text-gray-500">View assigned waste collectors for your sector.</p>
        </div>
    </div>


    <!-- Duty Schedule Table -->
    <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full table-fixed text-left text-sm whitespace-nowrap">
                <thead>
                    <tr class="text-gray-400 font-bold border-b border-gray-100 uppercase text-[11px] tracking-wider">
                        <th class="w-[22%] pb-3 px-2">Collector Name</th>
                        <th class="w-[18%] pb-3 px-2">Sector</th>
                        <th class="w-[24%] pb-3 px-2">Date</th>
                        <th class="w-[20%] pb-3 px-2">Shift</th>
                        <th class="w-[16%] pb-3 px-2">Status</th>
                    </tr>
                </thead>
                <tbody class="text-gray-800 font-semibold divide-y divide-gray-50">
                    @forelse($dutyRosters as $duty)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="py-4 px-2 flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-teal-100 flex items-center justify-center font-bold text-teal-700 text-xs overflow-hidden">
                                @if($duty->collector && $duty->collector->profile_image)
                                    <img src="{{ asset('storage/' . $duty->collector->profile_image) }}" class="w-full h-full object-cover">
                                @else
                                    {{ substr($duty->collector ? $duty->collector->name : 'U', 0, 1) }}
                                @endif
                            </div>
                            <span>{{ $duty->collector ? $duty->collector->name : 'Unassigned' }}</span>
                        </td>
                        <td class="py-4 px-2">{{ $duty->sector }}</td>
                        <td class="py-4 px-2">{{ \Carbon\Carbon::parse($duty->date)->format('M d, Y') }}</td>
                        <td class="py-4 px-2">
                            @if(str_contains(strtolower($duty->shift), 'morning'))
                                <span class="px-2.5 py-1 rounded-md text-[11px] font-black bg-blue-50 text-blue-600 tracking-wide uppercase">{{ $duty->shift }}</span>
                            @elseif(str_contains(strtolower($duty->shift), 'evening') || str_contains(strtolower($duty->shift), 'afternoon'))
                                <span class="px-2.5 py-1 rounded-md text-[11px] font-black bg-orange-50 text-orange-600 tracking-wide uppercase">{{ $duty->shift }}</span>
                            @elseif(str_contains(strtolower($duty->shift), 'night'))
                                <span class="px-2.5 py-1 rounded-md text-[11px] font-black bg-indigo-50 text-indigo-600 tracking-wide uppercase">{{ $duty->shift }}</span>
                            @else
                                <span class="px-2.5 py-1 rounded-md text-[11px] font-black bg-teal-50 text-teal-600 tracking-wide uppercase">{{ $duty->shift }}</span>
                            @endif
                        </td>
                        <td class="py-4 px-2">
                            @php
                                $todayDate = now()->toDateString();
                                $rosterDate = \Carbon\Carbon::parse($duty->date)->toDateString();
                                $currentTime = now()->format('H:i');
                                
                                if ($rosterDate < $todayDate) {
                                    $statusText = 'Completed';
                                    $statusClass = 'bg-gray-50 text-gray-400';
                                } elseif ($rosterDate > $todayDate) {
                                    $statusText = 'Upcoming';
                                    $statusClass = 'bg-blue-50 text-blue-500';
                                } else { // Today
                                    if (str_contains(strtolower($duty->shift), 'morning')) {
                                        if ($currentTime >= '07:00' && $currentTime <= '10:00') {
                                            $statusText = 'ACTIVE';
                                            $statusClass = 'bg-teal-50 text-teal-600 animate-pulse';
                                        } elseif ($currentTime < '07:00') {
                                            $statusText = 'Shift Pending';
                                            $statusClass = 'bg-amber-50 text-amber-600';
                                        } else {
                                            $statusText = 'Shift Ended';
                                            $statusClass = 'bg-gray-50 text-gray-400';
                                        }
                                    } elseif (str_contains(strtolower($duty->shift), 'evening') || str_contains(strtolower($duty->shift), 'afternoon')) {
                                        if ($currentTime >= '16:00' && $currentTime <= '18:00') {
                                            $statusText = 'ACTIVE';
                                            $statusClass = 'bg-teal-50 text-teal-600 animate-pulse';
                                        } elseif ($currentTime < '16:00') {
                                            $statusText = 'Shift Pending';
                                            $statusClass = 'bg-amber-50 text-amber-600';
                                        } else {
                                            $statusText = 'Shift Ended';
                                            $statusClass = 'bg-gray-50 text-gray-400';
                                        }
                                    } else {
                                        $statusText = 'Today';
                                        $statusClass = 'bg-slate-50 text-slate-400';
                                    }
                                }
                            @endphp
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-widest {{ $statusClass }}">
                                    {{ $statusText }}
                                </span>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-8 px-2 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                <span>No duty staff assignments found for this criteria.</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-6">
            {{ $dutyRosters->links() }}
        </div>
    </div>
@endsection
