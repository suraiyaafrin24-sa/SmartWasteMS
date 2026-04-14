@extends('layouts.user')

@section('title', 'User Settings')

@section('content')
    <div class="mb-8 mt-2">
        <h1 class="text-2xl font-black tracking-tight text-slate-800 mb-1">Account Settings</h1>
        <p class="text-[13px] font-medium text-slate-400">Update your personal information and profile security.</p>
    </div>

    <div class="bg-white rounded-3xl p-8 border border-gray-50 shadow-sm shadow-gray-100 max-w-4xl">
        <div class="space-y-8">
            <div class="border-b border-gray-50 pb-4">
                <h3 class="text-lg font-black text-slate-800 tracking-tight">Profile Information</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-2">Full Name</label>
                    <input type="text" value="{{ Auth::user()->name }}" class="w-full bg-slate-50 border-none text-slate-800 text-sm rounded-xl py-3.5 px-4 focus:ring-1 focus:ring-teal-200 transition-all outline-none font-bold">
                </div>
                <div>
                    <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-2">Email Address</label>
                    <input type="email" value="{{ Auth::user()->email }}" class="w-full bg-slate-50 border-none text-slate-800 text-sm rounded-xl py-3.5 px-4 focus:ring-1 focus:ring-teal-200 transition-all outline-none font-bold">
                </div>
                <div>
                    <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-2">Phone Number</label>
                    <input type="text" value="{{ Auth::user()->phone }}" class="w-full bg-slate-50 border-none text-slate-800 text-sm rounded-xl py-3.5 px-4 focus:ring-1 focus:ring-teal-200 transition-all outline-none font-bold">
                </div>
                <div>
                    <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-2">Home Address</label>
                    <input type="text" value="{{ Auth::user()->address }}" class="w-full bg-slate-50 border-none text-slate-800 text-sm rounded-xl py-3.5 px-4 focus:ring-1 focus:ring-teal-200 transition-all outline-none font-bold">
                </div>
            </div>

            <div class="pt-6 border-t border-gray-50 flex justify-end">
                <button class="bg-[#19817E] text-white text-[13px] font-black py-3.5 px-10 rounded-xl hover:bg-teal-700 transition shadow-lg shadow-teal-700/20 uppercase tracking-widest">
                    Save Changes
                </button>
            </div>
        </div>
    </div>
@endsection
