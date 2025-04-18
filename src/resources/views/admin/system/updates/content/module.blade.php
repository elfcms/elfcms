@if (!empty($module))
    <tr>
        <td>
            <div class="small-checkbox-wrapper">
                <div class="small-checkbox" style="--switch-color:var(--default-color);">
                    <input type="checkbox" name="modules[]" id="module_{{ $module->name }}" value="{{ $module->name }}"
                        checked>
                    <i></i>
                </div>
            </div>
        </td>
        <td>{{ $module->name }}</td>
        <td>{{ $module->current_version }}</td>
        <td>{{ $module->latest_version }}</td>
        <td>{{ $module->source }}</td>
    </tr>
@endif
