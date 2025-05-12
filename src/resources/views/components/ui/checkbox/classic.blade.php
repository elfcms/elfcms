@props([
    'checked' => false,
    'disabled' => false,
    'hidden' => false,
    'color' => null,
    'id' => 'checkbox',
    'name' => 'checkbox',
    'value' => 1,
    'title' => '',
    'attributes' => [],
])

<div @class([
    'classic-checkbox',
    'disabled' => $disabled,
    'hidden' => $hidden,
]) @if ($color) style="--switch-color: {{ $color }}" @endif>
    <input type="checkbox" name="{{ $name }}" id="{{ $id }}" value="{{ $value }}"
        @if ($checked === true) checked @endif @if ($disabled) disabled @endif
        title="{{ $title }}"
        @foreach ($attributes as $attribute => $val) {{ $attribute }}="{{ $val }}" @endforeach>
    <i></i>
</div>
