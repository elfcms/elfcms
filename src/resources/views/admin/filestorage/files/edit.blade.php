@extends('elfcms::admin.layouts.gallery')

@section('gallery-content')

    @if (Session::has('itemsuccess'))
        <div class="alert alert-success">{{ Session::get('itemsuccess') }}</div>
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

    @include('elfcms::admin.gallery.items.content.edit')

@endsection
