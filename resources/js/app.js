import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { ZiggyVue } from 'ziggy-js';
import { Head, Link } from '@inertiajs/vue3';
import { UsersIcon, XMarkIcon } from '@heroicons/vue/24/outline';

import ModalMedium from './Components/ModalMedium.vue';
import CustomInput from './Components/CustomInput.vue';
import CustomSelect from './Components/CustomSelect.vue';
import CustomTextArea from './Components/CustomTextArea.vue';
import msg from './Plugins/msg';
import GlobalMsg from './Components/GlobalMsg.vue';

createInertiaApp({
    title: title => (title ? `${title} - Minha Aplicação` : 'Minha Aplicação'),

    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true });
        const page = pages[`./Pages/${name}.vue`];

        if (!page) {
            throw new Error(`Página ${name} não encontrada em ./Pages`);
        }

        return page;
    },

    setup({ el, App, props, plugin }) {
        const vueApp = createApp({ render: () => h(App, props) });

        vueApp;
        vueApp.use(plugin);
        vueApp.use(msg); // ✅ CORREÇÃO: Use o plugin 'msg' aqui!
        vueApp.use(ZiggyVue);
        vueApp.component('Head', Head);
        vueApp.component('Link', Link);
        vueApp.component('UsersIcon', UsersIcon);
        vueApp.component('XMarkIcon', XMarkIcon);
        vueApp.component('ModalMedium', ModalMedium);
        vueApp.component('CustomInput', CustomInput);
        vueApp.component('CustomSelect', CustomSelect);
        vueApp.component('CustomTextArea', CustomTextArea);
        vueApp.component('GlobalMsg', GlobalMsg);

        vueApp.mount(el);
    },

    progress: {
        color: '#4B5563',
    },
});
