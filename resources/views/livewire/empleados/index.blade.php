<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex align-items-start justify-content-between gap-3 mb-4">
        <div>
            <h5 class="mb-1 fw-bold text-dark">Empleados</h5>
            <p class="mb-0 text-muted small">Gestión de empleados y acceso</p>
        </div>

        <button wire:click="crear" type="button" class="btn btn-primary">
            + Nuevo empleado
        </button>
    </div>

    {{-- FORM --}}
    @if ($showForm)
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header border-bottom">
                <div class="d-flex align-items-center justify-content-between">
                    <span class="fw-semibold text-dark">Formulario</span>
                    <button type="button" wire:click="$set('showForm', false)"
                        class="btn btn-sm btn-outline-secondary">
                        Cerrar
                    </button>
                </div>
            </div>

            <div class="card-body">
                <form wire:submit.prevent="guardar" class="row g-3">

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold">Nombre</label>
                        <input wire:model.lazy="nombre" class="form-control" placeholder="Nombre">
                        @error('nombre')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold">Teléfono</label>
                        <input wire:model.lazy="telefono" class="form-control" placeholder="Teléfono">
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold">DNI / NIE</label>
                        <input wire:model.lazy="dni" class="form-control" placeholder="DNI / NIE">
                        @error('dni')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold">Email</label>
                        <input wire:model.lazy="email" class="form-control" placeholder="Email"
                            @if ($empleadoId) disabled @endif>
                        @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                        @if ($empleadoId)
                            <div class="form-text text-muted">
                                Este dato no se puede modificar una vez creado.
                            </div>
                        @endif

                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold">Contraseña</label>
                        <input type="password" wire:model.lazy="password" class="form-control" placeholder="Contraseña">
                        @error('password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold">Confirmar contraseña</label>
                        <input type="password" wire:model.lazy="password_confirmation" class="form-control"
                            placeholder="Confirmar contraseña">
                    </div>

                    <div class="col-12">
                        <div class="d-flex flex-column flex-md-row gap-3 justify-content-between align-items-md-center">

                            <div class="d-flex flex-column flex-sm-row gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" wire:model="deshabilitado"
                                        id="chkDeshabilitado">
                                    <label class="form-check-label" for="chkDeshabilitado">
                                        Deshabilitado
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox"
                                        wire:model="geolocalizacion_estricta" id="chkGeo">
                                    <label class="form-check-label" for="chkGeo">
                                        Geolocalización estricta
                                    </label>
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="button" wire:click="$set('showForm', false)"
                                    class="btn btn-outline-secondary">
                                    Cancelar
                                </button>

                                <button type="submit" class="btn btn-success">
                                    Guardar
                                </button>
                            </div>

                        </div>
                    </div>

                </form>
            </div>
        </div>
    @endif

    {{-- TABLA --}}
    <div class="card shadow-sm border-0">
        <div class="card-header border-bottom">
            <div class="d-flex align-items-center justify-content-between">
                <span class="fw-semibold text-dark">Listado</span>
                <span class="text-muted small">
                    Total: {{ $empleados->total() ?? $empleados->count() }}
                </span>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr class="text-uppercase small text-muted">
                            <th class="ps-3">Empleado</th>
                            <th>DNI</th>
                            <th>Email</th>
                            <th class="text-end pe-3">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($empleados as $empleado)
                            <tr>
                                <td class="ps-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-circle bg-secondary-subtle text-secondary fw-bold d-flex align-items-center justify-content-center"
                                            style="width: 38px; height: 38px;">
                                            {{ strtoupper(substr($empleado->nombre ?? '—', 0, 1)) }}
                                        </div>

                                        <div class="d-flex flex-column">
                                            <span class="fw-semibold text-dark">
                                                {{ $empleado->nombre ?? '—' }}
                                            </span>

                                            <div class="d-flex flex-wrap gap-2 mt-1">
                                                @if ($empleado->deshabilitado ?? false)
                                                    <span class="badge text-bg-danger">Deshabilitado</span>
                                                @else
                                                    <span class="badge text-bg-success">Activo</span>
                                                @endif

                                                @if ($empleado->geolocalizacion_estricta ?? false)
                                                    <span class="badge text-bg-primary">Geo estricta</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td class="fw-semibold text-dark">
                                    {{ $empleado->dni ?? '—' }}
                                </td>

                                <td class="text-muted">
                                    {{ $empleado->user?->email ?? '—' }}
                                </td>

                                <td class="text-end pe-3">
                                    <div class="d-inline-flex gap-2">
                                        <button wire:click="editar({{ $empleado->id }})" type="button"
                                            class="btn btn-sm btn-outline-secondary">
                                            Editar
                                        </button>

                                        <button wire:click="confirmarEliminacion({{ $empleado->id }})"
                                            class="btn btn-sm btn-danger">
                                            Eliminar
                                        </button>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-5">
                                    No hay empleados registrados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>

        {{-- PAGINACIÓN --}}
        <div class="card-footer border-top py-3">
            <div class="d-flex justify-content-end">
                {{ $empleados->links() }}
            </div>
        </div>


    </div>

    @if ($showDeleteModal)
        <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,.5)">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title text-danger">Eliminar empleado</h5>
                        <button type="button" class="btn-close" wire:click="$set('showDeleteModal', false)">
                        </button>
                    </div>

                    <div class="modal-body">
                        <p class="mb-0">
                            Esta acción <strong>no se puede deshacer</strong>.<br>
                            ¿Seguro que deseas eliminar este empleado?
                        </p>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-outline-secondary" wire:click="$set('showDeleteModal', false)">
                            Cancelar
                        </button>

                        <button class="btn btn-danger" wire:click="eliminarConfirmado">
                            Sí, eliminar
                        </button>
                    </div>

                </div>
            </div>
        </div>
    @endif


</div>
