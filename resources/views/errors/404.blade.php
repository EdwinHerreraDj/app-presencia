@extends('layouts.vertical', ['subtitle' => '404 - Página No Encontrada'])


@section('content')
    <div class="min-vh-100 d-flex align-items-center justify-content-center bg-light px-3">
        <div class="card border-0 shadow-lg rounded-4 w-100" style="max-width: 560px;">
            <div class="card-body text-center py-5 px-4 px-md-5">

                <div class="mb-4">
                    <h1 class="display-1 fw-bold text-danger mb-0">404</h1>
                    <div class="mx-auto my-3"
                        style="width:70px;height:4px;background:linear-gradient(90deg,#0d6efd,#6610f2);border-radius:4px;">
                    </div>
                </div>

                <h3 class="fw-semibold text-dark mb-3">
                    Página no encontrada
                </h3>

                <p class="text-muted fs-6 mb-4 px-md-4">
                    La página que buscas no existe.
                    El equipo técnico ya puede revisar el problema si persiste. 
                </p>

                <a href="{{ url('/') }}" class="btn btn-primary px-4 py-2 fw-semibold rounded-pill shadow-sm">
                    Volver al inicio
                </a>

            </div>
        </div>
    </div>
@endsection
