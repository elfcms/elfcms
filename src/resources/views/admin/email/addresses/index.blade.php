@extends('elfcms::admin.layouts.main')

@section('pagecontent')

<div class="table-search-box">
    <a href="{{ route('admin.email.addresses.create') }}" class="button round-button theme-button">
        {!! iconHtmlLocal('elfcms/admin/images/icons/plus.svg', svg: true) !!}
        <span class="button-collapsed-text">
            {{ __('elfcms::default.create_email_address') }}
        </span>
    </a>
    <form action="{{ route('admin.email.addresses') }}" method="get">
        <div class="round-input-wrapper">
            <button type="submit" class="button round-button theme-button inner-button default-highlight-button">
                {!! iconHtmlLocal('elfcms/admin/images/icons/search.svg', width: 18, height: 18, svg: true) !!}
            </button>
            <input type="search" name="search" id="search" value="{{ $search ?? '' }}" placeholder="">
        </div>
    </form>
    <div class="table-search-result-title">
        @if (!empty($search))
            {{ __('elfcms::default.search_result_for') }} "{{ $search }}" <a
                href="{{ route('admin.email.addresses') }}" title="{{ __('elfcms::default.reset_search') }}">&#215;</a>
        @endif
    </div>
</div>
{{-- @if (Session::has('success'))
    <x-elf-notify type="success" title="{{ __('elfcms::default.success') }}" text="{{ Session::get('success') }}" />
@endif
@if ($errors->any())
    <x-elf-notify type="error" title="{{ __('elfcms::default.error') }}" text="{!! '<ul><li>' . implode('</li><li>', $errors->all()) . '</li></ul>' !!}" />
@endif --}}

    <div class="grid-table-wrapper">
        <table class="grid-table table-cols" style="--first-col:65px; --last-col:100px; --minw:800px; --cols-count:6;">
            <thead>
                <tr>
                    <th>
                        ID
                        <a href="{{ route('admin.email.addresses', UrlParams::addArr(['order' => 'id', 'trend' => ['desc', 'asc']])) }}"
                            class="ordering @if (UrlParams::case('order', ['id' => true])) {{ UrlParams::case('trend', ['desc' => 'desc'], 'asc') }} @endif"></a>
                    </th>
                    <th>
                        {{ __('elfcms::default.name') }}
                        <a href="{{ route('admin.email.addresses', UrlParams::addArr(['order' => 'name', 'trend' => ['desc', 'asc']])) }}"
                            class="ordering @if (UrlParams::case('order', ['name' => true])) {{ UrlParams::case('trend', ['desc' => 'desc'], 'asc') }} @endif"></a>
                    </th>
                    <th>
                        {{ __('elfcms::default.email') }}
                        <a href="{{ route('admin.email.addresses', UrlParams::addArr(['order' => 'email', 'trend' => ['desc', 'asc']])) }}"
                            class="ordering @if (UrlParams::case('order', ['email' => true])) {{ UrlParams::case('trend', ['desc' => 'desc'], 'asc') }} @endif"></a>
                    </th>
                    <th>
                        {{ __('elfcms::default.created') }}
                        <a href="{{ route('admin.email.addresses', UrlParams::addArr(['order' => 'created_at', 'trend' => ['desc', 'asc']])) }}"
                            class="ordering @if (UrlParams::case('order', ['created_at' => true])) {{ UrlParams::case('trend', ['desc' => 'desc'], 'asc') }} @endif"></a>
                    </th>
                    <th>
                        {{ __('elfcms::default.updated') }}
                        <a href="{{ route('admin.email.addresses', UrlParams::addArr(['order' => 'updated_at', 'trend' => ['desc', 'asc']])) }}"
                            class="ordering @if (UrlParams::case('order', ['updated_at' => true])) {{ UrlParams::case('trend', ['desc' => 'desc'], 'asc') }} @endif"></a>
                    </th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($addresses as $address)
                    <tr data-id="{{ $address->id }}" class="@empty($address->active) inactive @endempty">
                        <td>{{ $address->id }}</td>
                        <td>
                            <a href="{{ route('admin.email.addresses.edit', $address->id) }}">
                                {{ $address->name }}
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('admin.email.addresses.edit', $address->id) }}">
                                {{ $address->email }}
                            </a>
                        </td>
                        <td>{{ $address->created_at }}</td>
                        <td>{{ $address->updated_at }}</td>
                        <td class="table-button-column">
                            <a href="{{ route('admin.email.addresses.edit', $address->id) }}"
                                class="button icon-button" title="{{ __('elfcms::default.edit') }}">
                                {!! iconHtmlLocal('elfcms/admin/images/icons/buttons/edit.svg', svg: true) !!}
                            </a>
                            <form action="{{ route('admin.email.addresses.destroy', $address->id) }}" method="POST"
                                data-submit="check">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="id" value="{{ $address->id }}">
                                <input type="hidden" name="name" value="{{ $address->name }}">
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
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    let addressId = this.querySelector('[name="id"]').value,
                        addressName = this.querySelector('[name="name"]').value,
                        self = this
                    popup({
                        title: '{{ __('elfcms::default.deleting_of_element') }}' + addressId,
                        content: '<p>{{ __('elfcms::default.are_you_sure_to_deleting_address') }} "' +
                            addressName + '" (ID ' + addressId + ')?</p>',
                        buttons: [{
                                title: '{{ __('elfcms::default.delete') }}',
                                class: 'button delete-button',
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
