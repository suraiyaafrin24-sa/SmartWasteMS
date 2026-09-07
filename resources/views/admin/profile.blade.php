@extends('layouts.admin')

@section('title', 'Admin Settings')

@section('content')
    @if(session('success'))
        <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-600 px-4 py-3 rounded-xl flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
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
        <h1 class="text-[28px] font-black tracking-tight text-gray-900 mb-1">Account Settings</h1>
        <p class="text-sm font-medium text-gray-500">Update your personal information and profile security.</p>
    </div>

    <div class="max-w-4xl mx-auto">
        <!-- Main Form -->
        <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm">
                <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Profile Picture Upload Section -->
                    <div class="flex flex-col items-center sm:flex-row sm:items-end gap-10 mb-12">
                        <div class="relative shrink-0">
                            <div style="width: 120px; height: 120px;" class="rounded-full border-[6px] border-white shadow-xl overflow-hidden bg-gray-50 relative ring-1 ring-gray-100">
                                @if(Auth::user()->profile_image)
                                    <img id="profilePreview" src="{{ asset('storage/' . Auth::user()->profile_image) }}" class="w-full h-full object-cover">
                                @else
                                    <img id="profilePreview" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=F3F4F6&color=9CA3AF&size=128" class="w-full h-full object-cover">
                                @endif
                            </div>
                            <input type="file" name="profile_image" id="profile_image" class="hidden" accept="image/*">
                        </div>
                        
                        <div class="flex flex-col gap-3 pb-2">
                            <h4 class="text-sm font-black text-gray-800 tracking-tight uppercase">Profile Photo</h4>
                            
                            <div class="flex items-center gap-3 mt-2">
                                <label for="profile_image" style="background-color: #2D5CFE !important; color: #FFFFFF !important;" class="p-4 rounded-xl transition shadow-lg shadow-blue-500/20 cursor-pointer flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                </label>
                                <button type="button" onclick="document.getElementById('realDeleteForm').submit();" class="bg-white border border-gray-100 p-4 rounded-xl hover:bg-red-50 transition group shadow-sm">
                                    <svg class="w-5 h-5 text-gray-400 group-hover:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                            <span id="fileHint" class="hidden text-[10px] font-black text-emerald-600 uppercase tracking-widest mt-1"></span>
                        </div>
                    </div>

                    <!-- Form Fields -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest">First Name <span class="text-red-500">*</span></label>
                            <input type="text" name="first_name" value="{{ explode(' ', Auth::user()->name)[0] }}" placeholder="First name" class="w-full bg-white border border-gray-200 text-gray-800 text-sm rounded-xl p-4 focus:ring-2 focus:ring-blue-100 focus:border-blue-700 outline-none transition-all placeholder:text-gray-300">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest">Last Name <span class="text-red-500">*</span></label>
                            <input type="text" name="last_name" value="{{ explode(' ', Auth::user()->name)[1] ?? '' }}" placeholder="Last name" class="w-full bg-white border border-gray-200 text-gray-800 text-sm rounded-xl p-4 focus:ring-2 focus:ring-blue-100 focus:border-blue-700 outline-none transition-all placeholder:text-gray-300">
                        </div>
                        
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest">Email</label>
                            <input type="email" value="{{ Auth::user()->email }}" disabled class="w-full bg-white border border-gray-200 text-gray-800 text-sm rounded-xl p-4 outline-none placeholder:text-gray-300 opacity-60">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest">Mobile Number <span class="text-red-500">*</span></label>
                            <input type="text" name="phone" value="{{ Auth::user()->phone }}" placeholder="018XXXXXXXX" class="w-full bg-white border border-gray-200 text-gray-800 text-sm rounded-xl p-4 focus:ring-2 focus:ring-blue-100 focus:border-blue-700 outline-none transition-all placeholder:text-gray-300">
                        </div>

                        <div class="md:col-span-2 space-y-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest">Office Address</label>
                            <textarea name="address" rows="3" placeholder="Enter your office address" class="w-full bg-white border border-gray-200 text-gray-800 text-sm rounded-xl p-4 focus:ring-2 focus:ring-blue-100 focus:border-blue-700 outline-none transition-all placeholder:text-gray-300">{{ Auth::user()->address }}</textarea>
                        </div>
                    </div>

                    <div class="mt-8">
                        <button type="submit" style="background-color: #2D5CFE !important; color: #FFFFFF !important;" class="text-[12px] font-black py-4 px-10 rounded-2xl hover:opacity-90 transition shadow-lg shadow-blue-500/20 active:scale-[0.98] duration-150 tracking-wide uppercase">
                            SAVE CHANGES
                        </button>
                    </div>
                </form>
            </div>

            <!-- Password Change Card -->
            <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm mt-8">
                <div class="mb-6 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002-2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-gray-800 tracking-tight">SECURITY SETTINGS</h3>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Update your password</p>
                    </div>
                </div>

                <form action="{{ route('admin.password.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                        <div class="md:col-span-2 space-y-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest">Current Password</label>
                            <input type="password" name="current_password" placeholder="••••••••" class="w-full bg-white border border-gray-200 text-gray-800 text-sm rounded-xl p-4 focus:ring-2 focus:ring-amber-100 focus:border-amber-500 outline-none transition-all placeholder:text-gray-300">
                        </div>
                        
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest">New Password</label>
                            <input type="password" name="password" placeholder="••••••••" class="w-full bg-white border border-gray-200 text-gray-800 text-sm rounded-xl p-4 focus:ring-2 focus:ring-amber-100 focus:border-amber-500 outline-none transition-all placeholder:text-gray-300">
                        </div>
                        
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest">Confirm New Password</label>
                            <input type="password" name="password_confirmation" placeholder="••••••••" class="w-full bg-white border border-gray-200 text-gray-800 text-sm rounded-xl p-4 focus:ring-2 focus:ring-amber-100 focus:border-amber-500 outline-none transition-all placeholder:text-gray-300">
                        </div>
                    </div>

                    <div class="mt-8">
                        <button type="submit" style="background-color: #1F2937 !important; color: #FFFFFF !important;" class="text-[12px] font-black py-4 px-10 rounded-2xl hover:opacity-90 transition shadow-lg shadow-gray-200 active:scale-[0.98] duration-150 tracking-wide uppercase">
                            UPDATE PASSWORD
                        </button>
                    </div>
                </form>
            </div>
    </div>
    <script>
        document.getElementById('profile_image').onchange = function(evt) {
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
    <form action="{{ route('admin.profile.avatar.delete') }}" method="POST" id="realDeleteForm" class="hidden">
        @csrf
        @method('DELETE')
    </form>
@endsection
