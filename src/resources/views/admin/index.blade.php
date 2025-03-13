@extends('elfcms::admin.layouts.main')

@section('pagecontent')

    <div class="big-container">
        <div class="bigtile-box">
            @foreach ($menuData as $data)
            <a href="{{route($data['route'])}}" class="tile-item" style="--tile-color:{{$data['color']}}">
                <div class="tile-image">
                    {{-- <img src="{{$data['big_icon'] ?? $data['icon']}}" alt="" width="64"> --}}
                    {!! iconHtmlLocal($data['big_icon'] ?? $data['icon'], 64, 64, true) !!}
                </div>
                <div class="tile-content">
                    <h2>{{__($data['title'])}}</h2>
                    <div class="tile-description">
                        {{$data['text']}}
                    </div>
                    <div class="tile-data">
                        @foreach ($data['subdata'] as $subdata)
                        <div class="tile-data-item">
                            <div class="tile-data-item-title">{{$subdata['title']}}:</div>
                            <div class="tile-data-item-value">{{$subdata['value']}}</div>
                        </div>
                        @endforeach

                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>

@endsection
