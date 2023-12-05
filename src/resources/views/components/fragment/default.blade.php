@if ($item)
<div
    @foreach ($item->options as $option)
        data-{{ $option->name }}="{{ $option->value }}"
    @endforeach
    >
    <img src="{{ $item->image }}" alt="{{ $item->title }}" width="100%">
    <h4>{{ $item->title }}</h4>
    <p>{{ $item->text }}</p>
</div>
@endif
