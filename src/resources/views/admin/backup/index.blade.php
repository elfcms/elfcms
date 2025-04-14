@extends('elfcms::admin.layouts.main')

@section('pagecontent')
    <div class="table-search-box">
        <a href="{{ route('admin.backup.settings') }}" class="button round-button theme-button">
            {!! iconHtmlLocal('elfcms/admin/images/icons/settings.svg', svg: true) !!}
            <span class="button-collapsed-text">
                {{ __('elfcms::default.settings') }}
            </span>
        </a>
    </div>

    <div class="grid-table-wrapper">
        <table class="grid-table table-cols" style="--first-col:4rem; --minw:50rem; --cols-count:10;">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>{{ __('elfcms::default.created') }}</th>
                    <th>{{ __('elfcms::default.name') }}</th>
                    <th>{{ __('elfcms::default.type') }}</th>
                    <th>{{ __('elfcms::default.status') }}</th>
                    <th>{{ __('elfcms::default.file') }}</th>
                    <th>{{ __('elfcms::default.size') }}</th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($backups as $backup)
                    <tr data-id="{{ $backup->id }}" @class(['inactive' => $backup->active != 1])>
                        <td>{{ $backup->id }}</td>
                        <td>{{ $backup->created_at }}</td>
                        <td>{{ $backup->name }}</td>
                        <td class="backup-type-icon backup-type-{{ $backup->type }}" title="{{ __('elfcms::default.'.$backup->type) }}">
                            @if ($backup->type == 'files')
                            {!! iconHtmlLocal('elfcms/admin/images/icons/archive_file.svg', svg: true, width:28, height:28) !!}
                            @elseif ($backup->type == 'database')
                            {!! iconHtmlLocal('elfcms/admin/images/icons/database.svg', svg: true) !!}
                            @else
                            {{ $backup->type }}
                            @endif
                        </td>
                        <td class="backup-status-{{ $backup->status->name }}">{{ !empty($backup->status->lang_title) ? __($backup->status->lang_title) : $backup->status->name ?? '' }}
                        </td>
                        <td @class(['font-bold' => true, 'failed-string' => !$backup->isFile])>{{ $backup->file_path }}</td>
                        <td class="right_text number_text">{{ $backup->size }}</td>
                        <td class="table-button-column">
                            @if ($backup->isFile)
                            <a href="{{ route('admin.backup.restore_page', $backup) }}" class="button icon-button"
                                title="{{ __('elfcms::default.restore') }}">
                                {!! iconHtmlLocal('elfcms/admin/images/icons/backup_restore.svg', svg: true) !!}
                            </a>
                            @endif
                        </td>
                        <td class="table-button-column">
                            @if ($backup->isFile)
                                <a href="{{ route('admin.backup.download', $backup) }}" class="button icon-button"
                                    title="{{ __('elfcms::default.download') }}">
                                    {!! iconHtmlLocal('elfcms/admin/images/icons/download_file.svg', svg: true) !!}
                                </a>
                            @endif
                        </td>
                        <td class="table-button-column">
                            <form action="{{ route('admin.backup.delete', $backup) }}" method="POST" data-submit="check">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="id" value="{{ $backup->id }}">
                                <input type="hidden" name="name" value="{{ $backup->name }}">
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
    {{ $backups->links('elfcms::admin.layouts.pagination') }}

    <script>
        const checkForms = document.querySelectorAll('form[data-submit="check"]')

        if (checkForms) {
            checkForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    let backupName = this.querySelector('[name="name"]').value,
                        self = this
                    popup({
                        title: '{{ __('elfcms::default.deleting_backup') }}',
                        content: '<p>{{ __('elfcms::default.confirm_delete') }} <br> Backup: "' +
                            backupName + '"</p>',
                        buttons: [{
                                title: '{{ __('elfcms::default.delete') }}',
                                class: 'button color-text-button danger-button',
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
