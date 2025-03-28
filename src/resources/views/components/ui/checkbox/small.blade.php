<div @class(['small-checkbox', 'disabled' => $disabled ?? false, 'hidden' => $hidden ?? false]) @if (!empty($color)) style="--switch-color: {{ $color }}" @endif>
    <input type="checkbox" name="{{ $name ?? 'checkbox' }}" id="{{ $id ?? 'checkbox' }}" value="{{ $value ?? 1 }}"
    @checked($checked ?? false) @disabled($disabled ?? false) title="{{ $title ?? '' }}"
    @if ($attributes) @foreach ($attributes as $attribute => $value) {{ $attribute }}="{{ $value }}" @endforeach @endif>
    <i></i>
</div>