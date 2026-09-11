@extends('layouts.user')

@section('title', 'Submit Report')

@section('content')
    <div class="mb-8 mt-2">
        <h1 class="text-[28px] font-black tracking-tight text-gray-900 mb-1">Submit Issue Report</h1>
        <p class="text-sm font-medium text-gray-500">Tell us what's wrong and we'll fix it.</p>
    </div>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-[40px] p-10 border border-gray-50 shadow-sm shadow-gray-100/50">
            <form action="{{ route('user.reports.store') }}" method="POST">
                @csrf
                
                <div class="space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-2">Complaint Against <span class="text-red-500">*</span></label>
                            <input type="text" name="complained_person" value="{{ old('complained_person') }}" required placeholder="e.g. Collector name or staff name" class="w-full bg-slate-50 border-none text-slate-800 text-sm rounded-xl py-4 px-5 focus:ring-1 focus:ring-teal-200 transition-all outline-none font-bold placeholder:text-slate-300">
                            @error('complained_person') <p class="text-[10px] font-black text-red-500 uppercase tracking-widest mt-2 px-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-2">Incident Date <span class="text-red-500">*</span></label>
                            <input type="date" name="incident_date" value="{{ old('incident_date') }}" max="{{ date('Y-m-d') }}" required class="w-full bg-slate-50 border-none text-slate-800 text-sm rounded-xl py-4 px-5 focus:ring-1 focus:ring-teal-200 transition-all outline-none font-bold">
                            @error('incident_date') <p class="text-[10px] font-black text-red-500 uppercase tracking-widest mt-2 px-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-2">Issue Subject <span class="text-red-500">*</span></label>
                        <input type="text" name="subject" value="{{ old('subject') }}" required placeholder="e.g. Missed pickup in Sector 1" class="w-full bg-slate-50 border-none text-slate-800 text-sm rounded-xl py-4 px-5 focus:ring-1 focus:ring-teal-200 transition-all outline-none font-bold placeholder:text-slate-300">
                        @error('subject') <p class="text-[10px] font-black text-red-500 uppercase tracking-widest mt-2 px-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-2">Detailed Description <span class="text-red-500">*</span></label>
                        <textarea name="description" rows="6" required placeholder="Please provide as much detail as possible to help us resolve the issue faster..." class="w-full bg-slate-50 border-none text-slate-800 text-sm rounded-2xl py-4 px-5 focus:ring-1 focus:ring-teal-200 transition-all outline-none font-bold placeholder:text-slate-300 resize-none">{{ old('description') }}</textarea>
                        @error('description') <p class="text-[10px] font-black text-red-500 uppercase tracking-widest mt-2 px-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="pt-6 border-t border-gray-50 flex items-center justify-between">
                        <a href="{{ route('user.reports.index') }}" class="text-[11px] font-black text-slate-400 hover:text-slate-600 transition-colors uppercase tracking-widest">Discard & Go Back</a>
                        <button type="submit" class="bg-[#19817E] text-white text-[13px] font-black py-4 px-12 rounded-2xl hover:bg-teal-700 transition shadow-lg shadow-teal-700/20 uppercase tracking-widest">
                            Submit Report
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
