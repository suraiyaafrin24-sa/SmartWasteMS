@extends('layouts.admin')

@section('title', 'Admin Duty Staff')

@section('content')
    <div class="mb-8 mt-2">
        <h1 class="text-[28px] font-black tracking-tight text-gray-900 mb-1">Staff Duty Schedule</h1>
        <p class="text-sm font-medium text-gray-500">Configure and manage general field coverage schedules for Uttara
            Sectors.</p>
    </div>

    <!-- Staff Form Section -->
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

            <!-- Hidden inputs for auto-population from collector profile -->
            <input type="hidden" name="sector" id="hidden_sector">
            <input type="hidden" name="road" id="hidden_road">

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Collector -->
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">Staff Name <span
                            class="text-red-500">*</span></label>
                    <select name="collector_id" id="collector_select" required
                        class="w-full bg-gray-50 border border-gray-100 text-gray-800 text-sm font-bold rounded-xl p-3.5 focus:ring-4 focus:ring-teal-100 focus:border-[#19817E] outline-none transition-all cursor-pointer">
                        <option value="">Select Collector...</option>
                        @foreach($collectors as $c)
                            @php
                                $leave = $c->leaveRequests
                                    ->where('status', 'Approved')
                                    ->filter(function($l) {
                                        return $l->end_date->format('Y-m-d') >= date('Y-m-d');
                                    })
                                    ->first();
                            @endphp
                            <option value="{{ $c->id }}" data-sector="{{ $c->sector }}" data-road="{{ $c->road }}" data-name="{{ $c->name }}"
                                @if($leave)
                                    data-leave-start="{{ $leave->start_date->format('Y-m-d') }}"
                                    data-leave-end="{{ $leave->end_date->format('Y-m-d') }}"
                                    data-leave-text=" — On Leave ({{ $leave->start_date->format('M d') }} - {{ $leave->end_date->format('M d') }})"
                                @endif
                            >
                                {{ $c->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Staff Sector Display -->
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">Staff Sector</label>
                    <div id="display_address" class="w-full bg-gray-50 border border-gray-100 text-gray-400 text-sm font-bold rounded-xl p-3.5 italic h-[50px] flex items-center">
                        Select a collector...
                    </div>
                </div>


                <!-- Date -->
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">Duty Start Date <span
                            class="text-red-500">*</span></label>
                    <input type="date" name="date" id="duty_date" value="{{ $prefill['date'] ?? date('Y-m-d') }}" required
                        min="{{ date('Y-m-d') }}"
                        class="w-full bg-gray-50 border border-gray-100 text-gray-800 text-sm font-bold rounded-xl p-3.5 focus:ring-4 focus:ring-teal-100 focus:border-[#19817E] outline-none transition-all">
                </div>

                <!-- End Date (Bulk Assign) -->
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">End Date (Optional)</label>
                    <input type="date" name="end_date" id="end_date" min="{{ date('Y-m-d') }}"
                        placeholder="Bulk assign up to 7 days"
                        class="w-full bg-gray-50 border border-gray-100 text-gray-800 text-sm font-bold rounded-xl p-3.5 focus:ring-4 focus:ring-teal-100 focus:border-[#19817E] outline-none transition-all">
                    <p class="text-[9px] text-gray-400 font-medium italic mt-1">Leave blank for single day assignment.</p>
                </div>

                <!-- Shift -->
                <div class="space-y-2">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">Operating Shift <span class="text-red-500">*</span></label>
                    <select name="shift" id="shift_select" required class="w-full bg-gray-50 border border-gray-100 text-gray-800 text-sm font-bold rounded-xl p-3.5 focus:ring-4 focus:ring-teal-100 focus:border-[#19817E] outline-none transition-all cursor-pointer">
                        <option value="Morning Shift" {{ (isset($prefill['shift']) && $prefill['shift'] == 'Morning Shift') ? 'selected' : '' }}>🌅 Morning Shift (7-10 AM)</option>
                        <option value="Evening Shift" {{ (isset($prefill['shift']) && $prefill['shift'] == 'Evening Shift') ? 'selected' : '' }}>🌇 Evening Shift (4-6 PM)</option>
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

    <!-- Active Staff List -->
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
                                @php
                                    $todayDate = now()->toDateString();
                                    $rosterDate = \Carbon\Carbon::parse($r->date)->toDateString();
                                    $currentTime = now()->format('H:i');
                                    
                                    if ($rosterDate < $todayDate) {
                                        $statusText = 'Completed';
                                        $statusClass = 'bg-gray-100 text-gray-400';
                                    } elseif ($rosterDate > $todayDate) {
                                        $statusText = 'Upcoming';
                                        $statusClass = 'bg-blue-50 text-blue-600';
                                    } else { // Today
                                        if ($r->shift == 'Morning Shift') {
                                            if ($currentTime >= '07:00' && $currentTime <= '10:00') {
                                                $statusText = 'ACTIVE';
                                                $statusClass = 'bg-emerald-50 text-emerald-600 animate-pulse';
                                            } elseif ($currentTime < '07:00') {
                                                $statusText = 'Pending';
                                                $statusClass = 'bg-amber-50 text-amber-600';
                                            } else {
                                                $statusText = 'Shift Ended';
                                                $statusClass = 'bg-gray-100 text-gray-400';
                                            }
                                        } else { // Evening Shift
                                            if ($currentTime >= '16:00' && $currentTime <= '18:00') {
                                                $statusText = 'ACTIVE';
                                                $statusClass = 'bg-emerald-50 text-emerald-600 animate-pulse';
                                            } elseif ($currentTime < '16:00') {
                                                $statusText = 'Pending';
                                                $statusClass = 'bg-amber-50 text-amber-600';
                                            } else {
                                                $statusText = 'Shift Ended';
                                                $statusClass = 'bg-gray-100 text-gray-400';
                                            }
                                        }
                                    }
                                @endphp
                                <span class="px-2.5 py-1 rounded-full text-[9px] font-black uppercase tracking-widest {{ $statusClass }}">
                                    {{ $statusText }}
                                </span>
                            </td>
                            <td class="px-8 py-5 text-right">
                                <form action="{{ route('admin.duty_roster.delete', $r->id) }}" method="POST"
                                    onsubmit="return confirm('Remove this staff entry?');">
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
                                <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">No duty staff entries
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const collectorSelect = document.getElementById('collector_select');
        const dutyDate = document.getElementById('duty_date');
        const shiftSelect = document.getElementById('shift_select');
        const hiddenSector = document.getElementById('hidden_sector');
        const hiddenRoad = document.getElementById('hidden_road');
        const displayAddress = document.getElementById('display_address');

        function updateShiftOptions() {
            const selectedDate = dutyDate.value;
            const today = new Date();
            const year = today.getFullYear();
            const month = String(today.getMonth() + 1).padStart(2, '0');
            const day = String(today.getDate()).padStart(2, '0');
            const todayStr = `${year}-${month}-${day}`;
            const currentHour = today.getHours();

            Array.from(shiftSelect.options).forEach(option => {
                option.disabled = false;
                if (selectedDate === todayStr) {
                    if (option.value === 'Morning Shift' && currentHour >= 10) {
                        option.disabled = true;
                    }
                    if (option.value === 'Evening Shift' && currentHour >= 18) {
                        option.disabled = true;
                    }
                }
            });

            if (shiftSelect.selectedOptions[0] && shiftSelect.selectedOptions[0].disabled) {
                const enabledOption = Array.from(shiftSelect.options).find(opt => !opt.disabled);
                if (enabledOption) {
                    shiftSelect.value = enabledOption.value;
                } else {
                    shiftSelect.value = '';
                }
            }
        }

        function updateCollectorAvailability() {
            const selectedDate = dutyDate.value;
            
            Array.from(collectorSelect.options).forEach(option => {
                if (option.value === '') return; // Skip "Select Collector..." option
                
                const leaveStart = option.getAttribute('data-leave-start');
                const leaveEnd = option.getAttribute('data-leave-end');
                const leaveText = option.getAttribute('data-leave-text') || '';
                const baseName = option.getAttribute('data-name');
                
                // Check if selected date falls within leave period
                if (leaveStart && leaveEnd && selectedDate >= leaveStart && selectedDate <= leaveEnd) {
                    option.disabled = true;
                    option.textContent = baseName + leaveText;
                } else {
                    option.disabled = false;
                    option.textContent = baseName;
                }
            });

            // If current selection is disabled, clear it
            if (collectorSelect.selectedOptions[0]?.disabled) {
                collectorSelect.value = '';
                updateFields();
            }
        }

        if (collectorSelect) {
            function updateFields() {
                const selectedOption = collectorSelect.options[collectorSelect.selectedIndex];
                
                if (selectedOption && selectedOption.value) {
                    const sector = selectedOption.getAttribute('data-sector') || '';
                    const road = selectedOption.getAttribute('data-road') || '';
                    
                    hiddenSector.value = sector;
                    hiddenRoad.value = road;
                    
                    if (sector) {
                        displayAddress.textContent = sector;
                        displayAddress.classList.remove('text-gray-400', 'italic');
                        displayAddress.classList.add('text-gray-800');
                    } else {
                        displayAddress.textContent = 'No sector assigned';
                        displayAddress.classList.add('text-red-400');
                        displayAddress.classList.remove('text-gray-400', 'italic', 'text-gray-800');
                    }
                } else {
                    hiddenSector.value = '';
                    hiddenRoad.value = '';
                    displayAddress.textContent = 'Select a collector...';
                    displayAddress.classList.add('text-gray-400', 'italic');
                    displayAddress.classList.remove('text-gray-800', 'text-red-400');
                }
            }

            collectorSelect.addEventListener('change', updateFields);
            dutyDate.addEventListener('change', function() {
                updateShiftOptions();
                updateCollectorAvailability();
            });
            
            // Trigger once on load
            updateFields();
            updateShiftOptions();
            updateCollectorAvailability();
        }
    });
</script>
@endpush
