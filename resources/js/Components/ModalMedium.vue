<template>
    <TransitionRoot as="template" :show="modelValue">
        <Dialog class="relative z-50" @close="emitClose">
            <!-- Fundo (Backdrop) -->
            <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
                <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" />
            </TransitionChild>

            <!-- Conteúdo do Modal -->
            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4 text-center">
                    <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in duration-200" leave-from="opacity-100 translate-y-0 sm:scale-100" leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                        <DialogPanel class="relative w-full max-w-3xl transform rounded-lg bg-white dark:bg-gray-900 text-left shadow-xl transition-all flex flex-col max-h-[90vh]">
                            <!-- Cabeçalho -->
                            <div class="w-full flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
                                <DialogTitle as="h3" class="text-lg font-semibold text-gray-900 dark:text-white flex items-center justify-center text-center w-full">
                                    {{ title }}
                                </DialogTitle>
                                <button @click="emitClose" class="p-1 rounded-full text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    <XMarkIcon class="h-6 w-6" />
                                </button>
                            </div>

                            <!-- Corpo com Scroll -->
                            <div class="flex-1 overflow-y-auto p-6">
                                <slot />
                            </div>

                            <!-- Rodapé -->
                            <div v-if="actionTitle || $slots.footer" class="flex justify-end items-center gap-x-4 p-4 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-200 dark:border-gray-700">
                                <slot name="footer">
                                    <button type="button" class="px-4 py-2 text-sm font-semibold text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 rounded-md border dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600" @click="emitClose">Cancelar</button>
                                    <button v-if="actionHandler" type="button" class="px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-md shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600" @click="actionHandler">
                                        {{ actionTitle }}
                                    </button>
                                </slot>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

<script>
// Importa todos os componentes necessários do Headless UI
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue';

// O componente XMarkIcon já é global, mas importá-lo aqui não causa problemas
// e torna a dependência explícita.
import { XMarkIcon } from '@heroicons/vue/24/outline';

export default {
    // Registra os componentes para serem usados NESTE template
    components: {
        Dialog,
        DialogPanel,
        DialogTitle,
        TransitionChild,
        TransitionRoot,
        XMarkIcon,
    },
    props: {
        modelValue: { type: Boolean, required: true },
        title: { type: String, default: 'Modal' },
        actionTitle: { type: String, default: null },
        actionHandler: { type: Function, default: null },
    },
    emits: ['update:modelValue'],
    methods: {
        emitClose() {
            this.$emit('update:modelValue', false);
        },
    },
};
</script>
