@extends('elfcms::admin.layouts.menu')

@section('menupage-content')
<div class="table-search-box">
    <a href="{{ route('admin.menus.create') }}" class="default-btn success-button icon-text-button light-icon plus-button">{{__('elfcms::default.create_menu')}}</a>
</div>
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

    <div class="widetable-wrapper">
        <table class="grid-table  table-cols-6" style="--first-col:65px; --last-col:140px; --minw:800px">
            <thead>
                <tr>
                    <th>
                        ID
                        <a href="{{ route('admin.menus.index',UrlParams::addArr(['order'=>'id','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['id'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    <th>
                        {{ __('elfcms::default.name') }}
                        <a href="{{ route('admin.menus.index',UrlParams::addArr(['order'=>'name','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['name'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    <th>
                        {{ __('elfcms::default.code') }}
                        <a href="{{ route('admin.menus.index',UrlParams::addArr(['order'=>'code','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['code'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    <th>
                        {{ __('elfcms::default.created') }}
                        <a href="{{ route('admin.menus.index',UrlParams::addArr(['order'=>'created_at','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['created_at'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    <th>
                        {{ __('elfcms::default.updated') }}
                        <a href="{{ route('admin.menus.index',UrlParams::addArr(['order'=>'updated_at','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['updated_at'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            @foreach ($menus as $menu)
                <tr data-id="{{ $menu->id }}" class="@empty ($menu->active) inactive @endempty">
                    <td>{{ $menu->id }}</td>
                    <td>
                        <a href="{{ route('admin.menus.show',$menu->id) }}">
                            {{ $menu->name }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('admin.menus.show',$menu->id) }}">
                            {{ $menu->code }}
                        </a>
                    </td>
                    <td>{{ $menu->created_at }}</td>
                    <td>{{ $menu->updated_at }}</td>
                    <td class="button-column non-text-buttons">
                        <a href="{{ route('admin.menus.show',$menu) }}" class="default-btn content-button" title="{{ __('elfcms::default.edit_menu_contents') }}"></a>
                        <a href="{{ route('admin.menus.edit',$menu->id) }}" class="default-btn edit-button" title="{{ __('elfcms::default.edit') }}"></a>
                        <form action="{{ route('admin.menus.destroy',$menu->id) }}" method="POST" data-submit="check">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="id" value="{{ $menu->id }}">
                            <input type="hidden" name="name" value="{{ $menu->name }}">
                            <button type="submit" class="default-btn delete-button" title="{{ __('elfcms::default.delete') }}"></button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <script>
        const checkForms = document.querySelectorAll('form[data-submit="check"]')

        if (checkForms) {
            checkForms.forEach(form => {
                form.addEventListener('submit',function(e){
                    e.preventDefault();
                    let menuId = this.querySelector('[name="id"]').value,
                        menuName = this.querySelector('[name="name"]').value,
                        self = this
                    popup({
                        title:'{{ __('elfcms::default.deleting_of_element') }}' + menuId,
                        content:'<p>{{ __('elfcms::default.are_you_sure_to_deleting_menu') }} "' + menuName + '" (ID ' + menuId + ')?</p>',
                        buttons:[
                            {
                                title:'{{ __('elfcms::default.delete') }}',
                                class:'default-btn delete-button',
                                callback: function(){
                                    self.submit()
                                }
                            },
                            {
                                title:'{{ __('elfcms::default.cancel') }}',
                                class:'default-btn cancel-button',
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
