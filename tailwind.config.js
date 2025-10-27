import forms from '@tailwindcss/forms';
import flowbitePlugin from 'flowbite/plugin';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: ['./resources/**/*.blade.php', './resources/**/*.js', './resources/**/*.vue'],
    theme: {
        extend: {
            // 1. EXTENDE A PALETA DE CORES
            colors: {
                // 2. ADICIONA A NOVA COR DENTRO DO SLATE
                slate: {
                    ...require('tailwindcss/colors').slate, // Garante que as cores padrão do slate sejam mantidas
                    850: '#1c2535', // Cor entre #1E293B (800) e #0F172A (900)
                },
            },
        },
    },

    plugins: [forms, flowbitePlugin],
};
