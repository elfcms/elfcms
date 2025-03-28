<div class="item-form">
    <h2>{{ $page['title'] }}</h2>
    <form action="{{ route('admin.filestorage.files.store',$filestorage) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('POST')
        <input type="hidden" name="storage_id" value="{{ $filestorage->id }}">
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
                <x-elfcms::ui.checkbox.switch name="active" id="active" checked="true" />
            </div>
            <div class="input-box colored">
                <label for="name">{{ __('elfcms::default.name') }}</label>
                <div class="input-wrapper">
                    <input type="text" name="name" id="name">
                </div>
            </div>
            <div class="input-box colored">
                <label for="file">{{ __('elfcms::default.file') }}</label>
                <div class="input-wrapper">
                    <x-elf-input-file value="" :params="['name' => 'file','code'=>'file']" accept="{{implode(',',$mimes)}}" template="fs" />
                </div>
            </div>
            <div class="input-box colored">
                <label for="alt_text">{{ __('elfcms::default.alt_text') }}</label>
                <div class="input-wrapper">
                    <input type="text" name="alt_text" id="alt_text">
                </div>
            </div>
            <div class="input-box colored">
                <label for="link_title">{{ __('elfcms::default.link_title') }}</label>
                <div class="input-wrapper">
                    <input type="text" name="link_title" id="link_title">
                </div>
            </div>
            <div class="input-box colored">
                <label for="desctiption">{{ __('elfcms::default.description') }}</label>
                <div class="input-wrapper">
                    <textarea name="description" id="description"></textarea>
                </div>
            </div>
            <div class="input-box colored">
                <label for="position">{{ __('elfcms::default.position') }}</label>
                <div class="input-wrapper">
                    <input type="number" name="position" id="position" value="{{$position}}">
                </div>
            </div>
        </div>
        <div class="button-box single-box">
            <button type="submit" class="button color-text-button success-button">{{ __('elfcms::default.submit') }}</button>
            @if (empty($isAjax))
                <button type="submit" name="submit" value="save_and_close"
                    class="button color-text-button info-button">{{ __('elfcms::default.save_and_close') }}</button>
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

//add editor
//runEditor('#description')
</script>

