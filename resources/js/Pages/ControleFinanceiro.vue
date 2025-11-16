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
            openModalCategoria: false,

            loadDadosFinanceiro: false,
            headersDadosFinanceiro: [
                { label: 'Nome', key: 'PEDIDO' },
                { label: 'Data', key: 'DATA' },
                { label: 'Valor', key: 'VALORFORMATADO' },
                { label: 'Operador', key: 'OPERADOR' },
                { label: 'Pagamento', key: 'FORMA' },
                { label: 'Descrição', key: 'PAG' },
            ],
            itemsDadosFinanceiro: [
                {
                    PEDIDO: 'teste',
                    DATA: 'teste',
                    VALOR: 'teste',
                    VALORFORMATADO: 'teste',
                    OPERADOR: 'teste',
                    FORMA: 'teste',
                    PAG: 'teste',
                },
            ],
            loadCategoria: false,

            user: null,
            selectTipo: null,
            valor: null,
            novaCategoria: null,
        };
    },

    mounted() {
        const userProps = usePage();
        this.user = userProps.props.auth.user?.name ?? null;
    },

    methods: {
        /* CREATE */
        createDados() {
            if (this.user == null) {
                this.$msg.warning('Realize login antes de processeguir!');
            }
        },

        async createCategoria() {
            this.loadCategoria = true;
            try {
                await this.$axios.post('/create-categoria', { novaCategoria: this.novaCategoria });
                this.allCategorias();
            } catch {
                this.loadCategoria = false;
            }
        },
    },
};
</script>
