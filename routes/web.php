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
Route::inertia('/financial-control', 'FinancialControl')->name('financial.control');

Route::controller(ControleFinanceiroController::class)->group(function () {
    Route::post('create-category', 'createCategory')->name('create.category');
    Route::post('create-controle-financeiro', 'createFinancialControl')->name('create.controle.financeiro');
    Route::post('busca-controle-financeiro', 'buscaControleFinanceiro')->name('busca.controle.financeiro');
    Route::get('all-category', 'allCategoria')->name('all.category');
    Route::delete('delete-category', 'deleteCategoria')->name('delete.category');
});

require __DIR__ . '/auth.php';
