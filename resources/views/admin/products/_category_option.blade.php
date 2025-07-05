@php
$indent = str_repeat('- ', $level);
@endphp

<option value="{{$category->id_categories}}" {{ $selectedId == $category->id_categories ? 'selected' : '' }}>
        {!! $indent !!}{{ $category->category_name }}
</option>

@if ($category->children->count())
        @foreach($category->children as $child)
                @include('admin.products._category_option', ['category' => $child, 'level' + 1, 'selectedId' => $selectedId])
        @endforeach
@endif
