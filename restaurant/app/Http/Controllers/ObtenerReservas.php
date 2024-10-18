<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use Illuminate\Http\Request;

class ObtenerReservas extends Controller
{
    public function obtenerReservas()
    {
        $reservas = Reserva::all(); // Obtén todas las reservas
        return response()->json($reservas);
    }
}
