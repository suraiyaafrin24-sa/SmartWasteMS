@extends('layouts.waste_collector')

@section('title', 'Collector Settings')

@section('content')
    @if(session('success'))
        <div
            class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-600 px-4 py-3 rounded-xl flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span class="font-semibold text-sm">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl">
            <ul class="list-disc list-inside text-sm font-semibold">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="mb-8 mt-2">
        <h1 class="text-2xl font-black tracking-tight text-slate-800 mb-1">Staff Settings</h1>
        <p class="text-[13px] font-medium text-slate-400">Manage your collector profile and professional credentials.</p>
    </div>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-[40px] p-10 border border-gray-50 shadow-sm shadow-gray-100/50">
            <form action="{{ route('waste_collector.settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <!-- Staff Photo Upload -->
                <div class="flex flex-col items-center sm:flex-row sm:items-center gap-8 mb-12 pb-10 border-b border-gray-50">
                    <div class="relative shrink-0">
                        <div class="w-24 h-24 rounded-full border-4 border-white shadow-xl overflow-hidden bg-slate-50 relative">
                            @if(Auth::user()->profile_image)
                                <img id="profilePreview" src="{{ asset('storage/' . Auth::user()->profile_image) }}" class="w-full h-full object-cover">
                            @else
                                <div id="profilePreviewPlaceholder" class="w-full h-full flex items-center justify-center font-black text-slate-300 text-3xl">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <img id="profilePreview" src="" class="w-full h-full object-cover hidden">
                            @endif
                        </div>
                        <input type="file" name="profile_image" id="profile_image" class="hidden" accept="image/*">
                    </div>
                    
                    <div class="flex flex-col gap-2">
                        <h4 class="text-[11px] font-black text-slate-400 uppercase tracking-widest leading-none">Employee Photo</h4>
                        <div class="flex items-center gap-3 mt-1">
                            <label for="profile_image" class="bg-[#DCF5F2] text-[#19817E] text-[11px] font-black py-2.5 px-6 rounded-xl hover:bg-[#19817E] hover:text-white transition-all duration-300 uppercase tracking-widest cursor-pointer">
                                Update Photo
                            </label>
                            <button type="button" onclick="document.getElementById('realDeleteForm').submit();" class="text-red-400 hover:text-red-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                        <span id="fileHint" class="hidden text-[10px] font-black text-emerald-500 uppercase tracking-widest mt-1"></span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <div>
                        <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-2">First Name <span class="text-red-500">*</span></label>
                        <input type="text" name="first_name" value="{{ explode(' ', Auth::user()->name)[0] }}" class="w-full bg-slate-50 border-none text-slate-800 text-sm rounded-xl py-3.5 px-4 focus:ring-1 focus:ring-teal-200 transition-all outline-none font-bold">
                    </div>
                    <div>
                        <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-2">Last Name <span class="text-red-500">*</span></label>
                        <input type="text" name="last_name" value="{{ explode(' ', Auth::user()->name)[1] ?? '' }}" class="w-full bg-slate-50 border-none text-slate-800 text-sm rounded-xl py-3.5 px-4 focus:ring-1 focus:ring-teal-200 transition-all outline-none font-bold">
                    </div>
                    <div>
                        <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-2">Employee Email</label>
                        <input type="email" value="{{ Auth::user()->email }}" disabled class="w-full bg-slate-50 border-none text-slate-500 text-sm rounded-xl py-3.5 px-4 outline-none font-bold cursor-not-allowed">
                    </div>
                    <div>
                        <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-2">Contact Number <span class="text-red-500">*</span></label>
                        <input type="text" name="phone" value="{{ Auth::user()->phone }}" class="w-full bg-slate-50 border-none text-slate-800 text-sm rounded-xl py-3.5 px-4 focus:ring-1 focus:ring-teal-200 transition-all outline-none font-bold">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-2">Present Address</label>
                        <textarea name="address" rows="3" class="w-full bg-slate-50 border-none text-slate-800 text-sm rounded-xl py-3.5 px-4 focus:ring-1 focus:ring-teal-200 transition-all outline-none font-bold">{{ Auth::user()->address }}</textarea>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <button type="submit" class="bg-[#19817E] text-white text-[13px] font-black py-4 px-12 rounded-2xl hover:bg-teal-700 transition shadow-lg shadow-teal-700/20 uppercase tracking-widest">
                        Save Updates
                    </button>
                    <div class="flex items-center gap-3 text-[10px] font-black text-emerald-500 uppercase tracking-[0.2em]">
                        <span class="relative flex h-2 w-2">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                        </span>
                        Identity Verified
                    </div>
                </div>
            </form>
        </div>

            <div class="mt-16 pt-12 border-t border-gray-50">
                <div class="mb-8 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002-2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-slate-800 tracking-tight">Security</h3>
                        <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest mt-0.5">Update employee account password</p>
                    </div>
                </div>

                <form action="{{ route('waste_collector.password.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="md:col-span-2">
                            <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-2">Current Password</label>
                            <input type="password" name="current_password" placeholder="••••••••" class="w-full bg-slate-50 border-none text-slate-800 text-sm rounded-xl py-3.5 px-4 focus:ring-1 focus:ring-teal-200 transition-all outline-none font-bold">
                        </div>
                        
                        <div>
                            <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-2">New Password</label>
                            <input type="password" name="password" placeholder="••••••••" class="w-full bg-slate-50 border-none text-slate-800 text-sm rounded-xl py-3.5 px-4 focus:ring-1 focus:ring-teal-200 transition-all outline-none font-bold">
                        </div>
                        
                        <div>
                            <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-2">Confirm New Password</label>
                            <input type="password" name="password_confirmation" placeholder="••••••••" class="w-full bg-slate-50 border-none text-slate-800 text-sm rounded-xl py-3.5 px-4 focus:ring-1 focus:ring-teal-200 transition-all outline-none font-bold">
                        </div>
                    </div>

                    <div class="mt-8 flex justify-start">
                        <button type="submit" class="bg-[#0D161F] text-white text-[13px] font-black py-4 px-10 rounded-2xl hover:bg-black transition shadow-lg shadow-slate-900/20 uppercase tracking-widest">
                            Update Password
                        </button>
                    </div>
                </form>
        </div>
    </div>
    <script>
        document.getElementById('profile_image').onchange = function (evt) {
            const [file] = this.files;
            if (file) {
                const preview = document.getElementById('profilePreview');
                preview.src = URL.createObjectURL(file);

                // Show filename hint
                const hint = document.getElementById('fileHint');
                hint.textContent = 'Selected: ' + file.name;
                hint.classList.remove('hidden');
            }
        }
    </script>
    <form action="{{ route('waste_collector.settings.avatar.delete') }}" method="POST" id="realDeleteForm" class="hidden">
        @csrf
        @method('DELETE')
    </form>
@endsection