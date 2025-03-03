@extends('elfcms::admin.layouts.menu')

@section('menupage-content')
<div class="table-search-box">
    <a href="{{ route('admin.menus.items.create',$menu) }}" class="button success-button icon-text-button light-icon plus-button">{{__('elfcms::default.create_menu_item')}}</a>
</div>
    @if (Session::has('menuitemdeleted'))
    <div class="alert alert-alternate">{{ Session::get('menuitemdeleted') }}</div>
    @endif
    @if (Session::has('menuitemedited'))
    <div class="alert alert-alternate">{{ Session::get('menuitemedited') }}</div>
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

    <div class="grid-table-wrapper">
        <table class="grid-table menuitemtable">
            <thead>
                <tr>
                    <th>
                        ID
                        <a href="{{ route('admin.menus.items',UrlParams::addArr(['order'=>'id','trend'=>['desc','asc'],'menu'=>$menu])) }}" class="ordering @if (UrlParams::case('order',['id'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    <th>
                        {{ __('elfcms::default.text') }}
                        <a href="{{ route('admin.menus.items',UrlParams::addArr(['order'=>'text','trend'=>['desc','asc'],'menu'=>$menu])) }}" class="ordering @if (UrlParams::case('order',['text'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    <th>
                        {{ __('elfcms::default.link') }}
                        <a href="{{ route('admin.menus.items',UrlParams::addArr(['order'=>'link','trend'=>['desc','asc'],'menu'=>$menu])) }}" class="ordering @if (UrlParams::case('order',['link'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    <th>
                        {{ __('elfcms::default.menu') }}
                        <a href="{{ route('admin.menus.items',UrlParams::addArr(['order'=>'menu_id','trend'=>['desc','asc'],'menu'=>$menu])) }}" class="ordering @if (UrlParams::case('order',['menu_id'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    <th>
                        {{ __('elfcms::default.created') }}
                        <a href="{{ route('admin.menus.items',UrlParams::addArr(['order'=>'created_at','trend'=>['desc','asc'],'menu'=>$menu])) }}" class="ordering @if (UrlParams::case('order',['created_at'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    <th>
                        {{ __('elfcms::default.updated') }}
                        <a href="{{ route('admin.menus.items',UrlParams::addArr(['order'=>'updated_at','trend'=>['desc','asc'],'menu'=>$menu])) }}" class="ordering @if (UrlParams::case('order',['updated_at'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            @foreach ($items as $item)
                <tr data-id="{{ $item->id }}" class="level-{{ $item->level }}@empty ($item->active) inactive @endempty">
                    <td class="subline-{{ $item->level }}">{{ $item->id }}</td>
                    <td>
                        <a href="{{ route('admin.menus.items.edit',['item'=>$item->id,'menu'=>$menu]) }}">
                            {{ $item->text }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('admin.menus.items.edit',['item'=>$item->id,'menu'=>$menu]) }}">
                            {{ $item->link }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('admin.menus.edit',['menu'=>$menu]) }}">
                            {{ $item->menu->name }}
                        </a>
                    </td>
                    <td>{{ $item->created_at }}</td>
                    <td>{{ $item->updated_at }}</td>
                    <td class="table-button-column">
                        <form action="{{ route('admin.menus.items.create',$menu) }}" method="GET">
                            <input type="hidden" name="parent_id" value="{{ $item->id }}">
                            <button type="submit" class="button submit-button create-button" title="{{ __('elfcms::default.add_subitem') }}"></button>
                        </form>
                        <a href="{{ route('admin.menus.items.edit',['item'=>$item->id,'menu'=>$menu]) }}" class="button edit-button" title="{{ __('elfcms::default.edit') }}"></a>
                        <form action="{{ route('admin.menus.items.destroy',['item'=>$item->id,'menu'=>$menu]) }}" method="POST" data-submit="check">
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
                                class:'button delete-button',
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
