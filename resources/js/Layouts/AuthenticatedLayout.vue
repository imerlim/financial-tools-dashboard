<script setup>
import { ref, computed } from 'vue';
import NavBar from './NavBar.vue';
import Footer from './Footer.vue';
import { route } from 'ziggy-js';
import { usePage } from '@inertiajs/vue3'; // <--- NOVO IMPORT

// 1. OBTÉM ACESSO À PÁGINA ATUAL
const page = usePage();

// 2. DEFINE AS ROTAS ONDE A NAVBAR DEVE SER OCULTADA
// Adicione aqui qualquer rota que não deve mostrar a barra de navegação (ex: /login, /cadastro)
const rotasSemNavbar = ['/login', '/cadastro', '/aminia']; // <-- '/aminia' ADICIONADO AQUI

// 3. LÓGICA COMPUTADA PARA VISIBILIDADE
const showNavbar = computed(() => {
    // Pega o caminho da URL (ex: /dashboard, /login)
    const currentPath = page.url;

    // Retorna TRUE se a rota atual NÃO começar com nenhuma das rotas de exceção.
    // Assim, se a rota for '/login' ou '/aminia', showNavbar será false.
    return !rotasSemNavbar.some(routePath => currentPath.startsWith(routePath));
});
// Fim da Lógica de Visibilidade

// Estado para exemplo (mobile menu, etc.) caso queira passar para NavBar
const mobileMenuOpen = ref(false);

// URLs seguras usando computed para evitar erros de Ziggy
const homeUrl = computed(() => (typeof window !== 'undefined' && window.Ziggy ? route('home') : '#'));
const loginUrl = computed(() => (typeof window !== 'undefined' && window.Ziggy ? route('login') : '#'));
</script>

<template>
    <div class="min-h-screen flex flex-col bg-white dark:bg-slate-850">
        <!-- RENDERIZAÇÃO CONDICIONAL: NavBar só é exibida se showNavbar for true -->
        <NavBar v-if="showNavbar" :home-url="homeUrl" :login-url="loginUrl" />

        <!-- 
            AJUSTE DO PADDING TOP: 
            Se a NavBar estiver visível (showNavbar é true), usa pt-16.
            Se a NavBar estiver oculta, usa pt-0 para que o conteúdo comece no topo.
        -->
        <main :class="['flex-1 bg-white dark:bg-slate-850 text-gray-800 dark:text-gray-200', showNavbar ? 'pt-16' : 'pt-0']">
            <slot />
        </main>

        <Footer></Footer>
    </div>
</template>
