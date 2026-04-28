@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard Overview')
@section('page_subtitle', 'Ringkasan performa platform Clipfluence hari ini')

@section('content')
<div class="space-y-6">

    {{-- ===== KPI CARDS ===== --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">

        <div class="bg-neutral-900/60 border border-neutral-800 rounded-2xl p-5">
            <p class="text-2xl font-bold text-white">Rp 12.4M</p>
            <p class="text-xs text-slate-500">Pendapatan Platform</p>
        </div>

        <div class="bg-neutral-900/60 border border-neutral-800 rounded-2xl p-5">
            <p class="text-2xl font-bold text-white">Rp 148.5M</p>
            <p class="text-xs text-slate-500">Total Escrow</p>
        </div>

        <div class="bg-neutral-900/60 border border-neutral-800 rounded-2xl p-5">
            <p class="text-2xl font-bold text-white">86</p>
            <p class="text-xs text-slate-500">Campaign Aktif</p>
        </div>

        <div class="bg-neutral-900/60 border border-red-500/30 rounded-2xl p-5">
            <p class="text-2xl font-bold text-white">24</p>
            <p class="text-xs text-slate-500">Butuh Action</p>
        </div>

    </div>

    {{-- ===== ACTION QUEUE ===== --}}
    <div class="bg-neutral-900/60 border border-neutral-800 rounded-2xl p-6">
        <h3 class="text-lg font-bold text-white mb-4">Antrean Tugas</h3>

        <div class="space-y-3">

            <div class="p-4 bg-neutral-800/30 rounded-xl">
                <p class="text-white font-semibold">Moderasi Video UGC</p>
                <p class="text-xs text-slate-500">12 video menunggu review</p>
            </div>

            <div class="p-4 bg-neutral-800/30 rounded-xl">
                <p class="text-white font-semibold">Verifikasi KYC</p>
                <p class="text-xs text-slate-500">5 user perlu validasi</p>
            </div>

            <div class="p-4 bg-neutral-800/30 rounded-xl">
                <p class="text-white font-semibold">Withdrawal</p>
                <p class="text-xs text-slate-500">7 request pending</p>
            </div>

        </div>
    </div>

    {{-- ===== CHART SIMULASI ===== --}}
    <div class="bg-neutral-900/60 border border-neutral-800 rounded-2xl p-6">
        <h3 class="text-lg font-bold text-white mb-4">Kinerja Keuangan</h3>

        <div class="flex items-end gap-2 h-48">
            @php
                $data = [40, 60, 30, 80, 55, 70, 90, 50];
            @endphp

            @foreach($data as $d)
                <div class="w-6 bg-emerald-500/40 rounded-t" style="height: {{ $d }}%"></div>
            @endforeach
        </div>
    </div>

    {{-- ===== LOG ACTIVITY ===== --}}
    <div class="bg-neutral-900/60 border border-neutral-800 rounded-2xl p-6">
        <h3 class="text-lg font-bold text-white mb-4">Aktivitas Terbaru</h3>

        @php
            $logs = [
                'Escrow dibayarkan ke kreator',
                'Brand baru terdaftar',
                'Dispute dibuka user',
                'Backup sistem selesai'
            ];
        @endphp

        <div class="space-y-3">
            @foreach($logs as $log)
                <div class="text-sm text-slate-300 p-3 bg-neutral-800/20 rounded-lg">
                    {{ $log }}
                </div>
            @endforeach
        </div>
    </div>

</div>
@endsection