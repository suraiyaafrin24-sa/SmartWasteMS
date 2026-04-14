@extends('layouts.waste_collector')

@section('title', 'Collector Dashboard')

@section('content')
    <div class="mb-8 mt-2 flex justify-between items-end">
        <div>
            <h1 class="text-2xl font-black tracking-tight text-slate-800 mb-1">My Task Center</h1>
            <p class="text-[13px] font-medium text-slate-400">Manage your collection route and update pickup status.</p>
        </div>
        <div>
            <button class="bg-[#0D161F] text-white text-[13px] font-black py-2.5 px-6 rounded-xl hover:bg-black transition flex items-center gap-2 shadow-lg shadow-slate-900/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                Open Map View
            </button>
        </div>
    </div>

    <!-- Collector Status Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <!-- Assigned Today Card (Teal) -->
        <div class="bg-[#19817E] rounded-3xl p-6 shadow-xl shadow-teal-900/20 flex flex-col justify-between h-40 relative overflow-hidden group">
            <div class="absolute right-4 top-1/2 -translate-y-1/2 opacity-10 group-hover:scale-110 transition duration-500">
                <svg class="w-20 h-20 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
            </div>
            <div>
                <div class="text-teal-100 font-bold text-[13px] mb-1.5 flex items-center gap-1.5 leading-none">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Assigned Today
                </div>
                <div class="text-[32px] font-black text-white leading-none tracking-tight">8</div>
            </div>
            <p class="text-[11px] text-white font-black bg-white/10 w-fit px-3 py-1.5 rounded-lg uppercase tracking-wider">4 remaining</p>
        </div>

        <!-- Completed Card (White) -->
        <div class="bg-white rounded-3xl p-6 border border-gray-50 shadow-sm flex flex-col justify-between h-40 relative overflow-hidden group hover:border-[#F8F9FA] transition-colors">
            <div class="absolute right-4 top-1/2 -translate-y-1/2 opacity-[0.03] group-hover:scale-110 transition duration-500">
                <svg class="w-20 h-20 text-emerald-500" fill="currentColor" viewBox="0 0 24 24"><path d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4L9 16.2z"/></svg>
            </div>
            <div>
                <div class="text-emerald-500 font-bold text-[13px] mb-1.5 flex items-center gap-1.5 leading-none">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Completed
                </div>
                <div class="text-[32px] font-black text-slate-800 leading-none tracking-tight">4</div>
            </div>
            <p class="text-[12px] text-emerald-500 font-bold leading-none">50% efficiency streak</p>
        </div>

        <!-- Safety Alerts Card (Dark) -->
        <div class="bg-[#0D161F] rounded-3xl p-6 shadow-xl shadow-slate-900/20 flex flex-col justify-between h-40 relative overflow-hidden group">
             <div class="absolute right-4 top-1/2 -translate-y-1/2 opacity-10 group-hover:scale-110 transition duration-500">
                <svg class="w-20 h-20 text-slate-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
            </div>
            <div>
                <div class="text-slate-500 font-bold text-[13px] mb-1.5 flex items-center gap-1.5 leading-none">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Safety Alerts
                </div>
                <div class="text-[32px] font-black text-white leading-none tracking-tight">0</div>
            </div>
            <p class="text-[12px] text-slate-500 font-bold leading-none">All zones clear</p>
        </div>
    </div>

    <!-- Tasks Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- New Tasks -->
        <div class="bg-white rounded-3xl p-8 border border-gray-50 shadow-sm shadow-gray-100">
            <h2 class="text-xl font-black text-slate-800 tracking-tight leading-none mb-8">Unaccepted Job Offers</h2>
            <div class="space-y-4">
                <div class="p-6 border border-gray-50 rounded-[32px] flex justify-between items-center group transition-all duration-300 hover:border-[#19817E]/20 hover:bg-gray-50/30">
                    <div class="flex-1">
                        <div class="text-orange-500 text-[10px] font-black uppercase tracking-widest mb-1.5">Emergency Pickup</div>
                        <h4 class="text-base font-black text-slate-800 leading-tight mb-1">8 Edigbe Street, District A</h4>
                        <p class="text-[12px] text-slate-400 font-medium">Requested by: Suraiya Akter • 25kg Estimate</p>
                    </div>
                    <div class="flex gap-3">
                         <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-300 cursor-not-allowed">
                             <div class="w-5 h-2.5 bg-slate-200/50 rounded-full"></div>
                         </div>
                         <button class="text-slate-300 hover:text-red-500 transition-colors bg-gray-50 p-3 rounded-2xl">
                             <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                         </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Current Route -->
        <div class="bg-white rounded-3xl p-8 border border-gray-50 shadow-sm shadow-gray-100">
            <h2 class="text-xl font-black text-slate-800 tracking-tight leading-none mb-8">Current active Route</h2>
            <div class="space-y-4">
                <div class="p-5 border border-gray-50 rounded-[32px] flex justify-between items-center group bg-white hover:border-emerald-100 transition-all duration-300">
                    <div class="flex items-center gap-5">
                        <div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center font-black text-sm border border-emerald-100 shadow-sm">1</div>
                        <div>
                            <h4 class="text-base font-black text-slate-800 leading-tight">123 Elm Street</h4>
                            <p class="text-[10px] text-emerald-500 font-black uppercase tracking-widest mt-1">On Route Now</p>
                        </div>
                    </div>
                    <div>
                         <button class="bg-[#E2F0EC] text-[#19817E] text-[11px] font-black px-6 py-2.5 rounded-full hover:bg-[#19817E] hover:text-white transition-all duration-300 uppercase tracking-widest leading-none shadow-sm shadow-teal-700/5">Mark Done</button>
                    </div>
                </div>

                <div class="p-5 border border-gray-50 rounded-[32px] flex justify-between items-center opacity-40 hover:opacity-100 transition-all duration-500">
                    <div class="flex items-center gap-5">
                        <div class="w-12 h-12 rounded-full bg-slate-50 text-slate-400 flex items-center justify-center font-black text-sm border border-gray-100">2</div>
                        <div>
                            <h4 class="text-base font-black text-slate-700 leading-tight">45 High Street</h4>
                            <p class="text-[10px] text-slate-300 font-black uppercase tracking-widest mt-1">Next in queue</p>
                        </div>
                    </div>
                    <button class="text-slate-300 p-2 transform rotate-90"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></button>
                </div>
            </div>
        </div>
    </div>
@endsection
