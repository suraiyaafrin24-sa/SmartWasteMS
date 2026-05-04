@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')

    <div class="mb-8 mt-2">
        <h1 class="text-[28px] font-black tracking-tight text-gray-900 mb-1">Edit User Profile</h1>
        <p class="text-sm font-medium text-gray-500">Update system user details and operational roles.</p>
    </div>

    <div class="max-w-4xl mx-auto px-6 md:px-16">
        <div class="bg-white rounded-3xl p-10 border border-gray-100 shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] w-full mx-auto">
        
        <div class="border-b border-gray-100 pb-4 mb-6 flex justify-between items-center">
            <h2 class="text-lg font-bold text-gray-800">Modify Member: {{ $user->name }}</h2>
        </div>

        <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Role Selection -->
                <div class="md:col-span-2 mb-4">
                    <label for="role" class="block text-sm font-bold text-gray-700 mb-2">Assign Operational Role <span class="text-red-500">*</span></label>
                    <select id="role" name="role" required 
                        class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-sm rounded-xl focus:ring-[#19817E] focus:border-[#19817E] block p-3 transition appearance-none cursor-pointer">
                        <option value="admin" {{ (old('role', $user->role) == 'admin') ? 'selected' : '' }}>Administrator (System Admin)</option>
                        <option value="waste_collector" {{ (old('role', $user->role) == 'waste_collector') ? 'selected' : '' }}>Waste Collector (Field Staff)</option>
                        <option value="user" {{ (old('role', $user->role) == 'user') ? 'selected' : '' }}>General User (Resident)</option>
                    </select>
                    <x-input-error :messages="$errors->get('role')" class="mt-2" />
                </div>

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-bold text-gray-700 mb-2">Full Name <span class="text-red-500">*</span></label>
                    <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required autofocus placeholder="e.g. John Doe" 
                        class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-sm rounded-xl focus:ring-[#19817E] focus:border-[#19817E] block p-3 transition">
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-sm font-bold text-gray-700 mb-2">Email Address <span class="text-red-500">*</span></label>
                    <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required placeholder="e.g. user@cleancity.com"
                        class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-sm rounded-xl focus:ring-[#19817E] focus:border-[#19817E] block p-3 transition">
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Phone Number -->
                <div>
                    <label for="phone" class="block text-sm font-bold text-gray-700 mb-2">Phone Number</label>
                    <input id="phone" type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="+1 234 567 8900"
                        class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-sm rounded-xl focus:ring-[#19817E] focus:border-[#19817E] block p-3 transition">
                    <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                </div>

                <!-- Sector -->
                <div>
                    <label for="sector" class="block text-sm font-bold text-gray-700 mb-2">Uttara Sector</label>
                    <select id="sector" name="sector" 
                        class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-sm rounded-xl focus:ring-[#19817E] focus:border-[#19817E] block p-3 transition appearance-none cursor-pointer">
                        <option value="">Select Sector</option>
                        @for($i = 1; $i <= 18; $i++)
                            <option value="Sector {{ $i }}" {{ (old('sector', $user->sector) == "Sector $i") ? 'selected' : '' }}>Sector {{ $i }}</option>
                        @endfor
                    </select>
                    <x-input-error :messages="$errors->get('sector')" class="mt-2" />
                </div>

                <!-- Road -->
                <div>
                    <label for="road" class="block text-sm font-bold text-gray-700 mb-2">Road / Block / House</label>
                    <input id="road" type="text" name="road" value="{{ old('road', $user->road) }}" placeholder="e.g. Road 12, Sector 4"
                        class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-sm rounded-xl focus:ring-[#19817E] focus:border-[#19817E] block p-3 transition">
                    <x-input-error :messages="$errors->get('road')" class="mt-2" />
                </div>

                <!-- Address -->
                <div class="md:col-span-2">
                    <label for="address" class="block text-sm font-bold text-gray-700 mb-2">Full Address Details</label>
                    <input id="address" type="text" name="address" value="{{ old('address', $user->address) }}" placeholder="Detailed Landmark description"
                        class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-sm rounded-xl focus:ring-[#19817E] focus:border-[#19817E] block p-3 transition">
                    <x-input-error :messages="$errors->get('address')" class="mt-2" />
                </div>
            </div>

            <div class="flex items-center justify-end mt-8 pt-6 border-t border-gray-100 gap-4">
                <a href="{{ route('admin.users') }}" class="text-sm font-bold text-gray-400 hover:text-gray-600 transition">Cancel</a>
                <button type="submit" class="bg-[#19817E] text-white text-sm font-bold py-3 px-8 rounded-xl hover:bg-teal-700 focus:ring-4 focus:ring-teal-100 transition shadow-sm">
                    Update Member
                </button>
            </div>
        </form>
    </div>
    </div>

@endsection
