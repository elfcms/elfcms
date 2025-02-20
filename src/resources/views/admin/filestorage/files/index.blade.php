@extends('elfcms::admin.layouts.default')
@section('head')
    <link rel="stylesheet" href="{{ asset('elfcms/admin//css/filestorage.css') }}">
@endsection
@inject('image', 'Elfcms\Elfcms\Aux\Image')

@section('innerpage-content')

@if (Session::has('success'))
<div class="alert alert-alternate">{{ Session::get('success') }}</div>
@endif
@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="filestorage-info-box">
    {{-- <div class="filestorage-preview-box">
        <img src="{{ file_path($filestorage->preview) }}" alt="">
    </div> --}}
    <div class="filestorage-data-box">
        <h2>{{ $filestorage->name }}</h2>
        <div class="filestorage-description">{{ $filestorage->description }}</div>
        {{-- <div class="filestorage-addtitional-text">{{ $filestorage->addtitional_text }}</div> --}}
    </div>
    <div class="dallery-edit-button-box">
        <a href="{{ route('admin.filestorage.edit',$filestorage) }}" class="button big-square-button edit-button">
            {{__('elfcms::default.edit')}}
        </a>
    </div>
</div>
<div class="filestorage-files-box">
    @include('elfcms::admin.filestorage.files.content.index')
</div>

@endsection
