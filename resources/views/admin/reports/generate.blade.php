@extends('layouts.admin')

@section('title', 'Municipal Performance Reports')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-1">Activity Reports</h1>
        <p class="text-sm text-gray-500">Generate and download performance reports for staff and residents.</p>
    </div>

    <div class="max-w-4xl">
        <div class="bg-white rounded-2xl p-8 border border-gray-200 shadow-sm">
            <div class="flex items-center gap-3 mb-8 pb-6 border-b border-gray-100">
                <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 01-2-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <h2 class="text-lg font-bold text-gray-800">Report Configuration</h2>
            </div>

            <form action="{{ route('admin.reports.generate') }}" method="POST" class="space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Type Selection -->
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700">Reporting Target <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <select name="report_type" id="typeSelect" required onchange="toggleUserList(this.value)" class="w-full bg-white border border-gray-300 text-gray-900 text-sm rounded-lg block p-3.5 focus:ring-blue-500 focus:border-blue-500 transition-all appearance-none cursor-pointer">
                                <option value="collector" {{ old('report_type') == 'collector' ? 'selected' : '' }}>Waste Collector</option>
                                <option value="user" {{ old('report_type') == 'user' ? 'selected' : '' }}>Resident User</option>
                            </select>
                            <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                    </div>

                    <!-- Entity Selection -->
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700">Select Individual <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <select name="user_id" id="personSelect" required class="w-full bg-white border border-gray-300 text-gray-900 text-sm rounded-lg block p-3.5 focus:ring-blue-500 focus:border-blue-500 transition-all appearance-none cursor-pointer">
                                <option value="">Choose Staff/User...</option>
                                @foreach($collectors as $c)
                                    <option value="{{ $c->id }}" class="collector-opt">{{ $c->name }}</option>
                                @endforeach
                                @foreach($generalUsers as $u)
                                    <option value="{{ $u->id }}" class="user-opt hidden">{{ $u->name }}</option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                    </div>

                    <!-- Date Selection -->
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700">Start Date <span class="text-red-500">*</span></label>
                        <input type="date" name="start_date" value="{{ date('Y-m-01') }}" required class="w-full bg-white border border-gray-300 text-gray-900 text-sm rounded-lg block p-3.5 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                    </div>

                    <!-- Duration Selection -->
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-700">Reporting Duration <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <select name="days" required class="w-full bg-white border border-gray-300 text-gray-900 text-sm rounded-lg block p-3.5 focus:ring-blue-500 focus:border-blue-500 transition-all appearance-none cursor-pointer">
                                <option value="7">7 Days</option>
                                <option value="15">15 Days</option>
                                <option value="30" selected>30 Days (Monthly)</option>
                                <option value="90">90 Days (Quarterly)</option>
                                <option value="365">1 Year (Annual)</option>
                            </select>
                            <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-8 border-t border-gray-100 flex justify-end">
                    <button type="submit" class="bg-blue-600 text-white text-sm font-bold py-3 px-10 rounded-lg hover:bg-blue-700 transition shadow-sm flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13l-3 3m0 0l-3-3m3 3V8m0 13a9 9 0 110-18 9 9 0 010 18z"></path></svg>
                        Generate Official Report
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleUserList(type) {
            const select = document.getElementById('personSelect');
            const collectorOpts = document.querySelectorAll('.collector-opt');
            const userOpts = document.querySelectorAll('.user-opt');
            
            // Reset selection
            select.value = "";
            
            if (type === 'collector') {
                collectorOpts.forEach(o => o.classList.remove('hidden'));
                userOpts.forEach(o => o.classList.add('hidden'));
            } else {
                collectorOpts.forEach(o => o.classList.add('hidden'));
                userOpts.forEach(o => o.classList.remove('hidden'));
            }
        }
    </script>
@endsection
