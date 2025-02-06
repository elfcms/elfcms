<style>
    .fsfile-input-box {
        display: inline-block;
        position: relative;
        background-color: var(--main-contrast-color);
        border: 1px solid var(--main-select-shade-color);
        border-radius: 4px;
        /* box-shadow: -2px -2px 3px 0px rgba(0, 0, 0, 0.1) inset, 3px 3px 3px 0px rgba(255, 255, 255, 0.3) inset; */
        box-shadow: 1px 1px 10px 0px rgba(0, 0, 0, 0.2);
        padding: 5px;
        width: {{ $width }}px;
        height: {{ $height }}px;
        overflow: hidden;
    }

    .fsfile-input-image {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 10px;
        /* background-image: url(/elfcms/admin/images/icons/upload_48_mid.png);
    background-position: center;
    background-repeat: no-repeat; */
    }

    .fsfile-input-image.button-icon {
        background-image: url(/elfcms/admin/images/icons/upload_48_mid.png);
        background-position: center;
        background-repeat: no-repeat;
    }

    .fsfile-input-image img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .fsfile-input-text {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 27px;
        /* display: flex;
        align-items: center;
        justify-content: center; */
        text-align: center;
        font-size: .9em;
        color: #ffffff;
        background-color: rgba(0, 0, 0, 0.4);
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.4);
        padding: 5px;
        box-sizing: border-box;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .fsfile-input-button {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 10;
    }

    .fsfile-input-button input[type="file"] {
        display: block;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
    }

    .fsfile-delete {
        font-size: 24px;
        display: block;
        position: absolute;
        top: 0px;
        right: 5px;
        color: var(--main-danger-text-color);
        text-shadow: 1px 1px 2px var(--main-contrast-color), -1px -1px 2px var(--main-contrast-color), 1px -1px 2px var(--main-contrast-color), -1px 1px 2px var(--main-contrast-color);
        z-index: 20;
        cursor: pointer;
    }
</style>
<div class="fsfile-input-box" id="{{ $boxId }}">
    <input type="hidden" name="{{ $name }}_path" id="{{ $name }}_path" value="{{ $value }}">
    <div @class(['fsfile-delete', 'hidden' => empty($value)])>×</div>
    <div class="fsfile-input-image">
        @if (!empty($value))
            <img src="{{ route('files.preview', $file) }}" alt="" id="{{ $boxId }}_preview">
        @else
            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABAQMAAAAl21bKAAAABGdBTUEAALGPC/xhBQAAAAFzUkdCAK7OHOkAAAAZdEVYdENvbW1lbnQAQ3JlYXRlZCB3aXRoIEdJTVBXgQ4XAAAACXBIWXMAAC4jAAAuIwF4pT92AAAAA1BMVEVHcEyC+tLSAAAAAXRSTlMAQObYZgAAAApJREFUCNdjYAAAAAIAAeIhvDMAAAAASUVORK5CYII="
                alt="" id="{{ $boxId }}_preview">
        @endif
    </div>
    <div class="fsfile-input-text">
        @if (!empty($value))
            {{ __('elfcms::default.change_file') }}
            {{-- {{ $file->file_name ?? __('elfcms::default.change_file')}} --}}
        @else
            {{ __('elfcms::default.choose_file') }}
        @endif
    </div>
    <div class="fsfile-input-button">
        <input type="file" name="{{ $name }}" id="{{ $name }}" accept="{{ $accept ?? '*/*' }}">
    </div>
    @if ($download && !empty($file))
        <a href="{{ route('files', $file->id) }}" class="fsfile-input-download" download
            title="{{ $file->download_name ?? ($file->name ?? __('elfcms::default.download')) }}"></a>
    @endif
</div>

<script>
    //function inputFileExtComponent(input) {
    /* if (!input || !(input instanceof HTMLElement)) {
        console.warn("Input not found");
        return false;
    } */
    const input_{{ $boxId }} = document.getElementById('{{ $boxId }}');
    if (input_{{ $boxId }}) {

        //const wrapper = input.closest(".input-image-button");
        const imgBox = input_{{ $boxId }}.querySelector(".fsfile-input-image");
        const img = input_{{ $boxId }}.querySelector(".fsfile-input-image img");
        const input = input_{{ $boxId }}.querySelector('input[type="file"]');
        if (img && input) {
            const text = input_{{ $boxId }}.querySelector(".fsfile-input-text");

            //function deleteImage() {
            const del = input_{{ $boxId }}.querySelector(".fsfile-delete");
            const hid = input_{{ $boxId }}.querySelector('input[type="hidden"]');
            if (del) {
                del.addEventListener("click", function() {
                    if (img) {
                        img.src =
                            "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABAQMAAAAl21bKAAAABGdBTUEAALGPC/xhBQAAAAFzUkdCAK7OHOkAAAAZdEVYdENvbW1lbnQAQ3JlYXRlZCB3aXRoIEdJTVBXgQ4XAAAACXBIWXMAAC4jAAAuIwF4pT92AAAAA1BMVEVHcEyC+tLSAAAAAXRSTlMAQObYZgAAAApJREFUCNdjYAAAAAIAAeIhvDMAAAAASUVORK5CYII=";
                        if (imgBox) {
                            imgBox.classList.add("button-icon");
                        }
                    }
                    if (hid) {
                        hid.value = null;
                    }
                    input.value = null;
                    del.classList.add("hidden");
                    if (text) {
                        text.innerHTML = "{{ __('elfcms::default.choose_file') }}";
                    }
                });
            }
            //}

            //deleteImage(wrapper);

            input.addEventListener("change", function(e) {
                const files = e.target.files;
                if (files) {
                    const accept = input.getAttribute("accept");
                    const type = files[0].type.split('/')[1];
                    if (accept && !accept.includes(type)) {
                        //alert("File type not allowed");
                        return;
                    }
                    if (text) {
                        text.innerHTML = files[0].name;
                    }
                    /*const typeId = document.getElementById("type_id");
                    if (typeId) {
                        const optionToSelect  = typeId.querySelector('option[data-code="'+type+'"]') ?? typeId.querySelector('option[data-code="any"]');
                        if (optionToSelect) {
                            const value = optionToSelect.value;
                            typeId.value = value;
                        }
                    }*/
                    if (FileReader && files && files.length) {
                        var fReader = new FileReader();
                        fReader.onload = function() {
                            if (img) {
                                if (files[0].type.includes("image")) {
                                    if (imgBox) {
                                        imgBox.classList.add("button-icon");
                                    }
                                    img.src = fReader.result;
                                } else {
                                    if (imgBox) {
                                        imgBox.classList.remove("button-icon");
                                    };
                                    img.src =
                                        "/elfcms/admin/images/icons/filestorage/any.svg";
                                    fetch('/admin/helper/file-icon/' + (type ?? 'any'), {
                                            headers: {
                                                'X-Requested-With': 'XMLHttpRequest'
                                            }
                                        })
                                        .then((response) => {
                                            return response.text();
                                        })
                                        .then((data) => {
                                            img.src = data;
                                        });
                                }
                            }
                            //const del = wrapper.querySelector(".delete-image");
                            if (del) {
                                del.classList.remove("hidden");
                            }
                        };
                        fReader.readAsDataURL(files[0]);
                    }
                }
            });
        }
    }
</script>
