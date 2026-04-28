<!-- ===== SIDEBAR ===== -->
<aside
    class="fixed inset-y-0 left-0 z-40 w-64 bg-black border-r border-neutral-800/50 flex flex-col transform transition-transform duration-300 ease-in-out lg:relative lg:translate-x-0"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
>
    <!-- Logo -->
    <div class="h-20 flex items-center px-6 border-b border-neutral-800/50 flex-shrink-0">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 group">
            <div class="w-9 h-9 rounded-xl bg-brand flex items-center justify-center flex-shrink-0 shadow-[0_0_12px_rgba(139,92,246,0.6)]">
                <i data-lucide="building-2" class="w-5 h-5 text-white"></i>
            </div>
            <div class="pt-0.5">
                <h1 class="text-xl font-black text-white tracking-tight leading-none">Clip<span class="text-brand-light">fluence</span></h1>
            </div>
        </a>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1">

        <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i data-lucide="layout-dashboard" class="w-4 h-4 flex-shrink-0"></i>
            Dashboard
        </a>

        <!-- Dropdown: Pengguna -->
        <div x-data="{ open: {{ request()->routeIs('admin.users*', 'admin.kreators*', 'admin.brands*', 'admin.kyc*') ? 'true' : 'false' }} }">
            <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-medium hover:text-white hover:bg-white/5 transition-all duration-150 {{ request()->routeIs('admin.users*', 'admin.kreators*', 'admin.brands*', 'admin.kyc*') ? 'text-white' : 'text-slate-400' }}">
                <div class="flex items-center gap-3">
                    <i data-lucide="users" class="w-4 h-4 flex-shrink-0 {{ request()->routeIs('admin.users*', 'admin.kreators*', 'admin.brands*', 'admin.kyc*') ? 'text-brand' : '' }}"></i>
                    Pengguna & Entitas
                </div>
                <i data-lucide="chevron-down" class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
            </button>
            <div x-show="open" x-collapse style="display: {{ request()->routeIs('admin.users*', 'admin.kreators*', 'admin.brands*', 'admin.kyc*') ? 'block' : 'none' }};">
                <div class="ml-5 pl-4 border-l border-neutral-800 space-y-1 mt-1">
                    <a href="{{ route('admin.users') }}" class="block py-2 text-xs font-medium hover:text-white transition-colors {{ request()->routeIs('admin.users*') ? 'text-brand' : 'text-slate-500' }}">Staf Internal</a>
                    <a href="{{ route('admin.kreators') }}" class="block py-2 text-xs font-medium hover:text-white transition-colors {{ request()->routeIs('admin.kreators*') ? 'text-brand' : 'text-slate-500' }}">Daftar Kreator</a>
                    <a href="{{ route('admin.brands') }}" class="block py-2 text-xs font-medium hover:text-white transition-colors {{ request()->routeIs('admin.brands*') ? 'text-brand' : 'text-slate-500' }}">Daftar Brand</a>
                    <a href="{{ route('admin.kyc') }}" class="flex items-center justify-between py-2 text-xs font-medium hover:text-white transition-colors {{ request()->routeIs('admin.kyc*') ? 'text-brand' : 'text-slate-500' }}">
                        Verifikasi KYC <span class="text-[9px] bg-amber-500/10 text-amber-400 border border-amber-500/20 px-1.5 py-0.5 rounded-full font-bold">5</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Dropdown: Campaign & Konten -->
        <div x-data="{ open: {{ request()->routeIs('admin.campaigns*', 'admin.ugc*') ? 'true' : 'false' }} }">
            <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-medium hover:text-white hover:bg-white/5 transition-all duration-150 {{ request()->routeIs('admin.campaigns*', 'admin.ugc*') ? 'text-white' : 'text-slate-400' }}">
                <div class="flex items-center gap-3">
                    <i data-lucide="megaphone" class="w-4 h-4 flex-shrink-0 {{ request()->routeIs('admin.campaigns*', 'admin.ugc*') ? 'text-brand' : '' }}"></i>
                    Campaign & Konten
                </div>
                <i data-lucide="chevron-down" class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
            </button>
            <div x-show="open" x-collapse style="display: {{ request()->routeIs('admin.campaigns*', 'admin.ugc*') ? 'block' : 'none' }};">
                <div class="ml-5 pl-4 border-l border-neutral-800 space-y-1 mt-1">
                    <a href="{{ route('admin.campaigns') }}" class="block py-2 text-xs font-medium hover:text-white transition-colors {{ request()->routeIs('admin.campaigns*') ? 'text-brand' : 'text-slate-500' }}">Semua Campaign</a>
                    <a href="{{ route('admin.ugc') }}" class="flex items-center justify-between py-2 text-xs font-medium hover:text-white transition-colors {{ request()->routeIs('admin.ugc*') ? 'text-brand' : 'text-slate-500' }}">
                        Moderasi UGC <span class="text-[9px] bg-red-500/10 text-red-400 border border-red-500/20 px-1.5 py-0.5 rounded-full font-bold">12</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Dropdown: Keuangan -->
        <div x-data="{ open: {{ request()->routeIs('admin.payouts*', 'admin.withdrawals*', 'admin.disputes*') ? 'true' : 'false' }} }">
            <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-medium hover:text-white hover:bg-white/5 transition-all duration-150 {{ request()->routeIs('admin.payouts*', 'admin.withdrawals*', 'admin.disputes*') ? 'text-white' : 'text-slate-400' }}">
                <div class="flex items-center gap-3">
                    <i data-lucide="wallet" class="w-4 h-4 flex-shrink-0 {{ request()->routeIs('admin.payouts*', 'admin.withdrawals*', 'admin.disputes*') ? 'text-brand' : '' }}"></i>
                    Keuangan
                </div>
                <i data-lucide="chevron-down" class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
            </button>
            <div x-show="open" x-collapse style="display: {{ request()->routeIs('admin.payouts*', 'admin.withdrawals*', 'admin.disputes*') ? 'block' : 'none' }};">
                <div class="ml-5 pl-4 border-l border-neutral-800 space-y-1 mt-1">
                    <a href="{{ route('admin.payouts') }}" class="block py-2 text-xs font-medium hover:text-white transition-colors {{ request()->routeIs('admin.payouts*') ? 'text-brand' : 'text-slate-500' }}">Pembayaran & Escrow</a>
                    <a href="{{ route('admin.withdrawals') }}" class="flex items-center justify-between py-2 text-xs font-medium hover:text-white transition-colors {{ request()->routeIs('admin.withdrawals*') ? 'text-brand' : 'text-slate-500' }}">
                        Penarikan Dana <span class="text-[9px] bg-amber-500/10 text-amber-400 border border-amber-500/20 px-1.5 py-0.5 rounded-full font-bold">3</span>
                    </a>
                    <a href="{{ route('admin.disputes') }}" class="block py-2 text-xs font-medium hover:text-white transition-colors {{ request()->routeIs('admin.disputes*') ? 'text-brand' : 'text-slate-500' }}">Tiket Bantuan</a>
                </div>
            </div>
        </div>

        <!-- Dropdown: Laporan & Sistem -->
        <div x-data="{ open: {{ request()->routeIs('admin.analytics*', 'admin.fraud*', 'admin.notifications*', 'admin.logs*', 'admin.settings*') ? 'true' : 'false' }} }">
            <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-medium hover:text-white hover:bg-white/5 transition-all duration-150 {{ request()->routeIs('admin.analytics*', 'admin.fraud*', 'admin.notifications*', 'admin.logs*', 'admin.settings*') ? 'text-white' : 'text-slate-400' }}">
                <div class="flex items-center gap-3">
                    <i data-lucide="server" class="w-4 h-4 flex-shrink-0 {{ request()->routeIs('admin.analytics*', 'admin.fraud*', 'admin.notifications*', 'admin.logs*', 'admin.settings*') ? 'text-brand' : '' }}"></i>
                    Sistem Operasional
                </div>
                <i data-lucide="chevron-down" class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
            </button>
            <div x-show="open" x-collapse style="display: {{ request()->routeIs('admin.analytics*', 'admin.fraud*', 'admin.notifications*', 'admin.logs*', 'admin.settings*') ? 'block' : 'none' }};">
                <div class="ml-5 pl-4 border-l border-neutral-800 space-y-1 mt-1">
                    <a href="{{ route('admin.analytics') }}" class="block py-2 text-xs font-medium hover:text-white transition-colors {{ request()->routeIs('admin.analytics*') ? 'text-brand' : 'text-slate-500' }}">Analitik Platform</a>
                    {{-- <a href="{{ route('admin.fraud') }}" class="block py-2 text-xs font-medium hover:text-white transition-colors {{ request()->routeIs('admin.fraud*') ? 'text-brand' : 'text-slate-500' }}">Anti-Fraud Monitor</a> --}}
                    <a href="{{ route('admin.notifications') }}" class="block py-2 text-xs font-medium hover:text-white transition-colors {{ request()->routeIs('admin.notifications*') ? 'text-brand' : 'text-slate-500' }}">Notifikasi Broadcast</a>
                    <a href="{{ route('admin.logs') }}" class="block py-2 text-xs font-medium hover:text-white transition-colors {{ request()->routeIs('admin.logs*') ? 'text-brand' : 'text-slate-500' }}">Log Aktivitas</a>
                    <a href="{{ route('admin.settings') }}" class="block py-2 text-xs font-medium hover:text-white transition-colors {{ request()->routeIs('admin.settings*') ? 'text-brand' : 'text-slate-500' }}">Pengaturan</a>
                </div>
            </div>
        </div>

    </nav>

    <!-- User Profile Footer -->
    <div class="px-3 py-4 border-t border-neutral-800/50 flex-shrink-0">
        <div class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-white/5 transition-colors cursor-pointer">
            <div class="w-8 h-8 rounded-full bg-brand flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                <p class="text-xs text-slate-500 truncate">{{ auth()->user()->email }}</p>
            </div>
            <form method="POST" action="/logout">
                @csrf
                <button type="submit" title="Keluar" class="text-slate-600 hover:text-red-400 transition-colors">
                    <i data-lucide="log-out" class="w-4 h-4"></i>
                </button>
            </form>
        </div>
    </div>
</aside>
