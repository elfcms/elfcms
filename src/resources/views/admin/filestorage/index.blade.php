@extends('elfcms::admin.layouts.default')

@section('innerpage-content')
    <div class="table-search-box">
        <a href="{{ route('admin.filestorage.create') }}" class="button success-button icon-text-button light-icon plus-button">{{__('elfcms::default.create_storage')}}</a>
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
    @endif
    <div class="grid-table-wrapper">
        <table class="grid-table table-cols-7" style="--first-col:65px; --last-col:180px; --minw:800px; --cols-count:7;">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>{{ __('elfcms::default.name') }}</th>
                    <th>{{ __('elfcms::default.path') }}</th>
                    <th>{{ __('elfcms::default.files') }}</th>
                    <th>{{ __('elfcms::default.created') }}</th>
                    <th>{{ __('elfcms::default.updated') }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            @foreach ($storages as $storage)
                <tr data-id="{{ $storage->id }}">
                    <td>{{ $storage->id }}</td>
                    <td>
                        <a href="{{ route('admin.filestorage.show',$storage->id) }}">
                            {{ $storage->name }}
                        </a>
                    </td>
                    <td>{{ $storage->path }}</td>
                    <td>{{ $storage->files->count() }}</td>
                    <td>{{ $storage->created_at }}</td>
                    <td>{{ $storage->updated_at }}</td>
                    <td class="table-button-column">
                        <a href="{{ route('admin.filestorage.files.index',$storage) }}" class="button content-button" title="{{ __('elfcms::default.edit_storage_contents') }}"></a>
                        <a href="{{ route('admin.filestorage.edit',$storage) }}" class="button edit-button" title="{{ __('elfcms::default.edit_storage_params') }}"></a>
                        <form action="{{ route('admin.filestorage.destroy',$storage) }}" method="POST" data-submit="check">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="id" value="{{ $storage->id }}">
                            <input type="hidden" name="name" value="{{ $storage->name }}">
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
                    let storageId = this.querySelector('[name="id"]').value,
                        storageName = this.querySelector('[name="name"]').value,
                        self = this
                    popup({
                        title:'{{ __('elfcms::default.deleting_of_storage') }}' + storageId,
                        content:'<p>{{ __('elfcms::default.are_you_sure_to_deleting_storage') }} "' + storageName + '" (ID ' + storageId + ')?</p>',
                        buttons:[
                            {
                                title:'{{ __('elfcms::default.delete') }}',
                                class:'button color-button red-button',
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
