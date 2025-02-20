<div class="checkbox-switch-wrapper">
    <div @class(['checkbox-switch', $style, 'disabled' => ($disabled || $readonly)]) {!! $color !!}>
        <input type="checkbox" name="{{ $code }}" id="{{ $id }}" value="1" @if(!empty($checked)) checked @endif @if(!empty($disabled || $readonly)) disabled @endif>
        <i></i>
    </div>
    <label for="{{ $id }}">
        {{ $label }}
    </label>
</div>
