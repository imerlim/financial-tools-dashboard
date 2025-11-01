<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\HomeController;

// Route::get('/', function () {
//     return Inertia::render('Home');
// })->name('/');

Route::get('/aminia', function () {
    return Inertia::render('Aminia');
})->name('aminia');


Route::get('/', function () {
    return Inertia::render('CalculadoraJurosCompostos');
})->name('/');

// Route::get('/calculadora-juros-compostos', function () {
//     return Inertia::render('CalculadoraJurosCompostos');
// })->name('calculadora.juros.compostos');

Route::get('/calculo-margem', function () {
    return Inertia::render('CalculoMargem');
})->name('calculo.margem');

Route::controller(HomeController::class)->group(function () {
    Route::post('envia-mensagem', 'enviaMensagem')->name('envia.mensagem');
});
