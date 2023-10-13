@extends('elfcms::admin.layouts.users')

@section('userpage-content')

    <div class="table-search-box">
        <a href="{{ route('admin.users.create') }}" class="default-btn success-button icon-text-button light-icon plus-button">{{__('elfcms::default.create_new_user')}}</a>
        <div class="table-search-result-title">
            @if (!empty($search))
                {{ __('elfcms::default.search_result_for') }} "{{ $search }}" <a href="{{ route('admin.users') }}" title="{{ __('elfcms::default.reset_search') }}">&#215;</a>
            @endif
        </div>
        <form action="{{ route('admin.users') }}" method="get">
            <div class="input-box">
                <label for="search">
                    {{ __('elfcms::default.search') }}
                </label>
                <div class="input-wrapper">
                    <input type="text" name="search" id="search" value="{{ $search ?? '' }}" placeholder="">
                </div>
                <div class="non-text-buttons">
                    <button type="submit" class="default-btn search-button"></button>
                </div>
            </div>
        </form>
    </div>
    @if (Session::has('userdeleted'))
    <div class="alert alert-alternate">{{ Session::get('userdeleted') }}</div>
    @endif
    @if (Session::has('useredited'))
    <div class="alert alert-alternate">{{ Session::get('useredited') }}</div>
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
    @if (!empty($role))
    <div class="alert alert-standard">
        {{__('elfcms::default.show_users_for_lole',['name'=>$role->name,'id'=>$role->id])}}
    </div>
    @endif
    <div class="widetable-wrapper">
        <table class="grid-table table-cols-6" style="--first-col:65px; --last-col:140px; --minw:800px">
            <thead>
                <tr>
                    <th>
                        ID
                        <a href="{{ route('admin.users',UrlParams::addArr(['order'=>'id','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['id'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    <th>
                        Email
                        <a href="{{ route('admin.users',UrlParams::addArr(['order'=>'email','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['email'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    <th>
                        {{ __('elfcms::default.created') }}
                        <a href="{{ route('admin.users',UrlParams::addArr(['order'=>'created_at','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['created_at'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    <th>
                        {{ __('elfcms::default.updated') }}
                        <a href="{{ route('admin.users',UrlParams::addArr(['order'=>'updated_at','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['updated_at'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    <th>
                        {{ __('elfcms::default.confirmed') }}
                        <a href="{{ route('admin.users',UrlParams::addArr(['order'=>'is_confirmed','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['is_confirmed'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            @foreach ($users as $user)
                <tr data-id="{{ $user->id }}">
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->created_at }}</td>
                    <td>{{ $user->updated_at }}</td>
                    <td>
                    @if ($user->is_confirmed)
                        {{ __('elfcms::default.confirmed') }}
                    @else
                        {{ __('elfcms::default.not_confirmed') }}
                    @endif
                    </td>
                    <td class="button-column non-text-buttons">
                        <a href="{{ route('admin.users.edit',$user->id) }}" class="default-btn edit-button" title="{{ __('elfcms::default.edit') }}"></a>
                        <form action="{{ route('admin.users.update',$user->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="id" id="id" value="{{ $user->id }}">
                            <input type="hidden" name="is_confirmed" id="is_confirmed" value="{{ (int)!(bool)$user->is_confirmed }}">
                            <input type="hidden" name="notedit" value="1">
                            <button type="submit" @if ($user->is_confirmed == 1) class="default-btn deactivate-button" title="{{__('elfcms::default.deactivate') }}" @else class="default-btn activate-button" title="{{ __('elfcms::default.activate') }}" @endif>

                            </button>
                        </form>
                        <form action="{{ route('admin.users.destroy',$user->id) }}" method="POST" data-submit="check">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="id" value="{{ $user->id }}">
                            <input type="hidden" name="email" value="{{ $user->email }}">
                            <button type="submit" class="default-btn delete-button" title="{{ __('elfcms::default.delete') }}"></button>
                        </form>
                        <div class="contextmenu-content-box">
                            <a href="{{ route('admin.users.edit',$user->id) }}" class="contextmenu-item">{{ __('elfcms::default.edit') }}</a>
                            <form action="{{ route('admin.users.update',$user->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="id" id="id" value="{{ $user->id }}">
                                <input type="hidden" name="is_confirmed" id="is_confirmed" value="{{ (int)!(bool)$user->is_confirmed }}">
                                <input type="hidden" name="notedit" value="1">
                                <button type="submit" class="contextmenu-item">
                                @if ($user->is_confirmed == 1)
                                    {{ __('elfcms::default.deactivate') }}
                                @else
                                    {{ __('elfcms::default.activate') }}
                                @endif
                                </button>
                            </form>
                            <form action="{{ route('admin.users.destroy',$user->id) }}" method="POST" data-submit="check">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="id" value="{{ $user->id }}">
                                <input type="hidden" name="email" value="{{ $user->email }}">
                                <button type="submit" class="contextmenu-item">{{ __('elfcms::default.delete') }}</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        @if (empty(count($users)))
            <div class="no-results-box">
                {{ __('elfcms::default.nothing_was_found') }}
            </div>
        @endif
    </div>
    {{$users->links('elfcms::admin.layouts.pagination')}}

    <script>
        const checkForms = document.querySelectorAll('form[data-submit="check"]')

        /* if (checkForms) {
            checkForms.forEach(form => {
                form.addEventListener('submit',function(e){
                    e.preventDefault();
                    let userId = this.querySelector('[name="id"]').value,
                        userName = this.querySelector('[name="email"]').value,
                        self = this
                    popup({
                        title:'{{ __('elfcms::default.deleting_of_element') }}' + userId,
                        content:'<p>{{ __('elfcms::default.are_you_sure_to_deleting_user') }} "' + userName + '" (ID ' + userId + ')?</p>',
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
        } */

        function setConfirmDelete(forms) {
            if (forms) {
                forms.forEach(form => {
                    form.addEventListener('submit',function(e){
                        e.preventDefault();
                        let userId = this.querySelector('[name="id"]').value,
                            userName = this.querySelector('[name="email"]').value,
                            self = this
                        popup({
                            title:'{{ __('elfcms::default.deleting_of_element') }}' + userId,
                            content:'<p>{{ __('elfcms::default.are_you_sure_to_deleting_user') }} "' + userName + '" (ID ' + userId + ')?</p>',
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
        }

        setConfirmDelete(checkForms)


        const tablerow = document.querySelectorAll('.usertable tbody tr');
        if (tablerow) {
            tablerow.forEach(row => {
                row.addEventListener('contextmenu',function(e){
                    e.preventDefault()
                    let content = row.querySelector('.contextmenu-content-box').cloneNode(true)
                    let forms  = content.querySelectorAll('form[data-submit="check"]')
                    setConfirmDelete(forms)
                    contextPopup(content,{'left':e.x,'top':e.y})
                })
            })
        }
    </script>

@endsection
