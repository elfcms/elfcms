<div class="checkbox-switch-wrapper">
    <div @class(['checkbox-switch', $style]) {!! $color !!}>
        <input type="checkbox" name="{{ $code }}" id="{{ $code }}" value="1" @if(!empty($checked)) checked @endif>
        <i></i>
    </div>
    <label for="{{ $code }}">
        {{ $label }}
    </label>
</div>
