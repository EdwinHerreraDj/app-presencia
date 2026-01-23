@extends('layouts.vertical', ['subtitle' => 'Incidencias'])

@section('css')
@vite(['node_modules/gridjs/dist/theme/mermaid.min.css'])
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf/notyf.min.css">
<script src="https://cdn.jsdelivr.net/npm/notyf/notyf.min.js"></script>
@endsection


@section('content')
<div class="card">
    <div class="card-body text-center">
        <h1 class="fs-3 fw-semibold text-dark">MI ACTIVIDAD</h1>

        <div class="container">
            <h3 class="mb-4">Actividad de {{ $empleado->nombre }}</h3>

            <div class="card mb-4 border-0 shadow-sm" style="background:#1f1f1f;">
                <div class="card-header border-0" style="background:#252525; color:#e5e5e5;">
                    <i class="bi bi-clock-history me-2 text-muted"></i>
                    Incidencias pendientes
                </div>

                <div class="card-body">

                    @php
                    $pendientes = $incidencias->where('estado', 'pendiente');
                    @endphp

                    @if ($pendientes->isEmpty())
                    <p class="text-muted mb-0">
                        <i class="bi bi-info-circle me-1"></i>
                        No hay incidencias pendientes.
                    </p>
                    @else

                    <div class="row g-3">

                        @foreach ($pendientes as $i)
                        <div class="col-12">
                            <div class="p-3 rounded-3" style="background:#2b2b2b; border:1px solid #3a3a3a;">

                                <!-- TITULO -->
                                <h6 class="fw-semibold mb-1" style="color:#e5e5e5;">
                                    {{ ucfirst($i->tipo) }}
                                    <span class="ms-2 small text-muted">
                                        {{ \Carbon\Carbon::parse($i->fecha)->format('d/m/Y') }}
                                        •
                                        {{ $i->hora }}
                                    </span>
                                </h6>

                                <!-- MOTIVO -->
                                <p class="mb-2 small" style="color:#cfcfcf;">
                                    <strong style="color:#fff;">Motivo:</strong>
                                    {{ $i->motivo }}
                                </p>

                                <!-- BADGE -->
                                <span class="badge rounded-pill"
                                    style="background:#3d3d3d; color:#c1c1c1; padding:.45rem .8rem;">
                                    Pendiente
                                </span>

                            </div>
                        </div>
                        @endforeach

                    </div>

                    @endif
                </div>
            </div>





            <livewire:mi-actividad />


        </div>

    </div>
</div>



@endsection