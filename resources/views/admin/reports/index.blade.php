@extends('layouts.admin')

@section('title', 'User Reports')

@section('content')
    <div class="mb-8 mt-2">
        <h1 class="text-[28px] font-black tracking-tight text-gray-900 mb-1">User Issue Reports</h1>
        <p class="text-sm font-medium text-gray-500">Manage and resolve issues reported by users.</p>
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
            <table class="w-full text-left border-separate border-spacing-y-2">
                <thead>
                    <tr class="text-[10px] font-black text-gray-400 uppercase tracking-[2px]">
                        <th class="px-6 py-4">User</th>
                        <th class="px-6 py-4">Subject</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Date</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($reports as $report)
                        <tr class="group hover:bg-gray-50/50 transition">
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center text-[10px] font-black text-blue-600 uppercase">
                                        {{ substr($report->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-black text-gray-800 tracking-tight leading-none mb-1">{{ $report->user->name }}</div>
                                        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $report->user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <span class="text-sm font-black text-gray-800 tracking-tight leading-none">{{ $report->subject }}</span>
                                <p class="text-[11px] text-gray-500 mt-1 line-clamp-1 max-w-xs font-medium">{{ $report->description }}</p>
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
                            <td class="px-6 py-5 text-right">
                                @if($report->status !== 'Resolved')
                                    <form action="{{ route('admin.reports.resolve', $report->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-emerald-600 hover:text-emerald-800 text-[10px] font-black uppercase tracking-widest px-4 py-2 bg-emerald-50 rounded-xl transition">
                                            Mark Resolved
                                        </button>
                                    </form>
                                @else
                                    <span class="text-gray-400 text-[10px] font-black uppercase tracking-widest px-4 py-2 bg-gray-50 rounded-xl">Closed</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-400 font-bold uppercase tracking-widest text-xs">
                                No reports found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4">
            {{ $reports->links() }}
        </div>
    </div>
@endsection
