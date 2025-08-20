<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome', 
        'cpf',   
    ];

    public function vehicles(){
        return $this->hasMany(Vehicle::class);
    }

}
