@extends('elfcms::admin.layouts.email')

@section('emailpage-content')
    <div class="table-search-box">
        <a href="{{ route('admin.email.events.create') }}"
            class="default-btn success-button icon-text-button light-icon plus-button">{{ __('elfcms::default.create_email_event') }}</a>
    </div>
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

    <div class="widetable-wrapper">
        <table class="grid-table eeventtable">
            <thead>
                <tr>
                    <th>
                        ID
                        <a href="{{ route('admin.email.events',UrlParams::addArr(['order'=>'id','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['id'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    <th>
                        {{ __('elfcms::default.code') }}
                        <a href="{{ route('admin.email.events',UrlParams::addArr(['order'=>'code','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['code'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    <th>
                        {{ __('elfcms::default.name') }}
                        <a href="{{ route('admin.email.events',UrlParams::addArr(['order'=>'name','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['name'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    <th>
                        {{ __('elfcms::default.subject') }}
                        <a href="{{ route('admin.email.events',UrlParams::addArr(['order'=>'subject','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['subject'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    <th>
                        {{ __('elfcms::default.created') }}
                        <a href="{{ route('admin.email.events',UrlParams::addArr(['order'=>'created_at','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['created_at'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    <th>
                        {{ __('elfcms::default.updated') }}
                        <a href="{{ route('admin.email.events',UrlParams::addArr(['order'=>'updated_at','trend'=>['desc','asc']])) }}" class="ordering @if (UrlParams::case('order',['updated_at'=>true])) {{UrlParams::case('trend',['desc'=>'desc'],'asc')}} @endif"></a>
                    </th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            @foreach ($events as $event)
                <tr data-id="{{ $event->id }}" class="@empty ($event->active) inactive @endempty">
                    <td>{{ $event->id }}</td>
                    <td>
                        <a href="{{ route('admin.email.events.edit',$event->id) }}">
                            {{ $event->code }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('admin.email.events.edit',$event->id) }}">
                            {{ $event->name }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('admin.email.events.edit',$event->id) }}">
                            {{ $event->subject }}
                        </a>
                    </td>
                    <td>{{ $event->created_at }}</td>
                    <td>{{ $event->updated_at }}</td>
                    <td class="button-column non-text-buttons">
                        <a href="{{ route('admin.email.events.edit',$event->id) }}" class="default-btn edit-button" title="{{ __('elfcms::default.edit') }}"></a>
                        @if(!in_array($event->code,$protected))
                        <form action="{{ route('admin.email.events.destroy',$event->id) }}" method="POST" data-submit="check">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="id" value="{{ $event->id }}">
                            <input type="hidden" name="name" value="{{ $event->name }}">
                            <button type="submit" class="default-btn delete-button" title="{{ __('elfcms::default.delete') }}"></button>
                        </form>
                        @else
                        <button class="default-btn delete-button" title="{{ __('elfcms::default.delete') }}" disabled></button>
                        @endif
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
                    let eventId = this.querySelector('[name="id"]').value,
                        eventName = this.querySelector('[name="name"]').value,
                        self = this
                    popup({
                        title:'{{ __('elfcms::default.deleting_of_element') }}' + eventId,
                        content:'<p>{{ __('elfcms::default.are_you_sure_to_deleting_event') }} "' + eventName + '" (ID ' + eventId + ')?</p>',
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
