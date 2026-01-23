<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{

    protected $table = 'empleados';

    protected $fillable = [
        'user_id',
        'nombre',
        'telefono',
        'dni',
        'deshabilitado',
        'geolocalizacion_estricta',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
