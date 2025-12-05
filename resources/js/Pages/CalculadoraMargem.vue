<template>
    <Head title="Calculadora margem" />
    <div class="bg-slate-100 dark:bg-slate-900">
        <main>
            <div
                class="relative isolate overflow-hidden text-white bg-slate-100 dark:bg-slate-900 min-h-screen py-5 sm:px-5 sm:py-2 divide-y"
            >
                <div class="container mx-auto sm:px-16">
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
                                    { label: 'Sale Margin', value: 'mlv' },
                                    { label: 'Cost Margin', value: 'mlc' },
                                ]"
                            >
                            </CustomSelect>
                        </div>

                        <div class="sm:col-span-1 sm:col-start-1">
                            <CustomInput
                                inputmode="numeric"
                                placeholder="0,00"
                                type="text"
                                @change="calculaPrecoVenda()"
                                :formata="true"
                                v-model="custo"
                                label="Cost Price"
                                id="custo"
                                name="custo"
                                maxlength="10"
                            >
                                <template #prepend>
                                    <span class="text-base">R$</span>
                                </template>
                            </CustomInput>
                        </div>

                        <div class="sm:col-span-1">
                            <CustomInput
                                inputmode="numeric"
                                placeholder="0,00"
                                @change="calculaPrecoVenda()"
                                :formata="true"
                                v-model="margem"
                                label="Margin"
                                id="margem"
                                name="margem"
                            >
                                <template #append>
                                    <span class="text-base">%</span>
                                </template>
                            </CustomInput>
                        </div>
                        <div class="sm:col-span-1">
                            <CustomInput
                                inputmode="numeric"
                                placeholder="0,00"
                                @change="calculaMargens()"
                                :formata="true"
                                v-model="precoVenda"
                                label="Sale Price"
                                id="precoVenda"
                                name="precoVenda"
                            >
                                <template #prepend>
                                    <span class="text-base">R$</span>
                                </template>
                            </CustomInput>
                        </div>
                        <div class="sm:col-span-1">
                            <CustomInput
                                inputmode="numeric"
                                placeholder="0,00"
                                @change="descontoIfood()"
                                :formata="true"
                                v-model="taxaDesconto"
                                label="Discount"
                                id="taxaDesconto"
                                name="taxaDesconto"
                                title="Taxa sobre preço de venda"
                            >
                                <template #append>
                                    <span class="text-base">%</span>
                                </template>
                            </CustomInput>
                        </div>
                        <div class="sm:col-span-1">
                            <CustomInput
                                inputmode="numeric"
                                placeholder="0,00"
                                disabled
                                v-model="lucro"
                                :formata="true"
                                label="Profit"
                                id="lucro"
                                name="lucro"
                            >
                                <template #prepend>
                                    <span class="text-base">R$</span>
                                </template>
                            </CustomInput>
                        </div>
                        <div class="sm:col-span-1">
                            <CustomInput
                                inputmode="numeric"
                                placeholder="0,00"
                                disabled
                                v-model="margemLiquida"
                                :formata="true"
                                label="Net Margin"
                                id="margemLiquida"
                                name="margemLiquida"
                            >
                                <template #append>
                                    <span class="text-base">%</span>
                                </template>
                            </CustomInput>
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
            tituloMargem: 'Margin over sale price',
            selectTipoMargem: 'mlv',
            custo: null,
            margem: null,
            precoVenda: null,
            lucro: null,
            taxaDesconto: null,
            margemLiquida: null,

            /* ---------------------- */
            checkQtd: false,
            labelQtd: 'Qty in grams',
        };
    },

    methods: {
        mudaTipoMargem() {
            this.custo = null;
            this.margem = null;
            this.margemLiquida = null;
            this.precoVenda = null;
            this.lucro = null;
            this.taxaDesconto = null;
            if (this.selectTipoMargem == 'mlc') {
                this.tituloMargem = 'Margin over cost price';
            } else {
                this.tituloMargem = 'Margin over sale price';
            }
        },

        calculaPrecoVenda() {
            if (this.selectTipoMargem == 'mlv') {
                const custo = parseFloat(this.custo);

                const margem = parseFloat(this.margem);

                if (custo <= 0) {
                    this.custo = null;
                    return this.$msg.warning('Cost must be greater than 0.');
                }
                if (margem <= 0) {
                    this.margem = null;
                    this.margemLiquida = null;
                    return this.$msg.warning('Sale margin must be greater than 0.');
                }
                if (margem >= 100) {
                    this.margem = null;
                    this.margemLiquida = null;
                    return this.$msg.warning('Sale margin must be less than 100.');
                }

                if (custo > 0 && margem > 0 && margem < 100) {
                    const mlv_decimal = margem / 100;
                    // Fórmula: P.V. = Custo / (1 - MLV)
                    const precoCalculado = custo / (1 - mlv_decimal);
                    this.precoVenda = precoCalculado;
                    this.precoVenda = precoCalculado;

                    const lucroCalculado = precoCalculado - custo;
                    this.lucro = lucroCalculado;
                    this.lucro = lucroCalculado;

                    this.descontoIfood();
                } else {
                    this.precoVenda = null;
                    this.lucro = null;
                }
            } else {
                const custo = parseFloat(this.custo);
                const margem = parseFloat(this.margem); // margemCusto

                if (custo <= 0) {
                    this.custo = null;
                    return this.$msg.warning('Cost must be greater than 0.');
                }
                if (margem <= 0) {
                    this.margem = null;
                    this.margemLiquida = null;
                    return this.$msg.warning('Sale margin must be greater than 0.');
                }

                if (custo > 0 && margem > 0) {
                    const mlc_decimal = margem / 100;
                    // Fórmula do Markup: P.V. = Custo * (1 + MLC)
                    const precoCalculado = custo * (1 + mlc_decimal);
                    this.precoVenda = precoCalculado;

                    const lucroCalculado = precoCalculado - custo;
                    this.lucro = lucroCalculado;
                    this.descontoIfood();
                } else {
                    this.precoVenda = null;
                    this.lucro = null;
                }
            }
        },

        calculaMargens() {
            if (this.selectTipoMargem == 'mlv') {
                const custo = parseFloat(this.custo);
                const precoVenda = parseFloat(this.precoVenda);

                if (custo <= 0) {
                    this.precoVenda = null;
                    return this.$msg.warning('Cost must be greater than 0.');
                }
                if (custo > precoVenda) {
                    this.precoVenda = null;
                    return this.$msg.warning('Sale price must be greater than cost price.');
                }
                if (custo > 0 && precoVenda > custo) {
                    const lucroBruto = precoVenda - custo;
                    this.lucro = lucroBruto;

                    // MLV = (Lucro / Preço de Venda) * 100
                    const mlv_calculada = (lucroBruto / precoVenda) * 100;
                    this.margem = mlv_calculada;
                    this.margemLiquida = this.margem;
                    this.descontoIfood();
                } else {
                    this.margem = null;
                    this.margemLiquida = null;
                }
            } else {
                const custo = parseFloat(this.custo);
                const precoVenda = parseFloat(this.precoVenda);

                if (custo <= 0) {
                    this.precoVenda = null;
                    return this.$msg.warning('Cost must be greater than 0.');
                }
                if (custo > precoVenda) {
                    this.precoVenda = null;
                    return this.$msg.warning('Sale price must be greater than cost price.');
                }
                if (custo > 0 && precoVenda > custo) {
                    const lucroBruto = precoVenda - custo;
                    this.lucro = lucroBruto;

                    // MLC = (Lucro / Custo) * 100
                    const mlc_calculada = (lucroBruto / custo) * 100;
                    this.margem = mlc_calculada;
                    this.margemLiquida = this.margem;
                    this.descontoIfood();
                } else {
                    this.margem = null;
                    this.margemLiquida = null;
                }
            }
        },

        descontoIfood() {
            const taxa = isNaN(parseFloat(this.taxaDesconto) / 100) ? 0 : parseFloat(this.taxaDesconto) / 100;
            const preco = parseFloat(this.precoVenda);
            const custo = parseFloat(this.custo);

            if (taxa >= 1) {
                this.taxaDesconto = null;
                return this.$msg.warning('iFood fee must be lower than 100%.');
            }

            if (isNaN(preco) || preco <= 0 || isNaN(custo) || custo < 0 || isNaN(taxa) || taxa >= 100) {
                this.lucro = 0;
                return;
            }

            const valorDesconto = preco * taxa;

            const lucroBruto = preco - custo - valorDesconto;

            const margemLiq = this.selectTipoMargem == 'mlv' ? (lucroBruto / preco) * 100 : (lucroBruto / custo) * 100;

            this.margemLiquida = margemLiq;
            this.lucro = lucroBruto;
        },
    },
};
</script>
