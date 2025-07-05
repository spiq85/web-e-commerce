<x-public-layout>
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-rubylux-ruby mb-8 text-center">Detail Produk</h1>

        <div class="bg-white rounded-lg shadow-lg overflow-hidden border border-rubylux-ruby-dark flex flex-col md:flex-row">
            <div class="md:w-1/2 p-4">
                @if ($products->image)
                    <img src="{{ asset($products->image) }}" alt="{{ $products->products_name }}" class="w-full h-auto object-cover rounded-md border border-gray-500">
                @else
                    <div class="w-full h-80 bg-gray-600 flex items-center justify-center text-xl text-gray-300 rounded-md">No Image</div>
                @endif
            </div>
            <div class="md:w-1/2 p-6">
                <h2 class="text-3xl font-bold text-rubylux-light mb-3">{{ $products->products_name }}</h2>
                <p class="text-rubylux-ruby text-2xl font-semibold mb-4">Rp{{ number_format($products->price, 2, ',', '.') }}</p>
                <p class="text-md text-gray-400 mb-2">Brand: {{ $products->brand ?? 'Tidak ada brand' }}</p>
                <p class="text-md text-gray-500 mb-4">Stok: {{ $products->stock }}</p>
                @if($products->category)
                    <p class="text-md text-gray-400 mb-4">Kategori: {{ $products->category->category_name }}</p>
                @endif

                <p class="text-rubylux-light text-base leading-relaxed mb-6">{{ $products->description ?? 'Tidak ada deskripsi.' }}</p>

                <div class="mt-6">
                    <a href="{{ route('products.index') }}" class="px-6 py-3 bg-gray-600 rounded-md text-rubylux-light hover:bg-gray-700 transition duration-200">
                        Kembali ke Daftar Produk
                    </a>
                    {{-- Tombol "Tambah ke Keranjang" ada di Flutter --}}
                    {{-- <button class="px-6 py-3 bg-rubylux-ruby rounded-md text-rubylux-light hover:bg-rubylux-ruby-dark ml-4">
                        Tambah ke Keranjang
                    </button> --}}
                </div>
            </div>
        </div>
    </div>
</x-public-layout>