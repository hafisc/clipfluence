<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selesaikan Profilmu - Clipfluence</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-black text-slate-50 min-h-screen flex items-center justify-center p-4 selection:bg-brand/30">
    
    <!-- Pattern Background Animasi Ringan -->
    <div class="fixed inset-0 z-0 opacity-20 pointer-events-none" style="background-image: radial-gradient(circle at 2px 2px, rgba(255,255,255,0.15) 1px, transparent 0); background-size: 32px 32px;"></div>
    
    <div class="relative z-10 w-full max-w-4xl">
        <div class="text-center mb-10 text-white animate-fade-in-up">
            <h1 class="text-3xl md:text-5xl font-extrabold mb-4 tracking-tight">Halo, {{ Auth::user()->name }}! 👋</h1>
            <p class="text-neutral-400 text-lg">Kamu login menggunakan akun Google. Untuk menyiapkan dashboard-mu, silakan pilih tipe akun.</p>
        </div>

        <form action="{{ route('onboarding.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6 relative">
            @csrf
            
            <!-- Card Merek -->
            <label class="group relative cursor-pointer" onclick="selectRole('brand')">
                <input type="radio" name="role" id="radio-brand" value="brand" class="hidden" required>
                <div id="card-brand" class="role-card h-full rounded-3xl border-2 border-neutral-800 bg-neutral-900/80 backdrop-blur p-8 transition-all duration-300 hover:border-neutral-600">
                    <div class="w-16 h-16 rounded-2xl bg-orange-500/20 flex items-center justify-center mb-6 text-orange-500 transition-transform group-hover:scale-110">
                        <i data-lucide="building-2" class="w-8 h-8"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-3">Saya Merek</h3>
                    <p class="text-neutral-400 leading-relaxed transition-colors">
                        Saya punya bisnis/produk dan ingin membayar konten kreator untuk mempromosikannya.
                    </p>
                    
                    <!-- Checkmark Icon -->
                    <div id="check-brand" class="absolute top-6 right-6 opacity-0 scale-50 transition-all duration-300 text-orange-500">
                        <i data-lucide="check-circle-2" class="w-8 h-8"></i>
                    </div>
                </div>
            </label>

            <!-- Card Kreator -->
            <label class="group relative cursor-pointer" onclick="selectRole('kreator')">
                <input type="radio" name="role" id="radio-kreator" value="kreator" class="hidden" required>
                <div id="card-kreator" class="role-card h-full rounded-3xl border-2 border-neutral-800 bg-neutral-900/80 backdrop-blur p-8 transition-all duration-300 hover:border-neutral-600">
                    <div class="w-16 h-16 rounded-2xl bg-violet-500/20 flex items-center justify-center mb-6 text-violet-500 transition-transform group-hover:scale-110">
                        <i data-lucide="clapperboard" class="w-8 h-8"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-3">Saya Kreator (Clipper)</h3>
                    <p class="text-neutral-400 leading-relaxed transition-colors">
                        Saya pandai membuat video pendek dan ingin mendapatkan komisi/uang dari mengerjakan kampanye.
                    </p>
                    
                    <!-- Checkmark Icon -->
                    <div id="check-kreator" class="absolute top-6 right-6 opacity-0 scale-50 transition-all duration-300 text-violet-500">
                        <i data-lucide="check-circle-2" class="w-8 h-8"></i>
                    </div>
                </div>
            </label>

            <div class="col-span-1 md:col-span-2 text-center mt-8">
                <button type="submit" class="bg-white text-black hover:bg-neutral-200 px-8 py-4 rounded-xl font-bold text-lg inline-flex items-center gap-2 transition-transform hover:-translate-y-1 hover:shadow-[0_0_20px_rgba(255,255,255,0.3)]">
                    Lanjutkan ke Dasbor <i data-lucide="arrow-right" class="w-5 h-5"></i>
                </button>
            </div>
        </form>
        
        <div class="text-center mt-8">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="text-neutral-500 hover:text-white text-sm transition-colors cursor-pointer">
                    Batal dan Keluar
                </button>
            </form>
        </div>
    </div>

    <script>
        lucide.createIcons();
        
        function selectRole(role) {
            // Reset semua card
            document.getElementById('card-brand').className = "role-card h-full rounded-3xl border-2 border-neutral-800 bg-neutral-900/80 backdrop-blur p-8 transition-all duration-300 hover:border-neutral-600";
            document.getElementById('card-kreator').className = "role-card h-full rounded-3xl border-2 border-neutral-800 bg-neutral-900/80 backdrop-blur p-8 transition-all duration-300 hover:border-neutral-600";
            
            // Reset icon
            document.getElementById('check-brand').classList.remove('opacity-100', 'scale-100');
            document.getElementById('check-brand').classList.add('opacity-0', 'scale-50');
            document.getElementById('check-kreator').classList.remove('opacity-100', 'scale-100');
            document.getElementById('check-kreator').classList.add('opacity-0', 'scale-50');

            // Set state yang aktif (berubah warna & border)
            if (role === 'brand') {
                document.getElementById('radio-brand').checked = true;
                const card = document.getElementById('card-brand');
                card.classList.remove('border-neutral-800', 'bg-neutral-900/80');
                card.classList.add('border-orange-500', 'bg-orange-500/10');
                
                document.getElementById('check-brand').classList.remove('opacity-0', 'scale-50');
                document.getElementById('check-brand').classList.add('opacity-100', 'scale-100');
            } else if (role === 'kreator') {
                document.getElementById('radio-kreator').checked = true;
                const card = document.getElementById('card-kreator');
                card.classList.remove('border-neutral-800', 'bg-neutral-900/80');
                card.classList.add('border-violet-500', 'bg-violet-500/10');
                
                document.getElementById('check-kreator').classList.remove('opacity-0', 'scale-50');
                document.getElementById('check-kreator').classList.add('opacity-100', 'scale-100');
            }
        }
    </script>
</body>
</html>
