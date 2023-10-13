@extends('elfcms::admin.layouts.menu')

@section('menupage-content')

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
    <h2>Items of menu {{ $menu->name }} [{{$menu->id}}]</h2>
    <div class="widetable-wrapper">
        <table class="grid-table menuitemtable">
            <thead>
                <tr>
                    <th>
                        ID
                        <a href="{{ route('admin.menu.items',UrlParams::addArr(['order'=>'id','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['id'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    <th>
                        {{ __('elfcms::default.text') }}
                        <a href="{{ route('admin.menu.items',UrlParams::addArr(['order'=>'text','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['text'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    <th>
                        {{ __('elfcms::default.link') }}
                        <a href="{{ route('admin.menu.items',UrlParams::addArr(['order'=>'link','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['link'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    <th>
                        {{ __('elfcms::default.menu') }}
                        <a href="{{ route('admin.menu.items',UrlParams::addArr(['order'=>'menu_id','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['menu_id'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    <th>
                        {{ __('elfcms::default.created') }}
                        <a href="{{ route('admin.menu.items',UrlParams::addArr(['order'=>'created_at','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['created_at'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    <th>
                        {{ __('elfcms::default.updated') }}
                        <a href="{{ route('admin.menu.items',UrlParams::addArr(['order'=>'updated_at','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['updated_at'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            @foreach ($items as $item)
                <tr data-id="{{ $item->id }}" class="level-{{ $item->level }}@empty ($item->active) inactive @endempty">
                    <td class="subline-{{ $item->level }}">{{ $item->id }}</td>
                    <td>
                        <a href="{{ route('admin.menu.items.edit',$item->id) }}">
                            {{ $item->text }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('admin.menu.items.edit',$item->id) }}">
                            {{ $item->link }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('admin.menu.menus.edit',$item->menu_id) }}">
                            {{ $item->menu->name }}
                        </a>
                    </td>
                    <td>{{ $item->created_at }}</td>
                    <td>{{ $item->updated_at }}</td>
                    <td class="button-column">
                        <form action="{{ route('admin.menu.items.create') }}" method="GET">
                            <input type="hidden" name="parent_id" value="{{ $item->id }}">
                            <button type="submit" class="default-btn submit-button">{{ __('elfcms::default.add_subitem') }}</button>
                        </form>
                        <a href="{{ route('admin.menu.items.edit',$item->id) }}" class="default-btn edit-button">{{ __('elfcms::default.edit') }}</a>
                        <form action="{{ route('admin.menu.items.destroy',$item->id) }}" method="POST" data-submit="check">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="id" value="{{ $item->id }}">
                            <input type="hidden" name="name" value="{{ $item->name }}">
                            <button type="submit" class="default-btn delete-button">{{ __('elfcms::default.delete') }}</button>
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
