@extends('elfcms::admin.layouts.main')

@section('pagecontent')
    <div class="item-form">
        <h2>{{ __('elfcms::default.edit_menu_item') }} #{{ $item->id }}</h2>
        <form action="{{ route('admin.menus.items.update',['item'=>$item,'menu'=>$menu]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="colored-rows-box">
                <div class="input-box colored">
                    <label for="parent_id">{{ __('elfcms::default.parent_item') }}</label>
                    <div class="input-wrapper">
                        <select name="parent_id" id="parent_id">
                            <option value="" data-menu="0">{{ __('elfcms::default.none') }}</option>
                        @foreach ($items as $parent_item)
                            <option value="{{ $parent_item->id }}" @if($item->parent_id == $parent_item->id) selected @endif data-menu="{{ $parent_item->menu_id }}" @if ($parent_item->menu_id != $item->menu_id) style="display:none" @endif>{{ $parent_item->text }}</option>
                        @endforeach
                        </select>
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="position">{{ __('elfcms::default.position') }}</label>
                    <div class="input-wrapper">
                        <input type="number" name="position" id="position" autocomplete="off" value="{{ $item->position }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="text">{{ __('elfcms::default.text') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="text" id="text" autocomplete="off" value="{{ $item->text }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="link">{{ __('elfcms::default.link') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="link" id="link" autocomplete="off" value="{{ $item->link }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <label for="title">{{ __('elfcms::default.title') }}</label>
                    <div class="input-wrapper">
                        <input type="text" name="title" id="title" autocomplete="off" value="{{ $item->title }}">
                    </div>
                </div>
                <div class="input-box colored">
                    <div class="checkbox-switch-wrapper">
                        <div class="checkbox-switch blue">
                            <input type="checkbox" name="clickable" id="clickable" value="1" @if(!empty($item->clickable)) checked @endif>
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
                        <input type="text" name="handler" id="handler" autocomplete="off" value="{{ $item->handler }}">
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
                                @if(!empty($item->attributes))
                                @foreach ($item->attributes as $attribute_name => $attribute_value)
                                <div class="attributes-table-string-line" data-line="{{$loop->index}}">
                                    <div class="attributes-table-string">
                                        <input type="text" name="attributes_new[{{$loop->index}}][name]" id="attribute_new_name_{{$loop->index}}" data-attribute-name value="{{$attribute_name}}">
                                    </div>
                                    <div class="attributes-table-string">
                                        <input type="text" name="attributes_new[{{$loop->index}}][value]" id="attribute_new_value_{{$loop->index}}" data-attribute-value value="{{$attribute_value}}">
                                    </div>
                                    <div class="attributes-table-string">
                                        <button type="button" class="button" onclick="menuAttrDelete({{$loop->index}})">&#215;</button>
                                    </div>
                                </div>
                                @endforeach
                                @else
                                <div class="attributes-table-string-line" data-line="0">
                                    <div class="attributes-table-string">
                                        <input type="text" name="attributes_new[0][name]" id="attribute_new_name_0" data-attribute-name>
                                    </div>
                                    <div class="attributes-table-string">
                                        <input type="text" name="attributes_new[0][value]" id="attribute_new_value_0" data-attribute-value>
                                    </div>
                                    <div class="attributes-table-string">
                                        <button type="button" class="button icon-button icon-alarm-button string-delete-button" onclick="menuAttrDelete(0)">&#215;</button>
                                    </div>
                                </div>
                                @endif

                            </div>
                            <button type="button" class="button simple-button" id="addattributeline">{{ __('elfcms::default.add_attribute') }}</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="button-box single-box">
                <button type="submit" name="submit" value="save"
                    class="button color-button green-button">{{ __('elfcms::default.submit') }}</button>
                <button type="submit" name="submit" value="save_and_close"
                    class="button color-button blue-button">{{ __('elfcms::default.save_and_close') }}</button>
                <a href="{{ route('admin.menus.show',$menu) }}" class="button">{{ __('elfcms::default.cancel') }}</a>
            </div>
        </form>
    </div>
    <script>
        menuAttrBoxInit('#addattributeline',{{ count($item->attributes ?? [])-1 }})
    </script>

@endsection
