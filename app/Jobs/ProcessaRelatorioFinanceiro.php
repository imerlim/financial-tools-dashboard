<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class ProcessaRelatorioFinanceiro implements ShouldQueue
{
    use Queueable;

    // Criamos uma variável para guardar os filtros que virão do Service
    public $filters;

    /**
     * O construtor recebe os filtros do Service
     */
    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    /**
     * O método handle é onde a "mágica" acontece em segundo plano
     */
    public function handle(): void
    {
        Log::info('O Job começou a trabalhar!', ['filtros' => $this->filters]);

        // Aqui dentro colocaremos as queries pesadas no próximo passo
        // Por enquanto, apenas simule um trabalho pesado:
        sleep(5);

        Log::info('O Job terminou o cálculo pesado!');
    }
}
