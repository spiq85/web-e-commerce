<x-public-layout> {{-- Menggunakan layout publik baru --}}
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-rubylux-ruby mb-8 text-center">Koleksi Produk RubyLux</h1>

        {{-- Pesan Error (misal: kategori tidak ditemukan) --}}
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        {{-- Filter Kategori --}}
        <div class="mb-8 p-4 bg-white rounded-lg shadow-md border border-rubylux-ruby-dark">
            <h3 class="text-lg font-semibold text-rubylux-light mb-4">Filter Kategori:</h3>
            <div class="flex flex-wrap gap-3 mb-4">
                {{-- Tombol 'Semua Produk' (tanpa filter) --}}
                <a href="{{ route('products.index') }}"
                   class="px-4 py-2 rounded-md font-semibold text-sm transition-colors duration-200
                          {{ is_null($activeCategory) ? 'bg-rubylux-ruby text-rubylux-light' : 'bg-gray-700 text-gray-300 hover:bg-gray-600' }}">
                    Semua Produk
                </a>
            </div>

            {{-- Filter Kategori Utama (Parent) --}}
            <h4 class="text-md font-semibold text-rubylux-light mb-2">Kategori Utama:</h4>
            <div class="flex flex-wrap gap-3 mb-4">
                @forelse ($mainCategories as $category)
                    {{-- Pastikan hanya parent level teratas (parent_id is NULL) --}}
                    @if (is_null($category->parent_id)) 
                        <a href="{{ route('products.index', ['category' => $category->slug]) }}"
                           class="px-4 py-2 rounded-md font-semibold text-sm transition-colors duration-200
                                  {{ $activeCategory && $activeCategory->slug === $category->slug ? 'bg-rubylux-ruby text-rubylux-light' : 'bg-gray-700 text-gray-300 hover:bg-gray-600' }}">
                            {{ $category->category_name }}
                        </a>
                    @endif
                @empty
                    <p class="text-sm text-gray-500">Tidak ada kategori utama.</p>
                @endforelse
            </div>

            {{-- Filter Sub Kategori (Jika kategori utama aktif dan punya anak) --}}
            @if ($activeCategory && $activeCategory->children->count())
                <h4 class="text-md font-semibold text-rubylux-light mb-2">Sub Kategori ({{ $activeCategory->category_name }}):</h4>
                <div class="flex flex-wrap gap-3">
                    @foreach ($activeCategory->children as $childCategory)
                        <a href="{{ route('products.index', ['category' => $childCategory->slug]) }}"
                           class="px-4 py-2 rounded-md font-semibold text-sm transition-colors duration-200
                                  {{ $activeCategory->slug === $childCategory->slug ? 'bg-rubylux-ruby text-rubylux-light' : 'bg-gray-700 text-gray-300 hover:bg-gray-600' }}">
                            {{ $childCategory->category_name }}
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Daftar Produk --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse ($products as $product)
                <div class="bg-white rounded-lg shadow-lg overflow-hidden border border-rubylux-ruby-dark">
                    <a href="{{ route('products.show', $product->id_products) }}">
                        @if ($product->image) {{-- Pastikan pakai $product->image --}}
                            <img src="{{ asset($product->image) }}" alt="{{ $product->product_name }}" class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gray-600 flex items-center justify-center text-lg text-gray-300">No Image</div>
                        @endif
                    </a>
                    <div class="p-4">
                        <h2 class="text-xl font-bold text-rubylux-light mb-2">{{ $product->product_name }}</h2>
                        <p class="text-rubylux-ruby text-lg font-semibold mb-2">Rp{{ number_format($product->price, 2, ',', '.') }}</p>
                        <p class="text-sm text-gray-400 mb-2">Brand: {{ $product->brand ?? 'Tidak ada brand' }}</p>
                        <p class="text-sm text-gray-500">Stok: {{ $product->stock }}</p>
                        @if($product->category) {{-- Pastikan relasi category ada dan sudah eager loaded --}}
                            <p class="text-xs text-gray-400 mt-1">Kategori: {{ $product->category->category_name }}</p>
                        @endif
                        <div class="mt-4 flex justify-between items-center">
                            <a href="{{ route('products.show', $product->id_products) }}" class="text-rubylux-ruby hover:underline text-sm">Lihat Detail</a>
                            {{-- Tombol "Tambah ke Keranjang" akan ada di Flutter --}}
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-10 text-rubylux-light">Tidak ada produk ditemukan.</div>
            @endforelse
        </div>
    </div>
</x-public-layout>