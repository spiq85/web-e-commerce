{{-- resources/views/admin/categories/_category_row.blade.php --}}

@php
    $indent = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $level); // Tambah indentasi
@endphp

<tr>
    <td class="px-6 py-4 whitespace-nowrap">{!! $indent !!}{{ $category->id_categories }}</td>
    <td class="px-6 py-4 whitespace-nowrap">
        {!! $indent !!}
        @if ($level > 0) &#x21B3; {{-- Panah ke bawah untuk anak --}} @endif
        {{ $category->category_name }}
    </td>
    <td class="px-6 py-4 whitespace-nowrap">{{ $category->parent->category_name ?? '---' }}</td> {{-- Tampilkan nama parent --}}
    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
        <a href="{{ route('admin.categories.edit', $category->id_categories) }}" class="text-rubylux-ruby hover:text-rubylux-ruby-dark mr-3">Edit</a>
        <form action="{{ route('admin.categories.destroy', $category->id_categories) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus kategori ini? Menghapus kategori akan menghapus produk yang tidak punya kategori!');">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-red-500 hover:text-red-700">Hapus</button>
        </form>
    </td>
</tr>

@foreach ($category->children as $child)
    @include('admin.categories._category_row', ['category' => $child, 'level' => $level + 1])
@endforeach