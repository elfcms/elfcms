const menuButton = document.querySelector('.menubutton')
const menuContainer = document.querySelector('#header')
if (menuButton) {
    menuButton.addEventListener('click',function(){
        //console.log(this)
        if (this && this.classList.contains('opened')) {
            this.classList.remove('opened')
            this.classList.add('closed')
            if (menuContainer) {
                menuContainer.classList.remove('opened')
            }
        }
        else {
            this.classList.remove('closed')
            this.classList.add('opened')
            if (menuContainer) {
                menuContainer.classList.add('opened')
            }
        }
    })
}
let startGlobalPreload = preloadSet(document.body);
window.onload = () => {
    preloadUnset(startGlobalPreload);
}
