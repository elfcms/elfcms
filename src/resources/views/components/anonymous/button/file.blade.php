@props(['name' => 'file', 'value' => null, 'id' => uniqid()])
@inject('files', '\Elfcms\Elfcms\Elf\Files')
<div class="file-button" id="{{ $id }}">
    <input type="hidden" name="{{ $name }}[path]" id="{{ $name }}_path" value="{{ $value }}">
    <input type="file" name="{{ $name }}[file]" id="{{ $name }}_file">
    <div class="file-temp-name" data-text="{{ __('elfcms::default.choose_file') }}"></div>
    <div class="file-temp-delete">&#215;</div>
    @if (!empty($value))
    <div class="file-wrapper">
        <a href="{{ $value }}" class="file-link" target="_blank" rel="noopener noreferrer">{{ $files::name($value) }}</a>
        <div class="file-delete">&#215;</div>
    </div>
    @endif
</div>
<script>
const filebutton_{{ $id }} = document.getElementById('{{ $id }}');

if (filebutton_{{ $id }}) {
    window.onload = () => {
        inputFile(filebutton_{{ $id }});
    }
}
</script>
