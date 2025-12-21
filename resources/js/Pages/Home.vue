<script setup>
import { onMounted } from 'vue';
import { animate, svg } from 'https://esm.sh/animejs';

import { gsap } from 'gsap';
import { ref } from 'vue';
import GoldenSpiral from '../Components/Animations/GoldenSpiral.vue';

// Import Swiper Vue.js components
import { Swiper, SwiperSlide } from 'swiper/vue';

// Import Swiper styles
import 'swiper/css';

import 'swiper/css/zoom';
import 'swiper/css/effect-cube';
import 'swiper/css/pagination';
import 'swiper/css/navigation';

// import required modules
import { EffectCube, Pagination, Navigation, Autoplay, Zoom } from 'swiper/modules';

const svgContainer = ref(null);
const modules = [EffectCube, Pagination, Navigation, Autoplay, Zoom];
const activeIndex = ref(0);
const onSlideChange = swiper => {
    activeIndex.value = swiper.realIndex;
};
const slides = [
    {
        image: '/storage/consolidated_sales_OLD.jpg',
        title: 'BI & Interactive Dashboards',
        tags: ['Laravel 7', 'Vue.js 2', 'Highcharts', 'PHP 7.4', 'MySQL'],
        description:
            'Development of consolidated sales dashboards using Highcharts for real-time data visualization, with a robust backend built on Laravel 7.',
    },
    {
        image: '/storage/sales_agenda_old.jpeg',
        title: 'Scheduling Management',
        tags: ['Laravel 7', 'Vue.js 2', 'Highcharts', 'PHP 7.4', 'MySQL', 'FullCalendar'],
        description:
            'Implementation of a complex commercial scheduling system using FullCalendar, enabling dynamic event management and integration with the company’s CRM workflow.',
    },
    {
        image: '/storage/product_registration_old.jpg',
        title: 'Product Registration UX',
        tags: ['Laravel 7', 'Vue.js 2', 'PHP 7.4', 'MySQL'],
        description:
            'Dense and reactive product registration interface, optimized with Bootstrap-Vue to ensure agility in the entry of technical and tax-related data.',
    },
    {
        image: '/storage/product_registration_new.jpeg',
        title: 'Redesign Product Registration',
        tags: ['Laravel 12', 'Vue.js 3', 'SPA', 'Inertia.js', 'Tailwind CSS', 'PHP 8.4', 'MySQL', 'State Management'],
        description:
            'A high-performance SPA redesign of the core product management module. Engineered with Inertia.js to handle complex tax and technical data without page refreshes, featuring a streamlined Tailwind CSS interface optimized for administrative efficiency.',
    },
    {
        image: '/storage/nuvemshop_old.jpeg',
        title: 'API Nuvemshop Integration',
        tags: ['Laravel 7', 'Vue.js 2', 'Highcharts', 'PHP 7.4', 'MySQL', 'Trumbowyg'],
        description:
            'Creation of an integration dashboard with NuvemShop, automating the synchronization of orders and inventory between the ERP and the e-commerce platform.',
    },
    {
        image: '/storage/nuvemshop_new.jpeg',
        title: 'Redesign API Nuvemshop Integration',
        tags: ['Laravel 12', 'Vue.js 3', 'SPA', 'Inertia.js', 'Tailwind CSS', 'PHP 8.4', 'MySQL', 'Chart.js', 'Real-time Sync'],
        description:
            'A high-performance SPA redesign of the NuvemShop integration. Leverages Inertia.js for seamless state management, automating real-time synchronization of orders and inventory between the ERP and e-commerce platforms with a modern Tailwind CSS interface.',
    },
    {
        image: '/storage/mercado_livre_new.jpeg',
        title: 'Marketplace Integration: Mercado Livre',
        tags: ['Laravel 12', 'Vue.js 3', 'SPA', 'Inertia.js', 'Tailwind CSS', 'PHP 8.4', 'MySQL', 'REST API'],
        description:
            'A sophisticated marketplace integration allowing users to manage Mercado Livre listings directly from the ERP. Features include real-time order tracking and a specialized module to publish new product announcements, bridging internal inventory with the e-commerce ecosystem.',
    },
    {
        image: '/storage/tree_view_new.png',
        title: 'Recursive Data Architecture',
        tags: ['Laravel 12', 'Vue.js 3', 'SPA', 'Inertia.js', 'Tailwind CSS', 'PHP 8.4', 'MySQL', 'Recursive Logic'],
        description:
            'Implementation of a dynamic, infinite-level category tree for product grouping. Developed using recursive Vue.js components and optimized Eloquent queries, allowing users to manage complex hierarchical data structures with real-time updates and seamless state management.',
    },
    {
        image: '/storage/stock_adjustment_new.png',
        title: 'Precision Inventory Control',
        tags: ['Laravel 12', 'Vue.js 3', 'SPA', 'Inertia.js', 'Tailwind CSS', 'PHP 8.4', 'MySQL', 'Batch Updates'],
        description:
            'A high-efficiency stock adjustment module designed for rapid data entry. Features real-time product lookups and batch update capabilities, ensuring inventory synchronization with minimal administrative overhead.',
    },
    {
        image: '/storage/financial_management_new.png',
        title: 'High-Volume Financial Processing',
        tags: ['Laravel 12', 'Vue.js 3', 'SPA', 'Inertia.js', 'Tailwind CSS', 'PHP 8.4', 'MySQL', 'Pagination Engine'],
        description:
            'A sophisticated financial management component engineered to process massive datasets. Features an optimized server-side pagination engine that fetches records dynamically, allowing seamless navigation through thousands of financial documents with zero browser latency or performance degradation.',
    },
    {
        image: '/storage/margin_calculator_new.png',
        title: 'Financial Margin Engine',
        tags: ['Laravel 12', 'Vue.js 3', 'SPA', 'Inertia.js', 'Tailwind CSS', 'PHP 8.4', 'Reactive UI'],
        description:
            'A specialized pricing tool designed to calculate profit margins based on either cost or selling price. Built with a reactive interface that provides instant updates on Net Margin and Profit, allowing sales teams to make data-driven pricing decisions with precision.',
    },
    {
        image: '/storage/interest_calculator_new.png',
        title: 'Wealth Projection Engine',
        tags: ['Laravel 12', 'Vue.js 3', 'SPA', 'Inertia.js', 'Tailwind CSS', 'PHP 8.4', 'Financial Modeling'],
        description:
            'A sophisticated compound interest tool designed to estimate long-term wealth accumulation. Features a dynamic interface that calculates future value based on initial capital, monthly contributions, and variable interest rates, providing users with precise financial forecasting.',
    },
    {
        image: '/storage/financial_control_new.png',
        title: 'Secure Financial Analytics',
        tags: ['Laravel 12', 'Vue.js 3', 'SPA', 'Inertia.js', 'Breeze/Fortify', 'Chart.js', 'PHP 8.4'],
        description:
            'A secure, authenticated financial dashboard for comprehensive data control. This module features protected routes and user-level permissions, integrating real-time transaction tables with Chart.js visualizations to identify best and least-selling categories for strategic business planning.',
    },
];

onMounted(() => {
    const drawable = svg.createDrawable('.animated-svg path');

    animate(drawable, {
        // draw: ['0 0', '0 1', '1 1'],
        draw: ['0 0', '0 1'],
        ease: 'inOutQuad',
        duration: 8888,
        // loop: true,
    });

    const tl = gsap.timeline();

    tl.from('.js-title', {
        duration: 3,
        y: 30,
        opacity: 0,
        ease: 'power2.out',
    });

    tl.from(
        '.js-subtitle',
        {
            duration: 1.2,
            y: 20,
            opacity: 0,
            ease: 'power2.out',
        },
        '-=1.2'
    );
});
</script>

<template>
    <Head title="Igor Merlim" />
    <div class="w-full">
        <div class="relative w-full h-screen overflow-hidden scroll-snap-start bg-slate-100 dark:bg-slate-900">
            <GoldenSpiral class="absolute inset-0 z-10"></GoldenSpiral>

            <div class="text-center mt-32 relative z-20">
                <svg
                    ref="svgContainer"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 9.470000267028809 242.13999938964844 46.31999969482422"
                    data-asc="0.9052734375"
                    width="242"
                    height="46"
                    class="animated-svg mx-auto block"
                >
                    <defs />
                    <g fill="#ffffff">
                        <g transform="translate(0, 0)">
                            <path
                                d="M0 45.26L0 9.47L4.74 9.47L4.74 45.26L0 45.26ZM11.72 47.41L15.99 48.05Q16.26 50.02 17.48 50.93Q19.12 52.15 21.95 52.15Q25 52.15 26.66 50.93Q28.32 49.71 28.91 47.51Q29.25 46.17 29.22 41.87Q26.34 45.26 22.05 45.26Q16.70 45.26 13.77 41.41Q10.84 37.55 10.84 32.15Q10.84 28.44 12.18 25.31Q13.53 22.17 16.08 20.46Q18.63 18.75 22.07 18.75Q26.66 18.75 29.64 22.46L29.64 19.34L33.69 19.34L33.69 41.75Q33.69 47.80 32.46 50.33Q31.23 52.86 28.55 54.32Q25.88 55.79 21.97 55.79Q17.33 55.79 14.48 53.70Q11.62 51.61 11.72 47.41M15.36 31.84Q15.36 36.94 17.38 39.28Q19.41 41.63 22.46 41.63Q25.49 41.63 27.54 39.29Q29.59 36.96 29.59 31.98Q29.59 27.22 27.48 24.80Q25.37 22.39 22.39 22.39Q19.46 22.39 17.41 24.77Q15.36 27.15 15.36 31.84ZM38.70 32.30Q38.70 25.10 42.70 21.63Q46.04 18.75 50.85 18.75Q56.20 18.75 59.59 22.25Q62.99 25.76 62.99 31.93Q62.99 36.94 61.49 39.81Q59.99 42.68 57.12 44.26Q54.25 45.85 50.85 45.85Q45.41 45.85 42.05 42.36Q38.70 38.87 38.70 32.30M43.21 32.30Q43.21 37.28 45.39 39.76Q47.56 42.24 50.85 42.24Q54.13 42.24 56.30 39.75Q58.47 37.26 58.47 32.15Q58.47 27.34 56.29 24.87Q54.10 22.39 50.85 22.39Q47.56 22.39 45.39 24.85Q43.21 27.32 43.21 32.30ZM68.09 45.26L68.09 19.34L72.05 19.34L72.05 23.27Q73.56 20.51 74.84 19.63Q76.12 18.75 77.66 18.75Q79.88 18.75 82.18 20.17L80.66 24.24Q79.05 23.29 77.44 23.29Q76.00 23.29 74.85 24.16Q73.71 25.02 73.22 26.56Q72.49 28.91 72.49 31.69L72.49 45.26L68.09 45.26ZM99.10 45.26L99.10 9.47L106.23 9.47L114.70 34.81Q115.87 38.35 116.41 40.11Q117.02 38.16 118.31 34.38L126.88 9.47L133.25 9.47L133.25 45.26L128.69 45.26L128.69 15.31L118.29 45.26L114.01 45.26L103.66 14.79L103.66 45.26L99.10 45.26ZM158.08 36.91L162.62 37.48Q161.55 41.46 158.64 43.65Q155.74 45.85 151.22 45.85Q145.53 45.85 142.20 42.35Q138.87 38.84 138.87 32.52Q138.87 25.98 142.24 22.36Q145.61 18.75 150.98 18.75Q156.18 18.75 159.47 22.29Q162.77 25.83 162.77 32.25Q162.77 32.64 162.74 33.42L143.41 33.42Q143.65 37.70 145.83 39.97Q148.00 42.24 151.25 42.24Q153.66 42.24 155.37 40.97Q157.08 39.70 158.08 36.91M143.65 29.81L158.13 29.81Q157.84 26.54 156.47 24.90Q154.37 22.36 151.03 22.36Q148.00 22.36 145.94 24.39Q143.87 26.42 143.65 29.81ZM168.09 45.26L168.09 19.34L172.05 19.34L172.05 23.27Q173.56 20.51 174.84 19.63Q176.12 18.75 177.66 18.75Q179.88 18.75 182.18 20.17L180.66 24.24Q179.05 23.29 177.44 23.29Q176.00 23.29 174.85 24.16Q173.71 25.02 173.22 26.56Q172.49 28.91 172.49 31.69L172.49 45.26L168.09 45.26ZM184.69 45.26L184.69 9.47L189.09 9.47L189.09 45.26L184.69 45.26ZM195.92 14.53L195.92 9.47L200.32 9.47L200.32 14.53L195.92 14.53M195.92 45.26L195.92 19.34L200.32 19.34L200.32 45.26L195.92 45.26ZM207.01 45.26L207.01 19.34L210.94 19.34L210.94 22.97Q212.16 21.07 214.18 19.91Q216.21 18.75 218.80 18.75Q221.68 18.75 223.52 19.95Q225.37 21.14 226.12 23.29Q229.20 18.75 234.13 18.75Q237.99 18.75 240.06 20.89Q242.14 23.02 242.14 27.47L242.14 45.26L237.77 45.26L237.77 28.93Q237.77 26.29 237.34 25.13Q236.91 23.97 235.79 23.27Q234.67 22.56 233.15 22.56Q230.42 22.56 228.61 24.38Q226.81 26.20 226.81 30.20L226.81 45.26L222.41 45.26L222.41 28.42Q222.41 25.49 221.34 24.02Q220.26 22.56 217.82 22.56Q215.97 22.56 214.39 23.54Q212.82 24.51 212.11 26.39Q211.40 28.27 211.40 31.81L211.40 45.26L207.01 45.26Z"
                            />
                        </g>
                    </g>
                </svg>
                <p class="js-subtitle text-lg mt-4 text-white">
                    Full-Stack Developer. Building scalable solutions and modern architecture.
                </p>
            </div>
        </div>

        <section
            class="relative w-full min-h-screen lg:h-screen overflow-y-auto lg:overflow-hidden scroll-snap-start bg-slate-100 dark:bg-slate-900 lg:py-0 flex items-center"
        >
            <div class="mx-auto max-w-2xl px-6 lg:max-w-7xl lg:px-8 grid grid-cols-1 lg:grid-cols-3 gap-y-8 lg:gap-x-12 items-center">
                <div class="lg:col-span-3">
                    <h2 class="text-balance text-center text-3xl font-semibold tracking-tight text-slate-950 sm:text-5xl dark:text-white">
                        Professional Experience
                    </h2>
                </div>

                <div class="lg:col-span-2 w-full px-4 lg:px-0">
                    <swiper
                        :slidesPerView="1"
                        :style="{
                            '--swiper-navigation-color': '#00649e',
                            '--swiper-pagination-color': '#00649e',
                        }"
                        :effect="'cube'"
                        :zoom="true"
                        :grabCursor="true"
                        :loop="true"
                        :autoplay="{ delay: 15000, disableOnInteraction: false }"
                        :cubeEffect="{ shadow: true, slideShadows: true, shadowOffset: 20, shadowScale: 0.94 }"
                        :navigation="true"
                        :pagination="{ clickable: true }"
                        :modules="modules"
                        @slideChange="onSlideChange"
                        class="rounded-lg shadow-2xl"
                    >
                        <swiper-slide v-for="(slide, index) in slides" :key="index" class="bg-slate-600/40">
                            <div class="swiper-zoom-container">
                                <img :src="slide.image" class="rounded-lg w-full h-auto object-contain" />
                            </div>
                        </swiper-slide>
                    </swiper>
                </div>

                <div class="lg:col-span-1 flex flex-col justify-center lg:pb-0">
                    <h3 class="text-2xl font-bold text-sky-600 transition-all">
                        {{ slides[activeIndex].title }}
                    </h3>

                    <div class="mt-4 flex flex-wrap gap-2">
                        <span
                            v-for="tag in slides[activeIndex].tags"
                            :key="tag"
                            class="px-2 py-1 text-xs font-semibold rounded-md bg-sky-300/80 text-sky-800 dark:bg-sky-900/30 dark:text-sky-300 border border-sky-200 dark:border-sky-800"
                        >
                            {{ tag }}
                        </span>
                    </div>

                    <div class="mt-4 max-h-[300px] lg:max-h-none overflow-y-auto">
                        <p class="text-base lg:text-lg text-gray-600 dark:text-gray-400 leading-relaxed">
                            {{ slides[activeIndex].description }}
                        </p>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<style scoped>
.animated-svg path {
    fill: none;
    stroke: #ffffff;
    stroke-width: 2;
    stroke-linecap: round;
    vector-effect: non-scaling-stroke;
}
/* SWIPER JS STYLE*/
.swiper {
    width: 100%;
    max-width: 700px;

    aspect-ratio: 55 / 34;
}

.swiper-slide {
    background-position: center;
    background-size: cover;
}

.swiper-slide img {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: contain;
    color: #004a75;
}
</style>
