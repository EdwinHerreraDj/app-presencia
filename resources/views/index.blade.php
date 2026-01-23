@extends('layouts.vertical', ['subtitle' => 'Dashboard'])

@section('css')
    @vite(['node_modules/gridjs/dist/theme/mermaid.min.css'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf/notyf.min.css">
    <script src="https://cdn.jsdelivr.net/npm/notyf/notyf.min.js"></script>
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

    @include('layouts.partials/page-title', ['title' => 'Inicio', 'subtitle' => 'Panel'])

    <div class="row">
        <!-- Card 1 -->
        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <p class="text-muted mb-0 text-truncate">Total empleados</p>
                            <h3 class="text-dark mt-2 mb-0">{{ $totalEmpleados }}</h3>
                        </div>


                        <div class="col-6">
                            <div class="ms-auto avatar-md bg-soft-primary rounded">
                                <iconify-icon icon="solar:users-group-two-rounded-broken"
                                    class="fs-32 avatar-title text-primary"></iconify-icon>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <p class="text-muted mb-0 text-truncate">Total Empresas</p>
                            <h3 class="text-dark mt-2 mb-0">{{ $totalEmpresas }}</h3>
                        </div>

                        <div class="col-6">
                            <div class="ms-auto avatar-md bg-soft-primary rounded">
                                <iconify-icon icon="solar:globus-outline"
                                    class="fs-32 avatar-title text-primary"></iconify-icon>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#agregarEmpresa">
        <img src="images/brands/agregar.svg" alt="Icon de agregar">
        Agregar punto de fichaje
    </button>


    {{-- Modal de creación --}}
    <div class="modal fade" id="agregarEmpresa" tabindex="-1" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">

                <form action="{{ route('empresas.store') }}" method="POST" id="empresaForm">
                    @csrf

                    {{-- HEADER --}}
                    <div class="modal-header border-bottom-0 pb-0">
                        <div>
                            <h5 class="modal-title fw-semibold" id="exampleModalCenterTitle">
                                Agregar punto de fichaje
                            </h5>
                            <p class="text-muted small mb-0">
                                Define la ubicación y configuración del nuevo punto de fichaje
                            </p>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body pt-4">

                        {{-- BLOQUE DATOS GENERALES --}}
                        <div class="card mb-4">
                            <div class="card-body">
                                <h6 class="fw-semibold mb-3">Datos generales</h6>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Nombre</label>
                                        <input type="text" class="form-control @error('nombre') is-invalid @enderror"
                                            id="nombre" name="nombre" value="{{ old('nombre') }}"
                                            placeholder="Nombre de punto de fichaje" required>
                                        @error('nombre')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Dirección</label>
                                        <input type="text" class="form-control @error('direccion') is-invalid @enderror"
                                            id="direccion" name="direccion" value="{{ old('direccion') }}"
                                            placeholder="Dirección del punto de fichaje" required>
                                        @error('direccion')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">Descripción</label>
                                        <input type="text"
                                            class="form-control @error('descripcion') is-invalid @enderror" id="descripcion"
                                            name="descripcion" value="{{ old('descripcion') }}"
                                            placeholder="Descripción del punto de fichaje">
                                        @error('descripcion')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- BLOQUE MAPA --}}
                        <div class="card mb-4">
                            <div class="card-body">
                                <h6 class="fw-semibold mb-3">Ubicación del punto de fichaje</h6>

                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" id="crearDireccionBusqueda"
                                        placeholder="Buscar dirección (ej: Gran Vía, Madrid)">
                                    <button type="button" class="btn btn-outline-primary" id="crearBuscarDireccion">
                                        Buscar
                                    </button>
                                </div>

                                <div id="crearMapa" class="border" style="height:320px;border-radius:10px">
                                </div>

                                <div class="row g-3 mt-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Latitud</label>
                                        <input type="number" class="form-control @error('latitud') is-invalid @enderror"
                                            id="latitud" name="latitud" value="{{ old('latitud') }}" min="-90"
                                            max="90" step="0.000001" required>
                                        @error('latitud')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Longitud</label>
                                        <input type="number" class="form-control @error('longitud') is-invalid @enderror"
                                            id="longitud" name="longitud" value="{{ old('longitud') }}" min="-180"
                                            max="180" step="0.000001" required>
                                        @error('longitud')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- BLOQUE CONFIGURACIÓN --}}
                        <div class="card">
                            <div class="card-body">
                                <h6 class="fw-semibold mb-3">Configuración del fichaje</h6>

                                <div class="row align-items-center g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">
                                            Radio de fichaje (metros)
                                        </label>
                                        <input type="number" class="form-control @error('radio') is-invalid @enderror"
                                            id="radio" name="radio" value="{{ old('radio') }}" min="0"
                                            step="1" placeholder="Ejemplo: 100" required>
                                        @error('radio')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-check form-switch mt-4">
                                            <input class="form-check-input" type="checkbox"
                                                id="modalEmpresaFichajeActivo" name="fichaje_activo" value="1">
                                            <label class="form-check-label fw-medium">
                                                Punto de fichaje activo
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- FOOTER --}}
                    <div class="modal-footer border-top-0 pt-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                            Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary px-4">
                            Agregar punto de fichaje
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>



    <div class="card mt-3">
        <div class="card-header">
            <h5 class="card-title">Tabla de puntos de fichaje</h5>
            <p class="card-subtitle">En este modulo se pueden visualizar los puntos de fichaje registrados en el sistema.
            </p>
        </div>
        <div class="card-body">
            <div>
                <div id="table-empresas"></div>
            </div>
        </div>
    </div>


    {{-- Modal de ediccion --}}
    <div class="modal fade" id="editEmpresaModal" tabindex="-1" aria-labelledby="editEmpresaModalLabel"
        aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">

                <form id="editEmpresaForm" method="POST">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="empresa_id" id="modalEmpresaId">

                    {{-- HEADER --}}
                    <div class="modal-header border-bottom-0 pb-0">
                        <div>
                            <h5 class="modal-title fw-semibold" id="editEmpresaModalLabel">
                                Editar punto de fichaje
                            </h5>
                            <p class="text-muted small mb-0">
                                Configura la ubicación, radio y estado del punto de fichaje
                            </p>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body pt-4">

                        {{-- BLOQUE DATOS BÁSICOS --}}
                        <div class="card mb-4">
                            <div class="card-body">
                                <h6 class="fw-semibold mb-3">Datos generales</h6>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Nombre</label>
                                        <input type="text" class="form-control @error('nombre') is-invalid @enderror"
                                            id="modalEmpresaNombre" name="nombre" required>
                                        @error('nombre')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Dirección</label>
                                        <input type="text"
                                            class="form-control @error('direccion') is-invalid @enderror"
                                            id="modalEmpresaDireccion" name="direccion" required>
                                        @error('direccion')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">Descripción</label>
                                        <input type="text"
                                            class="form-control @error('descripcion') is-invalid @enderror"
                                            id="modalEmpresaDescripcion" name="descripcion">
                                        @error('descripcion')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- BLOQUE MAPA --}}
                        <div class="card mb-4">
                            <div class="card-body">
                                <h6 class="fw-semibold mb-3">Ubicación del punto de fichaje</h6>

                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" id="editarDireccionBusqueda"
                                        placeholder="Buscar dirección (ej: Gran Vía, Madrid)">
                                    <button type="button" class="btn btn-outline-primary" id="editarBuscarDireccion">
                                        Buscar
                                    </button>
                                </div>

                                <div id="editarMapa" style="height:320px;border-radius:10px" class="border">
                                </div>

                                <div class="row g-3 mt-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Latitud</label>
                                        <input type="number" class="form-control @error('latitud') is-invalid @enderror"
                                            id="modalEmpresaLatitud" name="latitud" min="-90" max="90"
                                            step="0.000001" required>
                                        @error('latitud')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Longitud</label>
                                        <input type="number"
                                            class="form-control @error('longitud') is-invalid @enderror"
                                            id="modalEmpresaLongitud" name="longitud" min="-180" max="180"
                                            step="0.000001" required>
                                        @error('longitud')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- BLOQUE CONFIGURACIÓN --}}
                        <div class="card">
                            <div class="card-body">
                                <h6 class="fw-semibold mb-3">Configuración del fichaje</h6>

                                <div class="row align-items-center g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">
                                            Radio de fichaje (metros)
                                        </label>
                                        <input type="number" class="form-control @error('radio') is-invalid @enderror"
                                            id="modalEmpresaRadio" name="radio" min="0" step="1"
                                            required>
                                        @error('radio')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-check form-switch mt-4">
                                            <input class="form-check-input" type="checkbox" id="editEmpresaFichajeActivo"
                                                name="fichaje_activo" value="1">
                                            <label class="form-check-label fw-medium">
                                                Punto de fichaje activo
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- FOOTER --}}
                    <div class="modal-footer border-top-0 pt-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                            Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary px-4">
                            Guardar cambios
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>



    {{-- Modal para eliminar --}}
    <div class="modal fade" id="modalEliminarEmpresa" tabindex="-1" aria-labelledby="modalEliminarEmpresaLabel"
        aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">

                {{-- HEADER --}}
                <div class="modal-header border-bottom-0 pb-0">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-danger bg-opacity-10 rounded-circle p-2">
                            <img src="/images/brands/warning.svg" alt="Advertencia" width="28" height="28">
                        </div>

                        <div>
                            <h5 class="modal-title fw-semibold mb-0" id="modalEliminarEmpresaLabel">
                                Eliminar punto de fichaje
                            </h5>
                            <p class="text-muted small mb-0">
                                Esta acción no se puede deshacer
                            </p>
                        </div>
                    </div>

                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                {{-- BODY --}}
                <div class="modal-body pt-4">
                    <div class="alert alert-warning d-flex align-items-start gap-2 mb-0">
                        <i class="bi bi-exclamation-triangle-fill text-warning mt-1"></i>
                        <div>
                            <p class="mb-1 fw-medium">
                                ¿Estás seguro de que deseas eliminar este punto de fichaje?
                            </p>
                            <p class="mb-0 text-muted small">
                                Se perderá toda la configuración asociada a este punto.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- FOOTER --}}
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        Cancelar
                    </button>

                    <form id="deleteEmpresaForm" method="POST">
                        @csrf
                        @method('DELETE')

                        <input type="hidden" name="empresa_id" id="deleteEmpresaId">

                        <button type="submit" class="btn btn-danger px-4">
                            Eliminar punto de fichaje
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>

    @if ($errors->any())
        <script>
            const modal = new bootstrap.Modal(document.getElementById('editEmpresaModal'));
            modal.show();
        </script>
    @endif
@endsection

@section('scripts')
    <script>
        window.empresasData = @json($empresas);
    </script>
    @vite(['resources/js/pages/dashboard.js'])
@endsection
