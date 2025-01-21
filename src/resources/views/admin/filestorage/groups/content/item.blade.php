<tr data-id="{{ $group->id }}">
    <td>
        <span>{{ $group->id }}</span>
        <input type="hidden" name="group[{{$group->id}}][edited]" value="0">
    </td>
    <td>
        <input type="text" name="group[{{$group->id}}][name]" id="group_{{$group->id}}_name" value="{{ $group->name }}" data-name="name">
    </td>
    <td>
        <div class="input-wrapper">
            <div class="autoslug-wrapper">
                <input type="checkbox" data-text-id="group_{{$group->id}}_name" data-slug-id="group_{{$group->id}}_code" class="autoslug" checked>
                <div class="autoslug-button"></div>
            </div>
        </div>
    </td>
    <td>
        <input type="text" name="group[{{$group->id}}][code]" id="group_{{$group->id}}_code" value="{{ $group->code }}" data-name="code">
    </td>
    <td>
        <input type="text" name="group[{{$group->id}}][description]" id="group_{{$group->id}}_description" value="{{ $group->description }}" data-name="description">
    </td>
    <td>
        <input type="text" name="group[{{$group->id}}][mime_prefix]" id="group_{{$group->id}}_mime_prefix" value="{{ $group->mime_prefix }}" data-name="mime_prefix">
    </td>
    {{-- <td>
        <div class="checkbox-switch green">
            <input type="checkbox" name="group[{{$group->id}}][is_filter]" id="group_{{$group->id}}_is_filter" value="1" data-name="is_filter" @if($group->is_filter) checked @endif>
            <i></i>
        </div>
    </td> --}}
    <td class="button-column non-text-buttons">
        <div class="check-delete-wrapper">
            <input type="checkbox" name="group[{{$group->id}}][delete]" id="group_{{$group->id}}_delete" value="1" data-id="{{ $group->id }}" title="{{ __('elfcms::default.delete') }}" onclick="setDynamicUnitRowDelete(this)">
            <i></i>
        </div>
    </td>

    {{-- <td @class(['table-subrow','showed' => $group->data_type->code=='list'])>
        <div class="infobox-option-box">
            <div class="infobox-option-multiple-line">
                <div class="checkbox-switch green" data-column="multiple">
                    <input type="checkbox" name="group[{{$group->id}}][multiple]" id="group_{{$group->id}}_multiple" value="1" data-name="multiple" @if($group->multiple) checked @endif>
                    <i></i>
                </div>
                <label for="group_{{$group->id}}_multiple">{{ __('infobox::default.multiple') }}</label>
            </div>
            <div class="infobox-option-box-label">
                {{ __('infobox::default.options') }}
            </div>
            <div class="infobox-option-table">
                <div class="infobox-option-table-head">
                    <div class="infobox-option-table-column">
                        {{ __('infobox::default.key') }}
                    </div>
                    <div class="infobox-option-table-column">
                        {{ __('infobox::default.value') }}
                    </div>
                    <div class="infobox-option-table-column">
                        {{ __('elfcms::default.delete') }}
                    </div>
                </div>
                <div class="infobox-option-table-body">
                @if (!empty($group->options))
                    @foreach ($group->options as $key => $value)
                    <div class="infobox-option-table-row">
                        <div class="infobox-option-table-column">
                            <input type="text" name="group[{{$group->id}}][options][{{$loop->index}}][key]" value="{{ $key }}" oninput="checkOptionChange(this)" data-loop="{{$loop->index}}" data-name="key">
                        </div>
                        <div class="infobox-option-table-column">
                            <input type="text" name="group[{{$group->id}}][options][{{$loop->index}}][value]" value="{{ $value }}" oninput="checkOptionChange(this)" data-loop="{{$loop->index}}" data-name="value">
                        </div>
                        <div class="infobox-option-table-column">
                            <div class="checkbox-switch red">
                                <input type="checkbox" name="group[{{$group->id}}][options][{{$loop->index}}][delete]" value="1" oninput="checkOptionChange(this)" data-loop="{{$loop->index}}" data-name="delete">
                                <i></i>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @endif
                </div>
                <div class="infobox-option-table-add">
                    <button class="default-btn" data-id="{{$group->id}}" onclick="addOption(this{{!$group->code ? '' : ',false' }})">{{ __('elfcms::default.add_option') }}</button>
                </div>
            </div>
        </div>
    </td> --}}
</tr>
