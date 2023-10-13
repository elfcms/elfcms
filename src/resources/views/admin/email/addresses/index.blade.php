@extends('elfcms::admin.layouts.email')

@section('emailpage-content')
    <div class="table-search-box">
        <a href="{{ route('admin.email.addresses.create') }}"
            class="default-btn success-button icon-text-button light-icon plus-button">{{ __('elfcms::default.create_email_address') }}</a>
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
        <table class="grid-table table-cols-6" style="--first-col:65px; --last-col:100px; --minw:800px">
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
                        <td class="button-column non-text-buttons">
                            <a href="{{ route('admin.email.addresses.edit', $address->id) }}"
                                class="default-btn edit-button" title="{{ __('elfcms::default.edit') }}"></a>
                            <form action="{{ route('admin.email.addresses.destroy', $address->id) }}" method="POST"
                                data-submit="check">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="id" value="{{ $address->id }}">
                                <input type="hidden" name="name" value="{{ $address->name }}">
                                <button type="submit" class="default-btn delete-button"
                                    title="{{ __('elfcms::default.delete') }}"></button>
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
                                class: 'default-btn delete-button',
                                callback: function() {
                                    self.submit()
                                }
                            },
                            {
                                title: '{{ __('elfcms::default.cancel') }}',
                                class: 'default-btn cancel-button',
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
