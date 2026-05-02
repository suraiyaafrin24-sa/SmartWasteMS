@extends('layouts.user')

@section('title', 'Request History')

@section('content')
    <div class="mb-8 mt-2 flex justify-between items-end">
        <div>
            <h1 class="text-[28px] font-black tracking-tight text-gray-900 mb-1">Request History</h1>
            <p class="text-sm font-medium text-gray-500">View all your past and scheduled waste collection requests.</p>
        </div>
        <div>
            <a href="{{ route('user.requests.create') }}" class="bg-[#19817E] text-white text-sm font-semibold py-2.5 px-6 rounded-xl hover:bg-teal-700 transition shadow-sm flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                New Collection Request
            </a>
        </div>
    </div>

    <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead>
                    <tr class="text-gray-400 font-bold border-b border-gray-100 uppercase text-[11px] tracking-wider">
                        <th class="pb-3 px-2">Type</th>
                        <th class="pb-3 px-2">Scheduled Date</th>
                        <th class="pb-3 px-2">Status</th>
                        <th class="pb-3 px-2">Collector</th>
                        <th class="pb-3 px-2 text-right">Details</th>
                    </tr>
                </thead>
                <tbody class="text-gray-800 font-semibold divide-y divide-gray-50">
                    @forelse($requests as $request)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="py-4 px-2">
                            <div class="font-bold">{{ $request->type }}</div>
                            <div class="text-[11px] text-gray-400 font-medium tracking-tight">ID: #RE-{{ str_pad($request->id, 5, '0', STR_PAD_LEFT) }}</div>
                        </td>
                        <td class="py-4 px-2">
                            <div>{{ $request->scheduled_date->format('M d, Y') }}</div>
                            <div class="text-[11px] text-gray-400 font-medium tracking-tight">{{ $request->scheduled_time ?? 'Any time' }}</div>
                        </td>
                        <td class="py-4 px-2">
                            @if($request->status == 'Completed' || $request->status == 'Collected')
                                <span class="px-2.5 py-1 rounded-md text-[11px] font-black bg-emerald-100 text-emerald-600 tracking-wide uppercase">Collected</span>
                            @elseif($request->status == 'Scheduled')
                                <span class="px-2.5 py-1 rounded-md text-[11px] font-black bg-amber-100 text-amber-600 tracking-wide uppercase">Scheduled</span>
                            @elseif($request->status == 'Cancelled')
                                <span class="px-2.5 py-1 rounded-md text-[11px] font-black bg-red-100 text-red-600 tracking-wide uppercase">Cancelled</span>
                            @else
                                <span class="px-2.5 py-1 rounded-md text-[11px] font-black bg-blue-100 text-blue-600 tracking-wide uppercase">{{ $request->status }}</span>
                            @endif
                        </td>
                        <td class="py-4 px-2 text-gray-500">
                            @if($request->collector)
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center text-[10px] font-bold text-gray-600">
                                        {{ substr($request->collector->name, 0, 1) }}
                                    </div>
                                    {{ $request->collector->name }}
                                </div>
                            @else
                                <span class="italic text-gray-400">Waiting for assignment</span>
                            @endif
                        </td>
                        <td class="py-4 px-2 text-right">
                            <button class="text-teal-600 hover:text-teal-700 font-bold">View</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center">
                            <div class="flex flex-col items-center gap-2">
                                <svg class="w-12 h-12 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                <p class="text-gray-500 font-medium">You haven't made any requests yet.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-8 border-t border-gray-100 pt-6">
            {{ $requests->links() }}
        </div>
    </div>
@endsection
