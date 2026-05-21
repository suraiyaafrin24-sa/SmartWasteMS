@extends('layouts.user')

@section('title', 'New Collection Request')

@section('content')
    <div class="mb-8 mt-2">
        <h1 class="text-[28px] font-black tracking-tight text-gray-900 mb-1">New Collection Request</h1>
        <p class="text-sm font-medium text-gray-500">Schedule a professional waste pickup at your convenience.</p>
    </div>

    <div class="max-w-5xl">
        <div class="bg-white rounded-[32px] p-10 border border-gray-100 shadow-xl shadow-gray-200/40">
            @if(! $isSubscriptionActive)
                <div class="mb-8 p-5 bg-amber-50 border border-amber-100 rounded-2xl flex items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center text-amber-600 shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-black text-amber-800 uppercase tracking-wider">Subscription required</p>
                            <p class="text-[11px] text-amber-600 font-medium">You can fill this form, but you need an active package before confirming the request.</p>
                        </div>
                    </div>
                    <a href="{{ route('user.subscription') }}"
                        class="shrink-0 rounded-xl bg-amber-500 px-4 py-3 text-[10px] font-black uppercase tracking-widest text-white hover:bg-amber-600 transition">
                        Buy Package
                    </a>
                </div>
            @endif

            <form action="{{ route('user.requests.store') }}" method="POST">
                @csrf

                <!-- Waste Type Selection (Dropdown) -->
                <div class="mb-10">
                    <label class="block text-[11px] font-black text-gray-400 uppercase tracking-[2px] mb-5">Select Waste
                        Category <span class="text-red-500">*</span></label>
                    <div class="relative max-w-xl">
                        <select name="type" required style="background-image: none;"
                            class="w-full bg-gray-50 border border-gray-100 text-gray-800 text-sm font-bold rounded-2xl p-5 focus:ring-4 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all appearance-none cursor-pointer">
                            <option value="">Choose Category...</option>
                            <option value="Organic" {{ old('type') == 'Organic' ? 'selected' : '' }}>🍀 Organic (Food, Garden
                                Waste)</option>
                            <option value="Recyclable" {{ old('type') == 'Recyclable' ? 'selected' : '' }}>♻️ Recyclable
                                (Plastic, Paper, Metal)</option>
                            <option value="Hazardous" {{ old('type') == 'Hazardous' ? 'selected' : '' }}>⚠️ Hazardous
                                (Chemicals, Batteries)</option>
                            <option value="General" {{ (old('type') == 'General' || !old('type')) ? 'selected' : '' }}>🗑️
                                General Waste</option>
                        </select>
                        <div class="absolute inset-y-0 right-5 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Fields Grid -->
                <div class="space-y-8">
                    <!-- Row 1: Date & Time -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest">Scheduled
                                Date <span class="text-red-500">*</span></label>
                            <input type="date" name="scheduled_date"
                                class="w-full bg-gray-50/50 border border-gray-100 text-gray-800 text-sm rounded-xl p-4 focus:ring-2 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all shadow-sm"
                                required min="{{ date('Y-m-d') }}">
                        </div>

                        <div class="space-y-2">
                            <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest">Preferred
                                Shift <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <select name="shift" required style="background-image: none;"
                                    class="w-full bg-gray-50/50 border border-gray-100 text-gray-800 text-sm font-bold rounded-xl p-4 focus:ring-2 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all shadow-sm cursor-pointer appearance-none">
                                    <option value="Morning Shift">🌅 Morning Shift (07 AM - 10 AM)</option>
                                    <option value="Evening Shift">🌇 Evening Shift (04 PM - 06 PM)</option>
                                </select>
                                <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-gray-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Row 2: Sector & Micro-location -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest">Sector
                                (Uttara)</label>
                            <div class="relative">
                                <input type="text" value="{{ auth()->user()->sector ?? 'Not Defined' }}"
                                    class="w-full bg-gray-100 border border-gray-100 text-gray-500 text-sm font-bold rounded-xl p-4 cursor-not-allowed"
                                    readonly>
                                <input type="hidden" name="sector" value="{{ auth()->user()->sector }}">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest">Road / Block
                                / House <span class="text-red-500">*</span></label>
                            <input type="text" name="road" value="{{ auth()->user()->road ?? '' }}"
                                placeholder="e.g. Road 12, Block D, House 5"
                                class="w-full bg-gray-50/50 border border-gray-100 text-gray-800 text-sm font-bold rounded-xl p-4 focus:ring-2 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all shadow-sm"
                                required>
                        </div>
                    </div>

                    <!-- Row 3: Phone & General Address -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest">Contact
                                Number</label>
                            <input type="text" name="phone" value="{{ auth()->user()->phone ?? '' }}"
                                placeholder="Enter contact number"
                                class="w-full bg-gray-50/50 border border-gray-100 text-gray-800 text-sm rounded-xl p-4 focus:ring-2 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all shadow-sm">
                        </div>

                        <div class="space-y-2">
                            <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest">Full Address
                                Details</label>
                            <input type="text" name="address" value="{{ auth()->user()->address ?? '' }}"
                                placeholder="Detailed landmark description"
                                class="w-full bg-gray-50/50 border border-gray-100 text-gray-800 text-sm rounded-xl p-4 focus:ring-2 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all shadow-sm">
                        </div>
                    </div>

                    <!-- Row 3: Instructions (Full Width) -->
                    <div class="space-y-2">
                        <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest">Additional
                            Instructions (Optional)</label>
                        <textarea name="details" rows="4"
                            class="w-full bg-gray-50/50 border border-gray-100 text-gray-800 text-sm rounded-2xl p-4 focus:ring-2 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all shadow-sm resize-none"
                            placeholder="Provide any extra details for the collection staff..."></textarea>
                    </div>

                    <!-- Assigned Collectors Display -->
                    <div id="collector-info" class="hidden animate-in fade-in slide-in-from-bottom-4 duration-500">
                        <div class="space-y-4">
                            <label class="block text-[11px] font-black text-blue-500 uppercase tracking-widest mb-1">Today's
                                Assigned Staff for this Sector</label>
                            <div id="collectors-grid" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Collector cards will be injected here -->
                            </div>
                        </div>
                    </div>

                    <div id="no-collector-alert" class="hidden">
                        <div class="p-5 bg-amber-50 border border-amber-100 rounded-2xl flex items-center gap-4">
                            <div
                                class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center text-amber-600 shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-black text-amber-800 uppercase tracking-wider">No collector assigned
                                    yet</p>
                                <p class="text-[11px] text-amber-600 font-medium">Your request will be sent to the admin
                                    panel for manual assignment.</p>
                            </div>
                        </div>
                    </div>
                    
                    <input type="hidden" name="collector_id" id="selected-collector-id">
                </div>

                <!-- Action Buttons -->
                <div class="mt-12 pt-8 border-t border-gray-50 flex items-center justify-between">
                    <a href="{{ route('user.dashboard') }}"
                        class="text-[10px] font-black text-gray-400 hover:text-gray-600 transition uppercase tracking-widest">Cancel
                        Request</a>
                    @if($isSubscriptionActive)
                        <button type="submit" style="background-color: #2D5CFE !important; color: #FFFFFF !important;"
                            class="text-[10px] font-black py-4 px-8 rounded-2xl hover:opacity-90 transition shadow-xl shadow-blue-500/25 active:scale-[0.98] duration-150 tracking-widest uppercase">
                            Confirm Request
                        </button>
                    @else
                        <a href="{{ route('user.subscription') }}"
                            class="text-[10px] font-black py-4 px-8 rounded-2xl bg-slate-200 text-slate-500 hover:bg-slate-300 transition tracking-widest uppercase">
                            Buy Subscription to Confirm
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sectorInput = document.querySelector('input[name="sector"]');
            const dateInput = document.querySelector('input[name="scheduled_date"]');
            const shiftSelect = document.querySelector('select[name="shift"]');

            const collectorInfo = document.getElementById('collector-info');
            const noCollectorAlert = document.getElementById('no-collector-alert');
            const collectorsGrid = document.getElementById('collectors-grid');

            function updateShiftOptions() {
                const now = new Date();
                const year = now.getFullYear();
                const month = String(now.getMonth() + 1).padStart(2, '0');
                const day = String(now.getDate()).padStart(2, '0');
                const todayStr = `${year}-${month}-${day}`;
                
                const selectedDate = dateInput.value;
                const currentTime = now.getHours() * 100 + now.getMinutes();
                
                let anyEnabled = false;
                Array.from(shiftSelect.options).forEach(option => {
                    if (selectedDate === todayStr) {
                        if (option.value === 'Morning Shift' && currentTime >= 1000) {
                            option.disabled = true;
                        } else if (option.value === 'Evening Shift' && currentTime >= 1800) {
                            option.disabled = true;
                        } else {
                            option.disabled = false;
                            anyEnabled = true;
                        }
                    } else {
                        option.disabled = false;
                        anyEnabled = true;
                    }
                });

                // If current selection is disabled, pick first enabled one
                if (shiftSelect.selectedOptions[0]?.disabled) {
                    const firstEnabled = Array.from(shiftSelect.options).find(opt => !opt.disabled && opt.value !== "");
                    if (firstEnabled) {
                        shiftSelect.value = firstEnabled.value;
                    } else {
                        shiftSelect.value = "";
                    }
                }
            }

            window.selectCollector = function(element, collectorId) {
                // Remove selected state from all cards
                document.querySelectorAll('.collector-card').forEach(card => {
                    card.classList.remove('border-blue-500', 'bg-blue-50', 'ring-2', 'ring-blue-100');
                    card.classList.add('border-gray-100', 'bg-white');
                    card.querySelector('.selection-indicator').classList.remove('border-blue-500');
                    card.querySelector('.selection-indicator div').classList.remove('scale-100');
                    card.querySelector('.selection-indicator div').classList.add('scale-0');
                });

                // Add selected state to clicked card
                element.classList.remove('border-gray-100', 'bg-white');
                element.classList.add('border-blue-500', 'bg-blue-50', 'ring-2', 'ring-blue-100');
                element.querySelector('.selection-indicator').classList.add('border-blue-500');
                element.querySelector('.selection-indicator div').classList.remove('scale-0');
                element.querySelector('.selection-indicator div').classList.add('scale-100');

                // Update hidden input
                document.getElementById('selected-collector-id').value = collectorId;
            }

            function fetchCollectors() {
                const sector = sectorInput.value;
                const date = dateInput.value;

                const shift = shiftSelect.value;

                if (sector && date && shift) {
                    fetch(`{{ route('user.get_collector') }}?sector=${sector}&date=${date}&shift=${shift}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success && data.rosters.length > 0) {
                                collectorsGrid.innerHTML = '';
                                data.rosters.forEach(roster => {
                                    const isCurrentShift = shiftSelect.value === roster.shift;
                                    const isBooked = roster.is_booked;
                                    const isPassed = roster.is_passed;
                                    const card = `
                                        <div class="collector-card p-4 rounded-2xl border ${(isBooked || isPassed) ? 'bg-gray-50/50 border-gray-100 opacity-70 cursor-not-allowed' : (isCurrentShift ? 'bg-blue-50/30 border-blue-200 shadow-sm' : 'bg-white border-gray-100')} transition-all cursor-pointer hover:border-blue-300 group relative overflow-hidden" 
                                             ${(isBooked || isPassed) ? '' : `onclick="selectCollector(this, ${roster.collector.id})"`}>
                                            ${(isBooked || isPassed) ? '<div class="absolute inset-0 bg-white/10 z-10"></div>' : ''}
                                            <div class="flex items-center gap-4 relative z-0">
                                                <div class="w-12 h-12 rounded-xl ${isCurrentShift ? 'bg-white' : 'bg-gray-50'} flex items-center justify-center font-black ${isCurrentShift ? 'text-blue-600' : 'text-gray-400'} border border-gray-100 overflow-hidden shrink-0">
                                                    ${roster.collector.image ? `<img src="${roster.collector.image}" class="w-full h-full object-cover">` : roster.collector.initial}
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-center gap-2 mb-1">
                                                        <div class="text-[9px] font-black ${(isBooked || isPassed) ? 'text-red-500' : (isCurrentShift ? 'text-blue-400' : 'text-gray-400')} uppercase tracking-widest leading-none">
                                                            ${roster.shift}
                                                        </div>
                                                        ${isBooked ? '<span class="bg-red-500 text-white text-[7px] px-1.5 py-0.5 rounded-md font-black uppercase tracking-tighter">Booked</span>' : ''}
                                                        ${isPassed ? '<span class="bg-gray-500 text-white text-[7px] px-1.5 py-0.5 rounded-md font-black uppercase tracking-tighter">Shift Ended</span>' : ''}
                                                    </div>
                                                    <h4 class="text-sm font-black text-gray-800 tracking-tight truncate">${roster.collector.name}</h4>
                                                    <p class="text-[10px] font-bold text-gray-400">${roster.collector.phone}</p>
                                                </div>
                                                <div class="selection-indicator w-5 h-5 rounded-full border-2 ${(isBooked || isPassed) ? 'border-gray-100' : 'border-gray-200'} flex items-center justify-center transition-all ${(isBooked || isPassed) ? '' : 'group-hover:border-blue-200'}">
                                                    <div class="w-2.5 h-2.5 rounded-full bg-blue-500 scale-0 transition-all"></div>
                                                </div>
                                            </div>
                                        </div>
                                    `;
                                    collectorsGrid.innerHTML += card;
                                });

                                // Auto-select if there is a collector for the current shift who is NOT booked AND NOT passed
                                const currentShiftCard = Array.from(document.querySelectorAll('.collector-card'))
                                    .find(c => c.innerHTML.includes(shiftSelect.value) && !c.innerHTML.includes('Booked') && !c.innerHTML.includes('Shift Ended'));
                                if (currentShiftCard) {
                                    currentShiftCard.click();
                                }

                                collectorInfo.classList.remove('hidden');
                                noCollectorAlert.classList.add('hidden');
                            } else {
                                collectorInfo.classList.add('hidden');
                                noCollectorAlert.classList.remove('hidden');
                                document.getElementById('selected-collector-id').value = '';
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching collectors:', error);
                        });
                } else {
                    collectorInfo.classList.add('hidden');
                    noCollectorAlert.classList.add('hidden');
                }
            }

            dateInput.addEventListener('change', () => {
                updateShiftOptions();
                fetchCollectors();
            });
            shiftSelect.addEventListener('change', fetchCollectors);

            // Initial check
            updateShiftOptions();
            if (sectorInput.value && dateInput.value) {
                fetchCollectors();
            }
        });
    </script>
@endsection
