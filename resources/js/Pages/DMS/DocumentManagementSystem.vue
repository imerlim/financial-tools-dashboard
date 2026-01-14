<template>
    <Head title="Document Management" />
    <div class="bg-slate-100 dark:bg-slate-900 min-h-screen">
        <main>
            <div
                class="relative isolate overflow-hidden text-white dark:text-slate-300 bg-slate-100 dark:bg-slate-900 pt-32 sm:px-5 sm:pt-10"
            >
                <div class="container mx-auto sm:px-16">
                    <div class="grid grid-cols-1 gap-x-8 gap-y-8 px-4 py-11 sm:px-6 md:grid-cols-4 lg:px-8">
                        <div class="sm:col-span-4 text-center">
                            <legend class="text-3xl font-bold text-slate-900 dark:text-white mb-2">Merlim DMS</legend>
                            <p class="text-slate-600 dark:text-slate-400">Intelligent document management powered by AWS AI</p>
                        </div>

                        <div class="sm:col-span-4">
                            <div class="grid grid-cols-1 gap-3 overflow-hidden rounded-2xl text-center sm:grid-cols-3">
                                <div class="flex flex-col bg-white p-8 dark:bg-white/5 shadow-sm">
                                    <dt class="text-sm font-medium text-slate-600 dark:text-slate-400">Total Documents</dt>
                                    <dd class="text-3xl font-semibold tracking-tight text-slate-900 dark:text-white">
                                        {{ documents?.length || 0 }}
                                    </dd>
                                </div>
                                <div
                                    class="flex flex-col bg-white p-8 dark:bg-white/5 shadow-sm border-x border-slate-200 dark:border-slate-800"
                                >
                                    <dt class="text-sm font-medium text-sky-500">AI Processed</dt>
                                    <dd class="text-3xl font-semibold tracking-tight text-sky-500">
                                        {{ processedCount }}
                                    </dd>
                                </div>
                                <div class="flex flex-col bg-white p-8 dark:bg-white/5 shadow-sm">
                                    <dt class="text-sm font-medium text-slate-600 dark:text-slate-400">Cloud Storage (S3)</dt>
                                    <dd class="text-3xl font-semibold tracking-tight text-slate-900 dark:text-white">{{ totalSize }} MB</dd>
                                </div>
                            </div>
                        </div>

                        <div class="sm:col-span-4 mt-4">
                            <div
                                @dragover.prevent="isDragging = true"
                                @dragleave.prevent="isDragging = false"
                                @drop.prevent="handleDrop"
                                :class="{ 'border-sky-500 bg-sky-500/10': isDragging }"
                                class="relative border-2 border-dashed border-slate-300 dark:border-slate-700 rounded-2xl p-12 text-center hover:border-sky-500 transition-all cursor-pointer group"
                            >
                                <input type="file" @change="handleFileUpload" class="hidden" id="doc-upload" />
                                <label for="doc-upload" class="cursor-pointer">
                                    <svg
                                        class="mx-auto h-12 w-12 text-slate-400 group-hover:text-sky-500 transition-colors"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="1.5"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"
                                        />
                                    </svg>
                                    <div class="mt-4 flex text-sm leading-6 text-slate-600 dark:text-slate-400 justify-center">
                                        <span class="relative font-semibold text-sky-500">Click to upload</span>
                                        <p class="pl-1">or drag and drop your files to the cloud</p>
                                    </div>
                                    <p class="text-xs leading-5 text-slate-500">PDF, PNG or JPG up to 10MB</p>
                                </label>
                            </div>
                        </div>

                        <div
                            class="sm:col-span-4 mt-8 bg-white dark:bg-white/5 rounded-2xl shadow-xl overflow-hidden border border-slate-200 dark:border-slate-800"
                        >
                            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-800">
                                <thead class="bg-slate-50 dark:bg-slate-800/50">
                                    <tr>
                                        <th
                                            class="px-6 py-4 text-left text-xs font-semibold text-slate-900 dark:text-white uppercase tracking-wider"
                                        >
                                            Document
                                        </th>
                                        <th
                                            class="px-6 py-4 text-left text-xs font-semibold text-slate-900 dark:text-white uppercase tracking-wider"
                                        >
                                            AI Status
                                        </th>
                                        <th
                                            class="px-6 py-4 text-right text-xs font-semibold text-slate-900 dark:text-white uppercase tracking-wider"
                                        >
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                                    <tr
                                        v-for="doc in documents"
                                        :key="doc.id"
                                        class="hover:bg-slate-50 dark:hover:bg-white/5 transition-colors"
                                    >
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div
                                                    class="h-10 w-10 flex-shrink-0 bg-sky-500/10 rounded-lg flex items-center justify-center"
                                                >
                                                    <svg class="h-6 w-6 text-sky-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                                        />
                                                    </svg>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-slate-900 dark:text-white">{{ doc.title }}</div>
                                                    <div class="text-xs text-slate-500">Uploaded on {{ formatDate(doc.created_at) }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                v-if="doc.status === 'completed'"
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-500/10 dark:text-green-400"
                                            >
                                                <span class="h-1.5 w-1.5 rounded-full bg-green-500 mr-1.5"></span>
                                                Text Extracted
                                            </span>
                                            <span
                                                v-else
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-500/10 dark:text-yellow-400"
                                            >
                                                <span class="h-1.5 w-1.5 rounded-full bg-yellow-500 mr-1.5 animate-pulse"></span>
                                                AI Processing...
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <button @click="viewDetails(doc)" class="text-sky-500 hover:text-sky-400 mr-4">
                                                View Content
                                            </button>
                                            <button class="text-slate-400 hover:text-white">Download</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>

<script>
import { usePage, Head } from '@inertiajs/vue3';

export default {
    components: { Head },
    props: {
        documents: { type: Array, default: () => [] },
    },
    data() {
        return {
            localDocuments: [],
            loading: false,
            isDragging: false,
            userId: null,
        };
    },
    mounted() {
        const page = usePage();
        this.userId = page.props.auth.user?.id ?? null;

        console.log('aki', page.props.auth);

        // Carregamento inicial paralelo
        this.refreshData();
    },
    methods: {
        async refreshData() {
            this.loading = true;
            try {
                // Se tivesse mais buscas, colocaria aqui dentro do array
                await Promise.all([this.fetchDocuments()]);
            } finally {
                this.loading = false;
            }
        },

        async fetchDocuments() {
            const response = await axios.get('/documents-list');
            this.localDocuments = response.data;
        },

        async uploadToServer(file) {
            let formData = new FormData();
            formData.append('document', file);
            formData.append('userId', this.userId);

            try {
                // Aqui você pode disparar o upload e já limpar o input
                await axios.post('document-upload', formData);
                this.$msg.success('Upload complete!');

                // Atualiza a lista imediatamente após o upload
                this.fetchDocuments();
            } catch (error) {
                this.$msg.warning('Upload failed.');
            }
        },

        // Métodos de auxílio
        handleFileUpload(event) {
            const file = event.target.files[0];
            if (file) this.uploadToServer(file);
        },
        handleDrop(event) {
            this.isDragging = false;
            const file = event.dataTransfer.files[0];
            if (file) this.uploadToServer(file);
        },
        formatDate(date) {
            return new Date(date).toLocaleDateString('en-US');
        },
    },
};
</script>
