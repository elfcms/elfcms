const items = Array.from(document.querySelectorAll(".menu-item-position"));

let draggedItem = null;
let draggedItemParent = null;

items.forEach((item) => {
    item.addEventListener("dragstart", () => {
        item.classList.add("dragging");
        itemBox = item.parentNode;
        draggedItem = itemBox;
        draggedItem.classList.add("dragging");
        draggedItemParent = itemBox.parentNode;
    });

    item.addEventListener("dragend", () => {
        draggedItem.classList.remove("dragging");
        draggedItem = null;
        draggedItemParent = null;
        item.classList.remove("dragging");
        itemPositionSuccess();
    });
});

const itemContainers = Array.from(
    document.querySelectorAll(".menu-items, .menu-item-subitems")
);

const menuBox = document.querySelector(".menu-items");
let menuId = null;
if (menuBox) {
    menuId = menuBox.dataset.menu;
}

itemContainers.forEach((container) => {
    container.addEventListener("dragover", (e) => {
        e.preventDefault();
        const afterElement = getDragAfterItem(container, e.clientY);
        try {
            if (draggedItem) container.insertBefore(draggedItem, afterElement);
        } catch (e) {
            //
        }
    });
});

function getDragAfterItem(container, y) {
    const draggableElements = Array.from(
        container.querySelectorAll(
            ".menu-item-box:not(.dragging), .menu-not-item"
        )
    );

    return draggableElements.reduce(
        (closest, child) => {
            const box = child.getBoundingClientRect();
            const offset = y - box.top - box.height / 2;

            if (offset < 0 && offset > closest.offset) {
                return { offset: offset, element: child };
            } else {
                return closest;
            }
        },
        { offset: Number.NEGATIVE_INFINITY }
    ).element;
}

let itemsData = {};
let preItems = {};
let isChanged = false;

function checkData(preItems, itemsData, id) {
    let isProp = preItems.hasOwnProperty(id);
    if (!isProp) {
        return true;
    } else if (
        preItems[id].parent != itemsData[id].parent ||
        preItems[id].position != itemsData[id].position
    ) {
        return true;
    }
    return false;
}

function setItemData() {
    const itemBoxes = document.querySelectorAll(".menu-item-box");
    preItems = Object.assign({}, itemsData);
    let position = 1;
    itemBoxes.forEach((item) => {
        const parentBox = item.closest(".menu-item-subitems");

        if (parentBox) {
            const parentItem = parentBox.closest(".menu-item-box");
            parentId = parentItem.dataset.id;
            let subposition = 1;
            const subitems = parentBox.querySelectorAll(".menu-item-box");
            if (subitems) {
                subitems.forEach((element) => {
                    itemsData[element.dataset.id] = {
                        parent: parentId,
                        position: subposition,
                        element,
                    };
                    const posBox = element.querySelector(".menu-item-position");
                    //if (posBox) posBox.innerHTML = subposition;
                    if (posBox) {
                        const positionValue = posBox.querySelector("span");
                        if (positionValue) {
                            positionValue.innerText = subposition;
                        } else {
                            posBox.innerHTML = subposition;
                        }
                    }
                    isChanged = checkData(
                        preItems,
                        itemsData,
                        element.dataset.id
                    );
                    itemsData[element.dataset.id].new = isChanged ? 1 : 0;
                    subposition++;
                });
            }
        } else {
            itemsData[item.dataset.id] = {
                parent: null,
                position: position,
                element: item,
            };
            const posBox = item.querySelector(".menu-item-position");
            //if (posBox) posBox.innerHTML = position;
            if (posBox) {
                const positionValue = posBox.querySelector("span");
                if (positionValue) {
                    positionValue.innerText = position;
                } else {
                    posBox.innerHTML = position;
                }
            }
            isChanged = checkData(preItems, itemsData, item.dataset.id);
            itemsData[item.dataset.id].new = isChanged ? 1 : 0;
            position++;
        }
    });
}

function itemPositionSuccess() {
    setItemData();
    if (isChanged) {
        const token = document.querySelector("input[name='_token']").value;

        const data = JSON.stringify({
            menuId,
            items: itemsData,
        });

        fetch(adminPath+"/elfcms/api/menu/" + menuId + "/itemorder", {
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
            .catch((error) => {
                //
            });
    }
}

setTimeout(() => {
    setItemData();
}, 100);
