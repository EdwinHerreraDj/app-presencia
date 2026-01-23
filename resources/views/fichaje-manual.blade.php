@extends('layouts.vertical', ['subtitle' => 'Registro de Fichajes Manuales'])

@section('css')
@vite(['node_modules/gridjs/dist/theme/mermaid.min.css'])
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf/notyf.min.css">
<script src="https://cdn.jsdelivr.net/npm/notyf/notyf.min.js"></script>
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

<div class="d-flex align-items-center gap-1 mb-3">
    {{-- Botón para regresar --}}
    <a href="{{ route('registrosFichajes', $empresa->id) }}" class="btn btn-primary d-flex align-items-center">
        <img src="/images/brands/regresar.svg" alt="" class="me-2">
        Volver atrás
    </a>
</div>

<div class="card mt-3">

    <div class="card-header">
        <h5 class="card-title">Agregar fichaje anteriores de la empresa {{ $empresa->nombre }}</h5>
        <p>En este formulario puedes registrar fichajes manualmente para la empresa seleccionada. </p>
    </div>

    <div class="card-body">
        <form action="{{ route('fichaje.manual.store') }}" method="POST">
            @csrf
            <input type="hidden" name="empresa_id" value="{{ $empresa->id }}">
            <input type="hidden" name="latitud" value="{{ $empresa->latitud }}">
            <input type="hidden" name="longitud" value="{{ $empresa->longitud }}">

            <div class="mb-3">
                <label for="empleado" class="form-label">Empleado</label>
                <select name="empleado_id" id="empleado" class="form-select" required>
                    <option value="" selected disabled>Selecciona un empleado</option>
                    @foreach ($empleados as $empleado)
                    <option value="{{ $empleado->id }}">{{ $empleado->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="fecha" class="form-label">Fecha del fichaje</label>
                    <input type="date" class="form-control" name="fecha" id="fecha" min="2025-01-01"
                        max="{{ now()->format('Y-m-d') }}" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="hora" class="form-label">Hora del fichaje</label>
                    <input type="time" class="form-control" name="hora" id="hora" required>
                </div>
            </div>



            <div class="mb-3">
                <label for="tipo" class="form-label">Tipo de fichaje</label>
                <select name="tipo" id="tipo" class="form-select" required>
                    <option value="" disabled selected>Selecciona tipo</option>
                    <option value="entrada">Entrada</option>
                    <option value="salida">Salida</option>
                </select>
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">Guardar fichaje</button>
            </div>
        </form>

    </div>
</div>
<div class="card mt-3">
    <div class="card-header">
        <h5 class="card-title">Ultimos registros del empleado</h5>
        <p>En este apartado podra visualizar información del ultimo registro del empleado según la fecha establecida en
            el formulario anterior</p>
    </div>
    <div id="resultados-fichajes"></div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
            const empleadoSelect = document.getElementById('empleado');
            const fechaInput = document.getElementById('fecha');
            const resultados = document.getElementById('resultados-fichajes');

            function cargarFichajes() {
                const empleadoId = empleadoSelect.value;
                const fecha = fechaInput.value;

                if (!empleadoId || !fecha) {
                    resultados.innerHTML = '';
                    return;
                }

                fetch(`/fichajes/por-dia?empleado_id=${empleadoId}&fecha=${fecha}`)
                    .then(response => response.json())
                    .then(data => {
                        resultados.innerHTML = '';

                        if (data.length === 0) {
                            resultados.innerHTML =
                                '<div class="alert alert-info">No hay fichajes para este día.</div>';
                            return;
                        }

                        let html = `<table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Tipo</th>
                            <th>Ip</th>
                            <th>Dispositivo</th>
                        </tr>
                    </thead>
                    <tbody>`;
                       data.forEach(fichaje => {
                            const fechaUTC = new Date(fichaje.fecha_hora);
                                            
                            // Convertir a fecha y hora local automáticamente (maneja horario de verano)
                            const fechaLocal = fechaUTC.toLocaleDateString('es-ES'); 
                            const horaLocal = fechaUTC.toLocaleTimeString('es-ES', {
                                hour: '2-digit',
                                minute: '2-digit'
                            });
                        
                            html += `<tr>
                                <td>${fechaLocal}</td>
                                <td>${horaLocal}</td>
                                <td>${fichaje.tipo}</td>
                                <td>${fichaje.ip}</td>
                                <td>${fichaje.dispositivo}</td>
                            </tr>`;
                        });
                        
                        html += '</tbody></table>';
                        resultados.innerHTML = html;



                        html += '</tbody></table>';
                        resultados.innerHTML = html;
                    })
                    .catch(error => {
                        console.error('Error cargando fichajes:', error);
                        resultados.innerHTML =
                            '<div class="alert alert-danger">Error al cargar los fichajes.</div>';
                    });
            }

            empleadoSelect.addEventListener('change', cargarFichajes);
            fechaInput.addEventListener('change', cargarFichajes);
        });
</script>
@endsection