@extends('layouts.landing')

@section('content')
<div class="relative pt-24 pb-12 overflow-hidden">
    <!-- Tab Selector -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-center mb-10 relative z-20">
        <div class="inline-flex p-1.5 bg-neutral-900/80 border border-neutral-800 rounded-2xl backdrop-blur-md">
            <button @click="activeTab = 'brand'; window.history.replaceState(null, '', '/?tab=brand')" 
                :class="activeTab === 'brand' ? 'bg-gradient-to-r from-brand to-brand-hover text-white shadow-lg shadow-brand/20' : 'text-slate-400 hover:text-white'" 
                class="px-8 py-3 rounded-xl text-sm font-bold transition-all duration-300 flex items-center gap-2">
                <i data-lucide="building-2" class="w-4 h-4"></i> Untuk Merek / Brand
            </button>
            <button @click="activeTab = 'creator'; window.history.replaceState(null, '', '/?tab=creator')" 
                :class="activeTab === 'creator' ? 'bg-gradient-to-r from-brand to-brand-hover text-white shadow-lg shadow-brand/20' : 'text-slate-400 hover:text-white'" 
                class="px-8 py-3 rounded-xl text-sm font-bold transition-all duration-300 flex items-center gap-2">
                <i data-lucide="clapperboard" class="w-4 h-4"></i> Untuk Kreator / Clipper
            </button>
        </div>
    </div>

    <!-- Brand View -->
    <div x-show="activeTab === 'brand'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
        @include('landing.sections.brand_view')
    </div>

    <!-- Creator View -->
    <div x-show="activeTab === 'creator'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
        @include('landing.sections.creator_view')
    </div>
</div>
@endsection
