<div class="input-image-button-box" id="{{ $boxId }}">
    <input type="hidden" name="{{ $code }}_path" id="{{ $code }}_path" value="{{$value}}">
    <div class="input-image-button">
        <div class="delete-image @if (empty($value)) hidden @endif">&#215;</div>
        <div class="image-button-img">
        @if (!empty($inputData['value']))
            <img src="{{ asset($inputData['value']) }}" alt="">
        @elseif (!empty($value))
            <img src="{{ asset($value) }}" alt="">
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
        <input type="file" name="{{ $code }}" id="{{ $code }}" accept="{{ $accept ?? 'image/*' }}">
    </div>
</div>
<script src="{{ asset('elfcms/admin/js/imageinput.js') }}"></script>
<script>
    const {{ $jsName }} = document.querySelector('#{{ $code }}')
    if ({{ $jsName }}) {
        inputImgComponent({{ $jsName }})
    }
</script>
