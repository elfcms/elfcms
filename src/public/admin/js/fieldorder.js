const items = Array.from(document.querySelectorAll('.form-field-position'));
const fieldBoxes = document.querySelectorAll('.form-field-box');

let draggedItem = null;
let draggedItemParent = null;

items.forEach(item => {
  item.addEventListener('dragstart', () => {
    item.classList.add('dragging');
    itemBox = item.parentNode;
    draggedItem = itemBox;
    draggedItemParent = itemBox.parentNode;
  });

  item.addEventListener('dragend', () => {
    draggedItem = null;
    draggedItemParent = null;
    item.classList.remove('dragging');
    itemPositionSuccess();
  });

});

const fieldContainers = Array.from(document.querySelectorAll('.form-group-fields, .form-groupless-fields'));

fieldContainers.forEach(container => {
  container.addEventListener('dragover', e => {
    e.preventDefault();
    const afterElement = getDragAfterItem(container, e.clientY);
    if (draggedItem) container.insertBefore(draggedItem, afterElement);
  });
});

function getDragAfterItem(container, y) {
  const draggableElements = Array.from(container.querySelectorAll('.form-field-box:not(.dragging)'));

  return draggableElements.reduce((closest, child) => {
    const box = child.getBoundingClientRect();
    const offset = y - box.top - box.height / 2;

    if (offset < 0 && offset > closest.offset) {
      return { offset: offset, element: child };
    } else {
      return closest;
    }
  }, { offset: Number.NEGATIVE_INFINITY }).element;
}

let fieldsData = {};
let isChanged = false;

function itemPositionSuccess() {

    const preFields = Object.assign({}, fieldsData);

    if (formGroups == undefined) {
        const formGroups = document.querySelector('.form-groups');
    }

    if (!fieldContainers || !formGroups || !formGroups.dataset.form) return false;

    let formId = formGroups.dataset.form;
    let groupId = null;
    let changeCount = 0;

    fieldContainers.forEach(box => {


        groupId = box.dataset.id ?? null;
        const boxFields = box.querySelectorAll('.form-field-box');
        let position = 1;
        if (boxFields) {
            boxFields.forEach(field => {
                if (field.dataset.id) {

                    fieldsData[field.dataset.id] = {
                        'position': position,
                        'group': groupId,
                        'new': false
                    };

                    let isNew = false;

                    if (preFields[field.dataset.id] !== undefined && fieldsData[field.dataset.id]  !== undefined) {
                        if (preFields[field.dataset.id].position != fieldsData[field.dataset.id].position || preFields[field.dataset.id].group != fieldsData[field.dataset.id].group) {
                            changeCount++;
                            isNew = true;
                        }
                    }
                    else if (preFields[field.dataset.id] === undefined && fieldsData[field.dataset.id]  !== undefined) {
                        changeCount++;
                        isNew = true;
                    }

                    fieldsData[field.dataset.id].new = isNew ? 1 : 0;


                    const positionBox = field.querySelector('.form-field-position');
                    if (positionBox) {
                        positionBox.innerHTML = position;
                    }
                    position++;
                }

            });
        }
    });

    if (changeCount > 0) {
        isChanged = true;
    }
    else {
        isChanged = false;
    }

    if (isChanged) {
        const data = JSON.stringify({
            formId,
            fields: fieldsData
        });
        const token = document.querySelector("input[name='_token']").value;

        fetch('/elfcms/api/form/' + formId + '/fieldorder',{
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': token,
            },
            credentials: 'same-origin',
            body: data
        }).then(
            (result) => result.json()
        ).catch(error => {
            //
        });
    }

}

