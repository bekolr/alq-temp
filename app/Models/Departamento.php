<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Departamento extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'nombre', 'direccion', 'piso', 'numero',
    ];

    public function estadias()
    {
        return $this->hasMany(Estadia::class);
    }
}
