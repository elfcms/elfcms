@extends('elfcms::admin.layouts.form')

@section('formpage-content')
    <div class="table-search-box">
        <a href="{{ route('admin.form.groups.create') }}" class="default-btn success-button icon-text-button light-icon plus-button">{{__('elfcms::default.create_form_field_group')}}</a>
    </div>
    @if (Session::has('groupdeleted'))
    <div class="alert alert-alternate">{{ Session::get('groupdeleted') }}</div>
    @endif
    @if (Session::has('groupedited'))
    <div class="alert alert-alternate">{{ Session::get('groupedited') }}</div>
    @endif
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
    <h2>{{ __('elfcms::default.groups_of_form_name_id',['name'=>$form->name,'id'=>$form->id]) }}</h2>
    <div class="widetable-wrapper">
        <table class="grid-table formgrouptable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>{{ __('elfcms::default.form') }}</th>
                    <th>{{ __('elfcms::default.title') }}</th>
                    <th>{{ __('elfcms::default.name') }}</th>
                    <th>{{ __('elfcms::default.code') }}</th>
                    <th>{{ __('elfcms::default.created') }}</th>
                    <th>{{ __('elfcms::default.updated') }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            @foreach ($groups as $group)
                <tr data-id="{{ $group->id }}">
                    <td class="subline-{{ $group->level }}">{{ $group->id }}</td>
                    <td>
                        <a href="{{ route('admin.form.forms.show',$group->form->id) }}">
                            {{ $group->form->title }} [{{ $group->form->id }}]
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('admin.form.groups.show',$group->id) }}">
                            {{ $group->title }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('admin.form.groups.show',$group->id) }}">
                            {{ $group->name }}
                        </a>
                    </td>
                    <td>{{ $group->code }}</td>
                    <td>{{ $group->created_at }}</td>
                    <td>{{ $group->updated_at }}</td>
                    <td class="button-column">
                        <form action="{{ route('admin.form.fields.create') }}" method="GET">
                            <input type="hidden" name="group_id" value="{{ $group->id }}">
                            <button type="submit" class="default-btn submit-button">{{ __('elfcms::default.add_field') }}</button>
                        </form>
                        <a href="{{ route('admin.form.groups.edit',$group->id) }}" class="default-btn edit-button">{{ __('elfcms::default.edit') }}</a>
                        <form action="{{ route('admin.form.groups.destroy',$group->id) }}" method="POST" data-submit="check">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="id" value="{{ $group->id }}">
                            <input type="hidden" name="name" value="{{ $group->name }}">
                            <button type="submit" class="default-btn delete-button">{{ __('elfcms::default.delete') }}</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>


    <h2>{{ __('elfcms::default.fields_of_form_name_id',['name'=>$form->name,'id'=>$form->id]) }}</h2>
    <div class="widetable-wrapper">
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
                    <td class="button-column">
                        {{--<form action="{{ route('admin.form.options.create') }}" method="GET">
                            <input type="hidden" name="field_id" value="{{ $field->id }}">
                            <button type="submit" class="default-btn submit-button">{{ __('elfcms::default.add_option') }}</button>
                        </form>--}}
                        <a href="{{ route('admin.form.fields.edit',$field->id) }}" class="default-btn edit-button">{{ __('elfcms::default.edit') }}</a>
                        <form action="{{ route('admin.form.fields.destroy',$field->id) }}" method="POST" data-submit="check">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="id" value="{{ $field->id }}">
                            <input type="hidden" name="name" value="{{ $field->name }}">
                            <button type="submit" class="default-btn delete-button">{{ __('elfcms::default.delete') }}</button>
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
                                class:'default-btn delete-button',
                                callback: function(){
                                    self.submit()
                                }
                            },
                            {
                                title:'{{ __('elfcms::default.cancel') }}',
                                class:'default-btn cancel-button',
                                callback:'close'
                            }
                        ],
                        class:'danger'
                    })
                })
            })
        }

        const checkGroups = document.querySelectorAll('form[data-submit="check"]')

        if (checkGroups) {
            checkGroups.forEach(group => {
                group.addEventListener('submit',function(e){
                    e.preventDefault();
                    let groupId = this.querySelector('[name="id"]').value,
                        groupName = this.querySelector('[name="name"]').value,
                        self = this
                    popup({
                        title:'{{ __('elfcms::default.deleting_of_element') }}' + groupId,
                        content:'<p>{{ __('elfcms::default.are_you_sure_to_deleting_group') }} "' + groupName + '" (ID ' + groupId + ')?</p>',
                        buttons:[
                            {
                                title:'{{ __('elfcms::default.delete') }}',
                                class:'default-btn delete-button',
                                callback: function(){
                                    self.submit()
                                }
                            },
                            {
                                title:'{{ __('elfcms::default.cancel') }}',
                                class:'default-btn cancel-button',
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
