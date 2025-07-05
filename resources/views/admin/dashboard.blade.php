<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-rubylux-light leading-tight"> 
            {{ __('Admin Dashboard RubyLux') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-rubylux-ruby-dark"> {{-- Tambah border --}}
                <div class="p-6 text-rubylux-light"> {{-- Ubah text-gray jadi rubylux-light --}}
                    <h3 class="text-2xl font-bold mb-6 text-rubylux-ruby">Selamat Datang, {{ Auth::user()->username }}!</h3> {{-- Tampilkan username admin --}}

                    <p class="mt-4 text-lg">Area Manajemen Utama RubyLux.</p>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
                        {{-- Card Statistik 1: User Pending --}}
                        <div class="bg-rubylux-dark rounded-lg p-6 shadow-md border border-rubylux-ruby-dark">
                            <div class="flex items-center justify-between">
                                <h4 class="text-lg font-semibold text-rubylux-light">User Menunggu Konfirmasi</h4>
                                <svg class="w-8 h-8 text-rubylux-ruby" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6m-12 0v-1a6 6 0 00-6-6H3m0 0a6 6 0 006-6v-1m0 0h1a2 2 0 012 2v1a2 2 0 01-2 2h-1a2 2 0 01-2-2v-1a2 2 0 012-2z"></path></svg>
                            </div>
                            <p class="text-3xl font-bold text-rubylux-ruby mt-4">
                                <span id="pendingUsersCount">{{$pendingUsersCount}}</span> {{-- Nanti kita isi dari JS atau PHP --}}
                            </p>
                        </div>

                        {{-- Card Statistik 2: Pesanan Baru --}}
                        <div class="bg-rubylux-dark rounded-lg p-6 shadow-md border border-rubylux-ruby-dark">
                            <div class="flex items-center justify-between">
                                <h4 class="text-lg font-semibold text-rubylux-light">Pesanan Pending</h4>
                                <svg class="w-8 h-8 text-rubylux-ruby" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            </div>
                            <p class="text-3xl font-bold text-rubylux-ruby mt-4">
                                <span id="pendingOrdersCount">{{$pendingOrdersCount}}</span>
                            </p>
                        </div>

                        {{-- Card Statistik 3: Total Produk --}}
                        <div class="bg-rubylux-dark rounded-lg p-6 shadow-md border border-rubylux-ruby-dark">
                            <div class="flex items-center justify-between">
                                <h4 class="text-lg font-semibold text-rubylux-light">Total Produk</h4>
                                <svg class="w-8 h-8 text-rubylux-ruby" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            </div>
                            <p class="text-3xl font-bold text-rubylux-ruby mt-4">
                                <span id="totalProductsCount">{{$totalProductsCount}}</span>
                            </p>
                        </div>
                    </div>

                    {{-- Nanti di sini kita akan tambahkan tabel user pending atau order terbaru --}}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>