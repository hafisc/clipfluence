<!-- Section Hero untuk Kreator / Clipper -->
<section class="relative pt-12 pb-24 overflow-hidden">
    <!-- Glow Ornamen Background -->
    <div class="absolute top-1/4 left-1/10 w-[500px] h-[500px] opacity-20 pointer-events-none">
        <div class="absolute inset-0 bg-brand rounded-full blur-[120px] mix-blend-screen animate-pulse" style="animation-duration: 8s;"></div>
    </div>
    <div class="absolute bottom-1/4 right-1/10 w-[600px] h-[600px] opacity-15 pointer-events-none">
        <div class="absolute inset-0 bg-brand-light rounded-full blur-[150px] mix-blend-screen animate-pulse" style="animation-duration: 12s;"></div>
    </div>

    <!-- Garis Grid Latar Belakang -->
    <div class="absolute inset-0 pointer-events-none bg-[linear-gradient(to_right,#262626_1px,transparent_1px),linear-gradient(to_bottom,#262626_1px,transparent_1px)] bg-[size:4rem_4rem] [mask-image:radial-gradient(ellipse_60%_50%_at_50%_40%,#000_70%,transparent_100%)] opacity-10"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 items-center">
            
            <!-- Kolom Kiri: Teks & Informasi Utama -->
            <div class="lg:col-span-7 space-y-8 text-center lg:text-left">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-brand/10 border border-brand/20 text-brand-light text-xs font-semibold tracking-wider uppercase mb-2">
                    <span class="w-2 h-2 rounded-full bg-brand animate-ping"></span> Clipfluence untuk Clipper
                </div>
                
                <h1 class="text-4xl sm:text-5xl md:text-6xl font-black tracking-tight text-white leading-tight">
                    Ladang Cuan Baru<br>
                    Buat <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand to-brand-light">Clipper di Indonesia.</span>
                </h1>
                
                <p class="text-slate-400 text-lg md:text-xl leading-relaxed max-w-2xl mx-auto lg:mx-0">
                    Join Komunitas Gratis, Gabung Sekarang! Edit video menarik dari brand, posting di akun media sosial Anda, dan cairkan pendapatan real-time dari views yang terkumpul.
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start pt-4">
                    <a href="/register?role=creator" class="w-full sm:w-auto px-8 py-4 rounded-xl bg-gradient-to-r from-brand to-brand-hover text-white font-bold shadow-lg shadow-brand/30 hover:shadow-brand/50 hover:scale-105 transition-all duration-300 flex items-center justify-center gap-2">
                        <i data-lucide="clapperboard" class="w-5 h-5"></i> Gabung Sekarang!
                    </a>
                    <a href="#langkah" class="w-full sm:w-auto px-8 py-4 rounded-xl bg-neutral-900 border border-neutral-800 text-slate-300 hover:text-white hover:bg-neutral-800 hover:border-neutral-700 transition-all font-semibold flex items-center justify-center gap-2">
                        Bagaimana Caranya? <i data-lucide="arrow-down" class="w-4 h-4"></i>
                    </a>
                </div>
            </div>
            
            <!-- Kolom Kanan: Mockup Wallet / Clipper Visual (Sesuai Gambar 3) -->
            <div class="lg:col-span-5 relative flex justify-center items-center">
                <!-- Outer Glow -->
                <div class="absolute inset-0 bg-brand/20 rounded-full blur-[80px] pointer-events-none"></div>
                
                <!-- Mockup Dompet Clipper -->
                <div class="relative w-full max-w-md bg-neutral-900/60 backdrop-blur-xl border border-neutral-800 rounded-3xl p-6 shadow-2xl overflow-hidden group">
                    <div class="absolute top-0 inset-x-0 h-px bg-gradient-to-r from-transparent via-brand/40 to-transparent"></div>
                    
                    <!-- Wallet Header -->
                    <div class="flex items-center justify-between border-b border-neutral-800 pb-4 mb-6">
                        <span class="text-xs font-bold text-slate-400 flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-yellow-500 animate-pulse"></span> Dompet Clipper
                        </span>
                        <span class="text-[10px] bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-2.5 py-1 rounded-full font-bold">Terverifikasi</span>
                    </div>

                    <!-- Saldo & Aset -->
                    <div class="space-y-6">
                        <div class="bg-neutral-950/80 border border-neutral-800/80 p-4 rounded-2xl">
                            <p class="text-xs font-semibold text-slate-400">Total Saldo Terkumpul</p>
                            <div class="flex items-baseline justify-between mt-2">
                                <span class="text-2xl font-black text-white tracking-tight">Rp 45.200.000</span>
                                <span class="text-xs text-brand font-bold">IDR Wallet</span>
                            </div>
                        </div>

                        <!-- Info Detail Penarikan -->
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-neutral-950/50 border border-neutral-800/80 p-3.5 rounded-xl">
                                <span class="text-[10px] text-slate-500 font-semibold block uppercase">Berhasil Ditarik</span>
                                <span class="text-sm font-bold text-white mt-1 block">Rp 12.450.000</span>
                            </div>
                            <div class="bg-neutral-950/50 border border-neutral-800/80 p-3.5 rounded-xl">
                                <span class="text-[10px] text-slate-500 font-semibold block uppercase">Total Pendapatan</span>
                                <span class="text-sm font-bold text-emerald-400 mt-1 block">Rp 57.650.000</span>
                            </div>
                        </div>

                        <!-- Progress Bar untuk Payout -->
                        <div class="space-y-2">
                            <div class="flex justify-between text-[11px] text-slate-400">
                                <span>Batas Minimum Penarikan</span>
                                <span class="text-white font-bold">Rp 100.000 / Rp 100.000 (100%)</span>
                            </div>
                            <div class="w-full bg-neutral-950 rounded-full h-2 overflow-hidden border border-neutral-800">
                                <div class="bg-gradient-to-r from-brand to-brand-light h-full rounded-full w-full"></div>
                            </div>
                        </div>

                        <!-- Action Button -->
                        <button class="w-full py-3 bg-gradient-to-r from-brand to-brand-hover text-white text-xs font-bold rounded-xl shadow-lg shadow-brand/20 flex items-center justify-center gap-2">
                            <i data-lucide="wallet" class="w-4 h-4"></i> Tarik Dana (Cairkan Instan)
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Section 2: 3 Langkah (Sesuai Gambar 3) -->
<section id="langkah" class="py-24 border-t border-neutral-900 bg-neutral-950/30">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-20">
            <span class="text-xs bg-brand/10 border border-brand/20 text-brand px-4 py-2 rounded-full font-bold uppercase tracking-wider">Langkah Alur</span>
            <h2 class="text-3xl md:text-5xl font-black text-white mt-6 mb-4">3 Langkah Doang.<br>Tinggal Posting, Cuan Ngalir.</h2>
            <p class="text-slate-400 text-lg">Hanya membutuhkan tiga langkah sederhana untuk mulai menghasilkan pendapatan harian.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Langkah 1 -->
            <div class="bg-neutral-900/40 border border-neutral-800/80 p-8 rounded-3xl relative overflow-hidden group">
                <span class="text-5xl font-black text-brand/20 absolute -top-2 -right-2 transition-transform duration-300 group-hover:scale-125">01</span>
                <div class="w-12 h-12 rounded-xl bg-brand/10 border border-brand/20 flex items-center justify-center mb-6 text-brand">
                    <i data-lucide="search" class="w-5 h-5"></i>
                </div>
                <h3 class="text-xl font-bold text-white mb-3">1. Pilih Campaign</h3>
                <p class="text-slate-400 text-sm leading-relaxed">Cari campaign dari merek ternama yang cocok dengan niche atau kategori video audiens media sosial Anda.</p>
            </div>

            <!-- Langkah 2 -->
            <div class="bg-neutral-900/40 border border-neutral-800/80 p-8 rounded-3xl relative overflow-hidden group">
                <span class="text-5xl font-black text-brand/20 absolute -top-2 -right-2 transition-transform duration-300 group-hover:scale-125">02</span>
                <div class="w-12 h-12 rounded-xl bg-cyan-500/10 border border-cyan-500/20 flex items-center justify-center mb-6 text-cyan-400">
                    <i data-lucide="scissors" class="w-5 h-5"></i>
                </div>
                <h3 class="text-xl font-bold text-white mb-3">2. Potong & Edit Video</h3>
                <p class="text-slate-400 text-sm leading-relaxed">Kreasikan potongan klip video produk semenarik dan sekreatif mungkin untuk memicu minat tontonan tinggi.</p>
            </div>

            <!-- Langkah 3 -->
            <div class="bg-neutral-900/40 border border-neutral-800/80 p-8 rounded-3xl relative overflow-hidden group">
                <span class="text-5xl font-black text-brand/20 absolute -top-2 -right-2 transition-transform duration-300 group-hover:scale-125">03</span>
                <div class="w-12 h-12 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center mb-6 text-emerald-400">
                    <i data-lucide="share-2" class="w-5 h-5"></i>
                </div>
                <h3 class="text-xl font-bold text-white mb-3">3. Posting dan Klaim Views</h3>
                <p class="text-slate-400 text-sm leading-relaxed">Posting di akun TikTok/YouTube/Instagram Anda, lalu tautkan ke platform kami untuk mulai merekam cuan Anda.</p>
            </div>
        </div>
    </div>
</section>

<!-- Section 3: Stat Pool Budget & Top Clippers (Sesuai Gambar 3) -->
<section class="py-24 border-t border-neutral-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-neutral-900/60 border border-neutral-800 p-8 md:p-12 rounded-3xl flex flex-col md:flex-row gap-10 items-center justify-between">
            <div class="space-y-4 max-w-xl text-center md:text-left">
                <span class="text-[10px] bg-brand/10 border border-brand/20 text-brand px-3 py-1 rounded-full font-bold uppercase tracking-wider">Total Pool Budget</span>
                <h2 class="text-3xl md:text-5xl font-black text-white leading-tight">Total Budget 500 Juta<br>Lagi Nunggu untuk Kamu Clip.</h2>
                <p class="text-slate-400 text-sm">Dana ini dialokasikan khusus dari Merek-merek aktif untuk dibagikan langsung ke Clipper berdasarkan tayangan valid.</p>
            </div>
            <div class="flex-shrink-0 bg-neutral-950 border border-neutral-800 p-6 rounded-2xl text-center shadow-xl shadow-black/40 min-w-[280px]">
                <p class="text-xs text-slate-500 font-bold uppercase tracking-wider">Rata-rata Pendapatan / Bulan</p>
                <p class="text-4xl font-black text-emerald-400 mt-2">Rp 8.5M+</p>
                <div class="border-t border-neutral-800 mt-4 pt-3 flex items-center justify-center gap-2 text-[11px] text-slate-400">
                    <i data-lucide="info" class="w-3.5 h-3.5 text-brand"></i> Bergabunglah dengan 4,200+ Clipper
                </div>
            </div>
        </div>

        <!-- Top Clippers Showcase -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12">
            <!-- Clipper 1 -->
            <div class="bg-neutral-900/40 border border-neutral-800/80 p-6 rounded-2xl flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-full bg-brand/20 flex items-center justify-center font-bold text-brand">H</div>
                    <div>
                        <h4 class="text-sm font-bold text-white">Hafis Al Hafid</h4>
                        <p class="text-[10px] text-slate-500">Clipper @hafis_clip</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-[10px] text-slate-500 font-bold">Total Penarikan</p>
                    <p class="text-sm font-bold text-emerald-400">Rp 25.400.000</p>
                </div>
            </div>

            <!-- Clipper 2 -->
            <div class="bg-neutral-900/40 border border-neutral-800/80 p-6 rounded-2xl flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-full bg-cyan-500/20 flex items-center justify-center font-bold text-cyan-400">A</div>
                    <div>
                        <h4 class="text-sm font-bold text-white">Ahmad Rofi</h4>
                        <p class="text-[10px] text-slate-500">Clipper @rofi_clippers</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-[10px] text-slate-500 font-bold">Total Penarikan</p>
                    <p class="text-sm font-bold text-emerald-400">Rp 18.250.000</p>
                </div>
            </div>

            <!-- Clipper 3 -->
            <div class="bg-neutral-900/40 border border-neutral-800/80 p-6 rounded-2xl flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-full bg-emerald-500/20 flex items-center justify-center font-bold text-emerald-400">N</div>
                    <div>
                        <h4 class="text-sm font-bold text-white">Novianti</h4>
                        <p class="text-[10px] text-slate-500">Clipper @novi_ugc</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-[10px] text-slate-500 font-bold">Total Penarikan</p>
                    <p class="text-sm font-bold text-emerald-400">Rp 12.100.000</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Section 4: Golden Premium Card Visual (Sesuai Gambar 3) -->
<section class="py-24 border-t border-neutral-900 bg-neutral-950/20">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="relative overflow-hidden bg-gradient-to-br from-neutral-900 to-neutral-950 border border-neutral-800 rounded-3xl p-8 md:p-16 shadow-2xl flex flex-col md:flex-row gap-12 items-center group">
            <!-- Background Glow -->
            <div class="absolute -top-24 -left-24 w-48 h-48 bg-brand/20 rounded-full blur-[80px] pointer-events-none group-hover:scale-150 transition-all duration-500"></div>
            <div class="absolute -bottom-24 -right-24 w-48 h-48 bg-brand-light/10 rounded-full blur-[80px] pointer-events-none group-hover:scale-150 transition-all duration-500"></div>

            <!-- Left side: Golden Card visual -->
            <div class="w-full md:w-2/5 flex justify-center">
                <div class="w-64 h-96 rounded-2xl bg-gradient-to-br from-neutral-900 to-neutral-950 border border-brand/30 p-6 flex flex-col justify-between shadow-2xl relative overflow-hidden group shadow-brand/10 hover:shadow-brand/20 transition-all">
                    <!-- Glow reflection -->
                    <div class="absolute inset-0 bg-gradient-to-tr from-brand/10 via-transparent to-transparent"></div>
                    
                    <div class="flex items-center justify-between relative z-10">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">Clipfluence Gold</span>
                        <div class="w-5 h-5 rounded-full bg-brand"></div>
                    </div>
                    
                    <div class="my-auto relative z-10 text-center">
                        <p class="text-[10px] text-slate-500 uppercase font-black tracking-widest">Penghasilan Tertinggi</p>
                        <p class="text-4xl font-black text-white mt-1">+RP 88 JT</p>
                        <p class="text-xs text-brand mt-2">Diterima oleh @rudi_ugc</p>
                    </div>

                    <div class="relative z-10 border-t border-neutral-800/80 pt-4 flex items-center justify-between text-[10px] text-slate-500">
                        <span>EST. 2026</span>
                        <span>CLIPPER MEMBER</span>
                    </div>
                </div>
            </div>

            <!-- Right side: Text details -->
            <div class="w-full md:w-3/5 space-y-6 text-center md:text-left">
                <span class="text-xs bg-brand/10 border border-brand/20 text-brand px-4 py-2 rounded-full font-bold uppercase tracking-wider">Transparansi Real-Time</span>
                <h2 class="text-3xl md:text-5xl font-black text-white leading-tight">Saatnya Clip Kamu Kerja Buat Kamu.</h2>
                <p class="text-slate-400 leading-relaxed text-sm md:text-base">
                    Sistem pelacakan kami terhubung langsung dengan API media sosial. Angka penayangan diperbarui secara transparan, dan setiap pendapatan yang diperoleh dapat langsung dilacak dan dicairkan kapan pun.
                </p>
                <div class="flex gap-6 justify-center md:justify-start text-sm">
                    <div class="flex items-center gap-2 text-slate-300">
                        <i data-lucide="check-circle" class="w-4 h-4 text-emerald-400"></i> No Admin Fee
                    </div>
                    <div class="flex items-center gap-2 text-slate-300">
                        <i data-lucide="check-circle" class="w-4 h-4 text-emerald-400"></i> 100% Amanah
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Section 5: Frequently Asked Questions (Clipper) -->
<section class="py-24 border-t border-neutral-900 bg-neutral-950/20">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="text-xs bg-brand/10 border border-brand/20 text-brand px-4 py-2 rounded-full font-bold uppercase tracking-wider">Tanya Jawab</span>
            <h2 class="text-3xl font-black text-white mt-6 mb-4">Frequently Asked Questions</h2>
        </div>

        <div class="space-y-4" x-data="{ activeFaq: null }">
            <!-- FAQ 1 -->
            <div class="border border-neutral-800 rounded-2xl bg-neutral-900/30 overflow-hidden">
                <button @click="activeFaq = (activeFaq === 1 ? null : 1)" class="w-full flex justify-between items-center px-6 py-5 text-left text-white font-semibold hover:bg-neutral-900/50 transition-colors">
                    <span>Apakah pendaftaran komunitas ini benar-benar gratis?</span>
                    <i data-lucide="chevron-down" class="w-5 h-5 text-slate-500 transition-transform" :class="activeFaq === 1 ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="activeFaq === 1" x-collapse class="px-6 pb-5 text-sm text-slate-400 border-t border-neutral-800/60 pt-4 leading-relaxed" style="display: none;">
                    Ya, 100% gratis! Kami tidak memungut biaya pendaftaran atau iuran bulanan apa pun. Anda hanya perlu mendaftar, memilih campaign, memotong video, dan mulai mengklaim pendapatan dari hasil views Anda.
                </div>
            </div>

            <!-- FAQ 2 -->
            <div class="border border-neutral-800 rounded-2xl bg-neutral-900/30 overflow-hidden">
                <button @click="activeFaq = (activeFaq === 2 ? null : 2)" class="w-full flex justify-between items-center px-6 py-5 text-left text-white font-semibold hover:bg-neutral-900/50 transition-colors">
                    <span>Kapan saya bisa menarik saldo pendapatan saya?</span>
                    <i data-lucide="chevron-down" class="w-5 h-5 text-slate-500 transition-transform" :class="activeFaq === 2 ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="activeFaq === 2" x-collapse class="px-6 pb-5 text-sm text-slate-400 border-t border-neutral-800/60 pt-4 leading-relaxed" style="display: none;">
                    Anda dapat mengajukan penarikan dana kapan pun setelah saldo Anda mencapai batas minimum penarikan sebesar Rp 100.000. Proses pencairan dana ke rekening bank atau e-wallet (DANA, OVO, GoPay) diproses secara instan atau dalam kurun waktu kurang dari 24 jam.
                </div>
            </div>

            <!-- FAQ 3 -->
            <div class="border border-neutral-800 rounded-2xl bg-neutral-900/30 overflow-hidden">
                <button @click="activeFaq = (activeFaq === 3 ? null : 3)" class="w-full flex justify-between items-center px-6 py-5 text-left text-white font-semibold hover:bg-neutral-900/50 transition-colors">
                    <span>Apakah saya boleh memposting video di banyak media sosial?</span>
                    <i data-lucide="chevron-down" class="w-5 h-5 text-slate-500 transition-transform" :class="activeFaq === 3 ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="activeFaq === 3" x-collapse class="px-6 pb-5 text-sm text-slate-400 border-t border-neutral-800/60 pt-4 leading-relaxed" style="display: none;">
                    Tentu saja! Anda dibebaskan memposting klip video kampanye di berbagai platform jejaring sosial seperti TikTok, Instagram Reels, maupun YouTube Shorts untuk melipatgandakan jumlah penayangan dan memaksimalkan cuan Anda.
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Section 6: CTA Akhir (Clipper) -->
<section class="py-24 border-t border-neutral-900 relative overflow-hidden bg-gradient-to-b from-black via-brand/10 to-black">
    <div class="max-w-4xl mx-auto px-4 relative z-10 text-center">
        <h2 class="text-3xl md:text-5xl font-black text-white mb-6">Mulai Hasilkan Cuan Pertama Anda Sekarang.</h2>
        <p class="text-slate-400 text-lg mb-10 max-w-2xl mx-auto">Gabung dengan ribuan clipper lainnya di Indonesia yang sudah sukses mendulang rupiah dari hobi editing mereka.</p>
        <a href="/register?role=creator" class="inline-flex justify-center items-center gap-2 px-8 py-4 rounded-xl bg-gradient-to-r from-brand to-brand-hover text-white font-bold hover:shadow-lg hover:shadow-brand/30 transition-all duration-300 hover:scale-105">
            Gabung Jadi Clipper <i data-lucide="clapperboard" class="w-5 h-5"></i>
        </a>
    </div>
</section>
