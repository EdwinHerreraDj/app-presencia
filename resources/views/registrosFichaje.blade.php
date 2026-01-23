@extends('layouts.vertical', ['subtitle' => 'Dashboard'])

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

@if (!empty($alertas))
<div class="alert alert-warning d-flex justify-content-between align-items-center">
    <div>
        <h5 class="mb-1">Alertas de asistencia detectadas</h5>
        <p class="mb-0">Existen desajustes en fichajes de días anteriores.</p>
    </div>
    <a href="{{ route('alertas.fichajes', $empresaId) }}" class="btn btn-dark">
        Ver detalles
    </a>
</div>
@endif





<div class="d-flex flex-wrap gap-2 mb-4">

    <!-- Volver atrás -->
    <a href="{{ route('empresas') }}" class="btn btn-primary d-flex align-items-center">
        <i class="bi bi-arrow-left-circle fs-5 me-2"></i>
        Volver atrás
    </a>

    <!-- Exportar tabla -->
    <button type="button" class="btn btn-success d-flex align-items-center gap-2 shadow-sm px-3" data-bs-toggle="modal"
        data-bs-target="#exportModal">
        <i class="bi bi-download fs-5"></i>
        Exportar tabla
    </button>

    <!-- Resumen de horas -->
    <a href="{{ route('resumen.horas', $empresaId) }}"
        class="btn btn-info d-flex align-items-center gap-2 shadow-sm px-3">
        <i class="bi bi-clock-history fs-5"></i>
        Resumen de horas
    </a>

    <!-- Registrar fichaje anterior -->
    <a href="{{ route('fichaje.manual', $empresaId) }}"
        class="btn btn-secondary d-flex align-items-center gap-2 shadow-sm px-3">
        <i class="bi bi-calendar-check fs-5"></i>
        Registrar fichaje anterior
    </a>

    <!-- Incidencias -->
    <a href="{{ route('admin.incidencias.empresa', $empresaId) }}"
        class="btn btn-primary position-relative d-flex align-items-center gap-2 shadow-sm px-3">

        <i class="bi bi-exclamation-circle fs-5"></i>
        Incidencias

        @if ($incidenciasPendientes > 0)
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
            {{ $incidenciasPendientes }}
        </span>
        @endif
    </a>

</div>



<div class="card mt-3">

    <div class="card-header">

        <h5 class="card-title">Tabla de registros</h5>
        <p class="card-subtitle">En este modulo se pueden visualizar los registros de los empleados de entradas y
            salidas.
        </p>
    </div>
    <div class="card-body">
        <div>
            <livewire:fichajes-table :empresa-id="$empresaId" />
        </div>
    </div>
</div>



{{-- Modal de edición --}}

<div class="modal fade" id="modalEditarFichaje" tabindex="-1" aria-labelledby="editarFichajeLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('fichaje.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Editar Fichaje</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="editar-id">
                    <div class="mb-3">
                        <label for="editar-name" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="editar-name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="editar-fecha" class="form-label">Fecha</label>
                        <input type="date" class="form-control" id="editar-fecha" name="fecha" required>
                    </div>
                    <div class="mb-3">
                        <label for="editar-hora" class="form-label">Hora</label>
                        <input type="time" class="form-control" id="editar-hora" name="hora" required>
                    </div>
                    <div class="mb-3">
                        <label for="editar-tipo" class="form-label">Tipo</label>
                        <select class="form-select" id="editar-tipo" name="tipo" required>
                            <option value="entrada">Entrada</option>
                            <option value="salida">Salida</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>


{{-- Modal de eliminación de registro --}}
<div class="modal fade" id="modalEliminarFichaje" tabindex="-1" aria-labelledby="eliminarFichajeLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="formEliminarFichaje" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title" id="eliminarFichajeLabel"><img style="margin-right: 10px;"
                            src="/images/brands/warning.svg" alt="logo de advertencia" width="30" height="30"> Confirmar
                        Eliminación </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro de que deseas eliminar este fichaje?</p>
                    <input type="hidden" name="id" id="eliminar-id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Modal Exportar-->
<div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('export.fichajes', ['empresaId' => $empresaId]) }}" method="GET">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exportModalLabel">Exportar fichajes por rango de fechas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="fecha_inicio" class="form-label">Fecha de inicio</label>
                        <input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio">
                    </div>
                    <div class="mb-3">
                        <label for="fecha_fin" class="form-label">Fecha de fin</label>
                        <input type="date" class="form-control" name="fecha_fin" id="fecha_fin">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Exportar</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    window.addEventListener('notyf', event => {
        const notyf = new Notyf({
            duration: 4000,
            dismissible: true,
            position: { x: 'right', y: 'top' }
        });

        if (event.detail.type === 'success') {
            notyf.success(event.detail.message);
        } else {
            notyf.error(event.detail.message);
        }
    });



    let mapa = null;
    let marker = null;

    function iniciarListenerMapa() {

        Livewire.on('verMapa', (data) => {

            const { lat, lng, nombre, fecha } = data;

            const modal = new bootstrap.Modal(document.getElementById('modalMapa'));
            modal.show();

            setTimeout(() => {

                const errorDiv = document.getElementById('mapa-error');
                const mapaDiv = document.getElementById('mapaFichaje');

                // Caso: NO hay coordenadas
                if (lat === null || lng === null || isNaN(lat) || isNaN(lng)) {

                    mapaDiv.style.display = "none";
                    errorDiv.classList.remove('d-none');
                    return;
                }

                // Caso: SÍ hay coordenadas
                errorDiv.classList.add('d-none');
                mapaDiv.style.display = "block";

                // Reiniciar mapa por si existía uno anterior
                if (mapa) {
                    mapa.remove();
                    mapa = null;
                }

                mapa = L.map('mapaFichaje').setView([lat, lng], 17);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19
                }).addTo(mapa);

                marker = L.marker([lat, lng]).addTo(mapa)
                    .bindPopup(`<b>${nombre}</b><br>${fecha}`)
                    .openPopup();

            }, 200);
        });
    }

    document.addEventListener('livewire:navigated', iniciarListenerMapa);
    iniciarListenerMapa();
</script>


@vite(['resources/js/pages/fichajes-registros.js'])
@endsection