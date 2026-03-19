<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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

    public function generarQrToken(): string
    {
        $this->qr_token = Str::uuid();
        $this->save();
        return $this->qr_token;
    }
}
