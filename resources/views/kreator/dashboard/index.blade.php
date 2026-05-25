@extends('layouts.kreator')

@section('title', 'Dasbor')

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
        <div class="bg-[#111111] border border-[#1f1f1f] rounded-[1.25rem] transition-all duration-200 active:scale-[0.97] p-4 lg:p-5 flex flex-col justify-between h-full cursor-pointer">
            <div class="flex items-center gap-3 mb-3 lg:mb-4">
                <div class="w-9 h-9 lg:w-11 lg:h-11 rounded-xl  flex items-center justify-center shrink-0">
                    <span class="text-xl lg:text-2xl drop-shadow-[0_2px_4px_rgba(139,92,246,0.3)]">💰</span>
                </div>
                <p class="text-[10px] lg:text-[11px] text-slate-400 font-semibold uppercase tracking-wider">Saldo Tersedia</p>
            </div>
            
            <div class="mt-auto">
                <p class="text-lg lg:text-2xl font-black text-white leading-none">
                    Rp <span data-count="{{ $stats['saldo_tersedia'] }}">{{ number_format($stats['saldo_tersedia'], 0, ',', '.') }}</span>
                </p>
                <a href="{{ route('kreator.finance') }}"
                    class="mt-2 lg:mt-3 inline-flex items-center gap-1 text-[10px] lg:text-xs font-bold text-violet-400 hover:text-violet-300 transition-colors">
                    Tarik Dana <i data-lucide="arrow-right" class="w-3 h-3"></i>
                </a>
            </div>
        </div>

        {{-- Saldo Tertahan --}}
        <div class="bg-[#111111] border border-[#1f1f1f] rounded-[1.25rem] transition-all duration-200 active:scale-[0.97] p-4 lg:p-5 flex flex-col justify-between h-full cursor-pointer">
            <div class="flex items-center gap-3 mb-3 lg:mb-4">
                <div class="w-9 h-9 lg:w-11 lg:h-11 rounded-xl  flex items-center justify-center shrink-0">
                    <span class="text-xl lg:text-2xl drop-shadow-[0_2px_4px_rgba(245,158,11,0.3)]">⏳</span>
                </div>
                <p class="text-[10px] lg:text-[11px] text-slate-400 font-semibold uppercase tracking-wider">Dalam Review</p>
            </div>
            
            <div class="mt-auto">
                <p class="text-lg lg:text-2xl font-black text-white leading-none">
                    Rp <span data-count="{{ $stats['dalam_review'] }}">{{ number_format($stats['dalam_review'], 0, ',', '.') }}</span>
                </p>
                <p class="mt-2 lg:mt-3 text-[10px] lg:text-xs text-slate-600 leading-tight">Menunggu konfirmasi brand</p>
            </div>
        </div>

        {{-- Total Tayangan --}}
        <div class="bg-[#111111] border border-[#1f1f1f] rounded-[1.25rem] transition-all duration-200 active:scale-[0.97] p-4 lg:p-5 flex flex-col justify-between h-full cursor-pointer">
            <div class="flex items-start justify-between mb-3 lg:mb-4 w-full">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 lg:w-11 lg:h-11 rounded-xl  flex items-center justify-center shrink-0">
                        <span class="text-xl lg:text-2xl drop-shadow-[0_2px_4px_rgba(16,185,129,0.3)]">🔥</span>
                    </div>
                    <p class="text-[10px] lg:text-[11px] text-slate-400 font-semibold uppercase tracking-wider leading-tight">Total Tayangan</p>
                </div>
                <span class="text-[9px] font-bold text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-1.5 py-0.5 rounded-full shrink-0">
                    Top 10%
                </span>
            </div>
            
            <div class="mt-auto">
                <p class="text-lg lg:text-2xl font-black text-white leading-none">
                    <span data-count="{{ $stats['total_views'] }}">{{ number_format($stats['total_views'], 0, ',', '.') }}</span>
                </p>
                <p class="mt-2 lg:mt-3 text-[10px] lg:text-xs text-slate-600">dari {{ $stats['videos_approved'] }} video disetujui</p>
            </div>
        </div>

        {{-- Berhasil Rate --}}
        <div class="bg-[#111111] border border-[#1f1f1f] rounded-[1.25rem] transition-all duration-200 active:scale-[0.97] p-4 lg:p-5 flex flex-col justify-between h-full cursor-pointer">
            <div class="flex items-center gap-3 mb-3 lg:mb-4">
                <div class="w-9 h-9 lg:w-11 lg:h-11 rounded-xl  flex items-center justify-center shrink-0">
                    <span class="text-xl lg:text-2xl drop-shadow-[0_2px_4px_rgba(59,130,246,0.3)]">🎯</span>
                </div>
                <p class="text-[10px] lg:text-[11px] text-slate-400 font-semibold uppercase tracking-wider">Berhasil Rate</p>
            </div>
            
            <div class="mt-auto">
                <p class="text-lg lg:text-2xl font-black text-white leading-none">{{ number_format($stats['success_rate'], 1) }}%</p>
                <div class="mt-2 lg:mt-3 w-full h-1.5 bg-neutral-800 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-blue-500 to-cyan-400 rounded-full progress-bar" style="--fill: {{ $stats['success_rate'] }}%;"></div>
                </div>
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
                <span class="text-[10px] font-bold text-slate-300 leading-tight">Kirim<br>Tayangan</span>
            </a>
            <a href="{{ route('kreator.campaigns') }}" class="bg-[#111111] border border-[#1f1f1f] rounded-2xl transition-all duration-150 hover:bg-[#161616] active:scale-[0.94] active:bg-[#1a1a1a] flex flex-col items-center justify-center gap-2 p-3.5 text-center">
                <div class="w-10 h-10 rounded-xl bg-fuchsia-500/15 flex items-center justify-center">
                    <i data-lucide="shopping-bag" class="w-5 h-5 text-fuchsia-400"></i>
                </div>
                <span class="text-[10px] font-bold text-slate-300 leading-tight">Cari<br>Kampanye</span>
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
        <div class="bg-[#0f0f0f] border border-white/[0.08] rounded-xl overflow-hidden flex flex-col">

            {{-- Header --}}
            <div class="flex items-center justify-between px-5 py-4 border-b border-white/[0.06]">
                <div>
                    <h3 class="text-sm font-semibold text-white">Status Pekerjaan</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Video yang sedang diproses</p>
                </div>
                <a href="{{ route('kreator.submissions') }}"
                    class="text-xs font-medium text-slate-400 hover:text-white transition-colors flex items-center gap-1">
                    Lihat Semua <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                </a>
            </div>

            {{-- Job List --}}
            <div class="px-5 py-4 space-y-2.5 flex-1">
                @forelse($jobs as $j)
                @php 
                    $colorMap = [
                        'amber'   => ['bg' => 'bg-amber-500/10',   'text' => 'text-amber-400',   'border' => 'border-amber-500/20'],
                        'emerald' => ['bg' => 'bg-emerald-500/10', 'text' => 'text-emerald-400', 'border' => 'border-emerald-500/20'],
                    ];
                    $c = $colorMap[$j['color']] ?? $colorMap['emerald']; 
                @endphp
                <div class="bg-black/20 border border-white/[0.06] rounded-lg hover:border-white/[0.12] transition-all p-3.5 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-white/[0.03] flex items-center justify-center flex-shrink-0">
                        <i data-lucide="{{ $j['icon'] }}" class="w-4 h-4 {{ $c['text'] }}"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-white truncate">{{ $j['campaign'] }}</p>
                        <p class="text-xs text-slate-500 mt-0.5">
                            <span class="text-slate-400">{{ $j['views'] }}</span> views diklaim
                        </p>
                    </div>
                    <span class="flex-shrink-0 text-[10px] font-medium px-2.5 py-1 rounded-md {{ $c['bg'] }} {{ $c['text'] }} border {{ $c['border'] }} uppercase tracking-wide">
                        {{ $j['status'] === 'Disetujui' ? 'Disetujui' : ($j['status'] === 'Empty' ? 'Kosong' : 'Review') }}
                    </span>
                </div>
                @empty
                <div class="text-center py-12">
                    <div class="w-14 h-14 mx-auto mb-3 rounded-lg bg-white/[0.03] flex items-center justify-center">
                        <i data-lucide="inbox" class="w-6 h-6 text-slate-600"></i>
                    </div>
                    <p class="text-sm font-medium text-slate-500">Belum ada pekerjaan</p>
                    <p class="text-xs text-slate-600 mt-1">Kirim screenshot views untuk mulai</p>
                </div>
                @endforelse
            </div>

            {{-- CTA --}}
            <div class="px-5 pb-5 pt-2">
                <a href="{{ route('kreator.submissions.create') }}"
                    class="w-full py-3 flex items-center justify-center gap-2 bg-white text-black text-sm font-medium rounded-lg hover:bg-white/90 transition-colors">
                    <i data-lucide="upload" class="w-4 h-4"></i>
                    Kirim Screenshot Tayangan
                </a>
            </div>
        </div>

        {{-- ===== CAMPAIGN REKOMENDASI ===== --}}
        <div class="bg-[#0f0f0f] border border-white/[0.08] rounded-xl overflow-hidden flex flex-col">

            {{-- Header --}}
            <div class="flex items-center justify-between px-5 py-4 border-b border-white/[0.06]">
                <div>
                    <h3 class="text-sm font-semibold text-white">Kampanye Rekomendasi</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Berdasarkan performa kamu</p>
                </div>
                <a href="{{ route('kreator.campaigns') }}"
                    class="text-xs font-medium text-slate-400 hover:text-white transition-colors flex items-center gap-1">
                    Marketplace <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                </a>
            </div>

            {{-- Kampanye List --}}
            <div class="px-5 py-4 space-y-2.5 flex-1">
                @forelse($recs as $r)
                <a href="{{ route('kreator.campaigns') }}" class="block p-3.5 bg-black/20 border border-white/[0.06] rounded-lg hover:border-white/[0.12] transition-all">
                    <div class="flex items-center gap-3.5">
                        {{-- Kampanye Image --}}
                        <div class="w-16 h-16 rounded-lg overflow-hidden flex-shrink-0 bg-white/[0.03]">
                            @if(isset($r['thumbnail']) && $r['thumbnail'])
                                <img src="{{ asset($r['thumbnail']) }}" 
                                     alt="{{ $r['title'] }}" 
                                     class="w-full h-full object-cover"
                                     onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'w-full h-full flex items-center justify-center\' style=\'background: {{ $r['iconBg'] }}\'><span class=\'w-3 h-3 rounded-full block\' style=\'background: {{ $r['dotColor'] }}\'></span></div>';">
                            @else
                                <div class="w-full h-full flex items-center justify-center"
                                     style="background: {{ $r['iconBg'] }};">
                                    <span class="w-3 h-3 rounded-full block"
                                          style="background: {{ $r['dotColor'] }};"></span>
                                </div>
                            @endif
                        </div>

                        {{-- Info --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <p class="text-[10px] font-medium text-slate-500 uppercase tracking-wider truncate">{{ $r['brand'] }}</p>
                                <span class="text-[9px] font-medium text-slate-400 bg-white/[0.05] border border-white/[0.08] px-2 py-0.5 rounded-md flex-shrink-0">{{ $r['tag'] }}</span>
                            </div>
                            <h4 class="text-sm font-medium text-white truncate mb-1">{{ $r['title'] }}</h4>
                            <p class="text-xs font-medium text-emerald-400">{{ $r['rate'] }}</p>
                        </div>

                        {{-- Arrow --}}
                        <div class="w-8 h-8 rounded-lg bg-white/[0.03] flex items-center justify-center flex-shrink-0">
                            <i data-lucide="arrow-right" class="w-4 h-4 text-slate-400"></i>
                        </div>
                    </div>
                </a>
                @empty
                <div class="text-center py-12">
                    <div class="w-14 h-14 mx-auto mb-3 rounded-lg bg-white/[0.03] flex items-center justify-center">
                        <i data-lucide="search" class="w-6 h-6 text-slate-600"></i>
                    </div>
                    <p class="text-sm font-medium text-slate-500">Belum ada rekomendasi</p>
                    <p class="text-xs text-slate-600 mt-1">Jelajahi campaign di marketplace</p>
                </div>
                @endforelse
            </div>

            {{-- CTA --}}
            <div class="px-5 pb-5 pt-2">
                <a href="{{ route('kreator.campaigns') }}"
                    class="w-full py-3 flex items-center justify-center gap-2 bg-white/[0.05] hover:bg-white/[0.08] text-white text-sm font-medium rounded-lg transition-colors border border-white/[0.08]">
                    <i data-lucide="shopping-bag" class="w-4 h-4"></i>
                    Jelajahi Kampanye
                </a>
            </div>
        </div>

    </div>

</div>
@endsection



