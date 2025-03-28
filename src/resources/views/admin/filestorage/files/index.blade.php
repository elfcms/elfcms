@extends('elfcms::admin.layouts.main')
@section('head')
    <link rel="stylesheet" href="{{ asset('elfcms/admin//css/filestorage.css') }}">
@endsection
@inject('image', 'Elfcms\Elfcms\Aux\Image')

@section('pagecontent')

<div class="filestorage-info-box">
    {{-- <div class="filestorage-preview-box">
        <img src="{{ file_path($filestorage->preview) }}" alt="">
    </div> --}}
    <div class="filestorage-data-box">
        <h2>{{ $filestorage->name }}</h2>
        <div class="filestorage-description">{{ $filestorage->description }}</div>
        {{-- <div class="filestorage-addtitional-text">{{ $filestorage->addtitional_text }}</div> --}}
    </div>
    <div class="filestorage-edit-button-box">
        <a href="{{ route('admin.filestorage.edit',$filestorage) }}" class="button round-button theme-button">
            <span class="button-collapsed-text">{{__('elfcms::default.edit')}}</span>
            {!! iconHtmlLocal('elfcms/admin/images/icons/buttons/edit.svg', svg: true) !!}
        </a>
    </div>
</div>
<div class="filestorage-files-box">
    @include('elfcms::admin.filestorage.files.content.index')
</div>

@endsection
