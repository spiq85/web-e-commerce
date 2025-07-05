<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-rubylux-light leading-tight">
            {{ __('Edit Produk') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-rubylux-ruby-dark">
                <div class="p-6 text-rubylux-light">
                    <h3 class="text-xl font-bold mb-4">Form Edit Produk RubyLux (ID: {{ $product->id_products }})</h3>

                    {{-- Tampilkan Error Validasi --}}
                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Tampilkan Pesan Sukses/Error --}}
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <form action="{{ route('admin.products.update', $product->id_products) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="product_name" class="block text-sm font-medium text-rubylux-light">Nama Produk</label>
                            <input type="text" name="product_name" id="product_name" value="{{ old('product_name', $product->product_name) }}" class="mt-1 block w-full rounded-md shadow-sm form-input">
                        </div>
                        <div class="mb-4">
                            <label for="price" class="block text-sm font-medium text-rubylux-light">Harga</label>
                            <input type="number" step="0.01" name="price" id="price" value="{{ old('price', $product->price) }}" class="mt-1 block w-full rounded-md shadow-sm form-input">
                        </div>
                        <div class="mb-4">
                            <label for="brand" class="block text-sm font-medium text-rubylux-light">Brand</label>
                            <input type="text" name="brand" id="brand" value="{{ old('brand', $product->brand) }}" class="mt-1 block w-full rounded-md shadow-sm form-input">
                        </div>

                        {{-- Kategori Produk --}}
                        <div class="mb-4">
                            <label for="id_categories" class="block text-sm font-medium text-rubylux-light">Kategori Produk</label>
                            <select name="id_categories" id="id_categories" class="mt-1 block w-full rounded-md shadow-sm form-select">
                                <option value="">-- Pilih Kategori --</option>
                                {{-- Loop untuk menampilkan kategori dengan hierarki --}}
                                @foreach ($categories as $category)
                                    @if (is_null($category->parent_id))
                                        <option value="{{ $category->id_categories }}" {{ old('id_categories', $product->id_categories) == $category->id_categories ? 'selected' : '' }}>
                                            {{ $category->category_name }}
                                        </option>
                                        @if ($category->children->count())
                                            @foreach ($category->children as $child)
                                                {{-- Panggil rekursif view yang sama --}}
                                                @include('admin.products._category_option', ['category' => $child, 'level' => 1, 'selectedId' => old('id_categories', $product->id_categories)])
                                            @endforeach
                                        @endif
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="stock" class="block text-sm font-medium text-rubylux-light">Stok</label>
                            <input type="number" name="stock" id="stock" value="{{ old('stock', $product->stock) }}" class="mt-1 block w-full rounded-md shadow-sm form-input">
                        </div>
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-rubylux-light">Deskripsi</label>
                            <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md shadow-sm form-textarea">{{ old('description', $product->description) }}</textarea>
                        </div>

                        {{-- Gambar Produk (Input File dan Tampilan Gambar Lama) --}}
                        <div class="mb-4">
                            <label for="image" class="block text-sm font-medium text-rubylux-light">Gambar Produk (Pilih jika ingin mengganti)</label>
                            <input type="file" name="image" id="image" class="mt-1 block w-full rounded-md shadow-sm form-input text-rubylux-light border-rubylux-ruby-dark">
                            <p class="mt-1 text-xs text-gray-400">Format yang didukung: jpeg, png, jpg, gif. Maksimal 2MB.</p>

                            @if ($product->image)
                                <div class="mt-4">
                                    <p class="text-sm font-medium text-rubylux-light mb-2">Gambar Saat Ini:</p>
                                    <img src="{{ asset($product->image) }}" alt="{{ $product->product_name }}" class="w-32 h-32 object-cover rounded-md border border-gray-500">
                                </div>
                            @else
                                <div class="mt-4 text-sm text-gray-400">Belum ada gambar untuk produk ini.</div>
                            @endif
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-rubylux-ruby border border-transparent rounded-md font-semibold text-xs text-rubylux-light uppercase tracking-widest hover:bg-rubylux-ruby-dark focus:outline-none focus:ring-2 focus:ring-rubylux-ruby focus:ring-offset-2 transition ease-in-out duration-150">
                                Update Produk
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>