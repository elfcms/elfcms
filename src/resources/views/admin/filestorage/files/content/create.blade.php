<div class="item-form">
    <h3>{{ $page['title'] }}</h3>
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
                <x-elfcms-input-checkbox code="active" label="{{ __('elfcms::default.active') }}" checked style="blue" />
            </div>
            <div class="input-box colored">
                <label for="name">{{ __('elfcms::default.name') }}</label>
                <div class="input-wrapper">
                    <input type="text" name="name" id="name">
                </div>
            </div>
            {{-- <div class="input-box colored">
                <label for="path">{{ __('elfcms::default.path') }}</label>
                <div class="input-wrapper">
                    <input type="text" name="path" id="path">
                </div>
                <div class="input-wrapper">
                    <div class="autoslug-wrapper">
                        <input type="checkbox" data-text-id="name" data-slug-id="path" class="autoslug" checked>
                        <div class="autoslug-button"></div>
                    </div>
                </div>
            </div> --}}
            <div class="input-box colored">
                <label for="file">{{ __('elfcms::default.file') }}</label>
                <div class="input-wrapper">
                    <x-elfcms-input-fileext code="file" accept="{{implode(',',$mimes)}}" />
                </div>
            </div>
            {{-- <div class="input-box colored">
                <label for="group_id">{{ __('elfcms::default.group') }}</label>
                <div class="input-wrapper">
                    <select name="group_id" id="group_id">
                        @foreach ($groups as $group)
                            <option value="{{ $group->id }}" @if (old('group_id') == $group->id) selected @endif data-group="{{ $group->id }}" data-code="{{ $group->code }}">
                                {{ $group->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div> --}}
            {{-- <div class="input-box colored">
                <label for="type_id">{{ __('elfcms::default.type') }}</label>
                <div class="input-wrapper">
                    <select name="type_id" id="type_id">
                        @foreach ($types as $type)
                            <option value="{{ $type->id }}" @if (old('type_id') == $type->id) selected @endif data-group="{{ $type->id }}" data-code="{{ $type->code }}">
                                {{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div> --}}
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
            {{-- <div class="input-box colored">
                <label for="additional_text">{{ __('filestorage::default.additional_text') }}</label>
                <div class="input-wrapper">
                    <textarea name="additional_text" id="additional_text"></textarea>
                </div>
            </div> --}}
            {{-- <div class="input-box colored">
                <label for="image">{{ __('elfcms::default.image') }}</label>
                <div class="input-wrapper">
                    <input type="hidden" name="image_path" id="image_path">
                    <div class="image-button">
                        <div class="delete-image hidden">&#215;</div>
                        <div class="image-button-img">
                            <img src="{{ asset('/vendor/elfcms/elfcms/admin/images/icons/upload.png') }}" alt="Upload file">
                        </div>
                        <div class="image-button-text">
                            {{ __('elfcms::default.choose_file') }}
                        </div>
                        <input type="file" name="image" id="image">
                    </div>
                </div>
            </div>
            <div class="input-box colored">
                <label for="preview">{{ __('elfcms::default.preview') }}</label>
                <div class="input-wrapper">
                    <input type="hidden" name="preview_path" id="preview_path">
                    <div class="image-button">
                        <div class="delete-image hidden">&#215;</div>
                        <div class="image-button-img">
                            <img src="{{ asset('/vendor/elfcms/elfcms/admin/images/icons/upload.png') }}" alt="Upload file">
                        </div>
                        <div class="image-button-text">
                            {{ __('elfcms::default.choose_file') }}
                        </div>
                        <input type="file" name="preview" id="preview">
                    </div>
                </div>
            </div>
            <div class="input-box colored">
                <label for="thumbnail">{{ __('elfcms::default.thumbnail') }}</label>
                <div class="input-wrapper">
                    <input type="hidden" name="thumbnail_path" id="thumbnail_path">
                    <div class="image-button">
                        <div class="delete-image hidden">&#215;</div>
                        <div class="image-button-img">
                            <img src="{{ asset('/vendor/elfcms/elfcms/admin/images/icons/upload.png') }}" alt="Upload file">
                        </div>
                        <div class="image-button-text">
                            {{ __('elfcms::default.choose_file') }}
                        </div>
                        <input type="file" name="thumbnail" id="thumbnail">
                    </div>
                </div>
            </div> --}}
            {{-- @if ($params['is_preview'])
            <div class="input-box colored">
                <label for="preview">{{ __('elfcms::default.preview') }}</label>
                <div class="input-wrapper">
                    <x-elfcms-input-image code="preview" />
                </div>
            </div>
            @endif
            @if ($params['is_thumbnail'])
            <div class="input-box colored">
                <label for="thumbnail">{{ __('elfcms::default.thumbnail') }}</label>
                <div class="input-wrapper">
                    <x-elfcms-input-image code="thumbnail" />
                </div>
            </div>
            @endif --}}
            <div class="input-box colored">
                <label for="position">{{ __('elfcms::default.position') }}</label>
                <div class="input-wrapper">
                    <input type="number" name="position" id="position" value="{{$position}}">
                </div>
            </div>
            {{-- <div class="input-box colored">
                <label for="link">{{ __('elfcms::default.link') }}</label>
                <div class="input-wrapper">
                    <input type="text" name="link" id="link">
                </div>
            </div> --}}
            {{-- <div class="input-box colored">
                <label for="option">{{ __('filestorage::default.option') }}</label>
                <div class="input-wrapper">
                    <input type="text" name="option" id="option">
                </div>
            </div> --}}
            {{-- <div class="input-box colored">
                <label for="tags">{{ __('elfcms::default.tags') }}</label>
                <div class="input-wrapper">
                    <div class="tag-form-wrapper">
                        <div class="tag-list-box"></div>
                        <div class="tag-input-box">
                            <input type="text" class="tag-input">
                            <button type="button" class="default-btn tag-add-button">Add</button>
                            <div class="tag-prompt-list"></div>
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>
        <div class="button-box single-box">
            <button type="submit" class="default-btn submit-button">{{ __('elfcms::default.submit') }}</button>
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
autoSlug('.autoslug')


//filestorageTagFormInit()

//add editor
runEditor('#description')
runEditor('#additional_text')
</script>
