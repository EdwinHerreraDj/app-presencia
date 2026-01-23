<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ResumenHorasExport implements FromView
{
    protected $resumen;
    protected $empresa;
    protected $desde;
    protected $hasta;

    public function __construct($resumen, $empresa, $desde, $hasta)
    {
        $this->resumen = $resumen;
        $this->empresa = $empresa;
        $this->desde = $desde;
        $this->hasta = $hasta;
    }

    public function view(): View
    {
        return view('exports.resumen_horas_excel', [
            'resumen' => $this->resumen,
            'empresa' => $this->empresa,
            'desde' => $this->desde,
            'hasta' => $this->hasta,
        ]);
    }
}
