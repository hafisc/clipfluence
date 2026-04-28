<!-- ===== SIDEBAR SIMPLE DROPDOWN VERSION ===== -->
<aside
    class="fixed inset-y-0 left-0 z-40 w-64 bg-black border-r border-neutral-800 flex flex-col
    transform transition-transform duration-300 ease-in-out
    lg:relative lg:translate-x-0"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
>

    <!-- Logo -->
    <div class="h-20 flex items-center px-6 border-b border-neutral-800 flex-shrink-0">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-brand flex items-center justify-center">
                <i data-lucide="layout-dashboard" class="w-5 h-5 text-white"></i>
            </div>

            <h1 class="text-xl font-bold text-white">
                Clip<span class="text-brand-light">fluence</span>
            </h1>
        </a>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-2">

        <!-- Dashboard -->
        <a href="{{ route('admin.dashboard') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium
           {{ request()->routeIs('admin.dashboard') ? 'bg-brand text-white' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
            <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
            Dashboard
        </a>

        <!-- Dropdown Campaign -->
        <div x-data="{ open: true }">
            <button
                @click="open = !open"
                class="w-full flex items-center justify-between px-4 py-3 rounded-xl text-sm font-medium text-slate-400 hover:bg-white/5 hover:text-white transition"
            >
                <div class="flex items-center gap-3">
                    <i data-lucide="megaphone" class="w-4 h-4"></i>
                    Campaign & Konten
                </div>

                <i
                    data-lucide="chevron-down"
                    class="w-4 h-4 transition-transform"
                    :class="open ? 'rotate-180' : ''"
                ></i>
            </button>

            <div x-show="open" x-collapse class="ml-6 mt-2 space-y-1">
                <a href="{{ route('admin.campaigns') }}"
                   class="block px-3 py-2 rounded-lg text-xs
                   {{ request()->routeIs('admin.campaigns*') ? 'text-brand' : 'text-slate-500 hover:text-white' }}">
                    Semua Campaign
                </a>

                <a href="{{ route('admin.ugc') }}"
                   class="block px-3 py-2 rounded-lg text-xs
                   {{ request()->routeIs('admin.ugc*') ? 'text-brand' : 'text-slate-500 hover:text-white' }}">
                    Moderasi UGC
                </a>
            </div>
        </div>

        <!-- Dropdown Verifikasi -->
        <div x-data="{ open: false }">
            <button
                @click="open = !open"
                class="w-full flex items-center justify-between px-4 py-3 rounded-xl text-sm font-medium text-slate-400 hover:bg-white/5 hover:text-white transition"
            >
                <div class="flex items-center gap-3">
                    <i data-lucide="shield-check" class="w-4 h-4"></i>
                    Verifikasi
                </div>

                <i
                    data-lucide="chevron-down"
                    class="w-4 h-4 transition-transform"
                    :class="open ? 'rotate-180' : ''"
                ></i>
            </button>

            <div x-show="open" x-collapse class="ml-6 mt-2 space-y-1">
                <a href="{{ route('admin.kyc') }}"
                   class="block px-3 py-2 rounded-lg text-xs
                   {{ request()->routeIs('admin.kyc*') ? 'text-brand' : 'text-slate-500 hover:text-white' }}">
                    Verifikasi KYC
                </a>
            </div>
        </div>

        <!-- Dropdown Keuangan -->
        <div x-data="{ open: false }">
            <button
                @click="open = !open"
                class="w-full flex items-center justify-between px-4 py-3 rounded-xl text-sm font-medium text-slate-400 hover:bg-white/5 hover:text-white transition"
            >
                <div class="flex items-center gap-3">
                    <i data-lucide="wallet" class="w-4 h-4"></i>
                    Keuangan
                </div>

                <i
                    data-lucide="chevron-down"
                    class="w-4 h-4 transition-transform"
                    :class="open ? 'rotate-180' : ''"
                ></i>
            </button>

            <div x-show="open" x-collapse class="ml-6 mt-2 space-y-1">
                <a href="{{ route('admin.withdrawals') }}"
                   class="block px-3 py-2 rounded-lg text-xs
                   {{ request()->routeIs('admin.withdrawals*') ? 'text-brand' : 'text-slate-500 hover:text-white' }}">
                    Penarikan Dana
                </a>
            </div>
        </div>

        <!-- Logs -->
        <a href="{{ route('admin.logs') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium
           {{ request()->routeIs('admin.logs*') ? 'bg-brand text-white' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
            <i data-lucide="file-text" class="w-4 h-4"></i>
            Log Aktivitas
        </a>

    </nav>

    <!-- Footer User -->
    <div class="p-4 border-t border-neutral-800">

        <div class="flex items-center gap-3 mb-4">
            <div class="w-9 h-9 rounded-full bg-brand flex items-center justify-center text-white font-bold">
                A
            </div>

            <div>
                <p class="text-sm font-semibold text-white">
                    Admin User
                </p>
                <p class="text-xs text-slate-500">
                    admin@clipfluence.com
                </p>
            </div>
        </div>

        <!-- Logout -->
        <form method="POST" action="/logout">
            @csrf
            <button
                type="submit"
                class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-xl
                bg-red-500 hover:bg-red-600 text-white text-sm font-semibold transition">
                <i data-lucide="log-out" class="w-4 h-4"></i>
                Logout
            </button>
        </form>

    </div>
</aside>