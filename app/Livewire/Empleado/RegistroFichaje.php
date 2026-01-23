<?php

namespace App\Livewire\Empleado;

use Livewire\Component;
use App\Models\Empresa;
use App\Models\Fichaje;
use App\Models\User;
use Illuminate\Support\Facades\Session;

class RegistroFichaje extends Component
{
    public $empresa_id;
    public $latitud;
    public $longitud;
    public $empresas;

    /* ===== MODAL ===== */
    public bool $showModal = false;
    public string $modalEstado = 'loading'; // loading | success | error
    public string $modalMensaje = '';

    public function mount()
    {
        $this->empresas = Empresa::orderBy('nombre')->get();
    }

    /* =======================================================
     * MÉTODO PRINCIPAL
     * ======================================================= */
    public function registrar(string $tipo): void
    {
        // Abrimos modal en estado carga
        $this->showModal = true;
        $this->modalEstado = 'loading';
        $this->modalMensaje = 'Registrando ' . ucfirst($tipo) . '…';

        if (!$this->empresa_id) {
            $this->setError('Debes seleccionar una empresa.');
            return;
        }

        if (!is_numeric($this->latitud) || !is_numeric($this->longitud)) {
            $this->setError('No se pudo obtener la geolocalización.');
            return;
        }

        $empleado = $this->obtenerEmpleado();
        if (!$empleado) {
            return;
        }

        if ($this->debeEsperar($empleado)) {
            return;
        }

        $empresa = Empresa::find($this->empresa_id);
        if (!$empresa) {
            $this->setError('Empresa no válida.');
            return;
        }

        $dentro = $this->estaDentroDelRango($empresa);

        if ($empleado->geolocalizacion_estricta && !$dentro) {
            $this->setError('No estás dentro de la zona permitida.');
            return;
        }

        // Guardar fichaje
        Fichaje::create([
            'empleado_id'  => $empleado->id,
            'empresa_id'   => $empresa->id,
            'tipo'         => $tipo,
            'fecha_hora'   => now(),
            'latitud'      => $this->latitud,
            'longitud'     => $this->longitud,
            'dentro_rango' => $dentro,
            'dispositivo'  => request()->userAgent(),
            'navegador'    => request()->userAgent(),
            'ip'           => request()->ip(),
        ]);

        // Éxito
        $this->modalEstado = 'success';
        $this->modalMensaje = 'Fichaje de ' . ucfirst($tipo) . ' registrado correctamente.';
    }

    /* =======================================================
     * MÉTODOS AUXILIARES
     * ======================================================= */

    private function obtenerEmpleado()
    {
        $correo = Session::get('user_email');

        if (!$correo) {
            $this->setError('Sesión no válida.');
            return null;
        }

        $user = User::with('empleado')->where('email', $correo)->first();

        if (!$user || !$user->empleado) {
            $this->setError('Empleado no encontrado.');
            return null;
        }

        if ($user->empleado->deshabilitado) {
            $this->setError('Este empleado está deshabilitado.');
            return null;
        }

        return $user->empleado;
    }

    private function debeEsperar($empleado): bool
    {
        $ultimo = Fichaje::where('empleado_id', $empleado->id)
            ->where('empresa_id', $this->empresa_id)
            ->latest('fecha_hora')
            ->first();

        if (
            $ultimo &&
            $ultimo->fecha_hora->isToday() &&
            $ultimo->fecha_hora->diffInMinutes(now()) < 60
        ) {
            $this->setError('Debes esperar 1 hora entre fichajes.');
            return true;
        }

        return false;
    }

    private function estaDentroDelRango($empresa): bool
    {
        $r = 6371000;
        $dLat = deg2rad($empresa->latitud - $this->latitud);
        $dLon = deg2rad($empresa->longitud - $this->longitud);

        $a = sin($dLat / 2) ** 2 +
            cos(deg2rad($this->latitud)) *
            cos(deg2rad($empresa->latitud)) *
            sin($dLon / 2) ** 2;

        $distancia = $r * 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $distancia <= $empresa->radio;
    }

    private function setError(string $mensaje): void
    {
        $this->modalEstado = 'error';
        $this->modalMensaje = $mensaje;
    }

    public function cerrarModal(): void
    {
        $this->showModal = false;
        $this->modalEstado = 'loading';
        $this->modalMensaje = '';
    }

    public function render()
    {
        return view('livewire.empleado.registro-fichaje');
    }
}
