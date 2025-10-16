<template>
  <div class="w-full">
    <label
      v-if="label"
      :for="id"
      class="block mb-1 font-medium text-gray-900 dark:text-white"
      :class="textSize"
    >
      {{ label }}
    </label>

    <textarea
      :id="id"
      :name="name"
      :placeholder="placeholder"
      :disabled="disabled"
      :required="required"
      :maxlength="maxlength"
      :value="modelValue"
      @input="updateValue"
      class="block w-full p-2 text-gray-900 bg-gray-50 dark:bg-gray-700 dark:text-white 
             placeholder-gray-400 dark:placeholder-gray-400 
             border border-gray-300 dark:border-gray-600 
             rounded-lg focus:ring-blue-500 focus:border-blue-500 
             focus:outline-none"
      :class="[textSize, { 'opacity-50 cursor-not-allowed': disabled }]"
      :rows="rows"
    ></textarea>

    <p
      v-if="helper"
      class="mt-1 text-gray-500 dark:text-gray-400"
      :class="textSize"
    >
      {{ helper }}
    </p>
  </div>
</template>

<script setup>
defineProps({
  modelValue: String,
  label: String,
  id: String,
  name: String,
  rows: Number,
  placeholder: String,
  disabled: Boolean,
  required: Boolean,
  maxlength: [Number, String],
  helper: String,
  textSize: {
    type: String,
    default: 'text-base',
  },
})

const emit = defineEmits(['update:modelValue'])

function updateValue(event) {
  emit('update:modelValue', event.target.value)
}
</script>
