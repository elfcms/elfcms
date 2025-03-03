@extends('elfcms::admin.layouts.main')

@section('pagecontent')
    {{-- <div class="table-search-box">
        <a href="{{ route('admin.email.events.create') }}"
            class="button success-button icon-text-button light-icon plus-button">{{ __('elfcms::default.create_email_event') }}</a>
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
    @endif --}}
    <div class="table-search-box">
        <a href="{{ route('admin.email.events.create') }}" class="button round-button theme-button">
            {!! iconHtmlLocal('elfcms/admin/images/icons/plus.svg', svg: true) !!}
            <span class="button-collapsed-text">
                {{ __('elfcms::default.create_email_address') }}
            </span>
        </a>
        <form action="{{ route('admin.email.events') }}" method="get">
            <div class="round-input-wrapper">
                <button type="submit" class="button round-button theme-button inner-button default-highlight-button">
                    {!! iconHtmlLocal('elfcms/admin/images/icons/search.svg', width: 18, height: 18, svg: true) !!}
                </button>
                <input type="search" name="search" id="search" value="{{ $search ?? '' }}" placeholder="">
            </div>
        </form>
        @if (!empty($search))
            <div class="table-search-result-title">
                    {{ __('elfcms::default.search_result_for') }} "{{ $search }}" <a
                        href="{{ route('admin.email.addresses') }}"
                        title="{{ __('elfcms::default.reset_search') }}">&#215;</a>
            </div>
        @endif
    </div>

    <div class="grid-table-wrapper">
        <table class="grid-table table-cols" style="--first-col:65px; --last-col:100px; --minw:800px; --cols-count:7;">
            <thead>
                <tr>
                    <th>
                        ID
                        <a href="{{ route('admin.email.events', UrlParams::addArr(['order' => 'id', 'trend' => ['desc', 'asc']])) }}"
                            class="ordering @if (UrlParams::case('order', ['id' => true])) {{ UrlParams::case('trend', ['desc' => 'desc'], 'asc') }} @endif"></a>
                    </th>
                    <th>
                        {{ __('elfcms::default.code') }}
                        <a href="{{ route('admin.email.events', UrlParams::addArr(['order' => 'code', 'trend' => ['desc', 'asc']])) }}"
                            class="ordering @if (UrlParams::case('order', ['code' => true])) {{ UrlParams::case('trend', ['desc' => 'desc'], 'asc') }} @endif"></a>
                    </th>
                    <th>
                        {{ __('elfcms::default.name') }}
                        <a href="{{ route('admin.email.events', UrlParams::addArr(['order' => 'name', 'trend' => ['desc', 'asc']])) }}"
                            class="ordering @if (UrlParams::case('order', ['name' => true])) {{ UrlParams::case('trend', ['desc' => 'desc'], 'asc') }} @endif"></a>
                    </th>
                    <th>
                        {{ __('elfcms::default.subject') }}
                        <a href="{{ route('admin.email.events', UrlParams::addArr(['order' => 'subject', 'trend' => ['desc', 'asc']])) }}"
                            class="ordering @if (UrlParams::case('order', ['subject' => true])) {{ UrlParams::case('trend', ['desc' => 'desc'], 'asc') }} @endif"></a>
                    </th>
                    <th>
                        {{ __('elfcms::default.created') }}
                        <a href="{{ route('admin.email.events', UrlParams::addArr(['order' => 'created_at', 'trend' => ['desc', 'asc']])) }}"
                            class="ordering @if (UrlParams::case('order', ['created_at' => true])) {{ UrlParams::case('trend', ['desc' => 'desc'], 'asc') }} @endif"></a>
                    </th>
                    <th>
                        {{ __('elfcms::default.updated') }}
                        <a href="{{ route('admin.email.events', UrlParams::addArr(['order' => 'updated_at', 'trend' => ['desc', 'asc']])) }}"
                            class="ordering @if (UrlParams::case('order', ['updated_at' => true])) {{ UrlParams::case('trend', ['desc' => 'desc'], 'asc') }} @endif"></a>
                    </th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($events as $event)
                    <tr data-id="{{ $event->id }}" class="@empty($event->active) inactive @endempty">
                        <td>{{ $event->id }}</td>
                        <td>
                            <a href="{{ route('admin.email.events.edit', $event->id) }}">
                                {{ $event->code }}
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('admin.email.events.edit', $event->id) }}">
                                {{ $event->name }}
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('admin.email.events.edit', $event->id) }}">
                                {{ $event->subject }}
                            </a>
                        </td>
                        <td>{{ $event->created_at }}</td>
                        <td>{{ $event->updated_at }}</td>
                        <td class="table-button-column">
                            <a href="{{ route('admin.email.events.edit', $event->id) }}" class="button icon-button"
                                title="{{ __('elfcms::default.edit') }}">
                                {!! iconHtmlLocal('elfcms/admin/images/icons/buttons/edit.svg', svg: true) !!}
                            </a>
                            @if (!in_array($event->code, $protected))
                                <form action="{{ route('admin.email.events.destroy', $event->id) }}" method="POST"
                                    data-submit="check">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="id" value="{{ $event->id }}">
                                    <input type="hidden" name="name" value="{{ $event->name }}">
                                    <button type="submit" class="button icon-button icon-alarm-button"
                                        title="{{ __('elfcms::default.delete') }}">
                                        {!! iconHtmlLocal('elfcms/admin/images/icons/buttons/delete.svg', svg: true) !!}
                                    </button>
                                </form>
                            @else
                                <button class="button icon-button icon-alarm-button"
                                    title="{{ __('elfcms::default.delete') }}" disabled>
                                    {!! iconHtmlLocal('elfcms/admin/images/icons/buttons/delete.svg', svg: true) !!}
                                </button>
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
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    let eventId = this.querySelector('[name="id"]').value,
                        eventName = this.querySelector('[name="name"]').value,
                        self = this
                    popup({
                        title: '{{ __('elfcms::default.deleting_of_element') }}' + eventId,
                        content: '<p>{{ __('elfcms::default.are_you_sure_to_deleting_event') }} "' +
                            eventName + '" (ID ' + eventId + ')?</p>',
                        buttons: [{
                                title: '{{ __('elfcms::default.delete') }}',
                                class: 'button color-button red-button',
                                callback: function() {
                                    self.submit()
                                }
                            },
                            {
                                title: '{{ __('elfcms::default.cancel') }}',
                                class: 'button cancel-button',
                                callback: 'close'
                            }
                        ],
                        class: 'danger'
                    })
                })
            })
        }
    </script>
@endsection
