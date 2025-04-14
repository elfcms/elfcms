@extends('elfcms::admin.layouts.main')

@section('pagecontent')
    <div class="table-search-box">
        <a href="{{ route('admin.backup.index') }}" class="button round-button theme-button">
            {!! iconHtmlLocal('elfcms/admin/images/icons/buttons/arrow_back.svg', svg: true) !!}
            <span class="button-collapsed-text">
                {{ __('elfcms::default.back') }}
            </span>
        </a>
    </div>
    <div class="item-form">
        <h2>{{ __('elfcms::default.restoring_backup', ['file' => $backup->name ?? '']) }}</h2>
        <form action="{{ route('admin.backup.restore', $backup->id) }}" method="POST" enctype="multipart/form-data"
            data-submit="check" id="restore-form">
            @csrf
            @method('POST')
            <input type="hidden" name="name" value="{{ $backup->name }}">
            <div class="colored-rows-box">
                <div class="input-box">
                    <div class="full-width">
                        <div class="backup-type-restore-title backup-type-icon backup-type-{{ $backup->type }}">
                            @if ($backup->type == 'files')
                                {!! iconHtmlLocal('elfcms/admin/images/icons/archive_file.svg', svg: true) !!}
                            @elseif ($backup->type == 'database')
                                {!! iconHtmlLocal('elfcms/admin/images/icons/database.svg', svg: true) !!}
                            @endif
                            <strong>
                                {{ __('elfcms::default.restore_from_' . $backup->type) }}
                            </strong>
                        </div>
                        @if (!$backup->isFile)
                            <div class="alert alert-error">
                                {{ __('elfcms::default.backup_file_not_found', ['file' => storage_path($backup->file_path)]) }}
                            </div>
                        @elseif ($backup->status->name != 'success')
                            <div class="alert alert-warning">
                                <strong>{{ __('elfcms::default.warning') }}:</strong>
                                {{ __('elfcms::default.backup_not_completed') }}
                            </div>
                        @endif
                    </div>
                </div>
                @if ($backup->isFile)
                    <div class="input-box">
                        <div class="small-checkbox-wrapper full-width">
                            <div class="small-checkbox" style="--switch-color:var(--default-color);">
                                <input type="checkbox" name="types[]" id="type_{{ $backup->type }}"
                                    value="{{ $backup->type }}" @disabled(!$backup->isFile) @checked($backup->isFile)>
                                <i></i>
                            </div>
                            <label for="type_{{ $backup->type }}"
                                @class(['failed-string' => !$backup->isFile])>{{ $backup->file_path }}</label>
                        </div>
                    </div>
                @endif
                @if (!empty($altBackup))
                    <div class="input-box input-box-top-line">
                        <div class="full-width">
                            <div class="backup-type-restore-title backup-type-icon backup-type-{{ $altBackup->type }}">
                                @if ($altBackup->type == 'files')
                                    {!! iconHtmlLocal('elfcms/admin/images/icons/archive_file.svg', svg: true, width: 28, height: 28) !!}
                                @elseif ($altBackup->type == 'database')
                                    {!! iconHtmlLocal('elfcms/admin/images/icons/database.svg', svg: true) !!}
                                @endif
                                <strong>
                                    {{ __('elfcms::default.restore_from_' . $altBackup->type) }}
                                </strong>
                            </div>
                            @if (!$altBackup->isFile)
                                <div class="alert alert-error">
                                    {{ __('elfcms::default.backup_file_not_found', ['file' => storage_path($altBackup->file_path)]) }}
                                </div>
                            @elseif ($altBackup->status->name != 'success')
                                <div class="alert alert-warning">
                                    <strong>{{ __('elfcms::default.warning') }}:</strong>
                                    {{ __('elfcms::default.backup_not_completed') }}
                                </div>
                            @endif
                        </div>
                    </div>
                    @if ($altBackup->isFile)
                        <div class="input-box">
                            <div class="small-checkbox-wrapper full-width">
                                <div class="small-checkbox" style="--switch-color:var(--default-color);">
                                    <input type="checkbox" name="types[]" id="type_{{ $altBackup->type }}"
                                        value="{{ $altBackup->type }}" @disabled(!$altBackup->isFile)>
                                    <i></i>
                                </div>
                                <label for="type_{{ $altBackup->type }}"
                                    @class(['failed-string' => !$altBackup->isFile])>{{ $altBackup->file_path }}</label>
                            </div>
                        </div>
                    @endif
                @endif
            </div>
            <div class="button-box single-box input-box-top-line">
                <button type="submit" class="button color-button" id="start-restore"
                    style="--button-color:var(--default-color);"
                    @disabled(!$altBackup?->isFile && !$backup?->isFile)>{{ __('elfcms::default.restore') }}</button>
            </div>
        </form>
        <div id="progress-container" class="progress-container hidden">
            <div id="progress-status" class="progress-status">{{ __('elfcms::default.preparing') }}</div>
            <div class="progress-line">
                <div class="progress-blinker" id="progress-blinker"></div>
            </div>
        </div>
        <div id="backup-result" class="backup-result hidden"></div>
    </div>
    <script>
        const form = document.getElementById('restore-form');
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                popup({
                    title: '{{ __('elfcms::default.restoring_backup', ['file' => $backup->name]) }}',
                    content: '<p>{{ __('elfcms::default.confirm_restore_action') }}</p><p>{{ __('elfcms::default.confirm_restore') }}</p>',
                    buttons: [{
                            title: '{{ __('elfcms::default.confirm') }}',
                            class: 'button color-text-button danger-button',
                            callback: [
                                function() {
                                    restoreStart(form)
                                },
                                'close'
                            ]
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
        }

        function restoreStart(form) {
            const backup = '{{ $backup->name ?? '' }}';
            if (!backup || backup === '') return false;
            const startRestoreButton = document.getElementById('start-restore');
            const progressContainer = document.getElementById('progress-container');
            const statusEl = document.getElementById('progress-status');
            const barEl = document.getElementById('progress-blinker');
            const resultBox = document.getElementById('backup-result');
            const formInputs = form.querySelectorAll('input');
            if (formInputs) {
                formInputs.forEach(input => {
                    input.disabled = true;
                });
            }
            startRestoreButton.disabled = true;
            progressContainer.classList.remove('hidden');
            statusEl.innerHTML =
                '<div class="alert alert-120">{{ __('elfcms::default.do_not_close_this_browser_window') }}</div><p>{{ __('elfcms::default.backup_restoring') }}</p>';
            barEl.classList.remove('full');
            if (form) {
                const formData = new FormData(form);
                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    credentials: 'same-origin',
                    body: formData
                }).then(
                    (result) => result.json()
                ).then(
                    (data) => {
                        if (data.message) {
                            if (formInputs) {
                                formInputs.forEach(input => {
                                    input.disabled = false;
                                });
                            }
                            startRestoreButton.disabled = false;
                            barEl.classList.add('full');
                            statusEl.textContent = data.message;
                        }
                    }
                ).catch(error => {
                    if (formInputs) {
                        formInputs.forEach(input => {
                            input.disabled = false;
                        });
                    }
                    startRestoreButton.disabled = false;
                    barEl.classList.add('full');
                    statusEl.textContent = '{{ __('elfcms::default.restore_completed_with_error') }}';
                });
            }
        }
    </script>
@endsection
