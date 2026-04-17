@extends('layouts.user')

@section('title', 'Submit Report')

@section('content')
    <div class="mb-8 mt-2">
        <h1 class="text-[28px] font-black tracking-tight text-gray-900 mb-1">Submit Issue Report</h1>
        <p class="text-sm font-medium text-gray-500">Tell us what's wrong and we'll fix it.</p>
    </div>

    <div class="max-w-3xl">
        <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm">
            <form action="{{ route('user.reports.store') }}" method="POST">
                @csrf
                
                <div class="space-y-6">
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest">Issue Subject</label>
                        <input type="text" name="subject" required placeholder="Briefly describe the issue" class="w-full bg-white border border-gray-200 text-gray-800 text-sm rounded-xl p-4 focus:ring-2 focus:ring-blue-100 focus:border-blue-700 outline-none transition-all placeholder:text-gray-300">
                        @error('subject') <p class="text-xs font-bold text-red-500 uppercase tracking-widest mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest">Description</label>
                        <textarea name="description" rows="5" required placeholder="Provide more details about the problem..." class="w-full bg-white border border-gray-200 text-gray-800 text-sm rounded-xl p-4 focus:ring-2 focus:ring-blue-100 focus:border-blue-700 outline-none transition-all placeholder:text-gray-300"></textarea>
                        @error('description') <p class="text-xs font-bold text-red-500 uppercase tracking-widest mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="pt-4 flex items-center justify-between">
                        <a href="{{ route('user.reports.index') }}" class="text-[12px] font-black text-gray-400 hover:text-gray-600 transition uppercase tracking-widest">Cancel</a>
                        <button type="submit" style="background-color: #2D5CFE !important; color: #FFFFFF !important;" class="text-[12px] font-black py-4 px-10 rounded-2xl hover:opacity-90 transition shadow-lg shadow-blue-500/20 active:scale-[0.98] duration-150 tracking-wide uppercase">
                            Submit Report
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
