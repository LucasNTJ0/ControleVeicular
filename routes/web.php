<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovementsVehicleController;
use App\Http\Controllers\DriversController;
use App\Http\Controllers\ReasonsController;
use App\Http\Controllers\VehiclesController;

Route::get('/', [MovementsVehicleController::class, 'index'])->name('movements.index');

Route::resource('drivers', DriversController::class);
Route::resource('movements', MovementsVehicleController::class);
Route::resource('reasons', ReasonsController::class);
Route::resource('vehicles', VehiclesController::class);

Route::get('/movements/{id}/return', [MovementsVehicleController::class, 'returnForm'])
    ->name('movements.returnForm');
Route::put('/movements/{id}/return', [MovementsVehicleController::class, 'returnUpdate'])
    ->name('movements.return');

// The variable name here {movement} should match the one in your controller
Route::get('/movements/{id}', [MovementsVehicleController::class, 'show']);
