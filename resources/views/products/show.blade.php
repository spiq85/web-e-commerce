{{-- resources/views/products/show.blade.php --}}

<x-public-layout>
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-rubylux-ruby mb-8 text-center">Detail Produk</h1>

        {{-- Pesan Sukses/Error --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 max-w-7xl mx-auto" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 max-w-7xl mx-auto" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 max-w-7xl mx-auto" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-lg shadow-lg overflow-hidden border border-rubylux-ruby-dark flex flex-col md:flex-row">
            <div class="md:w-1/2 p-4">
                @if ($product->image)
                    <img src="{{ asset($product->image) }}" alt="{{ $product->product_name }}" class="w-full h-auto object-cover rounded-md border border-gray-500">
                @else
                    <div class="w-full h-80 bg-gray-600 flex items-center justify-center text-xl text-gray-300 rounded-md">No Image</div>
                @endif
            </div>
            <div class="md:w-1/2 p-6">
                <h2 class="text-3xl font-bold text-rubylux-light mb-3">{{ $product->product_name }}</h2>
                <p class="text-rubylux-ruby text-2xl font-semibold mb-4">Rp{{ number_format($product->price, 2, ',', '.') }}</p>
                <p class="text-md text-gray-400 mb-2">Brand: {{ $product->brand ?? 'Tidak ada brand' }}</p>
                <p class="text-md text-gray-500 mb-4">Stok: {{ $product->stock }}</p>
                @if($product->category)
                    <p class="text-md text-gray-400 mb-4">Kategori: {{ $product->category->category_name }}</p>
                @endif

                <p class="text-rubylux-light text-base leading-relaxed mb-6">{{ $product->description ?? 'Tidak ada deskripsi.' }}</p>

                <div class="mt-6">
                    <a href="{{ route('products.index') }}" class="px-6 py-3 bg-gray-600 rounded-md text-rubylux-light hover:bg-gray-700 transition duration-200">
                        Kembali ke Daftar Produk
                    </a>

                    {{-- >>> BAGIAN PEMESANAN BARU <<< --}}
                    <div class="mt-8 pt-8 border-t border-rubylux-ruby-dark">
                        @auth('web') {{-- Hanya tampilkan jika user sudah login via web --}}
                            @if ($product->stock > 0)
                                <h4 class="text-xl font-bold text-rubylux-light mb-4">Pesan Sekarang:</h4>
                                <form action="{{ route('orders.place') }}" method="POST"> {{-- Nanti kita definisikan route ini --}}
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id_products }}">

                                    <div class="mb-4">
                                        <label for="quantity" class="block text-sm font-medium text-rubylux-light">Jumlah (Stok Tersedia: {{ $product->stock }})</label>
                                        <input type="number" name="quantity" id="quantity" value="1" min="1" max="{{ $product->stock }}" class="mt-1 block w-full rounded-md shadow-sm form-input">
                                    </div>

                                    <div class="mb-4">
                                        <label for="shipping_address" class="block text-sm font-medium text-rubylux-light">Alamat Pengiriman</label>
                                        <textarea name="shipping_address" id="shipping_address" rows="3" class="mt-1 block w-full rounded-md shadow-sm form-textarea" required>{{ old('shipping_address', Auth::user()->shipping_address ?? '') }}</textarea> {{-- Auto-fill jika user punya alamat di DB --}}
                                    </div>

                                    <div class="mb-4">
                                        <label for="payment_method" class="block text-sm font-medium text-rubylux-light">Metode Pembayaran</label>
                                        <select name="payment_method" id="payment_method" class="mt-1 block w-full rounded-md shadow-sm form-select" required>
                                            <option value="">-- Pilih Metode Pembayaran --</option>
                                            <option value="Transfer Bank">Transfer Bank</option>
                                            <option value="Credit Card">Credit Card</option>
                                            <option value="E-Wallet">E-Wallet</option>
                                        </select>
                                    </div>

                                    <button type="submit" class="px-6 py-3 bg-rubylux-ruby rounded-md text-rubylux-light hover:bg-rubylux-ruby-dark transition duration-200 w-full">
                                        Pesan Sekarang
                                    </button>
                                </form>
                            @else
                                <p class="text-lg font-bold text-red-400">Produk ini kehabisan stok.</p>
                            @endif
                        @else {{-- Jika user belum login --}}
                            <p class="text-lg font-bold text-rubylux-light">
                                <a href="{{ route('login') }}" class="underline text-rubylux-ruby hover:text-rubylux-ruby-dark">Login</a> untuk melakukan pemesanan.
                            </p>
                        @endauth
                    </div>
                    {{-- >>> AKHIR BAGIAN PEMESANAN BARU <<< --}}
                </div>
            </div>
        </div>
    </div>
</x-public-layout>