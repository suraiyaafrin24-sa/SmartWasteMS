@extends('layouts.admin')

@section('title', 'All Collections')

@section('content')
    <div class="mb-8 mt-2 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-[28px] font-black tracking-tight text-gray-900 mb-1">Waste Collections</h1>
            <p class="text-sm font-medium text-gray-500">History of all waste pickup requests across the system.</p>
        </div>
        <div class="w-full md:w-[400px]">
            <form action="{{ route('admin.collections') }}" method="GET" class="flex gap-2">
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="" class="w-full bg-white border border-gray-200 text-sm font-medium rounded-2xl pl-10 pr-10 py-3 focus:ring-4 focus:ring-teal-500/10 focus:border-[#19817E] outline-none transition-all shadow-sm">
                    @if(request('status'))
                        <input type="hidden" name="status" value="{{ request('status') }}">
                    @endif
                    @if(request('search'))
                        <a href="{{ route('admin.collections') }}" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-red-500 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </a>
                    @endif
                </div>
                <button type="submit" class="bg-[#19817E] text-white text-[10px] font-black px-6 py-3 rounded-2xl hover:bg-teal-700 transition shadow-lg shadow-teal-700/10 uppercase tracking-widest shrink-0">Search</button>
            </form>
        </div>
    </div>
    <div class="flex flex-wrap gap-3 mb-6">
        <a href="{{ route('admin.collections', request()->only(['search'])) }}" 
            class="px-5 py-2.5 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all duration-300 {{ !request('status') ? 'bg-[#19817E] text-white shadow-lg shadow-teal-700/20' : 'bg-white text-gray-500 border border-gray-100 hover:bg-gray-50' }}">
            All Requests
        </a>
        <a href="{{ route('admin.collections', array_merge(request()->only(['search']), ['status' => 'Pending'])) }}" 
            class="px-5 py-2.5 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all duration-300 {{ request('status') == 'Pending' ? 'bg-amber-500 text-white shadow-lg shadow-amber-500/20' : 'bg-white text-gray-500 border border-gray-100 hover:bg-gray-50' }}">
            Pending Pickup
        </a>
        <a href="{{ route('admin.collections', array_merge(request()->only(['search']), ['status' => 'Completed'])) }}" 
            class="px-5 py-2.5 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all duration-300 {{ request('status') == 'Completed' ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/20' : 'bg-white text-gray-500 border border-gray-100 hover:bg-gray-50' }}">
            Completed
        </a>
        <a href="{{ route('admin.collections', array_merge(request()->only(['search']), ['status' => 'Today'])) }}" 
            class="px-5 py-2.5 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all duration-300 {{ request('status') == 'Today' ? 'bg-indigo-500 text-white shadow-lg shadow-indigo-500/20' : 'bg-white text-gray-500 border border-gray-100 hover:bg-gray-50' }}">
            Today's Requests
        </a>
    </div>
    <div class="bg-white rounded-[32px] border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100">Request Date</th>
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100">Scheduled Date</th>
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100">User</th>
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 text-center">Sector</th>
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 text-center">Type</th>
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 text-center">Shift</th>
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100">Assigned Collector</th>
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($collections as $request)
                        <tr class="hover:bg-gray-50/30 transition group">
                            <td class="px-6 py-4">
                                <div class="text-[11px] font-black text-gray-800 leading-none mb-1">{{ $request->created_at->format('M d, Y') }}</div>
                                <div class="text-[9px] text-gray-400 font-bold uppercase tracking-widest">at {{ $request->created_at->format('h:i A') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-[11px] font-black text-gray-800 leading-none mb-1">{{ \Carbon\Carbon::parse($request->scheduled_date)->format('M d, Y') }}</div>
                            </td>
                            <td class="px-6 py-5">
                                <div class="text-sm font-black text-gray-800 tracking-tight leading-none">{{ $request->user->name }}</div>
                            </td>
                            <td class="px-6 py-5 text-center">
                                <span class="bg-teal-50 text-teal-600 border border-teal-100 px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest">
                                    {{ $request->sector ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-5 text-center">
                                <span class="px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-wider
                                    @if($request->type == 'Organic') bg-emerald-50 text-emerald-600
                                    @elseif($request->type == 'Recyclable') bg-blue-50 text-blue-600
                                    @elseif($request->type == 'Hazardous') bg-red-50 text-red-600
                                    @else bg-gray-50 text-gray-600 @endif tracking-widest leading-none">
                                    {{ $request->type }}
                                </span>
                            </td>
                            <td class="px-6 py-5 text-center">
                                <span class="px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest
                                    {{ $request->shift == 'Morning Shift' ? 'bg-orange-50 text-orange-600 border border-orange-100' : 'bg-indigo-50 text-indigo-600 border border-indigo-100' }}">
                                    {{ $request->shift ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-5">
                                @if($request->collector)
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-teal-50 text-teal-600 flex items-center justify-center text-[10px] font-black">
                                            {{ substr($request->collector->name, 0, 1) }}
                                        </div>
                                        <div class="text-[11px] font-black text-gray-700 tracking-tight">{{ $request->collector->name }}</div>
                                    </div>
                                @elseif($request->status == 'Pending')
                                    <form action="{{ route('admin.requests.assign', $request->id) }}" method="POST" class="flex items-center gap-2">
                                        @csrf
                                        <div class="relative">
                                            <select name="collector_id" required class="text-[10px] font-bold bg-gray-50 border border-gray-100 rounded-lg px-2 py-1.5 pr-8 focus:ring-1 focus:ring-teal-500 outline-none appearance-none cursor-pointer">
                                                <option value="">Select Collector</option>
                                                @foreach($collectors as $c)
                                                     @php
                                                         $scheduledDate = $request->scheduled_date ? $request->scheduled_date->format('Y-m-d') : date('Y-m-d');
                                                         $leave = $c->leaveRequests
                                                             ->where('status', 'Approved')
                                                             ->first(function ($leaveRequest) use ($scheduledDate) {
                                                                 return $leaveRequest->start_date->format('Y-m-d') <= $scheduledDate
                                                                     && $leaveRequest->end_date->format('Y-m-d') >= $scheduledDate;
                                                             });
                                                     @endphp
                                                    <option value="{{ $c->id }}">
                                                        {{ $c->name }}
                                                        @if($c->sector)
                                                            — {{ $c->sector }}
                                                        @elseif($c->dutyRosters->first())
                                                            — {{ $c->dutyRosters->first()->sector }}
                                                        @endif
                                                        @if($leave)
                                                            — On Leave
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="absolute inset-y-0 right-2.5 flex items-center pointer-events-none text-gray-400">
                                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                                            </div>
                                        </div>
                                        <input type="date" name="scheduled_date" required value="{{ $request->scheduled_date ? $request->scheduled_date->format('Y-m-d') : date('Y-m-d') }}" min="{{ date('Y-m-d') }}" class="text-[10px] font-bold bg-gray-50 border border-gray-100 rounded-lg px-2 py-1.5 outline-none">
                                        <button type="submit" class="bg-[#19817E] text-white text-[9px] font-black px-3 py-1.5 rounded-lg hover:bg-teal-700 transition uppercase tracking-widest">Assign</button>
                                    </form>
                                @else
                                    <span class="text-[10px] font-black text-gray-300 uppercase tracking-widest italic">Unassigned</span>
                                @endif
                            </td>
                            <td class="px-6 py-5 text-center">
                                <span class="px-3 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-widest
                                    @if($request->status == 'Completed' || $request->status == 'Collected') bg-emerald-50 text-emerald-600
                                    @elseif($request->status == 'Scheduled') bg-blue-50 text-blue-600
                                    @elseif($request->status == 'Pending') bg-amber-50 text-amber-600
                                    @elseif($request->status == 'Cancelled') bg-red-50 text-red-600
                                    @else bg-gray-50 text-gray-500 @endif">
                                    {{ $request->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-10 text-center text-gray-400 font-bold uppercase tracking-[2px]">No collection requests found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($collections->hasPages())
            <div class="px-6 py-4 border-t border-gray-50">
                {{ $collections->links() }}
            </div>
        @endif
    </div>
@endsection
