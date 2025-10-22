<script setup>
import { ref } from 'vue';
import { Bars3Icon, XMarkIcon, ChevronDownIcon } from '@heroicons/vue/20/solid';
import { Link } from '@inertiajs/vue3';
import { defineProps } from 'vue';
import ThemeSwitcher from '../Components/ThemeSwitcher.vue';

const props = defineProps({
    homeUrl: { type: String, required: true },
    loginUrl: { type: String, required: true },
});

const mobileMenuOpen = ref(false);

const navigation = [
    { name: 'Features', href: '#' },
    { name: 'Marketplace', href: '#' },
    { name: 'Company', href: '#' },
];
</script>

<template>
    <header class="bg-white dark:bg-slate-800 fixed top-0 z-50 w-full shadow-sm">
        <nav aria-label="Global" class="mx-auto flex max-w-7xl items-center justify-between p-6 lg:px-8">
            <div class="flex lg:flex-1">
                <a href="/" class="-m-1.5 p-1.5">
                    <span class="sr-only">Your Company</span>
                    <img src="https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=indigo&shade=600" alt="" class="h-8 w-auto dark:hidden" />
                    <img src="https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=indigo&shade=500" alt="" class="h-8 w-auto not-dark:hidden" />
                </a>
            </div>
            <div class="flex lg:hidden">
                <button type="button" command="show-modal" commandfor="mobile-menu" class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-slate-700 dark:text-slate-400">
                    <span class="sr-only">Open main menu</span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon" aria-hidden="true" class="size-6">
                        <path d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
            </div>

            <el-popover-group class="hidden lg:flex lg:gap-x-12" teleported="false">
                <div class="relative inline-block text-left">
                    <!-- Botão do menu -->
                    <button id="desktop-menu-product-button" popovertarget="desktop-menu-product" class="flex items-center gap-x-1 text-sm font-semibold text-slate-900 dark:text-white group">
                        Projetos
                        <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="size-5 flex-none text-slate-400 dark:text-slate-500 transition-transform duration-200 group-data-[open]:rotate-180">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" />
                        </svg>
                    </button>

                    <!-- Dropdown -->
                    <el-popover id="desktop-menu-product" anchor="desktop-menu-product-button" :show-arrow="false" popover anchor-strategy="fixed" teleported="false" trigger="click" class="absolute left-0 top-full mt-2 w-64 rounded-2xl bg-white dark:bg-slate-800 shadow-lg ring-1 ring-slate-900/5 dark:ring-white/10 transition-transform duration-200 ease-out data-[enter]:translate-y-0 data-[leave]:-translate-y-1 data-[enter]:opacity-100 data-[leave]:opacity-0">
                        <div class="p-2">
                            <a href="/aminia" class="block rounded-lg p-3 text-sm font-semibold text-slate-900 dark:text-white hover:bg-slate-50 dark:hover:bg-slate-700/40">
                                Aminia
                                <p class="text-xs font-normal text-slate-600 dark:text-slate-400">Music website</p>
                            </a>

                            <a href="/calculadora-juros-compostos" class="block rounded-lg p-3 text-sm font-semibold text-slate-900 dark:text-white hover:bg-slate-50 dark:hover:bg-slate-700/40">
                                Calculadora de investimentos
                                <p class="text-xs font-normal text-slate-600 dark:text-slate-400">Calcula juros compostos</p>
                            </a>
                        </div>
                    </el-popover>
                </div>
            </el-popover-group>

            <div class="p-2 hidden lg:flex lg:flex-1 lg:justify-end">
                <ThemeSwitcher class="mr-4" />
                <a href="#" class="text-sm/6 font-semibold text-slate-900 dark:text-white">Log in <span aria-hidden="true">&rarr;</span></a>
            </div>
        </nav>
        <el-dialog>
            <dialog id="mobile-menu" class="backdrop:bg-transparent lg:hidden">
                <div tabindex="0" class="fixed inset-0 focus:outline-none">
                    <el-dialog-panel class="fixed inset-y-0 right-0 z-50 w-full overflow-y-auto bg-white p-6 sm:max-w-sm sm:ring-1 sm:ring-slate-900/10 dark:bg-slate-900 dark:sm:ring-slate-100/10">
                        <div class="flex items-center justify-between">
                            <a href="#" class="-m-1.5 p-1.5">
                                <span class="sr-only">Your Company</span>
                                <img src="https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=indigo&shade=600" alt="" class="h-8 w-auto dark:hidden" />
                                <img src="https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=indigo&shade=500" alt="" class="h-8 w-auto not-dark:hidden" />
                            </a>
                            <button type="button" command="close" commandfor="mobile-menu" class="-m-2.5 rounded-md p-2.5 text-slate-700 dark:text-slate-400">
                                <span class="sr-only">Close menu</span>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon" aria-hidden="true" class="size-6">
                                    <path d="M6 18 18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </button>
                        </div>
                        <div class="mt-6 flow-root">
                            <div class="-my-6 divide-y divide-slate-500/10 dark:divide-white/10">
                                <div class="space-y-2 py-6">
                                    <div class="-mx-3">
                                        <button type="button" command="--toggle" commandfor="products" class="flex w-full items-center justify-between rounded-lg py-2 pr-3.5 pl-3 text-base/7 font-semibold text-slate-900 hover:bg-slate-50 dark:text-white dark:hover:bg-white/5">
                                            Projetos
                                            <svg viewBox="0 0 20 20" fill="currentColor" data-slot="icon" aria-hidden="true" class="size-5 flex-none in-aria-expanded:rotate-180">
                                                <path d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" fill-rule="evenodd" />
                                            </svg>
                                        </button>
                                        <el-disclosure id="products" hidden class="mt-2 block space-y-2">
                                            <a href="#" class="block rounded-lg py-2 pr-3 pl-6 text-sm/7 font-semibold text-slate-900 hover:bg-slate-50 dark:text-white dark:hover:bg-white/5">Analytics</a>
                                            <a href="#" class="block rounded-lg py-2 pr-3 pl-6 text-sm/7 font-semibold text-slate-900 hover:bg-slate-50 dark:text-white dark:hover:bg-white/5">Engagement</a>
                                            <a href="#" class="block rounded-lg py-2 pr-3 pl-6 text-sm/7 font-semibold text-slate-900 hover:bg-slate-50 dark:text-white dark:hover:bg-white/5">Security</a>
                                            <a href="#" class="block rounded-lg py-2 pr-3 pl-6 text-sm/7 font-semibold text-slate-900 hover:bg-slate-50 dark:text-white dark:hover:bg-white/5">Integrations</a>
                                            <a href="#" class="block rounded-lg py-2 pr-3 pl-6 text-sm/7 font-semibold text-slate-900 hover:bg-slate-50 dark:text-white dark:hover:bg-white/5">Automations</a>
                                            <a href="#" class="block rounded-lg py-2 pr-3 pl-6 text-sm/7 font-semibold text-slate-900 hover:bg-slate-50 dark:text-white dark:hover:bg-white/5">Watch demo</a>
                                            <a href="#" class="block rounded-lg py-2 pr-3 pl-6 text-sm/7 font-semibold text-slate-900 hover:bg-slate-50 dark:text-white dark:hover:bg-white/5">Contact sales</a>
                                        </el-disclosure>
                                    </div>
                                    <a href="#" class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-slate-900 hover:bg-slate-50 dark:text-white dark:hover:bg-white/5">Features</a>
                                    <a href="#" class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-slate-900 hover:bg-slate-50 dark:text-white dark:hover:bg-white/5">Marketplace</a>
                                    <a href="#" class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-slate-900 hover:bg-slate-50 dark:text-white dark:hover:bg-white/5">Company</a>
                                </div>
                                <div class="py-6">
                                    <a href="#" class="-mx-3 block rounded-lg px-3 py-2.5 text-base/7 font-semibold text-slate-900 hover:bg-slate-50 dark:text-white dark:hover:bg-white/5">Log in</a>
                                </div>
                            </div>
                        </div>
                    </el-dialog-panel>
                </div>
            </dialog>
        </el-dialog>
    </header>
</template>
