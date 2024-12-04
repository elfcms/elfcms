function csrfUpdate() {
    const tokenMeta = document.querySelector('meta[name="csrf-token"]');
    const inputTokens = document.querySelectorAll('input[name="_token"]');
    if (tokenMeta || inputTokens) {
        fetch("/elfcms/api/csrf",{headers:{'X-Requested-With': 'XMLHttpRequest'}})
            .then((response) => {
                return response.json();
            })
            .then((data) => {
                if (data && data.token) {
                    if (tokenMeta) {
                        if (tokenMeta.content != data.token) tokenMeta.content = data.token;;
                    }
                    if (inputTokens) {
                        inputTokens.forEach((inputToken) => {
                            if (inputToken.value != data.token) inputToken.value = data.token;
                        });
                    }
                }
            });
    }
}

function csrfAutoUpdate(timeout = 3600) {
    if (timeout < 60) timeout * 60;
    timeout *= 1000;
    const intervalId = setInterval(csrfUpdate, timeout);
    return intervalId;
}
