@extends('layouts.waste_collector')

@section('title', 'Payment Collection')

@section('content')
    <div class="mb-8 mt-2">
        <h1 class="text-[28px] font-black tracking-tight text-gray-900 mb-1">Payment Tracking</h1>
        <p class="text-sm font-medium text-gray-500">Monitor payment status for your assigned collection requests.</p>
    </div>

    <div class="bg-white rounded-[32px] border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100">Date</th>
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100">Resident</th>
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100">Sector</th>
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 text-center">Amount</th>
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 text-center">Payment Status</th>
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 text-right">Transaction ID</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($payments as $payment)
                        <tr class="hover:bg-gray-50/30 transition group">
                            <td class="px-6 py-5">
                                <div class="text-sm font-black text-gray-800 tracking-tight leading-none mb-1">{{ $payment->created_at->format('M d, Y') }}</div>
                                <div class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{{ $payment->created_at->format('h:i A') }}</div>
                            </td>
                            <td class="px-6 py-5">
                                <div class="text-sm font-black text-gray-800 tracking-tight leading-none mb-1">{{ $payment->user->name }}</div>
                                <div class="text-[10px] text-gray-500 font-medium">{{ $payment->user->phone ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-5">
                                <span class="bg-orange-50 text-orange-600 border border-orange-100 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest">
                                    {{ $payment->sector ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-5 text-center">
                                @if($payment->payment_status == 'Paid')
                                    <span class="text-[11px] font-black text-gray-800">BDT 20.00</span>
                                @else
                                    <span class="text-[11px] font-black text-gray-300">---</span>
                                @endif
                            </td>
                            <td class="px-6 py-5 text-center">
                                @if($payment->payment_status == 'Paid')
                                    <span class="px-3 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-widest bg-emerald-50 text-emerald-600">
                                        PAID
                                    </span>
                                @else
                                    <span class="px-3 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-widest bg-amber-50 text-amber-600">
                                        UNPAID
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-5 text-right">
                                <div class="text-[11px] font-mono font-bold text-gray-400">
                                    {{ $payment->transaction_id ?? '---' }}
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-20 text-center text-gray-400 font-bold uppercase tracking-[2px]">No payment records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($payments->hasPages())
            <div class="px-6 py-4 border-t border-gray-50">
                {{ $payments->links() }}
            </div>
        @endif
    </div>
@endsection
