@extends('layouts.user')

@section('title', 'My Subscription')

@section('content')
    <div class="mb-10 mt-2 flex justify-between items-end">
        <div>
            <h1 class="text-2xl font-black tracking-tight text-slate-800 mb-1">My Subscription</h1>
            <p class="text-[13px] font-medium text-slate-400">View details of your current subscription package.</p>
        </div>
    </div>

    <div class="bg-white rounded-[40px] border border-gray-50 shadow-sm shadow-gray-100/50 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-gray-100">Date</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-gray-100">Resident</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-gray-100">Sector</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-gray-100">Package</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-gray-100 text-center">Amount</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-gray-100 text-center">Status</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-gray-100">Valid Until</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-gray-100">Transaction ID</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-gray-100 text-right">Invoice</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @if($user->last_transaction_id)
                        @php
                            preg_match('/M(\d+)T/', $user->last_transaction_id, $matches);
                            $months = isset($matches[1]) ? (int) $matches[1] : 1;
                        @endphp
                        <tr class="hover:bg-gray-50/30 transition group">
                            <td class="px-6 py-5">
                                <div class="text-sm font-black text-gray-800 tracking-tight leading-none mb-1">{{ $user->updated_at ? $user->updated_at->format('M d, Y') : now()->format('M d, Y') }}</div>
                                <div class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{{ $user->updated_at ? $user->updated_at->format('h:i A') : now()->format('h:i A') }}</div>
                            </td>
                            <td class="px-6 py-5">
                                <div class="text-sm font-black text-gray-800 tracking-tight leading-none mb-1">{{ $user->name }}</div>
                                <div class="text-[10px] text-gray-500 font-medium">{{ $user->phone ?? 'No Phone' }}</div>
                            </td>
                            <td class="px-6 py-5">
                                <span class="bg-orange-50 text-orange-600 border border-orange-100 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest">
                                    {{ $user->sector ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-5">
                                <span class="bg-blue-50 text-blue-600 border border-blue-100 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest">
                                    {{ $months }} {{ $months === 1 ? 'Month' : 'Months' }}
                                </span>
                            </td>
                            <td class="px-6 py-5 text-center">
                                <span class="text-[11px] font-black text-gray-800">BDT {{ number_format($price, 2) }}</span>
                            </td>
                            <td class="px-6 py-5 text-center">
                                <span class="px-3 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-widest bg-emerald-50 text-emerald-600 border border-emerald-100">
                                    {{ $user->monthly_payment_status }}
                                </span>
                            </td>
                            <td class="px-6 py-5">
                                <div class="text-sm font-black text-gray-800 tracking-tight">
                                    {{ $validUntil ? $validUntil->format('M d, Y') : 'N/A' }}
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <div class="text-[11px] font-mono font-bold text-teal-600">
                                    {{ $user->last_transaction_id }}
                                </div>
                            </td>
                            <td class="px-6 py-5 text-right">
                                <a href="{{ route('user.subscription_receipt') }}"
                                   class="inline-flex items-center gap-1.5 bg-[#19817E] hover:bg-teal-700 text-white text-[10px] font-black px-4 py-2 rounded-xl transition shadow-md shadow-teal-700/10 uppercase tracking-widest">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Invoice
                                </a>
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="9" class="py-20 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-300">
                                    <svg class="w-12 h-12 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2H-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                    </svg>
                                    <p class="font-bold uppercase tracking-[2px]">No subscription history found</p>
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection
