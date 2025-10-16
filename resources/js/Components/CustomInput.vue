<script setup>
import { ref, computed } from 'vue'

const props = defineProps({
    modelValue: [String, Number],
    label: String,
    accept: String,
    id: String,
    name: String,

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
    errorMessage: { type: String, default: '' },

    textSize: {
        type: String,
        default: 'text-base',
    },
})

const emit = defineEmits([
    'update:modelValue',
    'on-search',
    'on-clear',
    'on-trash',
    'on-add',
])

// Computed para verificar se há botões visíveis
const hasButtons = computed(() => {
    return props.showSearch || props.showClear || props.showTrash || props.showAdd
})

const updateValue = (event) => {
    emit('update:modelValue', event.target.value)
}

// 🔹 ref do input
const fieldRef = ref(null) // Renomeado para clareza

// 🔹 Crie um método 'focus' que atua sobre a ref interna
const focus = () => {
  fieldRef.value?.focus()
}

// 🔹 Exponha o MÉTODO 'focus', não a referência
defineExpose({
  focus
})

const buttonSizeClass = computed(() => {
    switch (props.textSize) {
        case 'text-xs':
            return 'p-1.5'
        case 'text-sm':
            return 'p-2'
        case 'text-base':
            return 'p-2.5'
        case 'text-lg':
            return 'p-3'
        case 'text-xl':
            return 'p-3.5'
        default:
            return 'p-2'
    }
})

const iconSizeClass = computed(() => {
    switch (props.textSize) {
        case 'text-xs':
        case 'text-sm':
            return 'size-5'
        case 'text-base':
        case 'text-lg':
        case 'text-xl':
            return 'size-6'
        default:
            return 'size-5'
    }
})
</script>

<template>
  <div class="w-full">
    <label v-if="label" :for="id" class="block mb-1 font-medium text-gray-900 dark:text-white" :class="textSize">
      {{ label }}
    </label>

    <!-- Aplica gap-2 apenas se houver botões -->
    <div class="flex items-center w-full" :class="{ 'gap-2': hasButtons }">
      <!-- Campo de input -->
      <div class="flex-grow min-w-0">
        <div class="flex items-center w-full overflow-hidden rounded-lg px-2 bg-gray-50 dark:bg-gray-700"
          :class="[error ? 'border border-red-500 focus-within:ring-red-500' : 'border border-gray-300 dark:border-gray-600 focus-within:ring-blue-500 focus-within:border-blue-500', 'focus-within:ring-1']">
          <!-- Prepend -->
          <div class="flex items-center gap-1 select-none text-gray-700 dark:text-gray-300" :class="textSize" v-if="$slots.prepend || prepend">
            <slot name="prepend">{{ prepend }}</slot>
          </div>

          <!-- Input -->
          <component :is="mask ? 'input' : 'input'" v-if="mask" :id="id" :name="name" :type="type" :accept="accept" ref="fieldRef"
            :placeholder="placeholder" :disabled="disabled" :required="required" :maxlength="maxlength"
            :value="modelValue" @input="updateValue" v-mask="mask" autocomplete="off"
            class="w-full p-2 text-gray-900 bg-gray-50 dark:bg-gray-700 dark:text-white placeholder-gray-400 dark:placeholder-gray-400 focus:outline-none border-none"
            :class="[textSize, { 'opacity-50 cursor-not-allowed': disabled }]"/>

          <input v-else :id="id" :name="name" :type="type" :accept="accept" :placeholder="placeholder" :disabled="disabled" ref="fieldRef"
            :required="required" :maxlength="maxlength" :value="modelValue" @input="updateValue"
            autocomplete="off"
            class="w-full p-2 text-gray-900 bg-gray-50 dark:bg-gray-700 dark:text-white placeholder-gray-400 dark:placeholder-gray-400 focus:outline-none border-none"
            :class="[textSize, { 'opacity-50 cursor-not-allowed': disabled }]" :max="type === 'date' ? '9999-12-31' : null"/>

          <!-- Append -->
          <div class="flex items-center gap-1 text-gray-700 dark:text-gray-300" :class="textSize"
            v-if="$slots.append || append">
            <slot name="append">{{ append }}</slot>
          </div>
        </div>
      </div>

      <!-- Botões - só renderiza se houver botões visíveis -->
      <div v-if="hasButtons" class="flex-shrink-0 flex items-center gap-2">
        <!-- Trash -->
        <button v-if="showTrash" @click="$emit('on-trash')" type="button"
          :class="`rounded-md bg-red-600 text-white shadow-xs hover:bg-red-500 focus:outline-none focus:ring-1 focus:ring-offset-1 focus:ring-red-600 ${buttonSizeClass}`">
          <TrashIcon :class="iconSizeClass" aria-hidden="true" />
        </button>

        <!-- Clear -->
        <button v-if="showClear" @click="$emit('on-clear')" type="button"
          :class="`rounded-md bg-indigo-600 text-white shadow-xs hover:bg-indigo-500 focus:outline-none focus:ring-1 focus:ring-offset-1 focus:ring-indigo-600 ${buttonSizeClass}`">
          <BackspaceIcon :class="iconSizeClass" aria-hidden="true" />
        </button>

        <!-- Search -->
        <button v-if="showSearch" @click="$emit('on-search')" type="button"
          :class="`rounded-md bg-indigo-600 text-white shadow-xs hover:bg-indigo-500 focus:outline-none focus:ring-1 focus:ring-offset-1 focus:ring-indigo-600 ${buttonSizeClass}`">
          <MagnifyingGlassIcon :class="iconSizeClass" aria-hidden="true" />
        </button>

        <!-- Add -->
        <button v-if="showAdd" @click="$emit('on-add')" type="button"
          :class="`rounded-md bg-indigo-600 text-white shadow-xs hover:bg-indigo-500 focus:outline-none focus:ring-1 focus:ring-offset-1 focus:ring-indigo-600 ${buttonSizeClass}`">
          <PlusIcon :class="iconSizeClass" aria-hidden="true" />
        </button>
      </div>
    </div>

    <p v-if="error && errorMessage" class="mt-1 text-red-600" :class="textSize">
      {{ errorMessage }}
    </p>
  </div>
</template>