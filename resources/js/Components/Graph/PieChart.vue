<template>
    <div class="w-full h-96 relative">
        <Pie ref="pieChart" :key="chartKey" :data="chartData" :options="chartOptions" />

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
import { Pie } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, ArcElement } from 'chart.js';

import ChartDataLabels from 'chartjs-plugin-datalabels';

ChartJS.register(Title, Tooltip, Legend, ArcElement, ChartDataLabels);

export default {
    name: 'PieChart',
    components: { Pie },

    props: {
        labels: Array,
        series: Array,
        title: String,
        showCurrency: {
            type: Boolean,
            default: false,
        },
        desabilitaDisplay: {
            type: Boolean,
            default: false,
        },

        fullLabels: {
            type: Array,
            default: () => [],
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
                value: 0,
                fullLabel: '',
            },
            generatedColors: [],
            fixedColors: ['#024400', '#10B981', '#F59E0B', '#EF4444', '#6366F1'],
        };
    },

    computed: {
        chartData() {
            const total = this.series.reduce((acc, val) => acc + val, 0);

            return {
                labels: this.labels,
                datasets: [
                    {
                        data: this.series,
                        // backgroundColor: this.gerarCoresAleatorias(this.series.length),
                        backgroundColor: this.fixedColors.slice(0, this.series.length),
                        borderColor: '#ffffff',
                        borderWidth: 2,
                    },
                ],
                total,
            };
        },

        formattedModalValue() {
            const value = parseFloat(this.modalData.value) || 0;
            const prefixo = this.showCurrency ? 'R$ ' : '';
            const valorFormatado = value.toLocaleString('pt-BR');
            return `${prefixo}${valorFormatado}`;
        },

        chartOptions() {
            const showCurrency = this.showCurrency;
            const dark = this.isDarkMode;

            return {
                responsive: true,
                onClick: this.modalOnClick ? this.handleChartClick : null,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: {
                            color: dark ? '#f3f4f6' : '#444',
                            padding: 20,
                            font: {
                                size: 12,
                                family: 'Arial',
                                weight: 'bold',
                            },
                        },
                    },
                    title: {
                        display: !!this.title,
                        text: this.title,
                        font: { size: 18 },
                        color: dark ? '#f3f4f6' : '#333',
                        padding: {
                            top: 10,
                            bottom: 20,
                        },
                    },
                    tooltip: {
                        backgroundColor: dark ? '#1f2937' : '#f9fafb',
                        titleColor: dark ? '#f9fafb' : '#111827',
                        bodyColor: dark ? '#f9fafb' : '#111827',
                        borderColor: dark ? '#e5e7eb' : '#1f2937',
                        borderWidth: 1,
                        callbacks: {
                            label: context => {
                                const value = parseFloat(context.raw) || 0;
                                const total = context.dataset.data.reduce((acc, val) => {
                                    const numericValue = parseFloat(val) || 0;
                                    return acc + numericValue;
                                }, 0);

                                const percent = total > 0 ? ((value / total) * 100).toFixed(2) : 0;

                                const prefixo = showCurrency ? 'R$ ' : '';
                                const valorFormatado = value.toLocaleString('pt-BR');

                                return `${context.label}: ${prefixo}${valorFormatado} (${percent}%)`;
                            },
                        },
                    },

                    datalabels: {
                        display: !this.desabilitaDisplay,
                        color: dark ? '#ffffff' : '#333333',
                        font: {
                            weight: 'bold',
                        },
                        formatter: value => {
                            const valor = parseFloat(value) || 0;
                            const prefixo = showCurrency ? 'R$ ' : '';
                            return `${prefixo}${valor.toLocaleString('pt-BR')}`;
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
        handleChartClick(event) {
            if (!this.modalOnClick) return;

            const chart = this.$refs.pieChart?.chart;
            if (!chart) {
                return;
            }

            const elements = chart.getElementsAtEventForMode(event, 'nearest', { intersect: true }, true);

            if (elements.length === 0) {
                return;
            }

            const firstElement = elements[0];
            const index = firstElement.index;

            const label = this.labels[index];
            const value = this.series[index];

            const fullLabel = this.fullLabels && this.fullLabels.length > index ? this.fullLabels[index] : label;

            this.modalData = {
                label: label,
                value: value,
                fullLabel: fullLabel,
            };

            this.showModal = true;
        },

        gerarCorAleatoria() {
            const letras = '0123456789ABCDEF';
            let cor = '#';
            for (let i = 0; i < 6; i++) {
                cor += letras[Math.floor(Math.random() * 16)];
            }
            return cor;
        },

        gerarCoresAleatorias(qtd) {
            return Array.from({ length: qtd }, () => this.gerarCorAleatoria());
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
    },
};
</script>

<style>
.tes {
    background-color: #024400;
}
</style>
