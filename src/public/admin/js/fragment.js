let elfSBDataTypes = null;
async function getSBDataTypes() {
    if (elfSBDataTypes !== null && typeof elfSBDataTypes == "object") {
        return elfSBDataTypes;
    }
    let elfSBDataTypesResponse = await fetch("/elfcms/api/fragment/datatypes", {
        headers: { "X-Requested-With": "XMLHttpRequest" },
    });
    elfSBDataTypes = await elfSBDataTypesResponse.json();
    return elfSBDataTypes;
}
getSBDataTypes();

function fragmentOptionInit(addSelector = "#addoptionline", line = 0) {
    optionNextLine = line;

    const addButton = document.querySelector(addSelector);
    if (addButton) {
        addButton.addEventListener("click", function () {
            const lastLine = document.querySelector(
                '.options-table-string-line[data-line="' + optionNextLine + '"]'
            );
            optionNextLine++;
            let optionList = "";
            elfSBDataTypes.forEach((option) => {
                optionList += `<option value="${option.id}">${option.name}</option>`;
            });
            const htmlLine = `
                        <div class="options-table-string-line" data-line="${optionNextLine}">
                            <div class="options-table-string">
                                <select name="options_new[${optionNextLine}][type]" id="option_new_type_${optionNextLine}" data-option-type>
                                    ${optionList}
                                </select>
                            </div>
                            <div class="options-table-string">
                                <input type="text" name="options_new[${optionNextLine}][name]" id="option_new_name_${optionNextLine}" data-option-name data-isslug>
                            </div>
                            <div class="options-table-string">
                                <input type="text" name="options_new[${optionNextLine}][value]" id="option_new_value_${optionNextLine}" data-option-value>
                            </div>
                            <div class="options-table-string">
                                <input type="checkbox" name="options_new[${optionNextLine}][deleted]" id="option_new_disabled_${optionNextLine}" data-option-deleted>
                            </div>
                        </div>
                        `;
            if (lastLine) {
                lastLine.insertAdjacentHTML("afterend", htmlLine);
            }
        });
    }
}
