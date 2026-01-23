<?php

namespace App\Livewire;

use App\Models\Fichaje;
use App\Models\Empleado;
use Livewire\Component;
use Livewire\WithPagination;

class FichajesTable extends Component
{
    use WithPagination;

    public $searchInput = '';
    public $empleadoInput = '';
    public $tipoInput = '';
    public $fechaDesdeInput = '';
    public $fechaHastaInput = '';

    public $search = '';
    public $empleado_id = '';
    public $tipo = '';
    public $fechaDesde = '';
    public $fechaHasta = '';

    public $empresaId;

    public $modalEditar = false;
    public $modalEliminar = false;

    public $editId;
    public $editNombre;
    public $editFecha;
    public $editHora;
    public $editTipo;

    protected $paginationTheme = 'bootstrap';

    public function mount($empresaId)
    {
        $this->empresaId = $empresaId;
    }

    public function aplicarFiltros()
    {
        $this->search = $this->searchInput;
        $this->empleado_id = $this->empleadoInput;
        $this->tipo = $this->tipoInput;
        $this->fechaDesde = $this->fechaDesdeInput;
        $this->fechaHasta = $this->fechaHastaInput;
        $this->resetPage();
    }

    public function getFichajesProperty()
    {
        return Fichaje::with('empleado')
            ->where('empresa_id', $this->empresaId)
            ->when($this->search, fn($q) =>
                $q->whereHas('empleado', fn($e) =>
                    $e->where('nombre', 'like', "%{$this->search}%")
                      ->orWhere('DNI', 'like', "%{$this->search}%")
                )
            )
            ->when($this->empleado_id, fn($q) =>
                $q->where('empleado_id', $this->empleado_id)
            )
            ->when($this->tipo, fn($q) =>
                $q->where('tipo', $this->tipo)
            )
            ->when($this->fechaDesde, fn($q) =>
                $q->whereDate('fecha_hora', '>=', $this->fechaDesde)
            )
            ->when($this->fechaHasta, fn($q) =>
                $q->whereDate('fecha_hora', '<=', $this->fechaHasta)
            )
            ->orderBy('fecha_hora', 'desc')
            ->paginate(25);
    }

    public function abrirModalEditar($id)
    {
        $f = Fichaje::findOrFail($id);

        $this->editId = $f->id;
        $this->editNombre = $f->empleado->nombre;
        $this->editFecha = date('Y-m-d', strtotime($f->fecha_hora));
        $this->editHora = date('H:i', strtotime($f->fecha_hora));
        $this->editTipo = $f->tipo;

        $this->modalEditar = true;
    }

   public function guardarEdicion()
{
    $this->validate([
        'editFecha' => 'required|date',
        'editHora' => 'required',
        'editTipo' => 'required|in:entrada,salida',
    ]);

    Fichaje::findOrFail($this->editId)->update([
        'fecha_hora' => $this->editFecha . ' ' . $this->editHora,
        'tipo' => $this->editTipo,
    ]);

    $this->modalEditar = false;

    $this->dispatch('notyf', 
        type: 'success', 
        message: 'Fichaje actualizado correctamente.'
    );
}


    public function abrirModalEliminar($id)
    {
        $this->editId = $id;
        $this->modalEliminar = true;
    }

public function eliminarFichaje()
{
    Fichaje::findOrFail($this->editId)->delete();

    $this->modalEliminar = false;

    $this->dispatch('notyf', 
        type: 'success', 
        message: 'Fichaje eliminado correctamente.'
    );
}


    public function render()
    {
        return view('livewire.fichajes-table', [
            'fichajes' => $this->fichajes,
            'empleados' => Empleado::orderBy('nombre')->get(),
        ]);
    }
}
