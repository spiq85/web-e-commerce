{{-- resources/views/layouts/guest.blade.php --}}

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'RubyLux') }}</title> {{-- Ubah default name jadi RubyLux --}}

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body class="font-sans antialiased text-gray-900 bg-rubylux-dark"> 
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-rubylux-dark"> 
        <div>
            <a href="/"> {{-- Link ke halaman utama/produk --}}
                <h1 class="text-5xl font-extrabold text-rubylux-ruby shadow-md">RubyLux</h1>
            </a>
        </div>

        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg border border-rubylux-ruby"> {{-- Terapkan warna RubyLux di card --}}
            {{ $slot }}
        </div>
    </div>
</body>
</html>