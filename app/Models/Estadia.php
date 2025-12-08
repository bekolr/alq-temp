<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Acompanante;


class Estadia extends Model
{
    //
    use HasFactory;

protected $fillable = [
    'inquilino_id',
    'departamento_id',
    'fecha_ingreso',
    'fecha_egreso',
    'monto_total',
   'estado_pago',
    'estado',
    'observaciones',
    'tipo_viaje',
    
];

protected $casts = [
    'fecha_ingreso' => 'date',
    'fecha_egreso'  => 'date',
];


    public function inquilino()
    {
        return $this->belongsTo(Inquilino::class);
    }

    public function departamento()
    {
        return $this->belongsTo(Departamento::class);
    }

    public function movimientos()
    {
        return $this->hasMany(MovimientoCaja::class);
    }
    public function acompanantes()
{
    return $this->hasMany(Acompanante::class);
}

}
