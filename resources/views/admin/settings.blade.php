@extends('layouts.admin')

@section('title', 'System Settings')

@section('content')
    <div class="mb-8 mt-2">
        <h1 class="text-[28px] font-black tracking-tight text-gray-900 mb-1">System Control Center</h1>
        <p class="text-sm font-medium text-gray-500">Manage global features, waste types, and user panel controls.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Feature Toggles -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-[32px] p-8 border border-gray-100 shadow-sm transition hover:shadow-md">
                <div class="flex items-center gap-3 mb-8">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                    </div>
                    <h2 class="text-xl font-black text-gray-900 tracking-tight">User Panel Controls</h2>
                </div>

                <div class="space-y-6">
                    <div class="flex items-center justify-between p-4 bg-gray-50/50 rounded-2xl border border-gray-100">
                        <div>
                            <h4 class="text-sm font-black text-gray-800 tracking-tight mb-1">Public Registration</h4>
                            <p class="text-[11px] text-gray-400 font-bold uppercase tracking-widest">Allow new users to create accounts globally</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" checked class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between p-4 bg-gray-50/50 rounded-2xl border border-gray-100">
                        <div>
                            <h4 class="text-sm font-black text-gray-800 tracking-tight mb-1">Urgent Service Requests</h4>
                            <p class="text-[11px] text-gray-400 font-bold uppercase tracking-widest">Enable 'Express Pickup' feature for users</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between p-4 bg-gray-50/50 rounded-2xl border border-gray-100">
                        <div>
                            <h4 class="text-sm font-black text-gray-800 tracking-tight mb-1">Live Map Access</h4>
                            <p class="text-[11px] text-gray-400 font-bold uppercase tracking-widest">Allow collectors to use real-time GPS tracking</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" checked class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Waste Categorization -->
            <div class="bg-white rounded-[32px] p-8 border border-gray-100 shadow-sm transition hover:shadow-md">
                 <div class="flex items-center gap-3 mb-8">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </div>
                    <h2 class="text-xl font-black text-gray-900 tracking-tight">Waste Categories</h2>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    @foreach(['Organic', 'Recyclable', 'Hazardous', 'General'] as $type)
                    <div class="p-4 border border-gray-100 rounded-2xl flex items-center justify-between">
                        <div class="text-sm font-black text-gray-700">{{ $type }}</div>
                        <label class="relative inline-flex items-center cursor-pointer scale-90">
                            <input type="checkbox" checked class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-blue-600 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-full"></div>
                        </label>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Sidebar Config -->
        <div class="space-y-6">
            <div class="bg-gray-900 rounded-[32px] p-8 text-white">
                <h3 class="text-lg font-black tracking-tight mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002-2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    Maintenance Mode
                </h3>
                <p class="text-xs text-gray-400 font-bold uppercase tracking-widest leading-relaxed mb-6">Shut down the platform for updates. Only Administrators will be able to login during this time.</p>
                <button class="w-full bg-red-500/10 text-red-500 border border-red-500/20 text-[10px] font-black py-4 rounded-2xl hover:bg-red-500 hover:text-white transition shadow-lg shadow-red-500/10 uppercase tracking-widest">
                    Enable Maintenance
                </button>
            </div>

            <div class="bg-white rounded-[32px] p-8 border border-gray-100 shadow-sm">
                 <h3 class="text-sm font-black text-gray-800 tracking-tight uppercase mb-4">Quick Actions</h3>
                 <div class="space-y-3">
                    <button class="w-full text-left p-4 bg-gray-50 rounded-2xl text-[10px] font-black text-gray-500 hover:bg-gray-100 transition uppercase tracking-widest">Clear Session Cache</button>
                    <button class="w-full text-left p-4 bg-gray-50 rounded-2xl text-[10px] font-black text-gray-500 hover:bg-gray-100 transition uppercase tracking-widest">Rebuild Route Index</button>
                 </div>
            </div>
        </div>
    </div>
@endsection
