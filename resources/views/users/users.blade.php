@extends('layouts.vertical', ['subtitle' => 'Usuarios'])

@section('css')
    @vite(['node_modules/gridjs/dist/theme/mermaid.min.css'])
@endsection

@section('content')
    @include('layouts.partials/page-title', ['title' => 'Dashboard', 'subtitle' => 'Usuarios'])


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

    <a href="{{ route('empresas') }}" class="btn btn-primary">
        <img src="/images/brands/regresar.svg">
        Voler atras
    </a>

    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#agregarUsuario">
        <img src="images/brands/agregar.svg" alt="Icon de agregar">
        Agregar Usuario
    </button>



    {{-- Modal de creación --}}
    <div class="modal fade" id="agregarUsuario" tabindex="-1" aria-labelledby="agregarUsuarioLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content shadow-lg border-0">

                <!-- Header corporativo -->
                <div class="modal-header bg-light border-bottom border-primary-subtle">
                    <h5 class="modal-title fw-bold text-primary d-flex align-items-center" id="agregarUsuarioLabel">
                        <i class="bi bi-person-plus-fill me-2"></i>Agregar Usuario
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>

                <!-- Body -->
                <div class="modal-body px-4 py-3">
                    <form action="{{ route('users.store') }}" method="POST" id="userForm">
                        @csrf

                        <div class="row g-4">

                            <!-- Nombre -->
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-semibold text-secondary">Nombre</label>
                                <input type="text" class="form-control shadow-sm" id="name" name="name"
                                    value="{{ old('name') }}" placeholder="Nombre" required>
                                @error('name')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="col-md-6">
                                <label for="email" class="form-label fw-semibold text-secondary">Correo
                                    Electrónico</label>
                                <input type="email" class="form-control shadow-sm" id="email" name="email"
                                    value="{{ old('email') }}" placeholder="correo@ejemplo.com" required>
                                <div class="text-danger small" id="emailError"></div>
                                @error('email')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Contraseña -->
                            <div class="col-md-6">
                                <label for="password" class="form-label fw-semibold text-secondary">Contraseña</label>
                                <input type="password" class="form-control shadow-sm" id="password" name="password"
                                    placeholder="**********" required>
                                @error('password')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Confirmación -->
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label fw-semibold text-secondary">
                                    Confirmar Contraseña
                                </label>
                                <input type="password" class="form-control shadow-sm" id="password_confirmation"
                                    name="password_confirmation" placeholder="**********" required>
                                <div class="text-danger small" id="passwordConfirmationError"></div>
                            </div>

                            <!-- Rol -->
                            <div class="col-md-6">
                                <label for="rol" class="form-label fw-semibold text-secondary">Rol</label>
                                <select class="form-select shadow-sm" id="rol" name="rol" required>
                                    <option value="">Seleccionar rol</option>
                                    <option value="admin" {{ old('rol') == 'admin' ? 'selected' : '' }}>Administrador
                                    </option>
                                    <option value="encargado" {{ old('rol') == 'encargado' ? 'selected' : '' }}>Encargado
                                    </option>
                                    <option value="user" {{ old('rol') == 'user' ? 'selected' : '' }}>Usuario</option>
                                </select>
                                @error('rol')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        <!-- Footer -->
                        <div class="modal-footer border-0 px-0 mt-4">
                            <button type="button" class="btn btn-light border" data-bs-dismiss="modal"
                                style="padding: 0.55rem 1.4rem; font-weight: 500;">
                                Cerrar
                            </button>
                            <button type="submit" class="btn btn-primary px-4"
                                style="padding: 0.55rem 1.4rem; font-weight: 500;">
                                <i class="bi bi-check2-circle me-1"></i>Crear Usuario
                            </button>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>




    <div class="card mt-3">
        <div class="card-header">
            <h5 class="card-title">Tabla de Usuarios</h5>
            <p class="card-subtitle">En este modulo podras ver, editar y eliminar los usuarios registrados en el sistema.
            </p>
        </div>
        <div class="card-body">
            <div>
                <div id="table-gridjs"></div>
            </div>
        </div>
    </div>

    <!-- Modal de Edición -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content shadow-lg border-0">

                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Hidden ID -->
                    <input type="hidden" name="user_id" id="modalUserId">

                    <!-- Header -->
                    <div class="modal-header bg-light border-bottom border-primary-subtle">
                        <h5 class="modal-title fw-bold text-primary d-flex align-items-center" id="editModalLabel">
                            <i class="bi bi-pencil-square me-2"></i>Editar Usuario
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>

                    <!-- Body -->
                    <div class="modal-body px-4 py-3">
                        <div class="row g-4">

                            <!-- Nombre -->
                            <div class="col-md-6">
                                <label for="modalUserName" class="form-label fw-semibold text-secondary">
                                    Nombre
                                </label>
                                <input type="text" class="form-control shadow-sm" id="modalUserName" name="name"
                                    required>
                            </div>

                            <!-- Email -->
                            <div class="col-md-6">
                                <label for="modalUserEmail" class="form-label fw-semibold text-secondary">
                                    Email <span class="text-muted">(no se puede actualizar)</span>
                                </label>
                                <input type="email" class="form-control shadow-sm" id="modalUserEmail" name="email"
                                    disabled>
                            </div>

                            <!-- Nueva Contraseña -->
                            <div class="col-md-6">
                                <label for="password_edit" class="form-label fw-semibold text-secondary">
                                    Actualizar Contraseña (opcional)
                                </label>
                                <input type="password" class="form-control shadow-sm" id="password_edit" name="password"
                                    placeholder="**********">
                                @error('password')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Confirmación -->
                            <div class="col-md-6">
                                <label for="password_confirmation_edit" class="form-label fw-semibold text-secondary">
                                    Confirmar Contraseña
                                </label>
                                <input type="password" class="form-control shadow-sm" id="password_confirmation_edit"
                                    name="password_confirmation" placeholder="**********">
                                <div class="text-danger small" id="passwordConfirmationErrorEdit"></div>
                            </div>

                            <!-- Rol -->
                            <div class="col-md-6">
                                <label for="modalUserRol" class="form-label fw-semibold text-secondary">Rol</label>
                                <select class="form-select shadow-sm" id="modalUserRol" name="rol" required>
                                    <option value="admin">Administrador</option>
                                    <option value="encargado">Encargado</option>
                                    <option value="user">Usuario</option>
                                </select>
                            </div>

                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="modal-footer border-0 px-4 pb-3 mt-2">
                        <button type="button" class="btn btn-light border"
                            style="padding: .55rem 1.4rem; font-weight: 500;" data-bs-dismiss="modal">
                            Cerrar
                        </button>
                        <button type="submit" class="btn btn-primary px-4"
                            style="padding: .55rem 1.4rem; font-weight: 500;">
                            <i class="bi bi-save me-1"></i>Guardar Cambios
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>



    {{-- Modal para eliminar --}}
    <div class="modal fade" id="modalEliminar" tabindex="-1" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <img style="margin-right: 10px;" src="/images/brands/warning.svg" alt="logo de advertencia"
                        width="30" height="30">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Eliminar Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">

                    <p>¿Estás seguro de que deseas eliminar este usuario?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>

                    <form class="formularioEliminar" method="POST">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="user_id" class="user_id">
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        window.usersData = @json($users);
    </script>
    @vite(['resources/js/pages/table-users.js'])
@endsection
