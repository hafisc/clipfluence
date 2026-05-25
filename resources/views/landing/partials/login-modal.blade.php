<!-- Modal Masuk -->
<div x-show="showLoginModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center overflow-y-auto overflow-x-hidden p-4 sm:p-0">
    <!-- Backdrop -->
    <div x-show="showLoginModal" 
         x-transition:enter="ease-out duration-300" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100" 
         x-transition:leave="ease-in duration-200" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0" 
         @click="showLoginModal = false"
         class="fixed inset-0 bg-black/80 backdrop-blur-sm transition-opacity pointer-events-auto"></div>

    <!-- Modal Panel -->
    <div x-show="showLoginModal" 
         x-transition:enter="ease-out duration-300" 
         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
         x-transition:leave="ease-in duration-200" 
         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
         class="relative bg-neutral-900 border border-neutral-800 rounded-2xl shadow-2xl text-left overflow-hidden transform transition-all sm:my-8 sm:max-w-md w-full ring-1 ring-white/10 pointer-events-auto">
        
        <div class="px-6 py-8">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-white">Selamat Datang 👋</h3>
                <button @click="showLoginModal = false" class="text-neutral-400 hover:text-white transition-colors bg-neutral-800/50 hover:bg-neutral-800 p-2 rounded-full">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            
            <p class="text-neutral-400 text-sm mb-6">Masuk untuk mengelola project dan mengeksplorasi kesempatan baru di Clipfluence.</p>

            <!-- Tombol Masuk Google -->
            <!-- Note: Gunakan route yang valid jika socialite sudah siap -->
            <a href="/auth/google" class="w-full flex items-center justify-center gap-3 bg-white hover:bg-neutral-100 text-neutral-900 px-4 py-3 rounded-xl font-bold shadow-sm transition-colors duration-200">
                <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google" class="w-5 h-5">
                Lanjutkan dengan Google
            </a>

            <div class="my-6 flex items-center border-b border-neutral-800">
                <div class="flex-grow border-t border-neutral-800"></div>
                <span class="px-4 text-xs tracking-wider text-neutral-500 uppercase font-medium bg-neutral-900 relative -top-[1px]">atau dengan email</span>
                <div class="flex-grow border-t border-neutral-800"></div>
            </div>

            <!-- Standard Form -->
            <form action="/login" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-neutral-300 mb-1.5">Email</label>
                    <input type="email" name="email" required class="w-full bg-neutral-950 border border-neutral-800 focus:border-brand focus:ring-1 focus:ring-brand rounded-xl px-4 py-3 text-slate-100 placeholder-neutral-600 outline-none transition-all" placeholder="contoh@email.com">
                </div>
                <div>
                    <label class="block text-sm font-medium text-neutral-300 mb-1.5">Password</label>
                    <input type="password" name="password" required class="w-full bg-neutral-950 border border-neutral-800 focus:border-brand focus:ring-1 focus:ring-brand rounded-xl px-4 py-3 text-slate-100 placeholder-neutral-600 outline-none transition-all" placeholder="••••••••">
                </div>
                <div class="flex items-center justify-between mt-2 mb-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-neutral-700 text-brand focus:ring-brand bg-neutral-900">
                        <span class="text-xs text-neutral-400">Ingat saya</span>
                    </label>
                    <a href="/forgot-password" class="text-xs text-brand hover:text-brand-light transition-colors">Lupa Password?</a>
                </div>
                
                <button type="submit" class="w-full bg-gradient-to-r from-brand to-violet-500 hover:from-violet-600 hover:to-brand text-white font-bold py-3 px-4 rounded-xl shadow-lg shadow-brand/20 transition-all mt-4">
                    Masuk
                </button>
            </form>

            <p class="text-center text-sm text-neutral-400 mt-6">
                Belum punya akun? 
                <button @click="showLoginModal = false; setTimeout(() => showRegisterModal = true, 300)" class="text-brand hover:text-brand-light font-semibold hover:underline">Daftar sekarang</button>
            </p>
        </div>
    </div>
</div>
