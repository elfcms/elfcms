<div class="input-image-button-box" id="{{ $boxId }}">
    <input type="hidden" name="{{ $code }}_path" id="{{ $code }}_path" value="{{ $value }}">
    <div class="input-image-button">
        <div class="delete-image @if (empty($value)) hidden @endif">&#215;</div>
        <div class="image-button-img">
            @if (!empty($inputData['value']))
                @if ($mime === 'image')
                    <img src="{{ file_path($inputData['value']) ?? fsPreview($inputData['value']) }}" alt="" data-i="1">
                @else
                    <img src="{{ asset($icon) }}" alt="" data-i="2">
                @endif
            @elseif (!empty($value))
                @if ($mime === 'image')
                    <img src="{{ file_path($value) ?? fsPreview($value) }}" alt="" data-i="3">
                @else
                    <img src="{{ asset($icon) }}" alt="" data-i="4">
                @endif
            @else
                <img src="{{ asset('/elfcms/admin/images/icons/upload.png') }}" alt="" data-i="5">
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
    @if ($download && !empty($file))
        <a href="{{ route('files',$file->id) }}" class="input-file-download" download
            title="{{ $file->download_name ?? $file->name ?? __('elfcms::default.download') }}"></a>
    @endif
</div>
<script src="{{ asset('elfcms/admin/js/fileextinput.js') }}"></script>
<script>
    const {{ $jsName }} = document.querySelector('#{{ $code }}')
    if ({{ $jsName }}) {
        inputFileExtComponent({{ $jsName }})
    }
</script>
