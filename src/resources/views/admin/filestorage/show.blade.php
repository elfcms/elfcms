@extends('elfcms::admin.layouts.form')
@section('head')
<link rel="stylesheet" href="{{ asset('elfcms/admin/css/popnotifi.css') }}">
@endsection
@section('formpage-content')
    @if (Session::has('success'))
    <div class="alert alert-alternate">{{ Session::get('success') }}</div>
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

    <div class="table-search-box">
        <a href="{{ route('admin.forms.groups.create',$form) }}" class="default-btn success-button icon-text-button light-icon plus-button">{{__('elfcms::default.create_form_field_group')}}</a>
    </div>

    @if (!empty($form->groups))

    <div class="form-groups" data-form="{{ $form->id }}">
        @foreach ($form->groups as $group)
            <div class="form-group-box" data-id="{{ $group->id }}">
                <div @class(['form-group-position', 'inactive' => $group->active != 1]) draggable="true" data-title="{{ $group->title ?? $group->name }}">
                    {{ $group->position }}
                </div>
                <div class="form-group-data">
                    <div class="form-group-title-box">
                        <div class="form-group-title">
                        @if ($group->active != 1)
                            <span class="form-group-inactive-title">[{{ __('elfcms::default.inactive') }}]</span>
                        @endif
                            {{ $group->title ?? $group->name }}
                        </div>
                        <div class="form-group-button-box">
                            <a href="{{ route('admin.forms.groups.edit', [$form, $group]) }}" class="inline-button circle-button alternate-button" title="{{ __('elfcms::default.edit') }}"></a>
                            <form action="{{ route('admin.forms.groups.destroy', [$form, $group]) }}" method="POST" data-submit="check">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="id" value="{{ $group->id }}">
                                <input type="hidden" name="name" value="{{ $group->title ?? $group->name }}">
                                <button type="submit" class="inline-button circle-button delete-button" title="{{ __('elfcms::default.delete') }}"></button>
                            </form>
                        </div>
                    </div>
                    <div class="form-group-fields" data-id="{{ $group->id }}">
                    @if (!empty($group->fields))
                        @foreach ($group->fields as $field)
                            <x-elfcms::admin.formfield :field="$field" :form="$form" />
                        @endforeach
                    @endif
                    </div>
                        <div class="form-group-fields-buttons">
                            <a href="{{ route('admin.forms.fields.create',['form'=>$form, 'group'=>$group->id]) }}" class="default-btn success-button icon-text-button light-icon plus-button">{{-- {{__('elfcms::default.create_field')}} --}}</a>
                        </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="form-groupless-fields">
        @foreach ($form->fieldsWithoutGroup as $field)
        <x-elfcms::admin.formfield :field="$field" :form="$form" />
        @endforeach
    </div>

    @endif

    <div class="table-search-box">
        <a href="{{ route('admin.forms.fields.create',$form) }}" class="default-btn success-button icon-text-button light-icon plus-button">{{__('elfcms::default.create_field')}}</a>
    </div>

<script>

const checkForms = document.querySelectorAll('form[data-submit="check"]')

if (checkForms) {
    checkForms.forEach(form => {
        form.addEventListener('submit',function(e){
            e.preventDefault();
            let formId = this.querySelector('[name="id"]').value,
                formName = this.querySelector('[name="name"]').value,
                self = this
            popup({
                title:'{{ __('elfcms::default.deleting_of_element') }}' + formId,
                content:'<p>{{ __('elfcms::default.are_you_sure_to_deleting_group') }}</p>',
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
@section('footerscript')
<script src="{{ asset('elfcms/admin/js/popnotifi.js') }}"></script>
<script src="{{ asset('elfcms/admin/js/grouporder.js') }}"></script>
<script src="{{ asset('elfcms/admin/js/fieldorder.js') }}"></script>
@endsection
