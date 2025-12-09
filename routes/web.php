<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InquilinoController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\EstadiaController;
use App\Http\Controllers\MovimientoController;
use App\Http\Controllers\CheckinController;

/*
|--------------------------------------------------------------------------
| Rutas públicas (sin login)
|--------------------------------------------------------------------------
*/

// Página de inicio: mando directo al login
Route::get('/', function () {
    return redirect()->route('login');
});

// Check-in público del inquilino
Route::get('/checkin', [CheckinController::class, 'create'])->name('checkin.form');
Route::post('/checkin', [CheckinController::class, 'store'])->name('checkin.store');
Route::get('/checkin-ok', [CheckinController::class, 'ok'])->name('checkin.ok');

/*
|--------------------------------------------------------------------------
| Rutas privadas (requieren login + admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->group(function () {

    // Después de loguearse, Breeze redirige a "dashboard" por defecto
    Route::get('/dashboard', function () {
        // Podés mandar a la vista que quieras, por ahora a la lista de estadías
        return redirect()->route('estadias.index');
    })->name('dashboard');

        // Calendario de estadías
    Route::get('estadias/calendario', [EstadiaController::class, 'calendario'])
        ->name('estadias.calendario');

    Route::get('estadias/calendario/events', [EstadiaController::class, 'calendarioEvents'])
        ->name('estadias.calendario.events');
    // CRUDs internos
    Route::resource('inquilinos', InquilinoController::class);
    Route::resource('departamentos', DepartamentoController::class);
    Route::resource('estadias', EstadiaController::class);
    Route::resource('movimientos', MovimientoController::class);

    // Acciones especiales de estadías
    Route::post('estadias/{estadia}/checkout', [EstadiaController::class, 'checkout'])
        ->name('estadias.checkout');

    Route::post('estadias/{estadia}/pagar', [EstadiaController::class, 'pagar'])
        ->name('estadias.pagar');


});

/*
|--------------------------------------------------------------------------
| Rutas de autenticación generadas por Breeze
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';
