<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre_cliente',
        'numero_personas',
        'fecha_reserva',
        'estado',
        // otros campos...
    ];

    protected $dates = [
        'fecha_reserva', // Esto le dice a Eloquent que este campo debe ser tratado como una instancia de Carbon
    ];
}
