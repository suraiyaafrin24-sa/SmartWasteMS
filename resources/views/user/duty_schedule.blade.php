@extends('layouts.user')

@section('title', 'Collector Schedule')

@section('content')
    <div class="mb-8 mt-2 flex justify-between items-end">
        <div>
            <h1 class="text-[28px] font-black tracking-tight text-gray-900 mb-1">Collector Duty Schedule</h1>
            <p class="text-sm font-medium text-gray-500">View assigned waste collectors for your sector.</p>
        </div>
    </div>

    <!-- Search/Filter Section -->
    <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm mb-8">
        <form action="{{ route('user.duty_schedule') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
            <div class="w-full md:w-1/3">
                <label for="sector" class="block text-sm font-bold text-gray-700 mb-2">Search by Sector</label>
                <select name="sector" id="sector" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-teal-500 focus:border-teal-500 block p-3 font-semibold transition">
                    <option value="">All Sectors</option>
                    @foreach($sectors as $sec)
                        @if(!empty($sec))
                            <option value="{{ $sec }}" {{ request('sector') == $sec ? 'selected' : '' }}>Sector {{ $sec }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div>
                <button type="submit" class="bg-[#19817E] text-white text-sm font-semibold py-3 px-6 rounded-xl hover:bg-teal-700 transition shadow-sm flex items-center gap-2 h-[46px]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    Search
                </button>
            </div>
            @if(request('sector'))
            <div>
                <a href="{{ route('user.duty_schedule') }}" class="bg-gray-100 text-gray-600 text-sm font-semibold py-3 px-6 rounded-xl hover:bg-gray-200 transition shadow-sm flex items-center gap-2 h-[46px]">
                    Clear
                </a>
            </div>
            @endif
        </form>
    </div>

    <!-- Duty Schedule Table -->
    <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead>
                    <tr class="text-gray-400 font-bold border-b border-gray-100 uppercase text-[11px] tracking-wider">
                        <th class="pb-3 px-2">Collector Name</th>
                        <th class="pb-3 px-2">Sector</th>
                        <th class="pb-3 px-2">Road</th>
                        <th class="pb-3 px-2">Date</th>
                        <th class="pb-3 px-2">Shift</th>
                        <th class="pb-3 px-2">Status</th>
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
                        <td class="py-4 px-2">Sector {{ $duty->sector }}</td>
                        <td class="py-4 px-2">{{ $duty->road ?? 'N/A' }}</td>
                        <td class="py-4 px-2">{{ \Carbon\Carbon::parse($duty->date)->format('M d, Y') }}</td>
                        <td class="py-4 px-2">
                            @if($duty->shift == 'Morning')
                                <span class="px-2.5 py-1 rounded-md text-[11px] font-black bg-amber-50 text-amber-600 tracking-wide uppercase">{{ $duty->shift }}</span>
                            @elseif($duty->shift == 'Afternoon')
                                <span class="px-2.5 py-1 rounded-md text-[11px] font-black bg-orange-50 text-orange-600 tracking-wide uppercase">{{ $duty->shift }}</span>
                            @else
                                <span class="px-2.5 py-1 rounded-md text-[11px] font-black bg-indigo-50 text-indigo-600 tracking-wide uppercase">{{ $duty->shift }}</span>
                            @endif
                        </td>
                        <td class="py-4 px-2">
                            @if($duty->status == 'Completed')
                                <span class="px-2.5 py-1 rounded-md text-[11px] font-black bg-emerald-100 text-emerald-600 tracking-wide uppercase">Completed</span>
                            @else
                                <span class="px-2.5 py-1 rounded-md text-[11px] font-black bg-gray-100 text-gray-600 tracking-wide uppercase">{{ $duty->status }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-8 px-2 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                <span>No duty roster assignments found for this criteria.</span>
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
