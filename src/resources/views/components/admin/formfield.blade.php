<div class="form-field-box" data-id="{{ $field->id }}">
    <div @class(['form-field-position', 'inactive' => $field->active != 1]) draggable="true">
        {{ $field->position }}
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
                <a href="{{ route('admin.forms.fields.edit', ['form'=>$form, 'field'=>$field]) }}" class="inline-button circle-button alternate-button" title="{{ __('elfcms::default.edit') }}"></a>
                <form action="{{ route('admin.forms.fields.destroy', ['form'=>$form, 'field'=>$field]) }}" method="POST" data-submit="check">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="id" value="{{ $field->id }}">
                    <input type="hidden" name="name" value="{{ $field->title ?? $field->name }}">
                    <button type="submit" class="inline-button circle-button delete-button" title="{{ __('elfcms::default.delete') }}"></button>
                </form>
            </div>
        </div>
    </div>
</div>
