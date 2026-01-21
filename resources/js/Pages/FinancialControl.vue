<template>
    <Head title="Controle financeiro" />
    <div class="bg-slate-100 dark:bg-slate-900">
        <main>
            <div
                class="relative isolate overflow-hidden text-white dark:text-slate-300 bg-slate-100 dark:bg-slate-900 min-h-screen pt-32 sm:px-5 sm:pt-0 divide-y"
            >
                <div class="container mx-auto sm:px-16">
                    <form @submit.prevent="createDados" class="grid grid-cols-1 gap-x-8 gap-y-8 px-4 py-11 sm:px-6 lg:grid-cols-6 lg:px-8">
                        <div class="lg:col-span-1">
                            <CustomSelect
                                :disabled="!this.user"
                                required
                                v-model="selectType"
                                id="selectType"
                                name="selectType"
                                label="Type"
                                :options="[
                                    { label: 'Income', value: 'I' },
                                    { label: 'Expense', value: 'E' },
                                ]"
                            >
                            </CustomSelect>
                        </div>

                        <div class="lg:col-span-1">
                            <CustomInput
                                :disabled="!this.user"
                                required
                                maxlength="10"
                                inputmode="numeric"
                                placeholder="0,00"
                                :formata="true"
                                v-model="amountValue"
                                label="Amount"
                                id="amountValue"
                                name="amountValue"
                            >
                                <template #prepend>
                                    <span class="text-base">R$</span>
                                </template>
                            </CustomInput>
                        </div>

                        <div class="grid-col-1 lg:col-span-2">
                            <CustomSelect
                                :disabled="!this.user"
                                required
                                v-model="category"
                                label="Category"
                                id="category"
                                name="category"
                                :options="optionsCategoria"
                                :show-add="true"
                                @on-add="openCategoryModal = true"
                            ></CustomSelect>

                            <ModalMedium v-model="openCategoryModal" title="Category Registration">
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
                                        <PrimaryButton :disabled="!this.user" type="submit">Cadastrar</PrimaryButton>
                                    </div>

                                    <div class="sm:col-span-6">
                                        <Table
                                            :headers="headersCategoria"
                                            :items="itemsCategoria"
                                            :per-page="5"
                                            :loading="loadCategoria"
                                            :show-actions="true"
                                            action-type="delete"
                                            @delete="handleDeleteCategoria"
                                        />
                                    </div>
                                </form>
                            </ModalMedium>
                        </div>

                        <div class="lg:col-span-1">
                            <CustomInput
                                :disabled="!this.user"
                                required
                                type="date"
                                v-model="date"
                                label="Date"
                                id="date"
                                name="date"
                            ></CustomInput>
                        </div>

                        <div class="lg:col-span-1 flex justify-end self-end">
                            <PrimaryButton type="submit" :disabled="disableCreateuser"> Save </PrimaryButton>
                        </div>

                        <div ref="dropdownContainer" class="relative lg:col-span-1 lg:col-start-6 flex justify-end">
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
                                        <PrimaryButton type="button" @click="cleanFilter()" :disabled="!this.user"> Clean </PrimaryButton>

                                        <PrimaryButton type="button" @click="searchFilter()" :disabled="!this.user || loadFinancialControl">
                                            Search
                                        </PrimaryButton>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="grid-col-1 lg:col-span-6">
                            <Table
                                :headers="headers"
                                :items="itemsFinancialControl"
                                :per-page="5"
                                :show-search="true"
                                :loading="loadFinancialControl"
                                :show-actions="true"
                                action-type="delete"
                                @delete="handleDeleteFinancialData"
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
                    </form>
                    <div class="pb-5 grid grid-cols-1 lg:grid-cols-6 gap-4 items-start">
                        <div
                            class="lg:col-span-2"
                            v-if="Array.isArray(arraySomaValoresCategoriasTop5) && arraySomaValoresCategoriasTop5.length > 0"
                        >
                            <PieChart
                                :show-currency="true"
                                :desabilitaDisplay="true"
                                :labels="arrayLabelCategoriasTop5"
                                :series="arraySomaValoresCategoriasTop5"
                                title="Top 5 Categories"
                            />
                        </div>

                        <div class="grid-col-1 lg:col-span-2" v-if="Array.isArray(arraySumIncome) && arraySumIncome.length > 0">
                            <PieChart
                                :show-currency="true"
                                :desabilitaDisplay="true"
                                :labels="arrayLabelIncome"
                                :series="arraySumIncome"
                                title="Income per month"
                            />
                        </div>

                        <div class="grid-col-1 lg:col-span-2" v-if="Array.isArray(arraySumExpense) && arraySumExpense.length > 0">
                            <PieChart
                                :show-currency="true"
                                :desabilitaDisplay="true"
                                :labels="arrayLabelExpense"
                                :series="arraySumExpense"
                                title="Expense per month"
                            />
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
            disableCreate: false,
            openFiltro: false,
            openCategoryModal: false,

            headersCategoria: [
                { label: 'Category', key: 'category' },
                { label: '', key: 'acoes', customRender: 'acoes' },
            ],
            itemsCategoria: [],
            loadCategoria: true,

            headers: [
                { label: 'Category', key: 'category' },
                { label: 'Amount', key: 'valorFormatado' },
                { label: 'Date', key: 'dataFormatada' },
                { label: 'Type', key: 'type' },
            ],
            itemsFinancialControl: [],
            loadFinancialControl: true,

            dtHoje: null,
            user: null,
            userId: null,
            dataInicio: null,
            dataFim: null,
            selectType: null,
            amountValue: null,
            category: null,
            novaCategoria: null,
            date: null,
            optionsCategoria: [],
            arrayLabelCategoriasTop5: [],
            arraySomaValoresCategoriasTop5: [],
            arrayLabelIncome: [],
            arraySumIncome: [],
            arrayLabelExpense: [],
            arraySumExpense: [],
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
        this.date = this.dtHoje;

        this.inicia();
    },

    beforeUnmount() {
        document.removeEventListener('click', this.handleClickOutside);
    },

    methods: {
        async inicia() {
            if (!this.user) {
                return this.$msg.warning('Please log in before proceeding.');
            }
            await this.allCategorias();
            await this.searchFilter();
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
                    return this.$msg.warning('Please log in before proceeding.');
                }
                await axios.post('/create-controle-financeiro', {
                    selectType: this.selectType,
                    amountValue: this.amountValue,
                    category: this.category,
                    date: this.date,
                });
                this.searchFilter();
            } catch (error) {
                this.$msg.warning('Error creating record.');
            } finally {
                this.disableCreate = true;
            }
        },

        async createCategoria() {
            this.loadCategoria = true;
            try {
                await axios.post('/create-category', { novaCategoria: this.novaCategoria });
                this.allCategorias();
                this.novaCategoria = null;
            } catch {
                this.$msg.warning('Error creating record.');
                this.loadCategoria = false;
            }
        },

        async allCategorias() {
            this.loadCategoria = true;
            try {
                const response = await axios.get('/all-category');
                this.itemsCategoria = [];
                this.optionsCategoria = [];

                if (Array.isArray(response.data) && response.data.length > 0) {
                    this.itemsCategoria = response.data;
                    this.optionsCategoria = response.data.map(item => ({
                        value: item.category,
                        label: item.category,
                    }));
                }
            } finally {
                this.loadCategoria = false;
            }
        },

        async searchFilter() {
            this.loadFinancialControl = true;
            this.itemsFinancialControl = [];
            this.arrayLabelCategoriasTop5 = [];
            this.arraySomaValoresCategoriasTop5 = [];
            this.arrayLabelIncome = [];
            this.arraySumIncome = [];
            this.arrayLabelExpense = [];
            this.arraySumExpense = [];
            try {
                const response = await axios.post('/busca-controle-financeiro', { dataInicio: this.dataInicio, dataFim: this.dataFim });

                if (Array.isArray(response.data.query) && response.data.query.length > 0) {
                    this.itemsFinancialControl = [];
                    this.itemsFinancialControl = response.data.query.map(w => ({
                        idControle: w.idControle,
                        category: w.category,
                        valorFormatado: isNaN(parseFloat(w.amountValue))
                            ? 'R$ 0,00'
                            : parseFloat(w.amountValue).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }),
                        dataFormatada: w.date,
                        // dataFormatada: w.date.split('-').reverse().join('/'),
                        type: w.type == 'I' ? 'Income' : 'Expense',
                    }));

                    const somaCategorias = response.data.somaCategoria || [];
                    const somaPorMesExpense = response.data.somaPorMesExpense || [];
                    const somaPorMesIncome = response.data.somaPorMesIncome || [];

                    this.arrayLabelCategoriasTop5 = [];
                    this.arraySomaValoresCategoriasTop5 = [];

                    somaCategorias.slice(0, 5).forEach(item => {
                        this.arrayLabelCategoriasTop5.push(item.category);
                        this.arraySomaValoresCategoriasTop5.push(item.amountValue);
                    });

                    this.arrayLabelIncome = [];
                    this.arraySumIncome = [];

                    somaPorMesIncome.forEach(item => {
                        if (item.total_income != 0) {
                            this.arrayLabelIncome.push(item.month);
                            this.arraySumIncome.push(item.total_income);
                        }
                    });

                    somaPorMesExpense.forEach(item => {
                        if (item.total_expense != 0) {
                            this.arrayLabelExpense.push(item.month);
                            this.arraySumExpense.push(item.total_expense);
                        }
                    });
                    console.log(this.arraySumExpense);
                }
            } catch {
                this.$msg.warning('Error fetching record.');
            } finally {
                this.loadFinancialControl = false;
            }
        },

        async handleDeleteCategoria(item) {
            this.loadCategoria = true;
            try {
                await axios.delete('/delete-category', {
                    data: {
                        idCategory: item.idCategory,
                    },
                });
                this.allCategorias();
            } catch {
                this.$msg.warning('Error deleting record.');
                this.loadCategoria = false;
            }
        },

        async handleDeleteFinancialData(item) {
            console.log(item);
            this.loadFinancialControl = true;
            try {
                await axios.delete('/delete-financial-control', {
                    data: {
                        idFiancialData: item.idControle,
                    },
                });
                this.searchFilter();
            } catch {
                this.$msg.warning('Error deleting record.');
                this.loadFinancialControl = false;
            }
        },

        cleanFilter() {
            this.dataInicio = null;
            this.dataFim = null;
            this.searchFilter();
        },
    },
};
</script>
