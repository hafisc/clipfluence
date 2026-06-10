@extends('layouts.landing')

@section('content')
<div class="relative pt-24 pb-12 overflow-hidden">
    <!-- Tab Selector (Small & Compact - Sesuai Refrensi User) -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-center mb-12 relative z-20">
        {{-- <div class="p-1.5 bg-[#0e0e0e] rounded-xl border border-neutral-800/85 inline-flex justify-start items-center gap-1">
            <button type="button" @click="activeTab = 'creator'; window.history.replaceState(null, '', '/?tab=creator')" 
                :class="activeTab === 'creator' ? 'bg-brand text-white font-medium' : 'text-slate-400 hover:text-white hover:bg-white/5'" 
                class="px-4 py-1.5 rounded-lg inline-flex justify-center items-center transition-all duration-200 cursor-pointer text-sm">
                Creator
            </button>
            <button type="button" @click="activeTab = 'brand'; window.history.replaceState(null, '', '/?tab=brand')" 
                :class="activeTab === 'brand' ? 'bg-brand text-white font-medium' : 'text-slate-400 hover:text-white hover:bg-white/5'" 
                class="px-4 py-1.5 rounded-lg inline-flex justify-center items-center transition-all duration-200 cursor-pointer text-sm">
                Brand
            </button>
        </div> --}}
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
