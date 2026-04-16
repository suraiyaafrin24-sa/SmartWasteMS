@extends('layouts.user')

@section('title', 'New Collection Request')

@section('content')
    <div class="mb-8 mt-2">
        <h1 class="text-[28px] font-black tracking-tight text-gray-900 mb-1">New Collection Request</h1>
        <p class="text-sm font-medium text-gray-500">Schedule a professional waste pickup at your convenience.</p>
    </div>

    <div class="max-w-5xl">
        <div class="bg-white rounded-[32px] p-10 border border-gray-100 shadow-xl shadow-gray-200/40">
            <form action="{{ route('user.requests.store') }}" method="POST">
                @csrf
                
                <!-- Waste Type Selection (Dropdown) -->
                <div class="mb-10">
                    <label class="block text-[11px] font-black text-gray-400 uppercase tracking-[2px] mb-5">Select Waste Category <span class="text-red-500">*</span></label>
                    <div class="relative max-w-xl">
                        <select name="type" required class="w-full bg-gray-50 border border-gray-100 text-gray-800 text-sm font-bold rounded-2xl p-5 focus:ring-4 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all appearance-none cursor-pointer">
                            <option value="">Choose Category...</option>
                            <option value="Organic" {{ old('type') == 'Organic' ? 'selected' : '' }}>🍀 Organic (Food, Garden Waste)</option>
                            <option value="Recyclable" {{ old('type') == 'Recyclable' ? 'selected' : '' }}>♻️ Recyclable (Plastic, Paper, Metal)</option>
                            <option value="Hazardous" {{ old('type') == 'Hazardous' ? 'selected' : '' }}>⚠️ Hazardous (Chemicals, Batteries)</option>
                            <option value="General" {{ (old('type') == 'General' || !old('type')) ? 'selected' : '' }}>🗑️ General Waste</option>
                        </select>
                        <div class="absolute inset-y-0 right-5 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Fields Grid -->
                <div class="space-y-8">
                    <!-- Row 1: Date & Time -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest">Scheduled Date <span class="text-red-500">*</span></label>
                            <input type="date" name="scheduled_date" class="w-full bg-gray-50/50 border border-gray-100 text-gray-800 text-sm rounded-xl p-4 focus:ring-2 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all shadow-sm" required min="{{ date('Y-m-d') }}">
                        </div>

                        <div class="space-y-2">
                            <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest">Preferred Shift <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <select name="shift" required class="w-full bg-gray-50/50 border border-gray-100 text-gray-800 text-sm font-bold rounded-xl p-4 focus:ring-2 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all shadow-sm cursor-pointer appearance-none">
                                    <option value="Morning Shift">🌅 Morning Shift (08 AM - 11 AM)</option>
                                    <option value="Night Shift">🌙 Night Shift (08 PM - 10 PM)</option>
                                </select>
                                <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-gray-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Row 2: Sector & Micro-location -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest">Uttara Sector <span class="text-red-500">*</span></label>
                            <select name="sector" class="w-full bg-gray-50/50 border border-gray-100 text-gray-800 text-sm font-bold rounded-xl p-4 focus:ring-2 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all shadow-sm cursor-pointer" required>
                                <option value="">Choose your Sector...</option>
                                @for($i = 1; $i <= 18; $i++)
                                    <option value="Sector {{ $i }}">Sector {{ $i }}</option>
                                @endfor
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest">Road / Block / House <span class="text-red-500">*</span></label>
                            <input type="text" name="road" placeholder="e.g. Road 12, Block D, House 5" class="w-full bg-gray-50/50 border border-gray-100 text-gray-800 text-sm font-bold rounded-xl p-4 focus:ring-2 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all shadow-sm" required>
                        </div>
                    </div>

                    <!-- Row 3: Phone & General Address -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest">Contact Number</label>
                            <input type="text" name="phone" value="{{ auth()->user()->phone ?? '' }}" placeholder="Enter contact number" class="w-full bg-gray-50/50 border border-gray-100 text-gray-800 text-sm rounded-xl p-4 focus:ring-2 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all shadow-sm">
                        </div>

                        <div class="space-y-2">
                            <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest">Full Address Details</label>
                            <input type="text" name="address" value="{{ auth()->user()->address ?? '' }}" placeholder="Detailed landmark description" class="w-full bg-gray-50/50 border border-gray-100 text-gray-800 text-sm rounded-xl p-4 focus:ring-2 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all shadow-sm">
                        </div>
                    </div>

                    <!-- Row 3: Instructions (Full Width) -->
                    <div class="space-y-2">
                        <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest">Additional Instructions (Optional)</label>
                        <textarea name="details" rows="4" class="w-full bg-gray-50/50 border border-gray-100 text-gray-800 text-sm rounded-2xl p-4 focus:ring-2 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all shadow-sm resize-none" placeholder="Provide any extra details for the collection staff..."></textarea>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-12 pt-8 border-t border-gray-50 flex items-center justify-between">
                    <a href="{{ route('user.dashboard') }}" class="text-[10px] font-black text-gray-400 hover:text-gray-600 transition uppercase tracking-widest">Cancel Request</a>
                    <button type="submit" style="background-color: #2D5CFE !important; color: #FFFFFF !important;" class="text-[10px] font-black py-4 px-8 rounded-2xl hover:opacity-90 transition shadow-xl shadow-blue-500/25 active:scale-[0.98] duration-150 tracking-widest uppercase">
                        Submit Collection Request
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
