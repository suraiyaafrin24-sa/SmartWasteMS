@extends('layouts.admin')

@section('title', 'Assign Field Duty')

@section('content')
    <div class="mb-8">
        <h1 class="text-[28px] font-black tracking-tight text-gray-900 mb-1">Duty Assignment Center</h1>
        <p class="text-sm font-medium text-gray-500">Configure and deploy field staff to Uttara City Corporation Sectors.</p>
    </div>

    <div class="max-w-4xl mx-auto px-4 md:px-14 lg:px-26">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            <!-- Assignment Form -->
            <div class="lg:col-span-8">
                <div class="bg-white rounded-[32px] p-10 border border-gray-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)]">
                <div class="border-b border-gray-100 pb-4 mb-6">
                    <h2 class="text-lg font-bold text-gray-800">Assign Collector to Request</h2>
                </div>

                <form id="assignForm" method="POST">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Request Selection -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-700 mb-2">1. Select Resident Request <span class="text-red-500">*</span></label>
                            <select id="requestSelect" onchange="handleRequestChange(this)" name="request_id" required 
                                class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-sm rounded-xl focus:ring-[#19817E] focus:border-[#19817E] block p-3 transition">
                                <option value="">Choose a pending request...</option>
                                @foreach($unassignedRequests as $req)
                                    <option value="{{ $req->id }}" 
                                            data-sector="{{ $req->sector }}" 
                                            data-road="{{ $req->road }}"
                                            {{ isset($selectedRequestId) && $selectedRequestId == $req->id ? 'selected' : '' }}>
                                        {{ $req->user->name }} — {{ $req->sector }} ({{ $req->type }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Collector Selection -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-700 mb-2">2. Assign Field Collector <span class="text-red-500">*</span></label>
                            <select name="collector_id" required 
                                class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-sm rounded-xl focus:ring-[#19817E] focus:border-[#19817E] block p-3 transition">
                                <option value="">Choose a staff member...</option>
                                @foreach($collectors as $c)
                                    <option value="{{ $c->id }}">
                                        {{ $c->name }} 
                                        @if($c->sector)
                                            — {{ $c->sector }}
                                        @elseif($c->dutyRosters->first())
                                            — {{ $c->dutyRosters->first()->sector }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Sector Selection (Auto-filled) -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">3. Target Sector <span class="text-red-500">*</span></label>
                            <select id="sectorSelect" name="sector" required 
                                class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-sm rounded-xl focus:ring-[#19817E] focus:border-[#19817E] block p-3 transition">
                                <option value="">Choose Sector...</option>
                                @for($i = 1; $i <= 18; $i++)
                                    <option value="Sector {{ $i }}">Sector {{ $i }}</option>
                                @endfor
                            </select>
                        </div>

                        <!-- Micro-level Location (Auto-filled) -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">4. Road / Block / House <span class="text-red-500">*</span></label>
                            <input type="text" id="roadInput" name="road" placeholder="e.g. Road 12, Block D" required 
                                class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-sm rounded-xl focus:ring-[#19817E] focus:border-[#19817E] block p-3 transition">
                        </div>

                        <input type="hidden" name="zone" value="Uttara City Corporation Zone">

                        <!-- Deployment Date -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">5. Deployment Date <span class="text-red-500">*</span></label>
                            <input type="date" name="scheduled_date" value="{{ date('Y-m-d') }}" required 
                                class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-sm rounded-xl focus:ring-[#19817E] focus:border-[#19817E] block p-3 transition">
                        </div>

                        <!-- Shift Selection -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">6. Operating Shift <span class="text-red-500">*</span></label>
                            <select name="shift" required 
                                class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-sm rounded-xl focus:ring-[#19817E] focus:border-[#19817E] block p-3 transition">
                                <option value="Morning Shift">Morning Shift</option>
                                <option value="Night Shift">Night Shift</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-8 pt-6 border-t border-gray-100 gap-4">
                        <a href="{{ route('admin.dashboard') }}" class="text-sm font-bold text-gray-400 hover:text-gray-600 transition">Cancel</a>
                        <button type="submit" class="bg-[#19817E] text-white text-sm font-bold py-3 px-10 rounded-xl hover:bg-teal-700 focus:ring-4 focus:ring-teal-100 transition shadow-sm">
                            Confirm Assignment
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Live Status Sidebar -->
        <div class="lg:col-span-4 space-y-6">
            <div class="bg-gray-900 rounded-3xl p-6 text-white shadow-xl">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-2 h-2 rounded-full bg-teal-400 animate-pulse"></div>
                    <h3 class="text-sm font-black uppercase tracking-widest">Active Deployments</h3>
                </div>

                <div class="space-y-4 max-h-[500px] overflow-y-auto pr-2 custom-scrollbar">
                    @forelse($activeDeployments as $sector => $tasks)
                        <div class="p-4 bg-white/5 border border-white/10 rounded-2xl">
                            <div class="text-xs font-bold text-teal-400 mb-2 uppercase">{{ $sector }}</div>
                            @foreach($tasks as $task)
                                <div class="flex items-center justify-between mb-2 last:mb-0">
                                    <span class="text-xs font-medium text-gray-300">{{ $task->collector->name ?? 'Staff' }}</span>
                                    <span class="text-[9px] bg-teal-500/20 text-teal-300 px-2 py-0.5 rounded-full font-bold">DEPLOYED</span>
                                </div>
                            @endforeach
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <p class="text-xs text-gray-500">No active deployments for today.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="bg-teal-50 rounded-3xl p-6 border border-teal-100">
                <h4 class="text-sm font-bold text-teal-900 mb-2 italic">Pro-Tip for Admin</h4>
                <p class="text-[11px] leading-relaxed text-teal-700">
                    Check the dark panel above to see which collector is already working in which sector. Assign incoming requests to the nearest available collector to optimize city routes.
                </p>
            </div>
        </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const requestSelect = document.getElementById('requestSelect');
            if (requestSelect.value) {
                handleRequestChange(requestSelect);
            }
        });

        function handleRequestChange(select) {
            const form = document.getElementById('assignForm');
            const selectedOption = select.options[select.selectedIndex];
            
            if (selectedOption.value) {
                // Update form action
                form.action = `/admin/requests/${selectedOption.value}/assign`;
                
                // Auto-fill sector
                const sector = selectedOption.getAttribute('data-sector');
                if (sector) {
                    document.getElementById('sectorSelect').value = sector;
                }
                
                // Auto-fill road
                const road = selectedOption.getAttribute('data-road');
                if (road) {
                    document.getElementById('roadInput').value = road;
                }
            } else {
                form.action = '';
                document.getElementById('sectorSelect').value = '';
                document.getElementById('roadInput').value = '';
            }
        }
    </script>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
    </style>
@endsection
