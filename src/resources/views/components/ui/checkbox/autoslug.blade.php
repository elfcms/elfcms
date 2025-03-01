<div @class(['switchbox', 'autoslug-wrapper', 'disabled' => $disabled ?? false]) @if (!empty($color)) style="--switch-color: {{ $color }}" @endif>
    <input type="checkbox" value="{{ $value ?? 1 }}" @checked($checked ?? false) @disabled($disabled ?? false) title="{{ $title ?? '' }}" data-text-id="{{ $textid ?? '' }}" data-slug-id="{{ $slugid ?? '' }}" class="autoslug">
    <i></i>
</div>
@once
    @push('footerscript')
    <script>
        autoSlug('.autoslug');
    </script>
    @endpush
@endonce
