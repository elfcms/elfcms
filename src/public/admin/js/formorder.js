const formGroups = document.querySelector('.form-groups');
//const groupBoxes = formGroups.querySelectorAll('.form-group-box');

let draggedItem = null;

formGroups.addEventListener('dragstart', (e) => {
    if (e.target.classList.contains('form-group-position')) {
        draggedItem = e.target.closest('.form-group-box');
        e.dataTransfer.setData('text', 'dummy'); // Required for Firefox
    }
});

formGroups.addEventListener('dragover', (e) => {
    e.preventDefault();
    const targetItem = e.target.closest('.form-group-box');
    if (targetItem && targetItem !== draggedItem) {
        const rect = targetItem.getBoundingClientRect();
        const y = e.clientY - rect.top;
        if (y < rect.height / 2) {
            formGroups.insertBefore(draggedItem, targetItem);
        } else {
            formGroups.insertBefore(draggedItem, targetItem.nextSibling);
        }
    }
});

formGroups.addEventListener('dragend', () => {
    draggedItem = null;
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
            //console.log(groupId)
            const positionBox = groupBox.querySelector('.form-group-position');
            if (positionBox) {
                positionBox.innerHTML = position;
                if (groupId && formId) {
                    groups[groupId] = position;
                }
                position++;
            }
        });
        //console.log (formId, groups);
        //const data = JSON.stringify({name:input.value});
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
        ).then (
            (data) => {
                /* if (data.result && data.data) {
                    if (data.result == 'success' && data.data.id) {
                        tagList.push(data.data)
                    }
                    if (data.data.id) {
                        addTagToList (listBox,input,data.data)
                    }
                } */
                console.log(data);
            }
        ).catch(error => {
            //
        });
    }

}
