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
                            <CustomInput
                                inputmode="numeric"
                                :formata="true"
                                v-model="valorInicial"
                                label="Valor inicial"
                                id="valorInicial"
                                name="valorInicial"
                                @keydown.enter="$refs.botaoIncluirItem.focus()"
                            >
                                <template #prepend>
                                    <span class="text-base">R$</span>
                                </template>
                            </CustomInput>
                        </div>
                        <div class="sm:col-span-6 flex justify-end self-end mt-5">
                            <button
                                ref="botaoIncluirItem"
                                type="button"
                                class="cursor-pointer rounded-md ml-1 px-3 py-2 text-base font-semibold bg-sky-800 text-white shadow-xs hover:bg-sky-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-700 inline-flex"
                                @click="calculaJurosCompostos()"
                            >
                                Calcular
                            </button>
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
export default {
    data() {
        return {
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
        };
    },

    methods: {
        calculaJurosCompostos() {
            this.itemsDadosFinanceiro.push({});
        },
    },
};
</script>
