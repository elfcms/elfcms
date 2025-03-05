@extends('elfcms::admin.layouts.main')

@section('pagecontent')

    {{-- <div class="table-search-box">
        <a href="{{ route('admin.forms.create') }}" class="button success-button icon-text-button light-icon plus-button">{{__('elfcms::default.create_form')}}</a>
        <div class="table-search-result-title">
            @if (!empty($search))
                {{ __('elfcms::default.search_result_for') }} "{{ $search }}" <a href="{{ route('admin.user.users') }}" title="{{ __('elfcms::default.reset_search') }}">&#215;</a>
            @endif
        </div>
    </div>
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
    @endif --}}

    <div class="table-search-box">
        <a href="{{ route('admin.forms.create') }}" class="button round-button theme-button">
            {!! iconHtmlLocal('elfcms/admin/images/icons/plus.svg', svg: true) !!}
            <span class="button-collapsed-text">
                {{ __('elfcms::default.create_form') }}
            </span>
        </a>
    </div>

    <div class="grid-table-wrapper">
        <table class="grid-table table-cols" style="--first-col:65px; --last-col:180px; --minw:800px; --cols-count:8;">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>{{ __('elfcms::default.title') }}</th>
                    <th>{{ __('elfcms::default.name') }}</th>
                    <th>{{ __('elfcms::default.code') }}</th>
                    <th>{{ __('elfcms::default.created') }}</th>
                    <th>{{ __('elfcms::default.updated') }}</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            @foreach ($forms as $form)
                <tr data-id="{{ $form->id }}" @class(['inactive' => $form->active != 1])>
                    <td>{{ $form->id }}</td>
                    <td>
                        <a href="{{ route('admin.forms.show',$form->id) }}">
                            {{ $form->title }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('admin.forms.show',$form->id) }}">
                            {{ $form->name }}
                        </a>
                    </td>
                    <td>{{ $form->code }}</td>
                    <td>{{ $form->created_at }}</td>
                    <td>{{ $form->updated_at }}</td>
                    <td>{{ $form->active != 1 ? __('elfcms::default.inactive') : '' }}</td>
                    <td class="table-button-column">
                        <a href="{{ route('admin.form-results.form',$form->id) }}" class="button icon-button" title="{{ __('elfcms::default.showing_results_for_item') }}">
                            {!! iconHtmlLocal('elfcms/admin/images/icons/buttons/formresult.svg', svg: true) !!}
                        </a>
                        <a href="{{ route('admin.forms.show',$form->id) }}" class="button icon-button" title="{{ __('elfcms::default.edit_form_contents') }}">
                            {!! iconHtmlLocal('elfcms/admin/images/icons/buttons/list.svg', svg: true) !!}
                        </a>
                        <a href="{{ route('admin.forms.edit',$form->id) }}" class="button icon-button" title="{{ __('elfcms::default.edit_form_params') }}">
                            {!! iconHtmlLocal('elfcms/admin/images/icons/buttons/edit.svg', svg: true) !!}
                        </a>
                        <form action="{{ route('admin.forms.destroy',$form->id) }}" method="POST" data-submit="check">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="id" value="{{ $form->id }}">
                            <input type="hidden" name="name" value="{{ $form->name }}">
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
                        content:'<p>{{ __('elfcms::default.are_you_sure_to_deleting_form') }} "' + formName + '" (ID ' + formId + ')?</p>',
                        buttons:[
                            {
                                title:'{{ __('elfcms::default.delete') }}',
                                class:'button color-text-button red-button',
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
