<script setup>
// Nota: O defineOptions não é onde se definem props, mas sim opções do componente.
// Se 'disabled' é para ser uma prop, deve ser definida via 'defineProps'.
defineOptions({
    inheritAttrs: false,
});

// Definição correta da Prop
const props = defineProps({
    disabled: {
        type: Boolean,
        default: false,
    },
});
</script>

<template>
    <button
        type="button"
        :class="[
            // Classes condicionais (aplicadas se props.disabled for true)
            {
                'opacity-70': props.disabled,
                'cursor-not-allowed': props.disabled, // Adiciona o cursor-not-allowed aqui
            },
            // Classes estáticas (aplicadas sempre, note que 'cursor-pointer' foi removido daqui)
            'rounded-md px-3 py-2 text-base font-semibold bg-sky-800 text-white shadow-xs hover:bg-sky-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-700 inline-flex',
        ]"
        :disabled="props.disabled"
        v-bind="$attrs"
    >
        <slot />
    </button>
</template>
