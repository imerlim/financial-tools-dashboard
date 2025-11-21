import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp, Head, Link, useForm } from '@inertiajs/vue3';
import { ZiggyVue } from 'ziggy-js';
import { Ziggy } from './ziggy';

import ModalMedium from './Components/ModalMedium.vue';
import Modal from './Components/Modal.vue';

import CustomInput from './Components/CustomInput.vue';
import CustomSelect from './Components/CustomSelect.vue';
import CustomTextArea from './Components/CustomTextArea.vue';
import CustomCheckbox from './Components/CustomCheckbox.vue';

import BarChart from './Components/Graph/BarChart.vue';
import BarChartHorizontal from './Components/Graph/BarChartHorizontal.vue';
import MultipleBarChart from './Components/Graph/MultipleBarChart.vue';
import DonutChart from './Components/Graph/DonutChart.vue';
import Indicadores from './Components/Graph/Indicadores.vue';
import LineChart from './Components/Graph/LineChart.vue';
import PieChart from './Components/Graph/PieChart.vue';

import msg from './Plugins/msg';
import GlobalMsg from './Components/GlobalMsg.vue';
import Table from './Components/Table.vue';
import PrimaryButton from './Components/PrimaryButton.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import '@tailwindplus/elements';

import icons from './heroicons.js';

createInertiaApp({
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true });
        const page = pages[`./Pages/${name}.vue`];

        if (!page) {
            throw new Error(`Página ${name} não encontrada em ./Pages`);
        }
        // Todas as outras páginas usam o layout autenticado (com Navbar)
        page.default.layout = page.default.layout || AuthenticatedLayout;

        return page;
    },

    setup({ el, App, props, plugin }) {
        const vueApp = createApp({ render: () => h(App, props) });

        // 1. Plugins e Mixins
        vueApp.use(plugin);
        vueApp.use(msg);
        vueApp.use(ZiggyVue, Ziggy);

        // 2. Componentes Individuais
        vueApp.component('Head', Head);
        vueApp.component('Link', Link);
        vueApp.component('useForm', useForm);

        vueApp.component('ModalMedium', ModalMedium);
        vueApp.component('Modal', Modal);

        vueApp.component('CustomInput', CustomInput);
        vueApp.component('CustomSelect', CustomSelect);
        vueApp.component('CustomTextArea', CustomTextArea);
        vueApp.component('CustomCheckbox', CustomCheckbox);

        vueApp.component('BarChart', BarChart);
        vueApp.component('BarChartHorizontal', BarChartHorizontal);
        vueApp.component('MultipleBarChart', MultipleBarChart);
        vueApp.component('DonutChart', DonutChart);
        vueApp.component('Indicadores', Indicadores);
        vueApp.component('LineChart', LineChart);
        vueApp.component('PieChart', PieChart);

        vueApp.component('GlobalMsg', GlobalMsg);
        vueApp.component('Table', Table);
        vueApp.component('PrimaryButton', PrimaryButton);
        vueApp.component('AuthenticatedLayout', AuthenticatedLayout);

        // 3. REGISTRO GLOBAL DE HEROICONS (Corrigido)
        // Percorre o objeto 'icons' e registra cada um usando o nome (chave)
        // e o componente (valor) na instância 'vueApp'.
        Object.entries(icons).forEach(([name, component]) => {
            vueApp.component(name, component); // <<< CORREÇÃO AQUI: Usando 'vueApp'
        });

        vueApp.mount(el);
    },

    progress: {
        color: '#4B5563',
    },
});
