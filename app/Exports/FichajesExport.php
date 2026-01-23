<?php

namespace App\Exports;

use App\Models\Fichaje;
use Maatwebsite\Excel\Concerns\FromCollection;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class FichajesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $empresaId;
    protected $fechaInicio;
    protected $fechaFin;

    public function __construct($empresaId, $fechaInicio = null, $fechaFin = null)
    {
        $this->empresaId = $empresaId;
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin = $fechaFin;
    }

    public function collection()
    {
        $query = Fichaje::where('empresa_id', $this->empresaId)->with('empleado');

        if ($this->fechaInicio && $this->fechaFin) {
            $query->whereBetween('fecha_hora', [
                Carbon::parse($this->fechaInicio)->startOfDay(),
                Carbon::parse($this->fechaFin)->endOfDay()
            ]);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Empleado',
            'Teléfono',
            'DNI',
            'Fecha',
            'Hora',
            'Tipo',
            'Latitud',
            'Longitud',
            'Dentro del Rango',
            'ip',
            'Dispositivo'
        ];
    }

    public function map($fichaje): array
    {
        $fechaHora = Carbon::parse($fichaje->fecha_hora);

        return [
            optional($fichaje->empleado)->nombre ?? 'Sin datos',
            optional($fichaje->empleado)->telefono ?? 'Sin datos',
            optional($fichaje->empleado)->DNI ?? 'Sin datos',
            $fechaHora->format('d/m/Y'),
            $fechaHora->format('H:i:s'),
            $fichaje->tipo,
            number_format($fichaje->latitud, 6, '.', ''),
            number_format($fichaje->longitud, 6, '.', ''),
            $fichaje->dentro_rango ? "Sí" : "No",
            $fichaje->ip,
            $fichaje->dispositivo
        ];
    }
}
