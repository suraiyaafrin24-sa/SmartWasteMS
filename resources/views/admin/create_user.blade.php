@extends('layouts.admin')

@section('title', 'Manage System Users')

@section('content')

    <div class="mb-8">
        <h1 class="text-[28px] font-black tracking-tight text-gray-900 mb-1">User & Staff Directory</h1>
        <p class="text-sm font-medium text-gray-500">Register and manage Waste Collectors, Admins, and General Users.</p>
    </div>

    <!-- Stats summary before the form -->
    <div class="grid grid-cols-3 gap-6 mb-8">
        <div class="bg-[#19817E] text-white rounded-2xl p-6 shadow-sm border border-teal-800/10">
            <div class="text-teal-100 font-bold text-sm mb-1">Total Collectors</div>
            <div class="text-3xl font-black">25</div>
        </div>
        <div class="bg-white text-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="text-gray-500 font-bold text-sm mb-1">System Admins</div>
            <div class="text-3xl font-black">4</div>
        </div>
        <div class="bg-white text-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="text-gray-500 font-bold text-sm mb-1">General Users</div>
            <div class="text-3xl font-black">1,245</div>
        </div>
    </div>

    <!-- Registration Form -->
    <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] w-full max-w-4xl">
        
        <div class="border-b border-gray-100 pb-4 mb-6 flex justify-between items-center">
            <h2 class="text-lg font-bold text-gray-800">Add New Member</h2>
        </div>

        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Role Selection -->
                <div class="md:col-span-2">
                    <label for="role" class="block text-sm font-bold text-gray-700 mb-2">Assign Role <span class="text-red-500">*</span></label>
                    <select id="role" name="role" required class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-sm rounded-xl focus:ring-[#19817E] focus:border-[#19817E] block p-3 transition">
                        <option value="waste_collector">Waste Collector (Field Staff)</option>
                        <option value="user">General User (Customer)</option>
                    </select>
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
