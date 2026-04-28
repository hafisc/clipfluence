<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-black text-white">

    <div class="p-6">
        <h1 class="text-xl font-bold mb-4">Admin Panel</h1>

        @yield('content')
    </div>

</body>
</html>