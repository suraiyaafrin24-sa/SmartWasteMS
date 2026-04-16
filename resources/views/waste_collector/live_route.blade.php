@extends('layouts.waste_collector')

@section('title', 'Live Optimized Route')

@section('content')
    <div class="mb-8 mt-2 flex justify-between items-end">
        <div>
            <h1 class="text-[28px] font-black tracking-tight text-gray-900 mb-1">Live Optimized Route</h1>
            <p class="text-sm font-medium text-gray-500">Your current pickup sequence for maximum efficiency.</p>
        </div>
        <div class="flex gap-3">
             <a href="{{ route('waste_collector.map') }}" style="background-color: #2D5CFE !important; color: #FFFFFF !important;" class="text-[10px] font-black py-2.5 px-6 rounded-xl hover:opacity-90 transition flex items-center gap-2 shadow-lg shadow-blue-500/20 active:scale-[0.98] uppercase tracking-wide">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                View Map Mode
            </a>
        </div>
    </div>

    <div class="space-y-6">
        @forelse($activeRoute as $index => $request)
            <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-6 relative overflow-hidden group hover:border-blue-200 transition-all">
                <!-- Timeline Indicator -->
                <div class="absolute left-0 top-0 bottom-0 w-1.5 {{ $index == 0 ? 'bg-blue-500' : 'bg-gray-200' }}"></div>
                
                <div class="flex items-center gap-6 pl-2">
                    <div class="w-14 h-14 rounded-2xl {{ $index == 0 ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/20' : 'bg-gray-50 text-gray-400' }} flex flex-col items-center justify-center shrink-0">
                        <span class="text-[10px] font-black uppercase tracking-tighter leading-none mb-1">Stop</span>
                        <span class="text-xl font-black leading-none">{{ $index + 1 }}</span>
                    </div>
                    
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="px-2 py-0.5 rounded text-[8px] font-black uppercase tracking-wider
                                @if($request->type == 'Organic') bg-emerald-50 text-emerald-600
                                @elseif($request->type == 'Recyclable') bg-blue-50 text-blue-600
                                @elseif($request->type == 'Hazardous') bg-red-50 text-red-600
                                @else bg-gray-50 text-gray-600 @endif">
                                {{ $request->type }}
                            </span>
                            @if($index == 0)
                                <span class="bg-blue-50 text-blue-600 px-2 py-0.5 rounded text-[8px] font-black uppercase tracking-wider animate-pulse">Next Stop</span>
                            @endif
                        </div>
                        <h3 class="text-lg font-black text-gray-800 tracking-tight leading-none mb-1">{{ $request->address }}</h3>
                        <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">Client: {{ $request->user->name }} • {{ $request->phone }}</p>
                    </div>
                </div>

                <div class="flex flex-col md:items-end gap-3 shrink-0">
                    <div class="text-right">
                        <div class="text-sm font-black text-gray-800">{{ $request->scheduled_time ? date('h:i A', strtotime($request->scheduled_time)) : 'ASAP' }}</div>
                        <div class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Target Pickup</div>
                    </div>
                    <form action="{{ route('waste_collector.requests.complete', $request->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="{{ $index == 0 ? 'bg-emerald-500 shadow-emerald-500/20 shadow-lg' : 'bg-gray-100 text-gray-400' }} text-white text-[10px] font-black px-8 py-3 rounded-2xl hover:opacity-90 transition uppercase tracking-widest">
                            Confirm Collection
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-[32px] p-20 border border-gray-50 text-center">
                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center text-gray-300 mx-auto mb-4">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                </div>
                <h3 class="text-xl font-black text-gray-800 tracking-tight mb-1">No Active Route</h3>
                <p class="text-sm text-gray-400 font-bold uppercase tracking-widest">Go to 'My Tasks' to accept new job offers.</p>
            </div>
        @endforelse
    </div>
@endsection
