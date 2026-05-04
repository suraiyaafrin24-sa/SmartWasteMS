@extends('layouts.admin')

@section('title', 'All Collections')

@section('content')
    <div class="mb-8 mt-2 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-[28px] font-black tracking-tight text-gray-900 mb-1">Waste Collections</h1>
            <p class="text-sm font-medium text-gray-500">History of all waste pickup requests across the system.</p>
        </div>
        <div class="w-full md:w-80">
            <form action="{{ route('admin.collections') }}" method="GET" class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search collections..." class="w-full bg-white border border-gray-200 text-sm font-medium rounded-2xl pl-10 pr-4 py-3 focus:ring-4 focus:ring-teal-500/10 focus:border-[#19817E] outline-none transition-all shadow-sm">
                @if(request('search'))
                    <a href="{{ route('admin.collections') }}" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-red-500 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </a>
                @endif
            </form>
        </div>
    </div>

    <div class="bg-white rounded-[32px] border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100">Request Date</th>
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100">User / Resident</th>
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 text-center">Type</th>
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100">Assigned Collector</th>
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($collections as $request)
                        <tr class="hover:bg-gray-50/30 transition group">
                            <td class="px-6 py-5">
                                <div class="text-sm font-black text-gray-800 tracking-tight leading-none mb-1">{{ $request->created_at->format('M d, Y') }}</div>
                                <div class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">At {{ $request->created_at->format('h:i A') }}</div>
                            </td>
                            <td class="px-6 py-5">
                                <div class="text-sm font-black text-gray-800 tracking-tight leading-none mb-1">{{ $request->user->name }}</div>
                                <div class="text-[10px] text-gray-500 font-medium whitespace-nowrap overflow-hidden text-ellipsis max-w-[200px]">{{ $request->address }}</div>
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
                                        <select name="collector_id" required class="text-[10px] font-bold bg-gray-50 border border-gray-100 rounded-lg px-2 py-1.5 focus:ring-1 focus:ring-teal-500 outline-none">
                                            <option value="">Select Collector</option>
                                            @foreach($collectors as $c)
                                                <option value="{{ $c->id }}">
                                                    {{ $c->name }}
                                                    @if($c->sector)
                                                        — {{ $c->sector }}
                                                    @elseif($c->dutyRosters->first())
                                                        — {{ $c->dutyRosters->first()->sector }}
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                        <input type="date" name="scheduled_date" required value="{{ date('Y-m-d') }}" class="text-[10px] font-bold bg-gray-50 border border-gray-100 rounded-lg px-2 py-1.5 outline-none">
                                        <button type="submit" class="bg-[#19817E] text-white text-[9px] font-black px-3 py-1.5 rounded-lg hover:bg-teal-700 transition uppercase tracking-widest">Assign</button>
                                    </form>
                                @else
                                    <span class="text-[10px] font-black text-gray-300 uppercase tracking-widest italic">Unassigned</span>
                                @endif
                            </td>
                            <td class="px-6 py-5 text-right">
                                <span class="px-3 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-widest
                                    @if($request->status == 'Completed' || $request->status == 'Collected') bg-emerald-50 text-emerald-600
                                    @elseif($request->status == 'Scheduled') bg-blue-50 text-blue-600
                                    @elseif($request->status == 'Pending') bg-amber-50 text-amber-600
                                    @else bg-gray-50 text-gray-500 @endif">
                                    {{ $request->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-20 text-center text-gray-400 font-bold uppercase tracking-[2px]">No collection requests found.</td>
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
