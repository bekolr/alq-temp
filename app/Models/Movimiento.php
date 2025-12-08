<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Movimiento extends Model
{
    //

    use HasFactory;


      protected $fillable = [
        'fecha', 'tipo_movimiento', 'concepto_id',
        'monto', 'descripcion', 'metodo_pago'
    ];

    public function concepto()
    {
        return $this->belongsTo(Concepto::class);
    }

    
   
}
