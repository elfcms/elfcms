
function inputFileComponent (input) {
    if (!input) {
        console.log('err')
        return false
    }
    const wrapper = input.closest('.input-file-button')
    if (wrapper) {
        const text = wrapper.querySelector('.file-button-text')

        function deleteFile (wrap) {
            const del = wrap.querySelector('.delete-file')
            const hid = wrap.closest('.input-wrapper').querySelector('input[type="hidden"]')
            console.log(del)
            console.log(hid)
            if (del) {
                del.addEventListener('click',function(){
                    if (hid) {
                        hid.value = null
                    }
                    console.log(hid)
                    console.log(hid.value)
                    input.value = null
                    this.classList.add('hidden')
                    text.innerHTML = input.dataset.title ?? 'Choose file';
                })
            }
        }

        deleteFile(wrapper)

        input.addEventListener('change',function(e){
            const files = e.target.files
            if (files) {
                if (text) {
                    text.innerHTML = files[0].name
                }
            }
        })
    }
}
