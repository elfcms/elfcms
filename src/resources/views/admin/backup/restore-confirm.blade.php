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
            data-submit="check">
            @csrf
            @method('POST')
            @if (empty($restoreType))
                <div class="colored-rows-box">
                    <div class="input-box">
                        <div class="full-width">
                            <div class="backup-type-restore-title">
                                {{ __('elfcms::default.restoring_backup', ['file' => $backup->name]) }}
                            </div>
                            <div class="alert alert-warning">
                                {{ __('elfcms::default.nothing_to_restore') }}
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <input type="hidden" name="confirm" value="1">
                <input type="hidden" name="name" value="{{ $backup->name }}">
                <input type="hidden" name="type" value="{{ $restoreType }}">
                <div class="colored-rows-box">
                    <div class="input-box">
                        <div class="full-width">
                            @foreach ($types as $type)
                                <div class="backup-type-restore-title backup-type-icon backup-type-{{ $type }}">
                                    @if ($type == 'files')
                                        {!! iconHtmlLocal('elfcms/admin/images/icons/archive_file.svg', svg: true) !!}
                                    @elseif ($type == 'database')
                                        {!! iconHtmlLocal('elfcms/admin/images/icons/database.svg', svg: true) !!}
                                    @endif
                                    <span>
                                        {{ __('elfcms::default.restore_from_' . $type) }}
                                    </span>
                                </div>
                                <p></p>
                            @endforeach
                            <div class="alert alert-warning">
                                <strong>{{ __('elfcms::default.warning') }}:</strong>
                                <p>{{ __('elfcms::default.confirm_restore_action') }}</p>
                                <p>{{ __('elfcms::default.confirm_restore') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="button-box single-box input-box-top-line">
                    <button type="submit" class="button color-text-button warn-button"
                        id="start-restore">{{ __('elfcms::default.restore') }}</button>
                    <a href="{{ route('admin.backup.restore', $backup) }}"
                        class="button color-text-button">{{ __('elfcms::default.cancel') }}</a>
                </div>
            @endif
        </form>
        <div id="progress-container" class="progress-container hidden">
            <div id="progress-status" class="progress-status">{{ __('elfcms::default.preparing') }}</div>
            <div class="progress-line">
                {{-- <div class="progress-shuttle" id="progress-shuttle"></div> --}}
                <div class="progress-blinker" id="progress-blinker"></div>
            </div>
        </div>
        <div id="backup-result" class="backup-result hidden"></div>
    </div>
@endsection
