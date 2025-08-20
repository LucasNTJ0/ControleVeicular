<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vehicle extends Model
{

use HasFactory;

    protected $fillable = [
        'placa',
        'marca',
        'modelo',
        'ano',
    ];


    public function movements(){
        return $this->hasMany(VehicleMovement::class, 'vehicle_id');
    }
}
