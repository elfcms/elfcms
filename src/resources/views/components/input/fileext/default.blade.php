<div class="input-image-button-box" id="{{ $boxId }}">
    <input type="hidden" name="{{ $code }}_path" id="{{ $code }}_path" value="{{ $value }}">
    <div class="input-image-button">
        <div class="delete-image @if (empty($value)) hidden @endif">&#215;</div>
        <div class="image-button-img">
            @if (!empty($inputData['value']))
                @if ($mime === 'image')
                    <img src="{{ file_path($inputData['value']) }}" alt="">
                @else
                    <img src="{{ asset($icon) }}" alt="">
                @endif
            @elseif (!empty($value))
                @if ($mime === 'image')
                    <img src="{{ file_path($value) }}" alt="">
                @else
                    <img src="{{ asset($icon) }}" alt="">
                @endif
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
        <input type="file" name="{{ $code }}" id="{{ $code }}" accept="{{ $accept ?? '*/*' }}">
    </div>
    @if ($download && !empty($value))
        <a href="{{ file_path($value) }}" class="input-file-download" download
            title="{{ __('elfcms::default.download') }}"></a>
    @endif
</div>
<script src="{{ asset('elfcms/admin/js/fileextinput.js') }}"></script>
<script>
    const {{ $jsName }} = document.querySelector('#{{ $code }}')
    if ({{ $jsName }}) {
        inputFileExtComponent({{ $jsName }})
    }
</script>
