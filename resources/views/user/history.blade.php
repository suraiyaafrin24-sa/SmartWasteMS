@extends('layouts.user')

@section('title', 'Request History')

@section('content')
    <div class="mb-10 mt-2 flex justify-between items-end">
        <div>
            <h1 class="text-2xl font-black tracking-tight text-slate-800 mb-1">Collection History</h1>
            <p class="text-[13px] font-medium text-slate-400">Review your past schedules and tracking logs.</p>
        </div>
        <a href="{{ route('user.requests.create') }}" class="bg-[#19817E] text-white text-[13px] font-black py-4 px-10 rounded-2xl hover:bg-teal-700 transition shadow-lg shadow-teal-700/20 uppercase tracking-widest leading-none">
            + New Request
        </a>
    </div>

    <div x-data="{ showDetails: false, selectedRequest: {} }" class="bg-white rounded-[40px] p-10 border border-gray-50 shadow-sm shadow-gray-100/50">
        <div class="overflow-x-auto overflow-y-hidden">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead>
                    <tr class="text-slate-300 font-black border-b border-gray-50 uppercase text-[10px] tracking-[0.2em] leading-none">
                        <th class="pb-8 px-2">Type / Reference</th>
                        <th class="pb-8 px-2">Schedule Detail</th>
                        <th class="pb-8 px-2 text-center">Status</th>
                        <th class="pb-8 px-2 text-center">Payment</th>
                        <th class="pb-8 px-2 text-center">Receipt</th>
                        <th class="pb-8 px-2">Staff Assigned</th>
                        <th class="pb-8 px-2 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="text-slate-700 divide-y divide-gray-50/50">
                    @forelse($requests as $request)
                    <tr class="hover:bg-gray-50/50 transition-all duration-300 group">
                        <td class="py-6 px-2">
                            <div class="font-black text-slate-800">{{ $request->type }}</div>
                            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">ID: #RE-{{ str_pad($request->id, 5, '0', STR_PAD_LEFT) }}</div>
                        </td>
                        <td class="py-6 px-2">
                            <div class="font-bold text-slate-600">{{ $request->scheduled_date->format('M d, Y') }}</div>
                            <div class="text-[10px] font-black text-slate-300 uppercase tracking-widest mt-1">{{ $request->scheduled_time ?? 'Standard Window' }}</div>
                        </td>
                        <td class="py-6 px-2 text-center">
                            @if($request->status == 'Completed' || $request->status == 'Collected')
                                <span class="px-3 py-1.5 rounded-xl text-[10px] font-black bg-emerald-50 text-emerald-600 tracking-widest uppercase">Collected</span>
                            @elseif($request->status == 'Scheduled')
                                <span class="px-3 py-1.5 rounded-xl text-[10px] font-black bg-amber-50 text-amber-600 tracking-widest uppercase">Scheduled</span>
                            @elseif($request->status == 'Cancelled')
                                <span class="px-3 py-1.5 rounded-xl text-[10px] font-black bg-red-50 text-red-600 tracking-widest uppercase">Cancelled</span>
                            @else
                                <span class="px-3 py-1.5 rounded-xl text-[10px] font-black bg-blue-50 text-blue-600 tracking-widest uppercase">{{ $request->status }}</span>
                            @endif
                        </td>
                        <td class="py-6 px-2 text-center">
                            @if($request->payment_status == 'Paid')
                                <span class="px-3 py-1.5 rounded-xl text-[10px] font-black bg-emerald-50 text-emerald-600 tracking-widest uppercase">Paid</span>
                            @else
                                <form action="{{ route('sslc.pay') }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="amount" value="500">
                                    <input type="hidden" name="request_id" value="{{ $request->id }}">
                                    <button type="submit" class="px-3 py-1.5 rounded-xl text-[10px] font-black bg-rose-50 text-rose-600 tracking-widest uppercase hover:bg-rose-100 transition-colors">Unpaid</button>
                                </form>
                            @endif
                        </td>
                        <td class="py-6 px-2 text-center">
                            @if($request->payment_status == 'Paid')
                                <a href="{{ route('user.receipt', $request->id) }}" target="_blank" class="inline-flex items-center gap-1 text-[#19817E] hover:text-teal-700 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    <span class="text-[10px] font-black uppercase tracking-widest">PDF</span>
                                </a>
                            @else
                                <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest">N/A</span>
                            @endif
                        </td>
                        <td class="py-6 px-2">
                            @if($request->collector)
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-[#DCF5F2] text-[#19817E] flex items-center justify-center text-[10px] font-black border border-white shadow-sm shrink-0">
                                        {{ substr($request->collector->name, 0, 1) }}
                                    </div>
                                    <span class="font-bold text-slate-500">{{ $request->collector->name }}</span>
                                </div>
                            @else
                                <span class="italic text-[12px] text-slate-300 font-medium tracking-tight">Pending allocation</span>
                            @endif
                        </td>
                        <td class="py-6 px-2 text-right flex items-center justify-end gap-3">
                            <button @click="showDetails = true; selectedRequest = {
                                type: '{{ $request->type }}',
                                id: 'RE-{{ str_pad($request->id, 5, '0', STR_PAD_LEFT) }}',
                                date: '{{ $request->scheduled_date->format('M d, Y') }}',
                                time: '{{ $request->scheduled_time ?? 'Standard Window' }}',
                                status: '{{ $request->status }}',
                                sector: '{{ $request->sector }}',
                                road: '{{ $request->road }}',
                                address: '{{ $request->address }}',
                                details: '{{ $request->details ?? 'No additional details' }}',
                                collector: '{{ $request->collector ? $request->collector->name : 'Pending allocation' }}'
                            }" class="text-slate-400 hover:text-[#19817E] font-black text-[11px] uppercase tracking-widest transition-colors">Details</button>
                            
                            @if($request->status == 'Pending' || $request->status == 'Scheduled')
                                <form action="{{ route('user.requests.cancel', $request->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this request?')">
                                    @csrf
                                    <button type="submit" class="text-red-300 hover:text-red-500 font-black text-[11px] uppercase tracking-widest transition-colors">Cancel</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 px-2 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-16 h-16 bg-slate-50 rounded-[24px] flex items-center justify-center text-slate-200">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                </div>
                                <p class="text-[11px] font-black text-slate-300 uppercase tracking-widest">No request logs found</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($requests->hasPages())
        <div class="pt-8 mt-4 border-t border-gray-50 flex justify-end">
            {{ $requests->links() }}
        </div>
        @endif
        <!-- Details Modal -->
        <div x-show="showDetails" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm"></div>
                </div>

                <span class="hidden sm:inline-block sm:align-middle sm:min-h-screen" aria-hidden="true">&#8203;</span>

                <div @click.away="showDetails = false" 
                     class="inline-block align-bottom bg-white rounded-[32px] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100">
                    <div class="bg-white p-8">
                        <div class="flex justify-between items-start mb-6">
                            <div>
                                <h3 class="text-xl font-black text-slate-800 tracking-tight" x-text="'Request #' + selectedRequest.id"></h3>
                                <p class="text-[11px] font-black text-teal-600 uppercase tracking-widest mt-1" x-text="selectedRequest.type"></p>
                            </div>
                            <button @click="showDetails = false" class="text-slate-300 hover:text-slate-500 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>

                        <div class="space-y-6">
                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-[10px] font-black text-slate-300 uppercase tracking-widest mb-1">Schedule Date</label>
                                    <p class="text-sm font-bold text-slate-600" x-text="selectedRequest.date"></p>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-slate-300 uppercase tracking-widest mb-1">Time Window</label>
                                    <p class="text-sm font-bold text-slate-600" x-text="selectedRequest.time"></p>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-[10px] font-black text-slate-300 uppercase tracking-widest mb-1">Sector / Road</label>
                                    <p class="text-sm font-bold text-slate-600" x-text="'Sector ' + selectedRequest.sector + ' - ' + selectedRequest.road"></p>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-slate-300 uppercase tracking-widest mb-1">Staff Assigned</label>
                                    <p class="text-sm font-bold text-slate-600" x-text="selectedRequest.collector"></p>
                                </div>
                            </div>

                            <div>
                                <label class="block text-[10px] font-black text-slate-300 uppercase tracking-widest mb-1">Pickup Address</label>
                                <p class="text-sm font-bold text-slate-600" x-text="selectedRequest.address"></p>
                            </div>

                            <div class="p-4 bg-slate-50 rounded-2xl">
                                <label class="block text-[10px] font-black text-slate-300 uppercase tracking-widest mb-1">Additional Details</label>
                                <p class="text-xs font-medium text-slate-500 leading-relaxed" x-text="selectedRequest.details"></p>
                            </div>
                        </div>

                        <div class="mt-8">
                            <button @click="showDetails = false" class="w-full bg-slate-800 text-white font-black py-4 rounded-2xl hover:bg-slate-900 transition shadow-lg shadow-slate-200 uppercase text-[12px] tracking-widest">Close Details</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
