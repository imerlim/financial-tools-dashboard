<template>
    <div class="bg-gray-50 dark:bg-gray-800 px-4 py-10 sm:px-6 lg:px-8 rounded-lg shadow-md">
        <div class="flex items-start justify-between">
            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                {{ title }}
            </dt>

            <tooltip v-if="indicatorText" :text="tooltip || ''">
                <dd :class="[indicatorColor || 'text-gray-700 dark:text-gray-300', 'text-xs font-medium']">
                    {{ indicatorText }}
                </dd>
            </tooltip>
        </div>

        <div class="flex items-baseline gap-2 mt-2">
            <dd class="text-4xl font-semibold tracking-tight text-gray-900 dark:text-white">
                {{ value }}
            </dd>

            <span v-if="legend" class="text-sm text-gray-500 dark:text-gray-400 font-medium">
                {{ legend }}
            </span>
        </div>
    </div>
</template>

<script>
export default {
    name: 'Indicadores',

    props: {
        title: { type: String, required: true },
        value: { type: [String, Number], required: true },
        indicatorText: { type: String, default: null },
        indicatorColor: { type: String, default: null },
        legend: { type: String, default: null },
        tooltip: { type: String, default: null },
    },
};
</script>
