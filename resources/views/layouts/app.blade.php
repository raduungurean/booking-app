<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Front Office - @yield('title')</title>
    @vite('resources/css/app.css')
</head>
<body class="flex flex-col min-h-screen">
    <header class="bg-gray-900 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <div>
                <h1 class="text-left">Welcome to the Front Office!</h1>
            </div>
            <div>
                <a href="{{ route('login') }}" class="text-right">Admin</a>
            </div>
        </div>
    </header>

    <main class="flex-grow container mx-auto py-8">
        @yield('content')
    </main>

    <footer class="bg-gray-900 text-white py-4">
        <div class="container mx-auto">
            &copy; 2023 Front Office. All rights reserved.
        </div>
    </footer>
    @vite('resources/js/app.js')
</body>
</html>
