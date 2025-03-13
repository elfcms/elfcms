class Notify {
    constructor (params = {}) {

        if (!params.hasOwnProperty("close")) {
            params.close = {};
        }
        if (typeof params.close == 'boolean') {
            params.close = {
                auto: params.close,
                time: 500
            };
        }
        else if (typeof params.close == 'string') {
            params.close = {
                auto: false,
                time: parseInt(params.close) ?? 500,
            };
        }
        else if (typeof params.close == 'number') {
            params.close = {
                auto: false,
                time: params.close ?? 500
            };
        }
        else if (typeof params.close == 'object') {
            if (!params.close.hasOwnProperty("auto")) {
                params.close.auto = false;
            }
            if (!params.close.hasOwnProperty("time")) {
                params.close.time = 500;
            }
        }
        else {
            params.close = {
                auto: false,
                time: 500,
                delay: 5000
            };
        }
        if (params.close.time < 10) {
            params.close.time *= 1000;
        }
        else if (params.close.time < 100) {
            params.close.time = 500;
        }

        if (!params.hasOwnProperty("position") || !['left','right','center'].includes(params.position)) {
            params.position = 'center';
        }

        let box = document.querySelector('.notify-box');
        if (!box) {
            box = document.createElement('div');
            box.classList.add('notify-box');
            document.querySelector('body').append(box);
        }

        if (params.position != 'center') {
            box.classList.add(`notify-${params.position}-box`);
        }

        if (params.close.auto === true) {
            if (!params.close.hasOwnProperty("delay")) {
                params.close.delay = 5000;
            }
            if (params.close.delay < 100) {
                params.close.delay *= 1000;
            }
            if (params.close.delay < 1000) {
                params.close.delay = 5000;
            }
        }

        this.notifies = [];
        this.params = params;
        this.box = box;

        return this;
    }

    new (params = {}) {
        if (typeof params == 'string') {
            params = {
                text: params
            };
        }

        if (typeof params != 'object' || (!params.hasOwnProperty("text") && !params.hasOwnProperty("title"))) {
            return false;
        }

        if (
            !params.hasOwnProperty("type") ||
            !["error", "warning", "success", "info"].includes(params.type)
        ) {
            params.type = "info";
        }


        let styleCloseTime = '.5s';
        styleCloseTime = `${this.params.close.time}ms`;

        const notify = document.createElement('div');
        notify.classList.add('notify', 'notify-'+params.type, 'closed');
        notify.style.setProperty('--close-time',styleCloseTime);
        const notifyData = document.createElement('div');
        notifyData.classList.add('notify-data');
        notify.append(notifyData);
        const notifyIcon = document.createElement('div');
        notifyIcon.classList.add('notify-icon');
        const notifyContent = document.createElement('div');
        notifyContent.classList.add('notify-content');
        const notifyClose = document.createElement('div');
        notifyClose.classList.add('notify-close');
        notifyClose.innerHTML = '&#215;';
        notifyClose.addEventListener('click',()=> {
            this.close(notify);
        });
        notifyData.append(notifyIcon,notifyContent,notifyClose);
        const notifyTitle = document.createElement('div');
        notifyTitle.classList.add('notify-title');
        notifyTitle.textContent = params?.title ?? null;
        const notifyText = document.createElement('div');
        notifyText.classList.add('notify-text');
        //notifyText.textContent = params?.text ?? null;
        notifyText.insertAdjacentHTML('beforeend',params?.text ?? null);
        notifyContent.append(notifyTitle,notifyText);
        this.box.prepend(notify);
        setTimeout(() => {
            notify.classList.remove('closed');
        }, 10);
        if (this.params.close.auto === true) {
            setTimeout(() => {
                this.close(notify);
            }, this.params.close.delay);
        }
        notify.close = () => {
            this.close(notify);
        };
        this.notifies.push(notify);

        return notify;
    }

    close (notify) {
        notify.classList.add('closed');
        setTimeout(() => {
            notify.remove();
        }, this.params.close.time);
    }
}
