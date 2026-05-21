@extends('layouts.admin')

@section('title', 'Leave Requests')

@section('content')
    <div class="mb-8 mt-2">
        <h1 class="text-[28px] font-black tracking-tight text-gray-900 mb-1">Leave Requests</h1>
        <p class="text-sm font-medium text-gray-500">Approve collector leave requests.</p>
    </div>

    <div class="bg-white rounded-[32px] border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50">
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Collector</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Date Range</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Reason</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Status</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($leaveRequests as $leave)
                    <tr>
                        <td class="px-6 py-5">
                            <div class="text-sm font-black text-gray-800">{{ $leave->collector->name }}</div>
                            <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">ID: #COL-{{ str_pad($leave->collector->id, 3, '0', STR_PAD_LEFT) }}</div>
                        </td>
                        <td class="px-6 py-5 text-sm font-black text-gray-800">
                            {{ $leave->start_date->format('M d, Y') }} - {{ $leave->end_date->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-5 text-sm font-bold text-gray-500">{{ $leave->reason ?? 'N/A' }}</td>
                        <td class="px-6 py-5 text-center">
                            <span class="px-3 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-widest {{ $leave->status === 'Approved' ? 'bg-emerald-50 text-emerald-600' : ($leave->status === 'Rejected' ? 'bg-red-50 text-red-600' : 'bg-amber-50 text-amber-600') }}">
                                {{ $leave->status }}
                            </span>
                        </td>
                        <td class="px-6 py-5 text-right">
                            @if($leave->status === 'Pending')
                                <div class="flex items-center justify-end gap-2">
                                    <form action="{{ route('admin.leave_requests.approve', $leave->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-[#19817E] text-white text-[10px] font-black px-5 py-2.5 rounded-xl uppercase tracking-widest hover:bg-teal-700 transition">Approve</button>
                                    </form>
                                    <form action="{{ route('admin.leave_requests.reject', $leave->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-red-50 text-red-600 hover:bg-red-500 hover:text-white text-[10px] font-black px-5 py-2.5 rounded-xl uppercase tracking-widest transition">Reject</button>
                                    </form>
                                </div>
                            @else
                                <span class="text-[10px] font-black {{ $leave->status === 'Rejected' ? 'text-red-400' : 'text-gray-300' }} uppercase tracking-widest">{{ $leave->status }}</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400 font-bold uppercase tracking-widest text-[11px]">No leave requests found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if($leaveRequests->hasPages())
            <div class="px-6 py-4 border-t border-gray-50">{{ $leaveRequests->links() }}</div>
        @endif
    </div>
@endsection
