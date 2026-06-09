<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dasbor') - Admin Clipfluence</title>
    <link rel="icon" type="image/png" href="{{ asset('images/brand/logo-icon.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/brand/logo-icon.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest" defer></script>
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
        $compactSidebar = (bool) ($appearance['compact_sidebar'] ?? false);
    @endphp

    <style>
        :root {
            --admin-primary: {{ $primaryColor }};
            --admin-accent: {{ $accentColor }};
            --admin-primary-rgb: {{ implode(',', sscanf(ltrim($primaryColor, '#'), "%02x%02x%02x")) }};
            --admin-accent-rgb: {{ implode(',', sscanf(ltrim($accentColor, '#'), "%02x%02x%02x")) }};
        }

        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
        ::-webkit-scrollbar { width: 4px; height: 4px; }
        ::-webkit-scrollbar-track { background: #0a0a0a; }
        ::-webkit-scrollbar-thumb { background: #27272a; border-radius: 99px; }
        ::-webkit-scrollbar-thumb:hover { background: #3f3f46; }

        .bg-brand { background-color: var(--admin-primary) !important; }
        .hover\:bg-brand-hover:hover { background-color: color-mix(in srgb, var(--admin-primary) 88%, black) !important; }
        .text-brand { color: var(--admin-primary) !important; }
        .text-brand-light { color: var(--admin-accent) !important; }
        .border-brand { border-color: var(--admin-primary) !important; }
        .bg-brand\/10 { background-color: rgba(var(--admin-primary-rgb), 0.10) !important; }
        .border-brand\/20 { border-color: rgba(var(--admin-primary-rgb), 0.20) !important; }
        .shadow-brand,
        .shadow-\[0_0_12px_rgba\(139\,92\,246\,0\.6\)\] {
            box-shadow: 0 0 12px rgba(var(--admin-primary-rgb), 0.60) !important;
        }
        .focus\:border-brand:focus { border-color: var(--admin-primary) !important; }
        .focus\:ring-brand:focus { --tw-ring-color: var(--admin-primary) !important; }

        /* Admin-wide theme accents */
        aside a.active,
        aside .sidebar-link.active,
        aside .text-brand {
            color: var(--admin-primary) !important;
        }

        aside .sidebar-link.active {
            background-color: rgba(var(--admin-primary-rgb), 0.12) !important;
            border: 1px solid rgba(var(--admin-primary-rgb), 0.25) !important;
        }

        input:focus,
        select:focus,
        textarea:focus {
            border-color: var(--admin-primary) !important;
            box-shadow: 0 0 0 1px rgba(var(--admin-primary-rgb), 0.35) !important;
        }

        .admin-accent-chip {
            background-color: rgba(var(--admin-accent-rgb), 0.14) !important;
            border-color: rgba(var(--admin-accent-rgb), 0.35) !important;
            color: var(--admin-accent) !important;
        }

        .admin-theme-border {
            border-color: rgba(var(--admin-primary-rgb), 0.25) !important;
        }

        body.compact-sidebar aside { width: 5rem !important; }
        body.compact-sidebar aside nav a,
        body.compact-sidebar aside nav button {
            justify-content: center !important;
        }
        body.compact-sidebar aside nav a > :not(i),
        body.compact-sidebar aside nav button > div > :not(i),
        body.compact-sidebar aside nav button > i:last-child,
        body.compact-sidebar aside .ml-5,
        body.compact-sidebar aside .pt-0\.5,
        body.compact-sidebar aside .flex-1.min-w-0 {
            display: none !important;
        }
    </style>
</head>
<body class="bg-[#080808] text-slate-50 antialiased h-full {{ $compactSidebar ? 'compact-sidebar' : '' }}" x-data="{ sidebarOpen: false }">

    <!-- Mobile sidebar backdrop -->
    <div x-show="sidebarOpen" x-transition.opacity
        class="fixed inset-0 bg-black/60 backdrop-blur-sm z-30 lg:hidden"
        @click="sidebarOpen = false">
    </div>

    <div class="flex h-screen overflow-hidden">

        <!-- ===== SIDEBAR ===== -->
        @include('admin.partials.sidebar')

        <!-- ===== MAIN CONTENT ===== -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

            <!-- ===== TOP NAVBAR ===== -->
            @include('admin.partials.navbar')

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
