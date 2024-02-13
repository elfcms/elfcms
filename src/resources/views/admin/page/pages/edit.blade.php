@extends('elfcms::admin.layouts.page')

@section('pagepage-content')

    @if (Session::has('pageedited'))
        <div class="alert alert-success">{{ Session::get('pageedited') }}</div>
    @endif
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="errors-list">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="item-form">
        <h3>{{ __('elfcms::default.edit_page') }} #{{ $pageData->id }}</h3>
        <form action="{{ route('admin.page.pages.update',$pageData->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="colored-rows-box">
                <div class="input-box colored">
                    <x-elfcms-input-checkbox code="active" label="{{ __('elfcms::default.active') }}" style="blue" :checked="$pageData->active" />
                </div>
                <input type="hidden" name="id" id="id" value="{{ $pageData->id }}">
                <div class="input-box colored">
                    <label for="name">{{ __('elfcms::default.name') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="name" id="name" autocomplete="off" value="{{ $pageData->name }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="slug">{{ __('elfcms::default.slug') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="slug" id="slug" autocomplete="off" value="{{ $pageData->slug }}">
                    </div>
                    <div class="input-wrapper">
                        <div class="autoslug-wrapper">
                            <input type="checkbox" data-text-id="name" data-slug-id="slug" class="autoslug" checked>
                            <div class="autoslug-button"></div>
                        </div>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="title">{{ __('elfcms::default.title') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="title" id="title" autocomplete="off" value="{{ $pageData->title }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <div class="checkbox-switch-wrapper">
                        <div class="checkbox-switch blue">
                            <input type="checkbox" name="is_dynamic" id="is_dynamic" value="1" @if(!empty($pageData->is_dynamic)) checked @endif>
                            <i></i>
                        </div>
                        <label for="is_dynamic">
                            {{ __('elfcms::default.display_by_url') . ': ' . config('elfcms.elfcms.page_path') }}/&lt;slug&gt;
                        </label>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="path">{{ __('elfcms::default.path') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="path" id="path" autocomplete="off" value="{{ $pageData->path }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="image">{{ __('elfcms::default.image') }}</label>
                    <div class="input-wrapper">
                        <x-elfcms-input-image code="image" value="{{$pageData->image}}" />
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="content">{{ __('elfcms::default.content') }}</label>
                    <div class="input-wrapper">
                        <textarea name="content" id="content" cols="30" rows="10">{{ $pageData->getOriginal('content') }}</textarea>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="template">{{ __('elfcms::default.template') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="template" id="template" autocomplete="off" value="{{ $pageData->template }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="meta_keywords">{{ __('elfcms::default.meta_keywords') }}</label>
                    <div class="input-wrapper">
                        <textarea name="meta_keywords" id="meta_keywords" cols="30" rows="3">{{ $pageData->meta_keywords }}</textarea>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="meta_description">{{ __('elfcms::default.meta_description') }}</label>
                    <div class="input-wrapper">
                        <textarea name="meta_description" id="meta_description" cols="30" rows="3">{{ $pageData->meta_description }}</textarea>
                    </div>
                </div>
            </div>
            <div class="button-box single-box">
                <button type="submit" class="default-btn submit-button">{{ __('elfcms::default.submit') }}</button>
                <button type="submit" name="submit_close" value="save_and_close" class="default-btn alternate-button">{{ __('elfcms::default.save_and_close') }}</button>
                <a href="{{ route('admin.page.pages') }}" class="default-btn">{{ __('elfcms::default.cancel') }}</a>
            </div>
        </form>
    </div>
    <script>
    autoSlug('.autoslug')
    checkInactive()
    //add editor
    runEditor('#content')
    </script>

@endsection
