<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\OrdemServicoController;

Route::get('/', [OrdemServicoController::class, 'index'])->name('ordem_servico.index');
Route::post('/', [OrdemServicoController::class, 'store'])->name('ordem_servico.store');
Route::put('/alterar_tecnico/{id}', [OrdemServicoController::class, 'updateTecnico'])->name('ordem_servico.alterar_tecnico');
Route::put('/alterar_status/{id}', [OrdemServicoController::class, 'updateStatus'])->name('ordem_servico.alterar_status');
Route::delete('/{id}', [OrdemServicoController::class, 'destroy'])->name('ordem_servico.destroy');