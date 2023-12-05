@if ($item)
<div>
    <img src="{{ $item->image }}" alt="{{ $item->title }}" width="100%">
    <h4>{{ $item->title }}</h4>
    <p>{{ $item->text }}</p>
    <table>
        @foreach ($item->options as $option)
        <tr>
            <td>{{ $option->name }}</td>
            <td>{{ $option->value }}</td>
        </tr>
        @endforeach
    </table>
</div>
@endif
