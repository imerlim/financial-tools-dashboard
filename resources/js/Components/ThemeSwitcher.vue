<script setup>
import { ref, onMounted } from 'vue';
import { SunIcon, MoonIcon } from '@heroicons/vue/24/solid';

const isDarkMode = ref(false);

const toggleTheme = () => {
    isDarkMode.value = !isDarkMode.value;
    if (isDarkMode.value) {
        document.documentElement.classList.add('dark');
        localStorage.setItem('theme', 'dark');
    } else {
        document.documentElement.classList.remove('dark');
        localStorage.setItem('theme', 'light');
    }
};

onMounted(() => {
    if (localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        isDarkMode.value = true;
        document.documentElement.classList.add('dark');
    } else {
        isDarkMode.value = false;
        document.documentElement.classList.remove('dark');
    }
});
</script>

<template>
    <button @click="toggleTheme" class="rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500">
        <SunIcon v-if="isDarkMode" class="h-6 w-6 text-yellow-500" />
        <MoonIcon v-else class="h-6 w-6 text-sky-700" />
    </button>
</template>
