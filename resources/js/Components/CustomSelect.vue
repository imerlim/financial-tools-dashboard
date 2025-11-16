<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
    modelValue: [String, Number, null],
    label: String,
    id: String,
    name: String,
    placeholder: String,
    disabled: Boolean,
    required: Boolean,

    options: {
        type: Array,
        required: true,
    },

    prepend: String,
    append: String,

    showTrash: Boolean,
    showClear: Boolean,
    showSearch: Boolean,
    showAdd: Boolean,

    error: { type: Boolean, default: false },
    errorMessage: { type: String, default: '' },

    textSize: {
        type: String,
        default: 'text-base',
    },
});

const emit = defineEmits(['update:modelValue', 'on-trash', 'on-clear', 'on-search', 'on-add']);

const updateValue = event => {
    emit('update:modelValue', event.target.value);
};

// 🔹 ref do input
const fieldRef = ref(null); // Renomeado para clareza

// 🔹 Crie um método 'focus' que atua sobre a ref interna
const focus = () => {
    fieldRef.value?.focus();
};

// 🔹 Exponha o MÉTODO 'focus', não a referência
defineExpose({
    focus,
});

const buttonSizeClass = computed(() => {
    switch (props.textSize) {
        case 'text-xs':
            return 'p-1.5';
        case 'text-sm':
            return 'p-2';
        case 'text-base':
            return 'p-2.5';
        case 'text-lg':
            return 'p-3';
        case 'text-xl':
            return 'p-3.5';
        default:
            return 'p-2';
    }
});

const iconSizeClass = computed(() => {
    switch (props.textSize) {
        case 'text-xs':
        case 'text-sm':
            return 'size-5';
        case 'text-base':
        case 'text-lg':
        case 'text-xl':
            return 'size-6';
        default:
            return 'size-5';
    }
});
</script>

<template>
    <div class="sm:col-span-2">
        <label v-if="label" :for="id" class="block mb-1 font-medium text-slate-900 dark:text-white" :class="textSize">
            {{ label }}
        </label>

        <!-- Linha principal: select + botões -->
        <div class="flex items-center w-full" :class="{ 'gap-2': showTrash || showClear || showSearch || showAdd }">
            <!-- Wrapper do SELECT -->
            <div class="flex-grow min-w-0">
                <div
                    class="flex items-center w-full overflow-hidden rounded-lg px-2 bg-slate-50 dark:bg-slate-700"
                    :class="[
                        error
                            ? 'border border-red-500 focus-within:ring-red-500'
                            : 'border border-slate-300 dark:border-slate-600 focus-within:ring-blue-500 focus-within:border-blue-500',
                        'focus-within:ring-1',
                    ]"
                >
                    <!-- Prepend -->
                    <div v-if="$slots.prepend || prepend" class="flex items-center text-slate-700 dark:text-slate-300" :class="textSize">
                        <slot name="prepend">
                            {{ prepend }}
                        </slot>
                    </div>

                    <!-- SELECT -->
                    <select
                        :id="id"
                        ref="fieldRef"
                        :name="name"
                        :disabled="disabled"
                        :required="required"
                        :value="modelValue"
                        class="w-full p-2 text-slate-900 bg-slate-50 dark:bg-slate-700 dark:text-white placeholder-slate-400 dark:placeholder-slate-400 focus:outline-none border-none"
                        :class="[textSize, { 'opacity-50 cursor-not-allowed': disabled }]"
                        @change="updateValue"
                    >
                        <option :value="modelValue == null ? '' : 0" disabled>
                            {{ placeholder || 'Selecione' }}
                        </option>
                        <option v-for="(option, index) in options" :key="index" :value="option.value">
                            {{ option.label }}
                        </option>
                    </select>

                    <!-- Append -->
                    <div v-if="$slots.append || append" class="flex items-center text-slate-700 dark:text-slate-300" :class="textSize">
                        <slot name="append">
                            {{ append }}
                        </slot>
                    </div>
                </div>
            </div>

            <!-- Botões -->
            <div v-if="showTrash || showClear || showSearch || showAdd" class="flex-shrink-0 flex items-center gap-2">
                <!-- Trash -->
                <div v-if="showTrash" class="relative group">
                    <button
                        type="button"
                        :class="`rounded-md bg-red-600 text-white shadow-xs hover:bg-red-500 focus:outline-none focus:ring-1 focus:ring-offset-1 focus:ring-red-600 ${buttonSizeClass}`"
                        @click="$emit('on-trash')"
                    >
                        <TrashIcon :class="iconSizeClass" aria-hidden="true" />
                    </button>
                    <span class="tooltip">Deletar</span>
                </div>

                <!-- Clear -->
                <div v-if="showClear" class="relative group">
                    <button
                        type="button"
                        :class="`rounded-md bg-sky-800 text-white shadow-xs hover:bg-merlimSystem-500 focus:outline-none focus:ring-1 focus:ring-offset-1 focus:ring-sky-600 ${buttonSizeClass}`"
                        @click="$emit('on-clear')"
                    >
                        <BackspaceIcon :class="iconSizeClass" aria-hidden="true" />
                    </button>
                    <span class="tooltip">Limpar</span>
                </div>

                <!-- Search -->
                <div v-if="showSearch" class="relative group">
                    <button
                        type="button"
                        :class="`rounded-md bg-sky-800 text-white shadow-xs hover:bg-sky-800 focus:outline-none focus:ring-1 focus:ring-offset-1 focus:ring-sky-600 ${buttonSizeClass}`"
                        @click="$emit('on-search')"
                    >
                        <MagnifyingGlassIcon :class="iconSizeClass" aria-hidden="true" />
                    </button>
                    <span class="tooltip">Buscar</span>
                </div>

                <!-- Add -->
                <div v-if="showAdd" class="relative group">
                    <button
                        type="button"
                        :class="`rounded-md bg-sky-800 text-white shadow-xs hover:bg-sky-800 focus:outline-none focus:ring-1 focus:ring-offset-1 focus:ring-sky-600 ${buttonSizeClass}`"
                        @click="$emit('on-add')"
                    >
                        <PlusIcon :class="iconSizeClass" aria-hidden="true" />
                    </button>
                    <span class="tooltip">Adicionar</span>
                </div>
            </div>
        </div>

        <p v-if="error && errorMessage" class="mt-1 text-red-600" :class="textSize">
            {{ errorMessage }}
        </p>
    </div>
</template>

<style scoped>
@reference "tailwindcss";
.tooltip {
    @apply absolute z-10 -top-9 left-1/2 -translate-x-1/2 whitespace-nowrap rounded bg-black px-2 py-1 text-xs text-white opacity-0 transition-opacity duration-200 pointer-events-none;
}

.group:hover .tooltip {
    @apply opacity-100;
}
</style>
