<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empleado;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;




class EmpleadoController extends Controller
{
    public function store(Request $request)
    {

        // Validar los datos del formulario
        $request->validate([
            'nombre' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:255',
            'DNI' => 'required|string|max:255|unique:empleados,DNI',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'deshabilitado' => 'required|in:0,1',
            'geolocalizacion_estricta' => 'required|in:0,1',
        ]);


        // Crear usuario en la tabla users
        $user = User::create([
            'name' => $request->nombre,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol' => 'empleado'
        ]);

        // Crear empleado en la tabla empleados
        Empleado::create([
            'nombre' => $request->nombre,
            'telefono' => $request->telefono,
            'DNI' => $request->DNI,
            'email' => $request->email,
            'password' => $user->password,
            'deshabilitado' => (int) $request->deshabilitado,
            'geolocalizacion_estricta' => (int) $request->geolocalizacion_estricta,
        ]);


        return redirect()->route('empleados')->with('success', 'Empleado creado correctamente.');
    }

    public function update(Request $request, $id)
    {
        // Verificar que los datos lleguen correctamente
        // dd($request->all());

        // Validación de datos
        $request->validate([
            'nombre' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:255',
            'DNI' => 'required|string|max:255',
            'password' => 'nullable|string|min:6|confirmed',
            'deshabilitado' => 'required|in:0,1',
            'geolocalizacion_estricta' => 'required|in:0,1',
        ]);


        if ($request->filled('email')) { 
            $rules['email'] = 'required|string|email|max:255|unique:users,email';
        }

        $empleado = Empleado::findOrFail($id);
        $emailAnterior = $empleado->email;

        // Actualizar los campos del empleado
        $empleado->update([
            'nombre' => $request->nombre,
            'telefono' => $request->telefono,
            'DNI' => $request->DNI,
            'email' => $request->email,
            'deshabilitado' => (int) $request->deshabilitado,
            'geolocalizacion_estricta' => (int) $request->geolocalizacion_estricta,
        ]);

        $user = User::where('email', $emailAnterior)->first();

        if (!$user) {
            return redirect()->route('empleados')->with('error', 'No se encontró el usuario relacionado.');
        }

        // Actualizar los campos del usuario
        $datosUsuario = [
            'name' => $request->nombre,
            'email' => $request->email,
        ];

        if (!empty($request->password)) {
            $datosUsuario['password'] = Hash::make($request->password);
        }

        $user->update($datosUsuario);

        return redirect()->route('empleados')->with('success', 'Empleado actualizado correctamente.');
    }

    public function destroy($id)
    {
        // Buscar al empleado
        $empleado = Empleado::findOrFail($id);

        // Buscar al usuario relacionado
        $user = User::where('email', $empleado->email)->first();

        // Eliminar el usuario si existe
        if ($user) {
            $user->delete();
        }

        // Eliminar el empleado
        $empleado->delete();

        return redirect()->route('empleados')->with('success', 'Empleado eliminado correctamente.');
    }
}
