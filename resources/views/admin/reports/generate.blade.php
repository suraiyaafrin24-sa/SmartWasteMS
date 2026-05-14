@extends('layouts.admin')

@section('title', 'Municipal Performance Reports')

@section('content')
    <div class="mb-8 mt-2">
        <h1 class="text-[28px] font-black tracking-tight text-gray-900 mb-1">Performance Reports</h1>
        <p class="text-sm font-medium text-gray-500">Generate and download professional activity records for staff and
            residents.</p>
    </div>

    <div class="bg-white rounded-[32px] border border-gray-100 shadow-sm overflow-hidden mb-10">
        {{-- Card Header --}}
        <div class="bg-gray-900 px-8 py-5 flex items-center gap-4">
            <div class="w-9 h-9 bg-[#19817E] rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 01-2-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
            </div>
            <div>
                <h2 class="text-[15px] font-black text-white tracking-tight">Report Configuration</h2>
                <p class="text-[11px] text-gray-400 font-medium mt-0.5">Select target and date range to generate a report.</p>
            </div>
        </div>

        {{-- Form --}}
        <form action="{{ route('admin.reports.generate') }}" method="POST" class="p-8">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Reporting Target --}}
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Reporting Target <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select name="report_type" id="typeSelect" required onchange="toggleUserList(this.value); filterTable()"
                            style="background-image: none;"
                            class="w-full bg-gray-50 border border-gray-100 text-gray-800 text-sm font-bold rounded-xl px-4 py-3 pr-10 focus:ring-2 focus:ring-teal-500/20 focus:border-[#19817E] outline-none transition-all appearance-none cursor-pointer">
                            <option value="collector" {{ request('report_type', 'collector') == 'collector' ? 'selected' : '' }}>Waste Collector</option>
                            <option value="user" {{ request('report_type') == 'user' ? 'selected' : '' }}>Resident User</option>
                        </select>
                        <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>

                {{-- Select Individual --}}
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Select Individual <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select name="user_id" id="personSelect" required onchange="filterTable()"
                            style="background-image: none;"
                            class="w-full bg-gray-50 border border-gray-100 text-gray-800 text-sm font-bold rounded-xl px-4 py-3 pr-10 focus:ring-2 focus:ring-teal-500/20 focus:border-[#19817E] outline-none transition-all appearance-none cursor-pointer">
                            <option value="">Choose Staff / User...</option>
                            @foreach($collectors as $c)
                                <option value="{{ $c->id }}" class="collector-opt" {{ request('user_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                            @foreach($generalUsers as $u)
                                <option value="{{ $u->id }}" class="user-opt hidden" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>

                {{-- Start Date --}}
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Start Date <span class="text-red-500">*</span></label>
                    <input type="date" name="start_date" id="startDate" value="{{ request('start_date', date('Y-m-01')) }}" required onchange="filterTable()"
                        class="w-full bg-gray-50 border border-gray-100 text-gray-800 text-sm font-bold rounded-xl px-4 py-3 focus:ring-2 focus:ring-teal-500/20 focus:border-[#19817E] outline-none transition-all">
                </div>

                {{-- End Date --}}
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">End Date <span class="text-red-500">*</span></label>
                    <input type="date" name="end_date" id="endDate" value="{{ request('end_date', date('Y-m-d')) }}" required onchange="filterTable()"
                        class="w-full bg-gray-50 border border-gray-100 text-gray-800 text-sm font-bold rounded-xl px-4 py-3 focus:ring-2 focus:ring-teal-500/20 focus:border-[#19817E] outline-none transition-all">
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-50 flex justify-end">
                <button type="submit"
                    class="bg-[#19817E] text-white text-[10px] font-black py-3.5 px-10 rounded-2xl hover:bg-teal-700 transition shadow-lg shadow-teal-500/20 active:scale-[0.98] uppercase tracking-widest flex items-center gap-2.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 01-2-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    Generate Official Report
                </button>
            </div>
        </form>
    </div>

    {{-- Collection Records Table --}}
    <div class="mt-10 bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-8 py-6 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h2 class="text-base font-bold text-gray-800">All Collection Records</h2>
                <p class="text-[12px] text-gray-400 font-medium mt-0.5">Overview of all waste pickup requests in the system.</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.reports.download') }}?{{ http_build_query(request()->only(['report_type','user_id','start_date','end_date'])) }}"
                    class="flex items-center gap-2 bg-gray-100 text-gray-700 text-[10px] font-black px-5 py-2.5 rounded-xl hover:bg-gray-200 transition shadow-sm uppercase tracking-widest">
                    Download CSV
                </a>
                <a href="{{ route('admin.reports.pdf.download', request()->only(['report_type','user_id','start_date','end_date'])) }}"
                    class="flex items-center gap-2 bg-gray-800 text-white text-[10px] font-black px-5 py-2.5 rounded-xl hover:bg-gray-900 transition shadow-md uppercase tracking-widest">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    Download PDF
                </a>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="bg-gray-50 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100">
                        <th class="px-6 py-4">Date</th>
                        <th class="px-6 py-4">User</th>
                        <th class="px-6 py-4 text-center">Sector</th>
                        <th class="px-6 py-4 text-center">Waste Type</th>
                        <th class="px-6 py-4 text-center">Shift</th>
                        <th class="px-6 py-4">Assigned Collector</th>
                        <th class="px-6 py-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($collections as $item)
                        <tr class="hover:bg-gray-50/40 transition">
                            <td class="px-6 py-4">
                                <div class="text-sm font-black text-gray-800 leading-none">{{ $item->created_at->format('M d, Y') }}</div>
                                <div class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-0.5">At {{ $item->created_at->format('h:i A') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-black text-gray-800 text-sm">{{ $item->user->name ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="bg-teal-50 text-teal-600 border border-teal-100 px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest">
                                    {{ $item->sector ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-widest
                                    @if($item->type == 'Organic') bg-emerald-50 text-emerald-600
                                    @elseif($item->type == 'Recyclable') bg-blue-50 text-blue-600
                                    @elseif($item->type == 'Hazardous') bg-red-50 text-red-600
                                    @else bg-gray-50 text-gray-600 @endif">
                                    {{ $item->type }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest
                                    {{ $item->shift == 'Morning Shift' ? 'bg-orange-50 text-orange-600 border border-orange-100' : 'bg-indigo-50 text-indigo-600 border border-indigo-100' }}">
                                    {{ $item->shift ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($item->collector)
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-teal-50 text-teal-600 flex items-center justify-center text-[10px] font-black">
                                            {{ substr($item->collector->name, 0, 1) }}
                                        </div>
                                        <span class="text-[12px] font-black text-gray-700">{{ $item->collector->name }}</span>
                                    </div>
                                @else
                                    <span class="text-[10px] font-black text-gray-300 uppercase tracking-widest italic">Unassigned</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-3 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-widest
                                    @if($item->status == 'Completed' || $item->status == 'Collected') bg-emerald-50 text-emerald-600
                                    @elseif($item->status == 'Scheduled') bg-blue-50 text-blue-600
                                    @elseif($item->status == 'Pending') bg-amber-50 text-amber-600
                                    @else bg-gray-50 text-gray-500 @endif">
                                    {{ $item->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-16 text-center text-gray-400 font-bold uppercase tracking-[2px] text-[11px]">No collection records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($collections->hasPages())
            <div class="px-6 py-4 border-t border-gray-50">
                {{ $collections->links() }}
            </div>
        @endif
    </div>

    <script>
        function toggleUserList(type) {
            const select = document.getElementById('personSelect');
            const collectorOpts = document.querySelectorAll('.collector-opt');
            const userOpts = document.querySelectorAll('.user-opt');
            select.value = "";
            if (type === 'collector') {
                collectorOpts.forEach(o => o.classList.remove('hidden'));
                userOpts.forEach(o => o.classList.add('hidden'));
            } else {
                collectorOpts.forEach(o => o.classList.add('hidden'));
                userOpts.forEach(o => o.classList.remove('hidden'));
            }
        }

        function filterTable() {
            const reportType = document.getElementById('typeSelect').value;
            const userId = document.getElementById('personSelect').value;
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;
            const params = new URLSearchParams();
            if (reportType) params.set('report_type', reportType);
            if (userId) params.set('user_id', userId);
            if (startDate) params.set('start_date', startDate);
            if (endDate) params.set('end_date', endDate);
            window.location.href = '{{ route('admin.reports') }}?' + params.toString();
        }

        // On page load: restore correct person list visibility based on current GET param
        document.addEventListener('DOMContentLoaded', function () {
            const type = document.getElementById('typeSelect').value;
            const collectorOpts = document.querySelectorAll('.collector-opt');
            const userOpts = document.querySelectorAll('.user-opt');
            if (type === 'user') {
                collectorOpts.forEach(o => o.classList.add('hidden'));
                userOpts.forEach(o => o.classList.remove('hidden'));
            } else {
                collectorOpts.forEach(o => o.classList.remove('hidden'));
                userOpts.forEach(o => o.classList.add('hidden'));
            }
            // Re-select the person from GET param if present
            const userId = '{{ request('user_id') }}';
            if (userId) document.getElementById('personSelect').value = userId;
        });
    </script>
@endsection