<script setup>
import { onMounted } from 'vue';
import { gsap } from 'gsap';
import GoldenSpiral from '../Components/Animations/GoldenSpiral.vue';
import CuboScroll from '../Components/Animations/CuboScroll.vue';

onMounted(() => {
    const tl = gsap.timeline();

    tl.from('.js-title', {
        duration: 1.8,
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
    <div class="w-full">
        <div class="relative w-full h-screen overflow-hidden scroll-snap-start bg-slate-100 dark:bg-slate-900">
            <GoldenSpiral class="absolute inset-0 z-10"></GoldenSpiral>

            <div class="text-center mt-32 relative z-20">
                <h1 class="js-title text-5xl font-bold text-white">Igor Merlim</h1>
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
                                <p
                                    class="mt-2 text-lg font-medium tracking-tight text-gray-950 max-lg:text-center dark:text-white"
                                >
                                    Módulos gestão de empresa
                                </p>
                                <ul class="mt-2 max-w-lg text-lg text-gray-600 max-lg:text-center dark:text-gray-400">
                                    <li class="list-disc mx-auto mt-8">
                                        Desenvolvimento de módulos de cadastro para controle de funcionários, clientes e
                                        fornecedores.
                                    </li>
                                    <li class="list-disc mx-auto mt-8">
                                        Criação de módulos de estoque, incluindo cadastro de produtos e entrada de
                                        mercadorias via XML.
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div
                            class="pointer-events-none absolute inset-px rounded-lg shadow outline outline-1 outline-black/5 lg:rounded-l-[2rem] dark:outline-white/15"
                        ></div>
                    </div>
                    <div class="relative max-lg:row-start-1">
                        <div
                            class="absolute inset-px rounded-lg bg-white max-lg:rounded-t-[2rem] dark:bg-gray-800"
                        ></div>
                        <div
                            class="relative flex h-full flex-col overflow-hidden rounded-[calc(theme(borderRadius.lg)+1px)] max-lg:rounded-t-[calc(2rem+1px)]"
                        >
                            <div class="px-8 pt-8 sm:px-10 sm:pt-10">
                                <p
                                    class="mt-2 text-lg font-medium tracking-tight text-gray-950 max-lg:text-center dark:text-white"
                                >
                                    Integrações
                                </p>
                                <ul class="mt-2 max-w-lg text-lg text-gray-600 max-lg:text-center dark:text-gray-400">
                                    <li class="list-disc mx-auto mt-8">
                                        Integração de sistemas com plataformas de e-commerce como Nuvemshop e
                                        WooCommerce.
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
                        <div
                            class="relative flex h-full flex-col overflow-hidden rounded-[calc(theme(borderRadius.lg)+1px)]"
                        >
                            <div class="px-8 pt-8 sm:px-10 sm:pt-10">
                                <p
                                    class="mt-2 text-lg font-medium tracking-tight text-gray-950 max-lg:text-center dark:text-white"
                                >
                                    Análise financeiro
                                </p>
                                <ul class="mt-2 max-w-lg text-lg text-gray-600 max-lg:text-center dark:text-gray-400">
                                    <li class="list-disc mx-auto mt-8">
                                        desenvolvimento de telas de relatórios personalizados e dashboards interativos
                                        com gráficos de vendas mensais e anuais.
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
                                <p
                                    class="mt-2 text-lg font-medium tracking-tight text-gray-950 max-lg:text-center dark:text-white"
                                >
                                    CRM
                                </p>
                                <ul class="mt-2 max-w-lg text-lg text-gray-600 max-lg:text-center dark:text-gray-400">
                                    <li class="list-disc mx-auto mt-8">
                                        Implementação de módulos de CRM para acompanhamento de clientes e oportunidades
                                        de venda
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
