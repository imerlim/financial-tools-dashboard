<template>
    <Head title="Controle financeiro" />
    <div class="bg-slate-100 dark:bg-slate-900">
        <main>
            <div
                class="relative isolate overflow-hidden text-white dark:text-slate-300 bg-slate-100 dark:bg-slate-900 min-h-screen pt-32 sm:px-5 sm:pt-0 divide-y"
            >
                <div class="container mx-auto sm:px-16">
                    <form @submit.prevent="createDados" class="grid grid-cols-1 gap-x-8 gap-y-8 px-4 py-11 sm:px-6 md:grid-cols-6 lg:px-8">
                        <div class="sm:col-span-1">
                            <CustomSelect
                                :disabled="!this.user"
                                required
                                v-model="selectTipo"
                                id="selectTipo"
                                name="selectTipo"
                                label="Tipo"
                                :options="[
                                    { label: 'Income', value: 'E' },
                                    { label: 'Expense', value: 'S' },
                                ]"
                            >
                            </CustomSelect>
                        </div>
                        <div class="sm:col-span-1">
                            <CustomInput
                                :disabled="!this.user"
                                required
                                maxlength="10"
                                inputmode="numeric"
                                placeholder="0,00"
                                :formata="true"
                                v-model="valor"
                                label="Amount"
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
                                :disabled="!this.user"
                                required
                                v-model="categoria"
                                label="Category"
                                id="categoria"
                                name="categoria"
                                :options="optionsCategoria"
                                :show-add="true"
                                @on-add="openModalCategoria = true"
                            ></CustomSelect>

                            <ModalMedium v-model="openModalCategoria" title="Cadastro de categoria">
                                <form
                                    @submit.prevent="createCategoria"
                                    class="bg-white text-slate-900 dark:bg-slate-900 dark:text-white p-5 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6"
                                >
                                    <div class="sm:col-span-3">
                                        <CustomInput
                                            required
                                            maxlength="45"
                                            id="novaCategoria"
                                            v-model="novaCategoria"
                                            label="New Category"
                                            name="novaCategoria"
                                        />
                                    </div>
                                    <div class="sm:col-span-1 self-end flex">
                                        <PrimaryButton type="submit">Cadastrar</PrimaryButton>
                                    </div>

                                    <div class="sm:col-span-6">
                                        <Table :headers="headersCategoria" :items="itemsCategoria" :per-page="5" :loading="loadCategoria">
                                            <template #acoes="{ item }">
                                                <div class="flex justify-end">
                                                    <button
                                                        type="button"
                                                        title="Excluir"
                                                        class="inline-flex size-8 items-center justify-center rounded-full bg-transparent text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:outline-hidden"
                                                        @click="handleDeleteCategoria(item)"
                                                    >
                                                        <TrashIcon
                                                            class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 size-5"
                                                            aria-hidden="true"
                                                        />
                                                    </button>
                                                    <button
                                                        type="button"
                                                        title="Alterar"
                                                        class="inline-flex size-8 items-center justify-center rounded-full bg-transparent text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:outline-hidden"
                                                        @click="selecionaFabricante(item)"
                                                    >
                                                        <PencilSquareIcon class="size-5" aria-hidden="true" />
                                                    </button>
                                                </div>
                                            </template>
                                        </Table>
                                    </div>
                                </form>
                            </ModalMedium>
                        </div>
                        <div class="sm:col-span-1">
                            <CustomInput
                                :disabled="!this.user"
                                required
                                type="date"
                                v-model="data"
                                label="Date"
                                id="data"
                                name="data"
                            ></CustomInput>
                        </div>

                        <div class="sm:col-span-1 flex justify-end self-end">
                            <PrimaryButton type="submit" :disabled="disableCreateuser"> Salvar </PrimaryButton>
                        </div>

                        <div ref="dropdownContainer" class="relative sm:col-span-1 sm:col-start-6 flex justify-end">
                            <FunnelIcon
                                @click="
                                    openFiltro = !openFiltro;
                                    dataInicio = this.dtHoje;
                                    dataFim = this.dtHoje;
                                "
                                class="w-12 h-12 cursor-pointer text-sky-800 shadow-xs hover:text-sky-600"
                            ></FunnelIcon>

                            <div
                                v-if="openFiltro"
                                class="absolute top-full mt-2 min-w-[18rem] max-w-[90vw] w-max left-0 md:left-auto md:right-0 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg shadow-lg z-40"
                            >
                                <div class="p-4 space-y-4" @submit.prevent="submit">
                                    <div>
                                        <CustomInput
                                            :disabled="!this.user"
                                            required
                                            type="date"
                                            v-model="dataInicio"
                                            label="Start Date"
                                            id="dataInicio"
                                            name="dataInicio"
                                        ></CustomInput>
                                    </div>
                                    <CustomInput
                                        :disabled="!this.user"
                                        required
                                        type="date"
                                        v-model="dataFim"
                                        label="End Date"
                                        id="dataFim"
                                        name="dataFim"
                                    ></CustomInput>
                                    <div></div>

                                    <div class="flex justify-end gap-x-2">
                                        <PrimaryButton type="button" @click="limparFiltros()" :disabled="!this.user">
                                            Limpar
                                        </PrimaryButton>

                                        <PrimaryButton
                                            type="button"
                                            @click="buscaFiltros()"
                                            :disabled="!this.user || loadControleFinanceiro"
                                        >
                                            Buscar
                                        </PrimaryButton>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="sm:col-span-6">
                            <Table
                                :headers="headersDadosFinanceiro"
                                :items="itemsControleFinanceiro"
                                :per-page="5"
                                :show-search="true"
                                :loading="loadControleFinanceiro"
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

                        <div class="sm:col-span-3">
                            <PieChart
                                v-if="Array.isArray(arraySomaValoresCategoriasTop5) && arraySomaValoresCategoriasTop5.length > 0"
                                :show-currency="true"
                                :desabilitaDisplay="true"
                                :labels="arrayLabelCategoriasTop5"
                                :series="arraySomaValoresCategoriasTop5"
                                title="Top 10 Best-Selling Categories"
                            />
                        </div>

                        <div class="sm:col-span-3">
                            <PieChart
                                v-if="Array.isArray(arraySomaValoresCategoriasTop5) && arraySomaValoresCategoriasTop5.length > 0"
                                :show-currency="true"
                                :desabilitaDisplay="true"
                                :labels="arrayLabelCategoriasTop5Reverse"
                                :series="arraySomaValoresCategoriasTop5Reverse"
                                title="Top 10 Least-Selling Categories"
                            />
                        </div>
                    </form>
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
            disableCreate: false,
            openFiltro: false,
            openModalCategoria: false,

            headersCategoria: [
                { label: 'Category', key: 'categoria' },
                { label: '', key: 'acoes', customRender: 'acoes' },
            ],
            itemsCategoria: [],
            loadCategoria: true,

            headersDadosFinanceiro: [
                { label: 'Category', key: 'categoria' },
                { label: 'Amount', key: 'valorFormatado' },
                { label: 'Date', key: 'dataFormatada' },
                { label: 'Tipo', key: 'tipo' },
            ],
            itemsControleFinanceiro: [],
            loadControleFinanceiro: true,

            dtHoje: null,
            user: null,
            userId: null,
            dataInicio: null,
            dataFim: null,
            selectTipo: null,
            valor: null,
            categoria: null,
            novaCategoria: null,
            data: null,
            optionsCategoria: [],
            arrayLabelCategoriasTop5: [],
            arraySomaValoresCategoriasTop5: [],
            arrayLabelCategoriasTop5Reverse: [],
            arraySomaValoresCategoriasTop5Reverse: [],
        };
    },

    mounted() {
        const userProps = usePage();
        this.user = userProps.props.auth.user?.name ?? null;
        this.userId = userProps.props.auth.user?.id ?? null;
        document.addEventListener('click', this.handleClickOutside);

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
        this.data = this.dtHoje;

        this.inicia();
    },

    beforeUnmount() {
        document.removeEventListener('click', this.handleClickOutside);
    },

    methods: {
        async inicia() {
            if (!this.user) {
                return this.$msg.info('Please log in before proceeding.');
            }
            await this.allCategorias();
            await this.buscaFiltros();
        },

        handleClickOutside(event) {
            const dropdown = this.$refs.dropdownContainer;
            if (dropdown && !dropdown.contains(event.target)) {
                this.openFiltro = false;
            }
        },

        async createDados() {
            this.disableCreate = true;
            try {
                if (!this.user) {
                    return this.$msg.info('Please log in before proceeding.');
                }
                await axios.post('/create-controle-financeiro', {
                    selectTipo: this.selectTipo,
                    valor: this.valor,
                    categoria: this.categoria,
                    data: this.data,
                });
            } catch (error) {
                this.$msg.info('Error creating record.');
            } finally {
                this.disableCreate = true;
            }
        },

        async createCategoria() {
            this.loadCategoria = true;
            try {
                await axios.post('/create-categoria', { novaCategoria: this.novaCategoria, userId: this.userId });
                this.allCategorias();
                this.novaCategoria = null;
            } catch {
                this.$msg.info('Error creating record.');
                this.loadCategoria = false;
            }
        },

        async allCategorias() {
            this.loadCategoria = true;
            try {
                const response = await axios.get('/all-categoria');
                if (Array.isArray(response.data) && response.data.length > 0) {
                    this.itemsCategoria = response.data;
                    this.optionsCategoria = response.data.map(item => ({
                        value: item.categoria,
                        label: item.categoria,
                    }));
                }
            } finally {
                this.loadCategoria = false;
            }
        },

        async buscaFiltros() {
            this.loadControleFinanceiro = true;
            this.itemsControleFinanceiro = [];
            this.arrayLabelCategoriasTop5 = [];
            this.arraySomaValoresCategoriasTop5 = [];
            this.arrayLabelCategoriasTop5Reverse = [];
            this.arraySomaValoresCategoriasTop5Reverse = [];
            try {
                const response = await axios.post('/busca-controle-financeiro', { dataInicio: this.dataInicio, dataFim: this.dataFim });

                if (Array.isArray(response.data.query) && response.data.query.length > 0) {
                    this.itemsControleFinanceiro = [];
                    this.itemsControleFinanceiro = response.data.query.map(w => ({
                        categoria: w.categoria,
                        valorFormatado: isNaN(parseFloat(w.valor))
                            ? 'R$ 0,00'
                            : parseFloat(w.valor).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }),
                        dataFormatada: w.data,
                        // dataFormatada: w.data.split('-').reverse().join('/'),
                        tipo: w.tipo,
                    }));

                    const somaCategorias = response.data.somaCategoria || [];

                    this.arrayLabelCategoriasTop5 = [];
                    this.arraySomaValoresCategoriasTop5 = [];
                    this.arrayLabelCategoriasTop5Reverse = [];
                    this.arraySomaValoresCategoriasTop5Reverse = [];

                    somaCategorias.slice(0, 10).forEach(item => {
                        this.arrayLabelCategoriasTop5.push(item.categoria);
                        this.arraySomaValoresCategoriasTop5.push(item.valor);
                    });

                    somaCategorias.slice(-10).forEach(item => {
                        this.arrayLabelCategoriasTop5Reverse.push(item.categoria);
                        this.arraySomaValoresCategoriasTop5Reverse.push(item.valor);
                    });
                }
            } catch {
                this.$msg.info('Error fetching record.');
            } finally {
                this.loadControleFinanceiro = false;
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
                this.$msg.info('Error deleting record.');
                this.loadCategoria = false;
            }
        },

        limparFiltros() {
            this.dataInicio = null;
            this.dataFim = null;
            this.buscaFiltros();
        },
    },
};
</script>
