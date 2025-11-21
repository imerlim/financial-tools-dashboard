<template>
    <div class="w-full h-96">
        <Bar ref="barChart" :key="chartKey" :data="chartData" :options="chartOptions" />

        <div v-if="showModal && modalOnClick" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 max-w-sm w-full shadow-lg">
                <h2 class="text-lg font-bold mb-4 text-gray-900 dark:text-white">{{ modalTitle }}</h2>
                <p class="text-gray-800 dark:text-gray-100">
                    <span class="font-semibold">{{ modalLabelName }}:</span>
                    {{ modalData.fullLabel }}
                </p>

                <p class="text-gray-800 dark:text-gray-100">
                    <span class="font-semibold">{{ modalValueName }}:</span>
                    {{ formattedModalValue }}
                </p>

                <div class="mt-4 text-right">
                    <button class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700" @click="showModal = false">
                        Fechar
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { Bar } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js';

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale);
import ChartDataLabels from 'chartjs-plugin-datalabels';
ChartJS.register(ChartDataLabels);

export default {
    name: 'BarChart',

    components: { Bar },

    props: {
        title: { type: String, required: false },

        labels: { type: Array, required: true },

        fullLabels: {
            type: Array,
            required: false,
            default: () => [],
        },

        datasets: {
            type: Array,
            required: false,
            default: () => [],
        },

        datasetLabel: { type: String, default: 'Dados' },
        data: {
            type: Array,
            default: () => [],
        },
        backgroundColor: {
            type: [String, Array],
            default: '#3b82f6',
        },

        autoColor: { type: Boolean, default: true },

        valueFormatter: {
            type: Function,
            default: value => value,
        },

        modalOnClick: {
            type: Boolean,
            default: true,
        },
        modalTitle: {
            type: String,
            default: 'Detalhes',
        },
        modalLabelName: {
            type: String,
            default: 'Label',
        },
        modalValueName: {
            type: String,
            default: 'Valor',
        },
    },

    data() {
        return {
            isDarkMode: false,
            chartKey: 0,
            showModal: false,
            modalData: {
                label: '',
                fullLabel: '',
                value: 0,
            },
        };
    },

    computed: {
        usingMulti() {
            return this.datasets && this.datasets.length > 0;
        },

        chartData() {
            if (this.usingMulti) {
                return {
                    labels: this.labels,
                    datasets: this.normalizeDatasets(this.datasets),
                };
            }

            return {
                labels: this.labels,
                datasets: [
                    {
                        label: this.datasetLabel,
                        data: this.data,
                        backgroundColor: this.backgroundColor,
                        borderRadius: 6,
                        barThickness: 15,
                    },
                ],
            };
        },

        chartOptions() {
            const dark = this.isDarkMode;
            return {
                responsive: true,
                maintainAspectRatio: false,
                onClick: this.modalOnClick ? this.handleChartClick : null,
                layout: {
                    padding: { top: 10, bottom: 10 },
                },
                plugins: {
                    legend: {
                        display: false,
                    },
                    datalabels: {
                        display: false,
                    },
                    tooltip: {
                        backgroundColor: dark ? '#1f2937' : '#f9fafb',
                        titleColor: dark ? '#f9fafb' : '#111827',
                        bodyColor: dark ? '#f9fafb' : '#111827',
                        borderColor: dark ? '#e5e7eb' : '#1f2937',
                        borderWidth: 1,
                        callbacks: {
                            title: context => {
                                const index = context[0].dataIndex;
                                if (this.fullLabels && this.fullLabels.length > index) {
                                    return this.fullLabels[index];
                                }
                                return this.labels[index];
                            },
                            label: context => {
                                const label = context.dataset.label || '';
                                const value = this.valueFormatter(context.raw);
                                return label ? `${label}: ${value}` : value;
                            },
                        },
                    },
                    title: {
                        display: !!this.title,
                        text: this.title,
                        color: dark ? '#f3f4f6' : '#111827',
                        font: {
                            size: 20,
                            weight: 'bold',
                            family: 'Figtree',
                        },
                    },
                },
                scales: {
                    x: {
                        ticks: {
                            color: dark ? '#ffffff' : '#333',
                        },
                        grid: {
                            color: dark ? '#555' : '#ddd',
                        },
                    },
                    y: {
                        ticks: {
                            color: dark ? '#ffffff' : '#333',
                            callback: value => this.valueFormatter(value),
                        },
                        grid: {
                            color: dark ? '#555' : '#ddd',
                        },
                    },
                },
            };
        },

        formattedModalValue() {
            return this.valueFormatter(this.modalData.value);
        },
    },

    watch: {
        isDarkMode() {
            this.chartKey++;
        },
    },

    created() {
        this.isDarkMode = document?.documentElement?.classList?.contains('dark') ?? false;
    },

    mounted() {
        requestAnimationFrame(() => {
            this.syncDarkMode();
        });

        this.observeThemeChange();
    },

    methods: {
        handleChartClick(event) {
            if (!this.modalOnClick) return;

            const chart = this.$refs.barChart?.chart;
            if (!chart) {
                return;
            }

            const elements = chart.getElementsAtEventForMode(event, 'nearest', { intersect: true }, true);

            if (elements.length === 0) {
                return;
            }

            const firstElement = elements[0];
            const index = firstElement.index;
            const datasetIndex = firstElement.datasetIndex;

            const label = this.labels[index];

            const fullLabel = this.fullLabels && this.fullLabels.length > index ? this.fullLabels[index] : label;

            const value = this.chartData.datasets[datasetIndex].data[index];

            this.modalData = {
                label: label,
                fullLabel: fullLabel,
                value: value,
            };

            this.showModal = true;
        },

        syncDarkMode() {
            const newVal = document.documentElement.classList.contains('dark');
            if (newVal !== this.isDarkMode) {
                this.isDarkMode = newVal;
            }
        },

        observeThemeChange() {
            const observer = new MutationObserver(() => {
                this.syncDarkMode();
            });
            observer.observe(document.documentElement, {
                attributes: true,
                attributeFilter: ['class'],
            });
        },

        gerarCorAleatoria() {
            const h = Math.floor(Math.random() * 360);
            return `hsl(${h} 70% 60%)`;
        },

        normalizeDatasets(ds) {
            return ds.map(d => ({
                borderRadius: 6,
                barThickness: 15,
                backgroundColor: d.backgroundColor ?? (this.autoColor ? this.gerarCorAleatoria() : this.backgroundColor),
                ...d,
            }));
        },
    },
};
</script>
