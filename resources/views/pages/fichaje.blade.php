@extends('layouts.vertical', ['subtitle' => 'Dashboard'])

@section('css')
@vite(['node_modules/gridjs/dist/theme/mermaid.min.css'])
@endsection

@section('content')
<div class="card">
    <div class="card-body text-center">
        <h1 class="fs-3 fw-semibold text-dark">REGISTRO DE PRESENCIA</h1>
        <p class="fs-5 text-secondary mt-2">¡Bienvenido!</p>
        <p class="fs-1 fw-bold mt-3">{{ session('user_name') }}</p>
    </div>
</div>

<div class="card text-center">
    <div id="reloj" class="fw-bold font-monospace text-primary" style="font-size: 50px;">
        00:00:00
    </div>
</div>

<livewire:empleado.registro-fichaje />
@endsection