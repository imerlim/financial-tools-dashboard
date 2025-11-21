<template>
    <div class="w-full h-96">
        <Bar ref="barChart" :key="chartKey" :data="chartData" :options="chartOptions" />
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
    },

    data() {
        return {
            isDarkMode: false,
            chartKey: 0,
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
                indexAxis: 'y', // barras horizontais
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
                            size: 15,
                            weight: 'bold',
                            family: 'Figtree',
                        },
                    },
                },
                scales: {
                    x: {
                        ticks: {
                            color: dark ? '#ffffff' : '#333',
                            callback: value => this.valueFormatter(value),
                        },
                        grid: {
                            color: dark ? '#555' : '#ddd',
                        },
                    },
                    y: {
                        ticks: {
                            color: dark ? '#ffffff' : '#333',
                        },
                        grid: {
                            color: dark ? '#555' : '#ddd',
                        },
                    },
                },
            };
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
