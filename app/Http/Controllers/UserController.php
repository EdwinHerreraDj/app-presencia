<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    /* Metodo para rectificar si el email esta disponible */
    public function checkEmail(Request $request)
    {
        $email = $request->query('email');

        $exists = \App\Models\User::where('email', $email)->exists();

        return response()->json(['exists' => $exists]);
    }

    public function store(Request $request)
    {
        // Validación de los datos enviados
        $validatedData = $request->validate([
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|email|unique:users,email',
            'password'              => 'required|min:5|confirmed',
            'rol'                   => 'required|in:admin,encargado,user',
        ]);

        // Creación del usuario, asegurándose de encriptar la contraseña
        $user = User::create([
            'name'     => $validatedData['name'],
            'email'    => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'rol'      => $validatedData['rol'],
        ]);


        return redirect()->route('users')
            ->with('success', 'Usuario creado exitosamente');
    }


    public function update(Request $request, $id)
    {
        // Buscar el usuario a actualizar
        $user = User::findOrFail($id);

        // Definir las reglas de validación básicas
        $rules = [
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'rol'   => 'required|in:admin,encargado,user',
        ];

        // Si se envía una nueva contraseña, se valida y se requiere la confirmación
        if ($request->filled('password')) {
            $rules['password'] = 'min:5|confirmed';
        }

        // Mensajes de error personalizados 
        $messages = [
            'email.unique' => 'El correo ya está en uso por otro usuario.',
        ];

        // Validar los datos del formulario
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->route('users')->with('error', $validator->errors()->first());
        }

        // Si la validación es exitosa, obtener los datos validados
        $validatedData = $validator->validated();

        // Actualizar los campos del usuario
        $user->name  = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->rol   = $validatedData['rol'];

        // Si se ingresó una nueva contraseña, actualizarla
        if ($request->filled('password')) {
            $user->password = Hash::make($validatedData['password']);
        }

        $user->save();

        return redirect()->route('users')->with('success', 'Usuario actualizado correctamente');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        return redirect()->route('users')->with('success', 'Usuario eliminado correctamente');
    }
}
