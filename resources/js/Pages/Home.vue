<script setup>
import { onMounted } from 'vue';
import { animate, svg } from 'https://esm.sh/animejs';

import { gsap } from 'gsap';
import { ref } from 'vue';
import GoldenSpiral from '../Components/Animations/GoldenSpiral.vue';
import CuboScroll from '../Components/Animations/CuboScroll.vue';

const svgContainer = ref(null); // Seu ref está aqui!

onMounted(() => {
    // CÓDIGO QUE VOCÊ DISSE QUE FUNCIONOU
    const drawable = svg.createDrawable('.animated-svg path');

    animate(drawable, {
        draw: ['0 0', '0 1', '1 1'],
        // draw: ['0 0', '0 1'],
        ease: 'inOutQuad',
        duration: 8888,
        loop: true,
    });

    // Código GSAP

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
            <!-- <CuboScroll class="absolute inset-0 z-10"></CuboScroll> -->

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
                    Desenvolvedor Full-Stack. Construindo soluções escaláveis e arquitetura moderna.
                </p>
            </div>
        </div>

        <div class="bg-slate-100 dark:bg-slate-900 py-24 sm:py-32">
            <div class="mx-auto max-w-2xl px-6 lg:max-w-7xl lg:px-8">
                <p
                    class="mx-auto mt-2 text-balance text-center text-4xl font-semibold tracking-tight text-gray-950 sm:text-5xl dark:text-white"
                >
                    Experiências profissionais
                </p>
                <div class="mt-10 grid gap-4 sm:mt-16 lg:grid-cols-3 lg:grid-rows-2">
                    <div class="relative lg:row-span-2">
                        <div class="absolute inset-px rounded-lg bg-white lg:rounded-l-[2rem] dark:bg-gray-800"></div>
                        <div
                            class="relative flex h-full flex-col overflow-hidden rounded-[calc(theme(borderRadius.lg)+1px)] lg:rounded-l-[calc(2rem+1px)]"
                        >
                            <div class="px-8 pb-3 pt-8 sm:px-10 sm:pb-0 sm:pt-10">
                                <p class="mt-2 text-lg font-medium tracking-tight text-gray-950 max-lg:text-center dark:text-white">
                                    Módulos gestão de empresa
                                </p>
                                <ul class="mt-2 max-w-lg text-lg text-gray-600 max-lg:text-center dark:text-gray-400">
                                    <li class="list-disc mx-auto mt-8">
                                        Desenvolvimento de módulos de cadastro para controle de funcionários, clientes e fornecedores.
                                    </li>
                                    <li class="list-disc mx-auto mt-8">
                                        Criação de módulos de estoque, incluindo cadastro de produtos e entrada de mercadorias via XML.
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div
                            class="pointer-events-none absolute inset-px rounded-lg shadow outline outline-1 outline-black/5 lg:rounded-l-[2rem] dark:outline-white/15"
                        ></div>
                    </div>
                    <div class="relative max-lg:row-start-1">
                        <div class="absolute inset-px rounded-lg bg-white max-lg:rounded-t-[2rem] dark:bg-gray-800"></div>
                        <div
                            class="relative flex h-full flex-col overflow-hidden rounded-[calc(theme(borderRadius.lg)+1px)] max-lg:rounded-t-[calc(2rem+1px)]"
                        >
                            <div class="px-8 pt-8 sm:px-10 sm:pt-10">
                                <p class="mt-2 text-lg font-medium tracking-tight text-gray-950 max-lg:text-center dark:text-white">
                                    Integrações
                                </p>
                                <ul class="mt-2 max-w-lg text-lg text-gray-600 max-lg:text-center dark:text-gray-400">
                                    <li class="list-disc mx-auto mt-8">
                                        Integração de sistemas com plataformas de e-commerce como Nuvemshop e WooCommerce.
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div
                            class="pointer-events-none absolute inset-px rounded-lg shadow outline outline-1 outline-black/5 max-lg:rounded-t-[2rem] dark:outline-white/15"
                        ></div>
                    </div>
                    <div class="relative max-lg:row-start-3 lg:col-start-2 lg:row-start-2">
                        <div class="absolute inset-px rounded-lg bg-white dark:bg-gray-800"></div>
                        <div class="relative flex h-full flex-col overflow-hidden rounded-[calc(theme(borderRadius.lg)+1px)]">
                            <div class="px-8 pt-8 sm:px-10 sm:pt-10">
                                <p class="mt-2 text-lg font-medium tracking-tight text-gray-950 max-lg:text-center dark:text-white">
                                    Análise financeiro
                                </p>
                                <ul class="mt-2 max-w-lg text-lg text-gray-600 max-lg:text-center dark:text-gray-400">
                                    <li class="list-disc mx-auto mt-8">
                                        desenvolvimento de telas de relatórios personalizados e dashboards interativos com gráficos de
                                        vendas mensais e anuais.
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div
                            class="pointer-events-none absolute inset-px rounded-lg shadow outline outline-1 outline-black/5 dark:outline-white/15"
                        ></div>
                    </div>
                    <div class="relative lg:row-span-2">
                        <div
                            class="absolute inset-px rounded-lg bg-white max-lg:rounded-b-[2rem] lg:rounded-r-[2rem] dark:bg-gray-800"
                        ></div>
                        <div
                            class="relative flex h-full flex-col overflow-hidden rounded-[calc(theme(borderRadius.lg)+1px)] max-lg:rounded-b-[calc(2rem+1px)] lg:rounded-r-[calc(2rem+1px)]"
                        >
                            <div class="px-8 pb-3 pt-8 sm:px-10 sm:pb-0 sm:pt-10">
                                <p class="mt-2 text-lg font-medium tracking-tight text-gray-950 max-lg:text-center dark:text-white">CRM</p>
                                <ul class="mt-2 max-w-lg text-lg text-gray-600 max-lg:text-center dark:text-gray-400">
                                    <li class="list-disc mx-auto mt-8">
                                        Implementação de módulos de CRM para acompanhamento de clientes e oportunidades de venda
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div
                            class="pointer-events-none absolute inset-px rounded-lg shadow outline outline-1 outline-black/5 max-lg:rounded-b-[2rem] lg:rounded-r-[2rem] dark:outline-white/15"
                        ></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* 🚨 VERIFIQUE ESTA SEÇÃO! */
.animated-svg path {
    fill: none;
    stroke: #ffffff;
    stroke-width: 2;
    stroke-linecap: round;
    vector-effect: non-scaling-stroke;
}
</style>
