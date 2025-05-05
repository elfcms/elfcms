@extends('elfcms::admin.layouts.main')

@section('pagecontent')
    <div class="table-search-box">
        <a href="{{ route('admin.form-results.index') }}" class="button round-button theme-button" style="color:var(--default-color);">
            {!! iconHtmlLocal('elfcms/admin/images/icons/buttons/arrow_back.svg', svg: true) !!}
            <span class="button-collapsed-text">
                {{ __('elfcms::default.back') }}
            </span>
        </a>
    </div>
    <div class="grid-table-wrapper">
        <table class="grid-table table-cols" style="--first-col:4rem; --last-col:13rem; --minw:50rem; --cols-count:{{ count($fields) > 0 ? count($fields) + 2 : 3 }};">
            <thead>
                <tr>
                    <th>ID</th>
                    @foreach (array_values($fields) as $title)
                    <th>{{ $title }}</th>
                    @endforeach
                    @if (count($fields) == 0)
                        <th></th>
                    @endif
                    <th>{{ __('elfcms::default.created') }}</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($results as $result)
                <tr data-id="{{ $result->id }}">
                    <td>{{ $result->id }}</td>
                    @foreach (array_keys($fields) as $name)
                    <td>
                        @if (!empty($result->data[$name]))
                        <a href="{{ route('admin.form-results.show',['form'=>$result->form->id,'result'=>$result->id]) }}">{{ mb_strimwidth($result->data[$name],0,20,'...') }}</a>
                        @endif
                    </td>
                    @endforeach
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
