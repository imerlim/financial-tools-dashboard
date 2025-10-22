<script setup>
import { ref, computed } from 'vue';
import NavBar from './NavBar.vue';
import { route } from 'ziggy-js';

// Estado para exemplo (mobile menu, etc.) caso queira passar para NavBar
const mobileMenuOpen = ref(false);

// URLs seguras usando computed para evitar erros de Ziggy
const homeUrl = computed(() => (typeof window !== 'undefined' && window.Ziggy ? route('home') : '#'));
const loginUrl = computed(() => (typeof window !== 'undefined' && window.Ziggy ? route('login') : '#'));
</script>

<template>
    <div class="min-h-screen flex flex-col bg-white dark:bg-gray-900">
        <NavBar :home-url="homeUrl" :login-url="loginUrl" />

        <main class="pt-16 flex-1 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200">
            <slot />
        </main>
    </div>
</template>
