<!-- Register Modal -->
<div x-show="showRegisterModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center overflow-y-auto overflow-x-hidden p-4 sm:p-0">
    <!-- Backdrop -->
    <div x-show="showRegisterModal" 
         x-transition:enter="ease-out duration-300" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100" 
         x-transition:leave="ease-in duration-200" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0" 
         @click="showRegisterModal = false"
         class="fixed inset-0 bg-black/80 backdrop-blur-sm transition-opacity pointer-events-auto"></div>

    <!-- Modal Panel -->
    <div x-show="showRegisterModal" 
         x-transition:enter="ease-out duration-300" 
         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
         x-transition:leave="ease-in duration-200" 
         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
         class="relative bg-neutral-900 border border-neutral-800 rounded-3xl shadow-2xl text-left overflow-hidden transform transition-all sm:my-8 sm:max-w-2xl w-full ring-1 ring-white/10 pointer-events-auto">
        
        <div class="px-6 py-8 sm:p-10">
            <!-- Header -->
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h3 class="text-2xl font-bold text-white mb-1">Mulai Bersama Clipfluence 🚀</h3>
                    <p class="text-neutral-400 text-sm">Pilih tipe akun Anda untuk melanjutkan proses pendaftaran.</p>
                </div>
                <button @click="showRegisterModal = false" class="text-neutral-400 hover:text-white transition-colors bg-neutral-800/50 hover:bg-neutral-800 p-2 rounded-full self-start">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            
            <!-- Cards Container -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 my-8">
                <!-- Card Brand -->
                <a href="/register?role=brand" class="group block relative rounded-2xl border border-neutral-800 bg-neutral-950 p-6 hover:border-brand hover:bg-brand/5 focus:outline-none focus:ring-2 focus:ring-brand transition-all duration-300">
                    <div class="w-12 h-12 rounded-xl bg-orange-500/10 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <i data-lucide="building-2" class="w-6 h-6 text-orange-500"></i>
                    </div>
                    <h4 class="text-lg font-bold text-white mb-2 group-hover:text-brand transition-colors">Saya Brand</h4>
                    <p class="text-sm text-neutral-400 leading-relaxed">Saya ingin mempromosikan produk, mencari konten UGC, dan membayar jasa Kreator/Clipper.</p>
                    <div class="absolute top-6 right-6 opacity-0 group-hover:opacity-100 transform translate-x-2 group-hover:translate-x-0 transition-all">
                        <i data-lucide="arrow-right" class="w-5 h-5 text-brand"></i>
                    </div>
                </a>

                <!-- Card Kreator -->
                <a href="/register?role=creator" class="group block relative rounded-2xl border border-neutral-800 bg-neutral-950 p-6 hover:border-violet-500 hover:bg-violet-500/5 focus:outline-none focus:ring-2 focus:ring-violet-500 transition-all duration-300">
                    <div class="w-12 h-12 rounded-xl bg-violet-500/10 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <i data-lucide="clapperboard" class="w-6 h-6 text-violet-500"></i>
                    </div>
                    <h4 class="text-lg font-bold text-white mb-2 group-hover:text-violet-400 transition-colors">Saya Kreator</h4>
                    <p class="text-sm text-neutral-400 leading-relaxed">Saya ingin membuat video untuk brand, mengerjakan project kampanye, dan dibayar.</p>
                    <div class="absolute top-6 right-6 opacity-0 group-hover:opacity-100 transform translate-x-2 group-hover:translate-x-0 transition-all">
                        <i data-lucide="arrow-right" class="w-5 h-5 text-violet-500"></i>
                    </div>
                </a>
            </div>

            <div class="mt-8 text-center bg-neutral-950/50 rounded-xl py-6 px-4 border border-neutral-800/50">
                <p class="text-sm text-neutral-300 mb-4 font-medium">Bisa juga daftar lebih cepat lewat sinkronisasi akun Google</p>
                <a href="/auth/google" class="max-w-xs mx-auto flex items-center justify-center gap-3 bg-white hover:bg-neutral-100 text-neutral-900 px-4 py-3 rounded-xl font-bold shadow-sm transition-colors duration-200">
                    <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google" class="w-5 h-5">
                    Lanjutkan dengan Google
                </a>
                <p class="text-xs text-neutral-500 mt-4 max-w-sm mx-auto">Kami akan menanyakan kamu Brand atau Kreator pada langkah selanjutnya.</p>
            </div>

            <p class="text-center text-sm text-neutral-400 mt-8 font-medium">
                Sudah punya akun? 
                <button @click="showRegisterModal = false; setTimeout(() => showLoginModal = true, 300)" class="text-brand hover:text-brand-light font-semibold hover:underline">Masuk di sini</button>
            </p>
        </div>
    </div>
</div>
