<div class="input-image-button-box" id="{{ $boxId }}">
    <input type="hidden" name="{{ $valueName }}" id="{{ $valueId ?? $valueName }}" value="{{$value}}">
    <div class="input-image-button">
        <div class="delete-image @if (empty($value)) hidden @endif">&#215;</div>
        <div class="image-button-img">
        @if (!empty($value))
            <img src="{{ asset(file_path($value)) }}" alt="">
        @else
            <img src="{{ asset('/elfcms/admin/images/icons/upload.png') }}" alt="">
        @endif
        </div>
        <div class="image-button-text">
        @if (!empty($value))
            {{ __('elfcms::default.change_file') }}
        @else
            {{ __('elfcms::default.choose_file') }}
        @endif
        </div>
        <input type="file" name="{{ $inputName }}" id="{{ $jsName }}" accept="{{ $accept ?? 'image/*' }}">
    </div>
    @if ($download && !empty($value))
        <a href="{{ $value }}" class="input-file-download" download title="{{ __('elfcms::default.download') }}"></a>
    @endif
</div>
<script src="{{ asset('elfcms/admin/js/imageinput.js') }}"></script>
<script>
    const {{ $jsName }} = document.querySelector('#{{ $jsName }}')
    if ({{ $jsName }}) {
        inputImgComponent({{ $jsName }})
    }
</script>
