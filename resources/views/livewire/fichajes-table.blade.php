<div>
    {{-- Filtros --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-light border-bottom">
            <h6 class="fw-bold mb-0 text-secondary d-flex align-items-center">
                <i class="bi bi-funnel me-2"></i>Filtros de búsqueda
            </h6>
        </div>

        <div class="card-body">

            <div class="row g-4">

                <!-- Buscar -->
                <div class="col-md-4">
                    <label class="form-label text-secondary fw-semibold">
                        <i class="bi bi-search me-1"></i>Buscar
                    </label>
                    <input wire:model="searchInput" type="text" class="form-control shadow-sm"
                        placeholder="Buscar...">
                </div>

                <!-- Filtro de empleado -->
                <div class="col-md-4">
                    <label class="form-label text-secondary fw-semibold">
                        <i class="bi bi-person-badge me-1"></i>Empleado
                    </label>
                    <select wire:model="empleadoInput" class="form-select shadow-sm">
                        <option value="">Todos los empleados</option>
                        @foreach ($empleados as $empleado)
                            <option value="{{ $empleado->id }}">{{ $empleado->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Tipo -->
                <div class="col-md-4">
                    <label class="form-label text-secondary fw-semibold">
                        <i class="bi bi-filter me-1"></i>Tipo de fichaje
                    </label>
                    <select wire:model="tipoInput" class="form-select shadow-sm">
                        <option value="">Todos los tipos</option>
                        <option value="entrada">Entrada</option>
                        <option value="salida">Salida</option>
                    </select>
                </div>

                <!-- Fecha desde -->
                <div class="col-md-4">
                    <label class="form-label text-secondary fw-semibold">
                        <i class="bi bi-calendar-event me-1"></i>Fecha desde
                    </label>
                    <input type="date" wire:model="fechaDesdeInput" class="form-control shadow-sm">
                </div>

                <!-- Fecha hasta -->
                <div class="col-md-4">
                    <label class="form-label text-secondary fw-semibold">
                        <i class="bi bi-calendar-check me-1"></i>Fecha hasta
                    </label>
                    <input type="date" wire:model="fechaHastaInput" class="form-control shadow-sm">
                </div>

                <!-- Botón aplicar filtros -->
                <div class="col-md-4 d-flex align-items-end">
                    <button wire:click="aplicarFiltros"
                        class="btn btn-primary w-100 shadow-sm d-flex align-items-center justify-content-center gap-2 py-2">
                        <i class="bi bi-check2-circle"></i>
                        Aplicar filtros
                    </button>
                </div>

            </div>

        </div>
    </div>


    {{-- Tabla --}}
    @if ($fichajes->count() > 0)

        {{-- Tabla --}}
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Empleado</th>
                    <th>DNI</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Tipo</th>
                    <th>IP</th>
                    <th>Rango</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($fichajes as $fichaje)
                    @php
                        $fecha = \Carbon\Carbon::parse($fichaje->fecha_hora)->format('d/m/Y');
                        $hora = \Carbon\Carbon::parse($fichaje->fecha_hora)->format('H:i:s');
                    @endphp

                    <tr>
                        <td>{{ $fichaje->empleado->nombre }}</td>
                        <td>{{ $fichaje->empleado->DNI }}</td>
                        <td>{{ $fecha }}</td>
                        <td>{{ $hora }}</td>
                        <td>{{ ucfirst($fichaje->tipo) }}</td>
                        <td>{{ $fichaje->ip }}</td>
                        <td>{{ $fichaje->dentro_rango ? 'Sí' : 'No' }}</td>

                        <td>
                            <!-- Ver ubicación -->
                            <button class="btn btn-outline-primary btn-sm"
                                wire:click="
        $dispatch('verMapa', {
            lat: {{ $fichaje->latitud ?? 'null' }},
            lng: {{ $fichaje->longitud ?? 'null' }},
            nombre: '{{ $fichaje->empleado->nombre }}',
            fecha: '{{ $fichaje->fecha_hora }}'
        })
    ">
                                <i class="bi bi-geo-alt"></i> Ver ubicación
                            </button>



                            <!-- Editar -->
                            <button class="btn btn-sm btn-primary" wire:click="abrirModalEditar({{ $fichaje->id }})">
                                Editar
                            </button>

                            <!-- Eliminar -->
                            <button class="btn btn-sm btn-danger" wire:click="abrirModalEliminar({{ $fichaje->id }})">
                                Eliminar
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>


        {{-- Paginación --}}
        {{ $fichajes->links() }}
    @else
        {{-- Mensaje cuando no hay registros --}}
        <div class="alert alert-warning text-center mt-4">
            No se encontraron registros con los filtros seleccionados.
        </div>

    @endif




    @if ($modalEditar)
        <div class="modal fade show d-block" style="background: rgba(0,0,0,.5);">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <form wire:submit.prevent="guardarEdicion">
                        <div class="modal-header">
                            <h5 class="modal-title">Editar Fichaje</h5>
                            <button type="button" class="btn-close" wire:click="$set('modalEditar', false)"></button>
                        </div>

                        <div class="modal-body">

                            <div class="mb-3">
                                <label class="form-label">Empleado</label>
                                <input type="text" class="form-control" wire:model="editNombre" disabled>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Fecha</label>
                                <input type="date" class="form-control" wire:model="editFecha">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Hora</label>
                                <input type="time" class="form-control" wire:model="editHora">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Tipo</label>
                                <select class="form-select" wire:model="editTipo">
                                    <option value="entrada">Entrada</option>
                                    <option value="salida">Salida</option>
                                </select>
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                wire:click="$set('modalEditar', false)">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Guardar cambios</button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    @endif

    @if ($modalEliminar)
        <div class="modal fade show d-block" style="background: rgba(0,0,0,.5);">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">Confirmar eliminación</h5>
                        <button type="button" class="btn-close" wire:click="$set('modalEliminar', false)"></button>
                    </div>

                    <div class="modal-body">
                        <p>¿Estás seguro de que deseas eliminar este fichaje?</p>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" wire:click="$set('modalEliminar', false)">Cancelar</button>
                        <button class="btn btn-danger" wire:click="eliminarFichaje">Eliminar</button>
                    </div>

                </div>
            </div>
        </div>
    @endif

    <!-- Modal Mapa -->
    <div class="modal fade" id="modalMapa" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content shadow-lg border-0">

                <!-- HEADER -->
                <div class="modal-header bg-light border-bottom border-primary-subtle">
                    <h5 class="modal-title fw-bold text-primary d-flex align-items-center">
                        <i class="bi bi-geo-alt-fill me-2"></i>Ubicación del fichaje
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <!-- BODY -->
                <div class="modal-body p-4" style="min-height: 350px;">

                    <!-- MENSAJE DE ERROR (NO SE TOCA NADA, SOLO ESTÉTICA) -->
                    <div id="mapa-error" class="alert alert-danger d-none shadow-sm fw-semibold">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        No hay datos de geolocalización disponibles para este fichaje.
                    </div>

                    <!-- MAPA (MISMO ID Y MISMA LOGICA) -->
                    <div id="mapaFichaje" class="rounded shadow-sm border"
                        style="height: 350px; width: 100%; display:none;"></div>
                </div>

            </div>
        </div>
    </div>








</div>
