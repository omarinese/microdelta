<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Carbon\Carbon;

class ReservaController extends Controller
{
    public function index()
    {
        $hoy = Carbon::now(); // Fecha y hora actual

        // Obtener reservas pendientes (estado 'pendiente')
        $reservasPendientes = Reserva::where('estado', 'pendiente')->get();

        // Obtener reservas entrantes (estado 'entrante' y fecha futura)
        $reservasEntrantes = Reserva::where('estado', 'entrante')
            ->where('fecha_reserva', '>', $hoy) // Solo futuras
            ->get();

        // Obtener reservas inminentes (estado 'inminente' o que sean para hoy)
        $reservasInminentes = Reserva::where(function($query) use ($hoy) {
            $query->where('estado', 'inminente')
                ->orWhereDate('fecha_reserva', $hoy); // También las de hoy
        })->get();

        // Pasar las variables a la vista
        return view('reservas', compact('reservasPendientes', 'reservasEntrantes', 'reservasInminentes'));
    }


    public function importar(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        // Cargar el archivo
        $spreadsheet = IOFactory::load($request->file('file'));

        // Obtener la primera hoja
        $sheet = $spreadsheet->getActiveSheet();
        $data = $sheet->toArray(null, true, true, true);

        // Mapear los posibles valores de estado (pendiente, inminente, entrante)
        $estadoMap = [
            0 => 'pendiente',   // Estado pendiente
            1 => 'inminente',   // Estado inminente
            2 => 'entrante',    // Estado entrante
        ];

        // Procesar los datos
        foreach ($data as $row) {
            // Saltear la cabecera
            if ($row['A'] === 'id') {
                continue;
            }

            try {
                // Validar que los campos no estén vacíos
                if (empty($row['B']) || empty($row['C']) || empty($row['D'])) {
                    dd('Fila incompleta: ' . json_encode($row));
                }

                // Validar que 'numero_personas' sea un número
                if (!is_numeric($row['C'])) {
                    dd('Número de personas no válido en la fila: ' . json_encode($row));
                }

                // Intentar convertir la fecha a un objeto Carbon
                $fechaReserva = $row['D'];

                // Comprobar si es un formato de fecha válido
                if (preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $fechaReserva)) {
                    $fechaReserva = Carbon::createFromFormat('d/m/Y', $fechaReserva);
                } else {
                    // Si el formato no es válido, verificar si es un número
                    if (is_numeric($fechaReserva)) {
                        $fechaReserva = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((int)$fechaReserva);
                    } else {
                        dd('Fecha no válida en la fila: ' . json_encode($row));
                    }
                }

                // Obtener el estado en texto desde el mapeo, o asignar 'pendiente' si no coincide
                $estado = $estadoMap[$row['E']] ?? 'pendiente'; // Por defecto, pendiente

                // Crear una reserva
                Reserva::create([
                    'nombre_cliente' => $row['B'],
                    'numero_personas' => (int)$row['C'],
                    'fecha_reserva' => $fechaReserva,
                    'estado' => $estado, // Usar el estado en texto
                ]);
            } catch (\Exception $e) {
                dd('Error al procesar la fila: ' . json_encode($row) . ' - Mensaje: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('success', 'Reservas importadas correctamente.');
    }


    public function mover(Request $request, $id)
    {
        $reserva = Reserva::find($id);

        if (!$reserva) {
            return response()->json(['success' => false, 'message' => 'Reserva no encontrada']);
        }

        // Cambiar el estado según la dirección
        if ($request->direccion === 'siguiente') {
            // Lógica para mover a la siguiente categoría
            if ($reserva->estado === 'entrante') {
                $reserva->estado = 'inminente';
            } elseif ($reserva->estado === 'inminente') {
                $reserva->estado = 'pendiente';
            }
        } else {
            // Lógica para mover a la anterior categoría
            if ($reserva->estado === 'inminente') {
                $reserva->estado = 'entrante';
            } elseif ($reserva->estado === 'pendiente') {
                $reserva->estado = 'inminente';
            }
        }

        $reserva->save();

        return response()->json(['success' => true, 'reserva' => $reserva]);
    }

}
