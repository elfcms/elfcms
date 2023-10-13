@extends('elfcms::admin.layouts.email')

@section('emailpage-content')

    @if (Session::has('fielddeleted'))
    <div class="alert alert-alternate">{{ Session::get('fielddeleted') }}</div>
    @endif
    @if (Session::has('fieldedited'))
    <div class="alert alert-alternate">{{ Session::get('fieldedited') }}</div>
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


@endsection
