@extends('layouts.admin')
@section('title', 'Pembayaran & Escrow')
@section('page_title', 'Pembayaran & Escrow')
@section('page_subtitle', 'Validasi transaksi top up dan escrow brand')

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

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach([
            ['label'=>'Total Top Up Berhasil','val'=>'Rp ' . number_format((int) $stats['escrow'], 0, ',', '.'),'icon'=>'vault','color'=>'brand'],
            ['label'=>'Menunggu Validasi','val'=>'Rp ' . number_format((int) $stats['pending_deposit'], 0, ',', '.'),'icon'=>'clock','color'=>'amber'],
            ['label'=>'Total Withdrawal Cair','val'=>'Rp ' . number_format((int) $stats['paid_withdrawal'], 0, ',', '.'),'icon'=>'trending-up','color'=>'emerald'],
            ['label'=>'Total Transaksi','val'=>$stats['transactions'],'icon'=>'receipt','color'=>'violet'],
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
        <div class="flex items-center justify-between p-5 border-b border-neutral-800/60">
            <h3 class="text-sm font-semibold text-white">Riwayat Top Up Brand</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-neutral-800/60">
                        @foreach(['Order ID','Brand','Jumlah','Metode','Tanggal','Status','Aksi'] as $h)
                        <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ $h }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-800/40">
                    @forelse($deposits as $deposit)
                    @php
                        $statusClass = match($deposit->status) {
                            'success' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                            'pending' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                            'expired' => 'bg-blue-500/10 text-blue-400 border-blue-500/20',
                            default => 'bg-red-500/10 text-red-400 border-red-500/20',
                        };
                    @endphp
                    <tr class="hover:bg-white/[2%] transition-colors">
                        <td class="px-5 py-3.5 text-xs font-mono text-slate-400">{{ $deposit->order_id }}</td>
                        <td class="px-5 py-3.5">
                            <p class="text-xs font-semibold text-slate-300">{{ $deposit->user?->company_name ?: $deposit->user?->name ?: '-' }}</p>
                            <p class="text-xs text-slate-500">{{ $deposit->user?->email }}</p>
                        </td>
                        <td class="px-5 py-3.5 text-sm font-semibold text-white">Rp {{ number_format((int) $deposit->amount, 0, ',', '.') }}</td>
                        <td class="px-5 py-3.5"><span class="text-xs px-2 py-0.5 rounded-full bg-neutral-800 text-slate-400 border border-neutral-700">{{ $deposit->payment_type ?: 'manual/pending' }}</span></td>
                        <td class="px-5 py-3.5 text-xs text-slate-400">{{ $deposit->created_at->format('d M Y, H:i') }}</td>
                        <td class="px-5 py-3.5"><span class="text-xs font-semibold px-2.5 py-1 rounded-full border {{ $statusClass }}">{{ ucfirst($deposit->status) }}</span></td>
                        <td class="px-5 py-3.5">
                            <div class="flex flex-wrap gap-1.5">
                                @if($deposit->status === 'pending')
                                <form method="POST" action="{{ route('admin.payouts.update', $deposit) }}">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="success">
                                    <button class="flex items-center gap-1 px-3 py-1.5 text-xs font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 rounded-xl hover:bg-emerald-500/20 transition-colors">
                                        <i data-lucide="check" class="w-3 h-3"></i> Sahkan
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.payouts.update', $deposit) }}">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="failed">
                                    <button class="flex items-center gap-1 px-3 py-1.5 text-xs font-medium bg-red-500/10 text-red-400 border border-red-500/20 rounded-xl hover:bg-red-500/20 transition-colors">
                                        <i data-lucide="x" class="w-3 h-3"></i> Gagalkan
                                    </button>
                                </form>
                                @else
                                <span class="text-xs text-slate-500">Sudah diproses</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-5 py-8 text-center text-sm text-slate-500">Belum ada transaksi top up.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-3.5 border-t border-neutral-800/60">{{ $deposits->links() }}</div>
    </div>
</div>
@endsection
