@extends('layouts.waste_collector')

@section('title', 'Collector Settings')

@section('content')
    <div class="mb-8 mt-2">
        <h1 class="text-2xl font-black tracking-tight text-slate-800 mb-1">Staff Settings</h1>
        <p class="text-[13px] font-medium text-slate-400">Manage your collector profile and shift notifications.</p>
    </div>

    <div class="bg-white rounded-3xl p-8 border border-gray-50 shadow-sm shadow-gray-100 max-w-4xl">
        <div class="space-y-8">
            <div class="border-b border-gray-50 pb-4">
                <h3 class="text-lg font-black text-slate-800 tracking-tight">Collector Profile</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-2">Full Name</label>
                    <input type="text" value="{{ Auth::user()->name }}" class="w-full bg-slate-50 border-none text-slate-800 text-sm rounded-xl py-3.5 px-4 focus:ring-1 focus:ring-teal-200 transition-all outline-none font-bold">
                </div>
                <div>
                    <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-2">Staff ID</label>
                    <input type="text" value="CL-{{ str_pad(Auth::user()->id, 4, '0', STR_PAD_LEFT) }}" disabled class="w-full bg-slate-100 border-none text-slate-400 text-sm rounded-xl py-3.5 px-4 outline-none cursor-not-allowed font-bold">
                </div>
                <div>
                    <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-2">Contact Number</label>
                    <input type="text" value="{{ Auth::user()->phone }}" class="w-full bg-slate-50 border-none text-slate-800 text-sm rounded-xl py-3.5 px-4 focus:ring-1 focus:ring-teal-200 transition-all outline-none font-bold">
                </div>
                <div>
                    <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-2">Official Email</label>
                    <input type="email" value="{{ Auth::user()->email }}" class="w-full bg-slate-50 border-none text-slate-800 text-sm rounded-xl py-3.5 px-4 focus:ring-1 focus:ring-teal-200 transition-all outline-none font-bold">
                </div>
            </div>

            <div class="pt-6 border-t border-gray-50 flex items-center justify-between">
                <button class="bg-[#19817E] text-white text-[13px] font-black py-3.5 px-10 rounded-xl hover:bg-teal-700 transition shadow-lg shadow-teal-700/20 uppercase tracking-widest">
                    Update Profile
                </button>
                <div class="flex items-center gap-3 text-[10px] font-black text-emerald-500 uppercase tracking-[0.2em]">
                    <span class="relative flex h-2 w-2">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    Identity Verified
                </div>
            </div>
        </div>
    </div>
@endsection
