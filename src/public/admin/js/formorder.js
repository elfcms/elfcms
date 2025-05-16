const formGroups = document.querySelector('.form-groups');

let draggedElement = null;

formGroups.addEventListener('dragstart', (e) => {
    if (e.target.classList.contains('form-group-position')) {
        draggedElement = e.target.closest('.form-group-box');
        e.dataTransfer.setData('text', 'dummy'); // Required for Firefox
    }
});

formGroups.addEventListener('dragover', (e) => {
    e.preventDefault();
    const targetItem = e.target.closest('.form-group-box');
    if (targetItem && targetItem !== draggedElement) {
        const rect = targetItem.getBoundingClientRect();
        const y = e.clientY - rect.top;
        if (y < rect.height / 2) {
            formGroups.insertBefore(draggedElement, targetItem);
        } else {
            formGroups.insertBefore(draggedElement, targetItem.nextSibling);
        }
    }
});

formGroups.addEventListener('dragend', () => {
    draggedElement = null;
    elementPositionSuccess();
});

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
                const positionValue = positionBox.querySelector('span');
                if (positionValue) {
                    positionValue.innerText = position;
                }
                else {
                    positionBox.innerHTML = position;
                }
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
        fetch(adminPath+'/elfcms/api/form/' + formId + '/grouporder',{
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
        ).then (
            (data) => {
                //console.log(data);
            }
        ).catch(error => {
            //
        });
    }

}
