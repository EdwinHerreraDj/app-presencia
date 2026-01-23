@extends('layouts.vertical', ['subtitle' => 'Incidencias'])

@section('css')
@vite(['node_modules/gridjs/dist/theme/mermaid.min.css'])
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf/notyf.min.css">
<script src="https://cdn.jsdelivr.net/npm/notyf/notyf.min.js"></script>
@endsection


@section('content')
<div class="card">
    <div class="card-body text-center">
        <h1 class="fs-3 fw-semibold text-dark">REGISTRO DE INCIDENCIAS</h1>
        <p class="fs-5 text-secondary mt-2">¡Bienvenido!</p>
        <p class="fs-1 fw-bold mt-3">{{ session('user_name') }}</p>

        @if (session('success'))
        <div
            class="alert alert-success alert-dismissible fade show d-flex align-items-center justify-content-center mt-3">
            {{ session('success') }}
        </div>
        @elseif (session('error'))
        <div
            class="alert alert-danger alert-dismissible fade show d-flex align-items-center justify-content-center mt-3">
            {{ session('error') }}
        </div>
        @endif


        <form action="{{route('incidencias.store')}}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="empresa_id" class="form-label">Selecciona la Empresa</label>
                <select class="form-control" id="empresa_id" name="empresa_id" required>
                    <option value="" disabled selected>-- Selecciona una empresa --</option>
                    @foreach ($empresas as $empresa)
                    <option value="{{ $empresa->id }}">{{ $empresa->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="fecha">Fecha del olvido</label>
                <input type="date" name="fecha" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="hora">Hora estimada</label>
                <input type="time" name="hora" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="tipo">Tipo</label>
                <select name="tipo" class="form-select" required>
                    <option value="entrada">Entrada</option>
                    <option value="salida">Salida</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="motivo">Motivo</label>
                <textarea name="motivo" class="form-control" rows="3" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Enviar incidencia</button>
        </form>
    </div>
</div>



@endsection

@section('scripts')
@vite(['resources/js/pages/fichaje.js'])
@endsection