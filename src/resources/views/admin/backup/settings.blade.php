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
        <h2>{{ __('elfcms::default.settings') }}</h2>
        <form action="{{ route('admin.backup.settingsSave') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('POST')
            <div class="colored-rows-box">
                @foreach ($settings as $key => $value)
                    @if ($key == 'schedule')
                        @continue
                    @elseif (in_array($key, ['database__exclude_tables', 'paths__include', 'paths__exclude']))
                        <div class="input-box colored">
                            <label for="{{ $key }}">
                                {{ __('elfcms::default.' . $key) }}
                            </label>
                            <div class="input-wrapper">
                                <textarea name="{{ $key }}" id="{{ $key }}" cols="30" rows="3">{{ $value }}</textarea>
                            </div>
                        </div>
                    @else
                        <div class="input-box colored">
                            <label for="{{ $key }}">
                                {{ __('elfcms::default.' . $key) }}
                            </label>
                            <div class="input-wrapper">
                                <div class="switchbox">
                                    <input type="checkbox" name="{{ $key }}" id="{{ $key }}"
                                        value="1" @if (!empty($value)) checked @endif>
                                    <i></i>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
                <h3>{{ __('elfcms::default.schedule') }}</h3>
                @foreach ([
            'minute' => 60,
            'hour' => 24,
            'day' => 31,
            'month' => 12,
            'weekday' => 7,
        ] as $field => $max)
                    <div class="input-box colored">
                        <label>{{ __('elfcms::default.' . $field) }}</label>
                        <div class="input-wrapper">
                            <select name="{{ $field }}_mode" data-field="{{ $field }}">
                                <option value="exact" @selected(old($field . '_mode', $shedule[$field . '_mode'] ?? '') === 'exact')>
                                    {{ __('elfcms::default.exact') }}
                                </option>
                                <option value="every" @selected(old($field . '_mode', $shedule[$field . '_mode'] ?? '') === 'every')>
                                    {{ __('elfcms::default.every') }}
                                </option>
                            </select>
                        </div>
                        <div class="input-wrapper" data-mode="exact" data-field="{{ $field }}">
                            <select name="{{ $field }}">
                                <option value="*">*</option>
                                @if ($field === 'month')
                                    @foreach ($monthNames as $i => $label)
                                        <option value="{{ $i }}" @selected((string) $i === (string) old($field, $shedule[$field] ?? ''))>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                @elseif ($field === 'weekday')
                                    @foreach ($weekdayNames as $i => $label)
                                        <option value="{{ $i }}" @selected((string) $i === (string) old($field, $shedule[$field] ?? ''))>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                @else
                                    @for ($i = $field === 'day' ? 1 : 0; $i < $max; $i++)
                                        <option value="{{ $i }}" @selected((string) $i === (string) old($field, $shedule[$field] ?? ''))>
                                            {{ $i }}
                                        </option>
                                    @endfor
                                @endif
                            </select>
                        </div>
                        <div class="input-wrapper" data-mode="every" data-field="{{ $field }}">
                            <input type="number" name="{{ $field }}_every" min="1"
                                max="{{ $max - 1 }}"
                                value="{{ old($field . '_every', $shedule[$field . '_mode'] === 'every' ? $shedule[$field . '_every'] ?? '' : '') }}"
                                placeholder="{{ __('elfcms::default.every_x') }}">
                        </div>
                    </div>
                @endforeach
                <div class="input-box colored">
                    <strong>{{ __('elfcms::default.cron_preview') }}:</strong>
                    <div class="input-wrapper">
                        <input type="text" name="schedule" id="cron-preview" class="plain-text" readonly>
                    </div>
                </div>
            </div>
            <div class="button-box single-box">
                <button type="submit"
                    class="button color-text-button success-button">{{ __('elfcms::default.submit') }}</button>
            </div>
        </form>
    </div>
    <div class="item-form last-form backup-create-form">
        <button id="start-backup" class="button color-button" style="--button-color:var(--default-color);">Start
            Backup</button>

        <div id="progress-container" class="progress-container hidden">
            <div id="progress-status" class="progress-status">{{ __('elfcms::default.preparing') }}</div>
            <div class="progress-line">
                <div class="progress-bar" id="progress-bar" style="width:0%"></div>
            </div>
        </div>
        <div id="backup-result" class="backup-result hidden"></div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fields = ['minute', 'hour', 'day', 'month', 'weekday'];
            const previewEl = document.getElementById('cron-preview');

            function updatePreview() {
                const parts = fields.map(field => {
                    const mode = document.querySelector(`[name='${field}_mode']`)?.value;
                    if (mode === 'every') {
                        const every = document.querySelector(`[name='${field}_every']`)?.value || '*';
                        return '*/' + every;
                    } else {
                        return document.querySelector(`[name='${field}']`)?.value || '*';
                    }
                });
                previewEl.value = parts.join(' ');
            }

            document.querySelectorAll('select, input').forEach(el => {
                el.addEventListener('change', updatePreview);
                el.addEventListener('input', updatePreview);
            });

            updatePreview();

            function setSelectMode(select) {
                const field = select.dataset.field;
                const mode = select.value;
                const preMode = mode == 'every' ? 'exact' : 'every';
                const modeBox = document.querySelector(
                    `[data-field="${field}"][data-mode="${mode}"]`);
                const preModeBox = document.querySelector(
                    `[data-field="${field}"][data-mode="${preMode}"]`);
                if (modeBox) {
                    modeBox.classList.remove('hidden');
                }
                if (preModeBox) {
                    preModeBox.classList.add('hidden');
                }
            }
            modeSelects = document.querySelectorAll('select[data-field]');
            if (modeSelects) {
                modeSelects.forEach(select => {
                    select.addEventListener('change', function() {
                        setSelectMode(this);
                    });
                    setSelectMode(select);
                });
            }
        });

        const startBackupButton = document.getElementById('start-backup');
        const progressContainer = document.getElementById('progress-container');
        const statusEl = document.getElementById('progress-status');
        const barEl = document.getElementById('progress-bar');
        const resultBox = document.getElementById('backup-result');
        if (startBackupButton) {
            startBackupButton.addEventListener('click', function(e) {
                e.target.disabled = true;
                const token = document.querySelector('[name="_token"]').value;
                if (resultBox) {
                    resultBox.classList.add('hidden');
                    setTimeout(() => {
                        resultBox.innerHTML = '';
                    }, 500);
                }
                fetch('{{ route('admin.backup.start') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'Content-Type': 'application/json',
                        },
                    }).then(response => response.json())
                    .then(data => {
                        if (data.status === 'started') {
                            if (progressContainer) {
                                progressContainer.classList.remove('hidden');
                            }
                            trackProgress();
                        }
                    });
            });
        }

        function trackProgress() {

            const interval = setInterval(() => {
                fetch('{{ route('admin.backup.progress') }}')
                    .then(res => res.json())
                    .then(data => {
                        statusEl.textContent = data.step + ' (' + data.percent + '%)';
                        barEl.style.width = data.percent + '%';

                        if (data.percent >= 100) {
                            clearInterval(interval);
                            backupFinal();
                        }
                    });
            }, 1000);
        }

        function backupFinal() {

            if (resultBox) {

                fetch('{{ route('admin.backup.result') }}')
                    .then(res => res.json())
                    .then(data => {
                        let html = ``;
                        if (data.sql && data.sql_file) {
                            html +=
                                `<div class="backup-result-line backup-result-sql">{!! iconHtmlLocal('elfcms/admin/images/icons/database.svg', svg: true) !!}<a href="${data.sql_file}" target="_blank">${data.sql}</a></div>`;
                        }
                        if (data.zip && data.zip_file) {
                            html +=
                                `<div class="backup-result-line backup-result-files">{!! iconHtmlLocal('elfcms/admin/images/icons/archive_file.svg', svg: true) !!}<a href="${data.zip_file}" target="_blank">${data.zip}</a></div>`;
                        }
                        resultBox.innerHTML = html;
                        setTimeout(() => {
                            resultBox.classList.remove('hidden');
                            if (startBackupButton) startBackupButton.disabled = false;
                            if (progressContainer) {
                                progressContainer.classList.add('hidden');
                            }
                        }, 500);
                    });
            }
        }
    </script>
@endsection
