@extends('layouts.kreator')

@section('title', 'Dashboard')

@push('styles')
@endpush

@push('scripts')
<script>
    // Animated counter
    document.addEventListener('DOMContentLoaded', function () {
        const counters = document.querySelectorAll('[data-count]');
        counters.forEach(el => {
            const target = parseFloat(el.dataset.count);
            const isDecimal = el.dataset.count.includes('.');
            const duration = 1200;
            const start = performance.now();
            const update = (now) => {
                const progress = Math.min((now - start) / duration, 1);
                const ease = 1 - Math.pow(1 - progress, 3);
                const current = ease * target;
                el.textContent = isDecimal
                    ? current.toFixed(1)
                    : Math.round(current).toLocaleString('id-ID');
                if (progress < 1) requestAnimationFrame(update);
            };
            requestAnimationFrame(update);
        });
    });
</script>
@endpush

@section('content')

{{-- ===== WRAPPER: full width on desktop, constrained on mobile ===== --}}
<div class="space-y-5 pb-8">

    {{-- ===== HERO / GREETING CARD ===== --}}
    <div class="bg-gradient-to-br from-[#5b21b6] via-[#7c3aed] to-[#c026d3] relative overflow-hidden rounded-2xl p-5 lg:p-8 shadow-2xl shadow-violet-900/30">
        <!-- Decorative circles -->
        <div class="absolute -top-[60px] -right-[60px] w-[220px] h-[220px] bg-white/5 rounded-full pointer-events-none"></div>
        <div class="absolute -bottom-[80px] -left-[30px] w-[180px] h-[180px] bg-black/15 rounded-full pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">

            {{-- Left: Greeting --}}
            <div class="flex-1">
                {{-- Badge --}}
                <h1 class="text-xl lg:text-3xl font-black text-white tracking-tight leading-snug">
                    Halo, {{ auth()->user()->name }}! 🔥
                </h1>
                <p class="text-violet-200 text-xs lg:text-sm mt-1.5 leading-relaxed max-w-lg">
                    Ada <span class="font-bold text-white">{{ $stats['active_campaigns'] }} campaign baru</span> menantimu. Yuk mulai kerja dan kumpulkan cuan hari ini!
                </p>
            </div>

            {{-- Right: Earnings Box --}}
            <div class="bg-black/20 backdrop-blur-sm border border-white/10 rounded-xl p-4 lg:p-5 flex items-center justify-between lg:flex-col lg:items-start gap-3 lg:min-w-[220px]">
                <div>
                    <p class="text-[10px] text-violet-300 font-semibold uppercase tracking-widest">Total Pendapatan</p>
                    <p class="text-2xl lg:text-3xl font-black text-white mt-1 leading-none">
                        Rp&nbsp;<span data-count="{{ $stats['total_pendapatan'] }}">{{ number_format($stats['total_pendapatan'], 0, ',', '.') }}</span>
                    </p>
                </div>
                <div class="flex flex-col items-end lg:items-start gap-1.5">
                    <div class="flex items-center gap-1.5 {{ $stats['revenue_growth'] >= 0 ? 'bg-emerald-500/20 border-emerald-500/30' : 'bg-red-500/20 border-red-500/30' }} border rounded-lg px-2.5 py-1">
                        <i data-lucide="{{ $stats['revenue_growth'] >= 0 ? 'trending-up' : 'trending-down' }}" class="w-3 h-3 {{ $stats['revenue_growth'] >= 0 ? 'text-emerald-400' : 'text-red-400' }}"></i>
                        <span class="text-[10px] font-bold {{ $stats['revenue_growth'] >= 0 ? 'text-emerald-400' : 'text-red-400' }}">{{ $stats['revenue_growth'] > 0 ? '+' : '' }}{{ $stats['revenue_growth'] }}% bulan ini</span>
                    </div>
                    <a href="{{ route('kreator.finance') }}"
                        class="flex items-center gap-1 text-[10px] font-bold text-white/80 hover:text-white transition-colors">
                        Lihat wallet <i data-lucide="arrow-right" class="w-3 h-3"></i>
                    </a>
                </div>
            </div>

        </div>
    </div>

    {{-- ===== STAT CARDS: 2-col mobile → 4-col desktop ===== --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-4">

        {{-- Saldo Tersedia --}}
        <div class="bg-[#111111] border border-[#1f1f1f] rounded-[1.25rem] transition-all duration-200 active:scale-[0.97] p-4 lg:p-5 cursor-pointer">
            <div class="flex items-start justify-between mb-3 lg:mb-4">
                <div class="w-9 h-9 lg:w-11 lg:h-11 rounded-xl bg-violet-500/10 border border-violet-500/20 flex items-center justify-center">
                    <span class="text-xl lg:text-2xl drop-shadow-[0_2px_4px_rgba(139,92,246,0.3)]">💰</span>
                </div>
            </div>
            <p class="text-[10px] lg:text-xs text-slate-500 font-semibold mb-1">Saldo Tersedia</p>
            <p class="text-lg lg:text-2xl font-black text-white leading-none">
                Rp <span data-count="{{ $stats['saldo_tersedia'] }}">{{ number_format($stats['saldo_tersedia'], 0, ',', '.') }}</span>
            </p>
            <a href="{{ route('kreator.finance') }}"
                class="mt-2 lg:mt-3 inline-flex items-center gap-1 text-[10px] lg:text-xs font-bold text-violet-400 hover:text-violet-300 transition-colors">
                Tarik Dana <i data-lucide="arrow-right" class="w-3 h-3"></i>
            </a>
        </div>

        {{-- Saldo Tertahan --}}
        <div class="bg-[#111111] border border-[#1f1f1f] rounded-[1.25rem] transition-all duration-200 active:scale-[0.97] p-4 lg:p-5 cursor-pointer">
            <div class="flex items-start justify-between mb-3 lg:mb-4">
                <div class="w-9 h-9 lg:w-11 lg:h-11 rounded-xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center">
                    <span class="text-xl lg:text-2xl drop-shadow-[0_2px_4px_rgba(245,158,11,0.3)]">⏳</span>
                </div>
            </div>
            <p class="text-[10px] lg:text-xs text-slate-500 font-semibold mb-1">Dalam Review</p>
            <p class="text-lg lg:text-2xl font-black text-white leading-none">
                Rp <span data-count="{{ $stats['dalam_review'] }}">{{ number_format($stats['dalam_review'], 0, ',', '.') }}</span>
            </p>
            <p class="mt-2 lg:mt-3 text-[10px] lg:text-xs text-slate-600 leading-tight">Menunggu konfirmasi brand</p>
        </div>

        {{-- Total Views --}}
        <div class="bg-[#111111] border border-[#1f1f1f] rounded-[1.25rem] transition-all duration-200 active:scale-[0.97] p-4 lg:p-5 cursor-pointer">
            <div class="flex items-start justify-between mb-3 lg:mb-4">
                <div class="w-9 h-9 lg:w-11 lg:h-11 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center">
                    <span class="text-xl lg:text-2xl drop-shadow-[0_2px_4px_rgba(16,185,129,0.3)]">🔥</span>
                </div>
                <span class="text-[9px] font-bold text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-1.5 py-0.5 rounded-full">
                    Top 10%
                </span>
            </div>
            <p class="text-[10px] lg:text-xs text-slate-500 font-semibold mb-1">Total Views</p>
            <p class="text-lg lg:text-2xl font-black text-white leading-none">
                <span data-count="{{ $stats['total_views'] }}">{{ number_format($stats['total_views'], 0, ',', '.') }}</span>
            </p>
            <p class="mt-2 lg:mt-3 text-[10px] lg:text-xs text-slate-600">dari {{ $stats['videos_approved'] }} video disetujui</p>
        </div>

        {{-- Success Rate --}}
        <div class="bg-[#111111] border border-[#1f1f1f] rounded-[1.25rem] transition-all duration-200 active:scale-[0.97] p-4 lg:p-5 cursor-pointer">
            <div class="flex items-start justify-between mb-3 lg:mb-4">
                <div class="w-9 h-9 lg:w-11 lg:h-11 rounded-xl bg-blue-500/10 border border-blue-500/20 flex items-center justify-center">
                    <span class="text-xl lg:text-2xl drop-shadow-[0_2px_4px_rgba(59,130,246,0.3)]">🎯</span>
                </div>
            </div>
            <p class="text-[10px] lg:text-xs text-slate-500 font-semibold mb-1">Success Rate</p>
            <p class="text-lg lg:text-2xl font-black text-white leading-none">{{ number_format($stats['success_rate'], 1) }}%</p>
            <div class="mt-2 lg:mt-3 w-full h-1.5 bg-neutral-800 rounded-full overflow-hidden">
                <div class="h-full bg-gradient-to-r from-blue-500 to-cyan-400 rounded-full progress-bar" style="--fill: {{ $stats['success_rate'] }}%;"></div>
            </div>
        </div>

    </div>

    {{-- ===== QUICK ACTIONS: 3-col on mobile, hidden on desktop (sidebar handles nav) ===== --}}
    <div class="lg:hidden">
        <p class="text-[0.65rem] font-bold tracking-[0.12em] uppercase text-[#52525b] mb-2.5 px-0.5">Aksi Cepat</p>
        <div class="grid grid-cols-3 gap-2.5">
            <a href="{{ route('kreator.submissions.create') }}" class="bg-[#111111] border border-[#1f1f1f] rounded-2xl transition-all duration-150 hover:bg-[#161616] active:scale-[0.94] active:bg-[#1a1a1a] flex flex-col items-center justify-center gap-2 p-3.5 text-center">
                <div class="w-10 h-10 rounded-xl bg-violet-500/15 flex items-center justify-center">
                    <i data-lucide="upload" class="w-5 h-5 text-violet-400"></i>
                </div>
                <span class="text-[10px] font-bold text-slate-300 leading-tight">Submit<br>Views</span>
            </a>
            <a href="{{ route('kreator.campaigns') }}" class="bg-[#111111] border border-[#1f1f1f] rounded-2xl transition-all duration-150 hover:bg-[#161616] active:scale-[0.94] active:bg-[#1a1a1a] flex flex-col items-center justify-center gap-2 p-3.5 text-center">
                <div class="w-10 h-10 rounded-xl bg-fuchsia-500/15 flex items-center justify-center">
                    <i data-lucide="shopping-bag" class="w-5 h-5 text-fuchsia-400"></i>
                </div>
                <span class="text-[10px] font-bold text-slate-300 leading-tight">Cari<br>Campaign</span>
            </a>
            <a href="{{ route('kreator.ai_tools') }}" class="bg-[#111111] border border-[#1f1f1f] rounded-2xl transition-all duration-150 hover:bg-[#161616] active:scale-[0.94] active:bg-[#1a1a1a] flex flex-col items-center justify-center gap-2 p-3.5 text-center">
                <div class="w-10 h-10 rounded-xl bg-pink-500/15 flex items-center justify-center">
                    <i data-lucide="sparkles" class="w-5 h-5 text-pink-400"></i>
                </div>
                <span class="text-[10px] font-bold text-slate-300 leading-tight">AI<br>Clipper</span>
            </a>
        </div>
    </div>

    {{-- ===== BOTTOM GRID: stacked on mobile → 2-col on desktop ===== --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6">

        {{-- ===== STATUS PEKERJAAN ===== --}}
        <div class="bg-[#111111] ring-1 ring-white/5 rounded-2xl overflow-hidden flex flex-col">

            {{-- Header --}}
            <div class="flex items-center justify-between px-5 pt-5 pb-3">
                <div>
                    <h3 class="text-sm lg:text-base font-bold text-white">Status Pekerjaan</h3>
                    <p class="text-[10px] text-slate-500 mt-0.5">Video kamu yang sedang diproses</p>
                </div>
                <a href="{{ route('kreator.submissions') }}"
                    class="text-[10px] font-bold text-violet-400 hover:text-violet-300 transition-colors flex items-center gap-1">
                    Lihat Semua <i data-lucide="arrow-right" class="w-3 h-3"></i>
                </a>
            </div>

            {{-- Job List (Data dari Controller) --}}

            <div class="px-5 pb-3 space-y-2 flex-1">
                @foreach($jobs as $j)
                @php 
                    $colorMap = [
                        'amber'   => ['bg' => 'bg-amber-500/10',   'text' => 'text-amber-400',   'border' => 'border-amber-500/25'],
                        'emerald' => ['bg' => 'bg-emerald-500/10', 'text' => 'text-emerald-400', 'border' => 'border-emerald-500/25'],
                    ];
                    $c = $colorMap[$j['color']] ?? $colorMap['emerald']; 
                @endphp
                <div class="bg-[#141414] border border-[#1f1f1f] rounded-[0.875rem] transition-colors duration-200 hover:border-[#2f2f36] active:border-[#3f3f46] p-3.5 lg:p-4 flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-neutral-800 flex items-center justify-center flex-shrink-0">
                        <i data-lucide="{{ $j['icon'] }}" class="w-4 h-4 {{ $c['text'] }}"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs lg:text-sm font-bold text-white truncate">{{ $j['campaign'] }}</p>
                        <p class="text-[10px] text-slate-500 mt-0.5">
                            <span class="text-slate-400 font-semibold">{{ $j['views'] }}</span> views diklaim
                        </p>
                    </div>
                    <span class="flex-shrink-0 text-[9px] font-black px-2 py-1 rounded-lg {{ $c['bg'] }} {{ $c['text'] }} border {{ $c['border'] }} uppercase tracking-wide whitespace-nowrap">
                        {{ $j['status'] === 'Approved' ? '✓ OK' : ($j['status'] === 'Empty' ? 'KOSONG' : 'Review') }}
                    </span>
                </div>
                @endforeach
            </div>

            {{-- CTA --}}
            <div class="px-5 pb-5 pt-2">
                <a href="{{ route('kreator.submissions.create') }}"
                    class="w-full py-3 flex items-center justify-center gap-2 bg-violet-600 hover:bg-violet-700 active:bg-violet-800 text-white text-xs lg:text-sm font-bold rounded-xl transition-colors">
                    <i data-lucide="upload" class="w-4 h-4"></i>
                    Submit Screenshot Views Baru
                </a>
            </div>
        </div>

        {{-- ===== CAMPAIGN REKOMENDASI ===== --}}
        <div class="bg-[#111111] ring-1 ring-white/5 rounded-2xl overflow-hidden flex flex-col">

            {{-- Header --}}
            <div class="flex items-center justify-between px-5 pt-5 pb-3">
                <div>
                    <h3 class="text-sm lg:text-base font-bold text-white">Campaign Rekomendasi</h3>
                    <p class="text-[10px] text-slate-500 mt-0.5">Berdasarkan performa kamu</p>
                </div>
                <a href="{{ route('kreator.campaigns') }}"
                    class="text-[10px] font-bold text-violet-400 hover:text-violet-300 transition-colors flex items-center gap-1">
                    Marketplace <i data-lucide="arrow-right" class="w-3 h-3"></i>
                </a>
            </div>

            {{-- Data Recs dipass dari Controller --}}

            <div class="px-5 pb-4 space-y-3 flex-1">
                @foreach($recs as $r)
                <a href="{{ route('kreator.campaigns') }}" class="block p-4 lg:p-5 bg-[#111111] border border-[#1f1f1f] rounded-2xl transition-all duration-200 hover:border-violet-600/30 active:bg-[#161616] overflow-hidden relative">
                    {{-- Gradient overlay via inline style --}}
                    <div class="absolute inset-0 pointer-events-none rounded-[inherit]"
                         style="background: {{ $r['bgAlpha'] }};"></div>

                    <div class="relative flex items-center gap-3">
                        {{-- Brand Icon: colored bg + dot --}}
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0"
                             style="background: {{ $r['iconBg'] }};">
                            <span class="w-3 h-3 rounded-full block"
                                  style="background: {{ $r['dotColor'] }}; box-shadow: 0 0 8px {{ $r['dotColor'] }}80;"></span>
                        </div>

                        {{-- Info --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-0.5">
                                <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest truncate">{{ $r['brand'] }}</p>
                                <span class="text-[9px] font-bold text-violet-400 bg-violet-500/10 border border-violet-500/20 px-1.5 py-0.5 rounded-full flex-shrink-0">{{ $r['tag'] }}</span>
                            </div>
                            <h4 class="text-sm font-bold text-white truncate">{{ $r['title'] }}</h4>
                            <p class="text-xs font-semibold text-emerald-400 mt-1">{{ $r['rate'] }}</p>
                        </div>

                        {{-- Arrow --}}
                        <div class="w-7 h-7 rounded-full bg-violet-500/10 border border-violet-500/20 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="arrow-right" class="w-3.5 h-3.5 text-violet-400"></i>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>

            {{-- CTA --}}
            <div class="px-5 pb-5 pt-2">
                <a href="{{ route('kreator.campaigns') }}"
                    class="w-full py-3 flex items-center justify-center gap-2 bg-neutral-800 hover:bg-neutral-700 text-white text-xs lg:text-sm font-bold rounded-xl transition-colors border border-neutral-700">
                    <i data-lucide="shopping-bag" class="w-4 h-4 text-fuchsia-400"></i>
                    Jelajahi Semua Campaign
                </a>
            </div>
        </div>

    </div>

</div>
@endsection



