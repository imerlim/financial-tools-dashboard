<script setup>
defineOptions({
    inheritAttrs: false,
});
</script>

<template>
    <button
        type="button"
        class="cursor-pointer rounded-md px-3 py-2 text-base font-semibold bg-sky-800 text-white shadow-xs hover:bg-sky-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-700 inline-flex"
        v-bind="$attrs"
    >
        <slot />
    </button>
</template>
