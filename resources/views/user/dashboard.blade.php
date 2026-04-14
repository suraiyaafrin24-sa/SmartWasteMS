@extends('layouts.user')

@section('title', 'My Dashboard')

@section('content')
    <div class="mb-8 mt-2 flex justify-between items-end">
        <div>
            <h1 class="text-[28px] font-black tracking-tight text-gray-900 mb-1">My Dashboard</h1>
            <p class="text-sm font-medium text-gray-500">Track your waste collection requests and history.</p>
        </div>
        <div>
            <button class="bg-[#19817E] text-white text-sm font-semibold py-2.5 px-6 rounded-xl hover:bg-teal-700 transition shadow-sm flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                New Collection Request
            </button>
        </div>
    </div>

    <!-- User Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm flex flex-col justify-between h-40">
            <div>
                <div class="text-blue-500 font-bold text-sm mb-1.5 flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    Total Requests
                </div>
                <div class="text-[32px] font-black text-gray-800 leading-none tracking-tight">12</div>
            </div>
            <p class="text-[13px] text-gray-500 font-medium">Since joining SmartBin</p>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm flex flex-col justify-between h-40">
            <div>
                <div class="text-amber-500 font-bold text-sm mb-1.5 flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Pending Pickup
                </div>
                <div class="text-[32px] font-black text-gray-800 leading-none tracking-tight">1</div>
            </div>
            <p class="text-[13px] text-gray-500 font-medium tracking-tight">Assigned for tomorrow 10 AM</p>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm flex flex-col justify-between h-40">
            <div>
                <div class="text-emerald-500 font-bold text-sm mb-1.5 flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Completed
                </div>
                <div class="text-[32px] font-black text-gray-800 leading-none tracking-tight">11</div>
            </div>
            <p class="text-[13px] text-gray-500 font-medium">Eco-contribution: 120kg saved</p>
        </div>
    </div>

    <!-- Latest Requests Table -->
    <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-900 tracking-tight">Recent Requests</h2>
            <button class="text-teal-600 text-sm font-bold hover:underline">View All History</button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead>
                    <tr class="text-gray-400 font-bold border-b border-gray-100 uppercase text-[11px] tracking-wider">
                        <th class="pb-3 px-2">Type</th>
                        <th class="pb-3 px-2">Scheduled Date</th>
                        <th class="pb-3 px-2">Status</th>
                        <th class="pb-3 px-2">Collector</th>
                        <th class="pb-3 px-2 text-right">Details</th>
                    </tr>
                </thead>
                <tbody class="text-gray-800 font-semibold divide-y divide-gray-50">
                    <tr class="hover:bg-gray-50 transition">
                        <td class="py-4 px-2">Recyclable</td>
                        <td class="py-4 px-2">Oct 24, 2024</td>
                        <td class="py-4 px-2"><span class="px-2.5 py-1 rounded-md text-[11px] font-black bg-amber-100 text-amber-600 tracking-wide uppercase">Scheduled</span></td>
                        <td class="py-4 px-2 text-gray-500">Michael S.</td>
                        <td class="py-4 px-2 text-right">
                            <button class="text-teal-600 hover:text-teal-700">View</button>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="py-4 px-2">General Waste</td>
                        <td class="py-4 px-2">Oct 20, 2024</td>
                        <td class="py-4 px-2"><span class="px-2.5 py-1 rounded-md text-[11px] font-black bg-emerald-100 text-emerald-600 tracking-wide uppercase">Completed</span></td>
                        <td class="py-4 px-2 text-gray-500">David L.</td>
                        <td class="py-4 px-2 text-right">
                            <button class="text-teal-600 hover:text-teal-700">View</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
