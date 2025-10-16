<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\HomeController;

Route::get('/aminia', function () {
    return Inertia::render('Home');
});
Route::get('/calculadora-juros-compostos', function () {
    return Inertia::render('CalculadoraJurosCompostos');
});
Route::get('/', function () {
    return Inertia::render('CadastroProduto');
});

Route::controller(HomeController::class)->group(function () {
    Route::post('envia-mensagem', 'enviaMensagem')->name('envia.mensagem');
});