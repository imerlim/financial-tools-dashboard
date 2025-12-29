import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            // refresh: false, //celular
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    server: {
        host: 'localhost',
    },
    // server que possibilita entrar localmente pelo celular
    // server: {
    //     host: '0.0.0.0', // Permite acesso externo
    //     hmr: {
    //         host: '192.168.0.3', // OBRIGATÓRIO: Coloque o IP do seu PC aqui
    //     },
    //     watch: {
    //         ignored: ['**/*.php'],
    //     },
    // },
});
