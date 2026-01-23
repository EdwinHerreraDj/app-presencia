<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empresa;

class EmpresaController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:500',
            'latitud' => 'required|numeric|between:-90,90',
            'longitud' => 'required|numeric|between:-180,180',
            'radio' => 'required|integer|min:0',
            'fichaje_activo' => 'sometimes|boolean'
        ]);

        Empresa::create([
            'nombre' => $request->nombre,
            'direccion' => $request->direccion,
            'descripcion' => $request->descripcion,
            'latitud' => $request->latitud,
            'longitud' => $request->longitud,
            'radio' => $request->radio,
            'fichaje_activo' => $request->has('fichaje_activo') ? $request->fichaje_activo : 0
        ]);

        return redirect()->route('empresas')->with('success', 'Empresa creada correctamente.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:500',
            'latitud' => 'required|numeric|between:-90,90',
            'longitud' => 'required|numeric|between:-180,180',
            'radio' => 'required|integer|min:0',
            'fichaje_activo' => 'sometimes|boolean'
        ]);

        $empresa = Empresa::find($id);
        $empresa->nombre = $request->nombre;
        $empresa->direccion = $request->direccion;
        $empresa->descripcion = $request->descripcion;
        $empresa->latitud = $request->latitud;
        $empresa->longitud = $request->longitud;
        $empresa->radio = $request->radio;
        $empresa->fichaje_activo = $request->has('fichaje_activo') ? $request->fichaje_activo : 0;
        $empresa->save();

        return redirect()->route('empresas')->with('success', 'Empresa actualizada correctamente.');
    }

    public function destroy($id)
    {
        $empresa = Empresa::find($id);
        $empresa->delete();

        return redirect()->route('empresas')->with('success', 'Empresa eliminada correctamente.');
    }
}
