<?php

namespace App\Livewire\Encargado;

use Livewire\Component;
use App\Models\Empresa;
use App\Models\Empleado;
use App\Models\Fichaje;
use Livewire\Attributes\On;

class FichajeQr extends Component
{
    public string $empresaId = '';
    public string $tipo = 'entrada';
    public array $empresas = [];
    public bool $escaneando = false;

    // Último fichaje registrado (para mostrarlo en pantalla)
    public ?string $ultimoNombre = null;
    public ?string $ultimoTipo = null;
    public ?string $ultimaHora = null;
    public ?string $ultimoError = null;

    // Modal resultado (igual que TerminalFichaje)
    public bool $showModal = false;
    public string $estado = 'idle'; // idle | ok | error
    public ?string $nombreEmpleado = null;
    public ?string $mensaje = null;

    public function mount(): void
    {
        $this->empresas = Empresa::where('fichaje_activo', 1)->get()->toArray();
    }

    public function iniciarEscaneo(): void
    {
        if (!$this->empresaId) {
            $this->dispatch('notyf-error', message: 'Debes seleccionar una empresa.');
            return;
        }

        $this->ultimoNombre = null;
        $this->ultimoTipo   = null;
        $this->ultimaHora   = null;
        $this->ultimoError  = null;
        $this->escaneando   = true;

        // Le dice al JS que arranque la cámara
        $this->dispatch('iniciar-camara');
    }

    public function detenerEscaneo(): void
    {
        $this->escaneando = false;
        $this->dispatch('detener-camara');
    }

    // Llamado desde JS cuando se detecta un QR
    #[On('qr-escaneado')]
    public function procesarQr(string $contenido): void
    {
        // Reset
        $this->reset([
            'showModal',
            'estado',
            'mensaje',
            'nombreEmpleado',
            'ultimoNombre',
            'ultimoTipo',
            'ultimaHora',
            'ultimoError'
        ]);

        $token = null;
        try {
            $data  = json_decode($contenido, true);
            $token = $data['token'] ?? null;
        } catch (\Exception) {
            $token = $contenido;
        }

        if (!$token) {
            $this->estado  = 'error';
            $this->mensaje = 'QR no válido.';
            $this->showModal = true;
            $this->dispatch('terminal-sound', type: 'error');
            return;
        }

        $empleado = Empleado::where('qr_token', $token)->first();

        if (!$empleado) {
            $this->estado    = 'error';
            $this->mensaje   = 'QR no reconocido. Empleado no encontrado.';
            $this->showModal = true;
            $this->dispatch('terminal-sound', type: 'error');
            return;
        }

        if ($empleado->deshabilitado) {
            $this->estado    = 'error';
            $this->mensaje   = "'{$empleado->nombre}' está deshabilitado.";
            $this->showModal = true;
            $this->dispatch('terminal-sound', type: 'error');
            return;
        }

        $ultimoFichaje = Fichaje::where('empleado_id', $empleado->id)
            ->where('empresa_id', $this->empresaId)
            ->whereDate('fecha_hora', today())
            ->latest('fecha_hora')
            ->first();

        if ($ultimoFichaje) {

            $minutos = (int) round($ultimoFichaje->fecha_hora->diffInMinutes(now()));
            if ($minutos < 60) {
                $espera = 60 - $minutos;
                $this->estado    = 'error';
                $this->mensaje   = "'{$empleado->nombre}' ya fichó hace {$minutos} min. Espera {$espera} min.";
                $this->showModal = true;
                $this->dispatch('terminal-sound', type: 'error');
                return;
            }
        }

        $userAgent = request()->header('User-Agent');

        Fichaje::create([
            'empleado_id'  => $empleado->id,
            'empresa_id'   => $this->empresaId,
            'tipo'         => $this->tipo,
            'fecha_hora'   => now(),
            'latitud'      => null,
            'longitud'     => null,
            'dentro_rango' => 1,
            'dispositivo'  => $userAgent,
            'navegador'    => $userAgent,
            'ip'           => request()->ip(),
            'es_manual'    => false,
        ]);

        $this->estado         = 'ok';
        $this->nombreEmpleado = $empleado->nombre;
        $this->mensaje        = ucfirst($this->tipo) . ' registrada correctamente · ' . now()->format('H:i:s');
        $this->showModal      = true;
        $this->dispatch('terminal-sound', type: 'ok');
        $this->dispatch('fichaje-ok');
    }

    public function cerrarModal(): void
    {
        $this->showModal = false;
        $this->estado    = 'idle';
        $this->dispatch('fichaje-ok');
    }

    public function render()
    {
        return view('livewire.encargado.fichaje-qr');
    }
}
