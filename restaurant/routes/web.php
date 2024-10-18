<?php

use App\Http\Controllers\ExcelController;
use App\Http\Controllers\ReservaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ObtenerReservas;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/reservas', function () {
        return view('reservas');
    })->name('reservas');

    Route::get('/info', function () {
        return view('info');
    })->name('info');

    Route::get('/excel', function () {
        return view('excel');
    })->name('excel');

    Route::get('reservas/obtener', [ObtenerReservas::class, 'obtenerReservas']);

    // Añadir el nombre a la ruta de importación
    Route::post('reservas/importar', [ReservaController::class, 'importar'])->name('reservas.importar');

    Route::get('/reservas', [ReservaController::class, 'index'])->name('reservas');

    Route::get('/reservas', [ReservaController::class, 'index'])->name('reservas');


});

