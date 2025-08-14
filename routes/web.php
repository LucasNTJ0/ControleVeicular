<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovementsVehicleController;
use App\Http\Controllers\DriversController;
use App\Http\Controllers\ReasonsController;
use App\Http\Controllers\VehiclesController;

Route::get('/', function () {
    return view('create');
});

Route::get('/drivers', [DriversController::class, 'index'])->name('drivers.index');
Route::get('/drivers/create',[DriversController::class, 'create'])->name('drivers.create');
Route::post('drivers', [DriversController::class, 'store'])->name('drivers.store');
Route::post('/drivers/select', [DriversController::class, 'processSelection'])->name('drivers.processSelection');


Route::get('/vehicles', [VehiclesController::class, 'index'])->name('vehicles.index');
Route::get('/vehicles/create', [VehiclesController::class, 'create'])->name('vehicles.create');
Route::post('/vehicles', [VehiclesController::class, 'store'])->name('vehicles.store');

Route::get('/movements', [MovementsVehicleController::class, 'index'])->name('movements.index');
Route::get('/movements/create', [MovementsVehicleController::class, 'create'])->name('movements.create');
Route::post('/movements', [MovementsVehicleController::class, 'store'])->name('movements.store');

Route::get('/reasons', [ReasonsController::class, 'index'])->name('reasons.index');
Route::get('/reasons/create', [ReasonsController::class, 'create'])->name('reasons.create');
Route::post('/reasons/store', [ReasonsController::class, 'store'])->name('reasons.store');


