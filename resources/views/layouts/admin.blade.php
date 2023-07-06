<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - @yield('title')</title>
    @vite('resources/css/app.css')
</head>
<body class="flex flex-col min-h-screen">
    <header class="bg-blue-900 text-white py-4">
        <div class="container mx-auto">
            <a href="{{ route('admin.dashboard') }}">
                <h1>Admin Panel</h1>
            </a>
        </div>
    </header>

    <main class="flex-grow container mx-auto py-8">
        @yield('content')
    </main>

    <footer class="bg-blue-900 text-white py-4">
        <div class="container mx-auto">
            &copy; 2023 Admin Panel. All rights reserved.
        </div>
    </footer>
    @vite('resources/js/app.js')
</body>
</html>
