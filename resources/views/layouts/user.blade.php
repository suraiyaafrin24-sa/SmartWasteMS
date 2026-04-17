<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'User Dashboard') - SmartBin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #F8F9FB; }
        .sidebar-bg { background-color: #F2F4F7; }
    </style>
</head>
<body class="text-slate-800 h-screen flex overflow-hidden antialiased">
    <aside class="bg-[#F8F9FA] w-64 border-r border-gray-100 flex flex-col justify-between hidden md:flex shrink-0">
        <div>
            <!-- Profile Status Area -->
            <div class="px-7 py-8 mb-4">
                <div class="flex items-center gap-3">
                    <div class="relative shrink-0">
                        <div class="w-11 h-11 rounded-full bg-[#DCF5F2] flex items-center justify-center font-bold text-[#19817E] text-lg border-2 border-white shadow-sm overflow-hidden">
                            @if(Auth::user()->profile_image)
                                <img src="{{ asset('storage/' . Auth::user()->profile_image) }}" class="w-full h-full object-cover">
                            @else
                                {{ substr(Auth::user()->name, 0, 1) }}
                            @endif
                        </div>
                        </div>
                    </div>
                    <div class="leading-tight">
                        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-1">RESIDENT / USER</div>
                        <h2 class="text-[20px] font-black text-slate-800 tracking-tight leading-none">
                            Hi, {{ explode(' ', Auth::user()->name)[0] }}
                        </h2>
                    </div>
                </div>
            </div>
            
            <!-- Navigation Links -->
            <nav class="px-4 space-y-1">
                <a href="{{ route('user.dashboard') }}" class="flex items-center gap-4 px-4 py-3 {{ request()->routeIs('user.dashboard') ? 'bg-[#DCF5F2] text-[#19817E] font-bold' : 'text-slate-400 hover:bg-gray-100 font-semibold' }} rounded-xl transition-all duration-200">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M4 13h6a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1zm-1 7a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-4a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v4zm10 0a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-7a1 1 0 0 0-1-1h-6a1 1 0 0 0-1 1v7zm1-10h6a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1h-6a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1z"/></svg>
                    <span class="text-sm">My Dashboard</span>
                </a>

                <a href="{{ route('user.requests.create') }}" class="flex items-center gap-4 px-4 py-3 {{ request()->routeIs('user.requests.create') ? 'bg-[#DCF5F2] text-[#19817E] font-bold' : 'text-slate-400 hover:bg-gray-100 font-semibold' }} rounded-xl transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span class="text-sm">New Request</span>
                </a>

                <a href="{{ route('user.history') }}" class="flex items-center gap-4 px-4 py-3 {{ request()->routeIs('user.history') ? 'bg-[#DCF5F2] text-[#19817E] font-bold' : 'text-slate-400 hover:bg-gray-100 font-semibold' }} rounded-xl transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="text-sm">History</span>
                </a>
                
                <a href="{{ route('user.settings') }}" class="flex items-center gap-4 px-4 py-3 {{ request()->routeIs('user.settings') ? 'bg-[#DCF5F2] text-[#19817E] font-bold' : 'text-slate-400 hover:bg-gray-100 font-semibold' }} rounded-xl transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    <span class="text-sm">Settings</span>
                </a>

                <a href="{{ route('user.reports.index') }}" class="flex items-center gap-4 px-4 py-3 {{ request()->routeIs('user.reports.*') ? 'bg-[#DCF5F2] text-[#19817E] font-bold' : 'text-slate-400 hover:bg-gray-100 font-semibold' }} rounded-xl transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <span class="text-sm">Reports</span>
                </a>
            </nav>
        </div>
        
        <!-- Bottom Logout Option -->
        <div class="px-6 mb-8 mt-auto">
            <form method="POST" action="{{ route('logout') }}" id="sidebarLogoutForm">
                @csrf
                <button type="button" onclick="document.getElementById('sidebarLogoutForm').submit();" 
                    class="flex items-center gap-4 text-red-500 hover:text-red-600 font-bold transition-all duration-200 ml-4 group">
                    <svg class="w-5 h-5 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    <span class="text-sm">Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col h-screen overflow-y-auto w-full relative">
        <!-- Top Nav Header -->
        <header class="flex items-center justify-between px-8 py-5 sticky top-0 bg-white/80 backdrop-blur-md z-20">
            <!-- Search Bar -->
            <div class="relative w-[340px]">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" placeholder="Search my requests..." class="bg-[#F3F4F6] border-none text-sm text-gray-500 rounded-2xl w-full pl-11 pr-4 py-3 focus:ring-1 focus:ring-teal-200 transition-all outline-none">
            </div>
            
            <!-- Top Right Icons -->
            <div class="flex items-center gap-6">
                <button class="hover:text-[#19817E] transition relative text-gray-500">
                    <svg class="w-6 h-6 outline-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    <span class="absolute -top-1 -right-1 block w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
                </button>
            </div>
                </button>
                
                <div class="w-10 h-10 rounded-full bg-teal-100 flex items-center justify-center font-black text-teal-700 text-sm border-2 border-white shadow-sm overflow-hidden">
                    @if(Auth::user()->profile_image)
                        <img src="{{ asset('storage/' . Auth::user()->profile_image) }}" class="w-full h-full object-cover">
                    @else
                        {{ substr(Auth::user()->name, 0, 1) }}
                    @endif
                </div>
            </div>
        </header>

        <!-- Dynamic Content Slot -->
        <div class="px-8 pb-12 w-full max-w-[1400px]">
            @yield('content')
        </div>
    </main>
</body>
</html>
