@extends('layouts.admin')

@section('title', 'User Reports')

@section('content')
    <div class="mb-10 mt-2">
        <h1 class="text-2xl font-black tracking-tight text-slate-800 mb-1">User Support Reports</h1>
        <p class="text-[13px] font-medium text-slate-400">Review and resolve service issues reported by residents.</p>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-600 px-5 py-4 rounded-2xl flex items-center justify-between">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span class="font-black text-[11px] uppercase tracking-widest">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-[40px] p-10 border border-gray-50 shadow-sm shadow-gray-100/50">
        <div class="overflow-x-auto overflow-y-hidden">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead>
                    <tr class="text-slate-300 font-black border-b border-gray-50 uppercase text-[10px] tracking-[0.2em] leading-none">
                        <th class="pb-8 px-2">Resident Detail</th>
                        <th class="pb-8 px-2">Issue / Description</th>
                        <th class="pb-8 px-2 text-center">Status</th>
                        <th class="pb-8 px-2">Reported Date</th>
                        <th class="pb-8 px-2 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-slate-700 divide-y divide-gray-50/50">
                    @forelse($reports as $report)
                    <tr class="hover:bg-gray-50/50 transition-all duration-300 group">
                        <td class="py-6 px-2">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-[#DCF5F2] text-[#19817E] flex items-center justify-center text-[10px] font-black border border-white shadow-sm shrink-0">
                                    {{ substr($report->user->name, 0, 1) }}
                                </div>
                                <div class="leading-none">
                                    <div class="font-black text-slate-800">{{ $report->user->name }}</div>
                                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">{{ $report->user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-6 px-2">
                            <div class="font-black text-slate-800 leading-tight mb-1">{{ $report->subject }}</div>
                            <div class="mb-2 flex flex-wrap items-center gap-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                <span>Against: {{ $report->complained_person ?? 'N/A' }}</span>
                                <span class="text-slate-200">|</span>
                                <span>Incident: {{ $report->incident_date ? $report->incident_date->format('d M, Y') : 'N/A' }}</span>
                            </div>
                            <p class="text-[11px] text-slate-500 font-medium line-clamp-1 max-w-[250px] italic">"{{ $report->description }}"</p>
                        </td>
                        <td class="py-6 px-2 text-center">
                            @if($report->status == 'Resolved')
                                <span class="px-3 py-1.5 rounded-xl text-[10px] font-black bg-emerald-50 text-emerald-600 tracking-widest uppercase">Resolved</span>
                            @elseif($report->status == 'Pending')
                                <span class="px-3 py-1.5 rounded-xl text-[10px] font-black bg-amber-50 text-amber-600 tracking-widest uppercase">New Report</span>
                            @else
                                <span class="px-3 py-1.5 rounded-xl text-[10px] font-black bg-slate-50 text-slate-400 tracking-widest uppercase">{{ $report->status }}</span>
                            @endif
                        </td>
                        <td class="py-6 px-2 font-bold text-slate-400 uppercase text-[11px] tracking-tight">
                            {{ $report->created_at->format('d M, Y') }}
                        </td>
                        <td class="py-6 px-2 text-right">
                            @if($report->status !== 'Resolved')
                                <form action="{{ route('admin.complaints.resolve', $report->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="bg-[#19817E] text-white text-[10px] font-black px-5 py-2.5 rounded-xl hover:bg-teal-700 transition shadow-sm uppercase tracking-widest">
                                        Mark as Solved
                                    </button>
                                </form>
                            @else
                                <span class="text-slate-300 text-[10px] font-black uppercase tracking-[0.2em] px-5 py-2.5 border border-dashed border-slate-100 rounded-xl">Case Closed</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-12 px-2 text-center">
                            <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em]">All reports have been addressed</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($reports->hasPages())
        <div class="pt-8 mt-4 border-t border-gray-50 flex justify-end">
            {{ $reports->links() }}
        </div>
        @endif
    </div>
@endsection
