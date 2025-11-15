<script setup>
import { ref, computed } from 'vue';
import NavBar from './NavBar.vue';
import Footer from './Footer.vue';
import { route } from 'ziggy-js';
import { usePage } from '@inertiajs/vue3';

const page = usePage();

const rotasSemNavbar = ['/aminia'];

const showNavbar = computed(() => {
    const currentPath = page.url;

    return !rotasSemNavbar.some(routePath => currentPath.startsWith(routePath));
});

const showFooter = computed(() => {
    const currentPath = page.url;

    return !rotasSemNavbar.some(routePath => currentPath.startsWith(routePath));
});

const mobileMenuOpen = ref(false);

const homeUrl = computed(() => (typeof window !== 'undefined' && window.Ziggy ? route('home') : '#'));
const loginUrl = computed(() => (typeof window !== 'undefined' && window.Ziggy ? route('login') : '#'));
</script>

<template>
    <div class="min-h-screen flex flex-col bg-white dark:bg-slate-850">
        <NavBar v-if="showNavbar" :home-url="homeUrl" :login-url="loginUrl" />

        <main :class="['flex-1 bg-white dark:bg-slate-850 text-gray-800 dark:text-gray-200', showNavbar ? 'pt-16' : 'pt-0']">
            <slot />
        </main>

        <Footer v-if="showFooter"></Footer>
    </div>
</template>
