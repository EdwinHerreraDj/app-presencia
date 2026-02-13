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
    public bool $redirigirAIncidencias = false;

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

        // Validar empresa
        if (!$this->empresa_id) {
            $this->setError('Debes seleccionar una empresa.');
            return;
        }

        // Validar geolocalización (si no es válida la anulamos)
        if (!is_numeric($this->latitud) || !is_numeric($this->longitud)) {
            $this->latitud = null;
            $this->longitud = null;
        }

        // Obtener empleado
        $empleado = $this->obtenerEmpleado();
        if (!$empleado) {
            return;
        }

        // Control de espera entre fichajes
        if ($this->debeEsperar($empleado)) {
            return;
        }

        // Buscar empresa
        $empresa = Empresa::find($this->empresa_id);
        if (!$empresa) {
            $this->setError('Empresa no válida.');
            return;
        }

        // Calcular si está dentro del rango SOLO si hay coordenadas
        $dentro = false;

        if ($this->latitud !== null && $this->longitud !== null) {
            $dentro = $this->estaDentroDelRango($empresa);
        }

        // Si la geolocalización es estricta y no hay coordenadas → bloquear
        if ($empleado->geolocalizacion_estricta && $this->latitud === null) {
            $this->setError('La geolocalización es obligatoria para este empleado.');
            return;
        }

        // Si es estricta y está fuera del rango → bloquear
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

    public function obtenerEmpleado()
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

    public function ubicacionBloqueada(): void
    {
        $this->showModal = true;
        $this->modalEstado = 'error';
        $this->modalMensaje = 'Has bloqueado la ubicación en el navegador.

Debes activarla desde el icono 🔒 en la barra de direcciones.

Si no puedes activarla, genera una incidencia para que el administrador valide tu fichaje manualmente.';

        $this->redirigirAIncidencias = true;
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

    public function setError(string $mensaje): void
    {
        $this->showModal = true;
        $this->modalEstado = 'error';
        $this->modalMensaje = $mensaje;
    }


    public function cerrarModal(): void
    {
        $this->showModal = false;

        if ($this->redirigirAIncidencias) {
            $this->redirect(route('incidencias'));
            return;
        }

        $this->modalEstado = 'loading';
        $this->modalMensaje = '';
    }



    public function render()
    {
        return view('livewire.empleado.registro-fichaje');
    }
}
