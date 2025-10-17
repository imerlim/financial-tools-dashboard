<template>
    <div class="bg-gray-900">
        <GlobalMsg></GlobalMsg>
        <main>
            <div class="relative isolate overflow-hidden text-white bg-gray-900 min-h-screen pt-32 sm:px-5 sm:pt-0 divide-y">
                <div class="grid grid-cols-1 gap-x-8 gap-y-11 px-4 py-11 sm:px-6 md:grid-cols-8 lg:px-8">
                    <div class="sm:col-span-8 text-center text-2xl">
                        <legend class="text-white">Margem sobre o preço de venda</legend>
                    </div>
                    <div class="sm:col-span-2">
                        <CustomInput type="number" @change="calculaPrecoVendaMlv()" @input="formataBrMoeda()" v-model="custoMlv" label="Preço de custo" id="custoMlv" name="custoMlv">
                            <template #prepend>
                                <span class="text-base">R$</span>
                            </template>
                        </CustomInput>
                    </div>
                    <div class="sm:col-span-2">
                        <CustomInput type="number" @change="calculaPrecoVendaMlv()" v-model="margemMlv" label="Margem venda" title="Margem sobre o preço de venda" id="margemMlv" name="margemMlv">
                            <template #append>
                                <span class="text-base">%</span>
                            </template>
                        </CustomInput>
                    </div>
                    <div class="sm:col-span-2">
                        <CustomInput type="number" @change="calculaMargensMlv()" v-model="precoVendaMlv" label="Preço de venda" id="precoVendaMlv" name="precoVendaMlv">
                            <template #prepend>
                                <span class="text-base">R$</span>
                            </template>
                        </CustomInput>
                    </div>
                    <div class="sm:col-span-2">
                        <CustomInput type="number" disabled v-model="lucroMlv" label="Lucro" id="lucroMlv" name="lucroMlv">
                            <template #prepend>
                                <span class="text-base">R$</span>
                            </template>
                        </CustomInput>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-x-8 gap-y-15 px-4 py-15 sm:px-6 md:grid-cols-8 lg:px-8">
                    <div class="sm:col-span-8 text-center text-2xl">
                        <legend class="text-white">Margem sobre o preço de custo</legend>
                    </div>
                    <div class="sm:col-span-2">
                        <CustomInput type="number" @change="calculaPrecoVendaMlc()" v-model="custoMlc" label="Preço de custo" id="custoMlc" name="custoMlc">
                            <template #prepend>
                                <span class="text-base">R$</span>
                            </template>
                        </CustomInput>
                    </div>
                    <div class="sm:col-span-2">
                        <CustomInput type="number" @change="calculaPrecoVendaMlc()" v-model="margemMlc" label="Margem custo" id="margemMlc" name="margemMlc">
                            <template #append>
                                <span class="text-base">%</span>
                            </template>
                        </CustomInput>
                    </div>
                    <div class="sm:col-span-2">
                        <CustomInput type="number" @change="calculaMargensMlc()" v-model="precoVendaMlc" label="Preço de venda" id="precoVendaMlc" name="precoVendaMlc">
                            <template #prepend>
                                <span class="text-base">R$</span>
                            </template>
                        </CustomInput>
                    </div>
                    <div class="sm:col-span-2">
                        <CustomInput type="number" disabled v-model="lucroMlc" label="Lucro" id="lucroMlc" name="lucroMlc">
                            <template #prepend>
                                <span class="text-base">R$</span>
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
            custoMlv: null,
            margemMlv: null,
            precoVendaMlv: null,
            lucroMlv: null,

            custoMlc: null,
            margemMlc: null,
            precoVendaMlc: null,
            lucroMlc: null,
        };
    },

    methods: {
        // --- CÁLCULOS DE PREÇO DE VENDA (Quando muda CUSTO ou MARGEM) ---
        formataBrMoeda() {},

        calculaPrecoVendaMlv() {
            let self = this;

            console.log('aqui');

            const custo = parseFloat(self.custoMlv);
            const margem = parseFloat(self.margemMlv);

            if (custo <= 0) {
                self.custoMlv = null;
                return self.$msg.warning('Custo precisa ser maior do que 0.');
            }
            if (margem <= 0) {
                self.margemMlv = null;
                return self.$msg.warning('Margem venda precisa ser maior do que 0.');
            }
            if (margem >= 100) {
                self.margemMlv = null;
                return self.$msg.warning('Margem venda precisa ser menor do que 100.');
            }

            if (custo > 0 && margem > 0 && margem < 100) {
                const mlv_decimal = margem / 100;
                // Fórmula: P.V. = Custo / (1 - MLV)
                const precoCalculado = custo / (1 - mlv_decimal);
                self.precoVendaMlv = precoCalculado.toFixed(2);

                const lucroCalculado = precoCalculado - custo;
                self.lucroMlv = lucroCalculado.toFixed(2);
            } else {
                self.precoVendaMlv = null;
                self.lucroMlv = null;
            }
        },

        calculaPrecoVendaMlc() {
            let self = this;

            const custo = parseFloat(self.custoMlc);
            const margem = parseFloat(self.margemMlc); // margemCusto

            if (custo <= 0) {
                self.custoMlc = null;
                return self.$msg.warning('Custo precisa ser maior do que 0.');
            }
            if (margem <= 0) {
                self.margemMlc = null;
                return self.$msg.warning('Margem venda precisa ser maior do que 0.');
            }

            if (custo > 0 && margem > 0) {
                const mlc_decimal = margem / 100;
                // Fórmula do Markup: P.V. = Custo * (1 + MLC)
                const precoCalculado = custo * (1 + mlc_decimal);
                self.precoVendaMlc = precoCalculado.toFixed(2);

                const lucroCalculado = precoCalculado - custo;
                self.lucroMlc = lucroCalculado.toFixed(2);
            } else {
                self.precoVendaMlc = null;
                self.lucroMlc = null;
            }
        },

        // --- CÁLCULOS DE MARGEM (Quando muda PREÇO DE VENDA) ---

        // Calcula Margem de Venda (MLV)
        calculaMargensMlv() {
            let self = this;

            const custo = parseFloat(self.custoMlv);
            const precoVenda = parseFloat(self.precoVendaMlv);

            if (custo <= 0) {
                self.precoVendaMlv = null;
                return self.$msg.warning('Custo precisa ser maior do que 0.');
            }
            if (custo > precoVenda) {
                self.precoVendaMlv = null;
                return self.$msg.warning('Preço de venda precisa ser maior do que o preço de custo.');
            }
            if (custo > 0 && precoVenda > custo) {
                const lucroBruto = precoVenda - custo;
                self.lucroMlv = lucroBruto.toFixed(2);

                // MLV = (Lucro / Preço de Venda) * 100
                const mlv_calculada = (lucroBruto / precoVenda) * 100;
                self.margemMlv = mlv_calculada.toFixed(2);
            } else {
                self.margemMlv = null;
            }
        },

        // Calcula Margem de Custo (MLC)
        calculaMargensMlc() {
            let self = this;

            const custo = parseFloat(self.custoMlc);
            const precoVenda = parseFloat(self.precoVendaMlc);

            if (custo <= 0) {
                self.precoVendaMlc = null;
                return self.$msg.warning('Custo precisa ser maior do que 0.');
            }
            if (custo > precoVenda) {
                self.precoVendaMlc = null;
                return self.$msg.warning('Preço de venda precisa ser maior do que o preço de custo.');
            }
            if (custo > 0 && precoVenda > custo) {
                const lucroBruto = precoVenda - custo;
                self.lucroMlc = lucroBruto.toFixed(2);

                // MLC = (Lucro / Custo) * 100
                const mlc_calculada = (lucroBruto / custo) * 100;
                self.margemMlc = mlc_calculada.toFixed(2);
            } else {
                self.margemMlc = null;
            }
        },
    },
};
</script>
