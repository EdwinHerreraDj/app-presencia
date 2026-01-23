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
<div class="alert alert-warning">
    <h5>Alertas de asistencia de días anteriores</h5>
    <ul>
        @foreach ($alertas as $alerta)
        <li>
            <strong>{{ $alerta['nombre'] }}</strong>: {{ $alerta['mensaje'] }}
        </li>
        @endforeach
    </ul>
</div>
@endif




<div class="d-flex align-items-center gap-1 mb-3">
    {{-- Botón para regresar --}}
    <a href="{{route('registrosFichajes', $empresa->id)}}" class="btn btn-primary d-flex align-items-center">
        <img src="/images/brands/regresar.svg" alt="" class="me-2">
        Volver atrás
    </a>
    <!-- Botón para exportar -->
    <form action="{{ url('/empresa/' . $empresa->id . '/exportar-horas') }}" method="GET" class="m-0 p-0">
        @foreach (request()->all() as $key => $value)
        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endforeach
        <button class="btn btn-success d-flex align-items-center">
            <img src="/images/brands/descargar.svg" alt="descarga" class="me-2"> Exportar a Excel
        </button>
    </form>
</div>

<div class="alert alert-info alert-dismissible fade show mb-4" role="alert">
    <h5 class="alert-heading">Filtrar resumen de horas trabajadas</h5>
    <p>
        Utiliza este filtro para consultar el total de horas trabajadas por los empleados. Puedes seleccionar un
        <strong>rango de fechas</strong> y, de forma opcional, un <strong>empleado específico</strong>.
    </p>
    <ul>
        <li>Si no se selecciona ningún rango de fechas, se mostrará por defecto el <strong>último mes</strong>.</li>
        <li>Si no se elige ningún empleado, se mostrarán los registros de <strong>todos los empleados</strong>.</li>
        <li>Las horas se calculan únicamente cuando hay registros <strong>emparejados de entrada y salida</strong>.
            Si faltan datos en un día, se indicará que <em>"Faltan registros para calcular"</em>.</li>
        <li>Puedes exportar o revisar estos datos según el período y los trabajadores seleccionados.</li>
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
</div>



<form method="GET" class="row g-3 mb-4">
    <div class="col-md-3">
        <label>Desde</label>
        <input type="date" name="desde" class="form-control" value="{{ $desde }}">
    </div>
    <div class="col-md-3">
        <label>Hasta</label>
        <input type="date" name="hasta" class="form-control" value="{{ $hasta }}">
    </div>
    <div class="col-md-4">
        <label>Empleado</label>
        <select name="empleado_id" class="form-select">
            <option value="">Todos</option>
            @foreach ($empleados as $e)
            <option value="{{ $e->id }}" {{ $empleadoId==$e->id ? 'selected' : '' }}>
                {{ $e->nombre }}
            </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2 d-flex align-items-end">
        <button class="btn btn-primary w-100" type="submit">Filtrar</button>
        <button class="btn btn-secondary w-100 ms-2" type="button"
            onclick="window.location.href='{{ route('resumen.horas', $empresa->id) }}'">Resetear</button>

    </div>
</form>





<div class="card mt-3">

    <div class="card-header">
        @foreach ($resumen as $r)
        <div class="card mb-3">
            <div class="card-header">
                <strong>{{ $r['empleado'] }}</strong> — Total: {{ $r['total'] }}
            </div>
            <div class="card-body">
                <ul>
                    @foreach ($r['detalle'] as $dia)
                    <li>{{ \Carbon\Carbon::parse($dia['fecha'])->format('d/m/Y') }} — {{ $dia['horas'] }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endforeach
    </div>
</div>




@endsection

@section('scripts')
@vite(['resources/js/pages/fichajes-registros.js'])
@endsection