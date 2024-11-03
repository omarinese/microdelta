<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Reserva extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre_cliente',
        'numero_personas',
        'fecha_reserva',
        'estado',
    ];

    // Definir fecha_reserva como una instancia de Carbon
    protected $dates = ['fecha_reserva'];
}
