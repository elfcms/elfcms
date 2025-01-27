<div class="item-form">
    <h3>{{ $page['title'] }}</h3>
    <form action="{{ route('admin.gallery.items.update',['gallery'=>$gallery,'galleryItem'=>$item]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="hidden" name="id" value="{{ $item->id }}">
        <div class="colored-rows-box">
            <div class="input-box colored">
                <div class="checkbox-wrapper">
                    <div class="checkbox-inner">
                        <input
                            type="checkbox"
                            name="active"
                            id="active"
                            @if ($item->active == 1)
                            checked
                            @endif
                        >
                        <i></i>
                        <label for="active">
                            {{ __('elfcms::default.active') }}
                        </label>
                    </div>
                </div>
            </div>
            <div class="input-box colored">
                <label for="name">{{ __('elfcms::default.name') }}</label>
                <div class="input-wrapper">
                    <input type="text" name="name" id="name" value="{{ $item->name }}">
                </div>
            </div>
            <div class="input-box colored">
                <label for="slug">{{ __('elfcms::default.slug') }}</label>
                <div class="input-wrapper">
                    <input type="text" name="slug" id="slug" value="{{ $item->slug }}">
                </div>
                <div class="input-wrapper">
                    <div class="autoslug-wrapper">
                        <input type="checkbox" data-text-id="name" data-slug-id="slug" class="autoslug" checked>
                        <div class="autoslug-button"></div>
                    </div>
                </div>
            </div>
            <div class="input-box colored">
                <label for="desctiption">{{ __('elfcms::default.description') }}</label>
                <div class="input-wrapper">
                    <textarea name="description" id="description">{{ $item->description }}</textarea>
                </div>
            </div>
            <div class="input-box colored">
                <label for="additional_text">{{ __('gallery::default.additional_text') }}</label>
                <div class="input-wrapper">
                    <textarea name="additional_text" id="additional_text">{{ $item->additional_text }}</textarea>
                </div>
            </div>
            <div class="input-box colored">
                <label for="image">{{ __('elfcms::default.image') }}</label>
                <div class="input-wrapper">
                    <x-elfcms-input-image code="image" value="{{ $item->image }}" />
                </div>
            </div>
            @if ($params['is_preview'])
            <div class="input-box colored">
                <label for="preview">{{ __('elfcms::default.preview') }}</label>
                <div class="input-wrapper">
                    <x-elfcms-input-image code="preview" value="{{ $item->preview }}" />
                </div>
            </div>
            @endif
            @if ($params['is_thumbnail'])
            <div class="input-box colored">
                <label for="thumbnail">{{ __('elfcms::default.thumbnail') }}</label>
                <div class="input-wrapper">
                    <x-elfcms-input-image code="thumbnail" value="{{ $item->thumbnail }}" />
                </div>
            </div>
            @endif

            <div class="input-box colored">
                <label for="position">{{ __('elfcms::default.position') }}</label>
                <div class="input-wrapper">
                    <input type="number" name="position" id="position" value="{{ $item->position }}">
                </div>
            </div>
            <div class="input-box colored">
                <label for="link">{{ __('elfcms::default.link') }}</label>
                <div class="input-wrapper">
                    <input type="text" name="link" id="link" value="{{ $item->link }}">
                </div>
            </div>
            {{-- <div class="input-box colored">
                <label for="option">{{ __('gallery::default.option') }}</label>
                <div class="input-wrapper">
                    <input type="text" name="option" id="option" value="{{ $item->option }}">
                </div>
            </div> --}}
            <div class="input-box colored">
                <label for="tags">{{ __('elfcms::default.tags') }}</label>
                <div class="input-wrapper">
                    <div class="tag-form-wrapper">
                        <div class="tag-list-box">
                            @foreach ($item->tags as $tag)
                            <div class="tag-item-box" data-id="{{ $tag->id }}">
                                <span class="tag-item-name">{{ $tag->name }}</span>
                                <span class="tag-item-remove" onclick="removeTagFromList(this)">&#215;</span>
                                <input type="hidden" name="tags[]" value="{{ $tag->id }}">
                            </div>
                            @endforeach
                        </div>
                        <div class="tag-input-box">
                            <input type="text" class="tag-input" autocomplete="off">
                            <button type="button" class="default-btn tag-add-button">Add</button>
                            <div class="tag-prompt-list"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="button-box single-box">
            <button type="submit" class="default-btn submit-button">{{ __('elfcms::default.submit') }}</button>
        </div>
    </form>
</div>
<script>
const previewInput = document.querySelector('#preview')
console.log(previewInput)
if (previewInput) {
    inputFileImg(previewInput)
}
const imageInput = document.querySelector('#image')
if (imageInput) {
    inputFileImg(imageInput)
}
const thumbnailInput = document.querySelector('#thumbnail')
if (thumbnailInput) {
    inputFileImg(thumbnailInput)
}
autoSlug('.autoslug')

galleryTagFormInit()

//add editor
runEditor('#description')
runEditor('#additional_text')
</script>
