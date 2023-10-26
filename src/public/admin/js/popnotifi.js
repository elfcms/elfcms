function popnotifi (type='info',text='') {

    const box = document.createElement('div');

    box.classList.add('pop-notifi', 'pop-notifi-hidden', 'pop-notifi-' + type);

    const closeButton = document.createElement('div');

    closeButton.classList.add('pop-notifi-close');
    closeButton.addEventListener('click',() => {
        box.remove();
    });

    const textBox = document.createElement('div');
    textBox.classList.add('pop-notifi-text');

    textBox.innerText = text;

    box.append(closeButton);
    box.append(textBox);

    return box;

}


function showNotifi (type='info',text='',timeout=5000) {
    const box = popnotifi(type,text);
    document.body.append(box);
    setTimeout(()=>{
        box.classList.remove('pop-notifi-hidden');
    },100);
    if (timeout < 100) {
        timeout = timeout * 1000;
    }
    setTimeout(() => {
        box.classList.add('pop-notifi-hidden');
    }, timeout);
    setTimeout(() => {
        box.remove();
    }, timeout + 2000);
}
