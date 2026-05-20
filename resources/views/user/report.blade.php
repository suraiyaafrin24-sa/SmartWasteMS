@extends('layouts.user')

@section('title', 'My Reports')

@section('content')
    <div class="flex items-center justify-between mb-10 mt-2">
        <div>
            <h1 class="text-2xl font-black tracking-tight text-slate-800 mb-1">My Issues</h1>
            <p class="text-[13px] font-medium text-slate-400">Track and manage your submitted support tickets.</p>
        </div>
        <a href="{{ route('user.reports.create') }}" class="bg-[#19817E] text-white text-[13px] font-black py-4 px-10 rounded-2xl hover:bg-teal-700 transition shadow-lg shadow-teal-700/20 uppercase tracking-widest">
            + Report New Issue
        </a>
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
                        <th class="pb-8 px-2">Issue Subject</th>
                        <th class="pb-8 px-2 text-center">Current Status</th>
                        <th class="pb-8 px-2">Submission Date</th>
                        <th class="pb-8 px-2 text-right">Preview</th>
                    </tr>
                </thead>
                <tbody class="text-slate-700 divide-y divide-gray-50/50">
                    @forelse($reports as $report)
                    <tr class="hover:bg-gray-50/50 transition-all duration-300 group">
                        <td class="py-6 px-2">
                            <div class="font-black text-slate-800">{{ $report->subject }}</div>
                            <div class="mt-2 flex flex-wrap items-center gap-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                <span>Against: {{ $report->complained_person ?? 'N/A' }}</span>
                                <span class="text-slate-200">|</span>
                                <span>Date: {{ $report->incident_date ? $report->incident_date->format('M d, Y') : 'N/A' }}</span>
                            </div>
                        </td>
                        <td class="py-6 px-2 text-center">
                            @if($report->status == 'Resolved')
                                <span class="px-3 py-1.5 rounded-xl text-[10px] font-black bg-emerald-50 text-emerald-600 tracking-widest uppercase">Resolved</span>
                            @elseif($report->status == 'In Progress')
                                <span class="px-3 py-1.5 rounded-xl text-[10px] font-black bg-blue-50 text-blue-600 tracking-widest uppercase">In Progress</span>
                            @elseif($report->status == 'Pending')
                                <span class="px-3 py-1.5 rounded-xl text-[10px] font-black bg-amber-50 text-amber-600 tracking-widest uppercase">Pending</span>
                            @else
                                <span class="px-3 py-1.5 rounded-xl text-[10px] font-black bg-gray-50 text-gray-400 tracking-widest uppercase">{{ $report->status }}</span>
                            @endif
                        </td>
                        <td class="py-6 px-2 font-bold text-slate-400">{{ $report->created_at->format('M d, Y') }}</td>
                        <td class="py-6 px-2 text-right">
                             <p class="text-[11px] text-slate-500 line-clamp-1 max-w-[200px] font-medium italic ml-auto group-hover:text-slate-700 transition-colors">"{{ $report->description }}"</p>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-12 px-2 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-slate-50 rounded-[24px] flex items-center justify-center text-slate-300 mb-4 transition-transform group-hover:scale-110">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em]">No reports history available</p>
                            </div>
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
