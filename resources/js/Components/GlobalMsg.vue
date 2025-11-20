<template>
    <transition name="fade">
        <div
            v-if="visible"
            class="fixed top-6 left-1/2 -translate-x-1/2 px-4 py-3 rounded-lg shadow-2xl text-white text-sm z-[9999] w-fit max-w-md"
            :class="{
                'bg-amber-600': type === 'warning',
                'bg-red-600': type === 'error',
                'bg-blue-600': type === 'info',
                'bg-emerald-600': type === 'success',
            }"
        >
            <strong class="block text-base mb-1">{{ title }}</strong>
            <span>{{ body }}</span>
        </div>
    </transition>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

const visible = ref(false);
const title = ref('');
const body = ref('');
const type = ref('success');
let timeout = null;

const showMsg = event => {
    const { title: t, body: b, type: tp } = event.detail;
    title.value = t;
    body.value = b;
    type.value = tp;
    visible.value = true;

    clearTimeout(timeout);
    timeout = setTimeout(() => {
        visible.value = false;
    }, 3000);
};

onMounted(() => window.addEventListener('global-msg', showMsg));
onUnmounted(() => window.removeEventListener('global-msg', showMsg));
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.4s;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
