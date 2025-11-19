<template>
    <Head title="Controle financeiro" />
    <div class="bg-slate-100 dark:bg-slate-900">
        <GlobalMsg></GlobalMsg>
        <main>
            <div
                class="relative isolate overflow-hidden text-white dark:text-slate-300 bg-slate-100 dark:bg-slate-900 min-h-screen pt-32 sm:px-5 sm:pt-0 divide-y"
            >
                <div class="container mx-auto sm:px-16">
                    <div class="grid grid-cols-1 gap-x-8 gap-y-8 px-4 py-11 sm:px-6 md:grid-cols-6 lg:px-8">
                        <div class="sm:col-span-1">
                            <CustomSelect
                                @change="mudaTipoMargem()"
                                v-model="selectTipo"
                                id="selectTipo"
                                name="selectTipo"
                                label="Tipo"
                                :options="[
                                    { label: 'Entrada', value: 'E' },
                                    { label: 'Saída', value: 'S' },
                                ]"
                            >
                            </CustomSelect>
                        </div>
                        <div class="sm:col-span-1">
                            <CustomInput
                                inputmode="numeric"
                                placeholder="0,00"
                                :formata="true"
                                v-model="valor"
                                label="Valor"
                                id="valor"
                                name="valor"
                            >
                                <template #prepend>
                                    <span class="text-base">R$</span>
                                </template>
                            </CustomInput>
                        </div>
                        <div class="sm:col-span-2">
                            <CustomSelect
                                v-model="categoria"
                                label="Categoria"
                                id="categoria"
                                name="categoria"
                                :options="optionsCategoria"
                                :show-add="true"
                                @on-add="openModalCategoria = true"
                            ></CustomSelect>

                            <ModalMedium v-model="openModalCategoria" title="Cadastro de categoria">
                                <div
                                    class="bg-white text-slate-900 dark:bg-slate-900 dark:text-white p-5 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6"
                                >
                                    <div class="sm:col-span-3">
                                        <CustomInput
                                            id="novaCategoria"
                                            v-model="novaCategoria"
                                            label="Nova categoria"
                                            name="novaCategoria"
                                        />
                                    </div>
                                    <div class="sm:col-span-1 self-end flex">
                                        <PrimaryButton @click="createCategoria()">Cadastrar</PrimaryButton>
                                    </div>

                                    <div class="sm:col-span-6">
                                        <Table
                                            :headers="headersCategoria"
                                            :items="itemsCategoria"
                                            :per-page="5"
                                            :show-search="true"
                                            @delete="handleDeleteCategoria"
                                            :loading="loadCategoria"
                                        >
                                            <template #acoes="{ item }">
                                                <div class="flex justify-end">
                                                    <button
                                                        type="button"
                                                        title="Excluir fabricante"
                                                        class="inline-flex size-8 items-center justify-center rounded-full bg-transparent text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:outline-hidden"
                                                        @click="salvaNomeFabricanteExclusao(item)"
                                                    >
                                                        <TrashIcon
                                                            class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 size-5"
                                                            aria-hidden="true"
                                                        />
                                                    </button>
                                                    <button
                                                        type="button"
                                                        title="Alterar fabricante"
                                                        class="inline-flex size-8 items-center justify-center rounded-full bg-transparent text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:outline-hidden"
                                                        @click="selecionaFabricante(item)"
                                                    >
                                                        <PencilSquareIcon class="size-5" aria-hidden="true" />
                                                    </button>
                                                </div>
                                            </template>
                                        </Table>
                                    </div>
                                </div>
                            </ModalMedium>
                        </div>
                        <div class="sm:col-span-1">
                            <CustomInput type="date" v-model="data" label="Data" id="data" name="data"></CustomInput>
                        </div>
                        <div class="sm:col-span-6 flex justify-end self-end mt-5">
                            <PrimaryButton @click="createDados()"> Salvar </PrimaryButton>
                        </div>

                        <div class="sm:col-span-6">
                            <Table
                                :headers="headersDadosFinanceiro"
                                :items="itemsDadosFinanceiro"
                                :per-page="5"
                                :show-search="true"
                                :loading="loadDadosFinanceiro"
                                :multi-select="false"
                            >
                                <template #acoes="{ item }">
                                    <div class="flex justify-end">
                                        <button
                                            type="button"
                                            title="Excluir fabricante"
                                            class="inline-flex size-8 items-center justify-center rounded-full bg-transparent text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:outline-hidden"
                                            @click="salvaNomeFabricanteExclusao(item)"
                                        >
                                            <TrashIcon
                                                class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 size-5"
                                                aria-hidden="true"
                                            />
                                        </button>
                                        <button
                                            type="button"
                                            title="Alterar fabricante"
                                            class="inline-flex size-8 items-center justify-center rounded-full bg-transparent text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:outline-hidden"
                                            @click="selecionaFabricante(item)"
                                        >
                                            <PencilSquareIcon class="size-5" aria-hidden="true" />
                                        </button>
                                    </div>
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
import { usePage } from '@inertiajs/vue3';
export default {
    data() {
        return {
            openModalCategoria: false,

            headersCategoria: [
                { label: 'Categoria', key: 'categoria' },
                { label: '', key: 'acoes', customRender: 'acoes' },
            ],
            itemsCategoria: [],
            loadCategoria: true,

            headersDadosFinanceiro: [
                { label: 'Nome', key: 'PEDIDO' },
                { label: 'Data', key: 'DATA' },
                { label: 'Valor', key: 'VALORFORMATADO' },
                { label: 'Operador', key: 'OPERADOR' },
                { label: 'Pagamento', key: 'FORMA' },
                { label: 'Descrição', key: 'PAG' },
            ],
            itemsDadosFinanceiro: [],
            loadDadosFinanceiro: true,

            user: null,
            userId: null,
            selectTipo: null,
            valor: null,
            categoria: null,
            novaCategoria: null,
            data: null,
            optionsCategoria: [],
        };
    },

    mounted() {
        const userProps = usePage();
        this.user = userProps.props.auth.user?.name ?? null;
        this.userId = userProps.props.auth.user?.id ?? null;

        function formatDate(date) {
            var d = new Date(date),
                month = '' + (d.getMonth() + 1),
                day = '' + d.getDate(),
                year = d.getFullYear();

            if (month.length < 2) month = '0' + month;
            if (day.length < 2) day = '0' + day;

            return [year, month, day].join('-');
        }

        var today = new Date();
        this.dtHoje = formatDate(today);

        this.inicia();
    },

    methods: {
        inicia() {
            this.allCategorias();
        },

        createDados() {
            if (this.user == null) {
                this.$msg.warning('Realize login antes de processeguir.');
            }
        },

        async createCategoria() {
            if (!this.novaCategoria) {
                return this.$msg.warning('Preencha uma categoria.');
            }
            this.loadCategoria = true;
            try {
                await axios.post('/create-categoria', { novaCategoria: this.novaCategoria, userId: this.userId });
                this.allCategorias();
                this.novaCategoria = null;
            } catch {
                this.loadCategoria = false;
            }
        },

        async allCategorias() {
            this.loadCategoria = true;
            try {
                const response = await axios.get('/all-categoria');

                this.itemsCategoria = response.data;
                this.optionsCategoria = response.data.map(item => ({
                    value: item.categoria,
                    label: item.categoria,
                }));
            } finally {
                this.loadCategoria = false;
            }
        },

        async handleDeleteCategoria(item) {
            this.loadCategoria = true;
            try {
                await axios.delete('/delete-categoria', {
                    data: {
                        idCategoria: item.idCategoria,
                    },
                });
                this.allCategorias();
            } catch {
                this.loadCategoria = false;
            }
        },
    },
};
</script>
