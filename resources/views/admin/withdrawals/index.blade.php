@extends('layouts.admin')
@section('title', 'Penarikan Dana')
@section('page_title', 'Penarikan Dana')
@section('page_subtitle', 'Approve atau tolak permintaan penarikan saldo kreator')

@section('content')
<div class="space-y-5">
    @if(session('success'))
        <div class="rounded-xl border border-emerald-500/20 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-300">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="rounded-xl border border-red-500/20 bg-red-500/10 px-4 py-3 text-sm text-red-300">{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="rounded-xl border border-red-500/20 bg-red-500/10 px-4 py-3 text-sm text-red-300">{{ $errors->first() }}</div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @foreach([
            ['label'=>'Menunggu Approval','val'=>$stats['pending'],'icon'=>'clock','color'=>'amber'],
            ['label'=>'Total Dicairkan','val'=>'Rp ' . number_format((int) $stats['completed_amount'], 0, ',', '.'),'icon'=>'banknote','color'=>'emerald'],
            ['label'=>'Rata-rata Penarikan','val'=>'Rp ' . number_format((int) $stats['average'], 0, ',', '.'),'icon'=>'calculator','color'=>'brand'],
        ] as $s)
        <div class="stat-card">
            <div class="flex w-9 h-9 rounded-xl bg-{{ $s['color'] }}/10 border border-{{ $s['color'] }}/20 items-center justify-center mb-3">
                <i data-lucide="{{ $s['icon'] }}" class="w-4 h-4 text-{{ $s['color']==='brand'?'brand':$s['color'].'-400' }}"></i>
            </div>
            <p class="text-xl font-bold text-white">{{ $s['val'] }}</p>
            <p class="text-xs text-slate-500">{{ $s['label'] }}</p>
        </div>
        @endforeach
    </div>

    <div class="bg-neutral-900/60 border border-neutral-800/60 rounded-2xl overflow-hidden">
        <div class="p-5 border-b border-neutral-800/60"><h3 class="text-sm font-semibold text-white">Permintaan Penarikan</h3></div>
        <div class="divide-y divide-neutral-800/40">
            @forelse($withdrawals as $withdrawal)
            @php
                $statusClass = match($withdrawal->status) {
                    'completed' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                    'rejected' => 'bg-red-500/10 text-red-400 border-red-500/20',
                    default => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                };
            @endphp
            <div class="flex flex-col gap-4 px-5 py-4 hover:bg-white/[2%] transition-colors lg:flex-row lg:items-center">
                <div class="flex items-center gap-4 flex-1 min-w-0">
                    <div class="w-10 h-10 rounded-full bg-emerald-500/20 flex items-center justify-center text-sm font-bold text-emerald-400 flex-shrink-0">
                        {{ strtoupper(substr($withdrawal->user?->name ?? 'K', 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-white truncate">{{ $withdrawal->user?->name ?: $withdrawal->account_name }}</p>
                        <p class="text-xs text-slate-500">{{ $withdrawal->bank_name }} · {{ $withdrawal->bank_account }} · {{ $withdrawal->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                <p class="text-sm font-bold text-white">Rp {{ number_format((int) $withdrawal->amount, 0, ',', '.') }}</p>
                <span class="w-max text-xs font-semibold px-2.5 py-1 rounded-full border {{ $statusClass }}">{{ ucfirst($withdrawal->status) }}</span>
                @if($withdrawal->status === 'pending')
                <div class="flex gap-1.5">
                    <form method="POST" action="{{ route('admin.withdrawals.update', $withdrawal) }}">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="completed">
                        <button class="flex items-center gap-1 px-3 py-1.5 text-xs font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 rounded-xl hover:bg-emerald-500/20 transition-colors">
                            <i data-lucide="check" class="w-3 h-3"></i> Cairkan
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.withdrawals.update', $withdrawal) }}">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="rejected">
                        <button class="flex items-center gap-1 px-3 py-1.5 text-xs font-medium bg-red-500/10 text-red-400 border border-red-500/20 rounded-xl hover:bg-red-500/20 transition-colors">
                            <i data-lucide="x" class="w-3 h-3"></i> Tolak
                        </button>
                    </form>
                </div>
                @endif
            </div>
            @empty
            <div class="px-5 py-8 text-center text-sm text-slate-500">Belum ada permintaan penarikan.</div>
            @endforelse
        </div>
        <div class="px-5 py-3.5 border-t border-neutral-800/60">{{ $withdrawals->links() }}</div>
    </div>
</div>
@endsection
