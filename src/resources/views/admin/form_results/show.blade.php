@extends('elfcms::admin.layouts.main')

@section('pagecontent')
    <div class="table-search-box">
        <a href="{{ route('admin.form-results.index') }}" class="button round-button theme-button"
            style="color:var(--default-color);">
            {!! iconHtmlLocal('elfcms/admin/images/icons/buttons/arrow_back.svg', svg: true) !!}
            <span class="button-collapsed-text">
                {{ __('elfcms::default.back') }}
            </span>
        </a>
    </div>

    <div class="item-form">
        <h2>{{ __('elfcms::default.form_result') }} #{{ $result->id }}</h2>
        {{-- <form action="{{ route('admin.forms.update',$form->id) }}" method="POST" enctype="multipart/form-data"> --}}
        <div>
            <div class="colored-rows-box">
                @foreach ($fields as $field)
                    @if (isset($result->data[$field->name]))
                        <div class="input-box colored">
                            <span class="input-box-label">{{ $field->title }}</span>
                            <div class="input-wrapper">
                                <span>{{ $field->type->name == 'checkbox' ? ($result->data[$field->name] == 1 ? '✓' : '-') : $result->data[$field->name] }}</span>
                            </div>
                        </div>
                    @endif
                @endforeach

            </div>
            {{-- <div class="button-box single-box">
                <button type="submit" class="button color-text-button success-button">{{ __('elfcms::default.submit') }}</button>
                <button type="submit" name="submit" value="save_and_open" class="button color-text-button info-button">{{ __('elfcms::default.save_and_open') }}</button>
                <button type="submit" name="submit" value="save_and_close" class="button color-text-button info-button">{{ __('elfcms::default.save_and_close') }}</button>
                <a href="{{ route('admin.forms.index') }}" class="button color-text-button">{{ __('elfcms::default.cancel') }}</a>
            </div> --}}
        </div>
        {{-- </form> --}}
    </div>
@endsection
