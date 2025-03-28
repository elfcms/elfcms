<div class="form-field-box" data-id="{{ $field->id }}">
    <div @class(['form-field-position', 'inactive' => $field->active != 1]) draggable="true" data-title="{{ $field->title ?? $field->name }}">
        {!! iconHtmlLocal('elfcms/admin/images/icons/buttons/drag_indicator_slim.svg', svg: true) !!}
        <span>{{ $field->position }}</span>
    </div>
    <div class="form-field-data">
        <div class="form-field-title-box">
            <div class="form-field-title">
            @if ($field->active != 1)
                <span class="form-field-inactive-title">[{{ __('elfcms::default.inactive') }}]</span>
            @endif
                {{ $field->title ?? $field->name }}
            </div>
            <div class="form-field-button-box">
                <a href="{{ route('admin.forms.fields.edit', ['form'=>$form, 'field'=>$field]) }}" class="button icon-button" title="{{ __('elfcms::default.edit') }}">
                    {!! iconHtmlLocal('elfcms/admin/images/icons/buttons/edit.svg', svg: true) !!}
                </a>
                <form action="{{ route('admin.forms.fields.destroy', ['form'=>$form, 'field'=>$field]) }}" method="POST" data-submit="check">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="id" value="{{ $field->id }}">
                    <input type="hidden" name="name" value="{{ $field->title ?? $field->name }}">
                    <button type="submit" class="button icon-button icon-alarm-button" title="{{ __('elfcms::default.delete') }}">
                        {!! iconHtmlLocal('elfcms/admin/images/icons/buttons/delete.svg', svg: true) !!}
                    </button>
                </form>
            </div>
        </div>
        <div class="form-field-type">
            {{ $field->type->name }}
        </div>
    </div>
</div>
