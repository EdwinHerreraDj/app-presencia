<?php

namespace App\Livewire\Empleados;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Empleado;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    /* ==========
        ESTADO UI
    ========== */
    public bool $showForm = false;
    public ?int $empleadoId = null;

    /* ==========
        CAMPOS
    ========== */
    public string $nombre = '';
    public string $telefono = '';
    public string $dni = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public bool $deshabilitado = false;
    public bool $geolocalizacion_estricta = false;

    public ?int $userId = null;

    /* Variables para eliminar empleado */
    public bool $showDeleteModal = false;
    public ?int $empleadoIdEliminar = null;


    /* ==========
        VALIDACIÓN
    ========== */
    protected function rules(): array
    {
        return [
            'nombre' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:255',

            'dni' => $this->empleadoId
                ? 'required'
                : 'required|string|max:50|unique:empleados,dni',

            'email' => $this->empleadoId
                ? 'required|email'
                : 'required|email|max:255|unique:users,email',

            'password' => $this->empleadoId
                ? 'nullable|min:8|confirmed'
                : 'required|min:8|confirmed',

            'deshabilitado' => 'boolean',
            'geolocalizacion_estricta' => 'boolean',
        ];
    }


    protected function validationAttributes(): array
    {
        return [
            'nombre' => 'nombre',
            'telefono' => 'teléfono',
            'dni' => 'DNI/NIE',
            'email' => 'email',
            'password' => 'contraseña',
        ];
    }


    protected function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.max' => 'El nombre no puede superar los 255 caracteres.',

            'telefono.max' => 'El teléfono no puede superar los 255 caracteres.',

            'dni.required' => 'El DNI o NIE es obligatorio.',
            'dni.unique' => 'Ya existe un empleado registrado con este DNI/NIE.',

            'email.required' => 'El email es obligatorio.',
            'email.email' => 'El email no tiene un formato válido.',
            'email.unique' => 'Este email ya está en uso por otro empleado.',

            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ];
    }

    /** ==========
        VALIDACIÓN EN TIEMPO REAL
    ========== */
    public function updated($property)
    {
        $this->validateOnly($property);
    }



    /* ==========
        ACCIONES
    ========== */

    public function crear()
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function editar(int $id)
    {
        $empleado = Empleado::with('user')->findOrFail($id);

        $this->empleadoId = $empleado->id;
        $this->userId = $empleado->user_id;
        $this->nombre = $empleado->nombre;
        $this->telefono = $empleado->telefono;
        $this->dni = $empleado->dni;
        $this->email = $empleado->user->email;
        $this->deshabilitado = (bool) $empleado->deshabilitado;
        $this->geolocalizacion_estricta = (bool) $empleado->geolocalizacion_estricta;

        $this->showForm = true;
    }


    public function guardar()
    {
        $this->validate();

        DB::transaction(function () {

            if ($this->empleadoId) {
                // EDITAR
                $empleado = Empleado::with('user')->findOrFail($this->empleadoId);

                $empleado->update([
                    'nombre' => $this->nombre,
                    'telefono' => $this->telefono,
                    'dni' => $this->dni,
                    'deshabilitado' => $this->deshabilitado,
                    'geolocalizacion_estricta' => $this->geolocalizacion_estricta,
                ]);

                $empleado->user->update([
                    'email' => $this->email,
                    'name' => $this->nombre,
                    ...($this->password ? ['password' => Hash::make($this->password)] : [])
                ]);
            } else {
                // CREAR
                $user = User::create([
                    'name' => $this->nombre,
                    'email' => $this->email,
                    'password' => Hash::make($this->password),
                    'rol' => 'empleado',
                ]);

                Empleado::create([
                    'user_id' => $user->id,
                    'nombre' => $this->nombre,
                    'telefono' => $this->telefono,
                    'dni' => $this->dni,
                    'deshabilitado' => $this->deshabilitado,
                    'geolocalizacion_estricta' => $this->geolocalizacion_estricta,
                ]);
            }
        });

        $this->resetForm();
        $this->showForm = false;
        $this->dispatch(
            'notify',
            type: 'success',
            message: $this->empleadoId ? 'Empleado actualizado correctamente.' : 'Empleado creado correctamente.'
        );
    }

    /* METODOS PARA ELIMINAR EMPLEADO */
    public function confirmarEliminacion(int $id)
    {
        $this->empleadoIdEliminar = $id;
        $this->showDeleteModal = true;
    }

    public function eliminarConfirmado()
    {
        DB::transaction(function () {
            $empleado = Empleado::with('user')->findOrFail($this->empleadoIdEliminar);

            if ($empleado->user) {
                $empleado->user->delete();
            }

            $empleado->delete();
        });

        $this->showDeleteModal = false;
        $this->empleadoIdEliminar = null;

        $this->dispatch(
            'notify',
            type: 'success',
            message: 'Empleado eliminado correctamente.'
        );

        $this->resetPage();
    }

    private function resetForm()
    {
        $this->reset([
            'empleadoId',
            'nombre',
            'telefono',
            'dni',
            'email',
            'password',
            'password_confirmation',
            'deshabilitado',
            'geolocalizacion_estricta',
        ]);
    }

    public function render()
    {
        return view('livewire.empleados.index', [
            'empleados' => Empleado::with('user')->paginate(10),
        ]);
    }
}
