
const formGroups = document.querySelector('.form-groups');
const elements = Array.from(document.querySelectorAll('.form-group-position'));

let draggedElement = null;
let draggedElementParent = null;

elements.forEach(element => {
    element.addEventListener('dragstart', () => {
        element.classList.add('dragging');
        elementBox = element.parentNode;
        draggedElement = elementBox;
        draggedElementParent = elementBox.parentNode;
    });

    element.addEventListener('dragend', () => {
        draggedElement = null;
        draggedElementParent = null;
        element.classList.remove('dragging');
        elementPositionSuccess();
    });
});

const containers = Array.from(document.querySelectorAll('.form-groups'));

containers.forEach(container => {
    container.addEventListener('dragover', e => {
        e.preventDefault();
        const afterElement = getDragAfterElement(container, e.clientY);
        if (draggedElement) container.insertBefore(draggedElement, afterElement);
    });
});

function getDragAfterElement(container, y) {
  const draggableElements = Array.from(container.querySelectorAll('.form-group-box:not(.dragging)'));

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

function elementPositionSuccess() {
    const groupBoxes = formGroups.querySelectorAll('.form-group-box');
    if (groupBoxes) {
        let position = 1;
        let formId = formGroups.dataset.form;
        let groups = {};
        groupBoxes.forEach(groupBox => {
            const groupId = groupBox.dataset.id;
            const positionBox = groupBox.querySelector('.form-group-position');
            if (positionBox) {
                positionBox.innerHTML = position;
                if (groupId && formId) {
                    groups[groupId] = position;
                }
                position++;
            }
        });

        const data = JSON.stringify({
            formId,
            groups
        });
        const token = document.querySelector("input[name='_token']").value;
        fetch('/elfcms/api/form/' + formId + '/grouporder',{
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
