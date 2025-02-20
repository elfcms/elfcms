<div class="inputfile" id="{{ $boxId }}">
    <input type="hidden" name="{{ $params['value_name'] }}" value="{{ $params['value'] }}">
    @if (empty($params['value']))
        <div class="inputfile-icon default-icon">
            {!! iconHtmlLocal('/elfcms/admin/images/icons/upload.svg', svg:true) !!}
        </div>
    @elseif ($params['isImage']))
        <img src="{{ file_path($params['value']) }}" alt="" class="inputfile-icon image-icon">
    @elseif (!empty($params['icon']))
        <div class="inputfile-icon svg-icon">
            {!! iconHtmlLocal($params['icon'], svg:true) !!}
        </div>
    @else
    <div class="inputfile-icon svg-icon">
        {!! iconHtmlLocal('/elfcms/admin/images/icons/filestorage/none.svg', svg:true) !!}
    </div>
    @endif
    <input type="file" name="{{ $params['value_name'] }}">
</div>








{{-- <div class="input-file-button-box" id="{{ $boxId }}">
    <input type="hidden" name="{{ $code }}_path" id="{{ $jsName }}_path" value="{{$value}}">
    <div class="input-file-button">
        <div class="delete-file @if (empty($value)) hidden @endif">&#215;</div>
            <div class="file-button-text">
            @if (!empty($value))
                {{ basename($value) ?? __('elfcms::default.change_file') }}
            @else
                {{ __('elfcms::default.choose_file') }}
            @endif
            </div>
            <input type="file" name="{{ $code }}" id="{{ $jsName }}" accept="{{ $accept ?? '*' }}" data-title="{{ __('elfcms::default.choose_file') }}"
            title="@if (!empty($value))
            {{ basename($value) ?? __('elfcms::default.change_file') }}
            @else
                {{ __('elfcms::default.choose_file') }}
            @endif"
            />
    </div>
    @if ($download && !empty($value))
        <a href="{{ $value }}" class="input-file-download" download title="{{ __('elfcms::default.download') }}"></a>
    @endif
</div>
<script src="{{ asset('elfcms/admin/js/fileinput.js') }}"></script>
<script>
    const {{ $jsName }} = document.querySelector('#{{ $jsName }}')
    if ({{ $jsName }}) {
        inputFileComponent({{ $jsName }})
    }
</script>
 --}}
