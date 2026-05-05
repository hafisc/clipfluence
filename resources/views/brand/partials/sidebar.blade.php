<!-- ===== SIDEBAR ===== -->
<aside class="fixed inset-y-0 left-0 z-40 w-64 bg-black border-r border-neutral-800/50 flex flex-col transform transition-transform duration-300 ease-in-out lg:relative lg:translate-x-0 -translate-x-full" :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

    <!-- Logo -->
    <div class="h-20 flex items-center px-6 border-b border-neutral-800/50 flex-shrink-0">
        <a href="{{ route('kreator.dashboard') }}" class="flex items-center gap-3 md:gap-3.5 group">
            <div class="w-9 h-9 flex items-center justify-center flex-shrink-0">
                <img src="{{ asset('images/brand/logo-icon.png') }}" alt="Clipfluence" class="w-9 h-9 object-contain drop-shadow-[0_0_12px_rgba(139,92,246,0.6)]">
            </div>
            <div class="pt-0.5">
                <h1 class="text-xl font-black text-white tracking-tight leading-none">Clip<span class="text-transparent bg-clip-text bg-gradient-to-r from-violet-400 to-fuchsia-400">fluence</span></h1>
            </div>
        </a>
    </div>

    <!-- User Mini Profile -->
    {{-- <div class="px-6 py-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-full bg-neutral-800 border border-neutral-700 flex items-center justify-center text-slate-300 font-bold">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>
        <div class="overflow-hidden">
            <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</p>
            <p class="text-xs text-slate-500 truncate">Sisa Saldo: Rp {{ number_format(auth()->user()->balance ?? 0, 0, ',', '.') }}</p>
        </div>
    </div> --}}



    <!-- Navigation -->
    <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1">

        <a href="{{ route('brand.dashboard') }}" class="sidebar-link {{ request()->routeIs('brand.dashboard') ? 'active' : '' }}">
            <i data-lucide="layout-dashboard" class="w-4 h-4 flex-shrink-0"></i>
            Dashboard
        </a>

        <!-- Dropdown: Campaign Saya -->
        <div x-data="{ open: {{ request()->routeIs('brand.campaigns*', 'brand.submissions*') ? 'true' : 'false' }} }">
            <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-medium hover:text-white hover:bg-white/5 transition-all duration-150 {{ request()->routeIs('brand.campaigns*', 'brand.submissions*') ? 'text-white' : 'text-slate-400' }}">
                <div class="flex items-center gap-3">
                    <i data-lucide="megaphone" class="w-4 h-4 flex-shrink-0 {{ request()->routeIs('brand.campaigns*', 'brand.submissions*') ? 'text-violet-400' : '' }}"></i>
                    Campaign Saya
                </div>
                <i data-lucide="chevron-down" class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
            </button>
            <div x-show="open" x-collapse style="display: {{ request()->routeIs('brand.campaigns*', 'brand.submissions*') ? 'block' : 'none' }};">
                <div class="ml-5 pl-4 border-l border-neutral-800 space-y-1 mt-1">
                    <a href="{{ route('brand.campaigns') }}" class="block py-2 text-xs font-medium hover:text-white transition-colors {{ request()->routeIs('brand.campaigns') ? 'text-violet-400' : 'text-slate-500' }}">Daftar Campaign</a>
                    <a href="{{ route('brand.campaigns.create') }}" class="block py-2 text-xs font-medium hover:text-white transition-colors {{ request()->routeIs('brand.campaigns.create') ? 'text-violet-400' : 'text-slate-500' }}">Buat Campaign Baru</a>
                    <a href="{{ route('brand.submissions') }}" class="flex items-center justify-between py-2 text-xs font-medium hover:text-white transition-colors {{ request()->routeIs('brand.submissions*') ? 'text-violet-400' : 'text-slate-500' }}">
                        Review UGC <span class="text-[9px] bg-red-500/10 text-red-400 border border-red-500/20 px-1.5 py-0.5 rounded-full font-bold">0</span>
                    </a>
                </div>
            </div>
        </div>

        <a href="{{ route('brand.finance') }}" class="sidebar-link {{ request()->routeIs('brand.finance*') ? 'active' : '' }}">
            <i data-lucide="wallet" class="w-4 h-4 flex-shrink-0"></i>
            Keuangan & Deposit
        </a>

        <a href="{{ route('brand.settings') }}" class="sidebar-link {{ request()->routeIs('brand.settings*') ? 'active' : '' }}">
            <i data-lucide="settings" class="w-4 h-4 flex-shrink-0"></i>
            Pengaturan
        </a>

    </nav>

    <!-- Footer / User Actions -->
    <div class="p-4 border-t border-neutral-800/50">
        <form method="POST" action="/logout">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 text-sm font-medium text-slate-400 hover:text-white hover:bg-white/5 rounded-xl transition-colors">
                <i data-lucide="log-out" class="w-4 h-4"></i>
                Keluar
            </button>
        </form>
    </div>
</aside>
