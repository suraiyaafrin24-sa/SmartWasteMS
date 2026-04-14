@extends('layouts.user')

@section('title', 'My Dashboard')

@section('content')
    <div class="mb-10 mt-2 flex justify-between items-end">
        <div>
            <h1 class="text-2xl font-black tracking-tight text-slate-800 mb-1">My Dashboard</h1>
            <p class="text-[13px] font-medium text-slate-400">Track your waste collection requests and history.</p>
        </div>
        <div>
            <button class="bg-[#19817E] text-white text-[13px] font-bold py-3 px-7 rounded-2xl hover:bg-teal-700 transition shadow-lg shadow-teal-700/20 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                New Collection Request
            </button>
        </div>
    </div>

    <!-- User Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
        <!-- Total Requests Card -->
        <div class="bg-white rounded-[40px] p-8 border border-gray-50 flex flex-col justify-between h-48 relative overflow-hidden group shadow-sm shadow-gray-100/50">
            <div class="absolute -right-4 -top-4 opacity-[0.05] group-hover:scale-110 group-hover:opacity-[0.08] transition duration-700 ease-in-out pointer-events-none">
                <svg class="w-32 h-32 text-blue-500" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1s-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm2 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/></svg>
            </div>
            <div>
                <div class="text-blue-500 font-bold text-[13px] mb-2 flex items-center gap-2 leading-none">
                    <div class="w-1.5 h-1.5 rounded-full bg-blue-500"></div>
                    Total Requests
                </div>
                <div class="text-[42px] font-black text-slate-800 leading-none tracking-tight">12</div>
            </div>
            <p class="text-[12px] text-slate-400 font-medium tracking-wide">Since joining SmartBin</p>
        </div>

        <!-- Pending Pickup Card -->
        <div class="bg-white rounded-[40px] p-8 border border-gray-50 flex flex-col justify-between h-48 relative overflow-hidden group shadow-sm shadow-gray-100/50">
            <div class="absolute -right-4 -top-4 opacity-[0.05] group-hover:scale-110 group-hover:opacity-[0.08] transition duration-700 ease-in-out pointer-events-none">
                <svg class="w-32 h-32 text-amber-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 14.5c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zm1.5-4.5h-3V7h3v5z"/></svg>
            </div>
            <div>
                <div class="text-amber-500 font-bold text-[13px] mb-2 flex items-center gap-2 leading-none">
                    <div class="w-1.5 h-1.5 rounded-full bg-amber-500"></div>
                    Pending Pickup
                </div>
                <div class="text-[42px] font-black text-slate-800 leading-none tracking-tight">1</div>
            </div>
            <p class="text-[12px] text-slate-400 font-medium tracking-wide">Assigned for tomorrow 10 AM</p>
        </div>

        <!-- Completed Card -->
        <div class="bg-white rounded-[40px] p-8 border border-gray-50 flex flex-col justify-between h-48 relative overflow-hidden group shadow-sm shadow-gray-100/50">
            <div class="absolute -right-4 -top-4 opacity-[0.05] group-hover:scale-110 group-hover:opacity-[0.08] transition duration-700 ease-in-out pointer-events-none">
                <svg class="w-32 h-32 text-emerald-500" fill="currentColor" viewBox="0 0 24 24"><path d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4L9 16.2z"/></svg>
            </div>
            <div>
                <div class="text-emerald-500 font-bold text-[13px] mb-2 flex items-center gap-2 leading-none">
                    <div class="w-1.5 h-1.5 rounded-full bg-emerald-500"></div>
                    Completed
                </div>
                <div class="text-[42px] font-black text-slate-800 leading-none tracking-tight">11</div>
            </div>
            <p class="text-[12px] text-slate-400 font-medium tracking-wide">Eco-contribution: 120kg saved</p>
        </div>
    </div>

    <!-- Recent Requests Table -->
    <div class="bg-white rounded-[40px] p-10 border border-gray-50 shadow-sm shadow-gray-100/50">
        <div class="flex justify-between items-center mb-10">
            <h2 class="text-xl font-black text-slate-800 tracking-tight leading-none">Recent Requests</h2>
            <button class="text-[#19817E] text-[12px] font-black hover:underline uppercase tracking-[0.2em]">View All History</button>
        </div>

        <div class="overflow-x-auto overflow-y-hidden">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead>
                    <tr class="text-slate-300 font-black border-b border-gray-50 uppercase text-[10px] tracking-[0.2em] leading-none">
                        <th class="pb-6 px-2">Type</th>
                        <th class="pb-6 px-2">Scheduled Date</th>
                        <th class="pb-6 px-2 text-center">Status</th>
                        <th class="pb-6 px-2">Collector</th>
                        <th class="pb-6 px-2 text-right">Details</th>
                    </tr>
                </thead>
                <tbody class="text-slate-700 divide-y divide-gray-50/50">
                    <tr class="hover:bg-gray-50/50 transition-all duration-300 group">
                        <td class="py-6 px-2 font-black text-slate-800">Recyclable</td>
                        <td class="py-6 px-2 font-bold text-slate-400">Oct 24, 2024</td>
                        <td class="py-6 px-2 text-center">
                            <span class="px-3 py-1.5 rounded-xl text-[10px] font-black bg-amber-50 text-amber-600 tracking-widest uppercase">Scheduled</span>
                        </td>
                        <td class="py-6 px-2 font-bold text-slate-500">Michael S.</td>
                        <td class="py-6 px-2 text-right">
                            <button class="text-[#19817E] hover:text-teal-700 text-[11px] font-black transition-colors uppercase tracking-widest">View</button>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50/50 transition-all duration-300 group">
                        <td class="py-6 px-2 font-black text-slate-800">General Waste</td>
                        <td class="py-6 px-2 font-bold text-slate-400">Oct 20, 2024</td>
                        <td class="py-6 px-2 text-center">
                            <span class="px-3 py-1.5 rounded-xl text-[10px] font-black bg-emerald-50 text-emerald-600 tracking-widest uppercase">Completed</span>
                        </td>
                        <td class="py-6 px-2 font-bold text-slate-500">David L.</td>
                        <td class="py-6 px-2 text-right">
                            <button class="text-[#19817E] hover:text-teal-700 text-[11px] font-black transition-colors uppercase tracking-widest">View</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
