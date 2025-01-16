@extends('elfcms::admin.layouts.form')

@section('formpage-content')
    {{-- <div class="table-search-box">
        <div class="table-search-result-title">
            @if (!empty($search))
                {{ __('elfcms::default.search_result_for') }} "{{ $search }}" <a href="{{ route('admin.user.users') }}" title="{{ __('elfcms::default.reset_search') }}">&#215;</a>
            @endif
        </div>
    </div> --}}
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
    <div class="widetable-wrapper">
        <table class="grid-table table-cols-6" style="--first-col:65px; --last-col:140px; --minw:800px">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>{{ __('elfcms::default.form') }}</th>
                    <th>{{ __('elfcms::default.name') }}</th>
                    <th>{{ __('elfcms::default.created') }}</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            @foreach ($results as $result)
                <tr data-id="{{ $result->id }}">
                    <td>{{ $form->id }}</td>
                    <td>
                        <a href="{{ route('admin.form-results.form',$result->form->id) }}">
                            {{ $result->form->title  ?? $result->form->name }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('admin.form-results.show',$result->id) }}">
                            {{-- {{ $result->name }} --}}
                        </a>
                    </td>
                    <td>{{ $form->created_at }}</td>
                    <td></td>
                    <td class="button-column non-text-buttons">
                        {{-- <a href="{{ route('admin.forms.show',$form->id) }}" class="default-btn content-button" title="{{ __('elfcms::default.edit_form_contents') }}"></a>
                        <a href="{{ route('admin.forms.edit',$form->id) }}" class="default-btn edit-button" title="{{ __('elfcms::default.edit_form_params') }}"></a>
                        <form action="{{ route('admin.forms.destroy',$form->id) }}" method="POST" data-submit="check">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="id" value="{{ $form->id }}">
                            <input type="hidden" name="name" value="{{ $form->name }}">
                            <button type="submit" class="default-btn delete-button" title="{{ __('elfcms::default.delete') }}"></button>
                        </form> --}}
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
