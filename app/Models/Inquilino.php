<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;



use Illuminate\Database\Eloquent\Factories\HasFactory;


class Inquilino extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'nombre', 'apellido', 'dni', 'telefono', 'email', 'direccion',
    ];

    public function estadias()
    {
        return $this->hasMany(Estadia::class);
    }

    public function getNombreCompletoAttribute()
    {
        return trim("{$this->nombre} {$this->apellido}");
    }
}
