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

    {{-- CSS Khusus RubyLux (Ambil dari app.blade.php) --}}
    <style>
        /* Warna Palet RubyLux */
        :root {
            --rubylux-dark: #1a1a1a; /* Hitam Dominan */
            --rubylux-ruby: #e0115f; /* Merah Ruby terang */
            --rubylux-ruby-dark: #a00a40; /* Merah Ruby gelap */
            --rubylux-gradient-start: #e0115f;
            --rubylux-gradient-end: #a00a40;
            --rubylux-text-light: #f0f0f0; /* Teks terang */
            --rubylux-text-dark: #333333; /* Teks gelap */
        }

        /* Override warna Tailwind CSS default agar sesuai tema RubyLux */
        .bg-gray-100 { background-color: var(--rubylux-dark); }
        .text-gray-800 { color: var(--rubylux-text-light); }
        .text-gray-900 { color: var(--rubylux-text-light); }
        .bg-white { background-color: #2b2b2b; } /* Warna card/box */
        .shadow-sm { box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.5); }
        .text-gray-500 { color: #aaaaaa; }
        .border-gray-200 { border-color: #444444; }
        .text-gray-700 { color: #dddddd; }
        .form-input, .form-textarea, .form-select, .form-multiselect {
            background-color: #333333 !important;
            border-color: #555555 !important;
            color: var(--rubylux-text-light) !important;
        }
        .form-input:focus, .form-textarea:focus, .form-select:focus, .form-multiselect:focus {
            border-color: var(--rubylux-ruby) !important;
            box-shadow: 0 0 0 3px rgba(224, 17, 95, 0.5) !important;
        }

        /* Tombol default Breeze */
        .inline-flex.items-center.px-4.py-2.bg-gray-800.border.border-transparent.rounded-md.font-semibold.text-xs.text-white.uppercase.tracking-widest.hover\:bg-gray-700.focus\:bg-gray-700.active\:bg-gray-900.focus\:outline-none.focus\:ring-2.focus\:ring-indigo-500.focus\:ring-offset-2.transition.ease-in-out.duration-150 {
            background-color: var(--rubylux-ruby);
            border-color: var(--rubylux-ruby);
            color: var(--rubylux-text-light);
        }
        .inline-flex.items-center.px-4.py-2.bg-gray-800.border.border-transparent.rounded-md.font-semibold.text-xs.text-white.uppercase.tracking-widest.hover\:bg-gray-700.focus\:bg-gray-700.active\:bg-gray-900.focus\:outline-none.focus\:ring-2.focus\:ring-indigo-500.focus\:ring-offset-2.transition.ease-in-out.duration-150:hover {
            background-color: var(--rubylux-ruby-dark);
        }

        /* Kelas kustom RubyLux */
        .bg-rubylux-dark { background-color: var(--rubylux-dark); }
        .text-rubylux-ruby { color: var(--rubylux-ruby); }
        .bg-rubylux-ruby { background-color: var(--rubylux-ruby); }
        .hover\:bg-rubylux-ruby-dark:hover { background-color: var(--rubylux-ruby-dark); }
        .border-rubylux-ruby { border-color: var(--rubylux-ruby); }
        .gradient-rubylux {
            background: linear-gradient(to right, var(--rubylux-gradient-start), var(--rubylux-gradient-end));
        }
        .text-rubylux-light { color: var(--rubylux-text-light); }
        .text-rubylux-dark { color: var(--rubylux-text-dark); }
    </style>
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