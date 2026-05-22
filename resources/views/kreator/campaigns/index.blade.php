@extends('layouts.kreator')

@section('title', 'Cari Campaign (Marketplace)')

@section('content')
<div class="space-y-6 pb-12 pt-2" x-data="{ currentType: 'all', searchQuery: '' }">

    

    {{-- CONTROLS --}}
    <div class="flex gap-3 items-center flex-wrap">
        {{-- Search --}}
        <div class="relative flex-1 min-w-[200px] max-w-md">
            <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-500"></i>
            <input type="text" 
                   x-model="searchQuery" 
                   placeholder="Cari campaign..." 
                   class="w-full bg-[#0f0f0f] border border-white/[0.08] text-sm text-white rounded-lg py-2.5 pr-4 pl-10 outline-none transition-all focus:border-white/[0.16] placeholder-slate-500">
        </div>

        {{-- Type Filter --}}
        <div class="flex items-center gap-2 bg-[#0f0f0f] border border-white/[0.08] rounded-lg p-1">
            <button @click="currentType = 'all'" 
                    :class="currentType === 'all' ? 'bg-white text-black' : 'text-slate-400 hover:text-white'"
                    class="px-4 py-2 rounded-md text-xs font-medium transition-all">
                Semua
            </button>
            <button @click="currentType = 'clip'" 
                    :class="currentType === 'clip' ? 'bg-white text-black' : 'text-slate-400 hover:text-white'"
                    class="px-4 py-2 rounded-md text-xs font-medium transition-all">
                Nge-clip
            </button>
            <button @click="currentType = 'ugc'" 
                    :class="currentType === 'ugc' ? 'bg-white text-black' : 'text-slate-400 hover:text-white'"
                    class="px-4 py-2 rounded-md text-xs font-medium transition-all">
                UGC
            </button>
        </div>
    </div>

    {{-- CAMPAIGN GRID --}}
    <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4" id="ccGrid">
        @foreach($campaigns as $idx => $c)

        <a href="{{ $c['full'] ? '#' : route('kreator.campaigns.show', $c['id']) }}" 
           class="bg-[#0f0f0f] border border-white/[0.08] rounded-xl overflow-hidden flex flex-col transition-all hover:border-white/[0.16] {{ $c['full'] ? 'opacity-50 cursor-not-allowed' : 'hover:-translate-y-1' }}"
           x-show="(currentType === 'all' || currentType === '{{ $c['type'] }}') && (searchQuery === '' || '{{ strtolower(addslashes($c['title'] . ' ' . $c['brand'] . ' ' . $c['category'])) }}'.includes(searchQuery.toLowerCase()))">

            {{-- THUMBNAIL --}}
            <div class="relative w-full aspect-video overflow-hidden bg-black/20">
                <img src="{{ asset($c['image']) }}" alt="{{ $c['title'] }}" loading="lazy" class="w-full h-full object-cover">
                
                {{-- Overlay gradient --}}
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>

                {{-- Type badge --}}
                <span class="absolute top-2 left-2 text-[10px] font-medium py-1 px-2 rounded-md bg-black/60 backdrop-blur-sm text-white border border-white/10">
                    {{ $c['type'] === 'clip' ? 'Nge-clip' : 'UGC' }}
                </span>

                {{-- Status badge --}}
                @if(!$c['full'])
                <span class="absolute top-2 right-2 text-[10px] font-medium py-1 px-2 rounded-md bg-emerald-500/20 backdrop-blur-sm text-emerald-400 border border-emerald-500/30">
                    {{ $c['statusText'] }}
                </span>
                @else
                <span class="absolute top-2 right-2 text-[10px] font-medium py-1 px-2 rounded-md bg-red-500/20 backdrop-blur-sm text-red-400 border border-red-500/30">
                    HABIS
                </span>
                @endif

                {{-- Brand --}}
                <div class="absolute bottom-2 left-2 flex items-center gap-1.5 bg-black/60 backdrop-blur-sm rounded-md py-1 px-2 border border-white/10">
                    <span class="w-2 h-2 rounded-full" style="background: {{ $c['dotColor'] }}"></span>
                    <span class="text-[10px] font-medium text-white">{{ $c['brand'] }}</span>
                </div>
            </div>

            {{-- CARD BODY --}}
            <div class="p-4 flex-1 flex flex-col">
                {{-- Title --}}
                <h3 class="text-sm font-semibold text-white leading-tight mb-2 line-clamp-2">{{ $c['title'] }}</h3>

                {{-- Desc --}}
                <p class="text-xs text-slate-500 leading-relaxed mb-4 line-clamp-2 flex-1">{{ $c['desc'] }}</p>

                {{-- Stats --}}
                <div class="flex items-center justify-between pt-3 border-t border-white/[0.06]">
                    <div class="text-center">
                        <p class="text-[10px] text-slate-600 mb-0.5">Deadline</p>
                        <p class="text-xs font-medium text-white">{{ $c['deadline'] }}</p>
                    </div>
                    <div class="text-center">
                        <p class="text-[10px] text-slate-600 mb-0.5">Slot</p>
                        <p class="text-xs font-medium {{ $c['crColor'] }}">{{ $c['creator'] }}</p>
                    </div>
                    <div class="text-center">
                        <p class="text-[10px] text-slate-600 mb-0.5">Rate</p>
                        <p class="text-xs font-medium text-emerald-400">{{ $c['rate'] }}</p>
                    </div>
                </div>
            </div>
        </a>

        @endforeach
    </div>

</div>
@endsection
