<!-- ===== TOP NAVBAR ===== -->
<header class="bg-black border-b border-neutral-800/50 h-20 px-6 flex items-center justify-between gap-4 sticky top-0 z-20">

    <!-- Left: Mobile menu toggle + Breadcrumb -->
    <div class="flex items-center gap-4">
        <!-- Hamburger (mobile) -->
        <button @click="sidebarOpen = !sidebarOpen"
            class="lg:hidden text-slate-400 hover:text-white transition-colors p-1 rounded-lg hover:bg-white/5">
            <i data-lucide="menu" class="w-5 h-5"></i>
        </button>
    </div>

    <!-- Right: Actions -->
    <div class="flex items-center gap-3">

        <!-- Search -->
        <div class="hidden md:block relative" x-data="{ open: false, query: '', results: [], loading: false }" @click.away="open = false">
            <div class="flex items-center gap-2 bg-neutral-900 border border-neutral-800 rounded-xl px-3 py-2 text-sm hover:border-neutral-700 transition-colors w-56"
                 :class="open ? 'border-violet-500/50 ring-2 ring-violet-500/20' : ''">
                <i data-lucide="search" class="w-4 h-4 flex-shrink-0 text-slate-500"></i>
                <input type="text" 
                       x-model="query"
                       @input.debounce.300ms="
                           if (query.length >= 1) {
                               loading = true;
                               fetch(`/brand/campaigns/search?q=${encodeURIComponent(query)}`)
                                   .then(res => res.json())
                                   .then(data => {
                                       results = data.campaigns || [];
                                       open = true;
                                       loading = false;
                                   })
                                   .catch(() => {
                                       loading = false;
                                   });
                           } else {
                               results = [];
                               open = false;
                           }
                       "
                       @focus="if (query.length >= 1) open = true"
                       placeholder="Cari Campaign..."
                       class="bg-transparent border-0 outline-none text-xs text-white placeholder-slate-500 w-full">
            </div>

            <!-- Search Results Dropdown -->
            <div x-show="open && (results.length > 0 || loading)" 
                 x-transition
                 class="absolute top-full mt-2 w-80 bg-[#0f0f0f] border border-white/[0.08] rounded-xl shadow-2xl overflow-hidden z-50 right-0">
                
                <!-- Loading State -->
                <div x-show="loading" class="p-4 text-center">
                    <svg class="animate-spin h-5 w-5 text-violet-400 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p class="text-xs text-slate-500 mt-2">Mencari...</p>
                </div>

                <!-- Results -->
                <div x-show="!loading && results.length > 0" class="max-h-96 overflow-y-auto">
                    <template x-for="campaign in results" :key="campaign.id">
                        <a :href="`/brand/campaigns`" 
                           class="block p-3 hover:bg-white/[0.03] transition-colors border-b border-white/[0.04] last:border-0">
                            <div class="flex items-center gap-3">
                                <!-- Thumbnail -->
                                <div class="w-12 h-12 rounded-lg overflow-hidden flex-shrink-0 bg-white/[0.03]">
                                    <img :src="campaign.thumbnail ? `/${campaign.thumbnail}` : '/images/campaigns/default.png'" 
                                         :alt="campaign.title"
                                         class="w-full h-full object-cover"
                                         onerror="this.src='/images/campaigns/default.png'">
                                </div>
                                
                                <!-- Info -->
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-sm font-medium text-white truncate" x-text="campaign.title"></h4>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-[10px] font-medium text-slate-500 uppercase" x-text="campaign.type === 'clip' ? 'Nge-clip' : 'UGC'"></span>
                                        <span class="text-[9px] px-1.5 py-0.5 rounded bg-emerald-500/10 text-emerald-400 font-medium" x-text="campaign.status"></span>
                                    </div>
                                    <p class="text-xs text-emerald-400 mt-1" x-text="'Rp ' + campaign.price.toLocaleString('id-ID') + ' / 1K views'"></p>
                                </div>

                                <!-- Arrow -->
                                <i data-lucide="arrow-right" class="w-4 h-4 text-slate-500 flex-shrink-0"></i>
                            </div>
                        </a>
                    </template>
                </div>

                <!-- No Results -->
                <div x-show="!loading && results.length === 0 && query.length >= 1" class="p-6 text-center">
                    <i data-lucide="search-x" class="w-8 h-8 text-slate-600 mx-auto mb-2"></i>
                    <p class="text-sm font-medium text-slate-500">Tidak ada hasil</p>
                    <p class="text-xs text-slate-600 mt-1">Coba kata kunci lain</p>
                </div>
            </div>
        </div>

        <!-- Notifications -->
        <div class="relative" x-data="{ open: false, notifications: [], unreadCount: 0, loading: false }" 
             x-init="
                 // Load notifications on init
                 loading = true;
                 fetch('/brand/notifications')
                     .then(res => res.json())
                     .then(data => {
                         notifications = data.notifications || [];
                         unreadCount = data.unread_count || 0;
                         loading = false;
                     })
                     .catch(() => loading = false);
             "
             @click.away="open = false">
            
            <button @click="open = !open" 
                    class="relative w-9 h-9 flex items-center justify-center rounded-xl bg-neutral-900 border border-neutral-800 text-slate-400 hover:text-white hover:border-neutral-700 transition-all">
                <i data-lucide="bell" class="w-4 h-4"></i>
                <!-- Badge -->
                <span x-show="unreadCount > 0" 
                      class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-violet-500 shadow-[0_0_10px_rgba(139,92,246,0.8)] border border-[#0d0d0d]"></span>
            </button>

            <!-- Notifications Dropdown -->
            <div x-show="open" 
                 x-transition
                 class="absolute right-0 top-full mt-2 w-96 bg-[#0f0f0f] border border-white/[0.08] rounded-xl shadow-2xl overflow-hidden z-50">
                
                <!-- Header -->
                <div class="flex items-center justify-between px-4 py-3 border-b border-white/[0.06]">
                    <div>
                        <h3 class="text-sm font-semibold text-white">Notifikasi</h3>
                        <p class="text-xs text-slate-500 mt-0.5" x-text="unreadCount > 0 ? unreadCount + ' belum dibaca' : 'Semua sudah dibaca'"></p>
                    </div>
                    <button @click="
                        if (unreadCount > 0) {
                            fetch('/brand/notifications/mark-all-read', { method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content } })
                                .then(() => {
                                    notifications.forEach(n => n.read_at = new Date());
                                    unreadCount = 0;
                                });
                        }
                    " 
                    x-show="unreadCount > 0"
                    class="text-xs font-medium text-violet-400 hover:text-violet-300 transition-colors">
                        Tandai semua dibaca
                    </button>
                </div>

                <!-- Loading State -->
                <div x-show="loading" class="p-6 text-center">
                    <svg class="animate-spin h-5 w-5 text-violet-400 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p class="text-xs text-slate-500 mt-2">Memuat notifikasi...</p>
                </div>

                <!-- Notifications List -->
                <div x-show="!loading && notifications.length > 0" class="max-h-96 overflow-y-auto">
                    <template x-for="notif in notifications" :key="notif.id">
                        <div @click="
                            if (!notif.read_at) {
                                fetch(`/brand/notifications/${notif.id}/read`, { method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content } })
                                    .then(() => {
                                        notif.read_at = new Date();
                                        unreadCount = Math.max(0, unreadCount - 1);
                                    });
                            }
                            if (notif.action_url) window.location.href = notif.action_url;
                        "
                        class="block p-4 hover:bg-white/[0.03] transition-colors border-b border-white/[0.04] last:border-0 cursor-pointer"
                        :class="!notif.read_at ? 'bg-violet-500/5' : ''">
                            <div class="flex items-start gap-3">
                                <!-- Icon -->
                                <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0"
                                     :class="notif.type === 'success' ? 'bg-emerald-500/10' : (notif.type === 'warning' ? 'bg-amber-500/10' : 'bg-violet-500/10')">
                                    <i :data-lucide="notif.icon || 'bell'" 
                                       class="w-4 h-4"
                                       :class="notif.type === 'success' ? 'text-emerald-400' : (notif.type === 'warning' ? 'text-amber-400' : 'text-violet-400')"></i>
                                </div>
                                
                                <!-- Content -->
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-white" x-text="notif.title"></p>
                                    <p class="text-xs text-slate-500 mt-1 line-clamp-2" x-text="notif.message"></p>
                                    <p class="text-[10px] text-slate-600 mt-2" x-text="notif.time_ago"></p>
                                </div>

                                <!-- Unread Indicator -->
                                <div x-show="!notif.read_at" class="w-2 h-2 rounded-full bg-violet-500 flex-shrink-0 mt-2"></div>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Empty State -->
                <div x-show="!loading && notifications.length === 0" class="p-8 text-center">
                    <i data-lucide="inbox" class="w-12 h-12 text-slate-600 mx-auto mb-3"></i>
                    <p class="text-sm font-medium text-slate-500">Belum ada notifikasi</p>
                    <p class="text-xs text-slate-600 mt-1">Notifikasi akan muncul di sini</p>
                </div>

                <!-- Footer -->
                <div class="px-4 py-3 border-t border-white/[0.06] bg-black/20">
                    <a href="#" class="text-xs font-medium text-slate-400 hover:text-white transition-colors flex items-center justify-center gap-1">
                        Lihat semua notifikasi
                        <i data-lucide="arrow-right" class="w-3 h-3"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Avatar -->
        @php
            $user = auth()->user();
            // Prioritas: 1. Google avatar, 2. DiceBear random avatar
            $avatarUrl = $user->avatar 
                ? $user->avatar 
                : 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . urlencode($user->email);
        @endphp
        
        <div class="w-9 h-9 rounded-xl overflow-hidden border border-neutral-700/50 shadow-sm cursor-default">
            <img src="{{ $avatarUrl }}" 
                 alt="{{ $user->name }}" 
                 class="w-full h-full object-cover"
                 onerror="this.onerror=null; this.src='https://api.dicebear.com/7.x/avataaars/svg?seed={{ urlencode($user->email) }}';">
        </div>
    </div>
</header>
