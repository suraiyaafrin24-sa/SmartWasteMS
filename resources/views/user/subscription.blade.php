@extends('layouts.user')

@section('title', 'Subscription Package')

@section('content')
    @if(session('error') || request()->query('subscription_required'))
        <div class="mb-4 bg-amber-50 border border-amber-200 text-amber-700 px-4 py-3 rounded-xl flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                    </path>
                </svg>
                <span class="font-semibold text-sm">{{ session('error') ?? 'Please buy a subscription package before creating a collection request.' }}</span>
            </div>
        </div>
    @endif

    @php
        $packages = [
            [
                'months' => 1,
                'label' => 'Monthly',
                'duration' => '1 Month',
                'price' => 300,
                'accent' => 'text-[#19817E]',
                'button' => 'bg-[#19817E] hover:bg-[#146d6a]',
                'badge' => 'bg-[#DCF5F2] text-[#19817E]',
                'note' => 'Best for flexible monthly service.',
            ],
            [
                'months' => 6,
                'label' => '6 Months',
                'duration' => 'Half Year',
                'price' => 1800,
                'accent' => 'text-blue-500',
                'button' => 'bg-blue-500 hover:bg-blue-600',
                'badge' => 'bg-blue-50 text-blue-500',
                'note' => 'Pay once and stay covered for six months.',
            ],
            [
                'months' => 12,
                'label' => '1 Year',
                'duration' => 'Full Year',
                'price' => 3600,
                'accent' => 'text-amber-500',
                'button' => 'bg-amber-500 hover:bg-amber-600',
                'badge' => 'bg-amber-50 text-amber-500',
                'note' => 'Annual coverage for uninterrupted service.',
            ],
        ];
    @endphp

    @if(request()->query('payment') == 'success')
        <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-600 px-4 py-3 rounded-xl flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span class="font-semibold text-sm">Subscription payment successful. Transaction ID: {{ request()->query('tran_id') }}</span>
            </div>
        </div>
    @endif

    @if(request()->query('payment') == 'failed')
        <div class="mb-4 bg-rose-50 border border-rose-200 text-rose-600 px-4 py-3 rounded-xl flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                <span class="font-semibold text-sm">Payment failed. Please try again.</span>
            </div>
        </div>
    @endif

    @if(request()->query('payment') == 'cancelled')
        <div class="mb-4 bg-amber-50 border border-amber-200 text-amber-600 px-4 py-3 rounded-xl flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="font-semibold text-sm">Payment cancelled.</span>
            </div>
        </div>
    @endif

    <div class="mb-10 mt-2 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-2xl font-black tracking-tight text-slate-800 mb-1">Subscription Package</h1>
            <p class="text-[13px] font-medium text-slate-400">Choose a SmartBin package for regular household waste collection</p>
        </div>

        <div class="inline-flex items-center gap-2 self-start rounded-2xl px-4 py-2 {{ $isActive ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }}">
            <span class="h-2 w-2 rounded-full {{ $isActive ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
            <span class="text-[11px] font-black uppercase tracking-[0.18em]">{{ $isActive ? 'Active' : 'Inactive' }}</span>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        <div class="xl:col-span-2 bg-white rounded-[40px] p-8 md:p-10 border border-gray-50 shadow-sm shadow-gray-100/50">
            <div class="mb-8">
                <div class="text-[#19817E] font-black text-[11px] uppercase tracking-[0.2em] mb-4">Available Plans</div>
                <h2 class="text-3xl font-black text-slate-800 tracking-tight mb-3">SmartBin Resident Care</h2>
                <p class="text-sm font-medium text-slate-400 max-w-2xl leading-6">
                    Select the package that matches your home collection needs. Each payment extends your active subscription from the current valid date.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
                @foreach($packages as $package)
                    <div class="rounded-[32px] bg-slate-50 p-6 border border-slate-100 flex flex-col min-h-[360px]">
                        <div class="flex items-center justify-between gap-3 mb-8">
                            <span class="rounded-2xl px-3 py-2 text-[10px] font-black uppercase tracking-[0.18em] {{ $package['badge'] }}">
                                {{ $package['label'] }}
                            </span>
                            <div class="h-10 w-10 rounded-2xl bg-white flex items-center justify-center {{ $package['accent'] }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>

                        <div class="mb-6">
                            <h3 class="text-xl font-black text-slate-800 tracking-tight mb-2">{{ $package['duration'] }}</h3>
                            <p class="text-xs font-medium text-slate-400 leading-5">{{ $package['note'] }}</p>
                        </div>

                        <div class="mb-8">
                            <div class="text-[32px] font-black text-slate-800 leading-none tracking-tight flex items-baseline gap-2">
                                <span>BDT</span>
                                <span>{{ number_format($package['price']) }}</span>
                            </div>
                            <div class="text-[11px] font-bold text-slate-400 mt-2 uppercase tracking-[0.18em]">
                                {{ $package['months'] }} {{ $package['months'] === 1 ? 'Month' : 'Months' }}
                            </div>
                        </div>

                        <ul class="space-y-3 mb-8 text-xs font-bold text-slate-500">
                            <li class="flex items-center gap-2">
                                <span class="h-5 w-5 rounded-full bg-white flex items-center justify-center text-emerald-500">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </span>
                                Priority collection booking
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="h-5 w-5 rounded-full bg-white flex items-center justify-center text-emerald-500">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </span>
                                Digital payment record
                            </li>
                        </ul>

                        <form action="{{ route('sslc.pay') }}" method="POST" class="mt-auto">
                            @csrf
                            <input type="hidden" name="payment_type" value="subscription">
                            <input type="hidden" name="subscription_months" value="{{ $package['months'] }}">
                            <button type="submit"
                                class="w-full inline-flex items-center justify-center gap-3 rounded-2xl px-5 py-4 text-sm font-black text-white shadow-sm transition-all {{ $package['button'] }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a5 5 0 00-10 0v2m-2 0h14l-1 12H6L5 9z"></path>
                                </svg>
                                {{ $isActive ? 'Extend Plan' : 'Buy Package' }}
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="bg-white rounded-[40px] p-8 border border-gray-50 shadow-sm shadow-gray-100/50">
            <h2 class="text-xl font-black text-slate-800 tracking-tight mb-8">Current Status</h2>

            <div class="space-y-6">
                <div>
                    <div class="text-[10px] font-black text-slate-300 uppercase tracking-[0.2em] mb-2">Payment Status</div>
                    <div class="text-base font-black {{ $isActive ? 'text-emerald-600' : 'text-rose-600' }}">
                        {{ $isActive ? 'Paid' : 'Unpaid' }}
                    </div>
                </div>

                <div>
                    <div class="text-[10px] font-black text-slate-300 uppercase tracking-[0.2em] mb-2">Valid Until</div>
                    <div class="text-base font-black text-slate-800">
                        {{ $validUntil ? $validUntil->format('M d, Y') : 'Not active' }}
                    </div>
                </div>

                <div>
                    <div class="text-[10px] font-black text-slate-300 uppercase tracking-[0.2em] mb-2">Last Transaction</div>
                    <div class="text-sm font-bold text-slate-500 break-all">
                        {{ $user->last_transaction_id ?? 'N/A' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
