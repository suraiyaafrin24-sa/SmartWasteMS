@extends('layouts.admin')

@section('title', 'Admin Dashboard Overview')

@section('content')

    <!-- Dashboard Header -->
    <div class="mb-8 mt-2 flex justify-between items-end">
        <div>
            <h1 class="text-2xl font-black tracking-tight text-slate-800 mb-1">Overview Dashboard</h1>
            <p class="text-[13px] font-medium text-slate-400">Monitor collections, active staff, and system performance.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.reports') }}" class="bg-gray-50 text-slate-600 text-[13px] font-bold py-2.5 px-5 rounded-xl border border-gray-100 hover:bg-gray-100 transition shadow-sm">Filter Report</a>
            <a href="{{ route('admin.assign_duty') }}" class="bg-[#19817E] text-white text-[13px] font-bold py-2.5 px-5 rounded-xl hover:bg-teal-700 transition shadow-lg shadow-teal-700/20">+ Assign Duty</a>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
        <!-- Collection Status Card -->
        <div class="bg-white rounded-3xl p-6 border border-gray-50 flex flex-col justify-between h-44 relative overflow-hidden group">
            <div class="absolute right-4 top-1/2 -translate-y-1/2 opacity-[0.03] group-hover:scale-110 transition duration-500">
                <svg class="w-24 h-24 text-amber-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm.31 16.19V19.5h-1.5v-1.31c-1.3-.16-2.5-1-2.5-2.5h1.71c.05.97.77 1.64 2.35 1.64 1.22 0 2.1-.62 2.1-1.48 0-.72-.57-1.35-2.34-1.77-2-.47-3.65-1.16-3.65-3.09 0-1.5 1.2-2.34 2.5-2.5V7.5h1.5v1.31c1.84.19 3.1 1.46 3.15 3.42h-1.71c-.04-.98-.56-1.64-1.94-1.64-1.31 0-2.1.59-2.1 1.43 0 .73.57 1.22 2.34 1.67 2 .48 3.65 1.16 3.65 3.04 0 1.54-1.03 2.42-2.5 2.61z"/></svg>
            </div>
            <div>
                <div class="text-amber-500 font-bold text-[13px] mb-1.5 flex items-center gap-1.5 leading-none">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Pending Collections
                </div>
                <div class="text-4xl font-black text-slate-800 leading-none tracking-tight">{{ $pendingCollections }}</div>
            </div>
            <p class="text-[12px] text-slate-400 font-medium leading-snug">Awaiting pickup routing</p>
        </div>

        <!-- Active Collectors Card -->
        <div class="bg-white rounded-3xl p-6 border border-gray-50 flex flex-col justify-between h-44 relative overflow-hidden group">
            <div class="absolute right-4 top-1/2 -translate-y-1/2 opacity-[0.03] group-hover:scale-110 transition duration-500">
                <svg class="w-24 h-24 text-[#19817E]" fill="currentColor" viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
            </div>
            <div>
                <div class="text-[#19817E] font-bold text-[13px] mb-1.5 flex items-center gap-1.5 leading-none">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    Active Duty Staff
                </div>
                <div class="text-4xl font-black text-slate-800 leading-none tracking-tight">{{ $activeStaffCount }}</div>
            </div>
            <div class="flex items-center gap-1.5 mt-2">
                <span class="text-[10px] font-black px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-600 uppercase tracking-widest">{{ $onDutyCount }} Online Now</span>
                <p class="text-[12px] text-slate-400 font-medium leading-snug">registered staff</p>
            </div>
        </div>

        <!-- System Users Card -->
        <div class="bg-white rounded-3xl p-6 border border-gray-50 flex flex-col justify-between h-44 relative overflow-hidden group">
            <div class="absolute right-4 top-1/2 -translate-y-1/2 opacity-[0.03] group-hover:scale-110 transition duration-500">
                <svg class="w-24 h-24 text-blue-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
            </div>
            <div>
                <div class="text-blue-500 font-bold text-[13px] mb-1.5 flex items-center gap-1.5 leading-none">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    Total General Users
                </div>
                <div class="text-4xl font-black text-slate-800 leading-none tracking-tight">{{ number_format($totalUsersCount) }}</div>
            </div>
            <div class="flex items-center gap-1.5 mt-2">
                <span class="text-[10px] font-black px-2 py-0.5 rounded-md bg-blue-50 text-blue-600 uppercase tracking-widest">+{{ $newUsersThisMonth }} New This Month</span>
                <p class="text-[12px] text-slate-400 font-medium leading-snug">resident accounts</p>
            </div>
        </div>

        <!-- Completed Card -->
        <div class="bg-white rounded-3xl p-6 border border-gray-50 flex flex-col justify-between h-44 relative overflow-hidden group">
            <div class="absolute right-4 top-1/2 -translate-y-1/2 opacity-[0.03] group-hover:scale-110 transition duration-500">
                <svg class="w-24 h-24 text-emerald-500" fill="currentColor" viewBox="0 0 24 24"><path d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4L9 16.2z"/></svg>
            </div>
            <div>
                <div class="text-emerald-500 font-bold text-[13px] mb-1.5 flex items-center gap-1.5 leading-none">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Cleared Today
                </div>
                <div class="text-4xl font-black text-slate-800 leading-none tracking-tight">{{ $clearedTodayPercent }}<span class="text-xl text-emerald-500 font-bold ml-1">%</span></div>
            </div>
            <p class="text-[12px] text-slate-400 font-medium leading-snug">Of daily schedules completed</p>
        </div>
    </div>

    <!-- Main Grid Section -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        
        <!-- Live Collection Status Table -->
        <div class="xl:col-span-2 bg-white rounded-3xl p-8 border border-gray-50 shadow-sm shadow-gray-100">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-xl font-black text-slate-800 tracking-tight">Live Request Status</h2>
                    <p class="text-[12px] text-slate-400 font-medium mt-1">Real-time status of waste collections</p>
                </div>
                <button class="text-[#19817E] text-[12px] font-bold py-2 px-5 rounded-full border border-[#19817E]/20 hover:bg-[#19817E] hover:text-white transition-all duration-300 uppercase tracking-wider leading-none">Review All</button>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead>
                        <tr class="text-slate-300 font-bold text-[11px] uppercase tracking-widest border-b border-gray-50">
                            <th class="pb-4 px-2">User / Address</th>
                            <th class="pb-4 px-2">Waste Type</th>
                            <th class="pb-4 px-2">Assigned Collector</th>
                            <th class="pb-4 px-2">Status</th>
                            <th class="pb-4 px-2">Est. Time</th>
                            <th class="pb-4 px-2 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="text-slate-700 font-bold divide-y divide-gray-50/50">
                        @forelse($latestRequests as $request)
                        <tr class="hover:bg-gray-50/30 transition-all duration-300">
                            <td class="py-5 px-2">
                                <div class="font-black text-slate-800">{{ $request->user->name }}</div>
                                <div class="text-[11px] text-slate-400 font-medium">{{ $request->address }}</div>
                            </td>
                            <td class="py-5 px-2">
                                <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-wider
                                    @if($request->type == 'Organic') bg-emerald-50 text-emerald-600
                                    @elseif($request->type == 'Recyclable') bg-blue-50 text-blue-600
                                    @elseif($request->type == 'Hazardous') bg-red-50 text-red-600
                                    @else bg-gray-50 text-gray-600 @endif">
                                    {{ $request->type }}
                                </span>
                            </td>
                            <td class="py-5 px-2">
                                @if($request->collector)
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-[#DCF5F2] text-[#19817E] flex items-center justify-center text-[10px] font-black border border-white shadow-sm">
                                        {{ substr($request->collector->name, 0, 1) }}
                                    </div>
                                    <span class="text-[13px]">{{ $request->collector->name }}</span>
                                </div>
                                @else
                                <div class="text-[13px] text-slate-300 italic font-medium">Unassigned</div>
                                @endif
                            </td>
                            <td class="py-5 px-2">
                                @if($request->status == 'Completed')
                                    <span class="px-2.5 py-1 rounded-lg text-[9px] font-black bg-gray-100 text-slate-400 tracking-widest uppercase">Completed</span>
                                @elseif($request->status == 'Scheduled')
                                    <span class="px-2.5 py-1 rounded-lg text-[9px] font-black bg-[#E2F0EC] text-[#19817E] tracking-widest uppercase">Scheduled</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-lg text-[9px] font-black bg-[#FEF3C7] text-[#D97706] tracking-widest uppercase">Pending</span>
                                @endif
                            </td>
                            <td class="py-5 px-2 text-[13px] text-slate-500">{{ $request->scheduled_time ?? 'ASAP' }}</td>
                            <td class="py-5 px-2 text-right">
                                @if(!$request->collector && $request->status == 'Pending')
                                    <button class="text-white bg-[#19817E] hover:bg-teal-700 text-[11px] font-black px-4 py-1.5 rounded-lg shadow-sm">Assign</button>
                                @else
                                    <button class="text-slate-400 hover:text-slate-600 text-[11px] font-black border border-slate-100 px-4 py-1.5 rounded-lg transition-all duration-200">View</button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-4 text-center text-gray-500">No requests found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Right Side Widgets -->
        <div class="xl:col-span-1 space-y-8">
            
            <!-- Shift Roster Widget -->
            <div class="bg-white rounded-3xl p-8 border border-gray-50 shadow-sm shadow-gray-100">
                <div class="flex justify-between items-center mb-8">
                    <h2 class="text-xl font-black text-slate-800 tracking-tight leading-none">Today's Shift Roster</h2>
                    <button class="text-[#19817E] hover:bg-teal-50 p-1 rounded-lg transition-colors"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg></button>
                </div>
                
                <div class="space-y-6">
                    <div class="flex gap-4 items-center group cursor-pointer p-1 rounded-xl hover:bg-gray-50 transition-all duration-300">
                        <div class="w-1 bg-amber-400 h-10 rounded-full"></div>
                        <div class="flex-1">
                            <h4 class="text-sm font-black text-slate-800 leading-tight">Zone A - General</h4>
                            <p class="text-[11px] text-slate-400 font-bold">Assigned: Michael & David</p>
                        </div>
                        <div class="text-right text-[10px] font-bold text-slate-300 uppercase tracking-widest">08:00 - 14:00</div>
                    </div>
                    
                    <div class="flex gap-4 items-center group cursor-pointer p-1 rounded-xl hover:bg-gray-50 transition-all duration-300">
                        <div class="w-1 bg-teal-500 h-10 rounded-full"></div>
                        <div class="flex-1">
                            <h4 class="text-sm font-black text-slate-800 leading-tight">Zone B - Recycling</h4>
                            <p class="text-[11px] text-slate-400 font-bold">Assigned: Sarah W.</p>
                        </div>
                        <div class="text-right text-[10px] font-bold text-slate-300 uppercase tracking-widest">10:00 - 16:00</div>
                    </div>
                </div>
                <div class="mt-8">
                    <button class="w-full text-center text-[11px] font-black text-[#19817E] py-3.5 border border-dashed border-[#19817E]/30 rounded-2xl hover:bg-teal-50 transition-all duration-300 uppercase tracking-widest">Full Schedule Map</button>
                </div>
            </div>

            <!-- Active Staff Monitor Widget -->
            <div class="bg-[#0D161F] rounded-[40px] p-8 shadow-2xl shadow-slate-900/40 relative overflow-hidden group">
                <!-- Large Pause Background Icon -->
                <div class="absolute -right-6 -top-6 text-[#19817E]/10 rotate-12">
                   <svg class="w-40 h-40" fill="currentColor" viewBox="0 0 24 24"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/></svg>
                </div>
                
                <h2 class="text-xl font-black text-white tracking-tight mb-1 relative z-10">Active Staff</h2>
                <p class="text-[11px] font-bold text-slate-500 uppercase tracking-widest mb-8 relative z-10">Live monitoring</p>
                
                <div class="space-y-4 relative z-10">
                    @forelse($activeStaff as $staff)
                    <div class="flex justify-between items-center bg-[#1B252F] p-4 rounded-[24px] border border-white/5 hover:bg-[#202C38] transition-all duration-300">
                        <div class="flex items-center gap-4">
                            <div class="relative">
                                <div class="w-11 h-11 rounded-full bg-teal-500/10 flex items-center justify-center font-black text-teal-500 border border-teal-500/20 text-xs">
                                    {{ substr($staff->name, 0, 1) }}
                                </div>
                                <span class="absolute bottom-0 right-0 w-3 h-3 bg-emerald-500 rounded-full border-2 border-[#1B252F]"></span>
                            </div>
                            <div>
                                <div class="text-[14px] font-black text-white leading-tight">{{ $staff->name }}</div>
                                <div class="text-[10px] text-emerald-400 font-bold uppercase tracking-widest mt-0.5">Active • {{ $staff->collection_requests_count }} Tasks</div>
                            </div>
                        </div>
                        <button class="text-slate-600 hover:text-white transition-colors bg-white/5 p-2 rounded-xl"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg></button>
                    </div>
                    @empty
                    <p class="text-slate-500 text-xs italic">No staff on system.</p>
                    @endforelse
                            </div>
                        </div>
                        <button class="text-slate-600 hover:text-white transition-colors bg-white/5 p-2 rounded-xl"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg></button>
                    </div>
                    @empty
                    <p class="text-gray-500 text-xs italic">No staff on system.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
