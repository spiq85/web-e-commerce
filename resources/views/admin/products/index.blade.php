{{-- resources/views/admin/products/index.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-rubylux-light leading-tight">
            {{ __('Manajemen Produk') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-rubylux-ruby-dark">
                <div class="p-6 text-rubylux-light">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold">Daftar Produk RubyLux</h3>
                        <a href="{{ route('admin.products.create') }}" class="inline-flex items-center px-4 py-2 bg-rubylux-ruby border border-transparent rounded-md font-semibold text-xs text-rubylux-light uppercase tracking-widest hover:bg-rubylux-ruby-dark focus:outline-none focus:ring-2 focus:ring-rubylux-ruby focus:ring-offset-2 transition ease-in-out duration-150">
                            Tambah Produk Baru
                        </a>
                    </div>

                    {{-- Pesan Sukses/Error --}}
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
                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-rubylux-ruby-dark">
                            <thead class="bg-rubylux-dark">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-rubylux-light uppercase tracking-wider">
                                        ID
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-rubylux-light uppercase tracking-wider">
                                        Gambar Produk {{-- <<< TAMBAHKAN INI --}}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-rubylux-light uppercase tracking-wider">
                                        Nama Produk
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-rubylux-light uppercase tracking-wider">
                                        Harga
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-rubylux-light uppercase tracking-wider">
                                        Brand
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-rubylux-light uppercase tracking-wider">
                                        Stok
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-rubylux-light uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-rubylux-dark divide-y divide-rubylux-ruby-dark">
                                @forelse ($products as $product)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $product->id_products }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($product->image) {{-- Pastikan pakai $product->image --}}
                                                <img src="{{ asset($product->image) }}" alt="{{ $product->product_name }}" class="w-12 h-12 object-cover rounded-md border border-gray-400">
                                            @else
                                                <div class="w-12 h-12 bg-gray-600 rounded-md flex items-center justify-center text-xs text-gray-300">No Image</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $product->product_name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ number_format($product->price, 2, ',', '.') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $product->brand ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $product->stock }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('admin.products.edit', $product->id_products) }}" class="text-rubylux-ruby hover:text-rubylux-ruby-dark mr-3">Edit</a>
                                            <form action="{{ route('admin.products.destroy', $product->id_products) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus produk ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 whitespace-nowrap text-center text-rubylux-light">Tidak ada produk ditemukan.</td> {{-- Ubah colspan --}}
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>