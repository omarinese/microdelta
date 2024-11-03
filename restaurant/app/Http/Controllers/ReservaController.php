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
        $hoy = Carbon::now();

        $reservasPendientes = Reserva::where('estado', 'pendiente')->get();

        $reservasEntrantes = Reserva::where('estado', 'entrante')
            ->where('fecha_reserva', '>', $hoy) // Solo futuras
            ->get();

        $reservasInminentes = Reserva::where(function($query) use ($hoy) {
            $query->where('estado', 'inminente')
                ->orWhereDate('fecha_reserva', $hoy);
        })->get();

        return view('reservas', compact('reservasPendientes', 'reservasEntrantes', 'reservasInminentes'));
    }


    public function importar(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        $spreadsheet = IOFactory::load($request->file('file'));

        $sheet = $spreadsheet->getActiveSheet();
        $data = $sheet->toArray(null, true, true, true);

        $estadoMap = [
            0 => 'pendiente',   // Estado pendiente
            1 => 'inminente',   // Estado inminente
            2 => 'entrante',    // Estado entrante
        ];

        foreach ($data as $row) {
            if ($row['A'] === 'id') {
                continue;
            }

            try {
                if (empty($row['B']) || empty($row['C']) || empty($row['D'])) {
                    dd('Fila incompleta: ' . json_encode($row));
                }

                if (!is_numeric($row['C'])) {
                    dd('Número de personas no válido en la fila: ' . json_encode($row));
                }

                $fechaReserva = $row['D'];

                if (preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $fechaReserva)) {
                    $fechaReserva = Carbon::createFromFormat('d/m/Y', $fechaReserva);
                } else {
                    if (is_numeric($fechaReserva)) {
                        $fechaReserva = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((int)$fechaReserva);
                    } else {
                        dd('Fecha no válida en la fila: ' . json_encode($row));
                    }
                }

                $estado = $estadoMap[$row['E']] ?? 'pendiente'; // Por defecto, pendiente

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

        if ($request->direccion === 'siguiente') {
            if ($reserva->estado === 'entrante') {
                $reserva->estado = 'inminente';
            } elseif ($reserva->estado === 'inminente') {
                $reserva->estado = 'pendiente';
            }
        } else {
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
