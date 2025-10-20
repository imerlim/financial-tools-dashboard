<template>
    <div class="bg-slate-800">
        <GlobalMsg></GlobalMsg>
        <main>
            <div class="relative isolate overflow-hidden text-white bg-slate-800 min-h-screen pt-32 sm:px-5 sm:pt-0 divide-y">
                <div class="grid grid-cols-1 gap-x-8 gap-y-11 px-4 py-11 sm:px-6 md:grid-cols-6 lg:px-8">
                    <div class="sm:col-span-6 text-center text-2xl">
                        <legend class="text-white">{{ tituloMargem }}</legend>
                    </div>

                    <div class="sm:col-span-1">
                        <CustomSelect
                            @change="mudaTipoMargem()"
                            v-model="selectTipoMargem"
                            id="selectTipoMargem"
                            name="selectTipoMargem"
                            label="Tipo margem"
                            :options="[
                                { label: 'Margem venda', value: 'mlv' },
                                { label: 'Margem custo', value: 'mlc' },
                            ]"
                        >
                        </CustomSelect>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-x-8 gap-y-11 px-4 py-11 sm:px-6 md:grid-cols-6 lg:px-8">
                    <div class="sm:col-span-1">
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
                        <CustomInput inputmode="numeric" @change="descontoIfood()" :formata="true" v-model="taxaIfood" label="Taxa ifood" id="taxaIfood" name="taxaIfood">
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
                    <div class="sm:col-span-6">
                        <Table :headers="headersCaixa" :items="itemsCaixa" :per-page="5" :show-search="true" :loading="loadCaixa" :multi-select="false" />
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
            taxaIfood: null,

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
            self.precoVenda = null;
            self.lucro = null;
            self.taxaIfood = null;
            if (self.selectTipoMargem == 'mlc') {
                self.tituloMargem = 'Margem sobre o preço de custo';
            } else {
                self.tituloMargem = 'Margem sobre o preço de venda';
            }
        },

        calculaPrecoVenda() {
            let self = this;

            if (self.selectTipoMargem == 'mlv') {
                const custo = parseFloat(self.custo);
                const margem = parseFloat(self.margem);

                if (custo <= 0) {
                    self.custo = null;
                    return self.$msg.warning('Custo precisa ser maior do que 0.');
                }
                if (margem <= 0) {
                    self.margem = null;
                    return self.$msg.warning('Margem venda precisa ser maior do que 0.');
                }
                if (margem >= 100) {
                    self.margem = null;
                    return self.$msg.warning('Margem venda precisa ser menor do que 100.');
                }

                if (custo > 0 && margem > 0 && margem < 100) {
                    const mlv_decimal = margem / 100;
                    // Fórmula: P.V. = Custo / (1 - MLV)
                    const precoCalculado = custo / (1 - mlv_decimal);
                    self.precoVenda = isNaN(parseFloat(precoCalculado)) ? 'R$ 0,00' : parseFloat(precoCalculado).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

                    const lucroCalculado = precoCalculado - custo;
                    self.lucro = isNaN(parseFloat(lucroCalculado)) ? 'R$ 0,00' : parseFloat(lucroCalculado).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
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
                    return self.$msg.warning('Margem venda precisa ser maior do que 0.');
                }

                if (custo > 0 && margem > 0) {
                    const mlc_decimal = margem / 100;
                    // Fórmula do Markup: P.V. = Custo * (1 + MLC)
                    const precoCalculado = custo * (1 + mlc_decimal);
                    self.precoVenda = isNaN(parseFloat(precoCalculado)) ? 'R$ 0,00' : parseFloat(precoCalculado).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

                    const lucroCalculado = precoCalculado - custo;
                    self.lucro = isNaN(parseFloat(lucroCalculado)) ? 'R$ 0,00' : parseFloat(lucroCalculado).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
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
                    self.lucro = isNaN(parseFloat(lucroBruto)) ? 'R$ 0,00' : parseFloat(lucroBruto).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                    self.descontoIfood();

                    // MLV = (Lucro / Preço de Venda) * 100
                    const mlv_calculada = (lucroBruto / precoVenda) * 100;
                    self.margem = isNaN(parseFloat(mlv_calculada)) ? 'R$ 0,00' : parseFloat(mlv_calculada).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                } else {
                    self.margem = null;
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
                    self.lucro = isNaN(parseFloat(lucroBruto)) ? 'R$ 0,00' : parseFloat(lucroBruto).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                    self.descontoIfood();

                    // MLC = (Lucro / Custo) * 100
                    const mlc_calculada = (lucroBruto / custo) * 100;
                    self.margem = isNaN(parseFloat(mlc_calculada)) ? 'R$ 0,00' : parseFloat(mlc_calculada).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                } else {
                    self.margem = null;
                }
            }
        },

        descontoIfood() {
            let self = this;

            const taxa = parseFloat(self.taxaIfood) / 100;
            const preco = parseFloat(self.precoVenda);
            const custo = parseFloat(self.custo);

            if (taxa >= 1) {
                self.taxaIfood = null;
                return self.$msg.warning('Taxa do ifood precisa ser menor do que o 100%.');
            }

            if (isNaN(preco) || preco <= 0 || isNaN(custo) || custo < 0 || isNaN(taxa) || taxa >= 100) {
                self.lucro = 0;
                return;
            }

            const valorDesconto = preco * taxa;

            const lucroBruto = preco - custo - valorDesconto;

            self.lucro = isNaN(parseFloat(lucroBruto)) ? 'R$ 0,00' : parseFloat(lucroBruto).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        },
    },
};
</script>
