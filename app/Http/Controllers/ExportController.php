<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\FichajesExport;

class ExportController extends Controller
{
    public function export(Request $request, $empresaId)
    {
        // Si hay fechas, validamos que sean correctas
        if ($request->filled(['fecha_inicio', 'fecha_fin'])) {
            $request->validate([
                'fecha_inicio' => 'date',
                'fecha_fin' => 'date|after_or_equal:fecha_inicio',
            ]);
        }

        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');

        return Excel::download(new FichajesExport($empresaId, $fechaInicio, $fechaFin), 'control-presencia.xlsx');
    }
}
