@extends('layouts.admin')

@section('title', 'Manage System Users')

@section('content')

    <div class="mb-8">
        <h1 class="text-[28px] font-black tracking-tight text-gray-900 mb-1">User & Staff Directory</h1>
        <p class="text-sm font-medium text-gray-500">Register and manage Waste Collectors and General Users.</p>
    </div>

    <!-- Registration Form -->
    <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] w-full max-w-4xl">
        
        <div class="border-b border-gray-100 pb-4 mb-6 flex justify-between items-center">
            <h2 class="text-lg font-bold text-gray-800">Add New Member</h2>
        </div>

        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Role Selection Cards -->
                <div class="md:col-span-2 mb-4">
                    <label class="block text-sm font-bold text-gray-700 mb-5">Assign Operational Role <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <label class="cursor-pointer group">
                            <input type="radio" name="role" value="waste_collector" class="peer hidden" required {{ old('role') == 'waste_collector' ? 'checked' : '' }}>
                            <div class="p-6 border-2 border-gray-50 rounded-2xl bg-gray-50/20 transition-all peer-checked:border-[#19817E] peer-checked:bg-teal-50/30 hover:bg-gray-50/50 flex items-center gap-4">
                                <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center text-[#19817E] shadow-sm group-hover:scale-110 transition peer-checked:bg-[#19817E] peer-checked:text-white">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                </div>
                                <div>
                                    <div class="text-[10px] font-black text-[#19817E] uppercase tracking-widest mb-0.5">Field Staff</div>
                                    <div class="text-sm font-black text-gray-800 tracking-tight">Waste Collector</div>
                                </div>
                                <div class="ml-auto opacity-0 peer-checked:opacity-100 transition-opacity">
                                    <div class="w-5 h-5 bg-[#19817E] rounded-full flex items-center justify-center">
                                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                </div>
                            </div>
                        </label>

                        <label class="cursor-pointer group">
                            <input type="radio" name="role" value="user" class="peer hidden" {{ old('role') == 'user' ? 'checked' : '' }}>
                            <div class="p-6 border-2 border-gray-50 rounded-2xl bg-gray-50/20 transition-all peer-checked:border-blue-600 peer-checked:bg-blue-50/30 hover:bg-gray-50/50 flex items-center gap-4">
                                <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center text-blue-600 shadow-sm group-hover:scale-110 transition peer-checked:bg-blue-600 peer-checked:text-white">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                                </div>
                                <div>
                                    <div class="text-[10px] font-black text-blue-600 uppercase tracking-widest mb-0.5">Resident</div>
                                    <div class="text-sm font-black text-gray-800 tracking-tight">General User</div>
                                </div>
                                <div class="ml-auto opacity-0 peer-checked:opacity-100 transition-opacity">
                                    <div class="w-5 h-5 bg-blue-600 rounded-full flex items-center justify-center">
                                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </div>
                    <x-input-error :messages="$errors->get('role')" class="mt-2" />
                </div>

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-bold text-gray-700 mb-2">Full Name <span class="text-red-500">*</span></label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus placeholder="e.g. John Doe" 
                        class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-sm rounded-xl focus:ring-[#19817E] focus:border-[#19817E] block p-3 transition">
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-sm font-bold text-gray-700 mb-2">Email Address <span class="text-red-500">*</span></label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required placeholder="e.g. user@cleancity.com"
                        class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-sm rounded-xl focus:ring-[#19817E] focus:border-[#19817E] block p-3 transition">
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Phone Number -->
                <div>
                    <label for="phone" class="block text-sm font-bold text-gray-700 mb-2">Phone Number</label>
                    <input id="phone" type="text" name="phone" value="{{ old('phone') }}" placeholder="+1 234 567 8900"
                        class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-sm rounded-xl focus:ring-[#19817E] focus:border-[#19817E] block p-3 transition">
                    <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                </div>

                <!-- Address -->
                <div>
                    <label for="address" class="block text-sm font-bold text-gray-700 mb-2">Location / Address</label>
                    <input id="address" type="text" name="address" value="{{ old('address') }}" placeholder="Location Details"
                        class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-sm rounded-xl focus:ring-[#19817E] focus:border-[#19817E] block p-3 transition">
                    <x-input-error :messages="$errors->get('address')" class="mt-2" />
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-bold text-gray-700 mb-2">Password <span class="text-red-500">*</span></label>
                    <input id="password" type="password" name="password" required placeholder="••••••••"
                        class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-sm rounded-xl focus:ring-[#19817E] focus:border-[#19817E] block p-3 transition">
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-bold text-gray-700 mb-2">Confirm Password <span class="text-red-500">*</span></label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required placeholder="••••••••"
                        class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-sm rounded-xl focus:ring-[#19817E] focus:border-[#19817E] block p-3 transition">
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>
            </div>

            <div class="flex items-center justify-end mt-8 pt-6 border-t border-gray-100 gap-4">
                <button type="submit" class="bg-[#19817E] text-white text-sm font-bold py-3 px-8 rounded-xl hover:bg-teal-700 focus:ring-4 focus:ring-teal-100 transition shadow-sm">
                    Submit Registration
                </button>
            </div>
        </form>
    </div>

@endsection
