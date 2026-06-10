@extends('layouts.admin')
@section('title', 'Pencarian Admin')
@section('page_title', 'Pencarian Admin')
@section('page_subtitle', 'Temukan halaman, pengguna, campaign, dan transaksi')

@section('content')
@php
    $totalResults = $pages->count() + $users->count() + $campaigns->count() + $deposits->count() + $withdrawals->count();
@endphp

<div class="space-y-5">
    <div class="bg-neutral-900/60 border border-neutral-800/60 rounded-2xl p-5">
        <form method="GET" action="{{ route('admin.search') }}" class="flex flex-col md:flex-row gap-3">
            <div class="flex-1 flex items-center gap-3 bg-neutral-950/70 border border-neutral-800 rounded-xl px-4 py-3 focus-within:border-brand/60 transition-colors">
                <i data-lucide="search" class="w-4 h-4 text-slate-500 flex-shrink-0"></i>
                <input
                    type="search"
                    name="q"
                    value="{{ $query }}"
                    placeholder="Cari pengguna, campaign, transaksi, atau halaman admin..."
                    autofocus
                    class="w-full bg-transparent text-sm text-white placeholder-slate-600 outline-none"
                >
            </div>
            <button class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-brand text-white text-sm font-semibold rounded-xl hover:bg-brand-hover transition-colors">
                <i data-lucide="search" class="w-4 h-4"></i>
                Cari
            </button>
        </form>
        <p class="mt-3 text-xs text-slate-500">
            @if($query === '')
                Ketik kata kunci untuk mulai mencari.
            @else
                Ditemukan {{ $totalResults }} hasil untuk "{{ $query }}".
            @endif
        </p>
    </div>

    @if($query !== '')
        @if($totalResults === 0)
            <div class="bg-neutral-900/60 border border-neutral-800/60 rounded-2xl p-10 text-center">
                <div class="mx-auto w-12 h-12 rounded-2xl bg-neutral-800 border border-neutral-700 flex items-center justify-center mb-4">
                    <i data-lucide="search-x" class="w-5 h-5 text-slate-500"></i>
                </div>
                <h3 class="text-sm font-semibold text-white">Tidak ada hasil</h3>
                <p class="text-xs text-slate-500 mt-1">Coba gunakan nama, email, order ID, status, atau nama fitur admin lain.</p>
            </div>
        @endif

        @if($pages->isNotEmpty())
            <div class="bg-neutral-900/60 border border-neutral-800/60 rounded-2xl overflow-hidden">
                <div class="px-5 py-4 border-b border-neutral-800/60">
                    <h3 class="text-sm font-semibold text-white">Halaman Admin</h3>
                </div>
                <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-3 p-5">
                    @foreach($pages as $page)
                        <a href="{{ route($page['route']) }}" class="group flex items-start gap-3 rounded-xl border border-neutral-800 bg-neutral-950/40 p-4 hover:border-brand/40 hover:bg-white/[2%] transition-all">
                            <div class="w-9 h-9 rounded-xl bg-brand/10 border border-brand/20 flex items-center justify-center flex-shrink-0">
                                <i data-lucide="{{ $page['icon'] }}" class="w-4 h-4 text-brand"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-white group-hover:text-brand-light transition-colors">{{ $page['title'] }}</p>
                                <p class="text-xs text-slate-500 mt-1">{{ $page['subtitle'] }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        @if($users->isNotEmpty())
            <div class="bg-neutral-900/60 border border-neutral-800/60 rounded-2xl overflow-hidden">
                <div class="px-5 py-4 border-b border-neutral-800/60">
                    <h3 class="text-sm font-semibold text-white">Pengguna</h3>
                </div>
                <div class="divide-y divide-neutral-800/40">
                    @foreach($users as $user)
                        @php
                            $userRoute = match($user->role) {
                                'admin' => 'admin.users',
                                'kreator' => 'admin.kreators',
                                'brand' => 'admin.brands',
                                default => 'admin.users',
                            };
                        @endphp
                        <a href="{{ route($userRoute) }}" class="flex items-center gap-4 px-5 py-4 hover:bg-white/[2%] transition-colors">
                            <div class="w-9 h-9 rounded-xl bg-neutral-800 border border-neutral-700 flex items-center justify-center text-xs font-bold text-white flex-shrink-0">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-white truncate">{{ $user->name }}</p>
                                <p class="text-xs text-slate-500 truncate">{{ $user->email }}{{ $user->company_name ? ' · ' . $user->company_name : '' }}</p>
                            </div>
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-brand/10 text-brand border border-brand/20">{{ ucfirst($user->role) }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        @if($campaigns->isNotEmpty())
            <div class="bg-neutral-900/60 border border-neutral-800/60 rounded-2xl overflow-hidden">
                <div class="px-5 py-4 border-b border-neutral-800/60">
                    <h3 class="text-sm font-semibold text-white">Campaign</h3>
                </div>
                <div class="divide-y divide-neutral-800/40">
                    @foreach($campaigns as $campaign)
                        <a href="{{ route('admin.campaigns') }}" class="flex items-center gap-4 px-5 py-4 hover:bg-white/[2%] transition-colors">
                            <div class="w-9 h-9 rounded-xl bg-violet-500/10 border border-violet-500/20 flex items-center justify-center flex-shrink-0">
                                <i data-lucide="megaphone" class="w-4 h-4 text-violet-400"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-white truncate">{{ $campaign->title }}</p>
                                <p class="text-xs text-slate-500 truncate">{{ $campaign->user?->company_name ?: $campaign->user?->name ?: 'Brand tidak tersedia' }} · Rp {{ number_format((int) $campaign->budget, 0, ',', '.') }}</p>
                            </div>
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-neutral-800 text-slate-300 border border-neutral-700">{{ ucfirst($campaign->status) }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        @if($deposits->isNotEmpty() || $withdrawals->isNotEmpty())
            <div class="grid xl:grid-cols-2 gap-5">
                @if($deposits->isNotEmpty())
                    <div class="bg-neutral-900/60 border border-neutral-800/60 rounded-2xl overflow-hidden">
                        <div class="px-5 py-4 border-b border-neutral-800/60">
                            <h3 class="text-sm font-semibold text-white">Top Up & Escrow</h3>
                        </div>
                        <div class="divide-y divide-neutral-800/40">
                            @foreach($deposits as $deposit)
                                <a href="{{ route('admin.payouts') }}" class="flex items-center gap-4 px-5 py-4 hover:bg-white/[2%] transition-colors">
                                    <div class="w-9 h-9 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center flex-shrink-0">
                                        <i data-lucide="vault" class="w-4 h-4 text-emerald-400"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-white truncate">{{ $deposit->order_id }}</p>
                                        <p class="text-xs text-slate-500 truncate">{{ $deposit->user?->company_name ?: $deposit->user?->name ?: '-' }} · Rp {{ number_format((int) $deposit->amount, 0, ',', '.') }}</p>
                                    </div>
                                    <span class="text-xs text-slate-500">{{ ucfirst($deposit->status) }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($withdrawals->isNotEmpty())
                    <div class="bg-neutral-900/60 border border-neutral-800/60 rounded-2xl overflow-hidden">
                        <div class="px-5 py-4 border-b border-neutral-800/60">
                            <h3 class="text-sm font-semibold text-white">Penarikan Dana</h3>
                        </div>
                        <div class="divide-y divide-neutral-800/40">
                            @foreach($withdrawals as $withdrawal)
                                <a href="{{ route('admin.withdrawals') }}" class="flex items-center gap-4 px-5 py-4 hover:bg-white/[2%] transition-colors">
                                    <div class="w-9 h-9 rounded-xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center flex-shrink-0">
                                        <i data-lucide="wallet" class="w-4 h-4 text-amber-400"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-white truncate">{{ $withdrawal->account_name ?: $withdrawal->user?->name ?: '-' }}</p>
                                        <p class="text-xs text-slate-500 truncate">{{ $withdrawal->bank_name }} · {{ $withdrawal->bank_account }} · Rp {{ number_format((int) $withdrawal->amount, 0, ',', '.') }}</p>
                                    </div>
                                    <span class="text-xs text-slate-500">{{ ucfirst($withdrawal->status) }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @endif
    @endif
</div>
@endsection
