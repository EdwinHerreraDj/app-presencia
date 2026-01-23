@extends('layouts.vertical', ['subtitle' => 'Alertas de Fichajes'])

@section('css')
@vite(['node_modules/gridjs/dist/theme/mermaid.min.css'])
@endsection


@section('content')
{{-- Mensaje de éxito o de error --}}
@if (session('success'))
<script>
    const notyf = new Notyf({
                duration: 4000,
                dismissible: true,
                position: {
                    x: 'right',
                    y: 'top',
                },
            });

            // Mostrar mensaje de éxito
            notyf.success('{{ session('success') }}');
</script>
@elseif (session('error'))
<script>
    const notyf = new Notyf({
                duration: 4000,
                dismissible: true,
                position: {
                    x: 'right',
                    y: 'top',
                },
            });
            notyf.error('{{ session('error') }}');
</script>
@endif

@include('layouts.partials/page-title', ['title' => 'Alertas de Fichajes', 'subtitle' => 'Gestión de alertas de
fichajes'])

<div class="mb-3">
    <a href="{{ route('registrosFichajes', $empresa->id) }}" class="btn btn-primary">
        <img src="/images/brands/regresar.svg" alt="">
        Volver atrás
    </a>
</div>


<h3 class="mb-4 fw-bold text-primary">
    Alertas de Fichajes – {{ $empresa->nombre }}
</h3>

@if (empty($alertas))
<div class="alert alert-success shadow-sm">
    No hay desajustes de fichajes en días anteriores.
</div>
@else

<div class="card shadow-sm border-0 p-8">
    <div class="card-header bg-light border-bottom">
        <h5 class="mb-0 fw-semibold text-secondary">
            <i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>
            Desajustes detectados
        </h5>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">
                    <tr>
                        <th>Empleado</th>
                        <th>Fecha</th>
                        <th>Entradas</th>
                        <th>Salidas</th>
                        <th>Detalle</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($alertas as $alerta)
                    <tr>
                        <td class="fw-semibold">{{ $alerta['nombre'] }}</td>
                        <td>{{ $alerta['fecha'] }}</td>
                        <td>{{ $alerta['entradas'] }}</td>
                        <td>{{ $alerta['salidas'] }}</td>
                        <td class="text-muted">{{ $alerta['mensaje'] }}</td>
                        <td class="text-end">
                            <button class="btn btn-primary btn-sm px-3" data-bs-toggle="modal"
                                data-bs-target="#modalEditarFichaje" data-empleado="{{ $alerta['nombre'] }}"
                                data-empleado-id="{{ $alerta['empleado_id'] }}" data-fecha="{{ $alerta['fecha'] }}">
                                <i class="bi bi-pencil-square"></i> Corregir
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    </div>

</div>

@endif


<div class="modal fade" id="modalEditarFichaje" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <form method="POST" action="{{ route('fichaje.manual.store') }}">
            @csrf

            <div class="modal-content shadow-lg border-0">

                <!-- Header corporativo -->
                <div class="modal-header bg-light border-bottom border-primary-subtle">
                    <h5 class="modal-title fw-bold text-primary d-flex align-items-center">
                        <i class="bi bi-clock-history me-2"></i>Registrar Fichaje Manual
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <!-- Body -->
                <div class="modal-body px-4 py-3">

                    <input type="hidden" name="empleado_id" id="empleadoIdManual">
                    <input type="hidden" name="empresa_id" value="{{ $empresa->id }}">

                    <div class="row g-4">

                        <!-- Empleado -->
                        <div class="col-md-12">
                            <label class="form-label fw-semibold text-secondary">Empleado</label>
                            <input type="text" id="empleadoNombreManual" class="form-control shadow-sm" readonly>
                        </div>

                        <!-- Fecha -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary">Fecha</label>
                            <input type="text" id="fechaManual" name="fecha" class="form-control shadow-sm" readonly>
                        </div>

                        <!-- Tipo fichaje -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary">Tipo de fichaje</label>
                            <select name="tipo" class="form-select shadow-sm" required>
                                <option value="entrada">Entrada</option>
                                <option value="salida">Salida</option>
                            </select>
                        </div>

                        <!-- Hora -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary">Hora</label>
                            <input type="time" name="hora" class="form-control shadow-sm" required>
                        </div>

                    </div>
                </div>

                <!-- Footer -->
                <div class="modal-footer border-0 px-4 pb-3">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal"
                        style="padding: .55rem 1.4rem; font-weight: 500;">
                        Cancelar
                    </button>

                    <button class="btn btn-primary px-4" type="submit"
                        style="padding: .55rem 1.4rem; font-weight: 500;">
                        <i class="bi bi-check2-circle me-1"></i>Guardar
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>



@endsection

@section('scripts')
<script>
    document.getElementById('modalEditarFichaje')
    .addEventListener('show.bs.modal', function (event) {

        const button = event.relatedTarget;

        document.getElementById('empleadoIdManual').value = button.getAttribute('data-empleado-id');
        document.getElementById('empleadoNombreManual').value = button.getAttribute('data-empleado');
        document.getElementById('fechaManual').value = button.getAttribute('data-fecha');
    });
</script>

@endsection