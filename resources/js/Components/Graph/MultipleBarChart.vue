<template>
    <div class="w-full h-96 p-4">
        <Bar :data="chartData" :options="chartOptions" />
    </div>
</template>

<script>
import { Bar } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js';

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale);

export default {
    name: 'MultipleBarChart',

    components: {
        Bar,
    },

    props: {
        title: {
            type: String,
            required: false,
        },
        labels: {
            type: Array,
            required: true,
        },
        datasets: {
            type: Array,
            required: true,
        },
    },

    data() {
        return {
            isDarkMode: document.documentElement.classList.contains('dark'),
        };
    },

    computed: {
        chartData() {
            return {
                labels: this.labels,
                datasets: this.datasets.map(ds => ({
                    ...ds,
                    borderRadius: 6,
                    barThickness: 15,
                })),
            };
        },

        chartOptions() {
            const dark = this.isDarkMode;
            return {
                responsive: true,
                maintainAspectRatio: false,
                layout: {
                    padding: { top: 10, bottom: 10 },
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            color: dark ? '#f3f4f6' : '#4b5563',
                            font: {
                                family: 'Figtree',
                                size: 14,
                            },
                        },
                    },
                    tooltip: {
                        backgroundColor: dark ? '#1f2937' : '#f9fafb',
                        titleColor: dark ? '#f9fafb' : '#111827',
                        bodyColor: dark ? '#f9fafb' : '#111827',
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
                            color: this.isDarkMode ? '#ffffff' : '#333',
                        },
                        grid: {
                            color: this.isDarkMode ? '#555' : '#ddd',
                        },
                    },
                    y: {
                        ticks: {
                            color: this.isDarkMode ? '#ffffff' : '#333',
                        },
                        grid: {
                            color: this.isDarkMode ? '#555' : '#ddd',
                        },
                    },
                },
            };
        },
    },

    mounted() {
        this.observeThemeChange();
    },

    methods: {
        observeThemeChange() {
            const observer = new MutationObserver(() => {
                this.isDarkMode = document.documentElement.classList.contains('dark');
            });
            observer.observe(document.documentElement, {
                attributes: true,
                attributeFilter: ['class'],
            });
        },
    },
};
</script>
