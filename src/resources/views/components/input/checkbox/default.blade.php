<div class="checkbox-switch-wrapper">
    <div @class(['checkbox-switch', $style]) {!! $color !!}>
        <input type="checkbox" name="{{ $code }}" id="{{ $id }}" value="1" @if(!empty($checked)) checked @endif @if(!empty($disabled)) disabled @endif @if(!empty($readonly)) readonly @endif>
        <i></i>
    </div>
    <label for="{{ $id }}">
        {{ $label }}
    </label>
</div>
