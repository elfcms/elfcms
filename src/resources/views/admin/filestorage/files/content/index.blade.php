<form action="{{ route('admin.filestorage.files.groupSave', $filestorage) }}" method="POST" name="filestorage-file-list"
    id="filestorage-file-list">
    @method('POST')
    @csrf
    <div class="filestorage-files-content dragndrop-wrapper" draggable="false"
        data-uploadtext="{{ __('elfcms::default.file_upload') }}">
        <a href="{{ route('admin.filestorage.files.create', $filestorage) }}"
            class="filestorage-file-add filestorage-file-tile" id="filestoragefilecreate"
            title="{{ __('elfcms::default.create_file') }}"></a>
        @foreach ($filestorage->files as $file)
            @include('elfcms::admin.filestorage.files.content.file')
        @endforeach
    </div>
    <div class="filestorage-files-buttons">
        <button id="submitbutton" class="button color-text-button" style="--button-color:var(--default-color,var(--success-color))"
            disabled>{{ __('elfcms::default.save') }}</button>
    </div>
</form>


<script>
    const submitItems = document.querySelector('.filestorage-files-buttons #submitbutton');
    const editItemElements = document.querySelectorAll('.filestorage-file-element');
    const createButton = document.querySelector('#filestoragefilecreate');
    const dragndropBox = document.querySelector('.dragndrop-wrapper');
    const fileListForm = document.querySelector('#filestorage-file-list');

    if (fileListForm) {
        fileListForm.addEventListener('submit', function(e) {
            e.preventDefault();
            return false;
        });
    }

    if (submitItems) {
        submitItems.addEventListener('click', function(e) {
            e.preventDefault();
            let form = fileListForm;
            if (!form) {
                form = this.closest('form');
            }
            if (!form) {
                return false;
            }
            const filesForDelete = document.querySelectorAll('.fordelete');
            if (filesForDelete && filesForDelete.length > 0) {
                popup({
                    title: '{{ __('elfcms::default.deleting_files') }}',
                    content: '{{ __('elfcms::default.marked_files_will_be_removed') }}',
                    buttons: [{
                            title: '{{ __('elfcms::default.delete') }}',
                            class: 'button color-text-button danger-button',
                            callback: [
                                function() {
                                    fileListSave(form);
                                },
                                'close'
                            ]
                        },
                        {
                            title: '{{ __('elfcms::default.cancel') }}',
                            class: 'button color-text-button',
                            callback: 'close'
                        }
                    ],
                    class: 'danger'
                });
            } else {
                fileListSave(form);
            }
        });
    }

    function preloadSet(element) {
        if (typeof element === 'string') {
            element = document.querySelector(element);
        }
        if (!(element instanceof HTMLElement) && element !== document) {
            return false;
        }
        const preloader = document.createElement('div')
        preloader.classList.add('preload-wrapper');
        preloader.insertAdjacentHTML('beforeend', '<div class="preload-box"><div></div><div></div><div></div></div>');
        element.append(preloader);

        return preloader;
    }

    function preloadUnset(preloader) {
        if (typeof preloader === 'string') {
            preloader = document.querySelector(preloader);
        }
        if (!(preloader instanceof HTMLElement)) {
            return false;
        }
        preloader.remove();
    }

    function fileListSave(form) {
        if (!form || !form.action) {
            return false;
        }
        const formData = new FormData(form);
        const filesBox = document.querySelector('.filestorage-files-content');
        let preloader = preloadSet('.big-container');
        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            credentials: 'same-origin',
            body: formData
        }).then(
            (result) => result.json()
        ).then(
            (data) => {
                if (data.result && data.result == 'success') {
                    const forDeletes = document.querySelectorAll('.filestorage-file-element.fordelete');
                    if (forDeletes) {
                        forDeletes.forEach(delElem => {
                            delElem.remove();
                        });
                    }
                    submitItems.disabled = true;
                    /* popup({
                        title: '{{ __('elfcms::default.edit_filestorage') }}',
                        content: '{{ __('elfcms::default.filestorage_edited_successfully') }}',
                        buttons: [{
                            title: 'OK',
                            class: 'button submit-button',
                            callback: 'close'
                        }],
                        class: 'submit'
                    }); */
                    try {
                        new Notify({
                            close: {
                                auto: true,
                                time: 300,
                                delay: 3500
                            },
                            position: 'center',
                        }).new({
                            type: 'success',
                            title: '{{ __('elfcms::default.edit_filestorage') }}',
                            text: '{{ __('elfcms::default.filestorage_edited_successfully') }}',
                        });
                    } catch (e) {
                        //
                    }
                } else {
                    if (data.errors && data.message) {
                        /* popup({
                            title: '{{ __('elfcms::default.error') }}',
                            content: data.message,
                            buttons: [{
                                title: 'OK',
                                class: 'button submit-button',
                                callback: 'close'
                            }],
                            class: 'danger'
                        }); */
                        try {
                            new Notify({
                                close: {
                                    auto: true,
                                    time: 300,
                                    delay: 3500
                                },
                                position: 'center',
                            }).new({
                                type: 'error',
                                title: '{{ __('elfcms::default.error') }}',
                                text: data.message,
                            });
                        } catch (e) {
                            //
                        }
                    }
                }
                preloadUnset(preloader);
            }
        ).catch(error => {
            preloadUnset(preloader);
        });
    }

    function filesUpload(files) {
        if (!files) {
            return false;
        }

        let fileNames = [];
        let n = 0;
        for (key in files) {
            if (files[key] instanceof File) {
                fileNames[n] = files[key].name;
                n++;
            }
        };
        if (n > 0) {
            popup({
                title: '&nbsp;',
                content: '{{ __('elfcms::default.following_files_will_be_uploaded') }}: <br><br>' + fileNames
                    .join('<br>') + '<p>{{ __('elfcms::default.are_you_sure') }}</p>',
                buttons: [{
                        title: '{{ __('elfcms::default.cancel') }}',
                        class: 'button',
                        callback: 'close'
                    },
                    {
                        title: 'OK',
                        class: 'button color-text-button info-button',
                        callback: [
                            function() {
                                for (key in files) {
                                    if (files[key] instanceof File) {
                                        fileUpload(files[key], key);
                                    }
                                };
                            },
                            'close'
                        ]
                    }
                ],
                class: 'alternate'
            });
        }
    }

    function createLoder() {
        const box = document.createElement('div');
        box.classList.add('file-loader-box');
        const counter = document.createElement('div');
        counter.classList.add('file-loader-counter');
        const bar = document.createElement('div');
        bar.classList.add('file-loader-bar');
        const progress = document.createElement('div');
        bar.append(progress);
        box.append(counter, bar);
        return {
            box: box,
            counter: counter,
            bar: bar,
            progress: progress
        }
    }

    function fileUpload(file, key = null) {
        if (!(file instanceof File)) {
            return false;
        }
        let type = file.type.split('/')[1];
        if (type == 'svg+xml') {
            type = 'svg';
        }
        const fileItem = createItem({
            id: key,
            //slug: key,
            type: type,
            name: file.name,
            position: 1000,
            image: '/elfcms/admin/images/icons/filestorage/any.svg',
        }, true);

        fileItem.href = 'javascript:void(0)';

        dragndropBox.append(fileItem);

        const loader = createLoder();

        fileItem.append(loader.box);

        const formData = new FormData();
        formData.append('file', file, file.name);
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('_method', 'POST');
        formData.append('active', '1');
        eajax('{{ route('admin.filestorage.files.store', $filestorage) }}', {
            method: 'post',
            formData: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            resolve: function(result) {
                let answer = JSON.parse(result.response);
                if (result.status > 200) {
                    let message = '';
                    if (result.status == 422) {
                        for (key in answer) {
                            message += answer[key].join(" ");
                        }
                    } else if (result.status == 500) {
                        message = answer.message;
                    } else {
                        message = 'Error ' + result.status;
                    }
                    popup({
                        title: 'Error ' + result.status,
                        content: message,
                        buttons: [{
                            title: 'OK',
                            class: 'button color-text-button',
                            callback: [
                                function() {
                                    fileItem.remove();
                                },
                                'close'
                            ]
                        }],
                        class: 'danger'
                    });
                } else if (answer && answer.result && answer.result == 'success') {
                    setItemData(fileItem, answer.data, true, function() {
                        if (loader.box) {
                            loader.box.remove();
                        }
                    });
                } else {
                    loader.counter.innerHTML = 'Error';
                }
            },
            progress: function(e) {
                if (e.lengthComputable) {
                    let percent = Math.round(e.loaded / e.total * 100) + '%';
                    loader.counter.innerHTML = percent;
                    loader.progress.style.width = percent;
                }
            },
            uploadResolve: null,
            errorIgnore: true
        });
    }

    function setItemData(file, data, empty = false, callback = null) {
        if (typeof file === 'string') {
            file = document.querySelector(file);
        }
        if (!(file instanceof HTMLElement)) {
            return false;
        }

        file.href = '{{ $adminPath }}/filestorage/{{ $filestorage->id }}/files/' + data.id + '/edit';
        //file.dataset.slug = data.slug;
        file.dataset.id = data.id;
        file.style.order = data.position;
        file.title = '__("elfcms::default.edit") ' + data.name;
        const img = file.querySelector('img');
        if (img) {
            /* if (data.thumbnail) {
                img.src = data.thumbnail;
            }
            else if (data.preview) {
                img.src = data.preview;
            }
            else {
                img.src = data.image;
            } */
            console.log(1, data);
            img.src = data.public_path;;
        }
        const h5 = file.querySelector('h5');
        if (h5) {
            h5.innerHTML = data.name;
        }
        if (empty) {
            const deleteBox = document.createElement('div');
            deleteBox.classList.add('delete-file-box');
            deleteBox.title = '{{ __('elfcms::default.delete') }}'
            const deleteInp = document.createElement('input');
            deleteInp.type = 'checkbox';
            deleteInp.name = `file[${data.id}][delete]`;
            deleteInp.id = `file_${data.id}_delete`;
            deleteInp.dataset.field = 'delete';
            deleteInp.addEventListener('click', function(e) {
                e.stopPropagation();
                submitItems.disabled = false;
                if (this.checked) {
                    file.classList.add('fordelete')
                } else {
                    file.classList.remove('fordelete')
                }
            });
            deleteBox.append(deleteInp);
            deleteBox.insertAdjacentHTML('beforeend', '<i></i>');
            file.append(deleteBox);
            file.addEventListener('click', function(e) {
                e.preventDefault();
                editItem(file.href, file);
            });
            file.insertAdjacentHTML('beforeend',
                `<input type="hidden" name="file[${data.id}][position]" value="${data.position}" data-field="position">`
            );
        } else {
            const deleteInp = file.querySelector('.delete-file-box input[type="checkbox"]');
            deleteInp.type = 'checkbox';
            deleteInp.name = `file[${data.id}][delete]`;
            deleteInp.id = `file_${data.id}_delete`;
        }
        if (callback && typeof callback === 'function') {
            callback();
        }
    }

    function createItem(data, empty = false) {
        const newItem = document.createElement('a');
        newItem.href = '{{ $adminPath }}/filestorage/{{ $filestorage->id }}/files/' + data.id + '/edit';
        newItem.classList.add('filestorage-file-tile', 'filestorage-file-element');
        //newItem.dataset.slug = data.slug;
        newItem.dataset.id = data.id;
        newItem.style.order = data.position;
        newItem.title = '__("elfcms::default.edit") ' + data.name;
        newItem.draggable = true;
        const img = document.createElement('img');
        if (img) {
            /* if (data.thumbnail) {
                img.src = data.thumbnail;
            }
            else if (data.preview) {
                img.src = data.preview;
            }
            else {
                img.src = data.image;
            } */
            console.log(2, data);
            img.src = '{{ $adminPath }}/helper/file-icon/' + data.type;
            newItem.append(img)
        }
        const h5 = document.createElement('h5');
        if (h5) {
            h5.innerHTML = data.name;
            newItem.append(h5)
        }
        if (!empty) {
            const deleteBox = document.createElement('div');
            deleteBox.classList.add('delete-file-box');
            deleteBox.title = '{{ __('elfcms::default.delete') }}'
            const deleteInp = document.createElement('input');
            deleteInp.type = 'checkbox';
            deleteInp.name = `file[${data.id}][delete]`;
            deleteInp.id = `file_${data.id}_delete`;
            deleteInp.dataset.field = 'delete';
            deleteInp.addEventListener('click', function(e) {
                e.stopPropagation();
                submitItems.disabled = false;
                if (this.checked) {
                    newItem.classList.add('fordelete')
                } else {
                    newItem.classList.remove('fordelete')
                }
            });
            deleteBox.append(deleteInp);
            deleteBox.insertAdjacentHTML('beforeend', '<i></i>');
            newItem.append(deleteBox);
            newItem.addEventListener('click', function(e) {
                e.preventDefault();
                editItem(newItem.href, newItem);
            });
            newItem.insertAdjacentHTML('beforeend',
                `<input type="hidden" name="file[${data.id}][position]" value="${data.position}" data-field="position">`
            );
        }

        return newItem;
    }

    function dndInit(element) {

        let isMoved = false;

        if (typeof element === 'string') {
            element = document.querySelector(element);
        }
        if (!element) {
            return false;
        }
        element.addEventListener('dragstart', function(e) {
            e.target.classList.add('dragged');
        });
        element.addEventListener('dragleave', function(e) {
            element.classList.remove('filedrag');
        });

        element.addEventListener('drop', function(e) {
            e.preventDefault();
            element.classList.remove('filedrag');
            if (e.target === element && e.dataTransfer.files && e.dataTransfer.files.length) {
                filesUpload(e.dataTransfer.files);
            }
        });

        element.addEventListener('dragend', function(e) {
            e.preventDefault();
            element.classList.remove('filedrag');
            e.target.classList.remove('dragged');
            const files = element.querySelectorAll('.filestorage-file-element');
            if (files && isMoved) {
                submitItems.disabled = false;
                let i = 1;
                files.forEach(file => {
                    file.style.order = i;
                    let positionInput = file.querySelector('input[data-field="position"]');
                    if (positionInput) {
                        positionInput.value = i;
                    }
                    i++;
                });
            }
        });

        const getNextElement = (cursorPosition, currentElement) => {
            const currentElementCoord = currentElement.getBoundingClientRect();
            const currentElementCenter = currentElementCoord.x + currentElementCoord.height / 2;

            const nextElement = (cursorPosition < currentElementCenter) ?
                currentElement :
                currentElement.nextElementSibling;

            return nextElement;
        };

        element.addEventListener('dragover', function(e) {
            e.preventDefault();

            if (e.dataTransfer.types && e.dataTransfer.types.includes('Files')) {
                element.classList.add('filedrag');
            } else {
                element.classList.remove('filedrag');
            }

            const activeElement = element.querySelector('.dragged');

            if (!activeElement) {
                return false;
            }

            const currentElement = e.target;
            let isDraggable = activeElement !== currentElement &&
                currentElement.classList.contains('filestorage-file-element');

            if (!isDraggable) {
                return false;
            }

            let insertAfter = false;

            const currentElementCoord = currentElement.getBoundingClientRect();
            const currentElementCenter = currentElementCoord.x + currentElementCoord.height / 2;

            if (e.clientX > currentElementCenter) {
                insertAfter = true;
            }

            const nextElement = getNextElement(e.clientX, currentElement);

            if (
                nextElement &&
                activeElement === nextElement.previousElementSibling ||
                activeElement === nextElement
            ) {
                return false;
            }

            isMoved = true;

            if (insertAfter) {
                if (currentElement.nextElementSibling) {
                    activeElement.style.order = currentElement.nextElementSibling.style.order;
                    element.insertBefore(activeElement, currentElement.nextElementSibling);
                } else {
                    activeElement.style.order = Number.parseInt(currentElement.style.order) + 1
                    element.append(activeElement);
                }
            } else {
                activeElement.style.order = currentElement.style.order
                element.insertBefore(activeElement, currentElement);
            }

        });
    }

    function editItem(action, currentItem, isEdit = true) {
        const createBoxWrapper = document.createElement('div');
        createBoxWrapper.classList.add('filestorage-file-create-popup-wrapper');
        const createBox = document.createElement('div');
        createBox.classList.add('filestorage-file-create-popup-box');
        createBoxWrapper.append(createBox);
        const closeBox = document.createElement('a');
        closeBox.classList.add('filestorage-file-create-popup-close');
        closeBox.title = '{{ __('elfcms::default.cancel') }}';
        closeBox.addEventListener('click', function(e) {
            e.preventDefault();
            createBoxWrapper.innerHTML = '';
            createBoxWrapper.remove();
        });
        createBox.append(closeBox);
        document.body.append(createBoxWrapper);
        fetch(action, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            credentials: 'same-origin',
        }).then(
            (result) => result.text()
        ).then(
            (data) => {
                if (data) {
                    createBox.insertAdjacentHTML('afterbegin', data);
                    const createForm = createBox.querySelector('form');
                    if (createForm) {
                        const fileInputs = createForm.querySelectorAll('.inputfile');
                        fileInputs.forEach((fileInput) => {
                            ajaxInputfileInit(fileInput);
                        });
                        /* const previewInput = document.querySelector('#file')
                        if (previewInput) {
                            inputFileExt(previewInput)
                        } */
                        /* const imageInput = document.querySelector('#image')
                        if (imageInput) {
                            inputFileImg(imageInput)
                        }
                        const thumbnailInput = document.querySelector('#thumbnail')
                        if (thumbnailInput) {
                            inputFileImg(thumbnailInput)
                        } */
                        //autoSlug('.autoslug')

                        //add editor
                        runEditor('#description')
                        //runEditor('#additional_text')

                        const submitButton = createForm.querySelector('[type="submit"]');
                        const newSubmitButton = submitButton.cloneNode(true);
                        const submitButtonBox = submitButton.parentNode;
                        submitButtonBox.append(newSubmitButton);
                        submitButton.remove();
                        const infoMessageBox = document.createElement('div');
                        submitButtonBox.append(infoMessageBox);

                        function formSubmit() {
                            if (infoMessageBox) {
                                infoMessageBox.innerHTML = '';
                            }
                            const formData = new FormData(createForm);
                            fetch(createForm.action, {
                                method: 'POST',
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                },
                                credentials: 'same-origin',
                                body: formData
                            }).then(
                                (result) => result.json()
                            ).then(
                                (data) => {
                                    if (data.result && data.result == 'success' && data.data) {
                                        const filesBox = document.querySelector('.filestorage-files-content');
                                        if (isEdit) {
                                            //currentItem.dataset.slug = data.data.slug;
                                            currentItem.style.order = data.data.position;
                                            currentItem.title = '__("elfcms::default.edit") ' + data.data.name;
                                            const h5 = currentItem.querySelector('h5');
                                            if (h5) {
                                                h5.innerHTML = data.data.name;
                                            }
                                            const img = currentItem.querySelector('img');
                                            if (img) {
                                                /* if (data.data.thumbnail) {
                                                    img.src = data.data.thumbnail;
                                                }
                                                else if (data.data.preview) {
                                                    img.src = data.data.preview;
                                                }
                                                else {
                                                    img.src = data.data.image;
                                                } */
                                                img.src = data.data.public_path;
                                            }
                                            currentItem.href =
                                                '{{ $adminPath }}/filestorage/{{ $filestorage->id }}/files/' +
                                                data.data.id + '/edit';
                                        } else if (filesBox) {
                                            const newItem = document.createElement('a');
                                            newItem.href =
                                                '{{ $adminPath }}/filestorage/{{ $filestorage->id }}/files/' +
                                                data.data.id + '/edit';
                                            newItem.classList.add('filestorage-file-tile',
                                                'filestorage-file-element');
                                            //newItem.dataset.slug = data.data.slug;
                                            newItem.dataset.id = data.data.id;
                                            newItem.style.order = data.data.position;
                                            newItem.title = '__("elfcms::default.edit") ' + data.data.name;
                                            newItem.draggable = true;
                                            const img = document.createElement('img');
                                            if (img) {
                                                console.log(4, data);
                                                img.src = data.data.public_path;
                                                newItem.append(img)
                                            }
                                            const h5 = document.createElement('h5');
                                            if (h5) {
                                                h5.innerHTML = data.data.name;
                                                newItem.append(h5)
                                            }
                                            const deleteBox = document.createElement('div');
                                            deleteBox.classList.add('delete-file-box');
                                            deleteBox.title = '{{ __('elfcms::default.delete') }}'
                                            const deleteInp = document.createElement('input');
                                            deleteInp.type = 'checkbox';
                                            deleteInp.name = `file[${data.data.id}][delete]`;
                                            deleteInp.id = `file_${data.data.id}_delete`;
                                            deleteInp.dataset.field = 'delete';
                                            deleteInp.addEventListener('click', function(e) {
                                                e.stopPropagation();
                                                submitItems.disabled = false;
                                                if (this.checked) {
                                                    newItem.classList.add('fordelete')
                                                } else {
                                                    newItem.classList.remove('fordelete')
                                                }
                                            });
                                            deleteBox.append(deleteInp);
                                            deleteBox.insertAdjacentHTML('beforeend', '<i></i>');
                                            newItem.append(deleteBox);
                                            newItem.addEventListener('click', function(e) {
                                                e.preventDefault();
                                                editItem(newItem.href, newItem);
                                            });
                                            newItem.insertAdjacentHTML('beforeend',
                                                `<input type="hidden" name="file[${data.data.id}][position]" value="${data.data.position}" data-field="position">`
                                            );

                                            filesBox.append(newItem);
                                        }
                                        createBoxWrapper.remove();
                                    } else {
                                        if ((data.errors || data.result == 'error') && data.message) {
                                            let errorString = '<div class="alert alert-danger">' + data
                                                .message + '</div>';
                                            infoMessageBox.insertAdjacentHTML('beforeend', errorString);
                                        }
                                    }
                                }
                            ).catch(error => {
                                //
                            });
                        }
                        setTimeout(function() {
                            createForm.addEventListener('submit', function(e) {
                                e.preventDefault();
                                formSubmit();
                            });
                            if (newSubmitButton) {
                                newSubmitButton.addEventListener('click', function(e) {
                                    e.preventDefault();
                                    formSubmit();
                                });
                            }
                            //filestorageTagFormInit();
                        }, 500)
                    }
                }
            }
        ).catch(error => {
            //
        });
    }
    // Edit file
    if (editItemElements) {
        editItemElements.forEach(editElement => {
            editElement.draggable = true;
            editElement.addEventListener('click', function(e) {
                e.preventDefault();
                editItem(this.href, this);
            });
            const deleteInput = editElement.querySelector('input[data-field="delete"]');
            if (deleteInput) {
                deleteInput.addEventListener('click', function(e) {
                    e.stopPropagation();
                    submitItems.disabled = false;
                    if (this.checked) {
                        editElement.classList.add('fordelete')
                    } else {
                        editElement.classList.remove('fordelete')
                    }
                });
            }
        });
    }
    // Create file
    if (createButton) {
        createButton.addEventListener('click', function(e) {
            e.preventDefault();
            editItem(this.href, this, false);
        });
    }

    dndInit(dragndropBox);

    function ajaxInputfileInit(inputfileBox) {
        let box = null;
        if (typeof inputfileBox == 'string') {
            box = document.querySelector(inputfileBox);
        } else {
            box = inputfileBox;
        }
        if (!box || !(box instanceof HTMLElement)) {
            return;
        }
        const inputfile = box.querySelector('input[type="file"]');
        const inputfileDelete = box.querySelector('.inputfile-delete');
        const inputfileIcon = box.querySelector('.inputfile-icon');
        const inputfileTitle = box.querySelector('.inputfile-title');
        const inputfileButtons = box.querySelector('.inputfile-buttons');
        const inputfileHidden = box.querySelector('input[type="hidden"]');

        inputfileDelete.addEventListener('click', () => {
            inputfileHidden.value = null;
            inputfileButtons.classList.add('hidden');
            inputfileIcon.innerHTML = '';
            inputfileIcon.insertAdjacentHTML('beforeend', `{!! iconHtmlLocal('/elfcms/admin/images/icons/upload.svg', svg: true) !!}`);
            inputfileTitle.textContent = `{{ __('elfcms::default.choose_file') }}`;
        });

        inputfile.addEventListener('change', function(e) {
            const files = e.target.files
            if (files) {
                if (inputfileTitle) {
                    inputfileTitle.textContent = files[0].name
                }
                if (FileReader && files && files.length) {
                    var fReader = new FileReader();
                    fReader.onload = function() {
                        if (inputfileIcon) {
                            let type = files[0].type.split('/')[1];
                            if (['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'svg+xml', 'webp', 'ico',
                                    'vnd.microsoft.icon'
                                ].includes(
                                    type)) {
                                const img = document.createElement('img')
                                img.src = fReader.result;
                                inputfileIcon.innerHTML = '';
                                inputfileIcon.appendChild(img);
                            } else {
                                fetch(adminPath + '/helper/file-icon-data/' + (type ?? 'any'), {
                                        headers: {
                                            'X-Requested-With': 'XMLHttpRequest'
                                        }
                                    })
                                    .then((response) => {
                                        return response.text();
                                    })
                                    .then((data) => {
                                        inputfileIcon.innerHTML = '';
                                        inputfileIcon.insertAdjacentHTML('beforeend', data);
                                    });
                            }
                            inputfileHidden.value = null;
                            inputfileButtons.classList.remove('hidden');
                        }
                    };
                    fReader.readAsDataURL(files[0]);
                }
            }
        })


    }
</script>
