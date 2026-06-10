<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Clipfluence - Platform UGC & Clipper No. 1 di Indonesia</title>
    
    <!-- Memuat aset CSS & JS Laravel Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js untuk interaktivitas UI ringan seperti dropdown & mobile menu -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- AOS.js untuk animasi scroll yang smooth -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Lucide Icons (Ringan dan modern) -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    @php
        $adminSettings = [];

        if (\Illuminate\Support\Facades\Storage::disk('local')->exists('settings/admin.json')) {
            $decoded = json_decode(\Illuminate\Support\Facades\Storage::disk('local')->get('settings/admin.json'), true);
            $adminSettings = is_array($decoded) ? $decoded : [];
        }

        $appearance = $adminSettings['appearance'] ?? [];
        $primaryColor = $appearance['primary_color'] ?? '#6D28D9';
        $accentColor = $appearance['accent_color'] ?? '#A855F7';
    @endphp

    <style>
        :root {
            --admin-primary: {{ $primaryColor }};
            --admin-accent: {{ $accentColor }};
            --admin-primary-rgb: {{ implode(',', sscanf(ltrim($primaryColor, '#'), "%02x%02x%02x")) }};
            --admin-accent-rgb: {{ implode(',', sscanf(ltrim($accentColor, '#'), "%02x%02x%02x")) }};
        }

        /* Terapkan font Inter ke body */
        body { font-family: 'Inter', sans-serif; }
        
        /* Sembunyikan scrollbar untuk tampilan lebih clean */
        ::-webkit-scrollbar { display: none; } /* Chrome, Safari, dan Opera */
        * { -ms-overflow-style: none; scrollbar-width: none; } /* IE, Edge, dan Firefox */

        .bg-brand { background-color: var(--admin-primary) !important; }
        .text-brand { color: var(--admin-primary) !important; }
        .text-brand-light { color: var(--admin-accent) !important; }
        .border-brand { border-color: var(--admin-primary) !important; }
        .bg-brand\/10 { background-color: rgba(var(--admin-primary-rgb), 0.10) !important; }
        .bg-brand\/20 { background-color: rgba(var(--admin-primary-rgb), 0.20) !important; }
        .bg-brand\/30 { background-color: rgba(var(--admin-primary-rgb), 0.30) !important; }
        .bg-brand\/40 { background-color: rgba(var(--admin-primary-rgb), 0.40) !important; }
        .border-brand\/20 { border-color: rgba(var(--admin-primary-rgb), 0.20) !important; }
        .focus\:border-brand:focus { border-color: var(--admin-primary) !important; }
        .focus\:ring-brand:focus { --tw-ring-color: var(--admin-primary) !important; }
        .hover\:text-brand:hover { color: var(--admin-primary) !important; }
        .hover\:text-brand-light:hover { color: var(--admin-accent) !important; }
    </style>
</head>
<body class="bg-black text-slate-50 antialiased selection:bg-brand/30 selection:text-brand-light" x-data="{ activeTab: '{{ request()->query('tab') === 'creator' ? 'creator' : 'brand' }}' }">

    <!-- Memanggil komponen Bilah Navigasi Global -->
    @include('landing.partials.navbar')

    <!-- Konten Utama Halaman -->
    <main class="pb-24">
        @yield('content')
    </main>

    <!-- Memanggil komponen Catatan Kaki Global -->
    @include('landing.partials.footer')

    <!-- Tombol Kontak Floating (pojok kanan bawah) -->
    @include('partials.floating-contact')

    <!-- Inisialisasi library animasi AOS dan Lucide Icons -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Inisialisasi AOS langsung 
        AOS.init({
            once: false,  // Animasi diputar setiap elemen masuk viewport 
            mirror: true, // Animasi "bermain mundur" saat elemen dilewati agar saat scroll ke atas otomatis masuk lagi
            offset: 50, 
            duration: 800,
            easing: 'ease-out-cubic',
            anchorPlacement: 'top-bottom',
        });
        
        // Render semua icon lucide
        lucide.createIcons();

        // Refresh AOS persis setelah *semua* aset & font beres ter-load
        window.addEventListener('load', function() {
            AOS.refresh();
        });
    </script>
</body>
</html>
