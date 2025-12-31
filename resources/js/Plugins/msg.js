// resources/Plugins/msg.js
export default {
    install: app => {
        app.config.globalProperties.$msg = {
            show(title, body, type = 'success') {
                window.dispatchEvent(
                    new CustomEvent('global-msg', {
                        detail: { title, body, type },
                    })
                );
            },
            success(body, title = 'Success!') {
                this.show(title, body, 'success');
            },
            error(body, title = 'Error!') {
                this.show(title, body, 'error');
            },
            warning(body, title = 'Warning!') {
                this.show(title, body, 'warning');
            },
            info(body, title = 'Information') {
                this.show(title, body, 'info');
            },
        };
    },
};
