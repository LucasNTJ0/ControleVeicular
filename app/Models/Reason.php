<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\hasFactory;

class Reason extends Model
{
    use HasFactory;

    protected $fillable = [
        'descricao'
    ];
    public function movements()
    {
        return $this->hasMany(VehicleMovement::class, 'reason_id');
    }
}
