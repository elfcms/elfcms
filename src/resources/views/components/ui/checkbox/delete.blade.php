<div @class(['switchbox', 'delete-box-wrapper', 'disabled' => $disabled ?? false, 'hidden' => $hidden ?? false]) style="--switch-color: {{ $color ?? 'var(--red-color)' }}">
    <input type="checkbox" name="{{ $name ?? 'checkbox' }}" id="{{ $id ?? 'checkbox' }}" value="{{ $value ?? 1 }}" @checked($checked ?? false) @disabled($disabled ?? false) title="{{ $title ?? '' }}" data-id="{{ $dataid ?? '' }}" class="delete-box" @if (!empty($click) || !empty($onclick)) onclick="{{ $click ?? $onclick }}" @endif>
    <i></i>
</div>