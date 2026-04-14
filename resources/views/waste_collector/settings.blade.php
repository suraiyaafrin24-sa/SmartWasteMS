@extends('layouts.waste_collector')

@section('title', 'Collector Settings')

@section('content')
    <div class="mb-8 mt-2">
        <h1 class="text-[28px] font-black tracking-tight text-gray-900 mb-1">Staff Settings</h1>
        <p class="text-sm font-medium text-gray-500">Manage your collector profile and shift notifications.</p>
    </div>

    <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm max-w-4xl">
        <div class="space-y-6">
            <div class="border-b border-gray-100 pb-4">
                <h3 class="text-lg font-bold text-gray-800">Collector Profile</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Full Name</label>
                    <input type="text" value="{{ Auth::user()->name }}" class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-sm rounded-xl p-3 focus:ring-amber-500 focus:border-amber-500 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Staff ID</label>
                    <input type="text" value="CL-{{ str_pad(Auth::user()->id, 4, '0', STR_PAD_LEFT) }}" disabled class="w-full bg-gray-100 border border-gray-200 text-gray-400 text-sm rounded-xl p-3 outline-none cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Contact Number</label>
                    <input type="text" value="{{ Auth::user()->phone }}" class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-sm rounded-xl p-3 focus:ring-amber-500 focus:border-amber-500 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Official Email</label>
                    <input type="email" value="{{ Auth::user()->email }}" class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-sm rounded-xl p-3 focus:ring-amber-500 focus:border-amber-500 outline-none transition-all">
                </div>
            </div>

            <div class="pt-6 border-t border-gray-100 flex items-center justify-between">
                <button class="bg-[#19817E] text-white text-sm font-bold py-3 px-8 rounded-xl hover:bg-teal-700 transition shadow-sm">
                    Update Profile
                </button>
                <div class="flex items-center gap-2 text-xs font-bold text-emerald-600 uppercase">
                    <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                    Identity Verified
                </div>
            </div>
        </div>
    </div>
@endsection
