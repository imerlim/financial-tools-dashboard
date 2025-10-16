// resources/Plugins/msg.js
export default {
    install: (app) => {
        app.config.globalProperties.$msg = {
            show(title, body, type = 'success') {
                window.dispatchEvent(new CustomEvent('global-msg', {
                    detail: { title, body, type }
                }));
            },
            success(body, title = 'Sucesso!') {
                this.show(title, body, 'success');
            },
            error(body, title = 'Erro!') {
                this.show(title, body, 'error');
            },
            warning(body, title = 'Atenção!') {
                this.show(title, body, 'warning');
            },
            info(body, title = 'Informação') {
                this.show(title, body, 'info');
            },
        };
    }
};
