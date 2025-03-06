<div class="item-form">
    <h2>{{ $page['title'] }}</h2>
    <form action="{{ route('admin.filestorage.files.update', ['filestorage' => $filestorage, 'file' => $file]) }}"
        method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="hidden" name="storage_id" value="{{ $filestorage->id }}">
        <input type="hidden" name="id" value="{{ $file->id }}">
        <div class="colored-rows-box">
            <div class="input-box colored">
                <label>{{ __('elfcms::default.storage') }}</label>
                <div class="input-wrapper">
                    <strong>{{ $filestorage->name }}</strong>
                </div>
            </div>
            <div class="input-box colored">
                <label for="active">
                    {{ __('elfcms::default.active') }}
                </label>
                <x-elfcms::ui.checkbox.switch name="active" id="active" checked="{{ $file->active }}" />
            </div>
            <div class="input-box colored">
                <label for="name">{{ __('elfcms::default.name') }}</label>
                <div class="input-wrapper">
                    <input type="text" name="name" id="name" value="{{ $file->name }}">
                </div>
            </div>
            <div class="input-box colored">
                <label for="file">{{ __('elfcms::default.file') }}</label>
                <x-elf-input-file value="{{$file->public_path}}" :params="['name' => 'file','code'=>'file']" accept="{{implode(',',$mimes)}}" :download="true" template="fs" />
            </div>
            <div class="input-box colored">
                <label for="alt_text">{{ __('elfcms::default.alt_text') }}</label>
                <div class="input-wrapper">
                    <input type="text" name="alt_text" id="alt_text" value="{{ $file->alt_text }}">
                </div>
            </div>
            <div class="input-box colored">
                <label for="link_title">{{ __('elfcms::default.link_title') }}</label>
                <div class="input-wrapper">
                    <input type="text" name="link_title" id="link_title" value="{{ $file->link_title }}">
                </div>
            </div>
            <div class="input-box colored">
                <label for="desctiption">{{ __('elfcms::default.description') }}</label>
                <div class="input-wrapper">
                    <textarea name="description" id="description">{{ $file->description }}</textarea>
                </div>
            </div>
            <div class="input-box colored">
                <label for="position">{{ __('elfcms::default.position') }}</label>
                <div class="input-wrapper">
                    <input type="number" name="position" id="position" value="{{ $file->position }}">
                </div>
            </div>
        </div>
        <div class="button-box single-box">
            <button type="submit" class="button color-text-button green-button">{{ __('elfcms::default.submit') }}</button>
            @if (empty($isAjax))
                <button type="submit" name="submit" value="save_and_close"
                    class="button color-text-button blue-button">{{ __('elfcms::default.save_and_close') }}</button>
                <a href="{{ route('admin.filestorage.index') }}"
                    class="button color-text-button">{{ __('elfcms::default.cancel') }}</a>
            @endif
        </div>
    </form>
</div>
<script>
    const previewInput = document.querySelector('#file')
    if (previewInput) {
        inputFileExtComponent(previewInput)
    }
    /* const imageInput = document.querySelector('#image')
    if (imageInput) {
        inputFileImg(imageInput)
    }
    const thumbnailInput = document.querySelector('#thumbnail')
    if (thumbnailInput) {
        inputFileImg(thumbnailInput)
    } */
    //autoSlug('.autoslug')


    //filestorageTagFormInit()

    //add editor
    runEditor('#description')
</script>
