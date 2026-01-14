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
                            <Table :headers="headersDocuments" :items="localDocuments" :per-page="10" :loading="loading">
                                <template #acoes="{ item }">
                                    <div class="flex justify-end gap-4">
                                        <button @click="viewDocument(item)" class="text-sky-500 hover:text-sky-400 font-medium">
                                            View
                                        </button>
                                        <button @click="deleteDoc(item)" class="text-red-500 hover:text-red-400 font-medium">Delete</button>
                                    </div>
                                </template>

                                <template #status="{ item }">
                                    <span :class="item.status === 'completed' ? 'text-green-400' : 'text-yellow-400'">
                                        {{ item.status === 'completed' ? 'Extracted' : 'Processing...' }}
                                    </span>
                                </template>
                            </Table>
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
            headersDocuments: [
                { label: 'Document', key: 'title' }, // Usando a coluna 'title' do seu banco
                { label: 'Status', key: 'status', customRender: 'status' },
                { label: 'Size', key: 'size' },
                { label: '', key: 'acoes', customRender: 'acoes' },
            ],
            localDocuments: [],
            loading: false,
            isDragging: false,
            userId: null,
        };
    },
    mounted() {
        const page = usePage();
        this.userId = page.props.auth.user?.id ?? null;
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
            this.loading = true; // Ativa o spinner do seu componente Table
            try {
                const response = await axios.get('/documents-list');
                this.localDocuments = response.data; // Alimenta o :items do Table
            } finally {
                this.loading = false;
            }
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

        async viewDocument(doc) {
            try {
                const response = await axios.get(`/document-download/${doc.id}`);
                // Abre o arquivo em uma nova aba do navegador
                window.open(response.data.url, '_blank');
            } catch (error) {
                this.$msg.warning('Error opening document.');
            }
        },

        async deleteDoc(doc) {
            if (!confirm('Are you sure you want to delete this file?')) return;

            try {
                await axios.delete(`/document-delete/${doc.id}`);
                this.$msg.success('File removed!');
                this.fetchDocuments(); // Recarrega a lista para sumir da tela
            } catch (error) {
                this.$msg.warning('Error deleting file.');
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
