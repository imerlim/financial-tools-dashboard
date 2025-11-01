<template>
    <div class="relative w-full h-screen overflow-hidden">
        <video autoplay loop muted playsinline class="absolute w-full h-full object-cover">
            <source src="/videos/wallpaper-anime.mp4" type="video/mp4" />
        </video>

        <div class="relative z-10 flex flex-col items-center justify-center h-full text-white bg-black/40">
            <h1 class="text-5xl font-bold">Bem-vindo ao meu mundo anime ✨</h1>
            <p class="text-lg mt-4">Explore o portal com estilo</p>
        </div>
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
