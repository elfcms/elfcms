<div @class(['switchbox', 'disabled' => $disabled ?? false]) @if (!empty($color)) style="--switch-color: {{ $color }}" @endif>
    <input type="checkbox" name="{{ $name ?? 'checkbox' }}" id="{{ $id ?? 'checkbox' }}" value="{{ $value ?? 1 }}"
        @checked($checked ?? false) @disabled($disabled ?? false) title="{{ $title ?? '' }}">
    <i></i>
</div>
