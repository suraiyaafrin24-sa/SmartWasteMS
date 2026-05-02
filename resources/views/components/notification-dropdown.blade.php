<div class="relative" x-data="{ open: false }">
    <button @click="open = !open" class="hover:text-gray-800 transition relative">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
        </svg>
        @if(auth()->user()->unreadNotifications->count() > 0)
            <span class="absolute top-0 right-0 block w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white"></span>
        @endif
    </button>

    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-xl border border-gray-100 z-50 overflow-hidden" style="display: none;">
        <div class="p-4 border-b border-gray-50 flex justify-between items-center">
            <h3 class="text-sm font-black text-gray-900 uppercase tracking-wider">Notifications</h3>
            @if(auth()->user()->unreadNotifications->count() > 0)
                <form action="{{ route('notifications.markAllRead') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-[10px] font-black text-teal-600 hover:underline uppercase tracking-widest">Mark all as read</button>
                </form>
            @endif
        </div>
        <div class="max-h-96 overflow-y-auto">
            @forelse(auth()->user()->notifications->take(10) as $notification)
                <div class="p-4 border-b border-gray-50 {{ $notification->read_at ? 'opacity-60' : 'bg-teal-50/30' }} hover:bg-gray-50 transition">
                    <div class="flex gap-3">
                        <div class="shrink-0 mt-1">
                            @if($notification->data['type'] == 'collected')
                                <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                            @elseif($notification->data['type'] == 'new_request')
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                </div>
                            @else
                                <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center text-amber-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                            @endif
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-800 leading-snug">{{ $notification->data['message'] }}</p>
                            <p class="text-[10px] text-gray-400 font-medium mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center">
                    <p class="text-xs text-gray-500 font-medium">No notifications yet</p>
                </div>
            @endforelse
        </div>
        <div class="p-3 bg-gray-50 text-center">
            <a href="#" class="text-[10px] font-black text-gray-500 hover:text-gray-900 uppercase tracking-widest">View All Notifications</a>
        </div>
    </div>
</div>
