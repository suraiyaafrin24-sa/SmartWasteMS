@extends('layouts.user')

@section('title', 'My Subscription')

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Page Title & Header -->
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black tracking-tight text-slate-800 mb-1">My Subscription</h1>
                <p class="text-[13px] font-medium text-slate-400">View details of your active subscription package and download your invoice.</p>
            </div>
            
            <div class="inline-flex items-center gap-2 self-start sm:self-auto rounded-full px-4 py-1.5 {{ $isActive ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-rose-50 text-rose-600 border border-rose-100' }}">
                <span class="h-2 w-2 rounded-full {{ $isActive ? 'bg-emerald-500 animate-pulse' : 'bg-rose-500' }}"></span>
                <span class="text-[11px] font-black uppercase tracking-widest">{{ $isActive ? 'Active Subscription' : 'Inactive' }}</span>
            </div>
        </div>

        @if($user->last_transaction_id)
            @php
                $months = 1;
                if ($user->last_transaction_id && preg_match('/M(\d+)T/', $user->last_transaction_id, $matches)) {
                    $months = (int) $matches[1];
                }
            @endphp

            <!-- Main Subscription Card -->
            <div class="bg-white rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/40 overflow-hidden transition-all">
                <!-- Card Header Banner -->
                <div class="bg-gradient-to-r from-[#146664] via-[#19817E] to-[#209c98] p-6 sm:p-8 text-white relative overflow-hidden">
                    <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
                    
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 relative z-10">
                        <div>
                            <div class="inline-flex items-center gap-1.5 text-teal-100 text-[10px] font-black tracking-widest uppercase mb-2 bg-white/10 px-3 py-1 rounded-full backdrop-blur-sm">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                </svg>
                                SmartBin Household Waste Coverage
                            </div>
                            <h2 class="text-2xl sm:text-3xl font-black tracking-tight">{{ $planName != 'N/A' ? $planName : ($months . ' Month' . ($months > 1 ? 's' : '') . ' Package') }}</h2>

                        </div>
                        <div class="sm:text-right bg-white/10 sm:bg-transparent p-4 sm:p-0 rounded-2xl backdrop-blur-sm sm:backdrop-blur-none">
                            <div class="text-xs font-bold text-teal-100 uppercase tracking-wider">Total Package Amount</div>
                            <div class="text-3xl font-black tracking-tight text-white mt-0.5">
                                BDT {{ number_format($price > 0 ? $price : ($months * 300), 2) }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Body / Details Grid -->
                <div class="p-6 sm:p-8">
                    <h3 class="text-xs font-black uppercase tracking-widest text-slate-400 mb-6">Subscription Details</h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Resident Name -->
                        <div class="bg-slate-50/70 p-4 rounded-2xl border border-slate-100">
                            <div class="text-[10px] font-black uppercase tracking-wider text-slate-400 mb-1">Resident Name</div>
                            <div class="text-sm font-bold text-slate-800">{{ $user->name }}</div>
                            <div class="text-xs text-slate-500 font-medium mt-0.5">{{ $user->phone ?? 'No phone' }}</div>
                        </div>

                        <!-- Sector -->
                        <div class="bg-slate-50/70 p-4 rounded-2xl border border-slate-100">
                            <div class="text-[10px] font-black uppercase tracking-wider text-slate-400 mb-1">Sector / Area</div>
                            <div class="inline-flex items-center gap-1.5 mt-0.5">
                                <span class="bg-orange-50 text-orange-600 border border-orange-100 px-2.5 py-1 rounded-lg text-xs font-black uppercase tracking-wider">
                                    {{ $user->sector ?? 'N/A' }}
                                </span>
                            </div>
                        </div>

                        <!-- Package Duration -->
                        <div class="bg-slate-50/70 p-4 rounded-2xl border border-slate-100">
                            <div class="text-[10px] font-black uppercase tracking-wider text-slate-400 mb-1">Package Duration</div>
                            <div class="inline-flex items-center gap-1.5 mt-0.5">
                                <span class="bg-blue-50 text-blue-600 border border-blue-100 px-2.5 py-1 rounded-lg text-xs font-black uppercase tracking-wider">
                                    {{ $months }} {{ $months === 1 ? 'Month' : 'Months' }}
                                </span>
                            </div>
                        </div>

                        <!-- Payment Status -->
                        <div class="bg-slate-50/70 p-4 rounded-2xl border border-slate-100">
                            <div class="text-[10px] font-black uppercase tracking-wider text-slate-400 mb-1">Payment Status</div>
                            <div class="inline-flex items-center gap-1.5 mt-0.5">
                                <span class="bg-emerald-50 text-emerald-600 border border-emerald-100 px-2.5 py-1 rounded-lg text-xs font-black uppercase tracking-wider">
                                    {{ $user->monthly_payment_status ?? 'Paid' }}
                                </span>
                            </div>
                        </div>

                        <!-- Valid Until -->
                        <div class="bg-slate-50/70 p-4 rounded-2xl border border-slate-100">
                            <div class="text-[10px] font-black uppercase tracking-wider text-slate-400 mb-1">Valid Until</div>
                            <div class="text-sm font-bold text-slate-800">{{ $validUntil ? $validUntil->format('M d, Y') : 'N/A' }}</div>
                        </div>

                        <!-- Payment Date -->
                        <div class="bg-slate-50/70 p-4 rounded-2xl border border-slate-100">
                            <div class="text-[10px] font-black uppercase tracking-wider text-slate-400 mb-1">Payment Date & Time</div>
                            <div class="text-sm font-bold text-slate-800">{{ $user->updated_at ? $user->updated_at->format('M d, Y') : 'N/A' }}</div>
                            <div class="text-xs text-slate-400 font-medium mt-0.5">{{ $user->updated_at ? $user->updated_at->format('h:i A') : '' }}</div>
                        </div>
                    </div>

                    <!-- Transaction Reference Line -->
                    <div class="mt-6 pt-6 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div class="flex items-center gap-2 text-xs text-slate-500 font-medium">
                            <span class="text-slate-400 font-bold uppercase tracking-wider text-[10px]">Transaction ID:</span>
                            <code class="bg-slate-100 text-teal-700 px-2.5 py-1 rounded-md font-mono text-xs font-bold">{{ $user->last_transaction_id }}</code>
                        </div>
                        <div class="text-xs text-slate-400 font-medium">
                            Payment Method: <span class="font-bold text-slate-700">SSLCommerz Digital Payment</span>
                        </div>
                    </div>
                </div>

                <!-- Card Footer / Invoice Action -->
                <div class="bg-slate-50/80 px-6 py-5 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-center gap-2 text-xs text-slate-500">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Need an official payment receipt for your records?
                    </div>

                    <a href="{{ route('user.subscription_receipt') }}"
                       class="inline-flex items-center justify-center gap-2 bg-[#19817E] hover:bg-teal-700 text-white font-bold text-xs px-6 py-3 rounded-xl transition shadow-lg shadow-teal-700/20 uppercase tracking-wider">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Download Invoice
                    </a>
                </div>
            </div>
        @else
            <!-- Empty State Card -->
            <div class="bg-white rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/40 p-12 text-center">
                <div class="w-16 h-16 bg-teal-50 text-[#19817E] rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 01-2-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-black text-slate-800 mb-1">No Active Subscription</h3>
                <p class="text-sm font-medium text-slate-400 max-w-md mx-auto mb-6">You currently do not have an active waste collection subscription. Choose a package to start receiving waste collection services.</p>
                <a href="{{ route('user.subscription') }}"
                   class="inline-flex items-center gap-2 bg-[#19817E] hover:bg-teal-700 text-white font-bold text-xs px-6 py-3 rounded-xl transition shadow-lg shadow-teal-700/20 uppercase tracking-wider">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Subscribe Now
                </a>
            </div>
        @endif
    </div>
@endsection

