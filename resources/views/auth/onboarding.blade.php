<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Selesaikan Profilmu - Clipfluence</title>
    
    <!-- Memuat aset CSS & JS Laravel Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Lucide Icons & Alpine.js dengan plugin collapse -->
    <script src="https://unpkg.com/lucide@0.395.0"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    @php
        $adminSettings = [];

        if (\Illuminate\Support\Facades\Storage::disk('local')->exists('settings/admin.json')) {
            $decoded = json_decode(\Illuminate\Support\Facades\Storage::disk('local')->get('settings/admin.json'), true);
            $adminSettings = is_array($decoded) ? $decoded : [];
        }

        $appearance = $adminSettings['appearance'] ?? [];
        $primaryColor = $appearance['primary_color'] ?? '#6D28D9';
        $accentColor = $appearance['accent_color'] ?? '#A855F7';
        $primaryColorRgb = implode(',', sscanf(ltrim($primaryColor, '#'), "%02x%02x%02x"));
    @endphp

    <style>
        :root {
            --admin-primary: {{ $primaryColor }};
            --admin-accent: {{ $accentColor }};
            --admin-primary-rgb: {{ $primaryColorRgb }};
        }

        body { font-family: 'Inter', sans-serif; }

        .bg-brand { background-color: var(--admin-primary) !important; }
        .text-brand { color: var(--admin-primary) !important; }
        .text-brand-light { color: var(--admin-accent) !important; }
        .border-brand { border-color: var(--admin-primary) !important; }
        .bg-brand\/5 { background-color: rgba(var(--admin-primary-rgb), 0.05) !important; }
        .bg-brand\/10 { background-color: rgba(var(--admin-primary-rgb), 0.10) !important; }
        .bg-brand\/20 { background-color: rgba(var(--admin-primary-rgb), 0.20) !important; }
        .focus\:ring-brand:focus { --tw-ring-color: var(--admin-primary) !important; }
    </style>
</head>
<body class="bg-black text-slate-50 antialiased min-h-screen relative flex items-center justify-center p-4 md:p-8 selection:bg-brand/30 selection:text-brand-light" x-data="{ selectedRole: '' }">

    <!-- Ornamen Background Geometris & Glowing Radial -->
    <div class="fixed inset-0 pointer-events-none z-0 overflow-hidden">
        <!-- Grid pattern -->
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#1e293b_1px,transparent_1px),linear-gradient(to_bottom,#1e293b_1px,transparent_1px)] bg-[size:3rem_3rem] [mask-image:radial-gradient(ellipse_60%_60%_at_50%_50%,#000_70%,transparent_100%)] opacity-25"></div>
        
        <!-- Dynamic glows -->
        <div class="absolute -top-40 left-1/4 w-[500px] h-[500px] rounded-full blur-[120px] mix-blend-screen transition-all duration-700"
             :class="selectedRole === 'brand' ? 'bg-emerald-500/20 opacity-100' : (selectedRole === 'kreator' ? 'bg-brand/10 opacity-50' : 'bg-brand/15 opacity-60')"></div>
        <div class="absolute -bottom-40 right-1/4 w-[500px] h-[500px] rounded-full blur-[120px] mix-blend-screen transition-all duration-700"
             :class="selectedRole === 'kreator' ? 'bg-brand/25 opacity-100' : (selectedRole === 'brand' ? 'bg-emerald-500/10 opacity-50' : 'bg-brand/10 opacity-60')"></div>
    </div>

    <!-- Tombol Batal & Keluar (Pojok Kiri Atas) -->
    <div class="absolute top-6 left-6 z-20">
        <form action="{{ route('logout') }}" method="POST" id="logout-form">
            @csrf
            <button type="submit" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-400 hover:text-white transition-all duration-300 group px-4 py-2 rounded-full bg-neutral-900/60 border border-neutral-800 hover:border-neutral-700/80 backdrop-blur-md shadow-sm">
                <i data-lucide="log-out" class="w-3.5 h-3.5 group-hover:-translate-x-0.5 transition-transform duration-300"></i> Keluar
            </button>
        </form>
    </div>

    <div class="relative z-10 w-full max-w-4xl py-12">
        <!-- Logo & Header -->
        <div class="flex flex-col items-center justify-center mb-12 text-center">
            <div class="flex items-center justify-center gap-3 mb-6">
                <img src="{{ asset('images/brand/logo-icon.png') }}" alt="Clipfluence" class="w-12 h-12 object-contain drop-shadow-[0_0_16px_rgba(139,92,246,0.6)]">
                <span class="font-black text-2xl tracking-tight text-white pt-1">Clip<span class="text-transparent bg-clip-text bg-gradient-to-r from-violet-400 to-fuchsia-400">fluence</span></span>
            </div>
            
            <h1 class="text-3xl md:text-5xl font-black mb-4 tracking-tight leading-tight">
                Halo, <span class="text-transparent bg-clip-text bg-gradient-to-r from-violet-400 via-purple-300 to-emerald-400">{{ Auth::user()->name }}</span>! 👋
            </h1>
            <p class="text-slate-400 text-base md:text-lg max-w-2xl">
                Anda berhasil masuk menggunakan Google. Silakan pilih tipe akun Anda di bawah ini untuk menyiapkan dasbor Anda.
            </p>
        </div>

        <form action="{{ route('onboarding.store') }}" method="POST" class="space-y-10">
            @csrf
            
            <!-- Cards container -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- Brand Card -->
                <label class="group relative cursor-pointer block h-full">
                    <input type="radio" name="role" id="radio-brand" value="brand" x-model="selectedRole" class="sr-only" required>
                    
                    <div class="h-full rounded-3xl border-2 p-8 transition-all duration-500 bg-neutral-900/60 backdrop-blur-xl relative overflow-hidden flex flex-col justify-between"
                         :class="selectedRole === 'brand' ? 'border-emerald-500 shadow-[0_0_30px_rgba(16,185,129,0.15)] bg-emerald-950/10' : 'border-neutral-800/80 hover:border-neutral-700/80 hover:bg-neutral-800/20'">
                        
                        <!-- Top glow overlay -->
                        <div class="absolute top-0 inset-x-0 h-px bg-gradient-to-r from-transparent via-emerald-500/50 to-transparent transition-opacity duration-500"
                             :class="selectedRole === 'brand' ? 'opacity-100' : 'opacity-0'"></div>
                        
                        <div>
                            <!-- Icon Bubble -->
                            <div class="w-16 h-16 rounded-2xl flex items-center justify-center mb-6 transition-all duration-500"
                                 :class="selectedRole === 'brand' ? 'bg-emerald-500/20 text-emerald-400 scale-110 shadow-lg shadow-emerald-500/10' : 'bg-neutral-800/80 text-slate-400 group-hover:scale-110 group-hover:bg-neutral-700'">
                                <i data-lucide="building-2" class="w-8 h-8"></i>
                            </div>
                            
                            <h3 class="text-2xl font-extrabold text-white mb-3">
                                Saya Brand
                            </h3>
                            <p class="text-slate-400 text-sm leading-relaxed mb-6">
                                Saya memiliki bisnis/produk dan ingin bekerja sama dengan Clipper untuk mempromosikannya.
                            </p>
                            
                            <!-- Features Checklist -->
                            <ul class="space-y-3 border-t border-neutral-800/60 pt-5">
                                <li class="flex items-start gap-3 text-xs text-slate-300">
                                    <span class="flex-shrink-0 w-4 h-4 rounded-full bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400 mt-0.5">
                                        <i data-lucide="check" class="w-2.5 h-2.5"></i>
                                    </span>
                                    <span>Buat kampanye UGC video dengan praktis</span>
                                </li>
                                <li class="flex items-start gap-3 text-xs text-slate-300">
                                    <span class="flex-shrink-0 w-4 h-4 rounded-full bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400 mt-0.5">
                                        <i data-lucide="check" class="w-2.5 h-2.5"></i>
                                    </span>
                                    <span>Sistem pembayaran aman (Secured Escrow)</span>
                                </li>
                                <li class="flex items-start gap-3 text-xs text-slate-300">
                                    <span class="flex-shrink-0 w-4 h-4 rounded-full bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400 mt-0.5">
                                        <i data-lucide="check" class="w-2.5 h-2.5"></i>
                                    </span>
                                    <span>Pantau metrik performa secara real-time</span>
                                </li>
                            </ul>
                        </div>
                        
                        <!-- Checkmark Indicator -->
                        <div class="absolute top-6 right-6 transition-all duration-500 text-emerald-400"
                             :class="selectedRole === 'brand' ? 'opacity-100 scale-100' : 'opacity-0 scale-50'">
                            <i data-lucide="check-circle-2" class="w-8 h-8 fill-emerald-500/10"></i>
                        </div>
                    </div>
                </label>

                <!-- Clipper Card -->
                <label class="group relative cursor-pointer block h-full">
                    <input type="radio" name="role" id="radio-kreator" value="kreator" x-model="selectedRole" class="sr-only" required>
                    
                    <div class="h-full rounded-3xl border-2 p-8 transition-all duration-500 bg-neutral-900/60 backdrop-blur-xl relative overflow-hidden flex flex-col justify-between"
                         :class="selectedRole === 'kreator' ? 'border-brand shadow-[0_0_30px_rgba(109,40,217,0.15)] bg-brand/5' : 'border-neutral-800/80 hover:border-neutral-700/80 hover:bg-neutral-800/20'">
                        
                        <!-- Top glow overlay -->
                        <div class="absolute top-0 inset-x-0 h-px bg-gradient-to-r from-transparent via-brand/50 to-transparent transition-opacity duration-500"
                             :class="selectedRole === 'kreator' ? 'opacity-100' : 'opacity-0'"></div>
                        
                        <div>
                            <!-- Icon Bubble -->
                            <div class="w-16 h-16 rounded-2xl flex items-center justify-center mb-6 transition-all duration-500"
                                 :class="selectedRole === 'kreator' ? 'bg-brand/20 text-brand-light scale-110 shadow-lg shadow-brand/10' : 'bg-neutral-800/80 text-slate-400 group-hover:scale-110 group-hover:bg-neutral-700'">
                                <i data-lucide="scissors" class="w-8 h-8"></i>
                            </div>
                            
                            <h3 class="text-2xl font-extrabold text-white mb-3">
                                Saya Clipper
                            </h3>
                            <p class="text-slate-400 text-sm leading-relaxed mb-6">
                                Saya pandai membuat video pendek dan ingin mendapatkan komisi/uang dengan mempromosikan kampanye brand.
                            </p>
                            
                            <!-- Features Checklist -->
                            <ul class="space-y-3 border-t border-neutral-800/60 pt-5">
                                <li class="flex items-start gap-3 text-xs text-slate-300">
                                    <span class="flex-shrink-0 w-4 h-4 rounded-full bg-brand/10 border border-brand/20 flex items-center justify-center text-brand mt-0.5">
                                        <i data-lucide="check" class="w-2.5 h-2.5"></i>
                                    </span>
                                    <span>Akses brief kampanye brand ternama</span>
                                </li>
                                <li class="flex items-start gap-3 text-xs text-slate-300">
                                    <span class="flex-shrink-0 w-4 h-4 rounded-full bg-brand/10 border border-brand/20 flex items-center justify-center text-brand mt-0.5">
                                        <i data-lucide="check" class="w-2.5 h-2.5"></i>
                                    </span>
                                    <span>Hasilkan komisi dari views (RPM sistem)</span>
                                </li>
                                <li class="flex items-start gap-3 text-xs text-slate-300">
                                    <span class="flex-shrink-0 w-4 h-4 rounded-full bg-brand/10 border border-brand/20 flex items-center justify-center text-brand mt-0.5">
                                        <i data-lucide="check" class="w-2.5 h-2.5"></i>
                                    </span>
                                    <span>Penarikan dana instan & transparan</span>
                                </li>
                            </ul>
                        </div>
                        
                        <!-- Checkmark Indicator -->
                        <div class="absolute top-6 right-6 transition-all duration-500 text-brand-light"
                             :class="selectedRole === 'kreator' ? 'opacity-100 scale-100' : 'opacity-0 scale-50'">
                            <i data-lucide="check-circle-2" class="w-8 h-8 fill-brand/10"></i>
                        </div>
                    </div>
                </label>
            </div>
            
            <!-- Submit Button (Dinamis & Premium) -->
            <div class="text-center mt-8">
                <!-- State: Belum memilih -->
                <div x-show="selectedRole === ''" x-transition class="inline-block w-full max-w-sm">
                    <button type="button" class="w-full py-4 px-8 border border-neutral-800 rounded-2xl font-bold text-sm text-slate-500 bg-neutral-900/50 cursor-not-allowed transition-all duration-300">
                        Pilih Tipe Akun Terlebih Dahulu
                    </button>
                </div>
                
                <!-- State: Brand Terpilih -->
                <div x-show="selectedRole === 'brand'" x-transition.duration.300ms style="display: none;" class="inline-block w-full max-w-sm">
                    <button type="submit" class="w-full flex justify-center items-center gap-2 py-4 px-8 border border-transparent rounded-2xl shadow-xl shadow-emerald-500/20 text-sm font-extrabold text-white bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-black focus:ring-emerald-500 transform transition-all hover:-translate-y-0.5 active:translate-y-0 duration-300">
                        Lanjutkan Sebagai Brand <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </button>
                </div>
                
                <!-- State: Clipper Terpilih -->
                <div x-show="selectedRole === 'kreator'" x-transition.duration.300ms style="display: none;" class="inline-block w-full max-w-sm">
                    <button type="submit" class="w-full flex justify-center items-center gap-2 py-4 px-8 border border-transparent rounded-2xl shadow-xl shadow-brand/20 text-sm font-extrabold text-white bg-gradient-to-r from-brand to-brand hover:from-brand-hover hover:to-brand-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-black focus:ring-brand transform transition-all hover:-translate-y-0.5 active:translate-y-0 duration-300">
                        Lanjutkan Sebagai Clipper <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Init Icons -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
        });
    </script>
</body>
</html>
