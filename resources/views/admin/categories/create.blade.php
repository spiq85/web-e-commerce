<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-rubylux-light leading-tight">
            {{ __('Tambah Kategori Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-rubylux-ruby-dark">
                <div class="p-6 text-rubylux-light">
                    <h3 class="text-xl font-bold mb-4">Form Tambah Kategori RubyLux</h3>

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

                    <form action="{{ route('admin.categories.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label for="category_name" class="block text-sm font-medium text-rubylux-light">Nama Kategori</label>
                            <input type="text" name="category_name" id="category_name" value="{{ old('category_name') }}" class="mt-1 block w-full rounded-md shadow-sm form-input">
                        </div>
                        <div class="mb-4">
                            <label for="parent_id" class="block text-sm font-medium text-rubylux-light">Kategori Induk (Opsional)</label>
                            <select name="parent_id" id="parent_id" class="mt-1 block w-full rounded-md shadow-sm form-select">
                                <option value="">-- Tidak ada (Kategori Utama) --</option>
                                @foreach ($parentCategories as $parent)
                                    <option value="{{ $parent->id_categories }}" {{ old('parent_id') == $parent->id_categories ? 'selected' : '' }}>
                                        {{ $parent->category_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-rubylux-light">Deskripsi (Opsional)</label>
                            <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md shadow-sm form-textarea">{{ old('description') }}</textarea>
                        </div>
                        <div class="mb-4">
                            <label for="image_url" class="block text-sm font-medium text-rubylux-light">URL Gambar (Opsional)</label>
                            <input type="text" name="image_url" id="image_url" value="{{ old('image_url') }}" class="mt-1 block w-full rounded-md shadow-sm form-input">
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-rubylux-ruby border border-transparent rounded-md font-semibold text-xs text-rubylux-light uppercase tracking-widest hover:bg-rubylux-ruby-dark focus:outline-none focus:ring-2 focus:ring-rubylux-ruby focus:ring-offset-2 transition ease-in-out duration-150">
                                Simpan Kategori
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>