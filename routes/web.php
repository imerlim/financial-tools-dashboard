<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ControleFinanceiroController;
use App\Http\Controllers\DocumentController;

Route::inertia('/', 'Home')->name('/');
Route::inertia('/aminia', 'Aminia')->name('Aminia');
Route::inertia('/calculadora-juros-compostos', 'CalculadoraJurosCompostos')->name('calculadora.juros.compostos');
Route::inertia('/calculadora-margem', 'CalculadoraMargem')->name('calculadora.margem');
Route::inertia('/financial-control', 'FinancialControl')->name('financial.control');
Route::inertia('/document-management', 'DMS/DocumentManagementSystem')->name('document.management');

Route::controller(ControleFinanceiroController::class)->group(function () {
    Route::post('create-category', 'createCategory')->name('create.category');
    Route::post('create-controle-financeiro', 'createFinancialControl')->name('create.controle.financeiro');
    Route::post('busca-controle-financeiro', 'buscaControleFinanceiro')->name('busca.controle.financeiro');
    Route::get('all-category', 'allCategory')->name('all.category');
    Route::delete('delete-category', 'deleteCategoria')->name('delete.category');
});

Route::controller(DocumentController::class)->group(function () {
    Route::post('document-upload', 'uploadDocument')->name('document.upload');
    Route::get('documents-list', 'listDocuments')->name('documents.list');
    Route::get('document-download/{id}', 'downloadDocument');
    Route::delete('document-delete/{id}', 'deleteDocument');
});

require __DIR__ . '/auth.php';
