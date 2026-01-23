<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Empresa;
use App\Models\Empleado;
use App\Models\User;
use App\Models\Fichaje;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use App\Models\Incidencia;




class RoutingController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::check()) {
            return redirect()->route("empresas");
        }
        return redirect()->route("login");
    }

    public function users(Request $request)
    {
        $users = User::where('rol', '!=', 'empleado')->get();
        return view('users.users', compact('users'));
    }


    public function empresas(Request $request)
    {
        $totalEmpleados = Empleado::all()->count();
        $totalEmpresas = Empresa::all()->count();
        $empresas = Empresa::all();
        return view('index', compact('empresas', 'totalEmpresas', 'totalEmpleados'));
    }

    public function empleados()
    {
        return view('pages.empleados');
    }

    public function fichaje(Request $request)
    {
        $empresas = Empresa::all();
        return view('pages.fichaje', compact('empresas'));
    }


    /**
     * Muestra los registros de fichajes de una empresa específica.
     *
     * @param int $empresaId
     * @return \Illuminate\View\View
     */
    public function registrosFichajes($empresaId)
    {
        $empresa = Empresa::findOrFail($empresaId);

        $fichajes = Fichaje::where('empresa_id', $empresaId)
            ->with('empleado')
            ->get();

        $incidenciasPendientes = Incidencia::where('empresa_id', $empresaId)
            ->where('estado', 'pendiente')
            ->count();

        // Fechas anteriores al día de hoy con fichajes
        $fechasAnteriores = Fichaje::where('empresa_id', $empresaId)
            ->whereDate('fecha_hora', '<', now()->toDateString())
            ->selectRaw('DATE(fecha_hora) as fecha')
            ->distinct()
            ->orderBy('fecha', 'desc')
            ->pluck('fecha');

        // Obtener empleados que hayan fichado en esa empresa
        $empleadoIds = Fichaje::where('empresa_id', $empresaId)
            ->pluck('empleado_id')
            ->unique();

        $empleados = Empleado::whereIn('id', $empleadoIds)->get();

        $alertas = [];

        foreach ($empleados as $empleado) {
            foreach ($fechasAnteriores as $fecha) {
                $fichajesFecha = Fichaje::where('empresa_id', $empresaId)
                    ->where('empleado_id', $empleado->id)
                    ->whereDate('fecha_hora', $fecha)
                    ->orderBy('fecha_hora')
                    ->get();

                // Si no fichó ese día, se asume que descansó
                if ($fichajesFecha->isEmpty()) {
                    continue;
                }

                // Contar entradas y salidas
                $entradas = $fichajesFecha->where('tipo', 'entrada')->count();
                $salidas = $fichajesFecha->where('tipo', 'salida')->count();

                // Verificar si están balanceadas
                if ($entradas !== $salidas) {
                    $alertas[] = [
                        'nombre' => $empleado->nombre,
                        'fecha' => \Carbon\Carbon::parse($fecha)->format('d/m/Y'),
                        'entradas' => $entradas,
                        'salidas' => $salidas,
                        'mensaje' => "Desajuste de fichajes el día {$fecha}: {$entradas} entrada(s), {$salidas} salida(s).",
                    ];
                }
            }
        }

        return view('registrosFichaje', compact('fichajes', 'empresaId', 'alertas', 'incidenciasPendientes'));
    }



    public function verAlertasFichajes($empresaId)
    {
        $empresa = Empresa::findOrFail($empresaId);

        $registros = Fichaje::selectRaw("
                empleado_id,
                DATE(fecha_hora) as fecha,
                SUM(CASE WHEN tipo = 'entrada' THEN 1 ELSE 0 END) as entradas,
                SUM(CASE WHEN tipo = 'salida' THEN 1 ELSE 0 END) as salidas
            ")
            ->where('empresa_id', $empresaId)
            ->whereDate('fecha_hora', '<', now()->toDateString())
            ->groupBy('empleado_id', 'fecha')
            ->orderBy('fecha', 'desc')
            ->get();

        $empleadoIds = $registros->pluck('empleado_id')->unique();
        $empleados = Empleado::whereIn('id', $empleadoIds)->get()->keyBy('id');

        $alertas = [];

        foreach ($registros as $row) {
            if ($row->entradas !== $row->salidas) {
                $alertas[] = [
                    'empleado_id' => $row->empleado_id,
                    'nombre'   => $empleados[$row->empleado_id]->nombre,
                    'fecha'    => Carbon::parse($row->fecha)->format('d/m/Y'),
                    'entradas' => $row->entradas,
                    'salidas'  => $row->salidas,
                    'mensaje'  => "Desajuste el {$row->fecha}: {$row->entradas} entrada(s), {$row->salidas} salida(s).",
                ];
            }
        }

        return view('alertas.alertasFichajes', compact('alertas', 'empresa'));
    }




    /**
     * Muestra la vista para fichar manualmente.
     *
     * @param int $empresaId
     * @return \Illuminate\View\View
     */
    public function fichajeManual($empresaId)
    {
        $empresa = Empresa::where('id', $empresaId)->first();
        $empleados = Empleado::all();
        return view('fichaje-manual', compact('empresa', 'empleados'));
    }


    public function incidencias(Request $request)
    {
        $empresas = Empresa::all();
        return view('incidencias.incidencias', compact('empresas'));
    }

    public function miActividad()
    {
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

        $haceUnMes = Carbon::now()->subMonth();

        $fichajes = Fichaje::where('empleado_id', $empleado->id)
            ->where('fecha_hora', '>=', $haceUnMes)
            ->orderBy('fecha_hora', 'desc')
            ->get();

        $incidencias = Incidencia::where('empleado_id', $empleado->id)
            ->orderBy('fecha', 'desc')
            ->get();

        return view('pages.mi-actividad', compact('empleado', 'fichajes', 'incidencias'));
    }

    public function provicional()
    {
        return view('icons.boxicons');
    }
}
