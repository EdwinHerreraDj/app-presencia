<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Empleado;
use App\Models\Empresa;
use App\Models\Fichaje;

class TerminalFichaje extends Component
{
    public string $dni = '';
    public ?string $mensaje = null;
    public string $estado = 'idle'; // idle | ok | error

    public ?int $empresa_id = null;
    public $empresas = [];

    public bool $showModal = false;
    public ?string $nombreEmpleado = null;


    public function mount()
    {
        $this->empresas = Empresa::orderBy('nombre')->get();
    }

    public function registrar()
    {
        // Reset estado visual (IMPORTANTE)
        $this->reset(['mensaje', 'estado', 'showModal', 'nombreEmpleado']);

        // Validación básica
        $this->validate([
            'empresa_id' => 'required|exists:empresas,id',
            'dni'        => 'required|string|min:5',
        ]);

        // Buscar empleado por DNI
        $empleado = Empleado::where('dni', $this->dni)->first();

        if (! $empleado) {
            $this->estado    = 'error';
            $this->mensaje   = 'Empleado no encontrado';
            $this->showModal = true;
            $this->dispatch('terminal-sound', type: 'error');
            return;
        }

        // Último fichaje del empleado
        $ultimo = Fichaje::where('empleado_id', $empleado->id)
            ->orderByDesc('fecha_hora')
            ->first();
        // Validar tiempo mínimo entre fichajes (20 minutos)
        // Validar tiempo mínimo entre fichajes (20 minutos)
        if ($ultimo) {
            $minutosDesdeUltimo = $ultimo->fecha_hora->diffInMinutes(now());

            if ($minutosDesdeUltimo < 20) {
                $faltan = 20 - $minutosDesdeUltimo;

                $this->estado  = 'error';
                $this->mensaje = $faltan === 1
                    ? 'Debes esperar 1 minuto más para volver a fichar.'
                    : "Debes esperar minimo 20 minutos desde el ultimo fichaje.";

                $this->showModal = true;
                $this->dispatch('terminal-sound', type: 'error');
                return;
            }
        }



        /**
         * BLOQUEO 1: fichaje cruzado entre empresas
         */
        if ($ultimo && $ultimo->tipo === 'entrada' && $ultimo->empresa_id !== $this->empresa_id) {
            $this->estado    = 'error';
            $this->mensaje   = 'El empleado tiene una entrada abierta en otro punto de fichaje';
            $this->showModal = true;
            $this->dispatch('terminal-sound', type: 'error');
            return;
        }

        // Decidir tipo de fichaje
        $tipo = ($ultimo && $ultimo->tipo === 'entrada')
            ? 'salida'
            : 'entrada';

        /**
         * BLOQUEO 2: salida sin entrada previa
         */
        if ($tipo === 'salida' && ! $ultimo) {
            $this->estado    = 'error';
            $this->mensaje   = 'No existe una entrada previa para este empleado';
            $this->showModal = true;
            $this->dispatch('terminal-sound', type: 'error');
            return;
        }

        // Crear fichaje
        Fichaje::create([
            'empleado_id' => $empleado->id,
            'empresa_id'  => $this->empresa_id,
            'tipo'        => $tipo,
            'fecha_hora'  => now(),
            'ip'          => request()->ip(),
            'dispositivo' => request()->userAgent(),
            'navegador'   => request()->userAgent(),
        ]);

        // Feedback OK
        $this->estado    = 'ok';
        $this->mensaje   = ucfirst($tipo) . ' registrada correctamente';
        $this->nombreEmpleado = $empleado->nombre;
        $this->showModal = true;
        $this->dispatch('terminal-sound', type: 'ok');

        // Limpiar DNI para el siguiente empleado
        $this->dni = '';
    }

    public function cerrarModal()
    {
        $this->showModal = false;
    }


    public function render()
    {
        return view('livewire.terminal-fichaje');
    }
}
