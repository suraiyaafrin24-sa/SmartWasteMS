@extends('layouts.admin')

@section('title', 'Subscription History')

@section('content')
    <div class="mb-8 mt-2 flex justify-between items-end">
        <div>
            <h1 class="text-[28px] font-black tracking-tight text-gray-900 mb-1">Subscription History</h1>
            <p class="text-sm font-medium text-gray-500">Monitor resident subscription packages and transaction records.</p>
        </div>

    </div>

    <div class="bg-white rounded-[32px] p-8 border border-gray-100 shadow-sm flex items-center justify-between mb-8">
        <div>
            <p class="text-[11px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Subscription Revenue</p>
            <h3 class="text-3xl font-black text-emerald-600">BDT {{ number_format($totalSubscriptionRevenue, 2) }}</h3>
        </div>
        <div class="w-16 h-16 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-500">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
        </div>
    </div>

    <div class="bg-white rounded-[32px] border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100">Date</th>
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100">Resident</th>
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100">Sector</th>
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100">Package</th>
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 text-center">Amount</th>
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 text-center">Status</th>
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100">Valid Until</th>
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 text-right">Transaction ID</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($subscriptions as $subscription)
                        @php
                            preg_match('/M(\d+)T/', $subscription->last_transaction_id, $matches);
                            $months = isset($matches[1]) ? (int) $matches[1] : 1;
                            $amount = match ($months) {
                                6 => 1800,
                                12 => 3600,
                                default => 300,
                            };
                        @endphp
                        <tr class="hover:bg-gray-50/30 transition group">
                            <td class="px-6 py-5">
                                <div class="text-sm font-black text-gray-800 tracking-tight leading-none mb-1">{{ $subscription->updated_at->format('M d, Y') }}</div>
                                <div class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{{ $subscription->updated_at->format('h:i A') }}</div>
                            </td>
                            <td class="px-6 py-5">
                                <div class="text-sm font-black text-gray-800 tracking-tight leading-none mb-1">{{ $subscription->name }}</div>
                                <div class="text-[10px] text-gray-500 font-medium">{{ $subscription->phone ?? 'No Phone' }}</div>
                            </td>
                            <td class="px-6 py-5">
                                <span class="bg-orange-50 text-orange-600 border border-orange-100 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest">
                                    {{ $subscription->sector ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-5">
                                <span class="bg-blue-50 text-blue-600 border border-blue-100 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest">
                                    {{ $months }} {{ $months === 1 ? 'Month' : 'Months' }}
                                </span>
                            </td>
                            <td class="px-6 py-5 text-center">
                                <span class="text-[11px] font-black text-gray-800">BDT {{ number_format($amount, 2) }}</span>
                            </td>
                            <td class="px-6 py-5 text-center">
                                <span class="px-3 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-widest bg-emerald-50 text-emerald-600 border border-emerald-100">
                                    {{ $subscription->monthly_payment_status }}
                                </span>
                            </td>
                            <td class="px-6 py-5">
                                <div class="text-sm font-black text-gray-800 tracking-tight">
                                    {{ $subscription->subscription_valid_until ? $subscription->subscription_valid_until->format('M d, Y') : 'N/A' }}
                                </div>
                            </td>
                            <td class="px-6 py-5 text-right">
                                <div class="text-[11px] font-mono font-bold text-primary">
                                    {{ $subscription->last_transaction_id }}
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-20 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-300">
                                    <svg class="w-12 h-12 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                    </svg>
                                    <p class="font-bold uppercase tracking-[2px]">No subscription history found</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($subscriptions->hasPages())
            <div class="px-6 py-4 border-t border-gray-50">
                {{ $subscriptions->links() }}
            </div>
        @endif
    </div>
@endsection
