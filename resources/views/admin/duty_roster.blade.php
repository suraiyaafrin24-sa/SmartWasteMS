@extends('layouts.admin')

@section('title', 'Admin Duty Roster')

@section('content')
    <div class="mb-8 mt-2">
        <h1 class="text-[28px] font-black tracking-tight text-gray-900 mb-1">Staff Duty Schedule</h1>
        <p class="text-sm font-medium text-gray-500">Configure and manage general field coverage schedules for Uttara
            Sectors.</p>
    </div>

    <!-- Roster Form Section -->
    <div class="bg-white rounded-[32px] p-8 border border-gray-100 shadow-xl shadow-gray-200/40 mb-10">
        <div class="flex items-center gap-3 mb-8">
            <div class="w-10 h-10 bg-teal-50 rounded-xl flex items-center justify-center text-[#19817E]">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 00-2 2z"></path>
                </svg>
            </div>
            <h2 class="text-xl font-black text-gray-800 tracking-tight">Schedule New Shift Assignment</h2>
        </div>

        <form action="{{ route('admin.duty_roster.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Collector -->
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">Assign Staff <span
                            class="text-red-500">*</span></label>
                    <select name="collector_id" required
                        class="w-full bg-gray-50 border border-gray-100 text-gray-800 text-sm font-bold rounded-xl p-3.5 focus:ring-4 focus:ring-teal-100 focus:border-[#19817E] outline-none transition-all cursor-pointer">
                        <option value="">Select Collector...</option>
                        @foreach($collectors as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Sector -->
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">Target Sector <span
                            class="text-red-500">*</span></label>
                    <select name="sector" required
                        class="w-full bg-gray-50 border border-gray-100 text-gray-800 text-sm font-bold rounded-xl p-3.5 focus:ring-4 focus:ring-teal-100 focus:border-[#19817E] outline-none transition-all cursor-pointer">
                        <option value="">Choose Sector...</option>
                        @for($i = 1; $i <= 18; $i++)
                            <option value="Sector {{ $i }}">Sector {{ $i }}</option>
                        @endfor
                    </select>
                </div>

                <!-- Date -->
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">Duty Date <span
                            class="text-red-500">*</span></label>
                    <input type="date" name="date" value="{{ date('Y-m-d') }}" required
                        class="w-full bg-gray-50 border border-gray-100 text-gray-800 text-sm font-bold rounded-xl p-3.5 focus:ring-4 focus:ring-teal-100 focus:border-[#19817E] outline-none transition-all">
                </div>

                <!-- Shift -->
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">Operating Shift
                        <span class="text-red-500">*</span></label>
                    <select name="shift" required
                        class="w-full bg-gray-50 border border-gray-100 text-gray-800 text-sm font-bold rounded-xl p-3.5 focus:ring-4 focus:ring-teal-100 focus:border-[#19817E] outline-none transition-all cursor-pointer">
                        <option value="Morning Shift">🌅 Morning Shift</option>
                        <option value="Night Shift">🌙 Night Shift</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center justify-end mt-8 border-t border-gray-50 pt-6">
                <button type="submit"
                    class="bg-[#19817E] text-white text-[11px] font-black py-4 px-10 rounded-2xl hover:bg-teal-700 transition shadow-xl shadow-teal-500/20 active:scale-[0.98] uppercase tracking-widest">
                    Assign Staff
                </button>
            </div>
        </form>
    </div>

    <!-- Active Roster List -->
    <div class="bg-white rounded-[32px] border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-8 border-b border-gray-50 flex justify-between items-center">
            <h3 class="text-lg font-black text-gray-800 tracking-tight text-center lg:text-left">Current Coverage Overview
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 justify-center">
                        <th class="px-8 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">
                            Assigned Staff</th>
                        <th class="px-8 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">
                            Uttara Sector</th>
                        <th class="px-8 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Shift
                        </th>
                        <th class="px-8 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Date
                        </th>
                        <th class="px-8 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">
                            Status</th>
                        <th class="px-8 py-5 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($rosters as $r)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-9 h-9 bg-[#19817E]/10 rounded-xl flex items-center justify-center text-[#19817E] font-black text-xs">
                                        {{ strtoupper(substr($r->collector->name, 0, 1)) }}
                                    </div>
                                    <span
                                        class="text-sm font-black text-gray-800 tracking-tight">{{ $r->collector->name }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-5">
                                <span
                                    class="bg-orange-50 text-orange-600 border border-orange-100 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest">
                                    {{ $r->sector }}
                                </span>
                            </td>
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-bold text-gray-700">{{ $r->shift }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-5">
                                <span
                                    class="text-xs font-medium text-gray-500">{{ \Carbon\Carbon::parse($r->date)->format('M d, Y') }}</span>
                            </td>
                            <td class="px-8 py-5">
                                <span
                                    class="px-2.5 py-1 rounded-full text-[9px] font-black uppercase tracking-widest bg-emerald-50 text-emerald-600">
                                    {{ $r->status }}
                                </span>
                            </td>
                            <td class="px-8 py-5 text-right">
                                <form action="{{ route('admin.duty_roster.delete', $r->id) }}" method="POST"
                                    onsubmit="return confirm('Remove this roster entry?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-gray-300 hover:text-red-500 transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-8 py-20 text-center">
                                <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">No duty roster entries
                                    found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($rosters->hasPages())
            <div class="px-8 py-6 border-t border-gray-50 bg-gray-50/50">
                {{ $rosters->links() }}
            </div>
        @endif
    </div>
@endsection