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
                                required
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
                                required
                                maxlength="10"
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
                                required
                                v-model="categoria"
                                label="Categoria"
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
                                            label="Nova categoria"
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
                            <CustomInput required type="date" v-model="data" label="Data" id="data" name="data"></CustomInput>
                        </div>

                        <div class="sm:col-span-1 flex justify-end self-end">
                            <PrimaryButton type="submit" :disabled="disableCreate"> Salvar </PrimaryButton>
                        </div>

                        <div ref="dropdownContainer" class="relative sm:col-span-1 sm:col-start-6 flex justify-end">
                            <FunnelIcon
                                @click="openFiltro = !openFiltro"
                                class="w-12 h-12 cursor-pointer text-sky-800 shadow-xs hover:text-sky-600"
                            ></FunnelIcon>

                            <div
                                v-if="openFiltro"
                                class="absolute top-full mt-2 min-w-[18rem] max-w-[90vw] w-max left-0 md:left-auto md:right-0 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg z-40"
                            >
                                <div class="p-4 space-y-4" @submit.prevent="submit">
                                    <div class="sm:col-span-2">
                                        <CustomSelect
                                            id="inativoFiltro"
                                            v-model="inativoFiltro"
                                            label="Status"
                                            name="inativoFiltro"
                                            :options="[
                                                { value: 'N', label: 'Ativo' },
                                                { value: 'S', label: 'Inativo' },
                                            ]"
                                        />
                                    </div>

                                    <div class="sm:col-span-2">
                                        <CustomSelect
                                            id="inadimplenteFiltro"
                                            v-model="inadimplenteFiltro"
                                            label="Situação"
                                            name="inadimplenteFiltro"
                                            :options="[
                                                { value: 'N', label: 'Adimplente' },
                                                { value: 'S', label: 'Inadimplente' },
                                            ]"
                                        />
                                    </div>

                                    <div class="flex justify-end gap-x-2">
                                        <button
                                            type="button"
                                            class="inline-flex items-center rounded-md bg-white px-3 py-2 text-base font-semibold text-gray-900 shadow-xs ring-1 ring-gray-300 ring-inset hover:bg-gray-50"
                                            @click="limparFiltros()"
                                        >
                                            Limpar
                                        </button>

                                        <button
                                            type="button"
                                            class="rounded-md bg-indigo-600 px-3 py-2 text-base font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 inline-flex"
                                            @click="buscaFiltros()"
                                        >
                                            Buscar
                                        </button>
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

                        <div class="sm:col-span-2">
                            <PieChart
                                v-if="Array.isArray(arraySomaValoresCategorias) && arraySomaValoresCategorias.length > 0"
                                :show-currency="true"
                                :desabilitaDisplay="true"
                                :labels="arrayLabelCategorias"
                                :series="arraySomaValoresCategorias"
                                title="Top 5 categorias mais vendidas"
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
                { label: 'Categoria', key: 'categoria' },
                { label: '', key: 'acoes', customRender: 'acoes' },
            ],
            itemsCategoria: [],
            loadCategoria: true,

            headersDadosFinanceiro: [
                { label: 'Categoria', key: 'categoria' },
                { label: 'Valor', key: 'valorFormatado' },
                { label: 'Data', key: 'dataFormatada' },
                { label: 'Tipo', key: 'tipo' },
            ],
            itemsControleFinanceiro: [],
            loadControleFinanceiro: true,

            dtHoje: null,
            user: null,
            userId: null,
            selectTipo: null,
            valor: null,
            categoria: null,
            novaCategoria: null,
            data: null,
            optionsCategoria: [],
            arrayLabelCategorias: [],
            arraySomaValoresCategorias: [],
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
            await this.allCategorias();
            await this.allControleFinanceiro();
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
                    return this.$msg.warning('Realize login antes de processeguir.');
                }
                await axios.post('/create-controle-financeiro', {
                    selectTipo: this.selectTipo,
                    valor: this.valor,
                    categoria: this.categoria,
                    data: this.data,
                });
            } catch (error) {
                this.$msg.warning('Erro ao criar registro.');
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
                this.$msg.warning('Erro ao criar registro.');
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

        async allControleFinanceiro() {
            this.loadControleFinanceiro = true;
            try {
                const response = await axios.get('/all-controle-financeiro');
                console.log(response.data);
                if (Array.isArray(response.data.query) && response.data.query.length > 0) {
                    this.itemsControleFinanceiro = response.data.query.map(w => ({
                        categoria: w.categoria,
                        valorFormatado: isNaN(parseFloat(w.valor))
                            ? 'R$ 0,00'
                            : parseFloat(w.valor).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }),
                        dataFormatada: w.data.split('-').reverse().join('/'),
                        tipo: w.tipo,
                    }));

                    for (var w = 0; w < 5; w++) {
                        this.arrayLabelCategorias.push(response.data.somaCategoria[w].categoria);
                        this.arraySomaValoresCategorias.push(response.data.somaCategoria[w].valor);
                    }
                }
            } finally {
                this.loadControleFinanceiro = false;
            }
        },

        async handleDeleteCategoria(item) {
            console.log(item);
            this.loadCategoria = true;
            try {
                await axios.delete('/delete-categoria', {
                    data: {
                        idCategoria: item.idCategoria,
                    },
                });
                this.allCategorias();
            } catch {
                this.$msg.warning('Erro ao deletar registro.');
                this.loadCategoria = false;
            }
        },
    },
};
</script>
