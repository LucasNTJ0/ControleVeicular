<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'driver_id',
        'reason_id',
        'data_saida',
        'estimativa_retorno',
        'data_retorno',
        'odometro',
        'observacao',
    ];

    protected $casts = [
        'data_saida' => 'datetime',
        'estimativa_retorno' => 'datetime',
        'data_retorno' => 'datetime',
    ];

    public function vehicle(){
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }

    public function driver(){
        return $this->belongsTo(Driver::class, 'driver_id');
    }
    
    public function reason(){
        return $this->belongsTo(Reason::class, 'reason_id');
    }


}
