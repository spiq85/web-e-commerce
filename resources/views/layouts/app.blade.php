{{-- resources/views/layouts/app.blade.php --}}

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

    {{-- Tambahan CSS khusus RubyLux --}}
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
        .border-gray-200 { border-color: #444444; } /* Border untuk card/box */
        .text-gray-700 { color: #dddddd; } /* Contoh teks di form */
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
<body class="font-sans antialiased bg-rubylux-dark"> {{-- Body pakai warna gelap kita --}}

    <div class="min-h-screen flex"> {{-- Ubah dari div biasa jadi flex --}}
        {{-- Sidebar Admin --}}
        <aside class="w-64 bg-rubylux-dark border-r border-rubylux-ruby-dark p-6 shadow-lg">
            <div class="flex items-center justify-center py-4 border-b border-rubylux-ruby-dark mb-6">
                <a href="{{ route('admin.dashboard') }}" class="text-3xl font-bold text-rubylux-ruby">RubyLux</a>
            </div>
            <nav class="mt-6">
                <ul>
                    <li class="mb-4">
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center py-2 px-3 rounded-lg text-rubylux-light hover:bg-rubylux-ruby-dark transition duration-200">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m0 0l-7 7m7-7v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001 1h2a1 1 0 001-1m-6 0v-4a1 1 0 011-1h2a1 1 0 011 1v4m-6 7a1 1 0 001 1h2a1 1 0 001-1m-6 0v-4a1 1 0 011-1h2a1 1 0 001 1v4"></path></svg>
                            Dashboard
                        </a>
                    </li>
                    <li class="mb-4">
                        <a href="{{ route('admin.categories.index') }}" class="flex items-center py-2 px-3 rounded-lg text-rubylux-light hover:bg-rubylux-ruby-dark transition duration-200">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            Manajemen Kategori
                        </a>
                    </li>
                    <li class="mb-4">
                        <a href="{{route('admin.products.index')}}" class="flex items-center py-2 px-3 rounded-lg text-rubylux-light hover:bg-rubylux-ruby-dark transition duration-200">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            Manajemen Produk
                        </a>
                    </li>
                    <li class="mb-4">
                        <a href="{{route('admin.orders.index')}}" class="flex items-center py-2 px-3 rounded-lg text-rubylux-light hover:bg-rubylux-ruby-dark transition duration-200">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h2a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h2m3-2v2m0-7V4m0 7H4m10 7H7"></path></svg>
                            Manajemen Pesanan
                        </a>
                    </li>
                    <li class="mb-4">
                        <a href="{{route('admin.users.index')}}" class="flex items-center py-2 px-3 rounded-lg text-rubylux-light hover:bg-rubylux-ruby-dark transition duration-200">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6m-12 0v-1a6 6 0 00-6-6H3m0 0a6 6 0 006-6v-1m0 0h1a2 2 0 012 2v1a2 2 0 01-2 2h-1a2 2 0 01-2-2v-1a2 2 0 012-2z"></path></svg>
                            Manajemen User
                        </a>
                    </li>
                    {{-- Logout di Sidebar --}}
                    <li class="mt-6"> {{-- Posisi di paling bawah sidebar --}}
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a href="{{route('logout')}}" onclick="event.preventDefault(); this.closest('form').submit();"
                               class="flex items-center py-2 px-3 rounded-lg text-rubylux-light hover:bg-rubylux-ruby-dark transition duration-200">
                               <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                Logout
                            </a>
                        </form>
                    </li>
                </ul>
            </nav>
        </aside>

        {{-- Main Content Area --}}
        <div class="flex-1 flex flex-col">

            @if (isset($header))
                <header class="bg-rubylux-dark shadow border-b border-rubylux-ruby-dark">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <main class="flex-1 p-6">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>