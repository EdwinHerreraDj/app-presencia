<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Fichaje extends Model
{
    use HasFactory;

    protected $table = 'fichajes'; // Nombre de la tabla

    protected $fillable = [
        'empleado_id',
        'empresa_id',
        'tipo',
        'fecha_hora',
        'latitud',
        'longitud',
        'dentro_rango',
        'dispositivo',
        'ip',
        'navegador'
    ];

    protected $casts = [
        'fecha_hora' => 'datetime', // Convertir fecha_hora a tipo DateTime en Eloquent
        'dentro_rango' => 'boolean', // Convertir dentro_rango a booleano
    ];

    // Relación con el empleado
    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

    // Relación con la empresa
    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }
}
