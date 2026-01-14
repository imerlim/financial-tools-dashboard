<template>
    <TransitionRoot as="template" :show="modelValue">
        <Dialog as="div" class="relative z-[9999]" :initial-focus="null" @close="close">
            <TransitionChild
                as="div"
                enter="ease-out duration-300"
                enter-from="opacity-0"
                enter-to="opacity-100"
                leave="ease-in duration-200"
                leave-from="opacity-100"
                leave-to="opacity-0"
            >
                <!-- Fundo -->
                <div class="fixed inset-0 bg-gray-500/75 dark:bg-gray-900/80 transition-opacity" />
            </TransitionChild>

            <div class="fixed inset-0 z-50 w-screen overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4 text-center sm:items-center sm:p-0 pt-[40vh]">
                    <TransitionChild
                        as="template"
                        enter="ease-out duration-300"
                        enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        enter-to="opacity-100 translate-y-0 sm:scale-100"
                        leave="ease-in duration-200"
                        leave-from="opacity-100 translate-y-0 sm:scale-100"
                        leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    >
                        <DialogPanel
                            class="relative transform overflow-hidden rounded-lg bg-white dark:bg-gray-800 p-6 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg"
                        >
                            <!-- Botão de fechar -->
                            <div class="absolute top-0 right-0 hidden pt-4 pr-4 sm:block">
                                <button
                                    type="button"
                                    class="rounded-md bg-white dark:bg-gray-800 text-gray-400 hover:text-gray-500 focus:outline-none"
                                    @click="close"
                                >
                                    <span class="sr-only">Fechar</span>
                                    <XMarkIcon class="h-6 w-6" aria-hidden="true" />
                                </button>
                            </div>

                            <!-- Conteúdo -->
                            <div class="sm:flex sm:items-start space-y-4 sm:space-y-0 sm:space-x-4">
                                <div
                                    class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full"
                                    :class="typeClasses.iconBg"
                                >
                                    <component :is="typeClasses.icon" :class="typeClasses.iconColor" class="h-6 w-6" />
                                </div>

                                <div class="text-center sm:text-left w-full">
                                    <DialogTitle as="h3" class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                        {{ title }}
                                    </DialogTitle>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                                            {{ message }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Botões -->
                            <div class="mt-6 sm:mt-6 sm:flex sm:flex-row-reverse">
                                <button
                                    type="button"
                                    class="inline-flex w-full justify-center rounded-md px-4 py-2 text-sm font-semibold text-white shadow-sm sm:ml-3 sm:w-auto"
                                    :class="typeClasses.buttonBg"
                                    @click="confirm"
                                >
                                    {{ confirmButtonText }}
                                </button>
                                <button
                                    type="button"
                                    class="mt-3 inline-flex w-full justify-center rounded-md bg-white dark:bg-gray-700 px-4 py-2 text-sm font-semibold text-gray-900 dark:text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 sm:mt-0 sm:w-auto"
                                    @click="close"
                                >
                                    {{ titleClose }}
                                </button>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

<script>
import { Dialog, DialogPanel, DialogTitle, TransitionRoot, TransitionChild } from '@headlessui/vue';

import { XMarkIcon, ExclamationTriangleIcon, InformationCircleIcon } from '@heroicons/vue/24/outline';

export default {
    name: 'ConfirmDialog',

    components: {
        Dialog,
        DialogPanel,
        DialogTitle,
        TransitionRoot,
        TransitionChild,
        XMarkIcon,
        ExclamationTriangleIcon,
        InformationCircleIcon,
    },

    props: {
        modelValue: Boolean,
        title: {
            type: String,
            default: 'Confirmação',
        },
        message: {
            type: String,
            default: 'Tem certeza que deseja prosseguir?',
        },
        type: {
            type: String,
            default: 'info', // 'info', 'danger', 'warning'
        },
        confirmButtonText: {
            type: String,
            default: 'Confirmar',
        },
        titleClose: {
            type: String,
            default: 'Cancelar',
        },
    },

    emits: ['update:modelValue', 'confirm'],

    computed: {
        typeClasses() {
            switch (this.type) {
                case 'danger':
                    return {
                        icon: ExclamationTriangleIcon,
                        iconBg: 'bg-red-100',
                        iconColor: 'text-red-600',
                        buttonBg: 'bg-red-600 hover:bg-red-500',
                    };
                case 'warning':
                    return {
                        icon: ExclamationTriangleIcon,
                        iconBg: 'bg-yellow-100',
                        iconColor: 'text-yellow-600',
                        buttonBg: 'bg-yellow-600 hover:bg-yellow-500',
                    };
                case 'info':
                default:
                    return {
                        icon: InformationCircleIcon,
                        iconBg: 'bg-blue-100',
                        iconColor: 'text-blue-600',
                        buttonBg: 'bg-blue-600 hover:bg-blue-500',
                    };
            }
        },
    },

    methods: {
        close() {
            this.$emit('update:modelValue', false);
            this.$emit('close');
        },
        confirm() {
            this.$emit('confirm');
            this.close();
        },
    },
};
</script>
