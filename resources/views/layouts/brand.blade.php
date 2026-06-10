<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dasbor') - Merek Clipfluence</title>
    <link rel="icon" type="image/png" href="{{ asset('images/brand/logo-icon.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/brand/logo-icon.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@0.395.0" defer></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

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
        }

        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
        ::-webkit-scrollbar { width: 4px; height: 4px; }
        ::-webkit-scrollbar-track { background: #0a0a0a; }
        ::-webkit-scrollbar-thumb { background: #27272a; border-radius: 99px; }
        ::-webkit-scrollbar-thumb:hover { background: #3f3f46; }

        .bg-brand { background-color: var(--admin-primary) !important; }
        .text-brand { color: var(--admin-primary) !important; }
        .text-brand-light { color: var(--admin-accent) !important; }
        .border-brand { border-color: var(--admin-primary) !important; }
        .bg-brand\/10 { background-color: rgba(var(--admin-primary-rgb), 0.10) !important; }
        .bg-brand\/20 { background-color: rgba(var(--admin-primary-rgb), 0.20) !important; }
        .border-brand\/20 { border-color: rgba(var(--admin-primary-rgb), 0.20) !important; }
        .focus\:border-brand:focus { border-color: var(--admin-primary) !important; }
        .focus\:ring-brand:focus { --tw-ring-color: var(--admin-primary) !important; }
        .hover\:text-brand:hover { color: var(--admin-primary) !important; }
        .hover\:text-brand-light:hover { color: var(--admin-accent) !important; }
    </style>
    @stack('styles')
</head>
<body class="bg-[#080808] text-slate-50 antialiased h-full" x-data="{ sidebarOpen: false }">

    <!-- Mobile sidebar backdrop -->
    <div x-show="sidebarOpen" x-transition.opacity
        class="fixed inset-0 bg-black/60 backdrop-blur-sm z-30 lg:hidden"
        @click="sidebarOpen = false" style="display: none;">
    </div>

    <div class="flex h-screen overflow-hidden">

        <!-- ===== SIDEBAR ===== -->
        @include('brand.partials.sidebar')

        <!-- ===== MAIN CONTENT ===== -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

            <!-- ===== TOP NAVBAR ===== -->
            @include('brand.partials.navbar')

            <!-- ===== PAGE CONTENT ===== -->
            <main class="flex-1 overflow-y-auto p-6 pb-24">
                @yield('content')
            </main>

        </div>
    </div>

    <!-- Tombol Kontak Floating (pojok kanan bawah) -->
    @include('partials.floating-contact')

    <script>
        document.addEventListener('DOMContentLoaded', () => lucide.createIcons());
    </script>
    @stack('scripts')
</body>
</html>
