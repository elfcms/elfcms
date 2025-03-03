@extends('elfcms::admin.layouts.main')

@section('pagecontent')
    <div class="table-search-box">
        <a href="{{ route('admin.page.pages.create') }}" class="button round-button theme-button">
            {!! iconHtmlLocal('elfcms/admin/images/icons/plus.svg', svg: true) !!}
            <span class="button-collapsed-text">
                {{ __('elfcms::default.create_page') }}
            </span>
        </a>
    </div>

    <div class="grid-table-wrapper">
        <table class="grid-table table-cols" style="--first-col:65px; --last-col:140px; --minw:800px;  --cols-count:7;">
            <thead>
                <tr>
                    <th>
                        ID
                        <a href="{{ route('admin.page.pages', UrlParams::addArr(['order' => 'id', 'trend' => ['desc', 'asc']])) }}"
                            class="ordering @if (UrlParams::case('order', ['id' => true])) {{ UrlParams::case('trend', ['desc' => 'desc'], 'asc') }} @endif"></a>
                    </th>
                    <th>
                        {{ __('elfcms::default.title') }}
                        <a href="{{ route('admin.page.pages', UrlParams::addArr(['order' => 'title', 'trend' => ['desc', 'asc']])) }}"
                            class="ordering @if (UrlParams::case('order', ['title' => true])) {{ UrlParams::case('trend', ['desc' => 'desc'], 'asc') }} @endif"></a>
                    </th>
                    <th>
                        {{ __('elfcms::default.name') }}
                        <a href="{{ route('admin.page.pages', UrlParams::addArr(['order' => 'name', 'trend' => ['desc', 'asc']])) }}"
                            class="ordering @if (UrlParams::case('order', ['name' => true])) {{ UrlParams::case('trend', ['desc' => 'desc'], 'asc') }} @endif"></a>
                    </th>
                    <th>
                        {{ __('elfcms::default.slug') }}
                        <a href="{{ route('admin.page.pages', UrlParams::addArr(['order' => 'slug', 'trend' => ['desc', 'asc']])) }}"
                            class="ordering @if (UrlParams::case('order', ['slug' => true])) {{ UrlParams::case('trend', ['desc' => 'desc'], 'asc') }} @endif"></a>
                    </th>
                    <th>
                        {{ __('elfcms::default.created') }}
                        <a href="{{ route('admin.page.pages', UrlParams::addArr(['order' => 'created_at', 'trend' => ['desc', 'asc']])) }}"
                            class="ordering @if (UrlParams::case('order', ['created_at' => true])) {{ UrlParams::case('trend', ['desc' => 'desc'], 'asc') }} @endif"></a>
                    </th>
                    <th>
                        {{ __('elfcms::default.updated') }}
                        <a href="{{ route('admin.page.pages', UrlParams::addArr(['order' => 'updated_at', 'trend' => ['desc', 'asc']])) }}"
                            class="ordering @if (UrlParams::case('order', ['updated_at' => true])) {{ UrlParams::case('trend', ['desc' => 'desc'], 'asc') }} @endif"></a>
                    </th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pages as $pageData)
                    <tr data-id="{{ $pageData->id }}" class="@empty($pageData->active) inactive @endempty">
                        <td>{{ $pageData->id }}</td>
                        <td>
                            <a href="{{ route('admin.page.pages.edit', $pageData->id) }}">
                                {{ $pageData->name }}
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('admin.page.pages.edit', $pageData->id) }}">
                                {{ $pageData->title }}
                            </a>
                        </td>
                        <td>{{ $pageData->slug }}</td>
                        <td>{{ $pageData->created_at }}</td>
                        <td>{{ $pageData->updated_at }}</td>
                        <td class="table-button-column">
                            <a href="{{ route('admin.page.pages.edit', $pageData->id) }}" class="button icon-button"
                                title="{{ __('elfcms::default.edit') }}">
                                {!! iconHtmlLocal('elfcms/admin/images/icons/buttons/edit.svg', svg: true) !!}
                            </a>
                            <form action="{{ route('admin.page.pages.destroy', $pageData->id) }}" method="POST"
                                data-submit="check">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="id" value="{{ $pageData->id }}">
                                <input type="hidden" name="name" value="{{ $pageData->name }}">
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
    {{ $pages->links('elfcms::admin.layouts.pagination') }}

    <script>
        const checkForms = document.querySelectorAll('form[data-submit="check"]')

        if (checkForms) {
            checkForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    let pageId = this.querySelector('[name="id"]').value,
                        pageName = this.querySelector('[name="name"]').value,
                        self = this
                    popup({
                        title: '{{ __('elfcms::default.deleting_of_element') }}' + pageId,
                        content: '<p>{{ __('elfcms::default.are_you_sure_to_deleting_page') }} "' +
                            pageName + '" (ID ' + pageId + ')?</p>',
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
