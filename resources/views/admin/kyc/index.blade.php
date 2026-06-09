@extends('layouts.admin')
@section('title', 'Verifikasi KYC')
@section('page_title', 'Verifikasi KYC')
@section('page_subtitle', 'Review identitas kreator dan brand yang mendaftar')

@section('content')
<div class="space-y-5">
    @if(session('success'))
        <div class="rounded-xl border border-emerald-500/20 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-300">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="rounded-xl border border-red-500/20 bg-red-500/10 px-4 py-3 text-sm text-red-300">{{ $errors->first() }}</div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @foreach([
            ['label'=>'Menunggu Verifikasi','val'=>$stats['pending'],'icon'=>'clock','color'=>'amber'],
            ['label'=>'Terverifikasi','val'=>$stats['verified'],'icon'=>'badge-check','color'=>'emerald'],
            ['label'=>'Ditolak','val'=>$stats['rejected'],'icon'=>'x-circle','color'=>'red'],
        ] as $s)
        <div class="stat-card">
            <div class="flex w-9 h-9 rounded-xl bg-{{ $s['color'] }}/10 border border-{{ $s['color'] }}/20 items-center justify-center mb-3">
                <i data-lucide="{{ $s['icon'] }}" class="w-4 h-4 text-{{ $s['color'] }}-400"></i>
            </div>
            <p class="text-xl font-bold text-white">{{ $s['val'] }}</p>
            <p class="text-xs text-slate-500">{{ $s['label'] }}</p>
        </div>
        @endforeach
    </div>

    <div class="bg-neutral-900/60 border border-neutral-800/60 rounded-2xl overflow-hidden">
        <div class="p-5 border-b border-neutral-800/60">
            <h3 class="text-sm font-semibold text-white">Antrian Verifikasi KYC</h3>
        </div>
        <div class="divide-y divide-neutral-800/40">
            @forelse($users as $user)
            @php
                $statusLabel = match($user->kyc_status) {
                    'verified' => 'Diverifikasi',
                    'rejected' => 'Ditolak',
                    default => 'Menunggu',
                };
                $statusClass = match($user->kyc_status) {
                    'verified' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                    'rejected' => 'bg-red-500/10 text-red-400 border-red-500/20',
                    default => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                };
                $roleLabel = $user->role === 'brand' ? 'Brand' : 'Kreator';
                $documentType = $user->kyc_document_type ?: ($user->role === 'brand' ? 'NPWP' : 'KTP');
            @endphp
            <div class="flex flex-col gap-4 px-5 py-4 hover:bg-white/[2%] transition-colors lg:flex-row lg:items-center">
                <div class="flex items-center gap-4 flex-1 min-w-0">
                    <div class="w-10 h-10 rounded-full bg-brand/20 flex items-center justify-center text-sm font-bold text-brand flex-shrink-0">
                        {{ strtoupper(substr($user->name,0,1)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-white truncate">{{ $user->name }}</p>
                        <p class="text-xs text-slate-500">
                            {{ $roleLabel }} · Dokumen: {{ $documentType }} · {{ $user->created_at->diffForHumans() }}
                        </p>
                    </div>
                </div>
                <span class="w-max text-xs font-semibold px-2.5 py-1 rounded-full border {{ $statusClass }}">{{ $statusLabel }}</span>
                @if($user->kyc_status === 'pending')
                <div class="flex gap-1.5">
                    <form method="POST" action="{{ route('admin.kyc.update', $user) }}">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="kyc_status" value="verified">
                        <button class="flex items-center gap-1 px-3 py-1.5 text-xs font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 rounded-xl hover:bg-emerald-500/20 transition-colors">
                            <i data-lucide="check" class="w-3 h-3"></i> Verifikasi
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.kyc.update', $user) }}">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="kyc_status" value="rejected">
                        <button class="flex items-center gap-1 px-3 py-1.5 text-xs font-medium bg-red-500/10 text-red-400 border border-red-500/20 rounded-xl hover:bg-red-500/20 transition-colors">
                            <i data-lucide="x" class="w-3 h-3"></i> Tolak
                        </button>
                    </form>
                </div>
                @else
                <p class="text-xs text-slate-500">{{ $user->kyc_reviewed_at?->format('d M Y, H:i') }}</p>
                @endif
            </div>
            @empty
            <div class="px-5 py-8 text-center text-sm text-slate-500">Belum ada pengguna untuk diverifikasi.</div>
            @endforelse
        </div>
        <div class="px-5 py-3.5 border-t border-neutral-800/60">{{ $users->links() }}</div>
    </div>
</div>
@endsection
