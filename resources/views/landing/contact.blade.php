@extends('layouts.landing')

@section('content')
<section class="relative pt-32 pb-24 lg:pt-40 lg:pb-32 overflow-hidden min-h-screen flex flex-col justify-center bg-black">
    <!-- Ornamen Background Blur Glow (Sesuai tema neon purple & cyan) -->
    <div class="absolute top-1/4 left-10 w-[400px] h-[400px] opacity-25 pointer-events-none">
        <div class="absolute inset-0 bg-brand rounded-full blur-[100px] mix-blend-screen animate-pulse" style="animation-duration: 6s;"></div>
    </div>
    <div class="absolute bottom-1/4 right-10 w-[500px] h-[500px] opacity-20 pointer-events-none">
        <div class="absolute inset-0 bg-cyan-500 rounded-full blur-[120px] mix-blend-screen animate-pulse" style="animation-duration: 8s;"></div>
    </div>

    <!-- Grid Tech background overlay -->
    <div class="absolute inset-0 pointer-events-none bg-[linear-gradient(to_right,#1e293b_1px,transparent_1px),linear-gradient(to_bottom,#1e293b_1px,transparent_1px)] bg-[size:4rem_4rem] [mask-image:radial-gradient(ellipse_60%_50%_at_50%_50%,#000_70%,transparent_100%)] opacity-10"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full">
        <!-- Tombol Kembali -->
        <div class="mb-8 flex justify-start animate-fade-in" data-aos="fade-up">
            <a href="/" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-400 hover:text-white transition-colors group px-4 py-2.5 rounded-xl bg-neutral-900/50 border border-neutral-800/80 backdrop-blur-sm shadow-md hover:border-neutral-700/80">
                <i data-lucide="arrow-left" class="w-4 h-4 group-hover:-translate-x-1 transition-transform"></i> Kembali ke Beranda
            </a>
        </div>

        <!-- Header Halaman -->
        <div class="text-center max-w-3xl mx-auto mb-16" data-aos="fade-up">
            <span class="inline-flex items-center gap-1.5 py-1.5 px-3.5 rounded-full bg-neutral-900/80 border border-neutral-800 text-xs font-semibold text-brand-light mb-4 tracking-wider uppercase">
                <i data-lucide="help-circle" class="w-3.5 h-3.5"></i> Kontak Kami
            </span>
            <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight mb-6 text-white leading-tight">
                Hubungi Tim <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-light to-cyan-400">Clipfluence</span>
            </h1>
            <p class="text-slate-400 text-base md:text-lg">
                Punya pertanyaan tentang kolaborasi brand, pendaftaran kreator, pembayaran RPM, atau kendala teknis? Kami siap membantu Anda.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-stretch">
            <!-- Kolom Kiri: Info Kontak (lg:col-span-5) -->
            <div class="lg:col-span-5 flex flex-col justify-between space-y-8" data-aos="fade-right">
                <div class="space-y-6">
                    <div class="bg-neutral-900/40 backdrop-blur-md border border-neutral-800/80 rounded-3xl p-8 shadow-2xl relative overflow-hidden group hover:border-neutral-700/80 transition-all duration-300">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-brand/10 to-transparent rounded-full blur-2xl group-hover:scale-150 transition-all duration-500"></div>
                        <h2 class="text-xl font-bold text-white mb-6 flex items-center gap-3">
                            <i data-lucide="message-square" class="text-brand-light w-5 h-5"></i> Informasi Kontak
                        </h2>
                        
                        <div class="space-y-6">
                            <!-- Email Support -->
                            <div class="flex items-start gap-4">
                                <div class="p-3 rounded-xl bg-brand/10 text-brand-light border border-brand/20">
                                    <i data-lucide="mail" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <h3 class="text-sm font-semibold text-slate-300">Email Hubungan Kemitraan</h3>
                                    <p class="text-white font-medium mt-0.5">support@clipfluence.com</p>
                                    <p class="text-xs text-slate-500 mt-1">Waktu respon rata-rata: &lt; 2 jam kerja</p>
                                </div>
                            </div>

                            <!-- WhatsApp / Telegram -->
                            <div class="flex items-start gap-4">
                                <div class="p-3 rounded-xl bg-cyan-500/10 text-cyan-400 border border-cyan-500/20">
                                    <i data-lucide="phone" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <h3 class="text-sm font-semibold text-slate-300">Customer Success (WA)</h3>
                                    <p class="text-white font-medium mt-0.5">+62 821-2345-6789</p>
                                    <p class="text-xs text-slate-500 mt-1">Senin - Jumat | 09:00 - 18:00 WIB</p>
                                </div>
                            </div>

                            <!-- Kantor -->
                            <div class="flex items-start gap-4">
                                <div class="p-3 rounded-xl bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                    <i data-lucide="map-pin" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <h3 class="text-sm font-semibold text-slate-300">Kantor Pusat</h3>
                                    <p class="text-white font-medium mt-0.5">Sudirman Central Business District (SCBD)</p>
                                    <p class="text-xs text-slate-400 mt-1">Gedung Treasury Tower Lt. 28, Jakarta Selatan, Indonesia</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kartu Jam Layanan/FAQ Cepat -->
                    <div class="bg-neutral-900/20 backdrop-blur-sm border border-neutral-800/40 rounded-3xl p-8">
                        <h3 class="text-white font-semibold mb-3 flex items-center gap-2">
                            <i data-lucide="info" class="w-4 h-4 text-brand-light"></i> Jam Layanan
                        </h3>
                        <p class="text-slate-400 text-sm leading-relaxed">
                            Kami berusaha membalas semua pertanyaan dalam waktu kurang dari 24 jam. Untuk bantuan lebih cepat terkait pembayaran kampanye, silakan sertakan kode/ID Kampanye di formulir pesan.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Form Kontak (lg:col-span-7) -->
            <div class="lg:col-span-7" data-aos="fade-left">
                <div class="bg-neutral-900/50 backdrop-blur-xl border border-neutral-800 rounded-3xl p-8 md:p-10 shadow-2xl relative overflow-hidden">
                    <!-- Glow Efek Halus di Form -->
                    <div class="absolute -top-12 -left-12 w-32 h-32 bg-cyan-500/10 rounded-full blur-3xl pointer-events-none"></div>

                    @if(session('success'))
                        <div class="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm flex items-start gap-3" data-aos="zoom-in">
                            <i data-lucide="check-circle" class="w-5 h-5 flex-shrink-0 mt-0.5 text-emerald-400"></i>
                            <div>
                                <span class="font-bold">Berhasil!</span> {{ session('success') }}
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('contact.send') }}" method="POST" class="space-y-6" 
                        x-data="{ 
                            role: '{{ request()->query('role') === 'brand' ? 'brand' : 'creator' }}', 
                            category: 'general',
                            roleOpen: false,
                            categoryOpen: false,
                            loading: false,
                            get roleLabel() {
                                if (this.role === 'creator') return 'Kreator / Clipper';
                                if (this.role === 'brand') return 'Brand / Perusahaan';
                                return 'Umum / Lainnya';
                            },
                            get categoryLabel() {
                                if (this.category === 'general') return 'Pertanyaan Umum';
                                if (this.category === 'partnership') return 'Kampanye / Kemitraan Brand';
                                if (this.category === 'technical') return 'Kendala Teknis Platform';
                                if (this.category === 'payment') return 'Keuangan / Pembayaran RPM';
                                return 'Pertanyaan Umum';
                            }
                        }"
                        x-init="$watch('role', value => { if (value !== 'brand' && category === 'partnership') { category = 'general'; } })"
                        @submit="loading = true">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nama Lengkap -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-slate-300 mb-2">Nama Lengkap</label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                        <i data-lucide="user" class="h-4 w-4 text-slate-500 group-focus-within:text-brand transition-colors"></i>
                                    </div>
                                    <input type="text" name="name" id="name" required
                                        value="{{ old('name') }}"
                                        class="block w-full pl-10 pr-4 py-3 border border-neutral-800 rounded-xl leading-5 bg-black/40 text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand sm:text-sm transition-all"
                                        placeholder="cth. John Doe">
                                </div>
                                @error('name')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Alamat Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-slate-300 mb-2">Alamat Email</label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                        <i data-lucide="mail" class="h-4 w-4 text-slate-500 group-focus-within:text-brand transition-colors"></i>
                                    </div>
                                    <input type="email" name="email" id="email" required
                                        value="{{ old('email') }}"
                                        class="block w-full pl-10 pr-4 py-3 border border-neutral-800 rounded-xl leading-5 bg-black/40 text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand sm:text-sm transition-all"
                                        placeholder="cth. john@email.com">
                                </div>
                                @error('email')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Pilihan Peran -->
                            <div class="relative" @click.outside="roleOpen = false">
                                <label class="block text-sm font-medium text-slate-300 mb-2">Peran Anda</label>
                                
                                <input type="hidden" name="role" :value="role">
                                
                                <button type="button" @click="roleOpen = !roleOpen; categoryOpen = false" 
                                    class="flex items-center justify-between w-full px-4 py-3 border border-neutral-800 rounded-xl bg-black/40 text-slate-400 focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand sm:text-sm transition-all text-left">
                                    <span class="flex items-center gap-2">
                                        <i data-lucide="clapperboard" class="w-4 h-4 text-brand-light animate-pulse" x-show="role === 'creator'"></i>
                                        <i data-lucide="building-2" class="w-4 h-4 text-emerald-400 animate-pulse" x-show="role === 'brand'"></i>
                                        <i data-lucide="user" class="w-4 h-4 text-slate-400" x-show="role === 'other'"></i>
                                        <span x-text="roleLabel" class="text-white font-medium"></span>
                                    </span>
                                    <i data-lucide="chevron-down" class="w-4 h-4 text-slate-500 transition-transform duration-200" :class="roleOpen ? 'rotate-180' : ''"></i>
                                </button>
                                
                                <div x-show="roleOpen" 
                                    x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="opacity-0 scale-95"
                                    x-transition:enter-end="opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-75"
                                    x-transition:leave-start="opacity-100 scale-100"
                                    x-transition:leave-end="opacity-0 scale-95"
                                    class="absolute z-50 w-full mt-2 bg-neutral-950/95 backdrop-blur-xl border border-neutral-800 rounded-xl shadow-2xl overflow-hidden py-1"
                                    style="display: none;">
                                    
                                    <button type="button" @click="role = 'creator'; roleOpen = false" 
                                        class="flex items-center gap-3 w-full px-4 py-3 text-sm text-left hover:bg-neutral-900 transition-colors"
                                        :class="role === 'creator' ? 'bg-neutral-900 text-white' : 'text-slate-400'">
                                        <i data-lucide="clapperboard" class="w-4 h-4 text-brand-light"></i>
                                        <div class="flex-1">
                                            <div class="font-semibold text-white">Kreator / Clipper</div>
                                            <div class="text-xs text-slate-500">Mencari campaign atau sponsor</div>
                                        </div>
                                        <i data-lucide="check" class="w-4 h-4 text-brand-light" x-show="role === 'creator'"></i>
                                    </button>

                                    <button type="button" @click="role = 'brand'; roleOpen = false" 
                                        class="flex items-center gap-3 w-full px-4 py-3 text-sm text-left hover:bg-neutral-900 transition-colors"
                                        :class="role === 'brand' ? 'bg-neutral-900 text-white' : 'text-slate-400'">
                                        <i data-lucide="building-2" class="w-4 h-4 text-emerald-400"></i>
                                        <div class="flex-1">
                                            <div class="font-semibold text-white">Brand / Perusahaan</div>
                                            <div class="text-xs text-slate-500">Mempromosikan produk atau kampanye</div>
                                        </div>
                                        <i data-lucide="check" class="w-4 h-4 text-emerald-400" x-show="role === 'brand'"></i>
                                    </button>

                                    <button type="button" @click="role = 'other'; roleOpen = false" 
                                        class="flex items-center gap-3 w-full px-4 py-3 text-sm text-left hover:bg-neutral-900 transition-colors"
                                        :class="role === 'other' ? 'bg-neutral-900 text-white' : 'text-slate-400'">
                                        <i data-lucide="user" class="w-4 h-4 text-slate-400"></i>
                                        <div class="flex-1">
                                            <div class="font-semibold text-white">Umum / Lainnya</div>
                                            <div class="text-xs text-slate-500">Pertanyaan umum atau kerja sama lainnya</div>
                                        </div>
                                        <i data-lucide="check" class="w-4 h-4 text-slate-400" x-show="role === 'other'"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Kategori Pertanyaan -->
                            <div class="relative" @click.outside="categoryOpen = false">
                                <label class="block text-sm font-medium text-slate-300 mb-2">Kategori</label>
                                
                                <input type="hidden" name="category" :value="category">
                                
                                <button type="button" @click="categoryOpen = !categoryOpen; roleOpen = false" 
                                    class="flex items-center justify-between w-full px-4 py-3 border border-neutral-800 rounded-xl bg-black/40 text-slate-400 focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand sm:text-sm transition-all text-left">
                                    <span class="flex items-center gap-2">
                                        <i data-lucide="info" class="w-4 h-4 text-blue-400" x-show="category === 'general'"></i>
                                        <i data-lucide="briefcase" class="w-4 h-4 text-emerald-400" x-show="category === 'partnership'"></i>
                                        <i data-lucide="cpu" class="w-4 h-4 text-purple-400" x-show="category === 'technical'"></i>
                                        <i data-lucide="wallet" class="w-4 h-4 text-amber-400" x-show="category === 'payment'"></i>
                                        <span x-text="categoryLabel" class="text-white font-medium"></span>
                                    </span>
                                    <i data-lucide="chevron-down" class="w-4 h-4 text-slate-500 transition-transform duration-200" :class="categoryOpen ? 'rotate-180' : ''"></i>
                                </button>
                                
                                <div x-show="categoryOpen" 
                                    x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="opacity-0 scale-95"
                                    x-transition:enter-end="opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-75"
                                    x-transition:leave-start="opacity-100 scale-100"
                                    x-transition:leave-end="opacity-0 scale-95"
                                    class="absolute z-50 w-full mt-2 bg-neutral-950/95 backdrop-blur-xl border border-neutral-800 rounded-xl shadow-2xl overflow-hidden py-1"
                                    style="display: none;">
                                    
                                    <button type="button" @click="category = 'general'; categoryOpen = false" 
                                        class="flex items-center gap-3 w-full px-4 py-3 text-sm text-left hover:bg-neutral-900 transition-colors"
                                        :class="category === 'general' ? 'bg-neutral-900 text-white' : 'text-slate-400'">
                                        <i data-lucide="info" class="w-4 h-4 text-blue-400"></i>
                                        <div class="flex-1">
                                            <div class="font-semibold text-white">Pertanyaan Umum</div>
                                            <div class="text-xs text-slate-500">Seputar informasi umum platform</div>
                                        </div>
                                        <i data-lucide="check" class="w-4 h-4 text-blue-400" x-show="category === 'general'"></i>
                                    </button>

                                    <button type="button" x-show="role === 'brand'" @click="category = 'partnership'; categoryOpen = false" 
                                        class="flex items-center gap-3 w-full px-4 py-3 text-sm text-left hover:bg-neutral-900 transition-colors"
                                        :class="category === 'partnership' ? 'bg-neutral-900 text-white' : 'text-slate-400'">
                                        <i data-lucide="briefcase" class="w-4 h-4 text-emerald-400"></i>
                                        <div class="flex-1">
                                            <div class="font-semibold text-white">Kampanye / Kemitraan Brand</div>
                                            <div class="text-xs text-slate-500">Tanya seputar peluncuran campaign baru</div>
                                        </div>
                                        <i data-lucide="check" class="w-4 h-4 text-emerald-400" x-show="category === 'partnership'"></i>
                                    </button>

                                    <button type="button" @click="category = 'technical'; categoryOpen = false" 
                                        class="flex items-center gap-3 w-full px-4 py-3 text-sm text-left hover:bg-neutral-900 transition-colors"
                                        :class="category === 'technical' ? 'bg-neutral-900 text-white' : 'text-slate-400'">
                                        <i data-lucide="cpu" class="w-4 h-4 text-purple-400"></i>
                                        <div class="flex-1">
                                            <div class="font-semibold text-white">Kendala Teknis Platform</div>
                                            <div class="text-xs text-slate-500">Bug, error, atau masalah akun</div>
                                        </div>
                                        <i data-lucide="check" class="w-4 h-4 text-purple-400" x-show="category === 'technical'"></i>
                                    </button>

                                    <button type="button" @click="category = 'payment'; categoryOpen = false" 
                                        class="flex items-center gap-3 w-full px-4 py-3 text-sm text-left hover:bg-neutral-900 transition-colors"
                                        :class="category === 'payment' ? 'bg-neutral-900 text-white' : 'text-slate-400'">
                                        <i data-lucide="wallet" class="w-4 h-4 text-amber-400"></i>
                                        <div class="flex-1">
                                            <div class="font-semibold text-white">Keuangan / Pembayaran RPM</div>
                                            <div class="text-xs text-slate-500">Penarikan dana, billing, invoice</div>
                                        </div>
                                        <i data-lucide="check" class="w-4 h-4 text-amber-400" x-show="category === 'payment'"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Subjek -->
                        <div>
                            <label for="subject" class="block text-sm font-medium text-slate-300 mb-2">Subjek Pesan</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                    <i data-lucide="info" class="h-4 w-4 text-slate-500 group-focus-within:text-brand transition-colors"></i>
                                </div>
                                <input type="text" name="subject" id="subject" required
                                    value="{{ old('subject') }}"
                                    class="block w-full pl-10 pr-4 py-3 border border-neutral-800 rounded-xl leading-5 bg-black/40 text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand sm:text-sm transition-all"
                                    placeholder="cth. Pertanyaan kerja sama UGC">
                            </div>
                            @error('subject')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Pesan -->
                        <div>
                            <label for="message" class="block text-sm font-medium text-slate-300 mb-2">Pesan Anda</label>
                            <textarea name="message" id="message" rows="5" required
                                class="block w-full px-4 py-3 border border-neutral-800 rounded-xl leading-5 bg-black/40 text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-brand focus:border-brand sm:text-sm transition-all resize-none"
                                placeholder="Tuliskan detail pertanyaan atau kendala Anda di sini..."></textarea>
                            @error('message')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tombol Kirim -->
                        <div class="pt-2">
                            <button type="submit"
                                class="w-full flex justify-center items-center gap-2 py-3 px-4 border border-transparent rounded-xl shadow-lg shadow-brand/20 text-sm font-bold text-white bg-gradient-to-r from-brand to-brand hover:from-brand/90 hover:to-brand/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-neutral-900 focus:ring-brand transform transition-all active:scale-[0.98]">
                                <span x-show="!loading" class="flex items-center gap-2 justify-center">
                                    Kirim Pesan <i data-lucide="send" class="w-4 h-4"></i>
                                </span>
                                <span x-show="loading" style="display: none;" class="flex items-center gap-2 justify-center">
                                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Mengirimkan...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
