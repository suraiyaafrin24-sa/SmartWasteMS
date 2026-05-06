<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin Dashboard') - SmartBin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#19817E',
                        secondary: '#E2F0EC',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #fbf8f8ff;
        }

        .sidebar-bg {
            background-color: #d7d8daff;
        }

        /* Smooth scroll for main content */
        main::-webkit-scrollbar {
            width: 6px;
        }

        main::-webkit-scrollbar-thumb {
            background: #E2E8F0;
            border-radius: 10px;
        }
    </style>
</head>

<body class="text-slate-800 h-screen w-full flex overflow-hidden antialiased bg-[#F8F9FB]">
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
                    <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Administrator
                    </div>
                    <h2 class="text-base font-black text-slate-800 tracking-tight leading-none">
                        Hello, {{ explode(' ', Auth::user()->name)[0] }}
                    </h2>
                </div>
            </div>
        </div>

        <!-- Navigation Links -->
        <nav class="px-4 space-y-1">
            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center gap-4 px-4 py-3 {{ request()->routeIs('admin.dashboard') ? 'bg-[#DCF5F2] text-[#19817E] font-bold' : 'text-slate-400 hover:bg-gray-100 font-semibold' }} rounded-xl transition-all duration-200">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M4 13h6a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1zm-1 7a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-4a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v4zm10 0a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-7a1 1 0 0 0-1-1h-6a1 1 0 0 0-1 1v7zm1-10h6a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1h-6a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1z" />
                </svg>
                <span class="text-sm">Dashboard</span>
            </a>

            <!-- Collections -->
            <a href="{{ route('admin.collections') }}"
                class="flex items-center gap-4 px-4 py-3 {{ request()->routeIs('admin.collections') ? 'bg-[#DCF5F2] text-[#19817E] font-bold' : 'text-slate-400 hover:bg-gray-100 font-semibold' }} rounded-xl transition-all duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                    </path>
                </svg>
                <span class="text-sm">Waste Collections</span>
            </a>

            <!-- Duty Roster -->
            <a href="{{ route('admin.duty_roster') }}"
                class="flex items-center gap-4 px-4 py-3 {{ request()->routeIs('admin.duty_roster') ? 'bg-[#DCF5F2] text-[#19817E] font-bold' : 'text-slate-400 hover:bg-gray-100 font-semibold' }} rounded-xl transition-all duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                    </path>
                </svg>
                <span class="text-sm">Staff Schedule</span>
            </a>

            <!-- Manage Users -->
            <a href="{{ route('admin.users') }}"
                class="flex items-center gap-4 px-4 py-3 {{ request()->routeIs('admin.users') || request()->routeIs('admin.users.create') ? 'bg-[#DCF5F2] text-[#19817E] font-bold' : 'text-slate-400 hover:bg-gray-100 font-semibold' }} rounded-xl transition-all duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                    </path>
                </svg>
                <span class="text-sm">User Management</span>
            </a>

            <!-- User Reports -->
            <a href="{{ route('admin.complaints') }}"
                class="flex items-center gap-4 px-4 py-3 {{ request()->routeIs('admin.complaints') ? 'bg-[#DCF5F2] text-[#19817E] font-bold' : 'text-slate-400 hover:bg-gray-100 font-semibold' }} rounded-xl transition-all duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                    </path>
                </svg>
                <span class="text-sm">User Complaints</span>
            </a>

            <!-- Settings -->
            <div class="mt-4 border-t border-gray-100 pt-4">
                <a href="{{ route('admin.profile') }}"
                    class="flex items-center gap-4 px-4 py-3 {{ request()->routeIs('admin.profile') ? 'bg-[#DCF5F2] text-[#19817E] font-bold' : 'text-slate-400 hover:bg-gray-100 font-semibold' }} rounded-xl transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <span class="text-sm">Profile Settings</span>
                </a>
            </div>
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
        <!-- Top Nav Header -->
        <header class="flex items-center justify-between px-8 py-5 sticky top-0 bg-[#F8F9FB]/90 backdrop-blur-md z-20 no-print">
            <div></div>
            
            <!-- Top Right Icons -->
            <div class="flex items-center gap-6 text-gray-500">
                @include('components.notification-dropdown')
            </div>
        </header>

        <!-- Dynamic Content Slot -->
        <div class="px-8 pb-12 w-full max-w-[1400px]">
            @if (session('status'))
                <div class="mb-4 bg-emerald-100 text-emerald-800 p-4 rounded-xl font-bold flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ session('status') }}
                </div>
            @endif

            @yield('content')
        </div>
    </main>
</body>

</html>