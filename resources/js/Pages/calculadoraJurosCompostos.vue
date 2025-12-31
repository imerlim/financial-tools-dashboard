<template>
    <Head title="Compound Interest Calculator" />
    <div class="bg-slate-100 dark:bg-slate-900">
        <main>
            <div
                class="relative isolate overflow-hidden text-white dark:text-slate-300 bg-slate-100 dark:bg-slate-900 min-h-screen pt-32 sm:px-5 sm:pt-0 divide-y"
            >
                <div class="container mx-auto sm:px-16">
                    <div class="grid grid-cols-1 gap-x-8 gap-y-8 px-4 py-11 sm:px-6 md:grid-cols-4 lg:px-8">
                        <div class="sm:col-span-4 text-center text-2xl">
                            <legend class="text-slate-900 dark:text-white">Compound Interest Calculator</legend>
                        </div>
                        <div class="sm:col-span-4 justify-items-center" v-show="montanteFinal != 'R$ 0,00' && montanteFinal != 'R$ 0,00'">
                            <div class="grid grid-cols-1 gap-3 overflow-hidden rounded-2xl text-center sm:grid-cols-2 lg:grid-cols-3">
                                <div class="flex flex-col bg-slate-400/5 p-8 dark:bg-white/5">
                                    <dt class="text-2xl font-semibold text-slate-900 dark:text-white">Invested Amount</dt>
                                    <dd class="order-first text-3xl font-semibold tracking-tight text-slate-900 dark:text-white">
                                        {{ valorTotalInvestido }}
                                    </dd>
                                </div>
                                <div id="montanteDiv" class="flex flex-col bg-slate-400/5 p-8 dark:bg-white/5">
                                    <dt class="text-2xl font-semibold text-sky-500">Final Amount</dt>
                                    <dd class="order-first text-3xl font-semibold tracking-tight text-sky-500">
                                        {{ montanteFinal }}
                                    </dd>
                                </div>
                                <div class="flex flex-col bg-slate-400/5 p-8 dark:bg-white/5">
                                    <dt class="text-2xl font-semibold text-slate-900 dark:text-white">Total Interest</dt>
                                    <dd class="order-first text-3xl font-semibold tracking-tight text-slate-900 dark:text-white">
                                        {{ totalEmJuros }}
                                    </dd>
                                </div>
                            </div>
                        </div>
                        <div class="sm:col-span-1">
                            <CustomInput
                                @keyup.enter="calculaJurosCompostos"
                                inputmode="numeric"
                                placeholder="0,00"
                                :formata="true"
                                v-model="valorInicial"
                                label="Initial Amount"
                                id="valorInicial"
                                name="valorInicial"
                            >
                                <template #prepend>
                                    <span class="text-base">R$</span>
                                </template>
                            </CustomInput>
                        </div>
                        <div class="sm:col-span-1">
                            <CustomInput
                                @keyup.enter="calculaJurosCompostos"
                                inputmode="numeric"
                                placeholder="0,00"
                                :formata="true"
                                v-model="valorMensal"
                                label="Monthly Amount"
                                id="valorMensal"
                                name="valorMensal"
                            >
                                <template #prepend>
                                    <span class="text-base">R$</span>
                                </template>
                            </CustomInput>
                        </div>
                        <div class="sm:col-span-1">
                            <CustomInput
                                @keyup.enter="calculaJurosCompostos"
                                inputmode="numeric"
                                placeholder="0,00"
                                :formata="true"
                                :propLargeAppend="true"
                                v-model="taxaJuros"
                                label="Interest Rate"
                                id="taxaJuros"
                                name="taxaJuros"
                            >
                                <template #append>
                                    <CustomSelect
                                        :options="[
                                            { label: 'Monthly', value: 'Mensal' },
                                            { label: 'Annual', value: 'Anual' },
                                        ]"
                                        v-model="taxaJurosSelect"
                                        id="taxaJurosSelect"
                                        name="taxaJurosSelect"
                                    ></CustomSelect>
                                </template>
                            </CustomInput>
                        </div>
                        <div class="sm:col-span-1">
                            <CustomInput
                                @keyup.enter="calculaJurosCompostos"
                                type="number"
                                placeholder="0"
                                :propLargeAppend="true"
                                v-model="periodo"
                                label="Period"
                                id="periodo"
                                name="periodo"
                            >
                                <template #append>
                                    <CustomSelect
                                        :options="[
                                            { label: 'Years', value: 'Anos' },
                                            { label: 'Months', value: 'Meses' },
                                        ]"
                                        v-model="periodoSelect"
                                        id="periodoSelect"
                                        name="periodoSelect"
                                    ></CustomSelect>
                                </template>
                            </CustomInput>
                        </div>
                        <div class="sm:col-span-4 flex justify-end self-end mt-5">
                            <button
                                ref="botaoIncluirItem"
                                type="button"
                                class="cursor-pointer rounded-md ml-1 px-3 py-2 text-base font-semibold bg-sky-800 text-white shadow-xs hover:bg-sky-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-700 inline-flex"
                                @click="calculaJurosCompostos()"
                            >
                                Calcular
                            </button>
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
            valorInicial: null,
            valorMensal: null,
            taxaJuros: null,
            periodo: null,
            taxaJurosSelect: 'Mensal',
            periodoSelect: 'Anos',
            montanteFinal: 'R$ 0,00',
            valorTotalInvestido: 'R$ 0,00',
            totalEmJuros: 'R$ 0,00',
        };
    },

    methods: {
        calculaJurosCompostos() {
            if (!this.valorInicial && !this.valorMensal) {
                return this.$msg.warning('You need to enter at least one value: initial or monthly.');
            }
            if (!this.taxaJuros) {
                return this.$msg.warning('You need to enter an interest rate.');
            }
            if (!this.periodo) {
                return this.$msg.warning('You need to enter a periodo.');
            }
            console.log(this.valorInicial);
            console.log(this.valorMensal);
            console.log(this.taxaJuros);
            console.log(this.periodo);
            const C = parseFloat(this.valorInicial);
            if (!this.valorMensal) {
                this.valorMensal = 0;
            }
            const A = parseFloat(this.valorMensal);

            const i =
                this.taxaJurosSelect == 'Mensal'
                    ? parseFloat(this.taxaJuros) / 100
                    : Math.pow(1 + parseFloat(this.taxaJuros) / 100, 1 / 12) - 1;
            const t = this.periodoSelect == 'Anos' ? parseFloat(this.periodo) * 12 : parseFloat(this.periodo);

            const M = C * (1 + i) ** t + (A / i) * ((1 + i) ** t - 1);
            const valorTotalInvestido = C + A * t;
            const totalEmJuros = M - valorTotalInvestido;

            this.montanteFinal = isNaN(parseFloat(M)) ? 'R$ 0,00' : M.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
            this.valorTotalInvestido = valorTotalInvestido.toLocaleString('pt-BR', {
                style: 'currency',
                currency: 'BRL',
            });
            this.totalEmJuros = totalEmJuros.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
            this.$nextTick(() => {
                document.getElementById('montanteDiv').scrollIntoView({ behavior: 'smooth', block: 'center' });
            });
        },
    },
};
</script>
