@extends('elfcms::admin.layouts.menu')

@section('menupage-content')

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
    <div class="table-search-box">
        <a href="{{ route('admin.menus.items.create',$menu) }}" class="default-btn success-button icon-text-button light-icon plus-button">{{__('elfcms::default.create_menu_item')}}</a>
    </div>
    <div class="menu-items" data-menu="{{ $menu->id }}">
        @foreach ($menu->topitems as $item)
        <x-elfcms::admin.menuitem :item="$item" :menu="$menu" />
        @endforeach
    </div>
    <script>
        /* const checkForms = document.querySelectorAll('form[data-submit="check"]')

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
        } */
    </script>
@endsection
@section('footerscript')
<script src="{{ asset('elfcms/admin/js/popnotifi.js') }}"></script>
<script src="{{ asset('elfcms/admin/js/menuitemorder.js') }}"></script>
@endsection
