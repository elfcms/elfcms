<tr data-id="{{ $type->id }}">
    <td>
        <span>{{ $type->id }}</span>
        <input type="hidden" name="type[{{$type->id}}][edited]" value="0">
    </td>
    <td>
        <input type="text" name="type[{{$type->id}}][name]" id="type_{{$type->id}}_name" value="{{ $type->name }}" data-name="name">
    </td>
    <td>
        <div class="input-wrapper">
            <div class="autoslug-wrapper">
                <input type="checkbox" data-text-id="type_{{$type->id}}_name" data-slug-id="type_{{$type->id}}_code" class="autoslug" checked>
                <div class="autoslug-button"></div>
            </div>
        </div>
    </td>
    <td>
        <input type="text" name="type[{{$type->id}}][code]" id="type_{{$type->id}}_code" value="{{ $type->code }}" data-name="code">
    </td>
    <td>
        <select name="type[{{$type->id}}][group_id]" id="type_{{$type->id}}_group_id" data-name="group_id" data-id="{{$type->id}}" >
            <option value="0" data-code="none" >{{ __('elfcms::default.none') }}</option>
            @foreach ($groups as $group)
            <option value="{{ $group->id }}" data-code="{{ $group->code }}" @if ($type->group_id==$group->id) selected @endif>{{ $group->name }}</option>
            @endforeach
        </select>
    </td>
    <td>
        <input type="text" name="type[{{$type->id}}][description]" id="type_{{$type->id}}_description" value="{{ $type->description }}" data-name="description">
    </td>
    <td>
        <input type="text" name="type[{{$type->id}}][mime_prefix]" id="type_{{$type->id}}_mime_prefix" value="{{ $type->mime_prefix }}" data-name="mime_prefix">
    </td>
    <td>
        <input type="text" name="type[{{$type->id}}][mime_type]" id="type_{{$type->id}}_mime_type" value="{{ $type->mime_type }}" data-name="mime_type">
    </td>
    <td class="table-button-column">
        <div class="check-delete-wrapper">
            <input type="checkbox" name="type[{{$type->id}}][delete]" id="type_{{$type->id}}_delete" value="1" data-id="{{ $type->id }}" title="{{ __('elfcms::default.delete') }}" onclick="setDynamicUnitRowDelete(this)">
            <i></i>
        </div>
    </td>
</tr>
