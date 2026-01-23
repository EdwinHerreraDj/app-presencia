<div>

    <!-- ============================
         FILTROS
    ============================= -->
    <div class="card mb-3">
        <div class="card-header">Filtrar actividad</div>

        <div class="card-body">

            <div class="row g-3">

                <div class="col-md-4">
                    <label class="form-label">Desde</label>
                    <input type="date" wire:model.defer="desde" class="form-control">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Hasta</label>
                    <input type="date" wire:model.defer="hasta" class="form-control">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Tipo</label>
                    <select wire:model.defer="tipo" class="form-select">
                        <option value="">Todos</option>
                        <option value="entrada">Entrada</option>
                        <option value="salida">Salida</option>
                    </select>
                </div>

            </div>

            <div class="mt-3 text-end">
                <button class="btn btn-primary" wire:click="aplicarFiltros">
                    <i class="bi bi-funnel-fill me-1"></i>
                    Aplicar filtros
                </button>
            </div>

        </div>
    </div>


    <!-- ============================
         BOTÓN CAMBIO DE VISTA
    ============================= -->
    <div class="mb-3 text-end">
        @if($modo === 'tabla')
        <button class="btn btn-secondary btn-sm" wire:click="$set('modo', 'resumen')">
            <i class="bi bi-list-columns-reverse me-1"></i>
            Ver resumen diario
        </button>
        @else
        <button class="btn btn-primary btn-sm" wire:click="$set('modo', 'tabla')">
            <i class="bi bi-table me-1"></i>
            Ver tabla de fichajes
        </button>
        @endif
    </div>


    <!-- ============================
         TOTAL HORAS
    ============================= -->
    <div class="alert alert-info">
        <strong>Total de horas trabajadas:</strong> {{ $totalHoras }}
    </div>


    <!-- ============================
         MODO TABLA DE FICHAJES
    ============================= -->
    @if ($modo === 'tabla')

    <div class="card">
        <div class="card-header">Fichajes</div>

        <div class="card-body">

            @if (empty($fichajes) || count($fichajes) === 0)
            <p class="text-muted mb-0">No hay fichajes para mostrar.</p>
            @else
            <div class="table-responsive">
                <table class="table table-sm table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Tipo</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($fichajes as $f)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($f->fecha_hora)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($f->fecha_hora)->format('H:i') }}</td>
                            <td>{{ ucfirst($f->tipo) }}</td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
            @endif

        </div>
    </div>

    @endif


    <!-- ============================
         MODO RESUMEN DIARIO
    ============================= -->
    @if ($modo === 'resumen')

    <div class="card mt-3">
        <div class="card-header">
            <strong>{{ $resumen['empleado'] }}</strong>
            — Total: {{ $resumen['total'] }}
        </div>

        <div class="card-body">

            @if (!empty($resumen['detalle']))

            <ul class="mb-0">
                @foreach ($resumen['detalle'] as $dia)
                <li>
                    {{ \Carbon\Carbon::parse($dia['fecha'])->format('d/m/Y') }}
                    —
                    {{ $dia['horas'] }}
                </li>
                @endforeach
            </ul>

            @else
            <p class="text-muted mb-0">No hay datos para mostrar en el resumen.</p>
            @endif

        </div>
    </div>

    @endif

</div>