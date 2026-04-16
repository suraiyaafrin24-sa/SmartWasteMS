@extends('layouts.admin')

@section('title', 'Admin Dashboard Overview')

@section('content')

    <!-- Dashboard Header -->
    <div class="mb-8 mt-2 flex justify-between items-end">
        <div>
            <h1 class="text-[28px] font-black tracking-tight text-gray-900 mb-1">Overview Dashboard</h1>
            <p class="text-sm font-medium text-gray-500">Monitor collections, active staff, and system performance.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.reports') }}" class="bg-gray-100 text-gray-700 text-xs font-black py-4 px-6 rounded-xl hover:bg-gray-200 transition uppercase tracking-widest">Filter Report</a>
            <a href="{{ route('admin.assign_duty') }}" class="bg-[#19817E] text-white text-xs font-black py-4 px-6 rounded-xl hover:bg-teal-700 transition uppercase tracking-widest">+ Assign Duty</a>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
        <!-- Collection Status Card -->
        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm flex flex-col justify-between h-40 relative overflow-hidden group">
            <div class="absolute right-0 top-0 opacity-5 group-hover:scale-110 transition duration-500">
                <svg class="w-32 h-32 text-amber-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.31-8.86c-1.77-.45-2.34-.94-2.34-1.67 0-.84.79-1.43 2.1-1.43 1.38 0 1.9.66 1.94 1.64h1.71c-.05-1.97-1.31-3.24-3.15-3.24v-1.5h-1.5v1.5c-1.3.16-2.5 1-2.5 2.5 0 1.93 1.65 2.62 3.65 3.09 1.77.42 2.34 1.05 2.34 1.77 0 .86-.88 1.48-2.1 1.48-1.58 0-2.3-.87-2.35-1.84h-1.71c.07 1.94 1.42 3.23 3.06 3.42v1.5h1.5v-1.5c1.47-.19 2.5-1.07 2.5-2.61 0-1.88-1.66-2.56-3.65-3.04z"/></svg>
            </div>
            <div>
                <div class="text-amber-500 font-bold text-sm mb-1.5 flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Pending Collections
                </div>
                <div class="text-[32px] font-black text-gray-800 leading-none tracking-tight">{{ $pendingCollections }}</div>
            </div>
            <p class="text-[13px] text-gray-500 font-medium leading-snug truncate">Awaiting pickup routing</p>
        </div>

        <!-- Active Collectors Card -->
        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm flex flex-col justify-between h-40 relative overflow-hidden group">
            <div class="absolute right-0 top-0 opacity-5 group-hover:scale-110 transition duration-500">
                <svg class="w-32 h-32 text-[#19817E]" fill="currentColor" viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
            </div>
            <div>
                <div class="text-[#19817E] font-bold text-sm mb-1.5 flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    Active Duty Staff
                </div>
                <div class="text-[32px] font-black text-gray-800 leading-none tracking-tight">{{ $activeStaffCount }}</div>
            </div>
            <div class="flex items-center gap-1.5">
                <span class="text-[10px] font-black px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-600 uppercase tracking-widest">{{ $onDutyCount }} Online Now</span>
                <p class="text-[12px] text-gray-400 font-medium tracking-tight">registered staff</p>
            </div>
        </div>

        <!-- System Users Card -->
        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm flex flex-col justify-between h-40 relative overflow-hidden group">
            <div class="absolute right-0 top-0 opacity-5 group-hover:scale-110 transition duration-500">
                <svg class="w-32 h-32 text-blue-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
            </div>
            <div>
                <div class="text-blue-500 font-bold text-sm mb-1.5 flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    Total General Users
                </div>
                <div class="text-[32px] font-black text-gray-800 leading-none tracking-tight">{{ number_format($totalUsersCount) }}</div>
            </div>
            <div class="flex items-center gap-1.5">
                <span class="text-[10px] font-black px-2 py-0.5 rounded-md bg-blue-50 text-blue-600 uppercase tracking-widest">+{{ $newUsersThisMonth }} New This Month</span>
                <p class="text-[12px] text-gray-400 font-medium tracking-tight">resident accounts</p>
            </div>
        </div>

        <!-- Completed Card -->
        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm flex flex-col justify-between h-40 relative overflow-hidden group">
            <div class="absolute right-0 top-0 opacity-5 group-hover:scale-110 transition duration-500">
                <svg class="w-32 h-32 text-emerald-500" fill="currentColor" viewBox="0 0 24 24"><path d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4L9 16.2z"/></svg>
            </div>
            <div>
                <div class="text-emerald-500 font-bold text-sm mb-1.5 flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Cleared Today
                </div>
                <div class="text-[32px] font-black text-gray-800 leading-none tracking-tight">{{ $clearedTodayPercent }}<span class="text-lg text-emerald-500 font-bold ml-1">%</span></div>
            </div>
            <p class="text-[13px] text-gray-500 font-medium leading-snug">Of daily schedules completed</p>
        </div>
    </div>

    <!-- Main Grid Section -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        
        <!-- Live Collection Status Table (Left - 2 Columns wide) -->
        <div class="xl:col-span-2 bg-white rounded-3xl p-6 border border-gray-100 shadow-sm">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-xl font-bold text-gray-900 tracking-tight">Live Request Status</h2>
                    <p class="text-sm text-gray-500 mt-1">Real-time status of waste collections</p>
                </div>
                <div class="flex gap-2">
                    <button class="text-[#19817E] text-sm font-bold py-2 px-4 rounded-xl border border-teal-200 hover:bg-teal-50 transition">Review All</button>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead>
                        <tr class="text-gray-400 font-bold border-b border-gray-100 uppercase text-[11px] tracking-wider">
                            <th class="pb-3 px-2">User / Address</th>
                            <th class="pb-3 px-2">Waste Type</th>
                            <th class="pb-3 px-2">Assigned Collector</th>
                            <th class="pb-3 px-2">Status</th>
                            <th class="pb-3 px-2">Est. Time</th>
                            <th class="pb-3 px-2 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-800 font-semibold divide-y divide-gray-50">
                        @forelse($latestRequests as $request)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="py-4 px-2">
                                <div class="font-bold">{{ $request->user->name }}</div>
                                <div class="text-xs text-gray-500">{{ $request->address }}</div>
                            </td>
                            <td class="py-4 px-2">
                                <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-wider
                                    @if($request->type == 'Organic') bg-emerald-50 text-emerald-600
                                    @elseif($request->type == 'Recyclable') bg-blue-50 text-blue-600
                                    @elseif($request->type == 'Hazardous') bg-red-50 text-red-600
                                    @else bg-gray-50 text-gray-600 @endif">
                                    {{ $request->type }}
                                </span>
                            </td>
                            <td class="py-4 px-2">
                                @if($request->collector)
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-teal-100 text-teal-700 flex items-center justify-center text-xs font-bold">
                                        {{ substr($request->collector->name, 0, 1) }}
                                    </div>
                                    {{ $request->collector->name }}
                                </div>
                                @else
                                <div class="text-gray-400 text-sm">Unassigned</div>
                                @endif
                            </td>
                            <td class="py-4 px-2">
                                @if($request->status == 'Completed')
                                    <span class="px-2.5 py-1 rounded-md text-[11px] font-black bg-emerald-100 text-emerald-700 tracking-wide uppercase">Completed</span>
                                @elseif($request->status == 'Scheduled')
                                    <span class="px-2.5 py-1 rounded-md text-[11px] font-black bg-blue-100 text-blue-700 tracking-wide uppercase">Scheduled</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-md text-[11px] font-black bg-amber-100 text-amber-700 tracking-wide uppercase">Pending</span>
                                @endif
                            </td>
                            <td class="py-4 px-2 text-gray-500">{{ $request->scheduled_time ?? 'ASAP' }}</td>
                            <td class="py-4 px-2 text-right">
                                @if(!$request->collector && $request->status == 'Pending')
                                    <button class="text-white bg-[#19817E] hover:bg-teal-700 font-semibold px-3 py-1 rounded-lg shadow-sm">Assign</button>
                                @else
                                    <button class="text-slate-400 hover:text-teal-600 font-semibold border px-3 py-1 rounded-lg">View</button>
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

        <!-- Duties / Active Collectors Panel (Right Margin) -->
        <div class="xl:col-span-1 flex flex-col gap-6">
            
            <!-- Quick Schedule -->
            <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm flex-1">
                <div class="flex justify-between items-center mb-5">
                    <h2 class="text-lg font-bold text-gray-900 tracking-tight">Today's Shift Roster</h2>
                    <button class="text-[#19817E] hover:bg-teal-50 p-1.5 rounded-lg transition"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg></button>
                </div>
                
                <div class="space-y-4">
                    <!-- Shift block -->
                    <div class="flex gap-4 items-center group cursor-pointer p-2 rounded-xl hover:bg-gray-50 transition border border-transparent hover:border-gray-100">
                        <div class="w-2 bg-amber-400 h-12 rounded-full"></div>
                        <div class="flex-1">
                            <h4 class="text-sm font-bold text-gray-800">Zone A - General</h4>
                            <p class="text-[12px] text-gray-500">Assigned: Michael & David</p>
                        </div>
                        <div class="text-right text-[11px] font-bold text-slate-400 bg-slate-100 px-2 py-1 rounded-lg">
                            08:00 - 14:00
                        </div>
                    </div>
                    
                    <!-- Shift block -->
                    <div class="flex gap-4 items-center group cursor-pointer p-2 rounded-xl hover:bg-gray-50 transition border border-transparent hover:border-gray-100">
                        <div class="w-2 bg-emerald-500 h-12 rounded-full"></div>
                        <div class="flex-1">
                            <h4 class="text-sm font-bold text-gray-800">Zone B - Recycling</h4>
                            <p class="text-[12px] text-gray-500">Assigned: Sarah W.</p>
                        </div>
                        <div class="text-right text-[11px] font-bold text-slate-400 bg-slate-100 px-2 py-1 rounded-lg">
                            10:00 - 16:00
                        </div>
                    </div>
                </div>
                <div class="mt-6 border-t border-gray-100 pt-4">
                    <button class="w-full text-center text-sm font-bold text-[#19817E] py-2 border border-dashed border-teal-300 rounded-xl hover:bg-teal-50 transition">Full Schedule Map</button>
                </div>
            </div>

            <!-- Active Staff Stats List -->
            <div class="bg-gray-900 rounded-3xl p-6 shadow-sm flex-1 text-white relative overflow-hidden">
                <div class="absolute -right-8 -top-8 opacity-20">
                    <svg class="w-32 h-32 text-emerald-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14H9V8h2v8zm4 0h-2V8h2v8z"/></svg>
                </div>
                
                <h2 class="text-lg font-bold tracking-tight mb-1 relative z-10">Active Staff</h2>
                <p class="text-xs text-gray-400 mb-5 relative z-10">Live monitoring</p>
                
                <div class="space-y-4 relative z-10">
                    @forelse($activeStaff as $staff)
                    <div class="flex justify-between items-center bg-gray-800/80 p-3 rounded-2xl backdrop-blur-sm border border-gray-700/50">
                        <div class="flex items-center gap-3">
                            <div class="relative">
                                <div class="w-9 h-9 rounded-full bg-teal-500 flex items-center justify-center font-bold text-white border-2 border-gray-800">
                                    {{ substr($staff->name, 0, 1) }}
                                </div>
                                <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-emerald-500 rounded-full border-2 border-gray-800"></span>
                            </div>
                            <div>
                                <div class="text-sm font-bold text-gray-100 leading-tight">{{ $staff->name }}</div>
                                <div class="text-[11px] text-emerald-400 font-semibold">Active • {{ $staff->collection_requests_count }} Tasks</div>
                            </div>
                        </div>
                        <button class="bg-gray-700 hover:bg-gray-600 text-white rounded-lg p-1.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg></button>
                    </div>
                    @empty
                    <p class="text-gray-500 text-xs italic">No staff on system.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
