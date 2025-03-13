if (typeof adminPath === 'undefined') {
    var adminPath = '/admin';
}
function inputFileExtComponent(input) {
    if (!input || !(input instanceof HTMLElement)) {
        console.warn("Input not found");
        return false;
    }
    const wrapper = input.closest(".input-image-button");
    if (wrapper) {
        const img = wrapper.querySelector(".image-button-img img");
        const text = wrapper.querySelector(".image-button-text");

        function deleteImage(wrap) {
            const del = wrap.querySelector(".delete-image");
            const hid = wrap
                .closest(".input-wrapper")
                .querySelector('input[type="hidden"]');
            if (del) {
                del.addEventListener("click", function () {
                    if (img) {
                        img.src = "/elfcms/admin/images/icons/upload.png";
                        img.classList.remove("button-icon");
                    }
                    if (hid) {
                        hid.value = null;
                    }
                    input.value = null;
                    this.classList.add("hidden");
                    text.innerHTML = "Choose file";
                });
            }
        }

        deleteImage(wrapper);

        input.addEventListener("change", function (e) {
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
                const typeId = document.getElementById("type_id");
                if (typeId) {
                    const optionToSelect  = typeId.querySelector('option[data-code="'+type+'"]') ?? typeId.querySelector('option[data-code="any"]');
                    if (optionToSelect) {
                        const value = optionToSelect.value;
                        typeId.value = value;
                    }
                }
                if (FileReader && files && files.length) {
                    var fReader = new FileReader();
                    fReader.onload = function () {
                        if (img) {
                            if (files[0].type.includes("image")) {
                                img.classList.remove("button-icon");
                                img.src = fReader.result;
                            } else {
                                img.classList.add("button-icon");
                                img.src =
                                    "/elfcms/admin/images/icons/filestorage/any.svg";
                                fetch(adminPath + '/helper/file-icon/' + (type ?? 'any'),{headers: {'X-Requested-With': 'XMLHttpRequest'}})
                                    .then((response) => {
                                        return response.text();
                                    })
                                    .then((data) => {
                                        img.src = data;
                                    });
                            }
                        }
                        const del = wrapper.querySelector(".delete-image");
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
