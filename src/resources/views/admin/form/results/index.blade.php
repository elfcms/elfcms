@extends('elfcms::admin.layouts.form')

@section('formpage-content')

    @if (Session::has('formdeleted'))
    <div class="alert alert-alternate">{{ Session::get('formdeleted') }}</div>
    @endif
    @if (Session::has('formedited'))
    <div class="alert alert-alternate">{{ Session::get('formedited') }}</div>
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
        <table class="grid-table formresulttable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>{{ __('elfcms::default.form') }}</th>
                    <th>{{ __('elfcms::default.user') }}</th>
                    <th>{{ __('elfcms::default.created') }}</th>
                    <th>{{ __('elfcms::default.updated') }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            @foreach ($results as $result)
                <tr data-id="{{ $result->id }}">
                    <td class="subline-{{ $result->level }}">{{ $result->id }}</td>
                    <td>
                        <a href="{{ route('admin.form.results.show',$result->id) }}">
                            {{ $result->form->title }}
                        </a>
                    </td>
                    <td>{{ $result->code }}</td>
                    <td>{{ $result->created_at }}</td>
                    <td>{{ $result->updated_at }}</td>
                    <td class="button-column">
                        <form action="{{ route('admin.form.results.destroy',$result->id) }}" method="POST" data-submit="check">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="id" value="{{ $result->id }}">
                            <input type="hidden" name="name" value="{{ $result->name }}">
                            <button type="submit" class="button delete-button">{{ __('elfcms::default.delete') }}</button>
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
                                class:'button delete-button',
                                callback: function(){
                                    self.submit()
                                }
                            },
                            {
                                title:'{{ __('elfcms::default.cancel') }}',
                                class:'button cancel-button',
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
