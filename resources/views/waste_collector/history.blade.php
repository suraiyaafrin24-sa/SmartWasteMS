@extends('layouts.waste_collector')

@section('title', 'Collection History')

@section('content')
    <div class="mb-8 mt-2">
        <h1 class="text-[28px] font-black tracking-tight text-gray-900 mb-1">Collection History</h1>
        <p class="text-sm font-medium text-gray-500">View all your completed pick-ups and recorded data.</p>
    </div>

    <div class="bg-white rounded-[32px] border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100">Date / Time</th>
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100">User / Address</th>
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100">Waste Type</th>
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100">Weight</th>
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($completedRequests as $request)
                        <tr class="hover:bg-gray-50/30 transition group">
                            <td class="px-6 py-5">
                                <div class="text-sm font-black text-gray-800 tracking-tight leading-none mb-1">{{ $request->updated_at->format('M d, Y') }}</div>
                                <div class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{{ $request->updated_at->format('h:i A') }}</div>
                            </td>
                            <td class="px-6 py-5">
                                <div class="text-sm font-black text-gray-800 tracking-tight leading-none mb-1">{{ $request->user->name }}</div>
                                <div class="text-[11px] text-gray-500 font-medium whitespace-nowrap overflow-hidden text-ellipsis max-w-[200px]" title="{{ $request->address }}">
                                    {{ $request->address }}
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <span class="px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-wider
                                    @if($request->type == 'Organic') bg-emerald-50 text-emerald-600
                                    @elseif($request->type == 'Recyclable') bg-blue-50 text-blue-600
                                    @elseif($request->type == 'Hazardous') bg-red-50 text-red-600
                                    @else bg-gray-50 text-gray-600 @endif">
                                    {{ $request->type }}
                                </span>
                            </td>
                            <td class="px-6 py-5">
                                <div class="text-sm font-black text-gray-800 tracking-tight">{{ $request->weight }} <span class="text-[10px] text-gray-400 uppercase">KG</span></div>
                            </td>
                            <td class="px-6 py-5 text-right">
                                <span class="px-4 py-2 bg-emerald-50 text-emerald-600 text-[10px] font-black rounded-xl uppercase tracking-widest">
                                    Completed
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-20 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center text-gray-300 mb-2">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <h3 class="text-gray-900 font-black tracking-tight">No completed jobs yet</h3>
                                    <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">Your successful collections will appear here.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($completedRequests->hasPages())
            <div class="px-6 py-4 border-t border-gray-50">
                {{ $completedRequests->links() }}
            </div>
        @endif
    </div>
@endsection
