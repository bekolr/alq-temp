<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovimientoCaja extends Model
{
    use HasFactory;

    protected $table = 'movimiento_cajas';

    protected $fillable = [
        'tipo',         // ingreso | egreso
        'concepto',
        'fecha',
        'monto',
        'estadia_id',
        'medio_pago',
        'observaciones',
    ];

    protected $casts = [
        'fecha' => 'datetime',
    ];

    public function estadia()
    {
        return $this->belongsTo(Estadia::class);
    }
}
