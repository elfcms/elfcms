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
            <x-elfcms::ui.checkbox.autoslug textid="group_{{$group->id}}_name" slugid="group_{{$group->id}}_code" checked="true" />
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
    <td class="table-button-column">
        <x-elfcms::ui.checkbox.delete name="group[{{$group->id}}][delete]" id="group_{{$group->id}}_delete" dataid="{{ $group->id }}" title="{{ __('elfcms::default.delete') }}" click="setDynamicUnitRowDelete(this)" />
    </td>

</tr>
