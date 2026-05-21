<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Collector Dashboard') - SmartBin</title>
    <link rel="icon" href="data:,">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #F8F9FB;
        }

        .sidebar-bg {
            background-color: #f8fbf9ff;
        }

        .header-bg {
            background-color: #ffffff;
        }
    </style>
</head>

<body class="text-slate-800 h-screen flex overflow-hidden antialiased">
    <aside class="sidebar-bg w-64 border-r border-gray-100 flex flex-col justify-between hidden md:flex shrink-0">
        <div>
            <!-- Profile Status Area -->
            <div class="px-7 py-8 mb-4">
                <div class="flex items-center gap-3">
                    <div class="relative shrink-0">
                        <div
                            class="w-11 h-11 rounded-full bg-[#DCF5F2] flex items-center justify-center font-bold text-[#19817E] text-lg border-2 border-white shadow-sm overflow-hidden">
                            @if(Auth::user()->profile_image)
                                <img src="{{ asset('storage/' . Auth::user()->profile_image) }}"
                                    class="w-full h-full object-cover">
                            @else
                                {{ substr(Auth::user()->name, 0, 1) }}
                            @endif
                        </div>
                    </div>
                </div>
                <div class="leading-tight">
                    <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Field Staff</div>
                    <h2 class="text-base font-black text-slate-800 tracking-tight leading-none">
                        Hi, {{ explode(' ', Auth::user()->name)[0] }}
                    </h2>
                </div>
            </div>
        </div>

        <!-- Navigation Links -->
        <nav class="px-4 space-y-1">
            <a href="{{ route('waste_collector.dashboard') }}"
                class="flex items-center gap-4 px-4 py-3 {{ request()->routeIs('waste_collector.dashboard') ? 'bg-[#DCF5F2] text-[#19817E] font-bold' : 'text-slate-400 hover:bg-gray-100 font-semibold' }} rounded-xl transition-all duration-200">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M4 13h6a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1zm-1 7a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-4a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v4zm10 0a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-7a1 1 0 0 0-1-1h-6a1 1 0 0 0-1 1v7zm1-10h6a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1h-6a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1z" />
                </svg>
                <span class="text-sm">My Dashboard</span>
            </a>

            <a href="{{ route('waste_collector.duty_schedule') }}"
                class="flex items-center gap-4 px-4 py-3 {{ request()->routeIs('waste_collector.duty_schedule') ? 'bg-[#DCF5F2] text-[#19817E] font-bold' : 'text-slate-400 hover:bg-gray-100 font-semibold' }} rounded-xl transition-all duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                    </path>
                </svg>
                <span class="text-sm">Duty Schedule</span>
            </a>


            <a href="{{ route('waste_collector.history') }}"
                class="flex items-center gap-4 px-4 py-3 {{ request()->routeIs('waste_collector.history') ? 'bg-[#DCF5F2] text-[#19817E] font-bold' : 'text-slate-400 hover:bg-gray-100 font-semibold' }} rounded-xl transition-all duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="text-sm">Completed Tasks</span>
            </a>

            <a href="{{ route('waste_collector.leave_requests') }}"
                class="flex items-center gap-4 px-4 py-3 {{ request()->routeIs('waste_collector.leave_requests') ? 'bg-[#DCF5F2] text-[#19817E] font-bold' : 'text-slate-400 hover:bg-gray-100 font-semibold' }} rounded-xl transition-all duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10m-7 4h4m-7 4h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                    </path>
                </svg>
                <span class="text-sm">Leave Request</span>
            </a>

            <a href="{{ route('waste_collector.settings') }}"
                class="flex items-center gap-4 px-4 py-3 {{ request()->routeIs('waste_collector.settings') ? 'bg-[#DCF5F2] text-[#19817E] font-bold' : 'text-slate-400 hover:bg-gray-100 font-semibold' }} rounded-xl transition-all duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                    </path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <span class="text-sm">Profile Settings</span>
            </a>
        </nav>
        </div>

        <!-- Bottom Logout Option -->
        <div class="px-6 mb-8 mt-auto">
            <form method="POST" action="{{ route('logout') }}" id="sidebarLogoutForm">
                @csrf
                <button type="button" onclick="document.getElementById('sidebarLogoutForm').submit();"
                    class="flex items-center gap-4 text-red-500 hover:text-red-600 font-bold transition-all duration-200 ml-4 group">
                    <svg class="w-5 h-5 transition-transform group-hover:-translate-x-1" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                        </path>
                    </svg>
                    <span class="text-sm">Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col h-screen overflow-y-auto w-full relative">
        <header class="header-bg flex items-center justify-between px-8 py-5 sticky top-0 backdrop-blur-md z-20">
            <div></div>

            <div class="flex items-center gap-6 text-gray-500">
            </div>
        </header>

        <!-- Dynamic Content Slot -->
        <div class="px-8 pb-12 w-full max-w-[1400px]">
            @if(session('success'))
                <div
                    class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-600 px-4 py-3 rounded-xl flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="font-semibold text-sm">{{ session('success') }}</span>
                    </div>
                </div>
            @endif
            @yield('content')
        </div>
    </main>
</body>

</html>
