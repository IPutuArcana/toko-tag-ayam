<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Toko Tag Ayam</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-100">
        <div class="relative min-h-screen flex flex-col items-center justify-center">
            <div class="absolute top-0 right-0 p-6">
                @auth
                    <a href="{{ url('/dashboard') }}" class="font-semibold text-gray-600 hover:text-gray-900">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="font-semibold text-gray-600 hover:text-gray-900">Login</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="ml-4 font-semibold text-gray-600 hover:text-gray-900">Register</a>
                    @endif
                @endauth
            </div>

            <div class="max-w-3xl mx-auto p-6 text-center">
                <h1 class="text-4xl font-bold text-gray-900 mb-4">
                    Selamat Datang di Toko Tag Ayam
                </h1>
                <p class="text-xl text-gray-600 mb-8">
                    Solusi penandaan ayam kustom untuk peternakan Anda. Dibuat dengan presisi dan material terbaik.
                </p>
                <a href="{{ route('login') }}" class="inline-block px-8 py-3 bg-brand-600 text-white font-semibold rounded-lg shadow-md hover:bg-brand-700 transition duration-300">
                    Mulai
                </a>
            </div>
        </div>
    </body>
</html>