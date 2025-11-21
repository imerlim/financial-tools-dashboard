<template>
    <div class="w-full max-w-full h-80 sm:h-96 md:h-[28rem] lg:h-[32rem] p-2 sm:p-4">
        <Pie :key="redrawKey" :data="chartData" :options="getChartOptions()" />
    </div>
</template>

<script>
import { Pie } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, ArcElement } from 'chart.js';

ChartJS.register(Title, Tooltip, Legend, ArcElement);

const centerTextPlugin = {
    id: 'centerText',
    beforeDraw(chart) {
        const { width, height, ctx, options } = chart;
        const centerTextOpts = options.plugins.centerText || {};

        const value = centerTextOpts.value || '';
        const label = centerTextOpts.label || '';
        const color =
            centerTextOpts.color ||
            (document.documentElement.classList.contains('dark') ? 'rgba(243, 244, 246, 0.92)' : 'rgba(31, 41, 55, 0.92)');

        ctx.save();
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        ctx.fillStyle = color;

        const fontSizeValue = Math.min(width, height) * 0.07;
        ctx.font = `700 ${fontSizeValue}px 'Figtree', sans-serif`;
        ctx.fillText(value, width / 2, height / 2 - fontSizeValue * 0.15);

        if (label) {
            const fontSizeLabel = Math.min(width, height) * 0.035;
            ctx.font = `500 ${fontSizeLabel}px 'Figtree', sans-serif`;
            ctx.fillText(label, width / 2, height / 2 + fontSizeLabel * 1.2);
        }

        ctx.restore();
    },
};

ChartJS.register(centerTextPlugin);

export default {
    name: 'DonutChart',

    components: { Pie },

    props: {
        title: String,
        labels: Array,
        series: Array,
        backgroundColor: {
            type: Array,
            default: () => [],
        },
        centerTextValue: { type: String, default: '' },
        centerTextLabel: { type: String, default: '' },
    },

    data() {
        return {
            isDarkMode: false,
            redrawKey: 0,
        };
    },

    computed: {
        chartData() {
            return {
                labels: this.labels,
                datasets: [
                    {
                        data: this.series,
                        backgroundColor: this.normalizeBackgroundColors(),
                        borderColor: '#ffffff',
                        borderWidth: 2,
                        cutout: '80%',
                    },
                ],
            };
        },
    },

    watch: {
        isDarkMode() {
            this.redrawKey++;
        },
    },

    created() {
        this.isDarkMode = document.documentElement.classList.contains('dark');
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

        normalizeBackgroundColors() {
            if (Array.isArray(this.backgroundColor) && this.backgroundColor.length >= this.series.length) {
                return this.backgroundColor;
            }
            return this.series.map(() => this.gerarCorAleatoria());
        },

        getChartOptions() {
            const dark = this.isDarkMode;
            const textColor = dark ? '#f3f4f6' : '#1f2937';
            const tooltipBg = dark ? '#1f2937' : '#f9fafb';
            const tooltipText = dark ? '#f9fafb' : '#111827';

            return {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    },
                    tooltip: {
                        backgroundColor: tooltipBg,
                        titleColor: tooltipText,
                        bodyColor: tooltipText,
                        padding: 10,
                        cornerRadius: 4,
                        callbacks: {
                            label: context => {
                                const label = context.label || '';
                                const value = context.parsed || 0;
                                const formatted = 'R$ ' + value.toLocaleString('pt-BR', { minimumFractionDigits: 2 });
                                return `${label}: ${formatted}`;
                            },
                        },
                    },
                    title: {
                        display: !!this.title,
                        text: this.title,
                        color: textColor,
                        font: {
                            size: 18,
                            weight: '600',
                            family: 'Figtree',
                        },
                        padding: {
                            top: 10,
                            bottom: 20,
                        },
                    },
                    centerText: {
                        value: this.centerTextValue,
                        label: this.centerTextLabel,
                        color: textColor,
                    },
                },
            };
        },
    },
};
</script>
