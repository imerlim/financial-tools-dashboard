<template>
    <div ref="containerRef" class="p-4 rounded-lg relative overflow-x-auto">
        <div :style="{ minWidth: minCanvasWidth + 'px', height: height + 'px' }">
            <canvas ref="canvasRef" />
        </div>

        <!-- Modal -->
        <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 max-w-sm w-full shadow-lg">
                <h2 class="text-lg font-bold mb-4 text-gray-900 dark:text-white">Detalhes do ponto</h2>
                <p class="text-gray-800 dark:text-gray-100">
                    <span class="font-semibold">Data:</span>
                    {{ modalData.label }}
                </p>
                <!-- <p class="text-gray-800 dark:text-gray-100">
                    <span class="font-semibold">Valor:</span>
                    R$
                    {{
                        modalData.value.toLocaleString('pt-BR', {
                            minimumFractionDigits: 2,
                        })
                    }}
                </p> -->
                <p class="text-gray-800 dark:text-gray-100">
                    <span class="font-semibold">Valor:</span>
                    <span v-if="formatAsCurrency">R$ {{ modalData.value.toLocaleString('pt-BR', { minimumFractionDigits: 2 }) }}</span>
                    <span v-else>
                        {{ modalData.value }}
                    </span>
                </p>
                <!-- <p v-if="modalData.datasetLabel" class="text-gray-800 dark:text-gray-100">
                    <span class="font-semibold">Filial:</span>
                    {{ modalData.datasetLabel }}
                </p> -->
                <p v-if="datasetDisplayName && modalData.datasetLabel" class="text-gray-800 dark:text-gray-100">
                    <span class="font-semibold">{{ datasetDisplayName }}:</span>
                    {{ modalData.datasetLabel }}
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
import { Chart, registerables } from 'chart.js';
import { onMounted, ref, nextTick, watch } from 'vue';
import ChartDataLabels from 'chartjs-plugin-datalabels';

Chart.register(...registerables, ChartDataLabels);

export default {
    name: 'LineChart',
    props: {
        labels: Array,
        datasets: Array,
        height: {
            type: Number,
            default: 300,
        },

        formatAsCurrency: {
            type: Boolean,
            default: false,
        },

        datasetDisplayName: {
            type: String,
            default: null,
        },
    },
    setup(props) {
        const canvasRef = ref(null);
        const containerRef = ref(null);
        const showModal = ref(false);
        const modalData = ref({
            label: '',
            value: 0,
            datasetLabel: '',
        });
        const minCanvasWidth = ref(600);

        let chartInstance = null;

        const isDark = () => typeof window !== 'undefined' && document.documentElement.classList.contains('dark');

        const scrollToEnd = () => {
            if (containerRef.value) {
                containerRef.value.scrollLeft = containerRef.value.scrollWidth;
            }
        };

        const renderChart = () => {
            if (!canvasRef.value || typeof window === 'undefined') return;
            if (chartInstance) chartInstance.destroy();

            const ctx = canvasRef.value.getContext('2d');

            chartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: props.labels,
                    datasets: props.datasets,
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    layout: {
                        padding: {
                            top: 35,
                            bottom: 35,
                            left: 50,
                            right: 50,
                        },
                    },
                    onClick: (event, elements) => {
                        if (elements.length > 0) {
                            const element = elements[0];
                            const datasetIndex = element.datasetIndex;
                            const index = element.index;

                            const label = props.labels[index];
                            const datasetLabel = props.datasets[datasetIndex].label;
                            const value = props.datasets[datasetIndex].data[index];

                            modalData.value = {
                                label,
                                value,
                                datasetLabel,
                            };
                            showModal.value = true;
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: isDark() ? '#e5e7eb' : '#374151',
                            },
                        },
                        tooltip: {
                            backgroundColor: isDark() ? '#1f2937' : '#f9fafb',
                            titleColor: isDark() ? '#f9fafb' : '#111827',
                            bodyColor: isDark() ? '#f9fafb' : '#111827',
                            callbacks: {
                                label: context => {
                                    const val = context.parsed?.y ?? 0;
                                    // return val.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
                                    if (props.formatAsCurrency) {
                                        return val.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
                                    }
                                    return val;
                                },
                            },
                        },
                        datalabels: {
                            backgroundColor: ctx => ctx.dataset.borderColor || '#3b82f6',
                            borderRadius: 4,
                            color: '#ffffff',
                            font: {
                                weight: 'bold',
                                size: 12,
                            },
                            padding: 6,
                            anchor: ctx => {
                                if (ctx.dataIndex === 0) return 'start';
                                if (ctx.dataIndex === ctx.chart.data.labels.length - 1) return 'end';
                                return 'end';
                            },
                            align: ctx => {
                                if (ctx.dataIndex === 0) return 'start';
                                if (ctx.dataIndex === ctx.chart.data.labels.length - 1) return 'end';
                                return 'top';
                            },
                            offset: 8,
                            // formatter: (value) => value.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }),
                            formatter: value => {
                                if (props.formatAsCurrency) {
                                    return value.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
                                }
                                return value;
                            },
                            display: ctx => ctx.dataset.data[ctx.dataIndex] > 0,
                        },
                    },
                    scales: {
                        x: {
                            ticks: {
                                color: isDark() ? '#d1d5db' : '#374151',
                            },
                            grid: {
                                color: isDark() ? '#374151' : '#e5e7eb',
                            },
                        },
                        y: {
                            ticks: {
                                display: false,
                            },
                            grid: {
                                drawTicks: false,
                                display: false,
                            },
                        },
                    },
                    elements: {
                        point: {
                            radius: 6,
                            hoverRadius: 8,
                        },
                        line: {
                            borderWidth: 3,
                        },
                    },
                },
            });

            nextTick(() => {
                scrollToEnd();
            });
        };

        const observeDarkMode = () => {
            const observer = new MutationObserver(() => {
                renderChart();
            });

            observer.observe(document.documentElement, {
                attributes: true,
                attributeFilter: ['class'],
            });
        };

        onMounted(async () => {
            await nextTick();
            setTimeout(() => {
                if (props.labels.length > 0) {
                    minCanvasWidth.value = props.labels.length * 80;
                }
                renderChart();
                observeDarkMode();
            }, 100);
        });

        watch([() => props.labels, () => props.datasets], () => {
            if (props.labels.length > 0 && props.datasets.length > 0) {
                minCanvasWidth.value = props.labels.length * 80;
                renderChart();
            }
        });

        return {
            canvasRef,
            containerRef,
            showModal,
            modalData,
            minCanvasWidth,
        };
    },
};
</script>
