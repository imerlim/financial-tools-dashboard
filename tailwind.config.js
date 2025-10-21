/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class', // <<- isso é obrigatório
    content: ['./resources/**/*.blade.php', './resources/**/*.js', './resources/**/*.vue'],
    theme: {
        extend: {},
    },

    plugins: [],
};
