<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
    modelValue: [String, Number],
    label: String,
    accept: String,
    id: String,
    name: String,
    inputmode: String,

    type: {
        type: String,
        default: 'text',
    },

    placeholder: String,
    mask: [String, Array],
    disabled: Boolean,
    required: Boolean,
    maxlength: Number,
    prepend: String,
    append: String,

    // Botões opcionais
    showSearch: Boolean,
    showClear: Boolean,
    showTrash: Boolean,
    showAdd: Boolean,

    error: { type: Boolean, default: false },
    propLargeAppend: { type: Boolean, default: false },
    errorMessage: { type: String, default: '' },

    textSize: {
        type: String,
        default: 'text-base',
    },
    formata: { type: Boolean, default: false },
});

const emit = defineEmits(['update:modelValue', 'on-search', 'on-clear', 'on-trash', 'on-add']);

// Computed para verificar se há botões visíveis
const hasButtons = computed(() => {
    return props.showSearch || props.showClear || props.showTrash || props.showAdd;
});

const updateValue = event => {
    let raw = event.target.value;

    if (props.formata) {
        // 1️⃣ Troca vírgula por ponto (trata decimal BR)
        raw = raw.replace(',', '.');

        // 2️⃣ Remove apenas separadores de milhar (ponto no meio dos números inteiros)
        //    Ex: 1.000.000 -> 1000000
        //    Mas mantém ponto decimal caso só tenha um
        const partes = raw.split('.');
        if (partes.length > 2) {
            // se tiver mais de um ponto, remove todos menos o último (decimal)
            const decimal = partes.pop();
            raw = partes.join('') + '.' + decimal;
        }

        // 3️⃣ Converte para número
        const numero = parseFloat(raw) || 0;

        // 4️⃣ Emite número limpo para o pai
        emit('update:modelValue', numero);
    } else {
        emit('update:modelValue', raw);
    }
};

function formatarMoeda(valor) {
    if (valor === null || valor === undefined || isNaN(valor)) return '';
    return Number(valor).toLocaleString('pt-BR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    });
}

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
    <div class="w-full">
        <label v-if="label" :for="id" class="block mb-1 font-medium text-slate-900 dark:text-white" :class="textSize">
            {{ label }}
        </label>

        <!-- Aplica gap-2 apenas se houver botões -->
        <div class="flex items-center w-full" :class="{ 'gap-2': hasButtons }">
            <!-- Campo de input -->
            <div class="flex-grow min-w-0">
                <div class="flex items-center w-full overflow-hidden rounded-lg bg-slate-50 dark:bg-slate-700" :class="[propLargeAppend ? 'px-0' : 'px-2', error ? 'border border-red-500 focus-within:ring-red-500' : 'border border-slate-300 dark:border-slate-600 focus-within:ring-blue-500 focus-within:border-blue-500', 'focus-within:ring-1']">
                    <!-- Prepend -->
                    <div v-if="$slots.prepend || prepend" class="flex items-center gap-1 select-none text-slate-700 dark:text-slate-300" :class="textSize">
                        <slot name="prepend">
                            {{ prepend }}
                        </slot>
                    </div>

                    <!-- Input -->
                    <component :is="mask ? 'input' : 'input'" v-if="mask" :id="id" ref="fieldRef" v-mask="mask" :inputmode="inputmode" :name="name" :type="type" :accept="accept" :placeholder="placeholder" :disabled="disabled" :required="required" :maxlength="maxlength" :value="props.formata ? formatarMoeda(modelValue) : modelValue" autocomplete="off" class="w-full p-2 text-slate-900 bg-slate-50 dark:bg-slate-700 dark:text-white placeholder-slate-400 dark:placeholder-slate-400 focus:outline-none border-none" :class="[textSize, { 'opacity-50 cursor-not-allowed': disabled }]" @change="updateValue" />

                    <input v-else :id="id" ref="fieldRef" :inputmode="inputmode" :name="name" :type="type" :accept="accept" :placeholder="placeholder" :disabled="disabled" :required="required" :maxlength="maxlength" :value="props.formata ? formatarMoeda(modelValue) : modelValue" autocomplete="off" class="w-full p-2 text-slate-900 bg-slate-50 dark:bg-slate-700 dark:text-white placeholder-slate-400 dark:placeholder-slate-400 focus:outline-none border-none" :class="[textSize, { 'opacity-50 cursor-not-allowed': disabled }]" :max="type === 'date' ? '9999-12-31' : null" @change="updateValue" />

                    <!-- Append -->
                    <div v-if="$slots.append || append" class="flex items-end justify-end gap-1 text-slate-700 dark:text-slate-300" :class="[textSize, propLargeAppend ? 'w-60' : 'w-4']">
                        <slot name="append">
                            {{ append }}
                        </slot>
                    </div>
                </div>
            </div>

            <!-- Botões - só renderiza se houver botões visíveis -->
            <div v-if="hasButtons" class="flex-shrink-0 flex items-center gap-2">
                <!-- Trash -->
                <button v-if="showTrash" type="button" :class="`rounded-md bg-red-600 text-white shadow-xs hover:bg-red-500 focus:outline-none focus:ring-1 focus:ring-offset-1 focus:ring-red-600 ${buttonSizeClass}`" @click="$emit('on-trash')">
                    <TrashIcon :class="iconSizeClass" aria-hidden="true" />
                </button>

                <!-- Clear -->
                <button v-if="showClear" type="button" :class="`rounded-md bg-indigo-600 text-white shadow-xs hover:bg-indigo-500 focus:outline-none focus:ring-1 focus:ring-offset-1 focus:ring-indigo-600 ${buttonSizeClass}`" @click="$emit('on-clear')">
                    <BackspaceIcon :class="iconSizeClass" aria-hidden="true" />
                </button>

                <!-- Search -->
                <button v-if="showSearch" type="button" :class="`rounded-md bg-indigo-600 text-white shadow-xs hover:bg-indigo-500 focus:outline-none focus:ring-1 focus:ring-offset-1 focus:ring-indigo-600 ${buttonSizeClass}`" @click="$emit('on-search')">
                    <MagnifyingGlassIcon :class="iconSizeClass" aria-hidden="true" />
                </button>

                <!-- Add -->
                <button v-if="showAdd" type="button" :class="`rounded-md bg-indigo-600 text-white shadow-xs hover:bg-indigo-500 focus:outline-none focus:ring-1 focus:ring-offset-1 focus:ring-indigo-600 ${buttonSizeClass}`" @click="$emit('on-add')">
                    <PlusIcon :class="iconSizeClass" aria-hidden="true" />
                </button>
            </div>
        </div>

        <p v-if="error && errorMessage" class="mt-1 text-red-600" :class="textSize">
            {{ errorMessage }}
        </p>
    </div>
</template>
