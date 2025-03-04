@extends('elfcms::admin.layouts.main')

@section('pagecontent')
<div class="table-search-box">
    <a href="{{ route('admin.fragment.items.create') }}" class="button round-button theme-button">
        {!! iconHtmlLocal('elfcms/admin/images/icons/plus.svg', svg: true) !!}
        <span class="button-collapsed-text">
            {{ __('elfcms::default.create_item') }}
        </span>
    </a>
</div>
    <div class="grid-table-wrapper">
        @if (!empty($item))
            <div class="alert alert-alternate">
                {{ __('elfcms::default.showing_results_for_item') }} <strong>#{{ $item->id }} {{ $item->name }}</strong>
            </div>
        @endif
        <table class="grid-table table-cols" style="--first-col:65px; --last-col:140px; --minw:800px; --cols-count:6;">
            <thead>
                <tr>
                    <th>
                        ID
                        <a href="{{ route('admin.fragment.items',UrlParams::addArr(['order'=>'id','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['id'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    <th>
                        {{ __('elfcms::default.code') }}
                        <a href="{{ route('admin.fragment.items',UrlParams::addArr(['order'=>'code','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['code'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    <th>
                        {{ __('elfcms::default.title') }}
                        <a href="{{ route('admin.fragment.items',UrlParams::addArr(['order'=>'title','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['title'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    <th>
                        {{ __('elfcms::default.created') }}
                        <a href="{{ route('admin.fragment.items',UrlParams::addArr(['order'=>'created_at','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['created_at'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    <th>
                        {{ __('elfcms::default.updated') }}
                        <a href="{{ route('admin.fragment.items',UrlParams::addArr(['order'=>'updated_at','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['updated_at'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            @foreach ($items as $item)
            @php
                //dd($item);
            @endphp
                <tr data-id="{{ $item->id }}" class="">
                    <td>{{ $item->id }}</td>
                    <td>
                        <a href="{{ route('admin.fragment.items',['item'=>$item->id]) }}">
                            {{ $item->code }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('admin.fragment.items',['item'=>$item->id]) }}">
                            {{ $item->title }}
                        </a>
                    </td>
                    <td>{{ $item->created_at }}</td>
                    <td>{{ $item->updated_at }}</td>
                    <td class="table-button-column">
                        <a href="{{ route('admin.fragment.items.edit',$item->id) }}" class="button icon-button" title="{{ __('elfcms::default.edit') }}">
                            {!! iconHtmlLocal('elfcms/admin/images/icons/buttons/edit.svg', svg: true) !!}
                        </a>
                        <form action="{{ route('admin.fragment.items.destroy',$item->id) }}" method="POST" data-submit="check">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="id" value="{{ $item->id }}">
                            <input type="hidden" name="name" value="{{ $item->name }}">
                            <button type="submit" class="button icon-button icon-alarm-button"
                                        title="{{ __('elfcms::default.delete') }}">
                                        {!! iconHtmlLocal('elfcms/admin/images/icons/buttons/delete.svg', svg: true) !!}
                                    </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    {{$items->links('elfcms::admin.layouts.pagination')}}

    <script>
        const checkForms = document.querySelectorAll('form[data-submit="check"]')

        if (checkForms) {
            checkForms.forEach(form => {
                form.addEventListener('submit',function(e){
                    e.preventDefault();
                    let itemId = this.querySelector('[name="id"]').value,
                        itemName = this.querySelector('[name="name"]').value,
                        self = this
                    popup({
                        title:'{{ __('elfcms::default.deleting_of_element') }}' + itemId,
                        content:'<p>{{ __('elfcms::default.are_you_sure_to_deleting_item') }} "' + itemName + '" (ID ' + itemId + ')?</p>',
                        buttons:[
                            {
                                title:'{{ __('elfcms::default.delete') }}',
                                class:'button color-button red-button',
                                callback: function(){
                                    self.submit()
                                }
                            },
                            {
                                title:'{{ __('elfcms::default.cancel') }}',
                                class:'button cancel-button',
                                callback:'close'
                            }
                        ],
                        class:'danger'
                    })
                })
            })
        }
    </script>

@endsection
