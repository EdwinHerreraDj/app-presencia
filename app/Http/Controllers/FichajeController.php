<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fichaje;
use App\Models\Empresa;
use App\Models\Empleado;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Carbon\CarbonInterval;




class FichajeController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'empresa_id' => 'required|exists:empresas,id',
            'latitud' => 'required|numeric',
            'longitud' => 'required|numeric',
        ]);

        $correoEmpleado = Session::get('user_email');

        if (!$correoEmpleado) {
            return redirect()->route('fichaje')->with('error', 'No se encontró el correo del usuario en la sesión.');
        }

        $empleado = Empleado::where('email', $correoEmpleado)->first();

        if (!$empleado) {
            return redirect()->route('fichaje')->with('error', 'No se encontró al empleado en la base de datos.');
        }

        $horaActual = now();

        // Último fichaje
        $ultimoFichaje = Fichaje::where('empleado_id', $empleado->id)
            ->where('empresa_id', $request->empresa_id)
            ->latest('fecha_hora')
            ->first();

        if ($ultimoFichaje && $ultimoFichaje->fecha_hora->isSameDay($horaActual)) {
            $diferenciaEnMinutos = $ultimoFichaje->fecha_hora->diffInMinutes($horaActual);
            if ($diferenciaEnMinutos < 60) {
                return redirect()->route('fichaje')
                    ->with('error', 'No se puede fichar nuevamente hasta que haya pasado una hora desde el último fichaje.');
            }
        }

        // Tipo de fichaje
        $tipoFichaje = $request->input('tipo');
        if (!in_array($tipoFichaje, ['entrada', 'salida'])) {
            return redirect()->route('fichaje')->with('error', 'Tipo de fichaje inválido.');
        }

        // Cálculo de distancia
        $empresa = Empresa::findOrFail($request->empresa_id);
        $distancia = $this->calcularDistancia(
            $request->latitud,
            $request->longitud,
            $empresa->latitud,
            $empresa->longitud
        );

        $dentroRango = $distancia <= $empresa->radio ? 1 : 0;

        // ✔ Aplicar regla de geolocalización estricta
        if ($empleado->geolocalizacion_estricta == 1 && !$dentroRango) {
            return redirect()->route('fichaje')
                ->with('error', 'No se puede fichar, no estás dentro de la ubicación permitida.');
        }

        // Información de dispositivo
        $userAgent = $request->header('User-Agent');

        // Registrar fichaje
        Fichaje::create([
            'empleado_id' => $empleado->id,
            'empresa_id' => $request->empresa_id,
            'tipo' => $tipoFichaje,
            'fecha_hora' => $horaActual,
            'latitud' => $request->latitud,
            'longitud' => $request->longitud,
            'dentro_rango' => $dentroRango,
            'dispositivo' => $userAgent,
            'navegador' => $userAgent,
            'ip' => $request->ip(),
        ]);

        return redirect()->route('fichaje')->with('success', 'Fichaje de ' . ucfirst($tipoFichaje) . ' registrado correctamente.');
    }


    // Método para mostrar actualizar un registro de fichaje
    public function update(Request $request)
    {
        // Validar entrada
        $request->validate([
            'id' => 'required|exists:fichajes,id',
            'fecha' => 'required|date',
            'hora' => 'required|date_format:H:i',
            'tipo' => 'required|in:entrada,salida',
        ]);

        // Buscar el fichaje
        $fichaje = Fichaje::findOrFail($request->id);

        // Unificar fecha y hora en un solo campo
        $fechaHora = Carbon::createFromFormat('Y-m-d H:i', $request->fecha . ' ' . $request->hora);

        // Actualizar los campos permitidos
        $fichaje->fecha_hora = $fechaHora;
        $fichaje->tipo = $request->tipo;
        $fichaje->save();

        return redirect()->back()->with('success', 'Fichaje actualizado correctamente.');
    }

    public function destroy($id)
    {

        $fichaje = Fichaje::findOrFail($id);

        // Eliminar el fichaje
        $fichaje->delete();

        return redirect()->back()->with('success', 'Fichaje eliminado correctamente.');
    }



    // Función para calcular distancia entre dos puntos geográficos (Haversine formula)
    private function calcularDistancia($lat1, $lon1, $lat2, $lon2)
    {
        $radioTierra = 6371000; // Radio de la Tierra en metros
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $radioTierra * $c; // Distancia en metros
    }


    public function storeManual(Request $request)
    {
        $request->validate([
        'empleado_id' => 'required|exists:empleados,id',
        'empresa_id' => 'required|exists:empresas,id',
        'fecha' => 'required',
        'hora' => 'required|date_format:H:i',
        'tipo' => 'required|in:entrada,salida',
        'latitud' => 'nullable|numeric',
        'longitud' => 'nullable|numeric',
        ]);
    
        // Detectar formato de fecha automáticamente
        if (str_contains($request->fecha, '/')) {
            // Formato d/m/Y
            $fechaHora = Carbon::createFromFormat('d/m/Y H:i', $request->fecha . ' ' . $request->hora);
        } else {
            // Formato Y-m-d
            $fechaHora = Carbon::createFromFormat('Y-m-d H:i', $request->fecha . ' ' . $request->hora);
        }
    
        $userAgent = $request->header('User-Agent');
    
        Fichaje::create([
            'empleado_id' => $request->empleado_id,
            'empresa_id' => $request->empresa_id,
            'fecha_hora' => $fechaHora,
            'tipo' => $request->tipo,
            'latitud' => $request->latitud,
            'longitud' => $request->longitud,
            'dispositivo' => $userAgent ?? 'Manual (admin)',
            'navegador' => $userAgent ?? 'Manual (admin)',
            'ip' => $request->ip(),
            'es_manual' => true,
        ]);
    
        return back()->with('success', 'Fichaje manual registrado correctamente.');
    }


    public function getFichajesPorDia(Request $request)
    {
        $request->validate([
            'empleado_id' => 'required|exists:empleados,id',
            'fecha' => 'required|date',
        ]);

        $fichajes = Fichaje::where('empleado_id', $request->empleado_id)
            ->whereDate('fecha_hora', $request->fecha)
            ->orderBy('fecha_hora')
            ->get();

        return response()->json($fichajes);
    }


    public function resumenHoras(Request $request, $empresaId)
    {
        $empresa = Empresa::findOrFail($empresaId);

        $empleados = Empleado::whereIn('id', function ($query) use ($empresaId) {
            $query->select('empleado_id')
                ->from('fichajes')
                ->where('empresa_id', $empresaId);
        })->get();

        $desde = $request->input('desde');
        $hasta = $request->input('hasta');
        $empleadoId = $request->input('empleado_id');

        $query = Fichaje::where('empresa_id', $empresaId)->with('empleado');

        if ($desde && $hasta) {
            $query->whereBetween('fecha_hora', [$desde, $hasta]);
        } else {
            $query->whereDate('fecha_hora', '>=', now()->subMonth()->toDateString());
        }

        if ($empleadoId) {
            $query->where('empleado_id', $empleadoId);
        }

        $fichajes = $query->get()->groupBy(['empleado_id', function ($item) {
            return $item->fecha_hora->toDateString();
        }]);

        $resumen = [];

        foreach ($fichajes as $empleadoId => $porFecha) {
            $empleado = Empleado::find($empleadoId);
            $totalMinutos = 0;
            $detalle = [];

            foreach ($porFecha as $fecha => $lista) {
                $lista = $lista->sortBy('fecha_hora')->values();
                $minutos = 0;

                $entradas = $lista->where('tipo', 'entrada')->values();
                $salidas = $lista->where('tipo', 'salida')->values();

                $entradaIndex = 0;
                $salidaIndex = 0;

                while ($entradaIndex < $entradas->count() && $salidaIndex < $salidas->count()) {
                    $entrada = $entradas[$entradaIndex];
                    $salida = $salidas->firstWhere('fecha_hora', '>', $entrada->fecha_hora);

                    if ($salida) {
                        $minutos += $entrada->fecha_hora->diffInMinutes($salida->fecha_hora);
                        $salidaIndex = $salidas->search($salida) + 1;
                    }

                    $entradaIndex++;
                }

                $totalMinutos += $minutos;

                $detalle[] = [
                    'fecha' => $fecha,
                    'horas' => $minutos > 0
                        ? \Carbon\CarbonInterval::minutes($minutos)->cascade()->forHumans()
                        : 'Faltan registros para calcular',
                ];
            }

            $resumen[] = [
                'empleado' => $empleado->nombre,
                'total' => $totalMinutos > 0
                    ? \Carbon\CarbonInterval::minutes($totalMinutos)->cascade()->forHumans()
                    : 'Sin registros válidos',
                'detalle' => $detalle,
            ];
        }

        if ($request->wantsJson() || $request->input('exportar') === '1') {
            return [
                'resumen' => $resumen,
                'empresa' => $empresa,
                'desde' => $desde,
                'hasta' => $hasta,
            ];
        }

        return view('resumen-horas', compact('empresa', 'resumen', 'empleados', 'desde', 'hasta', 'empleadoId'));
    }
}
