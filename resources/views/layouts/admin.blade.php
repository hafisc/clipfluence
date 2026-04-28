<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Admin Clipfluence</title>
    <link rel="icon" type="image/png" href="{{ asset('images/brand/logo-icon.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/brand/logo-icon.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest" defer></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        ::-webkit-scrollbar { width: 4px; height: 4px; }
        ::-webkit-scrollbar-track { background: #0a0a0a; }
        ::-webkit-scrollbar-thumb { background: #27272a; border-radius: 99px; }
        ::-webkit-scrollbar-thumb:hover { background: #3f3f46; }
    </style>
</head>
<body class="bg-[#080808] text-slate-50 antialiased h-full" x-data="{ sidebarOpen: false }">

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
            <main class="flex-1 overflow-y-auto p-6">
                @yield('content')
            </main>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => lucide.createIcons());
    </script>
    @stack('scripts')
</body>
</html>
