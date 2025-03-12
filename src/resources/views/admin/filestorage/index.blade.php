@extends('elfcms::admin.layouts.main')

@section('pagecontent')
    <div class="table-search-box">
        <a href="{{ route('admin.filestorage.create') }}" class="button round-button theme-button">
            {!! iconHtmlLocal('elfcms/admin/images/icons/plus.svg', svg: true) !!}
            <span class="button-collapsed-text">
                {{ __('elfcms::default.create_storage') }}
            </span>
        </a>
    </div>
    <div class="grid-table-wrapper">
        <table class="grid-table table-cols" style="--first-col:4rem; --last-col:11rem; --minw:50rem; --cols-count:7;">
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
                            <a href="{{ route('admin.filestorage.files.index', ['filestorage'=>$storage->id]) }}">
                                {{ $storage->name }}
                            </a>
                        </td>
                        <td>{{ $storage->path }}</td>
                        <td>{{ $storage->files->count() }}</td>
                        <td>{{ $storage->created_at }}</td>
                        <td>{{ $storage->updated_at }}</td>
                        <td class="table-button-column">
                            <a href="{{ route('admin.filestorage.files.index', $storage) }}" class="button icon-button"
                                title="{{ __('elfcms::default.edit_storage_contents') }}">
                                {!! iconHtmlLocal('elfcms/admin/images/icons/buttons/list.svg', svg: true) !!}
                            </a>
                            <a href="{{ route('admin.filestorage.edit', $storage) }}" class="button icon-button"
                                title="{{ __('elfcms::default.edit_storage_params') }}">
                                {!! iconHtmlLocal('elfcms/admin/images/icons/buttons/edit.svg', svg: true) !!}
                            </a>
                            <form action="{{ route('admin.filestorage.destroy', $storage) }}" method="POST"
                                data-submit="check">
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
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    let storageId = this.querySelector('[name="id"]').value,
                        storageName = this.querySelector('[name="name"]').value,
                        self = this
                    popup({
                        title: '{{ __('elfcms::default.deleting_of_storage') }}' + storageId,
                        content: '<p>{{ __('elfcms::default.are_you_sure_to_deleting_storage') }} "' +
                            storageName + '" (ID ' + storageId + ')?</p>',
                        buttons: [{
                                title: '{{ __('elfcms::default.delete') }}',
                                class: 'button color-text-button red-button',
                                callback: function() {
                                    self.submit()
                                }
                            },
                            {
                                title: '{{ __('elfcms::default.cancel') }}',
                                class: 'button color-text-button',
                                callback: 'close'
                            }
                        ],
                        class: 'danger'
                    })
                })
            })
        }
    </script>
@endsection
