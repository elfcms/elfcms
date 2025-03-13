<div @class(['small-checkbox', 'disabled' => $disabled ?? false, 'hidden' => $hidden ?? false]) @if (!empty($color)) style="--checkbox-color: {{ $color }}" @endif>
    <input type="checkbox" name="{{ $name ?? 'checkbox' }}" id="{{ $id ?? 'checkbox' }}" value="{{ $value ?? 1 }}"
    @checked($checked ?? false) @disabled($disabled ?? false) title="{{ $title ?? '' }}">
    <i></i>
</div>