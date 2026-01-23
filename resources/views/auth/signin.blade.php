@extends('layouts.base', ['subtitle' => 'Login'])

@section('body-attribuet')
    class="authentication-bg"
@endsection

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@endsection

@section('content')
    <div class="account-pages py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-5">
                    <div class="card border-0 shadow-lg">
                        <div class="card-body">
                            <div class="text-center">
                                <div class="mx-auto mb-4 text-center auth-logo">
                                    <a href="javascript:void(0)" class="logo-dark">
                                        <img src="/images/logo-dark.png" height="32" alt="logo dark">
                                    </a>

                                    <a href="javascript:void(0)" class="logo-light">
                                        <img src="/images/logo-light.png" height="90" alt="logo light">
                                    </a>
                                </div>
                                <hr>
                                <h4 class="fw-bold text-dark mb-2">Bienvenido!</h3>
                                    <p class="text-muted">Inicia sesión para continuar</p>
                            </div>
                            <form method="POST" action="{{ route('login') }}" class="mt-4" id="loginForm">
                                @csrf

                                @if ($errors->any())
                                    @foreach ($errors->all() as $error)
                                        <div class="alert alert-danger alert-dismissible fade show text-center"
                                            role="alert">
                                            {{ $error }}
                                        </div>
                                    @endforeach
                                @endif

                                <div class="mb-3">
                                    <label for="email" class="form-label">Correo electrónico</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        placeholder="Ingrese su correo electrónico" required autofocus>
                                </div>

                                <div class="mb-3 position-relative">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <label for="password" class="form-label">Contraseña</label>
                                        <a href="https://alminares.es/es/contacto"
                                            class="small text-muted text-decoration-none">¿Olvidó su contraseña?</a>
                                    </div>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="password" name="password"
                                            placeholder="********" required>
                                        <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()">
                                            <i class="bi bi-eye" id="toggle-icon"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="form-check mb-4">
                                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                    <label class="form-check-label" for="remember">Recordar sesión</label>
                                </div>

                                <div class="d-grid">
                                    <button class="btn btn-dark btn-lg" type="submit">Iniciar sesión</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <p class="text-center mt-4 text-white text-opacity-50">Area de soporte -
                        <a href="https://alminares.es/es/contacto" class="text-decoration-none text-white fw-bold">Alminares
                            Soluciones Avanzadas</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('scripts')
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById("password");
            const icon = document.getElementById("toggle-icon");
            const isPassword = passwordInput.type === "password";

            passwordInput.type = isPassword ? "text" : "password";
            icon.classList.toggle("bi-eye");
            icon.classList.toggle("bi-eye-slash");
        }

        document.getElementById('loginForm').addEventListener('submit', async function() {
            try {
                await fetch('/sanctum/csrf-cookie', {
                    credentials: 'same-origin'
                });
            } catch (e) {}
        });
    </script>
@endsection
