<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\HomeController;

Route::get('/', function () {
    return Inertia::render('Home');
})->name('/');

Route::get('/aminia', function () {
    return Inertia::render('Aminia');
})->name('aminia');

Route::get('/calculadora-juros-compostos', function () {
    return Inertia::render('CalculadoraJurosCompostos');
})->name('calculadora.juros.compostos');

Route::get('/calculo-margem', function () {
    return Inertia::render('CalculoMargem');
})->name('calculo.margem');


// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::controller(HomeController::class)->group(function () {
    Route::post('envia-mensagem', 'enviaMensagem')->name('envia.mensagem');
});

require __DIR__ . '/auth.php';
