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
                {{-- <div class="input-box colored">
                    <label for="browser_title">{{ __('elfcms::default.browser_title') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="browser_title" id="browser_title" autocomplete="off" value="{{ $pageData->browser_title }}">
                    </div>
                </div> --}}
                <div class="input-box colored">
                    <div class="checkbox-switch-wrapper">
                        <div class="checkbox-switch blue">
                            <input type="checkbox" name="is_dynamic" id="is_dynamic" value="1" @if(!empty($pageData->is_dynamic)) checked @endif>
                            <i></i>
                        </div>
                        <label for="is_dynamic">
                            {{ __('elfcms::default.display_by_url') . ': ' . config('elfcms.basic.page_path') }}/&lt;slug&gt;
                        </label>
                    </div>
                    {{-- <div class="checkbox-wrapper">
                        <div class="checkbox-inner">
                            <input
                                type="checkbox"
                                name="is_dynamic"
                                id="is_dynamic"
                                @if ($pageData->is_dynamic == 1) checked @endif
                                data-inactive="path"
                            >
                            <i></i>
                            <label for="is_dynamic">
                                {{ __('elfcms::default.display_by_url') . ': ' . config('elfcms.basic.page_path') }}/&lt;slug&gt;
                            </label>
                        </div>
                    </div> --}}
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
                        {{-- <input type="hidden" name="image_path" id="image_path" value="{{$pageData->image}}">
                        <div class="image-button">
                            <div class="delete-image @if (empty($pageData->image)) hidden @endif">&#215;</div>
                            <div class="image-button-img">
                            @if (!empty($pageData->image))
                                <img src="{{ asset($pageData->image) }}" alt="Image">
                            @else
                                <img src="{{ asset('/elfcms/admin/images/icons/upload.png') }}" alt="Upload file">
                            @endif
                            </div>
                            <div class="image-button-text">
                            @if (!empty($pageData->image))
                                {{ __('elfcms::default.change_file') }}
                            @else
                                {{ __('elfcms::default.choose_file') }}
                            @endif
                            </div>
                            <input type="file" name="image" id="image">
                        </div> --}}
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
            </div>
        </form>
    </div>
    <script>
    autoSlug('.autoslug')
    checkInactive()
    //add editor
    runEditor('#content')
    const imageInput = document.querySelector('#image')
    if (imageInput) {
        inputFileImg(imageInput)
    }
    </script>

@endsection
