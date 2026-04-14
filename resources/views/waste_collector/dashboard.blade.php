@extends('layouts.waste_collector')

@section('title', 'Collector Dashboard')

@section('content')
    <div class="mb-8 mt-2 flex justify-between items-end">
        <div>
            <h1 class="text-[28px] font-black tracking-tight text-gray-900 mb-1">My Task Center</h1>
            <p class="text-sm font-medium text-gray-500">Manage your collection route and update pickup status.</p>
        </div>
        <div class="flex gap-3">
             <button class="bg-gray-900 text-white text-sm font-bold py-2.5 px-6 rounded-xl hover:bg-black transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                Open Map View
            </button>
        </div>
    </div>

    <!-- Collector Status Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-[#19817E] text-white rounded-3xl p-6 shadow-sm flex flex-col justify-between h-40">
            <div>
                <div class="text-teal-100 font-bold text-sm mb-1.5 flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Assigned Today
                </div>
                <div class="text-[32px] font-black leading-none tracking-tight">8</div>
            </div>
            <p class="text-[13px] text-teal-500 font-bold bg-white/10 w-fit px-2 py-1 rounded-lg">4 remaining</p>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm flex flex-col justify-between h-40">
            <div>
                <div class="text-emerald-500 font-bold text-sm mb-1.5 flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Completed
                </div>
                <div class="text-[32px] font-black text-gray-800 leading-none tracking-tight">4</div>
            </div>
            <p class="text-[13px] text-emerald-500 font-bold">50% efficiency streak</p>
        </div>

        <div class="bg-gray-900 text-white rounded-3xl p-6 shadow-sm flex flex-col justify-between h-40">
            <div>
                <div class="text-gray-400 font-bold text-sm mb-1.5 flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Safety Alerts
                </div>
                <div class="text-[32px] font-black leading-none tracking-tight">0</div>
            </div>
            <p class="text-[13px] text-gray-400">All zones clear</p>
        </div>
    </div>

    <!-- Tasks Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- New Tasks -->
        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm">
            <h2 class="text-xl font-bold text-gray-900 tracking-tight mb-6">Unaccepted Job Offers</h2>
            <div class="space-y-4">
                <div class="p-4 bg-orange-50 border border-orange-100 rounded-2xl flex justify-between items-center group transition hover:border-orange-200">
                    <div>
                        <div class="text-orange-600 text-xs font-black uppercase tracking-wider mb-1">Emergency Pickup</div>
                        <h4 class="text-sm font-bold text-gray-800">8 Edigbe Street, District A</h4>
                        <p class="text-[11px] text-gray-500 font-medium">Requested by: Suraiya Akter • 25kg Estimate</p>
                    </div>
                    <div class="flex gap-2">
                         <button class="bg-orange-600 text-white text-xs font-bold px-4 py-2 rounded-xl shadow-sm hover:bg-orange-700 transition">Accept</button>
                         <button class="text-gray-400 hover:text-red-500 p-2"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Current Route -->
        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm">
            <h2 class="text-xl font-bold text-gray-900 tracking-tight mb-6">Current active Route</h2>
            <div class="space-y-4">
                <div class="p-4 border border-gray-100 rounded-2xl flex justify-between items-center">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-teal-100 text-teal-700 flex items-center justify-center font-bold">1</div>
                        <div>
                            <h4 class="text-sm font-bold text-gray-800">123 Elm Street</h4>
                            <p class="text-xs text-emerald-500 font-bold uppercase tracking-widest text-[10px]">On Route Now</p>
                        </div>
                    </div>
                    <div>
                         <button class="bg-emerald-100 text-emerald-700 text-xs font-bold px-4 py-2 rounded-xl hover:bg-emerald-200 transition">Mark Done</button>
                    </div>
                </div>

                <div class="p-4 border border-gray-100 rounded-2xl flex justify-between items-center opacity-70">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-gray-100 text-gray-500 flex items-center justify-center font-bold">2</div>
                        <div>
                            <h4 class="text-sm font-bold text-gray-700">45 High Street</h4>
                            <p class="text-xs text-gray-400 font-bold uppercase tracking-widest text-[10px]">Next in queue</p>
                        </div>
                    </div>
                    <button class="text-gray-400 p-2"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9m-2.827 0l-4.244-4.243"></path></svg></button>
                </div>
            </div>
        </div>
    </div>
@endsection
