<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Incidencia;
use App\Models\Empresa;
use App\Models\Fichaje;
use App\Models\User;
use Illuminate\Support\Facades\Session;


class IncidenciaController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'empresa_id' => 'required|exists:empresas,id',
            'fecha' => 'required|date',
            'hora' => 'required',
            'tipo' => 'required|in:entrada,salida',
            'motivo' => 'required|string',
        ]);

        $correoEmpleado = Session::get('user_email');

        if (!$correoEmpleado) {
            return redirect()->back()->with('error', 'Sesión no válida.');
        }

        $user = User::with('empleado')
            ->where('email', $correoEmpleado)
            ->first();

        if (!$user || !$user->empleado) {
            return redirect()->back()->with('error', 'Empleado no encontrado.');
        }

        if ($user->empleado->deshabilitado) {
            return redirect()->back()->with('error', 'Empleado deshabilitado.');
        }

        $empleado = $user->empleado;

        Incidencia::create([
            'empleado_id' => $empleado->id,
            'empresa_id'  => $request->empresa_id,
            'fecha'       => $request->fecha,
            'hora'        => $request->hora,
            'tipo'        => $request->tipo,
            'motivo'      => $request->motivo,
            'estado'      => 'pendiente',
        ]);

        return redirect()->back()->with(
            'success',
            'Incidencia registrada correctamente y pendiente de revisión.'
        );
    }


    /**
     * Muestra todas las incidencias agrupadas por empresa.
     *
     * @return \Illuminate\View\View
     */
    public function verPorEmpresa($empresaId)
    {
        $empresa = Empresa::findOrFail($empresaId);

        $aprobadas = Incidencia::with('empleado')
            ->where('empresa_id', $empresaId)
            ->where('estado', 'aprobada')
            ->orderBy('fecha', 'desc')
            ->paginate(25, ['*'], 'aprobadas');

        $pendientes = Incidencia::with('empleado')
            ->where('empresa_id', $empresaId)
            ->where('estado', 'pendiente')
            ->orderBy('fecha', 'desc')
            ->get();

        $descartadas = Incidencia::with('empleado')
            ->where('empresa_id', $empresaId)
            ->where('estado', 'descartada')
            ->orderBy('fecha', 'desc')
            ->get();

        return view('pages.incidencias-admin', compact(
            'empresa',
            'pendientes',
            'aprobadas',
            'descartadas'
        ));
    }



    public function descartar($id)
    {
        $incidencia = Incidencia::findOrFail($id);

        if ($incidencia->estado !== 'pendiente') {
            return back()->with('error', 'La incidencia ya fue procesada.');
        }

        $incidencia->estado = 'descartada';
        $incidencia->save();

        return back()->with('success', 'Incidencia descartada correctamente.');
    }



    public function aprobar($id)
    {
        $incidencia = Incidencia::findOrFail($id);

        if ($incidencia->estado !== 'pendiente') {
            return back()->with('error', 'La incidencia ya fue procesada.');
        }

        // Crear el fichaje correspondiente
        Fichaje::create([
            'empleado_id' => $incidencia->empleado_id,
            'empresa_id' => $incidencia->empresa_id,
            'tipo' => $incidencia->tipo,
            'fecha_hora' => $incidencia->fecha . ' ' . $incidencia->hora,
            'latitud' => 0,
            'longitud' => 0,
            'dentro_rango' => 0,
            'dispositivo' => 'Generado manualmente por administrador de incidencias',
            'navegador' => 'Manual',
            'ip' => request()->ip(),
        ]);

        // Marcar incidencia como resuelta
        $incidencia->estado = 'aprobada';
        $incidencia->save();

        return back()->with('success', 'Incidencia aprobada y fichaje creado.');
    }


    public function limpiarAprobadasDescartadas($empresaId)
    {
        Incidencia::where('empresa_id', $empresaId)
            ->whereIn('estado', ['aprobada', 'descartada'])
            ->delete();

        return back()->with('success', 'Incidencias eliminadas correctamente.');
    }
}
