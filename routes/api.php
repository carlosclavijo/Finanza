<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\CuentaController;
use App\Http\Controllers\MovimientoController;
use App\Http\Controllers\MovimientotransferenciaController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/user/', [UserController::class, 'show'])->name('users.show');
Route::post('/users/', [UserController::class, 'store'])->name('users.store');
Route::post('/users/login', [UserController::class, 'login'])->name('users.login');

Route::post('/categorias/', [CategoriaController::class, 'store'])->name('categorias.store');
Route::get('/categorias/{id}', [CategoriaController::class, 'show'])->name('categorias.show');
Route::delete('/categorias/{id}', [CategoriaController::class, 'destroy'])->name('categorias.destroy');
Route::get('/categorias/', [CategoriaController::class, 'index'])->name('categorias.index');

Route::post('/cuentas/', [CuentaController::class, 'store'])->name('cuentas.store');
Route::get('/cuentas/', [CuentaController::class, 'index'])->name('cuentas.index');
Route::get('/cuentas/{id}', [CuentaController::class, 'show'])->name('cuentas.show');
Route::delete('/cuentas/{id}', [CuentaController::class, 'destroy'])->name('cuentas.delete');

Route::post('/movimientos/', [MovimientoController::class, 'store'])->name('movimientos.store');
Route::post('/movimientos/transferencia', [MovimientotransferenciaController::class, 'store'])->name('movimientos.store');
Route::get('/movimientos/{id}', [MovimientoController::class, 'index'])->name('movimientos.index');

