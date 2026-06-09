@extends('layouts.admin')
@section('title', 'Kelola Campaign')
@section('page_title', 'Kelola Campaign')
@section('page_subtitle', 'Kelola seluruh campaign dari semua brand')

@section('content')
<div class="space-y-5" x-data="{ createOpen: false }">
    @if(session('success'))
        <div class="rounded-xl border border-emerald-500/20 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-300">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="rounded-xl border border-red-500/20 bg-red-500/10 px-4 py-3 text-sm text-red-300">{{ $errors->first() }}</div>
    @endif

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach([
            ['label'=>'Total Campaign','val'=>$stats['total'],'icon'=>'megaphone','color'=>'brand'],
            ['label'=>'Aktif','val'=>$stats['active'],'icon'=>'play-circle','color'=>'emerald'],
            ['label'=>'Draft','val'=>$stats['draft'],'icon'=>'file-pen-line','color'=>'slate'],
            ['label'=>'Selesai','val'=>$stats['completed'],'icon'=>'check-circle','color'=>'blue'],
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
            <h3 class="text-sm font-semibold text-white">Semua Campaign</h3>
            <button @click="createOpen = true" class="inline-flex items-center gap-2 rounded-xl bg-brand px-3 py-2 text-xs font-semibold text-white hover:bg-brand-hover transition-colors">
                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                Tambah Campaign
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-neutral-800/60">
                        @foreach(['Campaign','Brand','Slot','Budget','Rate','Status','Deadline',''] as $h)
                        <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ $h }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-800/40">
                    @forelse($campaigns as $campaign)
                    @php
                        $statusClass = match($campaign->status) {
                            'active' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                            'completed' => 'bg-blue-500/10 text-blue-400 border-blue-500/20',
                            'cancelled' => 'bg-red-500/10 text-red-400 border-red-500/20',
                            default => 'bg-neutral-700/50 text-slate-400 border-neutral-600',
                        };
                    @endphp
                    <tr class="hover:bg-white/[2%] transition-colors" x-data="{ editOpen: false, deleteOpen: false }">
                        <td class="px-5 py-3.5">
                            <p class="text-sm font-medium text-white">{{ $campaign->title }}</p>
                            <p class="text-xs text-slate-500">{{ strtoupper($campaign->type) }} • {{ $campaign->platform }}</p>
                        </td>
                        <td class="px-5 py-3.5 text-xs text-slate-300">{{ $campaign->user?->company_name ?: $campaign->user?->name ?: '-' }}</td>
                        <td class="px-5 py-3.5 text-xs text-slate-300">{{ $campaign->slots }} kreator</td>
                        <td class="px-5 py-3.5 text-xs text-slate-300">Rp {{ number_format((int) $campaign->budget, 0, ',', '.') }}</td>
                        <td class="px-5 py-3.5 text-xs text-slate-300">Rp {{ number_format((int) $campaign->price_per_1k, 0, ',', '.') }}/1K</td>
                        <td class="px-5 py-3.5"><span class="text-xs font-semibold px-2.5 py-1 rounded-full border {{ $statusClass }}">{{ ucfirst($campaign->status) }}</span></td>
                        <td class="px-5 py-3.5 text-xs text-slate-400">{{ $campaign->deadline ? \Illuminate\Support\Carbon::parse($campaign->deadline)->format('d M Y') : '-' }}</td>
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-1.5 justify-end">
                                <button @click="editOpen = true" title="Ubah" class="p-1.5 rounded-lg text-slate-500 hover:text-amber-400 hover:bg-amber-500/10 transition-colors"><i data-lucide="pencil" class="w-3.5 h-3.5"></i></button>
                                <button @click="deleteOpen = true" title="Hapus" class="p-1.5 rounded-lg text-slate-500 hover:text-red-400 hover:bg-red-500/10 transition-colors"><i data-lucide="trash-2" class="w-3.5 h-3.5"></i></button>
                            </div>

                            <div x-show="editOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 p-4">
                                <form method="POST" action="{{ route('admin.campaigns.update', $campaign) }}" enctype="multipart/form-data" class="w-full max-w-3xl max-h-[90vh] overflow-y-auto rounded-2xl border border-neutral-800 bg-neutral-950 p-5">
                                    @csrf @method('PATCH')
                                    <h4 class="text-sm font-semibold text-white mb-4">Ubah Campaign</h4>
                                    @include('admin.campaigns.partials.form', ['campaign' => $campaign, 'brands' => $brands, 'requireThumbnail' => false])
                                    <div class="mt-5 flex justify-end gap-2">
                                        <button type="button" @click="editOpen = false" class="rounded-xl px-3 py-2 text-xs font-semibold text-slate-400 hover:text-white hover:bg-white/5">Batal</button>
                                        <button class="rounded-xl bg-brand px-3 py-2 text-xs font-semibold text-white">Simpan</button>
                                    </div>
                                </form>
                            </div>

                            <div x-show="deleteOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 p-4">
                                <form method="POST" action="{{ route('admin.campaigns.destroy', $campaign) }}" class="w-full max-w-sm rounded-2xl border border-neutral-800 bg-neutral-950 p-5">
                                    @csrf @method('DELETE')
                                    <h4 class="text-sm font-semibold text-white">Hapus Campaign?</h4>
                                    <p class="mt-2 text-xs leading-5 text-slate-400">Campaign {{ $campaign->title }} akan dihapus dari database.</p>
                                    <div class="mt-5 flex justify-end gap-2">
                                        <button type="button" @click="deleteOpen = false" class="rounded-xl px-3 py-2 text-xs font-semibold text-slate-400 hover:text-white hover:bg-white/5">Batal</button>
                                        <button class="rounded-xl bg-red-500 px-3 py-2 text-xs font-semibold text-white">Hapus</button>
                                    </div>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="px-5 py-8 text-center text-sm text-slate-500">Belum ada campaign.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-3.5 border-t border-neutral-800/60">{{ $campaigns->links() }}</div>
    </div>

    <div x-show="createOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 p-4">
        <form method="POST" action="{{ route('admin.campaigns.store') }}" enctype="multipart/form-data" class="w-full max-w-3xl max-h-[90vh] overflow-y-auto rounded-2xl border border-neutral-800 bg-neutral-950 p-5">
            @csrf
            <h4 class="text-sm font-semibold text-white mb-4">Tambah Campaign</h4>
            @include('admin.campaigns.partials.form', ['campaign' => null, 'brands' => $brands, 'requireThumbnail' => true])
            <div class="mt-5 flex justify-end gap-2">
                <button type="button" @click="createOpen = false" class="rounded-xl px-3 py-2 text-xs font-semibold text-slate-400 hover:text-white hover:bg-white/5">Batal</button>
                <button class="rounded-xl bg-brand px-3 py-2 text-xs font-semibold text-white">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
