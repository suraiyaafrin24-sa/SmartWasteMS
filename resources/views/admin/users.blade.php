@extends('layouts.admin')

@section('title', 'Manage Users')

@section('content')
    <div class="mb-8 mt-2 flex justify-between items-end">
        <div>
            <h1 class="text-[28px] font-black tracking-tight text-gray-900 mb-1">User Management</h1>
            <p class="text-sm font-medium text-gray-500">View and manage all system users, collectors, and admins.</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="bg-gray-900 text-white text-xs font-bold py-3 px-8 rounded-xl hover:bg-black transition shadow-lg shadow-gray-200">
            Create New User
        </a>
    </div>

    <div class="bg-white rounded-[32px] border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100">User / Identity</th>
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100">Email Address</th>
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 text-center">Role</th>
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100">Joined Date</th>
                        <th class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50/30 transition group">
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center font-black text-xs shadow-sm overflow-hidden">
                                        @if($user->profile_image)
                                            <img src="{{ asset('storage/' . $user->profile_image) }}" class="w-full h-full object-cover">
                                        @else
                                            {{ substr($user->name, 0, 1) }}
                                        @endif
                                    </div>
                                    <div>
                                        <div class="text-sm font-black text-gray-800 tracking-tight leading-none mb-1">{{ $user->name }}</div>
                                        <div class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">ID: #USR-{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5 text-sm font-medium text-gray-600">
                                {{ $user->email }}
                            </td>
                            <td class="px-6 py-5 text-center">
                                <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-wider
                                    @if($user->role == 'admin') bg-amber-50 text-amber-600
                                    @elseif($user->role == 'waste_collector') bg-teal-50 text-teal-700
                                    @else bg-gray-50 text-gray-600 @endif">
                                    {{ str_replace('_', ' ', $user->role) }}
                                </span>
                            </td>
                            <td class="px-6 py-5">
                                <div class="text-[11px] text-gray-800 font-black tracking-tight leading-none mb-1">{{ $user->created_at->format('M d, Y') }}</div>
                                <div class="text-[9px] text-gray-400 font-bold uppercase tracking-widest">{{ $user->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-6 py-5 text-right">
                                <div class="flex items-center justify-end gap-2">
                                     <button class="text-gray-400 hover:text-blue-600 transition p-2 bg-gray-50 rounded-lg hover:bg-blue-50">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </button>
                                    <button class="text-gray-400 hover:text-red-600 transition p-2 bg-gray-50 rounded-lg hover:bg-red-50">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-20 text-center text-gray-400 font-bold uppercase tracking-[2px]">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $users->links() }}
            </div>
        @endif
    </div>
@endsection
