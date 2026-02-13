@extends('layouts.vertical', ['subtitle' => '403 - Acceso Denegado'])


@section('content')
    <div class="min-vh-100 d-flex align-items-center justify-content-center bg-light px-3">
        <div class="card border-0 shadow-lg rounded-4 w-100" style="max-width: 520px;">
            <div class="card-body text-center p-5">

                <div class="mb-4">
                    <h1 class="display-1 fw-bold text-danger mb-0">403</h1>
                    <div class="mx-auto my-3"
                        style="width:60px;height:4px;background:linear-gradient(90deg,#0d6efd,#6610f2);border-radius:4px;">
                    </div>
                </div>

                <h2 class="fs-3 fw-semibold text-dark mb-3">Acceso Denegado</h2>

                <p class="fs-6 text-muted mb-4 px-md-4">
                    No tienes permiso para acceder a esta página.
                </p>

                @if (auth()->check())
                    <a href="{{ route('empresas') }}" class="btn btn-primary px-4 py-2 fw-semibold rounded-pill shadow-sm">
                        Ir al panel
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary px-4 py-2 fw-semibold rounded-pill shadow-sm">
                        Ir al login
                    </a>
                @endif

            </div>
        </div>
    </div>
@endsection
