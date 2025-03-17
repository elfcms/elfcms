@extends('elfcms::admin.layouts.form')

@section('formpage-content')

    @if (Session::has('fielddeleted'))
    <div class="alert alert-alternate">{{ Session::get('fielddeleted') }}</div>
    @endif
    @if (Session::has('fieldedited'))
    <div class="alert alert-alternate">{{ Session::get('fieldedited') }}</div>
    @endif
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <div class="grid-table-wrapper">
        <table class="grid-table formfieldtable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>{{ __('elfcms::default.title') }}</th>
                    <th>{{ __('elfcms::default.name') }}</th>
                    <th>{{ __('elfcms::default.form') }}</th>
                    <th>{{ __('elfcms::default.group') }}</th>
                    <th>{{ __('elfcms::default.field_type') }}</th>
                    <th>{{ __('elfcms::default.created') }}</th>
                    <th>{{ __('elfcms::default.updated') }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            @foreach ($fields as $field)
                <tr data-id="{{ $field->id }}" class="level-{{ $field->level }}@empty ($field->active) inactive @endempty">
                    <td class="subline-{{ $field->level }}">{{ $field->id }}</td>
                    <td>
                        <a href="{{ route('admin.form.fields.edit',$field->id) }}">
                            {{ $field->title }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('admin.form.fields.edit',$field->id) }}">
                            {{ $field->name }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('admin.form.forms.show',$field->form->id) }}">
                            {{ $field->form->name }}
                        </a>
                    </td>
                    <td>
                        @if ($field->group)
                        <a href="{{ route('admin.form.groups.show',$field->group->id) }}">
                            {{ $field->group->name }}
                        </a>
                        @endif
                    </td>
                    <td>{{ $field->type->name }}</td>
                    <td>{{ $field->created_at }}</td>
                    <td>{{ $field->updated_at }}</td>
                    <td class="table-button-column">
                        <a href="{{ route('admin.form.fields.edit',$field->id) }}" class="button edit-button" title="{{ __('elfcms::default.edit') }}"></a>
                        <form action="{{ route('admin.form.fields.destroy',$field->id) }}" method="POST" data-submit="check">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="id" value="{{ $field->id }}">
                            <input type="hidden" name="name" value="{{ $field->name }}">
                            <button type="submit" class="button icon-button icon-alarm-button"
                                        title="{{ __('elfcms::default.delete') }}">
                                        {!! iconHtmlLocal('elfcms/admin/images/icons/buttons/delete.svg', svg: true) !!}
                                    </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <script>
        const checkFields = document.querySelectorAll('form[data-submit="check"]')

        if (checkFields) {
            checkFields.forEach(field => {
                field.addEventListener('submit',function(e){
                    e.preventDefault();
                    let fieldId = this.querySelector('[name="id"]').value,
                        fieldName = this.querySelector('[name="name"]').value,
                        self = this
                    popup({
                        title:'{{ __('elfcms::default.deleting_of_element') }}' + fieldId,
                        content:'<p>{{ __('elfcms::default.are_you_sure_to_deleting_field') }} "' + fieldName + '" (ID ' + fieldId + ')?</p>',
                        buttons:[
                            {
                                title:'{{ __('elfcms::default.delete') }}',
                                class:'button color-text-button danger-button',
                                callback: function(){
                                    self.submit()
                                }
                            },
                            {
                                title:'{{ __('elfcms::default.cancel') }}',
                                class:'button color-text-button',
                                callback:'close'
                            }
                        ],
                        class:'danger'
                    })
                })
            })
        }
    </script>

@endsection
