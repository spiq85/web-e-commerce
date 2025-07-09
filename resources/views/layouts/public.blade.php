<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'RubyLux') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body class="font-sans antialiased bg-rubylux-dark">
    <div class="min-h-screen bg-rubylux-dark text-rubylux-light">
        {{-- Header/Navbar Publik --}}
        <nav class="bg-rubylux-dark border-b border-rubylux-ruby-dark p-4 shadow-md">
            <div class="max-w-7xl mx-auto flex justify-between items-center">
                <a href="{{ route('products.index') }}" class="text-3xl font-bold text-rubylux-ruby">RubyLux</a>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('products.index') }}" class="text-rubylux-light hover:text-rubylux-ruby">Produk</a>

                    @auth('web') {{-- Mengecek jika ada user yang login via web session --}}
                        @if (Auth::user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="text-rubylux-light hover:text-rubylux-ruby">Dashboard Admin</a>
                        @else
                            <a href="{{ route('products.index') }}" class="text-rubylux-light hover:text-rubylux-ruby">Dashboard Saya</a> {{-- User biasa kembali ke produk list --}}
                            {{-- Atau bisa ke halaman profil/pesanan mereka jika nanti dibuat --}}
                            {{-- <a href="{{ route('profile.edit') }}" class="text-rubylux-light hover:text-rubylux-ruby">Profil Saya</a> --}}
                            {{-- <a href="{{ route('orders.index') }}" class="text-rubylux-light hover:text-rubylux-ruby">Pesanan Saya</a> --}}
                        @endif
                        {{-- Logout Button --}}
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-rubylux-light hover:text-rubylux-ruby bg-transparent border-none cursor-pointer">Logout</button>
                        </form>
                    @else {{-- Jika user belum login (Auth::guest()) --}}
                        <a href="{{ route('login') }}" class="text-rubylux-light hover:text-rubylux-ruby">Login</a>
                    @endauth
                </div>
            </div>
        </nav>

        <main class="py-8 px-4">
            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 max-w-7xl mx-auto" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif
            {{ $slot }} {{-- Konten halaman akan di-inject di sini --}}
        </main>

        {{-- Footer Publik --}}
        <footer class="bg-rubylux-dark border-t border-rubylux-ruby-dark p-6 mt-8 text-center text-sm text-gray-500">
            &copy; {{ date('Y') }} RubyLux. All rights reserved.
        </footer>
    </div>
</body>
</html>