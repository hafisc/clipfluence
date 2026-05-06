<!-- ===== SIDEBAR ===== -->
<aside class="fixed inset-y-0 left-0 z-40 w-72 bg-black border-r border-neutral-800/50 flex flex-col transform transition-transform duration-300 ease-in-out lg:relative lg:translate-x-0 -translate-x-full" :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

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



    <!-- Navigation -->
    <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-2">

        <div class="text-[10px] font-bold text-slate-600 uppercase tracking-widest px-4 mb-2">Main Menu</div>

        <a href="{{ route('kreator.dashboard') }}" class="kreator-link {{ request()->routeIs('kreator.dashboard') ? 'active' : '' }}">
            <i data-lucide="layout-grid" class="w-4 h-4 flex-shrink-0"></i>
            Dashboard
        </a>

        <a href="{{ route('kreator.campaigns') }}" class="kreator-link flex items-center justify-between {{ request()->routeIs('kreator.campaigns*') ? 'active' : '' }}">
            <div class="flex items-center gap-3">
                <i data-lucide="shopping-bag" class="w-4 h-4 flex-shrink-0"></i>
                Cari Campaign
            </div>
            <span class="bg-violet-600 text-white text-[10px] font-bold px-2 py-0.5 rounded-full flex items-center gap-1">
                <div class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></div> {{ \App\Models\Campaign::where('status', 'active')->count() }} Job
            </span>
        </a>

        <a href="{{ route('kreator.ai_tools') }}" class="kreator-link relative overflow-hidden group border border-transparent {{ request()->routeIs('kreator.ai_tools') ? 'active border-violet-500/30 bg-violet-500/5' : 'hover:border-violet-500/20 hover:bg-violet-500/5' }}">
            <div class="absolute inset-0 bg-gradient-to-r from-violet-600/10 to-fuchsia-600/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <i data-lucide="sparkles" class="w-4 h-4 flex-shrink-0 text-fuchsia-400"></i>
            <span class="relative z-10 text-fuchsia-100 font-semibold group-hover:text-white transition-colors">AI Auto-Clipper</span>
        </a>

        <div class="h-4"></div>
        <div class="text-[10px] font-bold text-slate-600 uppercase tracking-widest px-4 mb-2">Pekerjaan & Finance</div>

        <!-- Dropdown: Tugas & Submit -->
        <div x-data="{ open: {{ request()->routeIs('kreator.submissions*') ? 'true' : 'false' }} }">
            <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-3 rounded-xl text-sm font-medium hover:text-white hover:bg-neutral-800/50 transition-all duration-200 {{ request()->routeIs('kreator.submissions*') ? 'text-white bg-neutral-900' : 'text-slate-400' }}">
                <div class="flex items-center gap-3">
                    <i data-lucide="upload-cloud" class="w-4 h-4 flex-shrink-0 {{ request()->routeIs('kreator.submissions*') ? 'text-violet-400' : '' }}"></i>
                    Tugas & Submit
                </div>
                <i data-lucide="chevron-down" class="w-3.5 h-3.5 transition-transform duration-300" :class="open ? 'rotate-180' : ''"></i>
            </button>
            <div x-show="open" x-collapse style="display: {{ request()->routeIs('kreator.submissions*') ? 'block' : 'none' }};">
                <div class="ml-6 pl-5 border-l-2 border-neutral-800/80 space-y-1.5 mt-2">
                    <a href="{{ route('kreator.submissions.create') }}" class="block py-2 text-xs font-semibold hover:text-violet-300 transition-colors {{ request()->routeIs('kreator.submissions.create') ? 'text-violet-400' : 'text-slate-500' }}">Klaim Views (Submit Baru)</a>
                    <a href="{{ route('kreator.submissions') }}" class="block py-2 text-xs font-semibold hover:text-white transition-colors {{ request()->routeIs('kreator.submissions.index') ? 'text-white' : 'text-slate-500' }}">Riwayat Submissions</a>
                </div>
            </div>
        </div>

        <a href="{{ route('kreator.finance') }}" class="kreator-link {{ request()->routeIs('kreator.finance*') ? 'active' : '' }}">
            <i data-lucide="wallet-cards" class="w-4 h-4 flex-shrink-0"></i>
            Wallet & Penarikan
        </a>

    </nav>

    <!-- Logout Footer -->
    <div class="p-4 border-t border-neutral-800/50">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-red-500 hover:text-white hover:bg-red-500/20 transition-all duration-200">
                <i data-lucide="log-out" class="w-4 h-4 flex-shrink-0"></i>
                Keluar
            </button>
        </form>
    </div>

</aside>
