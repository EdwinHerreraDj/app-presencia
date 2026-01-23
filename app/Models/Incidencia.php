<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Incidencia extends Model
{
    use HasFactory;

    protected $fillable = [
        'empleado_id',
        'empresa_id',
        'fecha',
        'hora',
        'tipo',
        'motivo',
        'estado',
    ];

    protected $table = 'incidencias';

    
    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }
}
