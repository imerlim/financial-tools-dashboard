<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\HomeController;

Route::get('/aminia', function () {
    return Inertia::render('Aminia');
})->name('aminia');

Route::get('/calculadora-juros-compostos', function () {
    return Inertia::render('CalculadoraJurosCompostos');
});

// Sua rota principal DEVE ter um nome para ser usada pelo Ziggy
Route::get('/', function () {
    return Inertia::render('CadastroProduto');
})->name('home');

Route::controller(HomeController::class)->group(function () {
    Route::post('envia-mensagem', 'enviaMensagem')->name('envia.mensagem');
});
