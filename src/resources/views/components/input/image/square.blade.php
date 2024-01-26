<div class="input-image-button-box" id="{{ $boxId }}">
    <input type="hidden" name="{{ $inputData['code'] ?? $code }}_path" id="{{ $inputData['code'] ?? $code }}_path" value="{{$inputData['value'] ?? $value}}">
    <div class="input-image-button">
        <div class="delete-image @if (empty($inputData['value'] ?? $value)) hidden @endif">&#215;</div>
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
        @if (!empty($inputData['value'] ?? $value))
            {{ __('elfcms::default.change_file') }}
        @else
            {{ __('elfcms::default.choose_file') }}
        @endif
        </div>
        <input type="file" name="{{ $inputData['code'] ?? $code }}" id="{{ $inputData['code'] ?? $code }}" accept="{{ $accept ?? 'image/*' }}">
    </div>
</div>
<script src="{{ asset('elfcms/admin/js/imageinput.js') }}"></script>
<script>
    const {{ Str::camel($inputData['code']) }} = document.querySelector('#{{ $inputData['code'] }}')
    if ({{ Str::camel($inputData['code']) }}) {
        inputImgComponent({{ Str::camel($inputData['code']) }})
    }
</script>
