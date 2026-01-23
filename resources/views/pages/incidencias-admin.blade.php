@extends('layouts.vertical', ['subtitle' => 'Incidencias'])

@section('css')
    @vite(['node_modules/gridjs/dist/theme/mermaid.min.css'])
@endsection


@section('content')

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
            notyf.success('{{ session('success') }}');
        </script>
    @endif

    <div class="mb-4">
        {{-- Boton para regresar --}}
        <a href="{{ route('registrosFichajes', $empresa->id) }}" class="btn btn-primary">
            <img src="/images/brands/regresar.svg" alt="">
            Volver atrás
        </a>
    </div>

    <div class="alert alert-info alert-dismissible fade show mb-4" role="alert">
        <h5 class="alert-heading">Gestión de Incidencias de Fichaje</h5>
        <p>
            En este apartado puedes revisar todas las <strong>incidencias registradas</strong> por los empleados de la
            empresa <strong>{{ $empresa->nombre }}</strong>.
        </p>
        <ul>
            <li><strong>Incidencias pendientes:</strong> solicitudes de fichaje por olvido que aún no han sido aprobadas.
            </li>
            <li><strong>Acción disponible:</strong> puedes aprobar la incidencia, lo que generará automáticamente un
                registro de fichaje con los datos proporcionados por el empleado.</li>
            <li><strong>Incidencias aprobadas:</strong> muestra el historial de incidencias que ya han sido procesadas.</li>
        </ul>
        <p class="mb-0">
            Esta herramienta permite mantener un control flexible y supervisado sobre posibles errores u omisiones en el
            registro diario de asistencia.
        </p>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>



    <div class="card shadow-sm border-0 mb-4">

        <div class="card-header bg-light border-bottom d-flex align-items-center">
            <i class="bi bi-exclamation-circle text-warning fs-4 me-2"></i>
            <h5 class="mb-0 fw-semibold text-secondary">
                Incidencias pendientes de {{ $empresa->nombre }}
            </h5>
        </div>

        <div class="card-body">

            @if ($pendientes->isEmpty())
                <div class="alert alert-success text-center shadow-sm mb-0">
                    ✔ No hay incidencias pendientes.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">

                        <thead class="table-light">
                            <tr>
                                <th>Empleado</th>
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Tipo</th>
                                <th>Motivo</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach ($pendientes as $i)
                                <tr>
                                    <td class="fw-semibold">
                                        {{ $i->empleado->nombre ?? 'Desconocido' }}
                                    </td>

                                    <td>{{ \Carbon\Carbon::parse($i->fecha)->format('d/m/Y') }}</td>

                                    <td>{{ $i->hora }}</td>

                                    <td>
                                        <span class="badge {{ $i->tipo === 'entrada' ? 'bg-info' : 'bg-secondary' }}">
                                            {{ ucfirst($i->tipo) }}
                                        </span>
                                    </td>

                                    <td class="text-muted">{{ $i->motivo }}</td>

                                    <td class="text-end">

                                        {{-- Aprobar --}}
                                        <form method="POST" action="{{ route('admin.incidencias.aprobar', $i->id) }}"
                                            class="d-inline">
                                            @csrf
                                            <button type="submit"
                                                class="btn btn-success btn-sm d-flex align-items-center gap-1">
                                                <i class="bi bi-check2-circle"></i>
                                                Aprobar
                                            </button>
                                        </form>

                                        {{-- Descartar --}}
                                        <form method="POST" action="{{ route('admin.incidencias.descartar', $i->id) }}"
                                            class="d-inline ms-1">
                                            @csrf
                                            <button type="submit"
                                                class="btn btn-outline-danger btn-sm d-flex align-items-center gap-1">
                                                <i class="bi bi-x-circle"></i>
                                                Descartar
                                            </button>
                                        </form>

                                    </td>
                                </tr>
                            @endforeach

                        </tbody>

                    </table>
                </div>
            @endif
        </div>
    </div>



    <div class="card mt-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Incidencias aprobadas de {{ $empresa->nombre }}</span>

            <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalLimpiarIncidencias">
                <i class="bi bi-trash"></i> Limpiar tabla
            </button>
        </div>






        <div class="card-body">

            @if ($aprobadas->isEmpty())
                <p class="text-muted">No hay incidencias aprobadas.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-sm table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Empleado</th>
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Tipo</th>
                                <th>Motivo</th>
                                <th>Aprobado por</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach ($aprobadas as $i)
                                <tr>
                                    <td>{{ $i->empleado->nombre }}</td>

                                    <td>{{ \Carbon\Carbon::parse($i->fecha)->format('d/m/Y') }}</td>

                                    <td>{{ $i->hora }}</td>

                                    <td>
                                        <span class="badge {{ $i->tipo === 'entrada' ? 'bg-info' : 'bg-secondary' }}">
                                            {{ ucfirst($i->tipo) }}
                                        </span>
                                    </td>

                                    <td>{{ $i->motivo }}</td>

                                    <td>Administrador</td>
                                </tr>
                            @endforeach

                        </tbody>

                    </table>
                </div>

                {{-- PAGINACIÓN --}}
                <div class="mt-3">
                    {{ $aprobadas->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>


    <!-- MODAL CONFIRMACIÓN -->
    <div class="modal fade" id="modalLimpiarIncidencias" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg border-0 rounded-3">

                <!-- HEADER -->
                <div class="modal-header bg-danger text-white border-0 rounded-top-3">
                    <h5 class="modal-title d-flex align-items-center">
                        <i class="bi bi-trash3-fill me-2 fs-4"></i>
                        Limpieza de incidencias
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <!-- BODY -->
                <div class="modal-body py-4">

                    <div class="alert alert-warning border-warning d-flex align-items-start" role="alert">
                        <i class="bi bi-exclamation-circle-fill me-2 fs-5"></i>
                        <div>
                            Estás a punto de eliminar <strong>todas las incidencias aprobadas y descartadas</strong>.
                            <br>
                            Esta acción es <strong>permanente</strong> y no podrás recuperarlas.
                        </div>
                    </div>

                    <p class="text-muted mb-0 text-center">
                        Confirma que deseas continuar con el proceso.
                    </p>
                </div>

                <!-- FOOTER -->
                <div class="modal-footer border-0 d-flex justify-content-between">

                    <button class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                        Cancelar
                    </button>

                    <!-- FORMULARIO QUE REALIZA EL BORRADO -->
                    <form action="{{ route('incidencias.limpiar', $empresa->id) }}" method="POST">
                        @csrf
                        @method('DELETE')

                        <button class="btn btn-danger px-4 fw-semibold">
                            <i class="bi bi-check2-circle me-1"></i> Sí, eliminar todo
                        </button>
                    </form>

                </div>

            </div>
        </div>
    </div>







@endsection

@section('scripts')
    @vite(['resources/js/pages/fichaje.js'])
@endsection
