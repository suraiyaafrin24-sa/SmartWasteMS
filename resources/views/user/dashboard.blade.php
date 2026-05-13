@extends('layouts.user')

@section('title', 'My Dashboard')

@section('content')
    @if(session('success'))
        <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-600 px-4 py-3 rounded-xl flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span class="font-semibold text-sm">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(request()->query('payment') == 'success')
        <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-600 px-4 py-3 rounded-xl flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span class="font-semibold text-sm">Payment Successful! Transaction ID: {{ request()->query('tran_id') }}</span>
            </div>
        </div>
    @endif

    @if(request()->query('payment') == 'failed')
        <div class="mb-4 bg-rose-50 border border-rose-200 text-rose-600 px-4 py-3 rounded-xl flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                <span class="font-semibold text-sm">Payment Failed. Please try again.</span>
            </div>
        </div>
    @endif

    @if(request()->query('payment') == 'cancelled')
        <div class="mb-4 bg-amber-50 border border-amber-200 text-amber-600 px-4 py-3 rounded-xl flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="font-semibold text-sm">Payment Cancelled.</span>
            </div>
        </div>
    @endif

    <div class="mb-10 mt-2 flex justify-between items-end">
        <div>
            <h1 class="text-2xl font-black tracking-tight text-slate-800 mb-1">My Dashboard</h1>
            <p class="text-[13px] font-medium text-slate-400">Track your waste collection requests and history.</p>
        </div>

    </div>

    <!-- User Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
        <!-- Total Requests Card -->
        <div
            class="bg-white rounded-[40px] p-8 border border-gray-50 flex flex-col justify-between h-48 relative overflow-hidden group shadow-sm shadow-gray-100/50">
            <div
                class="absolute -right-4 -top-4 opacity-[0.05] group-hover:scale-110 group-hover:opacity-[0.08] transition duration-700 ease-in-out pointer-events-none">
                <svg class="w-32 h-32 text-blue-500" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M19 3h-4.18C14.4 1.84 13.3 1 12 1s-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm2 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z" />
                </svg>
            </div>
            <div>
                <div class="text-blue-500 font-bold text-[13px] mb-2 flex items-center gap-2 leading-none">
                    <div class="w-1.5 h-1.5 rounded-full bg-blue-500"></div>
                    Total Requests
                </div>
                <div class="text-[42px] font-black text-slate-800 leading-none tracking-tight">{{ $totalRequests }}</div>
            </div>
            <p class="text-[12px] text-slate-400 font-medium tracking-wide">Since joining SmartBin</p>
        </div>

        <!-- Pending Pickup Card -->
        <div
            class="bg-white rounded-[40px] p-8 border border-gray-50 flex flex-col justify-between h-48 relative overflow-hidden group shadow-sm shadow-gray-100/50">
            <div
                class="absolute -right-4 -top-4 opacity-[0.05] group-hover:scale-110 group-hover:opacity-[0.08] transition duration-700 ease-in-out pointer-events-none">
                <svg class="w-32 h-32 text-amber-500" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 14.5c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zm1.5-4.5h-3V7h3v5z" />
                </svg>
            </div>
            <div>
                <div class="text-amber-500 font-bold text-[13px] mb-2 flex items-center gap-2 leading-none">
                    <div class="w-1.5 h-1.5 rounded-full bg-amber-500"></div>
                    Pending Pickup
                </div>
                <div class="text-[42px] font-black text-slate-800 leading-none tracking-tight">{{ $pendingPickups }}</div>
            </div>
            <p class="text-[12px] text-slate-400 font-medium tracking-wide">Assigned for tomorrow 10 AM</p>
        </div>

        <!-- Completed Card -->
        <div
            class="bg-white rounded-[40px] p-8 border border-gray-50 flex flex-col justify-between h-48 relative overflow-hidden group shadow-sm shadow-gray-100/50">
            <div
                class="absolute -right-4 -top-4 opacity-[0.05] group-hover:scale-110 group-hover:opacity-[0.08] transition duration-700 ease-in-out pointer-events-none">
                <svg class="w-32 h-32 text-emerald-500" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4L9 16.2z" />
                </svg>
            </div>
            <div>
                <div class="text-emerald-500 font-bold text-[13px] mb-2 flex items-center gap-2 leading-none">
                    <div class="w-1.5 h-1.5 rounded-full bg-emerald-500"></div>
                    Completed
                </div>
                <div class="text-[42px] font-black text-slate-800 leading-none tracking-tight">{{ $completedRequestsCount }}
                </div>
            </div>
            <p class="text-[12px] text-slate-400 font-medium tracking-wide">Eco-contribution: {{ $ecoContribution ?? 0 }}kg
                saved</p>
        </div>
    </div>

    <!-- Recent Requests Table -->
    <div class="bg-white rounded-[40px] p-10 border border-gray-50 shadow-sm shadow-gray-100/50">
        <div class="flex justify-between items-center mb-10">
            <h2 class="text-xl font-black text-slate-800 tracking-tight leading-none">Recent Requests</h2>
            <a href="{{ route('user.history') }}"
                class="text-[#19817E] text-[12px] font-black hover:underline uppercase tracking-[0.2em]">View All
                History</a>
        </div>

        <div class="overflow-x-auto overflow-y-hidden">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead>
                    <tr
                        class="text-slate-300 font-black border-b border-gray-50 uppercase text-[10px] tracking-[0.2em] leading-none">
                        <th class="pb-6 px-2">Type</th>
                        <th class="pb-6 px-2">Scheduled Date</th>
                        <th class="pb-6 px-2 text-center">Status</th>
                        <th class="pb-6 px-2 text-center">Payment</th>
                        <th class="pb-6 px-2 text-center">Receipt</th>
                        <th class="pb-6 px-2">Collector</th>
                        <th class="pb-6 px-2 text-right">Details</th>
                    </tr>
                </thead>
                <tbody class="text-slate-700 divide-y divide-gray-50/50">
                    @forelse($recentRequests as $request)
                        <tr class="hover:bg-gray-50/50 transition-all duration-300 group">
                            <td class="py-6 px-2 font-black text-slate-800">{{ $request->type }}</td>
                            <td class="py-6 px-2 font-bold text-slate-400">{{ $request->scheduled_date->format('M d, Y') }}</td>
                            <td class="py-6 px-2 text-center">
                                @if($request->status == 'Completed' || $request->status == 'Collected')
                                    <span
                                        class="px-3 py-1.5 rounded-xl text-[10px] font-black bg-emerald-50 text-emerald-600 tracking-widest uppercase">Collected</span>
                                @elseif($request->status == 'Scheduled')
                                    <span
                                        class="px-3 py-1.5 rounded-xl text-[10px] font-black bg-amber-50 text-amber-600 tracking-widest uppercase">Scheduled</span>
                                @else
                                    <span
                                        class="px-3 py-1.5 rounded-xl text-[10px] font-black bg-gray-50 text-gray-400 tracking-widest uppercase">{{ $request->status }}</span>
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
                            <td class="py-6 px-2 font-bold text-slate-500">
                                {{ $request->collector ? $request->collector->name : 'Unassigned' }}</td>
                            <td class="py-6 px-2 text-right">
                                <button type="button" data-type="{{ $request->type }}"
                                    data-date="{{ $request->scheduled_date->format('M d, Y') }}"
                                    data-status="{{ $request->status }}"
                                    data-collector="{{ $request->collector ? $request->collector->name : 'Waiting for assignment' }}"
                                    data-phone="{{ $request->collector ? $request->collector->phone : 'N/A' }}"
                                    data-details="{{ $request->details ?? 'No special instructions provided.' }}"
                                    data-address="{{ $request->address }}" onclick="openRequestModal(this)"
                                    class="text-[#19817E] hover:text-teal-700 text-[11px] font-black transition-colors uppercase tracking-widest">
                                    View
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7"
                                class="py-6 px-2 text-center text-slate-400 italic font-black uppercase text-[10px] tracking-widest">
                                No recent requests found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Request Details Modal -->
    <div id="detailsModal"
        class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-[9999] hidden flex items-center justify-center p-6">
        <div class="bg-white rounded-[24px] w-full max-w-sm overflow-hidden shadow-2xl transform transition-all">
            <div class="px-4 py-2 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-base font-black text-gray-900 tracking-tight">Request Details</h3>
                <button onclick="closeDetailsModal()" class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
            <div class="p-4 space-y-3">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Status</p>
                        <span id="modalStatus"
                            class="px-2 py-0.5 rounded text-[9px] font-black tracking-wide uppercase"></span>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Scheduled Date</p>
                        <p id="modalDate" class="text-xs font-bold text-gray-800"></p>
                    </div>
                </div>

                <div class="p-3 bg-teal-50/50 rounded-xl border border-teal-100/50 flex justify-between">
                    <div>
                        <p class="text-[9px] font-black text-teal-600 uppercase tracking-widest mb-0.5">Assigned Collector
                        </p>
                        <p id="modalCollector" class="text-xs font-bold text-gray-800"></p>
                    </div>
                    <div class="text-right">
                        <p class="text-[9px] font-black text-teal-600 uppercase tracking-widest mb-0.5">Contact</p>
                        <p id="modalCollectorPhone" class="text-xs font-bold text-gray-800"></p>
                    </div>
                </div>

                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Pickup Address</p>
                    <p id="modalAddress" class="text-xs font-medium text-gray-600 leading-tight"></p>
                </div>

                <div class="p-3 bg-amber-50/50 rounded-xl border border-amber-100/50">
                    <p class="text-[9px] font-black text-amber-600 uppercase tracking-widest mb-1">My Instructions</p>
                    <p id="modalDetails" class="text-[11px] font-medium text-amber-800 leading-normal"></p>
                </div>
            </div>
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end">
                <button onclick="closeDetailsModal()"
                    class="px-5 py-2 bg-gray-900 text-white text-[10px] font-black uppercase tracking-widest rounded-lg hover:bg-black transition">
                    Close
                </button>
            </div>
        </div>
    </div>

    <!-- Post-Submission Payment Modal -->
    @if(session('show_payment_modal'))
    <div id="paymentPromptModal" class="fixed inset-0 bg-slate-900/80 backdrop-blur-md z-[10000] flex items-center justify-center p-6">
        <div class="bg-white rounded-[40px] w-full max-w-lg overflow-hidden shadow-2xl transform animate-in zoom-in duration-300">
            <div class="relative h-48 bg-gradient-to-br from-[#19817E] to-teal-800 flex items-center justify-center overflow-hidden">
                <div class="absolute inset-0 opacity-10">
                    <svg class="w-full h-full" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.41 16.09V20h-2.82v-1.91c-1.39-.23-2.67-.89-3.72-1.94l1.32-1.32c.71.71 1.54 1.15 2.4 1.34v-3.08l-2.02-.67c-1.42-.47-2.38-1.54-2.38-2.92 0-1.12.63-2.13 1.67-2.69V5h2.82v1.91c1.17.19 2.21.72 3.09 1.48l-1.32 1.32c-.52-.45-1.11-.75-1.77-.91v3l2.02.67c1.42.47 2.38 1.54 2.38 2.92 0 1.14-.64 2.16-1.8 2.71zM10.59 9.87c0 .4.27.7.77.87v-1.7c-.45.14-.77.44-.77.83zm2.82 4.26c0-.4-.27-.7-.77-.87v1.7c.45-.14.77-.44.77-.83z"/></svg>
                </div>
                <div class="relative z-10 text-center">
                    <div class="w-20 h-20 bg-white/20 backdrop-blur-xl rounded-full flex items-center justify-center mx-auto mb-4 border border-white/30">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <h3 class="text-2xl font-black text-white tracking-tight">Request Submitted!</h3>
                </div>
            </div>
            <div class="p-10 text-center">
                <p class="text-slate-500 font-medium mb-8 leading-relaxed">Your collection request has been received. Would you like to pay your service fee now for a smoother experience?</p>
                
                <div class="bg-slate-50 rounded-3xl p-6 mb-8 flex justify-between items-center border border-slate-100">
                    <div class="text-left">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Service Fee</p>
                        <p class="text-2xl font-black text-slate-800">৳500.00</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <img src="https://securepay.sslcommerz.com/gw/asset/img/sslcommerz-logo.png" class="h-6 opacity-50 grayscale" alt="SSLCommerz">
                    </div>
                </div>

                <div class="flex flex-col gap-3">
                    <form action="{{ route('sslc.pay') }}" method="POST">
                        @csrf
                        <input type="hidden" name="amount" value="500">
                        <input type="hidden" name="request_id" value="{{ session('request_id') }}">
                        <button type="submit" class="w-full bg-[#19817E] text-white py-5 rounded-2xl font-black text-sm uppercase tracking-[0.2em] hover:bg-teal-700 transition shadow-xl shadow-teal-900/10 active:scale-[0.98]">
                            Pay Now via SSLCommerz
                        </button>
                    </form>
                    <button onclick="closePaymentModal()" class="w-full py-4 text-slate-400 font-black text-[11px] uppercase tracking-widest hover:text-slate-600 transition">
                        Pay Later from Dashboard
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script>
        function closePaymentModal() {
            document.getElementById('paymentPromptModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
        document.body.style.overflow = 'hidden';
    </script>
    @endif

    <script>
        function openRequestModal(btn) {
            const status = btn.getAttribute('data-status');
            const statusEl = document.getElementById('modalStatus');
            statusEl.innerText = status;

            // Status styling logic
            statusEl.className = 'px-2.5 py-1 rounded-md text-[10px] font-black tracking-wide uppercase ';
            if (status === 'Completed' || status === 'Collected') {
                statusEl.className += 'bg-emerald-100 text-emerald-600';
            } else if (status === 'Scheduled') {
                statusEl.className += 'bg-amber-100 text-amber-600';
            } else {
                statusEl.className += 'bg-gray-100 text-gray-600';
            }

            document.getElementById('modalDate').innerText = btn.getAttribute('data-date');
            document.getElementById('modalCollector').innerText = btn.getAttribute('data-collector');
            document.getElementById('modalCollectorPhone').innerText = btn.getAttribute('data-phone');
            document.getElementById('modalAddress').innerText = btn.getAttribute('data-address');
            document.getElementById('modalDetails').innerText = btn.getAttribute('data-details');

            const modal = document.getElementById('detailsModal');
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeDetailsModal() {
            const modal = document.getElementById('detailsModal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        window.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeDetailsModal();
        });
    </script>
@endsection