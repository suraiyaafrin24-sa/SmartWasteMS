@extends('layouts.user')

@section('title', 'User Settings')

@section('content')
    <div class="mb-8 mt-2">
        <h1 class="text-[28px] font-black tracking-tight text-gray-900 mb-1">Account Settings</h1>
        <p class="text-sm font-medium text-gray-500">Update your personal information and profile security.</p>
    </div>

    <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm max-w-4xl">
        <div class="space-y-6">
            <div class="border-b border-gray-100 pb-4">
                <h3 class="text-lg font-bold text-gray-800">Profile Information</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Full Name</label>
                    <input type="text" value="{{ Auth::user()->name }}" class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-sm rounded-xl p-3 focus:ring-teal-500 focus:border-teal-500 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Email Address</label>
                    <input type="email" value="{{ Auth::user()->email }}" class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-sm rounded-xl p-3 focus:ring-teal-500 focus:border-teal-500 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Phone Number</label>
                    <input type="text" value="{{ Auth::user()->phone }}" class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-sm rounded-xl p-3 focus:ring-teal-500 focus:border-teal-500 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Home Address</label>
                    <input type="text" value="{{ Auth::user()->address }}" class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-sm rounded-xl p-3 focus:ring-teal-500 focus:border-teal-500 outline-none transition-all">
                </div>
            </div>

            <div class="pt-6 border-t border-gray-100">
                <button class="bg-[#19817E] text-white text-sm font-bold py-3 px-8 rounded-xl hover:bg-teal-700 transition shadow-sm">
                    Save Changes
                </button>
            </div>
        </div>
    </div>
@endsection
