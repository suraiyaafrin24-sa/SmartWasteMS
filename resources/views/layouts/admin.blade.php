<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin Dashboard') - SmartBin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #F8F9FB; }
        .sidebar-bg { background-color: #F2F4F7; }
    </style>
</head>
<body class="text-slate-800 h-screen flex overflow-hidden antialiased">
    <!-- Sidebar -->
    <aside class="sidebar-bg w-64 border-r border-gray-200 flex flex-col justify-between hidden md:flex shrink-0">
        <div>
            <!-- Profile Status Area (Replacing Logo) -->
            <div class="px-6 py-8 mb-4 border-b border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="relative shrink-0">
                        <div class="w-12 h-12 rounded-full bg-teal-100 flex items-center justify-center font-bold text-teal-700 text-lg border-2 border-white shadow-sm overflow-hidden">
                            @if(Auth::user()->profile_image)
                                <img src="{{ asset('storage/' . Auth::user()->profile_image) }}" class="w-full h-full object-cover">
                            @else
                                {{ substr(Auth::user()->name, 0, 1) }}
                            @endif
                        </div>
                        <!-- Active Status Dot -->
                        <span class="absolute bottom-0 right-1 block w-3.5 h-3.5 bg-emerald-500 rounded-full border-2 border-white"></span>
                    </div>
                    <div class="leading-tight">
                        <div class="text-xs font-bold text-gray-400 uppercase tracking-widest">Administrator</div>
                        <h2 class="text-lg font-black text-gray-900 tracking-tight">
                            Hello, {{ explode(' ', Auth::user()->name)[0] }}
                        </h2>
                    </div>
                </div>
            </div>
            
            <!-- Navigation Links -->
            <nav class="px-4 space-y-1 relative">
                <!-- Overview -->
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-4 px-4 py-3 {{ request()->routeIs('admin.dashboard') ? 'bg-[#E2F0EC] text-teal-700 font-bold' : 'text-slate-500 hover:bg-gray-200/50 font-semibold' }} rounded-xl transition">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M4 13h6a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1zm-1 7a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-4a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v4zm10 0a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-7a1 1 0 0 0-1-1h-6a1 1 0 0 0-1 1v7zm1-10h6a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1h-6a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1z"/></svg>
                    Dashboard
                </a>

                <!-- Collections -->
                <a href="{{ route('admin.collections') }}" class="flex items-center gap-4 px-4 py-3 {{ request()->routeIs('admin.collections') ? 'bg-[#E2F0EC] text-teal-700 font-bold' : 'text-slate-500 hover:bg-gray-200/50 font-semibold' }} rounded-xl transition">
                    <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    Collections
                </a>

                <!-- Duty Roster -->
                <a href="{{ route('admin.duty_roster') }}" class="flex items-center gap-4 px-4 py-3 {{ request()->routeIs('admin.duty_roster') ? 'bg-[#E2F0EC] text-teal-700 font-bold' : 'text-slate-500 hover:bg-gray-200/50 font-semibold' }} rounded-xl transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Duty Roster
                </a>
                
                <!-- Manage Users -->
                <a href="{{ route('admin.users') }}" class="flex items-center gap-4 px-4 py-3 {{ request()->routeIs('admin.users') || request()->routeIs('admin.users.create') ? 'bg-[#E2F0EC] text-teal-700 font-bold' : 'text-slate-500 hover:bg-gray-200/50 font-semibold' }} rounded-xl transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    User Management
                </a>

                <!-- User Reports -->
                <a href="{{ route('admin.reports') }}" class="flex items-center gap-4 px-4 py-3 {{ request()->routeIs('admin.reports') ? 'bg-[#E2F0EC] text-teal-700 font-bold' : 'text-slate-500 hover:bg-gray-200/50 font-semibold' }} rounded-xl transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    User Reports
                </a>

                <!-- Settings -->
                <div class="mt-4 border-t border-gray-100 pt-4">
                    <a href="{{ route('admin.profile') }}" class="flex items-center gap-4 px-4 py-3 {{ request()->routeIs('admin.profile') ? 'bg-[#E2F0EC] text-teal-700 font-bold' : 'text-slate-500 hover:bg-gray-200/50 font-semibold' }} rounded-xl transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Profile Settings
                    </a>
                </div>
            </nav>
        </div>
        
        <!-- Bottom Logout Option -->
        <div class="px-4 mb-6">
            <form method="POST" action="{{ route('logout') }}" id="sidebarLogoutForm">
                @csrf
                <button type="button" onclick="document.getElementById('sidebarLogoutForm').submit();" 
                    class="w-full flex items-center gap-4 px-4 py-3 text-red-500 hover:bg-red-50 rounded-xl font-bold transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col h-screen overflow-y-auto w-full relative">
        <!-- Top Nav Header -->
        <header class="flex items-center justify-between px-8 py-5 sticky top-0 bg-[#F8F9FB]/90 backdrop-blur-md z-20">
            <!-- Search Bar -->
            <div class="relative w-[340px]">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" placeholder="Search..." class="bg-gray-100/80 border-none font-medium text-sm text-gray-700 rounded-full w-full pl-10 pr-4 py-2.5 focus:ring-2 focus:ring-teal-500 focus:bg-white transition-all">
            </div>
            
            <!-- Top Right Icons -->
            <div class="flex items-center gap-6 text-gray-500">
                <button class="hover:text-gray-800 transition relative">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    <!-- Notification Dot -->
                    <span class="absolute top-0 right-0 block w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white"></span>
                </button>
            </div>
        </header>

        <!-- Dynamic Content Slot -->
        <div class="px-8 pb-12 w-full max-w-[1400px]">
            @if (session('status'))
                <div class="mb-4 bg-emerald-100 text-emerald-800 p-4 rounded-xl font-bold flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    {{ session('status') }}
                </div>
            @endif

            @yield('content')
        </div>
    </main>
</body>
</html>
