@extends('layouts.waste_collector')

@section('title', 'Collection History')

@section('content')
    <div class="mb-10 mt-2">
        <h1 class="text-2xl font-black tracking-tight text-slate-800 mb-1">Collection Logs</h1>
        <p class="text-[13px] font-medium text-slate-400">Chronological history of all completed waste pick-ups and recorded metrics.</p>
    </div>

    <div class="bg-white rounded-[40px] p-10 border border-gray-50 shadow-sm shadow-gray-100/50">
        <div class="overflow-x-auto overflow-y-hidden">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead>
                    <tr class="text-slate-300 font-black border-b border-gray-50 uppercase text-[10px] tracking-[0.2em] leading-none">
                        <th class="pb-8 px-2">Completion Date</th>
                        <th class="pb-8 px-2">Resident Detail</th>
                        <th class="pb-8 px-2 text-center">Waste Type</th>
                        <th class="pb-8 px-2 text-center">Recorded Weight</th>
                        <th class="pb-8 px-2 text-right">Final Status</th>
                    </tr>
                </thead>
                <tbody class="text-slate-700 divide-y divide-gray-50/50">
                    @forelse($completedRequests as $request)
                    <tr class="hover:bg-gray-50/50 transition-all duration-300 group">
                        <td class="py-6 px-2">
                            <div class="font-black text-slate-800">{{ $request->updated_at->format('M d, Y') }}</div>
                            <div class="text-[10px] font-black text-slate-300 uppercase tracking-widest mt-1">{{ $request->updated_at->format('h:i A') }}</div>
                        </td>
                        <td class="py-6 px-2">
                            <div class="font-black text-slate-800 leading-tight mb-1">{{ $request->user->name }}</div>
                            <div class="text-[11px] text-slate-400 font-medium italic">"{{ Str::limit($request->address, 35) }}"</div>
                        </td>
                        <td class="py-6 px-2 text-center">
                            <span class="px-3 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-wider
                                @if($request->type == 'Organic') bg-emerald-50 text-emerald-600
                                @elseif($request->type == 'Recyclable') bg-blue-50 text-blue-600
                                @elseif($request->type == 'Hazardous') bg-red-50 text-red-600
                                @else bg-gray-50 text-slate-400 @endif">
                                {{ $request->type }}
                            </span>
                        </td>
                        <td class="py-6 px-2 text-center font-black text-slate-800">
                            {{ $request->weight }} <span class="text-[10px] text-slate-300 uppercase ml-0.5">KG</span>
                        </td>
                        <td class="py-6 px-2 text-right">
                            <span class="px-4 py-2 bg-emerald-50 text-emerald-600 text-[10px] font-black rounded-xl uppercase tracking-widest border border-emerald-100/50 shadow-sm shadow-emerald-500/5">
                                {{ $request->status }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-12 px-2 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-16 h-16 bg-slate-50 rounded-[24px] flex items-center justify-center text-slate-200">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <p class="text-[11px] font-black text-slate-300 uppercase tracking-widest">No completed records available</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($completedRequests->hasPages())
        <div class="pt-8 mt-4 border-t border-gray-50 flex justify-end">
            {{ $completedRequests->links() }}
        </div>
        @endif
    </div>
@endsection
