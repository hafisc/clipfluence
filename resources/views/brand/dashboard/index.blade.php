@extends('layouts.brand')

@section('title', 'Brand Dashboard')

@section('content')

<div class="space-y-5 pb-8">

    {{-- ===== HERO / GREETING CARD ===== --}}
    <div class="bg-gradient-to-br from-violet-800 via-violet-600 to-fuchsia-600 relative overflow-hidden rounded-2xl p-5 lg:p-8 shadow-2xl shadow-violet-900/30">
        <!-- Decorative Circles -->
        <div class="absolute -top-16 -right-16 w-56 h-56 bg-white/5 rounded-full pointer-events-none"></div>
        <div class="absolute -bottom-20 -left-8 w-48 h-48 bg-black/15 rounded-full pointer-events-none"></div>

        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-6">

            {{-- Left: Greeting --}}
            <div class="flex-1 min-w-0">
                <h1 class="text-xl lg:text-3xl font-black text-white tracking-tight leading-snug">
                    Selamat datang, {{ auth()->user()->name }} 👋
                </h1>
                <p class="text-violet-200 text-xs lg:text-sm mt-1.5 leading-relaxed max-w-lg">
                    Pantau kinerja <span class="font-bold text-white">campaign aktif</span> Anda dan review UGC dari kreator hari ini.
                </p>
            </div>

            {{-- Right: Action Buttons (Mobile friendly stack) --}}
            <div class="flex flex-col gap-2.5 sm:w-auto shrink-0 w-full">
                <a href="{{ route('brand.campaigns.create') ?? '#' }}" class="bg-white text-black font-extrabold rounded-xl transition-all duration-200 active:scale-95 flex items-center justify-center gap-2 px-6 py-3 lg:py-2.5 text-xs sm:w-48 whitespace-nowrap">
                    <i data-lucide="plus-circle" class="w-4 h-4"></i> Buat Campaign
                </a>
                <a href="{{ route('brand.finance') ?? '#' }}" class="bg-white/15 text-white border border-white/10 backdrop-blur-md font-extrabold rounded-xl transition-all duration-200 active:scale-95 active:bg-white/25 flex items-center justify-center gap-2 px-6 py-3 lg:py-2.5 text-xs sm:w-48 whitespace-nowrap">
                    <i data-lucide="wallet" class="w-4 h-4"></i> Top-up Saldo
                </a>
            </div>

        </div>
    </div>

    {{-- ===== STAT CARDS: 2-col mobile → 4-col desktop ===== --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-4">
        
        <div class="bg-neutral-900 border border-neutral-800 rounded-2xl transition-all duration-200 active:scale-95 p-4 lg:p-5 text-left">
            <div class="flex items-start justify-between mb-3 lg:mb-4">
                <div class="w-9 h-9 lg:w-11 lg:h-11 rounded-xl  flex items-center justify-center">
                    <img src="{{ asset('assets/images/money.png') }}" alt="Coin" class="w-6 h-6"> 
                </div>
                <p class="text-[10px] lg:text-xs text-slate-500 font-semibold mb-1">Saldo Deposit</p>
            </div>
            <h2 class="text-lg lg:text-2xl font-black text-white leading-none">
                <span class="text-xs lg:text-lg text-slate-400 font-bold mr-0.5">Rp</span>{{ number_format($balance, 0, ',', '.') }}
            </h2>
        </div>

        <div class="bg-neutral-900 border border-neutral-800 rounded-2xl transition-all duration-200 active:scale-95 p-4 lg:p-5 text-left">
            <div class="flex items-start justify-between mb-3 lg:mb-4">
                <div class="w-9 h-9 lg:w-11 lg:h-11 rounded-xl  flex items-center justify-center">
                    <img src="{{ asset('assets/images/fire.png') }}" alt="View" class="w-6 h-6">
                </div>
                 <p class="text-[10px] lg:text-xs text-slate-500 font-semibold mb-1">Total Views</p>
            </div>
           
            <h2 class="text-lg lg:text-2xl font-black text-white leading-none">
                {{ number_format($totalViews, 0, ',', '.') }}
            </h2>
        </div>

        <div class="bg-neutral-900 border border-neutral-800 rounded-2xl transition-all duration-200 active:scale-95 p-4 lg:p-5 text-left">
            <div class="flex items-start justify-between mb-3 lg:mb-4">
                <div class="w-9 h-9 lg:w-11 lg:h-11 rounded-xl bg-blue-500/10 border border-blue-500/20 flex items-center justify-center">
                    <span class="text-xl lg:text-2xl drop-shadow-[0_2px_4px_rgba(59,130,246,0.3)]">🎥</span>
                </div>
            </div>
            <p class="text-[10px] lg:text-xs text-slate-500 font-semibold mb-1">Video UGC Dibuat</p>
            <h2 class="text-lg lg:text-2xl font-black text-white leading-none">{{ $totalUgc }}</h2>
        </div>

        <div class="bg-neutral-900 border border-neutral-800 rounded-2xl transition-all duration-200 active:scale-95 p-4 lg:p-5 relative overflow-hidden text-left">
            <div class="absolute -right-4 -bottom-4 w-20 h-20 bg-amber-500/10 rounded-full blur-xl pointer-events-none"></div>
            <div class="flex items-start justify-between mb-3 lg:mb-4 relative z-10">
                <div class="w-9 h-9 lg:w-11 lg:h-11 rounded-xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center">
                    <span class="text-xl lg:text-2xl drop-shadow-[0_2px_4px_rgba(245,158,11,0.3)]">⏳</span>
                </div>
                @if($pendingReview > 0)
                <span class="text-[9px] font-bold text-amber-400 bg-amber-500/10 border border-amber-500/30 px-1.5 py-0.5 rounded-full animate-pulse">
                    Action Needed
                </span>
                @endif
            </div>
            <p class="text-[10px] lg:text-xs text-amber-500/80 font-bold mb-1 relative z-10">Menunggu Review</p>
            <h2 class="text-lg lg:text-2xl font-black text-white leading-none relative z-10">{{ $pendingReview }}</h2>
        </div>

    </div>

    {{-- ===== QUICK ACTIONS: 3-col on mobile, hidden on desktop ===== --}}
    <div class="lg:hidden">
        <p class="text-xs font-bold tracking-widest uppercase text-slate-500 mb-2.5 px-0.5">Aksi Cepat</p>
        <div class="grid grid-cols-3 gap-2.5">
            <a href="#" class="bg-neutral-900 border border-neutral-800 rounded-2xl transition-all duration-150 active:scale-95 active:bg-neutral-800 flex flex-col items-center justify-center gap-2 p-3.5 text-center">
                <div class="w-10 h-10 rounded-xl bg-violet-500/15 flex items-center justify-center">
                    <i data-lucide="check-square" class="w-5 h-5 text-violet-400"></i>
                </div>
                <span class="text-[10px] font-bold text-slate-300 leading-tight">Review<br>Konten</span>
            </a>
            <a href="#" class="bg-neutral-900 border border-neutral-800 rounded-2xl transition-all duration-150 active:scale-95 active:bg-neutral-800 flex flex-col items-center justify-center gap-2 p-3.5 text-center">
                <div class="w-10 h-10 rounded-xl bg-fuchsia-500/15 flex items-center justify-center">
                    <i data-lucide="bar-chart-2" class="w-5 h-5 text-fuchsia-400"></i>
                </div>
                <span class="text-[10px] font-bold text-slate-300 leading-tight">Laporan<br>Performa</span>
            </a>
            <a href="#" class="bg-neutral-900 border border-neutral-800 rounded-2xl transition-all duration-150 active:scale-95 active:bg-neutral-800 flex flex-col items-center justify-center gap-2 p-3.5 text-center">
                <div class="w-10 h-10 rounded-xl bg-emerald-500/15 flex items-center justify-center">
                    <i data-lucide="receipt" class="w-5 h-5 text-emerald-400"></i>
                </div>
                <span class="text-[10px] font-bold text-slate-300 leading-tight">Riwayat<br>Invoice</span>
            </a>
        </div>
    </div>

    {{-- ===== MAIN LAYOUT (2 Columns) ===== --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 lg:gap-6 mt-2">

        {{-- LEFT COLUMN: Active Campaigns --}}
        <div class="bg-neutral-900 border border-neutral-800 rounded-2xl overflow-hidden lg:col-span-2 flex flex-col">
            <div class="p-5 lg:p-6 pb-4 border-b border-white/5 flex items-center justify-between">
                <div>
                    <h3 class="text-sm lg:text-base font-black text-white mb-0.5">Campaign Aktif</h3>
                    <p class="text-[10px] lg:text-xs text-slate-400">Serapan budget & views real-time.</p>
                </div>
                <a href="#" class="text-[10px] lg:text-xs font-bold text-violet-400 hover:text-violet-300 transition-colors flex items-center gap-1">
                    Lihat <span class="hidden sm:inline">Semua</span> <i data-lucide="arrow-right" class="w-3 h-3"></i>
                </a>
            </div>

            <div class="p-5 lg:p-6 space-y-3 lg:space-y-4">
                @forelse($campaigns as $c)
                <div class="bg-neutral-900 border border-neutral-800 rounded-xl transition-colors duration-200 active:border-neutral-700 p-4 lg:p-5">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
                        <div class="min-w-0 pr-4">
                            <h4 class="text-xs lg:text-sm font-bold text-white mb-1 truncate">{{ $c->title }}</h4>
                            <p class="text-[9px] lg:text-[10px] font-bold text-slate-500 uppercase tracking-widest flex items-center gap-1.5"><i data-lucide="smartphone" class="w-3 h-3"></i> {{ $c->platform ?? 'Mixed' }}</p>
                        </div>
                        <span class="w-fit px-2 py-1 rounded border border-violet-500/20 text-[9px] font-bold uppercase tracking-widest text-violet-400 bg-violet-500/10">
                            {{ strtoupper($c->status) }}
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-2">
                        <div>
                            <p class="text-[9px] lg:text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1">Budget Total</p>
                            <p class="text-xs lg:text-sm font-black text-white">Rp {{ number_format($c->budget, 0, ',', '.') }}</p>
                        </div>
                        <div class="text-right flex flex-col items-end">
                            <p class="text-[9px] lg:text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1">UGC</p>
                            <p class="text-xs lg:text-sm font-black text-white flex items-center gap-1.5">0 <i data-lucide="video" class="w-3.5 h-3.5 text-slate-400"></i></p>
                        </div>
                    </div>

                    <div class="w-full h-1.5 bg-white/5 rounded-full overflow-hidden relative mt-3">
                        <div class="absolute left-0 top-0 h-full bg-gradient-to-r from-violet-500 to-fuchsia-600 rounded-full" style="width: 0%;"></div>
                    </div>
                </div>
                @empty
                <div class="p-10 text-center">
                    <p class="text-xs text-slate-500 font-medium">Belum ada campaign aktif.</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- RIGHT COLUMN: Action Needed (Review) --}}
        <div class="bg-neutral-900 border border-neutral-800 rounded-2xl overflow-hidden lg:col-span-1 flex flex-col">
            <div class="p-5 lg:p-6 pb-4 border-b border-white/5 flex items-center justify-between">
                <div>
                    <h3 class="text-sm lg:text-base font-black text-white flex items-center gap-2">
                        Butuh Review <span class="flex h-2 w-2 relative"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span><span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span></span>
                    </h3>
                    <p class="text-[10px] lg:text-xs text-slate-400 mt-0.5">UGC menunggu ACC.</p>
                </div>
            </div>

            <div class="p-3 lg:p-4 space-y-2 flex-1 overflow-y-auto">
                {{-- Dynamic submissions will be added here once the feature is ready --}}
                <div class="p-10 text-center">
                    <p class="text-xs text-slate-500 font-medium">Belum ada UGC yang butuh review.</p>
                </div>
            </div>

            <div class="p-3 lg:p-4 pt-0">
                <a href="{{ route('brand.submissions') }}" class="w-full py-2.5 rounded-xl text-[10px] font-bold text-slate-400 hover:text-white transition-colors flex items-center justify-center gap-1.5 bg-white/5 border border-white/10">
                    Lihat Semua ({{ $pendingReview }}) <i data-lucide="arrow-right" class="w-3 h-3"></i>
                </a>
            </div>
            
        </div>
    </div>

</div>
@endsection
