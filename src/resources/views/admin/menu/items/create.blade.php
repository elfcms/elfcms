@extends('elfcms::admin.layouts.menu')

@section('menupage-content')

    @if (Session::has('success'))
        <div class="alert alert-success">{{ Session::get('success') }}</div>
    @endif
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="errors-list">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="item-form">
        <h3>{{ __('elfcms::default.menu') . ' "' . $menu->name. '": ' . __('elfcms::default.create_menu_item') }}</h3>
        <form action="{{ route('admin.menus.items.store',$menu) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('POST')
            <div class="colored-rows-box">
                <div class="input-box colored">
                    <label for="parent_id">{{ __('elfcms::default.parent_item') }}</label>
                    <div class="input-wrapper">
                        <select name="parent_id" id="parent_id">
                            <option value="" data-menu="0">{{ __('elfcms::default.none') }}</option>
                        @foreach ($items as $subitem)
                            <option value="{{ $subitem->id }}" @if($item_id == $subitem->id) selected @endif data-menu="{{ $subitem->menu_id }}">{{ $subitem->text }}</option>
                        @endforeach
                        </select>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="position">{{ __('elfcms::default.position') }}</label>
                    <div class="input-wrapper">
                        <input type="number" name="position" id="position" autocomplete="off">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="text">{{ __('elfcms::default.text') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="text" id="text" autocomplete="off">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="link">{{ __('elfcms::default.link') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="link" id="link" autocomplete="off">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="title">{{ __('elfcms::default.title') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="title" id="title" autocomplete="off">
                    </div>
                </div>
                <div class="input-box colored">
                    <div class="checkbox-switch-wrapper">
                        <div class="checkbox-switch blue">
                            <input type="checkbox" name="clickable" id="clickable" value="1" checked>
                            <i></i>
                        </div>
                        <label for="clickable">
                            {{ __('elfcms::default.item_is_clickable') }}
                        </label>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="handler">{{ __('elfcms::default.handler') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="handler" id="handler" autocomplete="off">
                    </div>
                </div>
                <div class="input-box colored" id="attributesbox">
                    <label>{{ __('elfcms::default.attributes') }}</label>
                    <div class="input-wrapper">
                        <div>
                            <div class="input-attributes-table">
                                <div class="attributes-table-head-line">
                                    <div class="attributes-table-head">
                                        {{ __('elfcms::default.name') }}
                                    </div>
                                    <div class="attributes-table-head">
                                        {{ __('elfcms::default.value') }}
                                    </div>
                                    <div class="attributes-table-head"></div>
                                </div>
                                <div class="attributes-table-string-line" data-line="0">
                                    <div class="attributes-table-string">
                                        <input type="text" name="attributes_new[0][name]" id="attribute_new_name_0" data-attribute-name>
                                    </div>
                                    <div class="attributes-table-string">
                                        <input type="text" name="attributes_new[0][value]" id="attribute_new_value_0" data-attribute-value>
                                    </div>
                                    <div class="attributes-table-string"></div>
                                </div>

                            </div>
                            <button type="button" class="default-btn attribute-table-add" id="addattributeline">{{ __('elfcms::default.add_attribute') }}</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="button-box single-box">
                <button type="submit" class="default-btn submit-button">{{ __('elfcms::default.submit') }}</button>
                <button type="submit" name="submit" value="save_and_close" class="default-btn alternate-button">{{ __('elfcms::default.save_and_close') }}</button>
                <a href="{{ route('admin.menus.show',$menu) }}" class="default-btn">{{ __('elfcms::default.cancel') }}</a>
            </div>
        </form>
    </div>
    <script>
        menuAttrBoxInit('#addattributeline')
    </script>


@endsection
