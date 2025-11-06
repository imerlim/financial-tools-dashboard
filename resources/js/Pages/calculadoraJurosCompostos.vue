<template>
    <Head title="Calculadora juros compostos" />
    <div class="bg-slate-100 dark:bg-slate-900">
        <GlobalMsg></GlobalMsg>
        <main>
            <div
                class="relative isolate overflow-hidden text-white dark:text-slate-300 bg-slate-100 dark:bg-slate-900 min-h-screen pt-32 sm:px-5 sm:pt-0 divide-y"
            >
                <div class="grid grid-cols-1 gap-x-8 gap-y-8 px-4 py-11 sm:px-6 md:grid-cols-4 lg:px-8">
                    <div class="sm:col-span-4 text-center text-2xl">
                        <legend class="text-slate-900 dark:text-white">Calculadora juros compostos</legend>
                    </div>
                    <div
                        class="sm:col-span-4 justify-items-center"
                        v-if="montanteFinal != 'R$ 0,00' && montanteFinal != 'R$ 0,00'"
                    >
                        <div
                            class="grid grid-cols-1 gap-3 overflow-hidden rounded-2xl text-center sm:grid-cols-2 lg:grid-cols-3"
                        >
                            <div class="flex flex-col bg-slate-400/5 p-8 dark:bg-white/5">
                                <dt class="text-2xl font-semibold text-slate-900 dark:text-white">Valor investido</dt>
                                <dd
                                    class="order-first text-3xl font-semibold tracking-tight text-slate-900 dark:text-white"
                                >
                                    {{ valorTotalInvestido }}
                                </dd>
                            </div>
                            <div id="montanteDiv" class="flex flex-col bg-slate-400/5 p-8 dark:bg-white/5">
                                <dt class="text-2xl font-semibold text-sky-500">Montante final</dt>
                                <dd class="order-first text-3xl font-semibold tracking-tight text-sky-500">
                                    {{ montanteFinal }}
                                </dd>
                            </div>
                            <div class="flex flex-col bg-slate-400/5 p-8 dark:bg-white/5">
                                <dt class="text-2xl font-semibold text-slate-900 dark:text-white">Total em juros</dt>
                                <dd
                                    class="order-first text-3xl font-semibold tracking-tight text-slate-900 dark:text-white"
                                >
                                    {{ totalEmJuros }}
                                </dd>
                            </div>
                        </div>
                    </div>
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
                    <div class="sm:col-span-1">
                        <CustomInput
                            inputmode="numeric"
                            :formata="true"
                            v-model="valorMensal"
                            label="Valor mensal"
                            id="valorMensal"
                            name="valorMensal"
                            @keydown.enter="$refs.botaoIncluirItem.focus()"
                        >
                            <template #prepend>
                                <span class="text-base">R$</span>
                            </template>
                        </CustomInput>
                    </div>
                    <div class="sm:col-span-1">
                        <CustomInput
                            inputmode="numeric"
                            :propLargeAppend="true"
                            v-model="taxaJuros"
                            label="Taxa de juros"
                            id="taxaJuros"
                            name="taxaJuros"
                            @keydown.enter="$refs.botaoIncluirItem.focus()"
                        >
                            <template #append>
                                <CustomSelect
                                    :options="[
                                        { label: 'Mensal', value: 'Mensal' },
                                        { label: 'Anual', value: 'Anual' },
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
                            inputmode="numeric"
                            :propLargeAppend="true"
                            v-model="periodo"
                            label="Período"
                            id="periodo"
                            name="periodo"
                            @keydown.enter="$refs.botaoIncluirItem.focus()"
                        >
                            <template #append>
                                <CustomSelect
                                    :options="[
                                        { label: 'Anos', value: 'Anos' },
                                        { label: 'Meses', value: 'Meses' },
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
            taxaJurosSelect: 'Mensal',
            periodo: null,
            periodoSelect: 'Anos',
            montanteFinal: 'R$ 0,00',
            valorTotalInvestido: 'R$ 0,00',
            totalEmJuros: 'R$ 0,00',
        };
    },

    methods: {
        calculaJurosCompostos() {
            let self = this;

            const C = parseFloat(self.valorInicial);
            const A = parseFloat(self.valorMensal);

            const i =
                self.taxaJurosSelect == 'Mensal'
                    ? parseFloat(self.taxaJuros) / 100
                    : Math.pow(1 + parseFloat(self.taxaJuros) / 100, 1 / 12) - 1;
            const t = self.periodoSelect == 'Anos' ? parseFloat(self.periodo) * 12 : parseFloat(self.periodo);

            const M = C * (1 + i) ** t + (A / i) * ((1 + i) ** t - 1);
            const valorTotalInvestido = C + A * t;
            const totalEmJuros = M - valorTotalInvestido;

            self.montanteFinal = isNaN(parseFloat(M))
                ? 'R$ 0,00'
                : M.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
            self.valorTotalInvestido = valorTotalInvestido.toLocaleString('pt-BR', {
                style: 'currency',
                currency: 'BRL',
            });
            self.totalEmJuros = totalEmJuros.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
            document.getElementById('montanteDiv').scrollIntoView({ behavior: 'smooth', block: 'center' });
        },
    },
};
</script>
