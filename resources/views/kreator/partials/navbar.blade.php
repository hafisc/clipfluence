<!-- ===== TOP NAVBAR ===== -->
<header class="bg-black border-b border-neutral-800/50 h-20 px-6 flex items-center justify-between gap-4 sticky top-0 z-20">

    <!-- Left: Mobile menu toggle -->
    <div class="flex items-center gap-4">
        <!-- Hamburger (mobile) -->
        <button @click="sidebarOpen = !sidebarOpen"
            class="lg:hidden text-slate-400 hover:text-white transition-colors p-1 rounded-lg hover:bg-neutral-800">
            <i data-lucide="menu" class="w-5 h-5"></i>
        </button>
    </div>

    <!-- Right: Actions -->
    <div class="flex items-center gap-4">

        <!-- Search -->
        <div class="hidden md:flex items-center gap-2 bg-neutral-900 border border-neutral-800 rounded-xl px-3 py-2 text-sm text-slate-500 hover:border-neutral-700 transition-colors cursor-text w-56">
            <i data-lucide="search" class="w-4 h-4 flex-shrink-0"></i>
            <span class="text-xs">Cari Campaign...</span>
        </div>

        <!-- Notifications -->
        <button class="relative w-10 h-10 flex items-center justify-center rounded-xl bg-neutral-900 border border-neutral-800 text-slate-400 hover:text-white hover:border-neutral-700 hover:bg-neutral-800 transition-all">
            <i data-lucide="bell" class="w-4 h-4 text-violet-400"></i>
            <!-- Badge -->
            <span class="absolute top-2 right-2.5 w-2 h-2 rounded-full bg-violet-500 shadow-[0_0_10px_rgba(139,92,246,0.8)] border border-[#0d0d0d]"></span>
        </button>

        <!-- Avatar -->
        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-violet-600 to-fuchsia-600 flex items-center justify-center text-white text-sm font-bold border border-neutral-700/50 shadow-sm cursor-default">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>
    </div>
</header>
