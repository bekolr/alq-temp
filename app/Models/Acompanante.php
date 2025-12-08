<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Acompanante extends Model
{
    //
      use HasFactory;

    protected $fillable = [
        'estadia_id',
        'nombre',
        'dni',
        'parentesco',
    ];

    public function estadia()
    {
        return $this->belongsTo(Estadia::class);
    }
}
