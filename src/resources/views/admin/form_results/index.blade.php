@extends('elfcms::admin.layouts.main')

@section('pagecontent')
    <div class="grid-table-wrapper">
        <table class="grid-table table-cols" style="--first-col:4rem; --last-col:200px; --minw:600px; --cols-count:4;">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>{{ __('elfcms::default.form') }}</th>
                    <th>{{ __('elfcms::default.content') }}</th>
                    <th>{{ __('elfcms::default.created') }}</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($results as $result)
                <tr data-id="{{ $result->id }}">
                    <td>{{ $result->form->id }}</td>
                    <td>
                        <a href="{{ route('admin.form-results.form',$result->form->id) }}">
                            {{ $result->form->title  ?? $result->form->name }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('admin.form-results.show',['form'=>$result->form->id,'result'=>$result->id]) }}">
                            {{ isset($result->data['message']) ? mb_strimwidth($result->data['message'],0,20,'...') : (isset($result->data['name']) ? $result->data['name'] : __('elfcms::default.message').' #'.$result->id) }}
                        </a>
                    </td>
                    <td>{{ $result->created_at }}</td>
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
