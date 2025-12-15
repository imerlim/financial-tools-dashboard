<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\ControleFinanceiroController;
use App\Http\Controllers\HomeController;

Route::inertia('/', 'Home')->name('/');
Route::inertia('/aminia', 'Aminia')->name('Aminia');
Route::inertia('/calculadora-juros-compostos', 'CalculadoraJurosCompostos')->name('calculadora.juros.compostos');
Route::inertia('/calculadora-margem', 'CalculadoraMargem')->name('calculadora.margem');
Route::inertia('/controle-financeiro', 'ControleFinanceiro')->name('controle.financeiro');

Route::controller(ControleFinanceiroController::class)->group(function () {
    Route::post('create-categoria', 'createCategoria')->name('create.categoria');
    Route::post('create-controle-financeiro', 'createControleFinanceiro')->name('create.controle.financeiro');
    Route::post('busca-controle-financeiro', 'buscaControleFinanceiro')->name('busca.controle.financeiro');
    Route::get('all-categoria', 'allCategoria')->name('all.categoria');
    Route::delete('delete-categoria', 'deleteCategoria')->name('delete.categoria');
});

require __DIR__ . '/auth.php';
