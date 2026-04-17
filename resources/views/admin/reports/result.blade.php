@extends('layouts.admin')

@section('title', 'Generated Activity Report')

@section('content')
    <div class="mb-8 mt-2 flex justify-between items-center no-print">
        <div>
            <h1 class="text-[28px] font-black tracking-tight text-gray-900 mb-1">Activity Synthesis</h1>
            <p class="text-sm font-medium text-gray-500">Summary for {{ $actor->name }} ({{ $start_date }} to {{ $end_date }})</p>
        </div>
        <div class="flex gap-4">
            <a href="{{ route('admin.reports') }}" class="bg-gray-100 text-gray-700 text-xs font-bold py-3 px-6 rounded-xl hover:bg-gray-200 transition">Back to Generator</a>
            <button onclick="window.print()" class="bg-[#19817E] text-white text-xs font-bold py-3 px-8 rounded-xl shadow-lg shadow-teal-500/20 hover:bg-teal-700 transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Print / Export PDF
            </button>
        </div>
    </div>

    <!-- Printable Report Container -->
    <div id="printableReport" class="bg-white rounded-[32px] p-10 border border-gray-100 shadow-xl print:shadow-none print:border-none print:p-0">
        <!-- Letterhead (Visible when printing) -->
        <div class="hidden print:flex justify-between items-center border-b-2 border-gray-900 pb-8 mb-10">
            <div>
                <h1 class="text-2xl font-black text-gray-900">UTTARA WASTE MANAGEMENT</h1>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">Strategic Operations Intelligence</p>
            </div>
            <div class="text-right">
                <p class="text-xs font-bold text-gray-700">Official Activity Report</p>
                <p class="text-[10px] text-gray-400 font-medium">Generated: {{ date('F d, Y') }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mb-10">
            <div class="p-6 bg-gray-50 rounded-[28px] border border-gray-100 overflow-hidden relative">
                <div class="absolute top-0 right-0 w-20 h-20 bg-white rounded-bl-full opacity-50"></div>
                <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">Target Information</h3>
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-[#19817E] rounded-2xl flex items-center justify-center text-white font-black text-xl shadow-lg">
                        {{ strtoupper(substr($actor->name, 0, 1)) }}
                    </div>
                    <div>
                        <h4 class="text-lg font-black text-gray-900 leading-none">{{ $actor->name }}</h4>
                        <p class="text-xs font-bold text-teal-600 mt-1 uppercase tracking-widest">{{ $actor->role == 'waste_collector' ? 'Waste Collector' : 'Resident' }}</p>
                        <p class="text-[10px] text-gray-500 font-medium mt-1">{{ $actor->email }}</p>
                    </div>
                </div>
            </div>

            <div class="p-6 bg-gray-900 rounded-[28px] text-white flex flex-col justify-between">
                <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Statistical Overview</h3>
                <div class="flex items-end justify-between">
                    <div>
                        <div class="text-[32px] font-black tracking-tight leading-none">{{ $activity->count() }}</div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">Total Engagements</p>
                    </div>
                    <div class="text-right">
                        <div class="text-[11px] font-bold text-teal-400 mb-1">Date Range</div>
                        <p class="text-[10px] font-medium text-gray-500">{{ $start_date }} → {{ $end_date }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-4">
            <h3 class="text-[11px] font-black text-gray-600 uppercase tracking-[2px] mb-6">Detailed Activity Ledger</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Date/Time</th>
                            <th class="py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Area / Road</th>
                            <th class="py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Waste Type</th>
                            <th class="py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ $type == 'collector' ? 'Requested By' : 'Collected By' }}</th>
                            <th class="py-4 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($activity as $item)
                        <tr class="group">
                            <td class="py-5">
                                <span class="text-sm font-bold text-gray-800">{{ \Carbon\Carbon::parse($item->created_at)->format('M d, Y') }}</span>
                                <p class="text-[10px] text-gray-400 font-medium">{{ \Carbon\Carbon::parse($item->created_at)->format('h:i A') }}</p>
                            </td>
                            <td class="py-5">
                                <span class="text-xs font-black text-gray-700 uppercase tracking-widest">{{ $item->sector }}</span>
                                <p class="text-[11px] text-gray-500 font-medium mt-0.5">{{ $item->road }}</p>
                            </td>
                            <td class="py-5">
                                <div class="px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest inline-block
                                    @if($item->type == 'Organic') bg-emerald-50 text-emerald-600
                                    @elseif($item->type == 'Recyclable') bg-blue-50 text-blue-600
                                    @elseif($item->type == 'Hazardous') bg-red-50 text-red-600
                                    @else bg-gray-50 text-gray-600 @endif">
                                    {{ $item->type }}
                                </div>
                            </td>
                            <td class="py-5">
                                <span class="text-xs font-black text-gray-800">{{ $type == 'collector' ? ($item->user->name ?? 'N/A') : ($item->collector->name ?? 'N/A') }}</span>
                            </td>
                            <td class="py-5 text-right">
                                <span class="px-2.5 py-1 rounded-full text-[9px] font-black uppercase tracking-widest
                                    @if($item->status == 'Completed') bg-emerald-50 text-emerald-600
                                    @elseif($item->status == 'Pending') bg-orange-50 text-orange-600
                                    @else bg-gray-50 text-gray-600 @endif">
                                    {{ $item->status }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($activity->isEmpty())
                <div class="py-20 text-center bg-gray-50 rounded-[28px] border-2 border-dashed border-gray-100">
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">No activity data available for this range</p>
                </div>
            @endif
        </div>

        <div class="hidden print:block mt-16 border-t border-gray-200 pt-8 text-center">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">This is a computer-generated official document from the Uttara City Waste Management Portal.</p>
        </div>
    </div>

    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; padding: 0 !important; }
            .bg-emerald-50 { background-color: #ecfdf5 !important; }
            .bg-emerald-500 { background-color: #10b981 !important; }
        }
    </style>
@endsection
