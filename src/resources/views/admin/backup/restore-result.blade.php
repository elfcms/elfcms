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
        <form>
            <div class="colored-rows-box">
                <div class="input-box">
                    <div class="full-width">
                        @if (Session::has('successrestore'))
                            <div class="alert alert-success">
                                {{ Session::get('successrestore') }}
                            </div>
                        @elseif (Session::has('errorrestore'))
                            <div class="alert alert-error">
                                {{ Session::get('errorrestore') }}
                            </div>
                        @elseif ($errors->any())
                            <div class="alert alert-error">
                                {!! '<ul><li>' . implode('</li><li>', $errors->all()) . '</li></ul>' !!}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
