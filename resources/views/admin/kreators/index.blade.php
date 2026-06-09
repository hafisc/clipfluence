@extends('layouts.admin')
@section('title', 'Kelola Kreator')
@section('page_title', 'Kelola Kreator')
@section('page_subtitle', 'Ubah atau hapus akun kreator yang terdaftar di platform')

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
            ['label'=>'Total Kreator','val'=>$stats['kreators'],'icon'=>'users','color'=>'brand'],
            ['label'=>'Total Brand','val'=>$stats['brands'],'icon'=>'briefcase','color'=>'emerald'],
            ['label'=>'Staf Internal','val'=>$stats['admins'],'icon'=>'shield-check','color'=>'amber'],
            ['label'=>'Total Pengguna','val'=>$stats['total'],'icon'=>'activity','color'=>'violet'],
        ] as $s)
        <div class="stat-card">
            <div class="flex items-center mb-3 w-9 h-9 rounded-xl bg-{{ $s['color'] }}/10 border border-{{ $s['color'] }}/20 justify-center">
                <i data-lucide="{{ $s['icon'] }}" class="w-4 h-4 text-{{ $s['color']==='brand'?'brand':$s['color'].'-400' }}"></i>
            </div>
            <p class="text-xl font-bold text-white">{{ $s['val'] }}</p>
            <p class="text-xs text-slate-500">{{ $s['label'] }}</p>
        </div>
        @endforeach
    </div>

    <div class="bg-neutral-900/60 border border-neutral-800/60 rounded-2xl overflow-hidden">
        <div class="flex items-center justify-between p-5 border-b border-neutral-800/60">
            <h3 class="text-sm font-semibold text-white">Daftar Kreator</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-neutral-800/60">
                        @foreach(['Kreator','Kontak','Profil','Saldo','Bergabung',''] as $h)
                        <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ $h }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-800/40">
                    @forelse($kreators as $kreator)
                    <tr class="hover:bg-white/[2%] transition-colors" x-data="{ editOpen: false, deleteOpen: false }">
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-brand/20 flex items-center justify-center text-xs font-bold text-brand flex-shrink-0">{{ strtoupper(substr($kreator->name,0,1)) }}</div>
                                <div>
                                    <p class="text-sm font-medium text-white">{{ $kreator->name }}</p>
                                    <p class="text-xs text-slate-500">Kreator</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3.5">
                            <p class="text-xs text-slate-300">{{ $kreator->email }}</p>
                            <p class="text-xs text-slate-500">{{ $kreator->phone ?: '-' }}</p>
                        </td>
                        <td class="px-5 py-3.5">
                            <p class="text-xs text-slate-300">{{ $kreator->bio ? str($kreator->bio)->limit(36) : '-' }}</p>
                            <p class="text-xs text-slate-500">{{ $kreator->instagram_url ?: $kreator->tiktok_url ?: '-' }}</p>
                        </td>
                        <td class="px-5 py-3.5 text-xs font-semibold text-white">Rp {{ number_format((int) $kreator->balance, 0, ',', '.') }}</td>
                        <td class="px-5 py-3.5 text-xs text-slate-400">{{ $kreator->created_at->format('d M Y') }}</td>
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-1.5 justify-end">
                                <button @click="editOpen = true" title="Ubah" class="p-1.5 rounded-lg text-slate-500 hover:text-amber-400 hover:bg-amber-500/10 transition-colors"><i data-lucide="pencil" class="w-3.5 h-3.5"></i></button>
                                <button @click="deleteOpen = true" title="Hapus" class="p-1.5 rounded-lg text-slate-500 hover:text-red-400 hover:bg-red-500/10 transition-colors"><i data-lucide="trash-2" class="w-3.5 h-3.5"></i></button>
                            </div>

                            <div x-show="editOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 p-4">
                                <form method="POST" action="{{ route('admin.users.update', $kreator) }}" class="w-full max-w-lg rounded-2xl border border-neutral-800 bg-neutral-950 p-5">
                                    @csrf @method('PATCH')
                                    <h4 class="text-sm font-semibold text-white mb-4">Ubah Kreator</h4>
                                    <div class="grid gap-3 sm:grid-cols-2">
                                        <input name="name" value="{{ old('name', $kreator->name) }}" required class="rounded-xl border border-neutral-700 bg-neutral-900 px-3 py-2 text-sm text-white outline-none" placeholder="Nama">
                                        <input name="email" type="email" value="{{ old('email', $kreator->email) }}" required class="rounded-xl border border-neutral-700 bg-neutral-900 px-3 py-2 text-sm text-white outline-none" placeholder="Email">
                                        <input name="phone" value="{{ old('phone', $kreator->phone) }}" class="rounded-xl border border-neutral-700 bg-neutral-900 px-3 py-2 text-sm text-white outline-none" placeholder="Nomor telepon">
                                        <input name="password" type="password" class="rounded-xl border border-neutral-700 bg-neutral-900 px-3 py-2 text-sm text-white outline-none" placeholder="Password baru">
                                        <input name="instagram_url" value="{{ old('instagram_url', $kreator->instagram_url) }}" class="rounded-xl border border-neutral-700 bg-neutral-900 px-3 py-2 text-sm text-white outline-none" placeholder="URL Instagram">
                                        <input name="tiktok_url" value="{{ old('tiktok_url', $kreator->tiktok_url) }}" class="rounded-xl border border-neutral-700 bg-neutral-900 px-3 py-2 text-sm text-white outline-none" placeholder="URL TikTok">
                                        <textarea name="bio" class="sm:col-span-2 rounded-xl border border-neutral-700 bg-neutral-900 px-3 py-2 text-sm text-white outline-none" rows="3" placeholder="Bio">{{ old('bio', $kreator->bio) }}</textarea>
                                    </div>
                                    <div class="mt-5 flex justify-end gap-2">
                                        <button type="button" @click="editOpen = false" class="rounded-xl px-3 py-2 text-xs font-semibold text-slate-400 hover:text-white hover:bg-white/5">Batal</button>
                                        <button class="rounded-xl bg-brand px-3 py-2 text-xs font-semibold text-white">Simpan</button>
                                    </div>
                                </form>
                            </div>

                            <div x-show="deleteOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 p-4">
                                <form method="POST" action="{{ route('admin.users.destroy', $kreator) }}" class="w-full max-w-sm rounded-2xl border border-neutral-800 bg-neutral-950 p-5">
                                    @csrf @method('DELETE')
                                    <h4 class="text-sm font-semibold text-white">Hapus Kreator?</h4>
                                    <p class="mt-2 text-xs leading-5 text-slate-400">Akun {{ $kreator->name }} akan dihapus dari database.</p>
                                    <div class="mt-5 flex justify-end gap-2">
                                        <button type="button" @click="deleteOpen = false" class="rounded-xl px-3 py-2 text-xs font-semibold text-slate-400 hover:text-white hover:bg-white/5">Batal</button>
                                        <button class="rounded-xl bg-red-500 px-3 py-2 text-xs font-semibold text-white">Hapus</button>
                                    </div>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-5 py-8 text-center text-sm text-slate-500">Belum ada kreator.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-3.5 border-t border-neutral-800/60">{{ $kreators->links() }}</div>
    </div>
</div>
@endsection
