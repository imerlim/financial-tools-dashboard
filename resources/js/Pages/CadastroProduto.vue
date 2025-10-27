<template>
    <div class="bg-slate-100 dark:bg-slate-800">
        <GlobalMsg></GlobalMsg>
        <main>
            <div class="relative isolate overflow-hidden text-white bg-slate-100 dark:bg-slate-800 min-h-screen pt-32 sm:px-5 sm:pt-0 divide-y">
                <!-- <div class="grid grid-cols-1 gap-x-8 gap-y-8 px-4 py-11 sm:px-6 md:grid-cols-6 lg:px-8">
                    <div class="sm:col-span-6 text-xl">
                        <legend class="text-slate-900 dark:text-white">Calculo preço de custo ingredientes</legend>
                    </div>

                    <div class="sm:col-span-1 sm:col-start-1">
                        <CustomInput inputmode="numeric" type="text" @change="calculaPrecoVenda()" :formata="true" v-model="custo" label="Preço" id="custo" name="custo" maxlength="10">
                            <template #prepend>
                                <span class="text-base">R$</span>
                            </template>
                        </CustomInput>
                    </div>

                    <div class="sm:col-span-1">
                        <CustomInput :propLargeAppend="true" inputmode="numeric" type="text" @change="calculaPrecoVenda()" :formata="true" v-model="custo" label="Qtd (gramas)" id="custo" name="custo" maxlength="10">
                            <template #append>
                                <CustomSelect
                                    :options="[
                                        { label: 'Gramas', value: 'Gramas' },
                                        { label: 'Ml', value: 'Ml' },
                                    ]"
                                    v-model="taxaJurosSelect"
                                    id="taxaJurosSelect"
                                    name="taxaJurosSelect"
                                ></CustomSelect>
                            </template>
                        </CustomInput>
                    </div>

                    <div class="sm:col-span-6">
                        <Table :headers="headersCaixa" :items="itemsCaixa" :per-page="5" :show-search="true" :loading="loadCaixa" :multi-select="false" />
                    </div>
                </div> -->

                <div class="grid grid-cols-1 gap-x-8 gap-y-8 px-4 py-11 sm:px-6 md:grid-cols-6 lg:px-8">
                    <div class="sm:col-span-6 text-xl">
                        <legend class="text-slate-900 dark:text-white">{{ tituloMargem }}</legend>
                    </div>

                    <div class="sm:col-span-1">
                        <CustomSelect
                            @change="mudaTipoMargem()"
                            v-model="selectTipoMargem"
                            id="selectTipoMargem"
                            name="selectTipoMargem"
                            :options="[
                                { label: 'Margem venda', value: 'mlv' },
                                { label: 'Margem custo', value: 'mlc' },
                            ]"
                        >
                        </CustomSelect>
                    </div>

                    <div class="sm:col-span-1 sm:col-start-1">
                        <CustomInput inputmode="numeric" type="text" @change="calculaPrecoVenda()" :formata="true" v-model="custo" label="Preço de custo" id="custo" name="custo" maxlength="10">
                            <template #prepend>
                                <span class="text-base">R$</span>
                            </template>
                        </CustomInput>
                    </div>

                    <div class="sm:col-span-1">
                        <CustomInput inputmode="numeric" @change="calculaPrecoVenda()" :formata="true" v-model="margem" label="Margem" id="margem" name="margem">
                            <template #append>
                                <span class="text-base">%</span>
                            </template>
                        </CustomInput>
                    </div>
                    <div class="sm:col-span-1">
                        <CustomInput inputmode="numeric" @change="calculaMargens()" :formata="true" v-model="precoVenda" label="Preço de venda" id="precoVenda" name="precoVenda">
                            <template #prepend>
                                <span class="text-base">R$</span>
                            </template>
                        </CustomInput>
                    </div>
                    <div class="sm:col-span-1">
                        <CustomInput inputmode="numeric" @change="descontoIfood()" :formata="true" v-model="taxaDesconto" label="Desconto" id="taxaDesconto" name="taxaDesconto" title="Taxa sobre preço de venda">
                            <template #append>
                                <span class="text-base">%</span>
                            </template>
                        </CustomInput>
                    </div>
                    <div class="sm:col-span-1">
                        <CustomInput inputmode="numeric" disabled v-model="lucro" :formata="true" label="Lucro" id="lucro" name="lucro">
                            <template #prepend>
                                <span class="text-base">R$</span>
                            </template>
                        </CustomInput>
                    </div>
                    <div class="sm:col-span-1">
                        <CustomInput inputmode="numeric" disabled v-model="margemLiquida" :formata="true" label="Margem líquida" id="margemLiquida" name="margemLiquida" title="Margem após desconto da taxa ifood">
                            <template #append>
                                <span class="text-base">%</span>
                            </template>
                        </CustomInput>
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
            tituloMargem: 'Margem sobre o preço de venda',
            selectTipoMargem: 'mlv',
            custo: null,
            margem: null,
            precoVenda: null,
            lucro: null,
            taxaDesconto: null,
            margemLiquida: null,

            /* ---------------------- */
            checkQtd: false,
            labelQtd: 'Qtd em gramas',

            loadCaixa: false,
            headersCaixa: [
                { label: 'Pedido', key: 'PEDIDO' },
                { label: 'Data', key: 'DATA' },
                { label: 'Valor', key: 'VALORFORMATADO' },
                { label: 'Operador', key: 'OPERADOR' },
                { label: 'Pagamento', key: 'FORMA' },
                { label: 'Descrição', key: 'PAG' },
            ],
            itemsCaixa: [
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
        mudaTipoMargem() {
            let self = this;

            self.custo = null;
            self.margem = null;
            self.margemLiquida = null;
            self.precoVenda = null;
            self.lucro = null;
            self.taxaDesconto = null;
            if (self.selectTipoMargem == 'mlc') {
                self.tituloMargem = 'Margem sobre o preço de custo';
            } else {
                self.tituloMargem = 'Margem sobre o preço de venda';
            }
        },

        calculaPrecoVenda() {
            let self = this;

            console.log(self.custo, self.margem);
            if (self.selectTipoMargem == 'mlv') {
                const custo = parseFloat(self.custo);

                const margem = parseFloat(self.margem);
                console.log(custo, margem);

                if (custo <= 0) {
                    self.custo = null;
                    return self.$msg.warning('Custo precisa ser maior do que 0.');
                }
                if (margem <= 0) {
                    self.margem = null;
                    self.margemLiquida = null;
                    return self.$msg.warning('Margem venda precisa ser maior do que 0.');
                }
                if (margem >= 100) {
                    self.margem = null;
                    self.margemLiquida = null;
                    return self.$msg.warning('Margem venda precisa ser menor do que 100.');
                }

                if (custo > 0 && margem > 0 && margem < 100) {
                    const mlv_decimal = margem / 100;
                    // Fórmula: P.V. = Custo / (1 - MLV)
                    const precoCalculado = custo / (1 - mlv_decimal);
                    self.precoVenda = precoCalculado;
                    self.precoVenda = precoCalculado;

                    const lucroCalculado = precoCalculado - custo;
                    self.lucro = lucroCalculado;
                    self.lucro = lucroCalculado;

                    self.descontoIfood();
                } else {
                    self.precoVenda = null;
                    self.lucro = null;
                }
            } else {
                const custo = parseFloat(self.custo);
                const margem = parseFloat(self.margem); // margemCusto

                if (custo <= 0) {
                    self.custo = null;
                    return self.$msg.warning('Custo precisa ser maior do que 0.');
                }
                if (margem <= 0) {
                    self.margem = null;
                    self.margemLiquida = null;
                    return self.$msg.warning('Margem venda precisa ser maior do que 0.');
                }

                if (custo > 0 && margem > 0) {
                    const mlc_decimal = margem / 100;
                    // Fórmula do Markup: P.V. = Custo * (1 + MLC)
                    const precoCalculado = custo * (1 + mlc_decimal);
                    self.precoVenda = precoCalculado;

                    const lucroCalculado = precoCalculado - custo;
                    self.lucro = lucroCalculado;
                    self.descontoIfood();
                } else {
                    self.precoVenda = null;
                    self.lucro = null;
                }
            }
        },

        calculaMargens() {
            let self = this;

            if (self.selectTipoMargem == 'mlv') {
                const custo = parseFloat(self.custo);
                const precoVenda = parseFloat(self.precoVenda);

                if (custo <= 0) {
                    self.precoVenda = null;
                    return self.$msg.warning('Custo precisa ser maior do que 0.');
                }
                if (custo > precoVenda) {
                    self.precoVenda = null;
                    return self.$msg.warning('Preço de venda precisa ser maior do que o preço de custo.');
                }
                if (custo > 0 && precoVenda > custo) {
                    const lucroBruto = precoVenda - custo;
                    self.lucro = lucroBruto;

                    // MLV = (Lucro / Preço de Venda) * 100
                    const mlv_calculada = (lucroBruto / precoVenda) * 100;
                    self.margem = mlv_calculada;
                    self.margemLiquida = self.margem;
                    self.descontoIfood();
                } else {
                    self.margem = null;
                    self.margemLiquida = null;
                }
            } else {
                const custo = parseFloat(self.custo);
                const precoVenda = parseFloat(self.precoVenda);

                if (custo <= 0) {
                    self.precoVenda = null;
                    return self.$msg.warning('Custo precisa ser maior do que 0.');
                }
                if (custo > precoVenda) {
                    self.precoVenda = null;
                    return self.$msg.warning('Preço de venda precisa ser maior do que o preço de custo.');
                }
                if (custo > 0 && precoVenda > custo) {
                    const lucroBruto = precoVenda - custo;
                    self.lucro = lucroBruto;

                    // MLC = (Lucro / Custo) * 100
                    const mlc_calculada = (lucroBruto / custo) * 100;
                    self.margem = mlc_calculada;
                    self.margemLiquida = self.margem;
                    self.descontoIfood();
                } else {
                    self.margem = null;
                    self.margemLiquida = null;
                }
            }
        },

        descontoIfood() {
            let self = this;

            const taxa = isNaN(parseFloat(self.taxaDesconto) / 100) ? 0 : parseFloat(self.taxaDesconto) / 100;
            const preco = parseFloat(self.precoVenda);
            const custo = parseFloat(self.custo);

            if (taxa >= 1) {
                self.taxaDesconto = null;
                return self.$msg.warning('Taxa do ifood precisa ser menor do que o 100%.');
            }

            if (isNaN(preco) || preco <= 0 || isNaN(custo) || custo < 0 || isNaN(taxa) || taxa >= 100) {
                self.lucro = 0;
                return;
            }

            const valorDesconto = preco * taxa;

            const lucroBruto = preco - custo - valorDesconto;

            const margemLiq = this.selectTipoMargem == 'mlv' ? (lucroBruto / preco) * 100 : (lucroBruto / custo) * 100;

            self.margemLiquida = margemLiq;
            self.lucro = lucroBruto;
        },
    },
};
</script>
