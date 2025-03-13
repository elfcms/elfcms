@extends('elfcms::admin.layouts.main')

@section('pagecontent')
    <div class="table-search-box">
        <a href="{{ route('admin.menus.items.create', $menu) }}"
            class="button round-button theme-button">
            {!! iconHtmlLocal('elfcms/admin/images/icons/plus.svg', svg: true) !!}
            <span>{{ __('elfcms::default.create_menu_item') }}</span>
        </a>
    </div>
    <div class="menu-items" data-menu="{{ $menu->id }}">
        @foreach ($menu->topitems as $item)
            <x-elfcms::admin.menuitem :item="$item" :menu="$menu" />
        @endforeach
    </div>
    <script>
        const checkForms = document.querySelectorAll('form[data-submit="check"]')

        if (checkForms) {
            checkForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    let itemId = this.querySelector('[name="id"]').value,
                        itemName = this.querySelector('[name="name"]').value,
                        self = this
                    popup({
                        title: '{{ __('elfcms::default.deleting_of_element') }}' + itemId,
                        content: '<p>{{ __('elfcms::default.are_you_sure_to_deleting_item') }} (ID ' +
                            itemId + ')?</p>',
                        buttons: [{
                                title: '{{ __('elfcms::default.delete') }}',
                                class: 'button color-text-button red-button',
                                callback: function() {
                                    self.submit()
                                }
                            },
                            {
                                title: '{{ __('elfcms::default.cancel') }}',
                                class: 'button color-text-button',
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
{{-- @section('footerscript')
<script src="{{ asset('elfcms/admin/js/popnotifi.js') }}"></script>
<script src="{{ asset('elfcms/admin/js/menuitemorder.js') }}"></script>
@endsection --}}
@once
    @push('footerscript')
        <script src="{{ asset('elfcms/admin/js/menuitemorder.js') }}"></script>
    @endpush
@endonce
