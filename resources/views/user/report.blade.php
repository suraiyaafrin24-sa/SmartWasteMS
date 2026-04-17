@extends('layouts.user')

@section('title', 'My Reports')

@section('content')
    <div class="flex items-center justify-between mb-8 mt-2">
        <div>
            <h1 class="text-[28px] font-black tracking-tight text-gray-900 mb-1">My Reports</h1>
            <p class="text-sm font-medium text-gray-500">View and track your submitted issues.</p>
        </div>
        <a href="{{ route('user.reports.create') }}" style="background-color: #2D5CFE !important; color: #FFFFFF !important;" class="text-[10px] font-black py-4 px-10 rounded-2xl hover:opacity-90 transition shadow-lg shadow-blue-500/20 active:scale-[0.98] duration-150 tracking-wide uppercase">
            Report New Issue
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-600 px-4 py-3 rounded-xl flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span class="font-semibold text-sm">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-3xl p-4 border border-gray-100 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-gray-50 text-[10px] font-black text-gray-400 uppercase tracking-[2px]">
                        <th class="px-6 py-4">Subject</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Date</th>
                        <th class="px-6 py-4">Details</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($reports as $report)
                        <tr class="group hover:bg-gray-50/50 transition">
                            <td class="px-6 py-5">
                                <span class="text-sm font-black text-gray-800 tracking-tight leading-none">{{ $report->subject }}</span>
                            </td>
                            <td class="px-6 py-5">
                                <span class="px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-wider 
                                    @if($report->status == 'Pending') bg-amber-50 text-amber-600 
                                    @elseif($report->status == 'In Progress') bg-blue-50 text-blue-600 
                                    @elseif($report->status == 'Resolved') bg-emerald-50 text-emerald-600 
                                    @else bg-gray-50 text-gray-600 @endif">
                                    {{ $report->status }}
                                </span>
                            </td>
                            <td class="px-6 py-5 text-sm font-bold text-gray-400">
                                {{ $report->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-5">
                                <p class="text-sm text-gray-500 line-clamp-1 max-w-xs font-medium">{{ $report->description }}</p>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center text-gray-400 mb-4">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    </div>
                                    <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">No reports found</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t border-gray-50">
            {{ $reports->links() }}
        </div>
    </div>
@endsection
