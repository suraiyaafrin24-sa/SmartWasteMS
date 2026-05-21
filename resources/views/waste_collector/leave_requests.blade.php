@extends('layouts.waste_collector')

@section('title', 'Leave Request')

@section('content')
    <div class="mb-8 mt-2">
        <h1 class="text-[28px] font-black tracking-tight text-gray-900 mb-1">Leave Request</h1>
        <p class="text-sm font-medium text-gray-500">Send leave requests to admin for approval.</p>
    </div>

    @if(session('error'))
        <div class="mb-4 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <span class="font-semibold text-sm">{{ session('error') }}</span>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl">
            <ul class="list-disc list-inside text-sm font-semibold">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-[32px] p-8 border border-gray-100 shadow-sm mb-8">
        <form action="{{ route('waste_collector.leave_requests.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Start Date</label>
                    <input type="date" name="start_date" required min="{{ date('Y-m-d') }}"
                        class="w-full bg-gray-50 border border-gray-100 text-gray-800 text-sm font-bold rounded-xl p-3.5 focus:ring-4 focus:ring-teal-100 focus:border-[#19817E] outline-none">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">End Date</label>
                    <input type="date" name="end_date" min="{{ date('Y-m-d') }}"
                        class="w-full bg-gray-50 border border-gray-100 text-gray-800 text-sm font-bold rounded-xl p-3.5 focus:ring-4 focus:ring-teal-100 focus:border-[#19817E] outline-none">
                    <p class="mt-1.5 text-[9px] font-bold text-gray-400">Leave blank for single day assignment.</p>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Reason</label>
                    <input type="text" name="reason" required
                        class="w-full bg-gray-50 border border-gray-100 text-gray-800 text-sm font-bold rounded-xl p-3.5 focus:ring-4 focus:ring-teal-100 focus:border-[#19817E] outline-none">
                </div>
            </div>
            <div class="mt-6 flex justify-end">
                <button type="submit" class="bg-[#19817E] text-white text-[10px] font-black px-6 py-3 rounded-2xl uppercase tracking-widest">Send Request</button>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-[32px] border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50">
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Date Range</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Reason</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($leaveRequests as $leave)
                    <tr>
                        <td class="px-6 py-5 text-sm font-black text-gray-800">
                            {{ $leave->start_date->format('M d, Y') }} - {{ $leave->end_date->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-5 text-sm font-bold text-gray-500">{{ $leave->reason ?? 'N/A' }}</td>
                        <td class="px-6 py-5 text-center">
                            <span class="px-3 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-widest {{ $leave->status === 'Approved' ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }}">
                                {{ $leave->status }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-12 text-center text-gray-400 font-bold uppercase tracking-widest text-[11px]">No leave requests found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if($leaveRequests->hasPages())
            <div class="px-6 py-4 border-t border-gray-50">{{ $leaveRequests->links() }}</div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const occupiedDates = @json($occupiedDates ?? []);
            const startDateInput = document.querySelector('input[name="start_date"]');
            const endDateInput = document.querySelector('input[name="end_date"]');

            function validateDates() {
                const startVal = startDateInput.value;
                const endVal = endDateInput.value;

                if (startVal && occupiedDates.includes(startVal)) {
                    startDateInput.setCustomValidity('You have already requested or taken leave on this date.');
                    startDateInput.reportValidity();
                    startDateInput.value = '';
                    return;
                } else {
                    startDateInput.setCustomValidity('');
                }

                if (endVal && occupiedDates.includes(endVal)) {
                    endDateInput.setCustomValidity('You have already requested or taken leave on this date.');
                    endDateInput.reportValidity();
                    endDateInput.value = '';
                    return;
                } else {
                    endDateInput.setCustomValidity('');
                }

                if (startVal && endVal) {
                    const start = new Date(startVal + 'T00:00:00');
                    const end = new Date(endVal + 'T00:00:00');
                    let current = new Date(start);
                    let hasOverlap = false;

                    while (current <= end) {
                        const year = current.getFullYear();
                        const month = String(current.getMonth() + 1).padStart(2, '0');
                        const day = String(current.getDate()).padStart(2, '0');
                        const dateString = `${year}-${month}-${day}`;

                        if (occupiedDates.includes(dateString)) {
                            hasOverlap = true;
                            break;
                        }
                        current.setDate(current.getDate() + 1);
                    }

                    if (hasOverlap) {
                        endDateInput.setCustomValidity('The selected date range overlaps with your existing leaves.');
                        endDateInput.reportValidity();
                        endDateInput.value = '';
                    } else {
                        endDateInput.setCustomValidity('');
                    }
                }
            }

            if (startDateInput) {
                startDateInput.addEventListener('change', validateDates);
                startDateInput.addEventListener('input', validateDates);
            }
            if (endDateInput) {
                endDateInput.addEventListener('change', validateDates);
                endDateInput.addEventListener('input', validateDates);
            }
            
            const form = document.querySelector('form[action*="leave_requests"]');
            if (form) {
                form.addEventListener('submit', function(e) {
                    validateDates();
                    if (!startDateInput.checkValidity() || !endDateInput.checkValidity()) {
                        e.preventDefault();
                    }
                });
            }
        });
    </script>
@endsection
