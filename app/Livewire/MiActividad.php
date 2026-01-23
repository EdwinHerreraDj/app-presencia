<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Fichaje;
use App\Models\Incidencia;
use App\Models\User;
use Carbon\Carbon;
use Session;

class MiActividad extends Component
{
    public $empleado;
    public $desde;
    public $hasta;
    public $tipo;

    public $fichajes = [];
    public $incidencias = [];
    public $totalHoras = '00:00';

    public $modo = 'tabla'; // tabla | resumen

    public $resumen = [];


    /**
     * Se carga el empleado y los filtros por defecto (último mes).
     */
    public function mount()
    {
        $correoEmpleado = Session::get('user_email');

        if (!$correoEmpleado) {
            session()->flash('error', 'Sesión no válida.');
            return;
        }

        $user = User::with('empleado')
            ->where('email', $correoEmpleado)
            ->first();

        if (!$user || !$user->empleado) {
            session()->flash('error', 'Empleado no encontrado.');
            return;
        }

        if ($user->empleado->deshabilitado) {
            session()->flash('error', 'Empleado deshabilitado.');
            return;
        }

        $this->empleado = $user->empleado;

        // Filtro por defecto: último mes
        $this->desde = Carbon::now()->subMonth()->format('Y-m-d');
        $this->hasta = Carbon::now()->format('Y-m-d');

        $this->aplicarFiltros();
    }



    /**
     * Método manual que se ejecuta al pulsar el botón "Aplicar filtros".
     */
    public function aplicarFiltros()
    {
        if (!$this->empleado) return;

        $query = Fichaje::where('empleado_id', $this->empleado->id);

        if ($this->desde) {
            $query->whereDate('fecha_hora', '>=', $this->desde);
        }

        if ($this->hasta) {
            $query->whereDate('fecha_hora', '<=', $this->hasta);
        }

        if ($this->tipo) {
            $query->where('tipo', $this->tipo);
        }

        $this->fichajes = $query
            ->orderBy('fecha_hora', 'asc')
            ->get();

        // Incidencias del empleado (no tienen filtro)
        $this->incidencias = Incidencia::where('empleado_id', $this->empleado->id)
            ->orderBy('fecha', 'desc')
            ->get();

        // Calcular horas del período
        $this->totalHoras = $this->calcularHoras($this->fichajes);

        // Calcular resumen diario
        $this->calcularResumen($this->fichajes);
    }


    /**
     * Cálculo de horas trabajadas agrupado por día.
     */
    private function calcularHoras($fichajes)
    {
        if ($fichajes->isEmpty()) {
            return '00:00';
        }

        $totalMinutos = 0;

        // Agrupar por fecha
        $porFecha = $fichajes->groupBy(function ($item) {
            return $item->fecha_hora->toDateString();
        });

        foreach ($porFecha as $fecha => $lista) {

            $lista = $lista->sortBy('fecha_hora')->values();
            $entradas = $lista->where('tipo', 'entrada')->values();
            $salidas  = $lista->where('tipo', 'salida')->values();

            $entradaIndex = 0;
            $salidaIndex = 0;
            $minutos = 0;

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
        }

        if ($totalMinutos === 0) {
            return 'Faltan registros';
        }

        // Convertir minutos a HH:MM
        return sprintf(
            "%02d:%02d",
            floor($totalMinutos / 60),
            $totalMinutos % 60
        );
    }


    /**
     * Crear resumen diario (como tu vista resumenHoras).
     */
    private function calcularResumen($fichajes)
    {
        $this->resumen = [];

        if ($fichajes->isEmpty()) return;

        // Agrupar por fecha
        $porFecha = $fichajes->groupBy(function ($item) {
            return $item->fecha_hora->toDateString();
        });

        $detalle = [];
        $totalMinutos = 0;

        foreach ($porFecha as $fecha => $lista) {

            $lista = $lista->sortBy('fecha_hora')->values();
            $entradas = $lista->where('tipo', 'entrada')->values();
            $salidas  = $lista->where('tipo', 'salida')->values();

            $entradaIndex = 0;
            $salidaIndex = 0;
            $minutos = 0;

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

        $this->resumen = [
            'empleado' => $this->empleado->nombre,
            'total' => $totalMinutos > 0
                ? sprintf("%d horas %d minutos", floor($totalMinutos / 60), $totalMinutos % 60)
                : 'Sin registros válidos',
            'detalle' => $detalle,
        ];
    }


    /**
     * Vista
     */
    public function render()
    {
        return view('livewire.mi-actividad');
    }
}
