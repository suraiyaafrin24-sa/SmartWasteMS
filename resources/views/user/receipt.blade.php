<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt - SmartBin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Inter', sans-serif; 
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        @media print {
            .no-print { display: none !important; }
            body { 
                background: white !important; 
                padding: 0 !important;
                margin: 0 !important;
            }
            .max-w-2xl { 
                max-width: 100% !important; 
                width: 100% !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .receipt-card { 
                border: none !important; 
                box-shadow: none !important; 
                border-radius: 0 !important;
                width: 100% !important;
            }
            @page {
                margin: 0.5cm;
            }
        }
    </style>
</head>
<body class="bg-slate-50 min-h-screen py-12 px-4">
    <div class="max-w-2xl mx-auto">
        <div class="no-print mb-8 flex justify-between items-center">
            <a href="{{ route('user.dashboard') }}" class="text-slate-500 hover:text-slate-800 flex items-center gap-2 font-bold text-sm transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Dashboard
            </a>
            <div class="flex gap-3">
                <button onclick="downloadPDF()" class="bg-slate-800 text-white px-6 py-2.5 rounded-xl font-black text-sm uppercase tracking-widest hover:bg-black transition shadow-lg shadow-slate-900/10 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Download PDF
                </button>
                <button onclick="window.print()" class="bg-[#19817E] text-white px-6 py-2.5 rounded-xl font-black text-sm uppercase tracking-widest hover:bg-teal-700 transition shadow-lg shadow-teal-900/10 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Print
                </button>
            </div>
        </div>

        <div class="bg-white rounded-[40px] shadow-2xl shadow-slate-200/50 border border-slate-100 overflow-hidden receipt-card">
            <!-- Header -->
            <div class="bg-slate-900 p-10 text-white flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-black tracking-tighter mb-1">SMART<span class="text-teal-400">BIN</span></h1>
                    <p class="text-slate-400 text-[10px] font-black uppercase tracking-[0.3em]">Smart Waste Management</p>
                </div>
                <div class="text-right">
                    <div class="bg-teal-500/10 text-teal-400 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest border border-teal-500/20 mb-2 inline-block">
                        Payment Successful
                    </div>
                    <p class="text-slate-400 text-xs font-medium">{{ now()->format('F d, Y') }}</p>
                </div>
            </div>

            <!-- Content -->
            <div class="p-10">
                <div class="grid grid-cols-2 gap-10 mb-12">
                    <div>
                        <p class="text-[10px] font-black text-[#19817E] uppercase tracking-[0.2em] mb-4 border-b border-teal-50 pb-2">Customer Details</p>
                        <div class="space-y-3">
                            <div>
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Customer Name</p>
                                <p class="text-sm font-black text-slate-800">{{ $request->user->name }}</p>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Email Address</p>
                                <p class="text-sm font-bold text-slate-600">{{ $request->user->email }}</p>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Contact Number</p>
                                <p class="text-sm font-bold text-slate-600">{{ $request->user->phone ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Sector</p>
                                <p class="text-sm font-bold text-slate-600 leading-relaxed max-w-[240px]">{{ $request->sector }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] font-black text-[#19817E] uppercase tracking-[0.2em] mb-4 border-b border-teal-50 pb-2 inline-block ml-auto">Transaction Info</p>
                        <div class="space-y-3">
                            <div>
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Transaction ID</p>
                                <p class="text-sm font-black text-slate-800">{{ $request->transaction_id }}</p>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Request Number</p>
                                <p class="text-sm font-black text-slate-800">#CR-{{ str_pad($request->id, 6, '0', STR_PAD_LEFT) }}</p>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Payment Date</p>
                                <p class="text-sm font-black text-slate-800">{{ $request->updated_at->format('M d, Y h:i A') }}</p>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Payment Status</p>
                                <span class="text-[10px] font-black text-emerald-600 bg-emerald-50 px-3 py-1 rounded-full uppercase tracking-widest border border-emerald-100">Verified</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="border-t border-b border-slate-100 py-8 mb-8">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                <th class="pb-4">Description</th>
                                <th class="pb-4 text-center">Service Type</th>
                                <th class="pb-4 text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="text-slate-700">
                            <tr>
                                <td class="py-2">
                                    <p class="font-black text-slate-800">Waste Collection Service Fee</p>
                                    <p class="text-xs text-slate-500">Scheduled for {{ $request->scheduled_date->format('M d, Y') }} ({{ $request->shift }})</p>
                                </td>
                                <td class="py-2 text-center text-sm font-bold">{{ $request->type }}</td>
                                <td class="py-2 text-right font-black text-slate-800">৳20.00</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Totals -->
                <div class="flex justify-end mb-12">
                    <div class="w-64 space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-400 font-bold">Subtotal</span>
                            <span class="text-slate-800 font-black">৳20.00</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-400 font-bold">VAT (0%)</span>
                            <span class="text-slate-800 font-black">৳0.00</span>
                        </div>
                        <div class="pt-3 border-t border-slate-100 flex justify-between items-center">
                            <span class="text-slate-800 font-black uppercase text-xs tracking-widest">Total Amount</span>
                            <span class="text-2xl font-black text-[#19817E]">৳20.00</span>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="bg-slate-50 rounded-3xl p-8 text-center border border-dashed border-slate-200">
                    <p class="text-slate-500 text-xs font-medium leading-relaxed">
                        This is a computer-generated receipt and does not require a physical signature. 
                        Thank you for your eco-friendly contribution to SmartBin.
                    </p>
                </div>
            </div>

            <div class="bg-slate-50 px-10 py-4 flex justify-center gap-6">
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">www.smartbin.com</p>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">support@smartbin.com</p>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">+880 1700-000000</p>
            </div>
        </div>
        
        <p class="mt-8 text-center text-slate-400 text-[10px] font-black uppercase tracking-[0.2em]">
            © {{ date('Y') }} SmartBin Waste Management System. All rights reserved.
        </p>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        function downloadPDF() {
            const element = document.querySelector('.receipt-card');
            const opt = {
                margin: 10,
                filename: 'SmartBin-Receipt-{{ $request->transaction_id }}.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2, useCORS: true, letterRendering: true },
                jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
            };

            // New Promise-based usage:
            html2pdf().set(opt).from(element).save();
        }
    </script>
</body>
</html>
