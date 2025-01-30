<a href="{{ route('admin.filestorage.files.edit',['filestorage'=>$filestorage,'filestorageFile'=>$file]) }}" class="filestorage-file-tile filestorage-file-element" title="{{ __('elfcms::default.edit') . ' ' . $file->name }}" style="order:{{$file->position}};" data-id="{{ $file->id }}" data-slug="{{ $file->slug }}">
    <img src="
        {{ route('files.preview',$file) }}
    " alt="">
    <h5>{{ $file->name }}</h5>
    <div class="delete-file-box" title="{{ __('elfcms::default.delete') }}">
        <input type="checkbox" name="file[{{$file->id}}][delete]" id="file_{{$file->id}}_delete" data-field="delete" onclick="event.stopPropagation()">
        <i></i>
    </div>
    <input type="hidden" name="file[{{$file->id}}][position]" value="{{$file->position}}" data-field="position">
</a>
