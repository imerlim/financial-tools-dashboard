<script setup>
import { computed } from 'vue';

const props = defineProps({
    modelValue: { type: [String, Number, Boolean, Object, null], default: null },
    id: { type: String, required: true },
    type: { type: String, default: 'checkbox' },
    name: String,
    label: { type: String, required: true },
    description: String,
    tooltip: String,
    value: { type: [String, Number, Boolean, Object], default: true },
    uncheckedValue: { type: [String, Number, Boolean, Object], default: false },
    disabled: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(['update:modelValue', 'change']);

const isChecked = computed(() => {
    if (typeof props.value === 'boolean') {
        return props.modelValue === props.value;
    }

    return String(props.modelValue) === String(props.value);
});

function onChange(event) {
    const checked = event.target.checked;
    const out = checked ? props.value : props.uncheckedValue;
    emit('update:modelValue', out);
    emit('change', out);
}
</script>

<template>
    <div class="flex items-start gap-3">
        <div class="flex items-center h-5 relative group">
            <input :id="id" :name="name" :type="type" :checked="isChecked" :title="tooltip" :disabled="disabled" class="cursor-pointer h-4 w-4 rounded border-slate-300 text-sky-600 focus:ring-sky-500 dark:bg-slate-800 dark:border-slate-600 dark:checked:bg-sky-600 dark:checked:border-sky-600" @change="onChange" />
        </div>

        <div class="text-base leading-6">
            <label :for="id" class="font-medium text-slate-900 dark:text-white">
                {{ label }}
            </label>
            <p v-if="description" class="text-slate-500 dark:text-slate-400">
                {{ description }}
            </p>
        </div>
    </div>
</template>
