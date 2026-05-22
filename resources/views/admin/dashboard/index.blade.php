@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard Overview')
@section('page_subtitle', 'Ringkasan performa platform Clipfluence hari ini')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">

    

    {{-- ===== KPIS / STAT CARDS ===== --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">

        {{-- Card 1: Revenue --}}
        <div class="bg-neutral-900/60 border border-neutral-800/60 rounded-2xl p-5 hover:bg-neutral-800/40 transition-colors">
            <div class="flex items-start justify-between mb-4">
                <div class="w-10 h-10 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center">
                    <i data-lucide="dollar-sign" class="w-5 h-5 text-emerald-400"></i>
                </div>
                <span class="flex items-center gap-1 text-xs font-semibold text-emerald-400 bg-emerald-500/10 px-2 py-1 rounded-full">
                    <i data-lucide="trending-up" class="w-3 h-3"></i> +12%
                </span>
            </div>
            <p class="text-2xl font-bold text-white mb-0.5">Rp 12.4M</p>
            <p class="text-xs text-slate-500">Pendapatan Platform (Bulan ini)</p>
        </div>

        {{-- Card 2: Escrow --}}
        <div class="bg-neutral-900/60 border border-neutral-800/60 rounded-2xl p-5 hover:bg-neutral-800/40 transition-colors">
            <div class="flex items-start justify-between mb-4">
                <div class="w-10 h-10 rounded-xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center">
                    <i data-lucide="lock" class="w-5 h-5 text-amber-400"></i>
                </div>
                <span class="flex items-center gap-1 text-xs font-semibold text-emerald-400 bg-emerald-500/10 px-2 py-1 rounded-full">
                    <i data-lucide="trending-up" class="w-3 h-3"></i> +5%
                </span>
            </div>
            <p class="text-2xl font-bold text-white mb-0.5">Rp 148.5M</p>
            <p class="text-xs text-slate-500">Total Escrow Ditahan</p>
        </div>

        {{-- Card 3: Campaigns --}}
        <div class="bg-neutral-900/60 border border-neutral-800/60 rounded-2xl p-5 hover:bg-neutral-800/40 transition-colors">
            <div class="flex items-start justify-between mb-4">
                <div class="w-10 h-10 rounded-xl bg-brand/10 border border-brand/20 flex items-center justify-center">
                    <i data-lucide="megaphone" class="w-5 h-5 text-brand"></i>
                </div>
                <span class="text-xs font-medium text-slate-400 bg-neutral-800 px-2 py-1 rounded-full">
                    12 Baru
                </span>
            </div>
            <p class="text-2xl font-bold text-white mb-0.5">86</p>
            <p class="text-xs text-slate-500">Campaign Berjalan</p>
        </div>

        {{-- Card 4: Action Needed --}}
        <div class="bg-neutral-900/60 border border-red-500/30 rounded-2xl p-5 hover:bg-neutral-800/40 transition-colors relative overflow-hidden">
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-red-500/5 rounded-full blur-xl pointer-events-none"></div>
            <div class="flex items-start justify-between mb-4 relative z-10">
                <div class="w-10 h-10 rounded-xl bg-red-500/10 border border-red-500/20 flex items-center justify-center shadow-[0_0_15px_rgba(239,68,68,0.2)]">
                    <i data-lucide="alert-circle" class="w-5 h-5 text-red-500"></i>
                </div>
                <span class="flex items-center gap-1 text-xs font-semibold text-red-400 bg-red-500/10 px-2 py-1 rounded-full animate-pulse">
                    Urgent
                </span>
            </div>
            <p class="text-2xl font-bold text-white mb-0.5 relative z-10">24 Menunggu</p>
            <p class="text-xs text-slate-500 relative z-10">Total Tindakan Admin</p>
        </div>

    </div>

    {{-- ===== MAIN GRID ===== --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- 1. TUGAS MENUNGGU / ACTION QUEUE --}}
        <div class="bg-neutral-900/60 border border-neutral-800/60 rounded-2xl p-6 flex flex-col xl:col-span-1">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="text-lg font-bold text-white">Antrean Tugas</h3>
                    <p class="text-xs text-slate-400 mt-1">Butuh persetujuan manual</p>
                </div>
            </div>

            <div class="space-y-3 flex-1">
                {{-- UGC Task --}}
                <div class="group bg-neutral-800/30 border border-neutral-800 rounded-xl p-4 hover:border-brand/40 transition-all cursor-pointer">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-red-500 shadow-[0_0_5px_rgba(239,68,68,0.6)]"></span>
                            <span class="text-sm font-semibold text-white">Moderasi Video UGC</span>
                        </div>
                        <span class="text-xs font-bold text-white bg-red-500 rounded-full px-2 py-0.5">12</span>
                    </div>
                    <p class="text-xs text-slate-500 mb-3">Video di-submit & menunggu ditinjau sebelum masuk ke Brand.</p>
                    <a href="{{ route('admin.ugc') }}" class="text-xs font-medium text-brand hover:text-brand-light flex items-center gap-1 w-max">
                        Tinjau Sekarang <i data-lucide="arrow-right" class="w-3 h-3"></i>
                    </a>
                </div>

                {{-- KYC Task --}}
                <div class="group bg-neutral-800/30 border border-neutral-800 rounded-xl p-4 hover:border-amber-500/40 transition-all cursor-pointer">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-amber-500 shadow-[0_0_5px_rgba(245,158,11,0.6)]"></span>
                            <span class="text-sm font-semibold text-white">Verifikasi KYC</span>
                        </div>
                        <span class="text-xs font-bold text-amber-500 bg-amber-500/10 border border-amber-500/20 rounded-full px-2 py-0.5">5</span>
                    </div>
                    <p class="text-xs text-slate-500 mb-3">KTP dan Selfie kreator baru perlu divalidasi manual.</p>
                    <a href="{{ route('admin.kyc') }}" class="text-xs font-medium text-amber-500 hover:text-amber-400 flex items-center gap-1 w-max">
                        Validasi Kreator <i data-lucide="arrow-right" class="w-3 h-3"></i>
                    </a>
                </div>

                {{-- Withdrawal Task --}}
                <div class="group bg-neutral-800/30 border border-neutral-800 rounded-xl p-4 hover:border-emerald-500/40 transition-all cursor-pointer">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_5px_rgba(16,185,129,0.6)]"></span>
                            <span class="text-sm font-semibold text-white">Penarikan Dana</span>
                        </div>
                        <span class="text-xs font-bold text-emerald-500 bg-emerald-500/10 border border-emerald-500/20 rounded-full px-2 py-0.5">7</span>
                    </div>
                    <p class="text-xs text-slate-500 mb-3">Permintaan payout dari wallet kreator ke rekening Bank.</p>
                    <a href="{{ route('admin.withdrawals') }}" class="text-xs font-medium text-emerald-500 hover:text-emerald-400 flex items-center gap-1 w-max">
                        Proses Transfer <i data-lucide="arrow-right" class="w-3 h-3"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- 2. CHART GRAFIK PENDAPATAN & ESCROW --}}
        <div class="bg-neutral-900/60 border border-neutral-800/60 rounded-2xl p-6 xl:col-span-2 flex flex-col">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-bold text-white">Kinerja Keuangan</h3>
                    <p class="text-xs text-slate-400 mt-1">Pergerakan Escrow VS Platform Fee (30 hari)</p>
                </div>
                <select class="bg-neutral-800 border border-neutral-700 text-slate-300 text-xs rounded-lg px-3 py-1.5 focus:outline-none focus:border-brand">
                    <option>Bulan Ini</option>
                    <option>Bulan Lalu</option>
                    <option>Tahun Ini</option>
                </select>
            </div>

            {{-- Placeholder bar chart --}}
            <div class="flex-1 flex items-end justify-between gap-2 h-48 px-1 mt-4">
                @php
                    $escrow = [40, 65, 45, 80, 55, 90, 70, 85, 60, 95, 75, 88, 50, 72, 93];
                    $fee = [10, 20, 15, 25, 18, 28, 22, 26, 19, 30, 24, 27, 16, 23, 29];
                @endphp
                @foreach($escrow as $i => $h)
                <div class="flex-1 flex flex-col items-center justify-end h-full gap-1 group relative">
                    {{-- Tooltip hover --}}
                    <div class="absolute -top-10 scale-0 group-hover:scale-100 transition-transform bg-neutral-800 border border-neutral-700 rounded-md px-2 py-1 text-[10px] whitespace-nowrap z-10 shadow-xl opacity-0 group-hover:opacity-100">
                        <span class="text-emerald-400 block">Escrow: Rp{{ $h }} Jt</span>
                        <span class="text-brand block">Fee: Rp{{ $fee[$i] }} Jt</span>
                    </div>

                    {{-- Fee Bar --}}
                    <div class="w-full max-w-[16px] xl:max-w-[24px] rounded-t-sm bg-brand/80 transition-all duration-300 hover:bg-brand" style="height: {{ $fee[$i] }}%;"></div>
                    {{-- Escrow Bar --}}
                    <div class="w-full max-w-[16px] xl:max-w-[24px] rounded-t-sm bg-emerald-500/20 transition-all duration-300 hover:bg-emerald-500/40" style="height: {{ $h }}%; margin-top: -{{ $h }}%; z-index: -1;"></div>
                </div>
                @endforeach
            </div>

            <div class="flex justify-between text-[10px] text-slate-500 mt-4 px-2 font-medium">
                <span>1 Mar</span><span>5 Mar</span><span>10 Mar</span><span>15 Mar</span><span>20 Mar</span><span>25 Mar</span><span>30 Mar</span>
            </div>
        </div>
    </div>

    {{-- ===== BOTTOM GRID: Activity & Top Campaigns ===== --}}
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

        {{-- Log Aktivitas Terbaru --}}
        <div class="bg-neutral-900/60 border border-neutral-800/60 rounded-2xl p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-bold text-white">Log Aktivitas Terbaru</h3>
                    <p class="text-xs text-slate-400 mt-1">Transaksi & Notifikasi sistem</p>
                </div>
                <a href="{{ route('admin.logs') }}" class="text-xs text-brand hover:text-brand-light font-medium transition-colors">Lihat Semua →</a>
            </div>

            <div class="space-y-4">
                @php
                    $logs = [
                        ['type' => 'finance', 'icon' => 'banknote', 'color' => 'emerald', 'title' => 'Pembayaran Escrow Selesai', 'desc' => 'Campaign Tokopedia 12.12 telah dirilis ke 15 kreator.', 'time' => '10 menit yang lalu'],
                        ['type' => 'user', 'icon' => 'user-plus', 'color' => 'brand', 'title' => 'Brand Baru Terdaftar', 'desc' => 'Wardah Beauty ID (wardah@brand.com) baru mendaftar.', 'time' => '1 jam yang lalu'],
                        ['type' => 'alert', 'icon' => 'alert-triangle', 'color' => 'red', 'title' => 'Dispute Dibuka', 'desc' => 'Kreator @dimasviral membuka komplain untuk campaign Indomie.', 'time' => '2 jam yang lalu'],
                        ['type' => 'system', 'icon' => 'cpu', 'color' => 'slate', 'title' => 'Backup Database Selesai', 'desc' => 'Backup harian otomatis berhasil pada 03:00 WIB.', 'time' => 'Hari ini, 03:00'],
                    ];
                @endphp
                @foreach($logs as $log)
                <div class="flex gap-4">
                    <div class="w-9 h-9 rounded-full bg-{{ $log['color'] === 'brand' ? 'brand/10' : ($log['color'] === 'emerald' ? 'emerald-500/10' : ($log['color'] === 'red' ? 'red-500/10' : 'slate-500/10')) }} flex items-center justify-center flex-shrink-0 mt-0.5 border border-{{ $log['color'] === 'brand' ? 'brand/20' : ($log['color'] === 'emerald' ? 'emerald-500/20' : ($log['color'] === 'red' ? 'red-500/20' : 'slate-500/20')) }}">
                        <i data-lucide="{{ $log['icon'] }}" class="w-4 h-4 text-{{ $log['color'] === 'brand' ? 'brand' : ($log['color'] === 'emerald' ? 'emerald-400' : ($log['color'] === 'red' ? 'red-400' : 'slate-400')) }}"></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-slate-200">{{ $log['title'] }}</p>
                        <p class="text-xs text-slate-400 mt-0.5">{{ $log['desc'] }}</p>
                        <p class="text-[10px] text-slate-500 mt-1.5 font-medium">{{ $log['time'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Top Campaign --}}
        <div class="bg-neutral-900/60 border border-neutral-800/60 rounded-2xl p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-bold text-white">Campaign Paling Aktif</h3>
                    <p class="text-xs text-slate-400 mt-1">Berdasarkan volume submission UGC</p>
                </div>
                <a href="{{ route('admin.campaigns') }}" class="text-xs text-brand hover:text-brand-light font-medium transition-colors">Kelola →</a>
            </div>

            <div class="space-y-3">
                @php
                    $campaigns = [
                        ['brand' => 'Wardah Beauty', 'title' => 'Skincare Routine Challenge', 'progress' => 85, 'kreators' => 48, 'color' => 'brand'],
                        ['brand' => 'Tokopedia', 'title' => 'Promo Bebas Ongkir', 'progress' => 45, 'kreators' => 102, 'color' => 'emerald'],
                        ['brand' => 'Indomie', 'title' => 'Kreasi Indomie Goreng', 'progress' => 92, 'kreators' => 23, 'color' => 'amber'],
                        ['brand' => 'Shopee', 'title' => 'Haul Shopee 12.12', 'progress' => 60, 'kreators' => 77, 'color' => 'violet'],
                    ];
                @endphp
                @foreach($campaigns as $c)
                <div class="bg-neutral-800/30 border border-neutral-800 hover:border-neutral-700 border-l-2 border-l-{{ $c['color'] === 'brand' ? 'brand' : $c['color'].'-500' }} rounded-r-xl p-4 transition-all cursor-pointer">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-3">
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500">{{ $c['brand'] }}</span>
                            <h4 class="text-sm font-semibold text-white mt-0.5">{{ $c['title'] }}</h4>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="flex items-center gap-1 text-xs text-slate-400 bg-neutral-900 px-2 py-1 rounded-md border border-neutral-800">
                                <i data-lucide="users" class="w-3 h-3 text-brand"></i> {{ $c['kreators'] }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between text-xs mb-1.5">
                        <span class="text-slate-400">Penyelesaian Campaign</span>
                        <span class="font-semibold text-slate-300">{{ $c['progress'] }}%</span>
                    </div>
                    <div class="h-1.5 rounded-full bg-neutral-800 overflow-hidden">
                        <div class="h-full rounded-full bg-gradient-to-r {{ $c['color'] === 'brand' ? 'from-brand/50 to-brand' : ($c['color'] === 'emerald' ? 'from-emerald-500/50 to-emerald-400' : ($c['color'] === 'amber' ? 'from-amber-500/50 to-amber-400' : 'from-violet-500/50 to-violet-400')) }} transition-all"
                            style="width: {{ $c['progress'] }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

    </div>

</div>
@endsection
