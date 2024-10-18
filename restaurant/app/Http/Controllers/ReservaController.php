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

        // Obtener reservas pendientes
        $reservasPendientes = Reserva::where('estado', 'pendiente')->get();

        // Obtener reservas entrantes (futuras)
        $reservasEntrantes = Reserva::where('fecha_reserva', '>', $hoy)->get();

        // Obtener reservas inminentes (por ejemplo, las que son para hoy)
        $reservasInminentes = Reserva::whereDate('fecha_reserva', $hoy)->get();

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

                // Crear una reserva
                Reserva::create([
                    'nombre_cliente' => $row['B'],
                    'numero_personas' => (int)$row['C'],
                    'fecha_reserva' => $fechaReserva,
                    'estado' => $row['E'] ?? 'Pendiente', // Asignar un estado por defecto si no existe
                ]);
            } catch (\Exception $e) {
                dd('Error al procesar la fila: ' . json_encode($row) . ' - Mensaje: ' . $e->getMessage());
            }
        }

            return redirect()->back()->with('success', 'Reservas importadas correctamente.');
    }
}
