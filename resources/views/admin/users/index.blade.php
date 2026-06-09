@extends('layouts.admin')
@section('title', 'Staf Internal')
@section('page_title', 'Staf Internal')
@section('page_subtitle', 'Kelola akun admin yang memiliki akses ke dashboard admin')

@section('content')
<div class="space-y-5" x-data="{ createOpen: false }">
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
            ['label'=>'Staf Internal','val'=>$stats['admins'],'icon'=>'shield-check','color'=>'brand'],
            ['label'=>'Total Pengguna','val'=>$stats['total'],'icon'=>'users','color'=>'violet'],
            ['label'=>'Kreator','val'=>$stats['kreators'],'icon'=>'clapperboard','color'=>'emerald'],
            ['label'=>'Brand','val'=>$stats['brands'],'icon'=>'briefcase','color'=>'amber'],
        ] as $s)
        <div class="stat-card">
            <div class="flex items-center justify-center mb-3 w-9 h-9 rounded-xl bg-{{ $s['color'] }}/10 border border-{{ $s['color'] }}/20">
                <i data-lucide="{{ $s['icon'] }}" class="w-4 h-4 text-{{ $s['color'] === 'brand' ? 'brand' : $s['color'].'-400' }}"></i>
            </div>
            <p class="text-xl font-bold text-white">{{ $s['val'] }}</p>
            <p class="text-xs text-slate-500">{{ $s['label'] }}</p>
        </div>
        @endforeach
    </div>

    <div class="bg-neutral-900/60 border border-neutral-800/60 rounded-2xl overflow-hidden">
        <div class="flex items-center justify-between p-5 border-b border-neutral-800/60">
            <h3 class="text-sm font-semibold text-white">Daftar Admin</h3>
            <button @click="createOpen = true" class="inline-flex items-center gap-2 rounded-xl bg-brand px-3 py-2 text-xs font-semibold text-white hover:bg-brand-hover transition-colors">
                <i data-lucide="user-plus" class="w-3.5 h-3.5"></i>
                Tambah Staf
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-neutral-800/60">
                        @foreach(['Admin','Kontak','Bergabung',''] as $h)
                        <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ $h }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-800/40">
                    @forelse($users as $user)
                    <tr class="hover:bg-white/[2%] transition-colors" x-data="{ editOpen: false, deleteOpen: false }">
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-brand/20 text-brand flex items-center justify-center text-xs font-bold flex-shrink-0">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                                <div>
                                    <p class="text-sm font-medium text-white">{{ $user->name }}</p>
                                    <p class="text-xs text-slate-500">Staf Internal</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3.5">
                            <p class="text-xs text-slate-300">{{ $user->email }}</p>
                            <p class="text-xs text-slate-500">{{ $user->phone ?: '-' }}</p>
                        </td>
                        <td class="px-5 py-3.5 text-xs text-slate-400">{{ $user->created_at->format('d M Y') }}</td>
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-1.5 justify-end">
                                <button @click="editOpen = true" title="Ubah" class="p-1.5 rounded-lg text-slate-500 hover:text-amber-400 hover:bg-amber-500/10 transition-colors"><i data-lucide="pencil" class="w-3.5 h-3.5"></i></button>
                                <button @click="deleteOpen = true" title="Hapus" class="p-1.5 rounded-lg text-slate-500 hover:text-red-400 hover:bg-red-500/10 transition-colors"><i data-lucide="trash-2" class="w-3.5 h-3.5"></i></button>
                            </div>

                            <div x-show="editOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 p-4">
                                <form method="POST" action="{{ route('admin.users.update', $user) }}" class="w-full max-w-md rounded-2xl border border-neutral-800 bg-neutral-950 p-5">
                                    @csrf @method('PATCH')
                                    <h4 class="text-sm font-semibold text-white mb-4">Ubah Staf Internal</h4>
                                    <div class="space-y-3">
                                        <input name="name" value="{{ old('name', $user->name) }}" required class="w-full rounded-xl border border-neutral-700 bg-neutral-900 px-3 py-2 text-sm text-white outline-none" placeholder="Nama">
                                        <input name="email" type="email" value="{{ old('email', $user->email) }}" required class="w-full rounded-xl border border-neutral-700 bg-neutral-900 px-3 py-2 text-sm text-white outline-none" placeholder="Email">
                                        <input name="phone" value="{{ old('phone', $user->phone) }}" class="w-full rounded-xl border border-neutral-700 bg-neutral-900 px-3 py-2 text-sm text-white outline-none" placeholder="Nomor telepon">
                                        <input name="password" type="password" class="w-full rounded-xl border border-neutral-700 bg-neutral-900 px-3 py-2 text-sm text-white outline-none" placeholder="Password baru, kosongkan jika tidak diubah">
                                    </div>
                                    <div class="mt-5 flex justify-end gap-2">
                                        <button type="button" @click="editOpen = false" class="rounded-xl px-3 py-2 text-xs font-semibold text-slate-400 hover:text-white hover:bg-white/5">Batal</button>
                                        <button class="rounded-xl bg-brand px-3 py-2 text-xs font-semibold text-white">Simpan</button>
                                    </div>
                                </form>
                            </div>

                            <div x-show="deleteOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 p-4">
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="w-full max-w-sm rounded-2xl border border-neutral-800 bg-neutral-950 p-5">
                                    @csrf @method('DELETE')
                                    <h4 class="text-sm font-semibold text-white">Hapus Staf Internal?</h4>
                                    <p class="mt-2 text-xs leading-5 text-slate-400">Akun {{ $user->name }} akan dihapus dari database.</p>
                                    <div class="mt-5 flex justify-end gap-2">
                                        <button type="button" @click="deleteOpen = false" class="rounded-xl px-3 py-2 text-xs font-semibold text-slate-400 hover:text-white hover:bg-white/5">Batal</button>
                                        <button class="rounded-xl bg-red-500 px-3 py-2 text-xs font-semibold text-white">Hapus</button>
                                    </div>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-5 py-8 text-center text-sm text-slate-500">Belum ada staf internal.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-3.5 border-t border-neutral-800/60">{{ $users->links() }}</div>
    </div>

    <div x-show="createOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 p-4">
        <form method="POST" action="{{ route('admin.users.store') }}" class="w-full max-w-md rounded-2xl border border-neutral-800 bg-neutral-950 p-5">
            @csrf
            <h4 class="text-sm font-semibold text-white mb-4">Tambah Staf Internal</h4>
            <div class="space-y-3">
                <input name="name" value="{{ old('name') }}" required class="w-full rounded-xl border border-neutral-700 bg-neutral-900 px-3 py-2 text-sm text-white outline-none" placeholder="Nama">
                <input name="email" type="email" value="{{ old('email') }}" required class="w-full rounded-xl border border-neutral-700 bg-neutral-900 px-3 py-2 text-sm text-white outline-none" placeholder="Email">
                <input name="phone" value="{{ old('phone') }}" class="w-full rounded-xl border border-neutral-700 bg-neutral-900 px-3 py-2 text-sm text-white outline-none" placeholder="Nomor telepon">
                <input name="password" type="password" required class="w-full rounded-xl border border-neutral-700 bg-neutral-900 px-3 py-2 text-sm text-white outline-none" placeholder="Password minimal 8 karakter">
            </div>
            <div class="mt-5 flex justify-end gap-2">
                <button type="button" @click="createOpen = false" class="rounded-xl px-3 py-2 text-xs font-semibold text-slate-400 hover:text-white hover:bg-white/5">Batal</button>
                <button class="rounded-xl bg-brand px-3 py-2 text-xs font-semibold text-white">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
