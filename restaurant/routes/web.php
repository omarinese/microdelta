<?php

use App\Http\Controllers\ExcelController;
use Illuminate\Support\Facades\Route;

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
        return view('excel'); // Asegúrate de que la vista se llame "excel.blade.php"
    })->name('excel');


});
