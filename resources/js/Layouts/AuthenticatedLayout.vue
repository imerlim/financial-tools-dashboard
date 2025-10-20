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
    <div class="min-h-screen flex flex-col">
        <!-- Navbar sempre no topo -->
        <NavBar :home-url="homeUrl" :login-url="loginUrl" />

        <!-- Conteúdo principal da página -->
        <main class="pt-16 flex-1">
            <slot />
        </main>
    </div>
</template>
