<?php

namespace App\Livewire\Encargado;

use Livewire\Component;
use App\Models\Empresa;
use App\Models\Empleado;
use App\Models\Fichaje;
use Carbon\Carbon;

class FichajeManual extends Component
{
    public $empresaId;
    public $empresas = [];
    public $empleados = [];

    public $mostrarFichajes = false;
    public $tablaFichajesVisible = true;
    public $fichajesHoy = [];

    public $editandoId = null;
    public $horaEditada = null;

    public $mostrarModalEliminar = false;
    public $fichajeAEliminar = null;

    public function mount(): void
    {
        $this->empresas = Empresa::where('fichaje_activo', 1)->get();
    }

    public function verFichajesHoy(): void
    {
        if (!$this->empresaId) {
            $this->dispatch('notyf-error', message: 'Debes seleccionar una empresa');
            return;
        }

        $this->cargarFichajes();
        $this->mostrarFichajes = true;
        $this->tablaFichajesVisible = true;
    }

    private function cargarFichajes(): void
    {
        $this->fichajesHoy = Fichaje::with('empleado')
            ->where('empresa_id', $this->empresaId)
            ->whereDate('fecha_hora', today())
            ->orderBy('fecha_hora')
            ->get();
    }

    public function confirmarEliminar(int $id): void
    {
        $this->fichajeAEliminar = $id;
        $this->mostrarModalEliminar = true;
    }

    public function eliminarFichajeConfirmado(): void
    {
        $fichaje = Fichaje::find($this->fichajeAEliminar);

        if ($fichaje) {
            $fichaje->delete();
            $this->dispatch('notyf-success', message: 'Fichaje eliminado correctamente');
        }

        $this->mostrarModalEliminar = false;
        $this->fichajeAEliminar = null;

        $this->cargarFichajes();
    }

    public function cambiarTipo(int $id): void
    {
        $fichaje = Fichaje::find($id);

        if (!$fichaje) return;

        $fichaje->tipo = $fichaje->tipo === 'entrada' ? 'salida' : 'entrada';
        $fichaje->save();

        $this->dispatch('notyf-success', message: 'Tipo de fichaje actualizado');
        $this->cargarFichajes();
    }

    public function editarHora(int $id): void
    {
        $fichaje = Fichaje::find($id);

        if (!$fichaje) return;

        $this->editandoId = $id;
        $this->horaEditada = Carbon::parse($fichaje->fecha_hora)->format('H:i');
    }

    public function guardarHora(int $id): void
    {
        $fichaje = Fichaje::find($id);

        if (!$fichaje) return;

        $fecha = Carbon::parse($fichaje->fecha_hora)->format('Y-m-d');
        $fichaje->fecha_hora = "{$fecha} {$this->horaEditada}:00";
        $fichaje->save();

        $this->editandoId = null;
        $this->horaEditada = null;

        $this->dispatch('notyf-success', message: 'Hora actualizada correctamente');
        $this->cargarFichajes();
    }

    public function updatedEmpresaId(): void
    {
        if (!$this->empresaId) {
            $this->empleados = [];
            return;
        }

        $empleados = Empleado::where('deshabilitado', 0)->get();

        $this->empleados = $empleados->map(function ($empleado) {
            $ultimo = Fichaje::where('empleado_id', $empleado->id)
                ->orderByDesc('fecha_hora')
                ->first();

            $empleado->estado = ($ultimo &&
                Carbon::parse($ultimo->fecha_hora)->isToday() &&
                $ultimo->tipo === 'entrada')
                ? 'dentro'
                : 'fuera';

            return $empleado;
        });
    }

    public function ficharEntrada(int $empleadoId): void
    {
        $this->crearFichaje($empleadoId, 'entrada');
    }

    public function ficharSalida(int $empleadoId): void
    {
        $this->crearFichaje($empleadoId, 'salida');
    }

    private function crearFichaje(int $empleadoId, string $tipo): void
    {
        Fichaje::create([
            'empleado_id'  => $empleadoId,
            'empresa_id'   => $this->empresaId,
            'tipo'         => $tipo,
            'fecha_hora'   => now(),
            'latitud'      => null,
            'longitud'     => null,
            'dentro_rango' => 1,
            'dispositivo'  => 'encargado',
            'navegador'    => 'manual',
        ]);

        $mensaje = $tipo === 'entrada'
            ? 'Entrada registrada correctamente'
            : 'Salida registrada correctamente';

        $this->dispatch('notyf-success', message: $mensaje);
        $this->updatedEmpresaId();

        if ($this->mostrarFichajes) {
            $this->cargarFichajes();
        }
    }

    public function render()
    {
        return view('livewire.encargado.fichaje-manual');
    }
}
