function popup(params = {}) {
    if (params.reload == undefined) {
        params.reload = true;
    }
    if (params.remove == undefined) {
        params.remove = true;
    }
    if (params.class == undefined) {
        params.class = "";
    }
    if (params.id == undefined || typeof params.id != "string") {
        params.id = Date.now();
    } else {
        params.id = params.id.replace(/[\s.,%]/g, "");
    }
    if (params.title == undefined) {
        params.title = "";
    }
    if (params.content == undefined) {
        params.content = "";
    }
    if (params.buttons == undefined || typeof params.buttons != "object") {
        params.buttons = [];
    }

    let existWrapper = document.querySelectorAll(".popup-wrapper");

    if (existWrapper && params.reload) {
        existWrapper.forEach((wrapElem) => {
            wrapElem.remove();
        });
    }

    const wrapper = document.createElement("div");
    wrapper.className = "popup-wrapper";
    if (params.class != "") {
        wrapper.className += " " + params.class;
    }
    wrapper.id = "popup_" + params.id;
    wrapper.style.position = "fixed";
    wrapper.style.top = 0;
    wrapper.style.bottom = 0;
    wrapper.style.left = 0;
    wrapper.style.right = 0;
    wrapper.style.zIndex = 10000;

    const box = document.createElement("div");
    box.className = "popup-box";

    const container = document.createElement("div");
    container.className = "popup-content";
    container.insertAdjacentHTML("afterbegin", params.content);

    const header = document.createElement("div");
    header.className = "popup-header";

    if (params.title != "") {
        const title = document.createElement("div");
        title.className = "popup-title";
        title.insertAdjacentHTML("afterbegin", params.title);
        header.append(title);
    }

    const closeBut = document.createElement("div");
    closeBut.className = "popup-close";
    closeBut.addEventListener("click", close);

    const buttonBox = document.createElement("div");
    buttonBox.className = "popup-button-box";

    if (params.buttons.length > 0) {
        params.buttons.forEach((button) => {
            if (!button.title) {
                return;
            }
            let buttonElem = document.createElement("button");
            buttonElem.innerHTML = button.title;

            if (button.class !== undefined) {
                buttonElem.className = button.class;
            }
            if (button.callback !== undefined) {
                if (typeof button.callback == "object") {
                    button.callback.forEach((callback) => {
                        if (callback == "close") {
                            callback = close;
                        }
                        buttonElem.addEventListener("click", callback);
                    });
                } else {
                    if (button.callback == "close") {
                        button.callback = close;
                    }
                    buttonElem.addEventListener("click", button.callback);
                }
            }
            buttonBox.append(buttonElem);
        });
    }

    box.append(closeBut);
    box.append(header);
    box.append(container);
    box.append(buttonBox);
    wrapper.append(box);

    function close() {
        if (params.remove) {
            wrapper.remove();
        } else {
            wrapper.style.display = "none";
        }
    }

    document.body.append(wrapper);
}

function inputFile(box) {
    if (typeof box == "string") {
        box = document.querySelector(box);
    }
    if (!box || !(box instanceof HTMLElement)) {
        return false;
    }
    const fileInput = box.querySelector('input[type="file"]');
    if (!fileInput) {
        return false;
    }
    const tempName = box.querySelector(".file-temp-name");
    const valueInput = box.querySelector('input[type="hidden"]');
    const tempDelete = box.querySelector(".file-temp-delete");
    const wrapper = box.querySelector(".file-wrapper");

    fileInput.addEventListener("change", function (e) {
        const files = e.target.files;
        if (files) {
            if (tempName) {
                if (files[0] && files[0].name) {
                    tempName.innerHTML = files[0].name;
                } else {
                    tempName.innerHTML = "";
                }
            }
        }
    });

    if (tempDelete) {
        tempDelete.addEventListener("click", function () {
            tempName.innerHTML = "";
            fileInput.value = null;
        });
    }

    if (wrapper) {
        const fileDelete = wrapper.querySelector(".file-delete");

        if (fileDelete) {
            fileDelete.addEventListener("click", function () {
                wrapper.remove();
                if (valueInput) {
                    valueInput.value = null;
                }
            });
        }
    }
}

function inputFileImg(input) {
    if (!input) {
        console.log("err");
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
                if (text) {
                    text.innerHTML = files[0].name;
                }
                if (FileReader && files && files.length) {
                    var fReader = new FileReader();
                    fReader.onload = function () {
                        if (img) {
                            img.src = fReader.result;
                        }
                        const del = wrapper.querySelector(".delete-image");
                        if (del) {
                            del.classList.remove("hidden");
                        }
                    };
                    fReader.readAsDataURL(files[0]);
                }
            }
            //deleteImage(wrapper)
        });
    }
}

function inputFileExt(input) {
    if (!input) {
        console.log("err");
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
                                fetch('/admin/helper/file-icon/' + (files[0].type.split('/')[1] ?? 'any'),{headers: {'X-Requested-With': 'XMLHttpRequest'}})
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

function translitSmall(string) {
    const symbols = [
        { in: "а", out: "a" },
        { in: "б", out: "b" },
        { in: "в", out: "v" },
        { in: "г", out: "g" },
        { in: "д", out: "d" },
        { in: "е", out: "e" },
        { in: "ё", out: "yo" },
        { in: "ж", out: "zh" },
        { in: "з", out: "z" },
        { in: "и", out: "i" },
        { in: "й", out: "y" },
        { in: "к", out: "k" },
        { in: "л", out: "l" },
        { in: "м", out: "m" },
        { in: "н", out: "n" },
        { in: "о", out: "o" },
        { in: "п", out: "p" },
        { in: "р", out: "r" },
        { in: "с", out: "s" },
        { in: "т", out: "t" },
        { in: "у", out: "u" },
        { in: "ф", out: "f" },
        { in: "х", out: "h" },
        { in: "ц", out: "ts" },
        { in: "ч", out: "ch" },
        { in: "ш", out: "sh" },
        { in: "щ", out: "sch" },
        { in: "ъ", out: "" },
        { in: "ы", out: "y" },
        { in: "ь", out: "" },
        { in: "э", out: "e" },
        { in: "ю", out: "yu" },
        { in: "я", out: "ya" },
    ];

    symbols.forEach((symb) => {
        string = string.replace(new RegExp(symb.in, "gi"), symb.out);
    });

    return string;
}

function slug(text, space = "-", translit = true) {
    if (typeof text === "string" && text.length > 0) {
        text = text.trim().toLowerCase();

        if (translit === true) {
            text = translitSmall(text);
        }
        text = text
            .replace(/ä/g, "ae")
            .replace(/ö/g, "oe")
            .replace(/ü/g, "ue")
            .replace(/ß/g, "ss")
            .replace(/\u00e4/g, "ae")
            .replace(/\u00f6/g, "oe")
            .replace(/\u00fc/g, "ue")
            .replace(/\u00df/g, "ss")
            .replace(".", "_")
            .replace(/\s+/g, space)
            .replace(/[^\w\-]+/g, "")
            .replace(/\-\-+/g, "-")
            .replace(/^-+/, "")
            .replace(/-+$/, "");

        return text;
    }

    return null;
}

function autoSlug(checkbox, translit = true, space = "-") {
    if (checkbox) {
        if (typeof checkbox === "string") {
            checkbox = document.querySelectorAll(checkbox);
        }

        function slugAction(elem, space, translit) {
            let textElement = document.getElementById(elem.dataset.textId),
                slugElement = document.getElementById(elem.dataset.slugId);

            if (textElement && slugElement) {
                /* elem.addEventListener('click',function(e) {
                    if (this.checked) {
                        slugElement.value = slug(textElement.value, currentSpace, translit)
                    }
                }) */

                textElement.addEventListener("input", function (e) {
                    if (elem.checked) {
                        slugElement.value = slug(this.value, space, translit);
                    }
                });
            }
        }

        if (typeof checkbox === "object" && checkbox instanceof HTMLElement) {
            if (checkbox.dataset.slugSpace) {
                currentSpace = checkbox.dataset.slugSpace;
            } else {
                currentSpace = space;
            }
            slugAction(checkbox, currentSpace, translit);
        } else if (
            typeof checkbox === "object" &&
            checkbox instanceof NodeList
        ) {
            checkbox.forEach((element) => {
                if (element.dataset.slugSpace) {
                    currentSpace = element.dataset.slugSpace;
                } else {
                    currentSpace = space;
                }
                slugAction(element, currentSpace, translit);
            });
        }
    }
}

function inputSlugInit(space = "-", translit = true) {
    const inputs = document.querySelectorAll("input[data-isslug]");
    if (inputs) {
        inputs.forEach((elem) => {
            elem.addEventListener("input", function () {
                this.value = slug(this.value, space, translit);
            });
        });
    }
}

function hashTag(string = "", hash = true) {
    string = string
        .trim()
        .toLowerCase()
        .replace(/[^a-zA-Zа-яА-Я0-9]/g, "");
    if (hash) {
        string = "#" + string;
    }
    return string;
}

function tagInput(input, hash = false) {
    if (typeof input === "string") {
        input = document.querySelectorAll(input);
    }

    function hashAction(elem) {
        elem.addEventListener("input", function () {
            this.value = hashTag(this.value, hash);
        });
    }

    if (typeof input === "object" && input instanceof HTMLElement) {
        hashAction(input);
    } else if (typeof input === "object" && input instanceof NodeList) {
        input.forEach((element) => {
            hashAction(element);
        });
    }
}

function removeTagFromList(th) {
    const box = th.closest(".tag-item-box");
    if (box) box.remove();
}

function tagFormInit() {
    const tagForm = document.querySelectorAll(".tag-form-wrapper");
    let tagList = null;

    async function getTagList() {
        if (tagList !== null && typeof tagList == "object") {
            return tagList;
        }
        let response = await fetch("/admin/blog/tags", {
            headers: { "X-Requested-With": "XMLHttpRequest" },
        });
        tagList = await response.json();
        return tagList;
    }
    getTagList();

    function addTagToList(listBox, input, item) {
        const check = document.querySelector(
            '.tag-item-box[data-id="' + item.id + '"]'
        );
        if (!check) {
            const elem = `<div class="tag-item-box" data-id="${item.id}">
                <span class="tag-item-name">${item.name}</span>
                <span class="tag-item-remove" onclick="removeTagFromList(this)">&#215;</span>
                <input type="hidden" name="tags[]" value="${item.id}">
            </div>`;
            listBox.insertAdjacentHTML("beforeend", elem);
        }
    }

    if (tagForm) {
        tagForm.forEach((wrapBox) => {
            let listBox = wrapBox.querySelector(".tag-list-box");
            let promptBox = wrapBox.querySelector(".tag-prompt-list");
            let inputBox = wrapBox.querySelector(".tag-input-box");
            let input = wrapBox.querySelector("input.tag-input");
            let button = wrapBox.querySelector("button.tag-add-button");
            input.addEventListener("input", function () {
                const th = this;
                const list = tagList.filter((item) => {
                    if (item.name.indexOf(th.value) > -1 && th.value != "") {
                        return item;
                    }
                });
                promptBox.innerHTML = "";
                list.forEach((item) => {
                    let prompt = document.createElement("div");
                    prompt.classList.add("tag-prompt-item");
                    prompt.dataset.id = item.id;
                    prompt.innerHTML = item.name;
                    prompt.addEventListener("click", function () {
                        addTagToList(listBox, input, item);
                    });
                    promptBox.append(prompt);
                });
            });
            button.addEventListener("click", function () {
                const data = JSON.stringify({ name: input.value });
                const token = document.querySelector(
                    "input[name='_token']"
                ).value;
                fetch("/admin/blog/tags/addnotexist", {
                    method: "POST",
                    headers: {
                        Accept: "application/json",
                        "Content-Type": "application/json",
                        "X-Requested-With": "XMLHttpRequest",
                        "X-CSRF-TOKEN": token,
                    },
                    credentials: "same-origin",
                    body: data,
                })
                    .then((result) => result.json())
                    .then((data) => {
                        if (data.result && data.data) {
                            if (data.result == "success" && data.data.id) {
                                tagList.push(data.data);
                            }
                            if (data.data.id) {
                                addTagToList(listBox, input, data.data);
                            }
                        }
                    })
                    .catch((error) => {
                        //
                    });
            });
        });
    }
}

function fieldGroupInit() {
    const formSelect = document.querySelector("#form_id");
    const groupSelect = document.querySelector("#group_id");

    if (formSelect) {
        formSelect.addEventListener("change", function () {
            let val = this.value;
            const token = document.querySelector("input[name='_token']").value;
            fetch("/admin/form/groups?form_id=" + val, {
                method: "GET",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": token,
                },
                credentials: "same-origin",
            })
                .then((result) => result.json())
                .then((data) => {
                    let optionText = `
                        <option value="null">${elfLang.none}</option>
                    `;
                    data.forEach((elem) => {
                        optionText += `
                        <option value="${elem.id}">${elem.name}</option>
                        `;
                    });
                    groupSelect.innerHTML = optionText;
                })
                .catch((error) => {
                    //
                });
        });
    }
}

let elfLang = null;
async function getElfLang() {
    if (elfLang !== null && typeof elfLang == "object") {
        return elfLang;
    }
    let elfLangResponse = await fetch("/admin/ajax/json/lang/elf", {
        headers: { "X-Requested-With": "XMLHttpRequest" },
    });
    elfLang = await elfLangResponse.json();
    return elfLang;
}
getElfLang();

function showOptionsSelect(select, optionBox, hiddenClass = "hidden") {
    if (typeof select === "string") {
        select = document.querySelector(select);
    }

    function selectInit(select, optionBox, hiddenClass = "hidden") {
        function setVisible(optionBox, hiddenClass, visible = false) {
            if (typeof optionBox === "string") {
                optionBox = document.querySelector(optionBox);
            }
            if (optionBox) {
                if (visible === true) {
                    optionBox.classList.remove(hiddenClass);
                } else {
                    if (!optionBox.classList.contains(hiddenClass)) {
                        optionBox.classList.add(hiddenClass);
                    }
                }
            }
        }

        if (select.selectedIndex) {
            if (
                select.options[select.selectedIndex].text == "select" ||
                select.options[select.selectedIndex].text == "radio"
            ) {
                setVisible(optionBox, hiddenClass, true);
            } else {
                setVisible(optionBox, hiddenClass, false);
            }
        }
    }

    if (typeof select === "object" && select instanceof HTMLElement) {
        selectInit(select, optionBox, hiddenClass);
        select.addEventListener("change", function () {
            selectInit(this, optionBox, hiddenClass);
        });
    }
}

let optionNextLine = 0;

function optionBoxInit(addSelector = "#addoptionline", line = 0) {
    optionNextLine = line;

    const addButton = document.querySelector(addSelector);
    if (addButton) {
        addButton.addEventListener("click", function () {
            const lastLine = document.querySelector(
                '.options-table-string-line[data-line="' + optionNextLine + '"]'
            );
            optionNextLine++;
            const htmlLine = `
            <div class="options-table-string-line" data-line="${optionNextLine}">
                <div class="options-table-string">
                    <input type="text" name="options_new[${optionNextLine}][value]" id="option_new_value_${optionNextLine}" data-option-value>
                </div>
                <div class="options-table-string">
                    <input type="text" name="options_new[${optionNextLine}][text]" id="option_new_text_${optionNextLine}" data-option-text>
                </div>
                <div class="options-table-string">
                    <input type="checkbox" name="options_new[${optionNextLine}][selected]" id="option_new_selected_${optionNextLine}" data-option-selected>
                </div>
                <div class="options-table-string">
                    <input type="checkbox" name="options_new[${optionNextLine}][disabled]" id="option_new_disabled_${optionNextLine}" data-option-disabled>
                </div>
                <div class="options-table-string">
                    <input type="checkbox" name="options_new[${optionNextLine}][deleted]" id="option_new_deleted_${optionNextLine}" data-option-deleted>
                </div>
                <div class="options-table-string"></div>
            </div>
            `;
            if (lastLine) {
                lastLine.insertAdjacentHTML("afterend", htmlLine);
                setTimeout(function () {
                    onlyOneCheckedInit(
                        "#option_new_selected_" + optionNextLine,
                        "[data-option-selected]"
                    );
                }, 1000);
            }
        });
    }
}

function eventParamBoxInit(addSelector = "#addparamline", line = 0) {
    paramNextLine = line;

    const addButton = document.querySelector(addSelector);
    if (addButton) {
        addButton.addEventListener("click", function () {
            const lastLine = document.querySelector(
                '.params-table-string-line[data-line="' + paramNextLine + '"]'
            );
            paramNextLine++;
            const htmlLine = `
            <div class="params-table-string-line" data-line="${paramNextLine}">
                <div class="params-table-string">
                    <input type="text" name="params_new[${paramNextLine}][name]" id="param_new_name_${paramNextLine}" data-param-name>
                </div>
                <div class="params-table-string">
                    <input type="text" name="params_new[${paramNextLine}][value]" id="param_new_value_${paramNextLine}" data-param-value>
                </div>
                <div class="params-table-string">
                    <button type="button" class="default-btn" onclick="eventParamDelete(${paramNextLine})">&#215;</button>
                </div>
            </div>
            `;

            if (lastLine) {
                lastLine.insertAdjacentHTML("afterend", htmlLine);
            }
        });
    }
}

function eventParamDelete(line) {
    const lineBox = document.querySelector(
        '.params-table-string-line[data-line="' + line + '"]'
    );
    if (lineBox) {
        lineBox.remove();
    }
}

function menuAttrBoxInit(addSelector = "#addattributeline", line = 0) {
    attributeNextLine = line;

    const addButton = document.querySelector(addSelector);
    if (addButton) {
        addButton.addEventListener("click", function () {
            const lastLine = document.querySelector(
                '.attributes-table-string-line[data-line="' +
                    attributeNextLine +
                    '"]'
            );
            attributeNextLine++;
            const htmlLine = `
            <div class="attributes-table-string-line" data-line="${attributeNextLine}">
                <div class="attributes-table-string">
                    <input type="text" name="attributes_new[${attributeNextLine}][name]" id="attribute_new_name_${attributeNextLine}" data-attribute-name>
                </div>
                <div class="attributes-table-string">
                    <input type="text" name="attributes_new[${attributeNextLine}][value]" id="attribute_new_value_${attributeNextLine}" data-attribute-value>
                </div>
                <div class="attributes-table-string">
                    <button type="button" class="default-btn" onclick="menuAttrDelete(${attributeNextLine})">&#215;</button>
                </div>
            </div>
            `;

            if (lastLine) {
                lastLine.insertAdjacentHTML("afterend", htmlLine);
            }
        });
    }
}

function menuAttrDelete(line) {
    const lineBox = document.querySelector(
        '.attributes-table-string-line[data-line="' + line + '"]'
    );
    if (lineBox) {
        lineBox.remove();
    }
}

function selectFilter(condition, target, parameter, defaultValue = null) {
    if (parameter) {
        if (typeof condition === "string") {
            condition = document.querySelector(condition);
        }
        if (typeof condition === "object" && condition instanceof HTMLElement) {
            if (typeof target === "string") {
                target = document.querySelector(target);
            }
            if (typeof target === "object" && target instanceof HTMLElement) {
                optionList = target.querySelectorAll("option");

                document.addEventListener("change", function (e) {
                    let count = 0;
                    let defaultIndex = 0;
                    optionList.forEach((element, index) => {
                        optionParam = element.getAttribute(parameter);
                        if (optionParam == condition.value) {
                            element.style.display = "initial";
                            count++;
                        }
                        if (optionParam == defaultValue) {
                            element.style.display = "initial";
                            defaultIndex = index;
                            count++;
                        } else {
                            element.style.display = "none";
                        }
                    });
                    if (count == 0) {
                        target.selectedIndex = defaultIndex;
                    }
                });
            } else {
                console.warn("Element not found");
            }
        } else {
            console.warn("Element not found");
        }
    } else {
        console.warn("Parameter not found");
    }
}

function oneCheked(element, list) {
    if (typeof element === "string") {
        element = document.querySelector(element);
    }
    if (typeof element === "object" && element instanceof HTMLElement) {
        if (typeof list === "string") {
            list = document.querySelectorAll(list);
        }
        if (typeof list === "object" && list instanceof NodeList) {
            //console.log(element);
        }
    }

    return false;
    /* if (typeof element === 'object' && element instanceof HTMLElement) {

    }
    else if (typeof input === 'object' && input instanceof NodeList) {

        input.forEach(element => {
            hashAction(element)
        });

    } */
}

function oneCheckedInit(selector, reinit = false) {
    elements = document.querySelectorAll(selector);
    if (elements) {
        elements.forEach((element) => {
            if (reinit) {
                element.removeEventListener("click", oneCheked);
            }
            element.addEventListener("click", oneCheked(element, elements));
        });
    }
}

function onlyOneCheckedInit(elements, listSelector) {
    if (typeof elements === "string") {
        elements = document.querySelectorAll(elements);
    }
    if (typeof elements === "object" && elements instanceof NodeList) {
        /* if (typeof list === 'string') {
            list = document.querySelectorAll(list)
        } */
        //if (typeof list === 'object' && list instanceof NodeList) {
        /* elements.addEventListener('change',function () {
                console.log(this)
                //console.log(this.checked)
            }) */
        elements.forEach((element) => {
            element.addEventListener("change", function () {
                if (this.checked) {
                    list = document.querySelectorAll(listSelector);
                    if (typeof list === "object" && list instanceof NodeList) {
                        list.forEach((item) => {
                            if (item !== element) {
                                item.checked = false;
                            }
                        });
                    }
                }
            });
        });
        //}
    }
}

function deleteConfirm(e) {
    e.preventDefault();
    let roleId = this.querySelector('[name="id"]').value,
        roleName = this.querySelector('[name="name"]').value,
        self = this;
    popup({
        title: elfLang.deleting_of_element + roleId,
        content:
            "<p>" +
            elfLang.are_you_sure_to_deleting_role +
            '"' +
            roleName +
            '"' +
            "(ID " +
            roleId +
            ")?</p>",
        buttons: [
            {
                title: elfLang.delete,
                class: "default-btn delete-button",
                callback: function () {
                    self.submit();
                },
            },
            {
                title: elfLang.cancel,
                class: "default-btn cancel-button",
                callback: "close",
            },
        ],
        class: "danger",
    });
}

function contextPopup(content, position = {}) {
    if (window.isContextPopup) {
        return false;
    }
    window.isContextPopup = true;
    let top = position.top ?? null;
    let left = position.left ?? null;
    let bottom = position.bottom ?? null;
    let right = position.right ?? null;

    const boxBg = document.createElement("div");
    boxBg.classList.add("context-popup-background");
    const box = document.createElement("div");
    box.classList.add("context-popup");
    const closeBox = document.createElement("div");
    closeBox.classList.add("context-popup-close");
    const close = document.createElement("span");
    close.innerHTML = "&#215;";
    const contentBox = document.createElement("div");
    contentBox.classList.add("context-popup-content");
    closeBox.append(close);
    box.append(closeBox);
    box.append(contentBox);
    if (typeof content === "string") {
        contentBox.insertAdjacentHTML("beforeend", content);
    } else {
        contentBox.append(content);
    }
    close.addEventListener("click", function () {
        thisClose();
    });
    boxBg.addEventListener("contextmenu", function (e) {
        e.preventDefault();
    });
    boxBg.addEventListener("click", function (e) {
        thisClose();
    });
    box.addEventListener("contextmenu", function (e) {
        e.preventDefault();
    });
    document.body.append(boxBg);
    document.body.append(box);
    document.body.style.overflowY = "hidden";

    if (top) {
        if (top + box.offsetHeight > window.innerHeight) {
            top = top - box.offsetHeight;
        }
        box.style.top = top + "px";
    }
    if (left) {
        if (left + box.offsetWidth > window.innerWidth) {
            left = left - box.offsetWidth;
        }
        box.style.left = left + "px";
    }
    if (bottom) {
        box.style.bottom = bottom + "px";
    }
    if (right) {
        box.style.right = right + "px";
    }

    function thisClose() {
        boxBg.remove();
        box.remove();
        document.body.style.overflowY = "";
        window.isContextPopup = false;
    }
}

function checkInactive() {
    const elems = document.querySelectorAll("input[data-inactive]");
    if (elems) {
        elems.forEach((elem) => {
            let field = document.querySelector(
                'input[name="' + elem.dataset.inactive + '"]'
            );
            if (elem.checked) {
                field.setAttribute("readonly", "readonly");
                field.classList.add("inactive");
            } else {
                field.removeAttribute("readonly");
                field.classList.remove("inactive");
            }
            elem.addEventListener("change", function (e) {
                if (elem.checked) {
                    field.setAttribute("readonly", "readonly");
                    field.classList.add("inactive");
                } else {
                    field.removeAttribute("readonly");
                    field.classList.remove("inactive");
                }
            });
        });
    }
}

function runEditor(element) {
    if (typeof element === "string") {
        element = document.querySelector(element);
    }
    let result = false;
    if (element) {
        const form = element.closest("form");
        //const submitButton = form.querySelector('[type="submit"]')
        const submitButtons = form.querySelectorAll('[type="submit"]');
        if (form && submitButtons) {
            /* form.addEventListener('submit',function(e){
                e.preventDefault()
            }) */
            /* submitButton.addEventListener('click',function(){
                form.submit()
            }) */
            /* submitButtons.forEach(submitButton => {
                submitButton.addEventListener('click',function(){
                    form.submit();
                })
            }); */
        }
        if (typeof Gnommy != "undefined") {
            result = new Gnommy(element);
        }
    }
    return result;
}

function eajax(url, params = {}) {
    if (!params.method && typeof params.method !== "sting") {
        params.method = "GET";
    }
    params.method = params.method.toUpperCase();
    if (params.method != "GET" && params.method != "POST") {
        params.method = "GET";
    }
    if (!params.resolve) {
        params.resolve = (result) => {
            return result;
        };
    }

    let promise = new Promise(function (resolve, reject) {
        let request = new XMLHttpRequest();
        request.open(params.method, url);
        if (params.headers && typeof params.headers === "object") {
            Object.entries(params.headers).forEach((entry) => {
                const [key, value] = entry;
                if (key && value) {
                    request.setRequestHeader(key, value);
                }
            });
        }

        if (params.progress && typeof params.progress === "function") {
            request.upload.onprogress = function (event) {
                params.progress(event);
            };
        }

        if (
            params.uploadResolve &&
            typeof params.uploadResolve === "function"
        ) {
            request.upload.onload = function () {
                params.uploadResolve(request);
            };
        }

        if (params.progress && typeof params.progress === "function") {
            request.onprogress = function (event) {
                params.progress(event);
            };
        }

        request.onload = function () {
            if (request.status == 200) {
                resolve(request);
            } else if (params.errorIgnore) {
                resolve(request);
            } else {
                reject(Error(request.statusText));
            }
        };

        request.onerror = function () {
            reject(Error("Network Error"));
        };

        if (params.method == "POST" && params.formData) {
            request.send(params.formData);
        } else {
            request.send();
        }
    });

    if (params.resolve && typeof params.resolve === "function") {
        promise.then(params.resolve);
    }
}

function tabsInit(selector = ".tab-title", boxSelector = ".tab-box") {
    const head = document.querySelectorAll(selector);
    const boxes = document.querySelectorAll(boxSelector);

    if (head && boxes) {
        head.forEach((title) => {
            title.addEventListener("click", function () {
                if (this.classList.contains("selected")) {
                    return false;
                }
                let seletedTitle = document.querySelector(
                    selector + ".selected"
                );
                if (seletedTitle) {
                    seletedTitle.classList.remove("selected");
                }
                let boxId = this.dataset.tab;
                boxes.forEach((box) => {
                    box.classList.remove("showed");
                });
                let showedBox = document.getElementById(boxId);
                if (showedBox) {
                    showedBox.classList.add("showed");
                }
                this.classList.add("selected");
            });
        });
    }
}

function preloadSet(element) {
    if (typeof element === "string") {
        element = document.querySelector(element);
    }
    if (!(element instanceof HTMLElement) && element !== document) {
        return false;
    }
    const preloader = document.createElement("div");
    preloader.classList.add("preload-wrapper");
    preloader.insertAdjacentHTML(
        "beforeend",
        '<div class="preload-box"><div></div><div></div><div></div></div>'
    );
    element.append(preloader);

    return preloader;
}

function preloadUnset(preloader) {
    if (typeof preloader === "string") {
        preloader = document.querySelector(preloader);
    }
    if (!(preloader instanceof HTMLElement)) {
        return false;
    }
    preloader.remove();
}

/* function datetimeFormat (dateString, seconds = true, dateformat = 'Y-M-D') {
    let current = new Date();
    let tz = current.getTimezoneOffset() * 60000;
    let dateFromString = new Date(Date.parse(dateString))
    let year = dateFromString.getFullYear(),
        month = dateFromString.getMonth(),
        day = dateFromString.getDate(),
        hour = dateFromString.getHours(),
        min = dateFromString.getMinutes(),
        sec = dateFromString.getSeconds()
    if (month < 0) {
        month = '0' + month
    }
    if (day < 0) {
        day = '0' + day
    }
    if (hour < 0) {
        hour = '0' + hour
    }
    if (min < 0) {
        min = '0' + min
    }
    if (sec < 0) {
        sec = '0' + sec
    }
    let time = hour + ':' + min
    if (seconds) {
        time =  + ':' + sec
    }
        console.log(year,month,day,hour,min,sec)
        console.log(time)
    let resultString = year + '-' + month + '-' + day + ' ' + time
    if (dateformat = 'D.M.Y') {
        resultString = day + '.' + month + '.' + year + ' ' + time
    }
    return resultString
} */

const csrfInterval = setInterval(function () {
    const tokenMeta = document.querySelector('meta[name="csrf-token"]');
    const inputTokens = document.querySelectorAll('input[name="_token"]');
    if (tokenMeta || inputTokens) {
        fetch("/elfcms/api/csrf",{headers:{'X-Requested-With': 'XMLHttpRequest'}})
            .then((response) => {
                return response.json();
            })
            .then((data) => {
                if (data && data.token) {
                    if (tokenMeta) {
                        if (tokenMeta.content != data.token) tokenMeta.content = data.token;;
                    }
                    if (inputTokens) {
                        inputTokens.forEach((inputToken) => {
                            if (inputToken.value != data.token) inputToken.value = data.token;
                        });
                    }
                }
            });
    }
}, 3600000);

/* Dynamic table */
/*
function checkParamChange(th,props=false) {
    const row = th.closest('tr[data-id]');
    if (row) {
        let inputName = 'parameter';
        if (props) {
            inputName = 'property';
        }
        const id = row.dataset.id;
        const name = th.dataset.name;
        const editInput = row.querySelector('input[name="'+inputName+'['+id+'][edited]"]');
        const optionDeleteInput = row.querySelector('.shop-option-table input[type="checkbox"]:checked');
        if (id && name) {
            let value = th.value;
            if (th.type == 'checkbox') {
                if (th.checked) {
                    value = 1;
                }
                else {
                    value = 0;
                }
            }
            if (value === '') {
                value = null;
            }
            controlData[id][name] = value;
            if (!optionDeleteInput && objectCompare(controlData[id],unitListData.data[id]) && objectCompare(controlData[id]['options'],unitListData.data[id]['options'])) {
                row.classList.remove('edited');
                if (editInput) {
                    editInput.value="0";
                }
            }
            else {
                row.classList.add('edited');
                if (editInput) {
                    editInput.value="1";
                }
            }
        }
        setSaveEnabled();
    }
} */

function setDynamicSaveEnabled() {
    const saveButton = document.querySelector('.dynamic-table-buttons button[data-action="save"]');
    if (saveButton) {
        saveButton.disabled = false;
    }
}

function setDynamicUnitRowDelete(th) {
    const row = th.closest('tr[data-id]');
    if (row) {
        if (th.checked) {
            row.classList.add('deletable');
        }
        else {
            row.classList.remove('deletable');
        }
        setDynamicSaveEnabled();
    }
}

/* Dynamic table */

/* Filestorage */

function isFilestorageEditedUnits() {
    const editedRows = document.querySelectorAll('tr[data-id].edited');
    if (editedRows && editedRows.length) {
        return true;
    }
    return false;
}

function isFilestorageDeletableUnits() {
    const editedRows = document.querySelectorAll('tr[data-id].deletable');
    if (editedRows && editedRows.length) {
        return true;
    }
    return false;
}

function setFilestorageSaveEnabled() {
    const saveButton = document.querySelector('button[data-action="save"]');
    if (saveButton) {
        if (isFilestorageDeletableUnits() || isFilestorageEditedUnits()) {
            saveButton.disabled = false;
        }
        else {
            saveButton.disabled = true;
        }
    }
}

function addFilestorageGroupItem() {
    if (!emptyItem) return false;
    if (!newItemId && newItemId !== 0) return false;
    const container = document.querySelector('table.filestorage-group-table tbody');
    if (container) {
        let itemString = emptyItem.replaceAll('btn" data-id="newgroup"','btn" data-id="'+newItemId+'"').replaceAll('id="newgroup"','id="new_'+newItemId+'"').replaceAll('group[newgroup]','newgroup['+newItemId+']').replaceAll('group_newgroup','newgroup_'+newItemId).replaceAll('<span>newgroup</span>','');
        container.insertAdjacentHTML('beforeend',itemString);
        const newRow = container.lastElementChild;
        if (newRow) {
            newRow.classList.add('edited');
        }
        newItemId++;
        autoSlug(newRow.querySelectorAll('.autoslug'));
        setFilestorageSaveEnabled();
    }
}

function addFilestorageTypeItem() {
    if (!emptyItem) return false;
    if (!newItemId && newItemId !== 0) return false;
    const container = document.querySelector('table.filestorage-type-table tbody');
    if (container) {
        let itemString = emptyItem.replaceAll('btn" data-id="newtype"','btn" data-id="'+newItemId+'"').replaceAll('id="newtype"','id="new_'+newItemId+'"').replaceAll('type[newtype]','newtype['+newItemId+']').replaceAll('type_newtype','newtype_'+newItemId).replaceAll('<span>newtype</span>','');
        container.insertAdjacentHTML('beforeend',itemString);
        const newRow = container.lastElementChild;
        if (newRow) {
            newRow.classList.add('edited');
        }
        newItemId++;
        autoSlug(newRow.querySelectorAll('.autoslug'));
        setFilestorageSaveEnabled();
    }
}

function inputCheckValue(values, invert=false) {
    if (!values) return;
    const checkers = document.querySelectorAll('.input-checker[data-inpcheck]');
    if (checkers) {
        checkers.forEach(checker => {
            const name = checker.getAttribute('data-inpcheck');
            const input = document.querySelector(`input[name="${name}"]`);
            const listen = document.querySelector(`input[name="${checker.getAttribute('data-listen')}"]`);
            if (listen && input) {
            listen.addEventListener('input', function () {
                    if (!values[name] || typeof values[name] !== 'object' || !Array.isArray(values[name])) return;
                    let value = input.value.toLowerCase();
                    let incl = values[name].includes(value);
                    if (input.value.length == 0) {
                        checker.classList.remove('failed');
                        checker.classList.remove('checked');
                        checker.classList.add('none');
                    } else if (invert && !incl || !invert && incl) {
                        checker.classList.remove('none');
                        checker.classList.remove('checked');
                        checker.classList.add('failed');
                    } else {
                        checker.classList.remove('none');
                        checker.classList.remove('failed');
                        checker.classList.add('checked');
                    }
                })
            }
        })
    }
}

/*
function checkFilestorageGroupChange(th) {
    const row = th.closest('tr[data-id]');
    if (row) {
        let inputName = 'group';
        const id = row.dataset.id;
        const name = th.dataset.name;
        const editInput = row.querySelector('input[name="'+inputName+'['+id+'][edited]"]');
        const optionDeleteInput = row.querySelector('.shop-option-table input[type="checkbox"]:checked');
        if (id && name) {
            let value = th.value;
            if (th.type == 'checkbox') {
                if (th.checked) {
                    value = 1;
                }
                else {
                    value = 0;
                }
            }
            if (value === '') {
                value = null;
            }
            controlData[id][name] = value;
            if (!optionDeleteInput && objectCompare(controlData[id],unitListData.data[id]) && objectCompare(controlData[id]['options'],unitListData.data[id]['options'])) {
                row.classList.remove('edited');
                if (editInput) {
                    editInput.value="0";
                }
            }
            else {
                row.classList.add('edited');
                if (editInput) {
                    editInput.value="1";
                }
            }
        }
        setSaveEnabled();
    }
} */

/* /Filestorage */
