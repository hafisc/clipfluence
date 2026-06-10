@extends('layouts.admin')
@section('title', 'Notifikasi Sistem')
@section('page_title', 'Notifikasi Sistem')
@section('page_subtitle', 'Kirim pengumuman dan notifikasi massal ke pengguna')

@section('content')
<div class="space-y-5" x-data="{ tab: 'send' }">
    @if(session('success'))
        <div class="rounded-xl border border-emerald-500/20 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-300">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="rounded-xl border border-red-500/20 bg-red-500/10 px-4 py-3 text-sm text-red-300">{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="rounded-xl border border-red-500/20 bg-red-500/10 px-4 py-3 text-sm text-red-300">{{ $errors->first() }}</div>
    @endif

    <!-- Tabs -->
    <div class="flex gap-1 bg-neutral-900/60 border border-neutral-800/60 rounded-2xl p-1 w-fit">
        <button @click="tab='send'" :class="tab==='send'?'bg-brand/10 text-brand border border-brand/20':'text-slate-500 hover:text-white'" class="px-4 py-2 rounded-xl text-xs font-medium transition-all">Kirim Notifikasi</button>
        <button @click="tab='history'" :class="tab==='history'?'bg-brand/10 text-brand border border-brand/20':'text-slate-500 hover:text-white'" class="px-4 py-2 rounded-xl text-xs font-medium transition-all">Riwayat Terkirim</button>
    </div>

    <!-- Send Form -->
    <form method="POST" action="{{ route('admin.notifications.store') }}" x-show="tab==='send'" class="bg-neutral-900/60 border border-neutral-800/60 rounded-2xl p-6 space-y-4">
        @csrf
        <h3 class="text-sm font-semibold text-white mb-4">Buat Notifikasi Baru</h3>
        <div>
            <label class="block text-xs font-medium text-slate-400 mb-2">Target Penerima</label>
            <select name="target" required class="w-full bg-neutral-800 border border-neutral-700 rounded-xl px-4 py-2.5 text-sm text-slate-300 outline-none focus:border-brand transition-colors">
                <option value="all" @selected(old('target') === 'all')>Semua Pengguna</option>
                <option value="kreator" @selected(old('target') === 'kreator')>Hanya Kreator</option>
                <option value="brand" @selected(old('target') === 'brand')>Hanya Merek</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-slate-400 mb-2">Jenis Notifikasi</label>
            <select name="type" required class="w-full bg-neutral-800 border border-neutral-700 rounded-xl px-4 py-2.5 text-sm text-slate-300 outline-none focus:border-brand transition-colors">
                <option value="info" @selected(old('type', 'info') === 'info')>Info</option>
                <option value="success" @selected(old('type') === 'success')>Berhasil</option>
                <option value="warning" @selected(old('type') === 'warning')>Peringatan</option>
                <option value="error" @selected(old('type') === 'error')>Penting</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-slate-400 mb-2">Judul Notifikasi</label>
            <input type="text" name="title" value="{{ old('title') }}" required maxlength="120" placeholder="contoh: Pembaruan Platform Terbaru" class="w-full bg-neutral-800 border border-neutral-700 rounded-xl px-4 py-2.5 text-sm text-slate-300 placeholder-slate-600 outline-none focus:border-brand transition-colors">
        </div>
        <div>
            <label class="block text-xs font-medium text-slate-400 mb-2">Isi Pesan</label>
            <textarea rows="4" name="message" required maxlength="1000" placeholder="Tulis isi notifikasi di sini..." class="w-full bg-neutral-800 border border-neutral-700 rounded-xl px-4 py-2.5 text-sm text-slate-300 placeholder-slate-600 outline-none focus:border-brand transition-colors resize-none">{{ old('message') }}</textarea>
        </div>
        <div>
            <label class="block text-xs font-medium text-slate-400 mb-2">Link Aksi Opsional</label>
            <input type="text" name="action_url" value="{{ old('action_url') }}" maxlength="255" placeholder="contoh: /kreator/campaigns" class="w-full bg-neutral-800 border border-neutral-700 rounded-xl px-4 py-2.5 text-sm text-slate-300 placeholder-slate-600 outline-none focus:border-brand transition-colors">
        </div>
        <div class="flex items-center gap-3">
            <button type="submit" class="flex items-center gap-2 px-5 py-2.5 bg-brand text-white text-sm font-semibold rounded-xl hover:bg-brand-hover transition-colors">
                <i data-lucide="send" class="w-4 h-4"></i> Kirim Sekarang
            </button>
            <button type="reset" class="px-5 py-2.5 bg-neutral-800 border border-neutral-700 text-slate-300 text-sm font-medium rounded-xl hover:border-neutral-600 transition-colors">
                Reset
            </button>
        </div>
    </form>

    <!-- History -->
    <div x-show="tab==='history'" class="bg-neutral-900/60 border border-neutral-800/60 rounded-2xl overflow-hidden">
        <div class="p-5 border-b border-neutral-800/60"><h3 class="text-sm font-semibold text-white">Riwayat Notifikasi</h3></div>
        <div class="divide-y divide-neutral-800/40">
            @forelse($history as $n)
            <div class="flex items-center gap-4 px-5 py-4 hover:bg-white/[2%] transition-colors">
                <div class="w-8 h-8 rounded-xl bg-brand/10 border border-brand/20 flex items-center justify-center flex-shrink-0">
                    <i data-lucide="{{ $n['icon'] }}" class="w-4 h-4 text-brand"></i>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-white">{{ $n['title'] }}</p>
                    <p class="text-xs text-slate-500">{{ $n['target'] }} · {{ $n['sent'] }}</p>
                    <p class="text-xs text-slate-600 mt-1 line-clamp-1">{{ $n['message'] }}</p>
                </div>
                @if($n['action_url'])
                    <a href="{{ $n['action_url'] }}" class="text-xs text-brand hover:text-brand-light">Buka Link</a>
                @endif
                <span class="text-xs text-slate-500">{{ number_format($n['reach'], 0, ',', '.') }} penerima</span>
                <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">Terkirim</span>
            </div>
            @empty
            <div class="px-5 py-8 text-center text-sm text-slate-500">Belum ada notifikasi terkirim.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
