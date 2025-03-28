
function inputImgComponent (input) {
    if (!input) {
        console.log('err')
        return false
    }
    const wrapper = input.closest('.input-image-button')
    if (wrapper) {
        const img = wrapper.querySelector('.image-button-img img')
        const text = wrapper.querySelector('.image-button-text')

        function deleteImage (wrap) {
            const del = wrap.querySelector('.delete-image')
            const hid = wrap.closest('.input-wrapper').querySelector('input[type="hidden"]')
            if (del) {
                del.addEventListener('click',function(){
                    if (img) {
                        img.src = '/elfcms/admin/images/icons/upload.png'
                    }
                    if (hid) {
                        hid.value = null
                    }
                    input.value = null
                    this.classList.add('hidden')
                    text.innerHTML = 'Choose file';
                })
            }
        }

        deleteImage(wrapper)

        input.addEventListener('change',function(e){
            const files = e.target.files
            if (files) {
                if (text) {
                    text.innerHTML = files[0].name
                }
                if (FileReader && files && files.length) {
                    var fReader = new FileReader();
                    fReader.onload = function () {
                        if (img) {
                            img.src = fReader.result;
                        }
                        const del = wrapper.querySelector('.delete-image')
                        if (del) {
                            del.classList.remove('hidden')
                        }
                    }
                    fReader.readAsDataURL(files[0]);
                }
            }
        })
    }
}
