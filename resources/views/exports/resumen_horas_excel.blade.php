<h3 style="margin-bottom: 10px;">
    Resumen de horas trabajadas
</h3>

<p style="margin-bottom: 20px;">

    @if ($desde && $hasta)
        Rango de fechas: <strong>{{ \Carbon\Carbon::parse($desde)->format('d/m/Y') }}</strong>
        hasta <strong>{{ \Carbon\Carbon::parse($hasta)->format('d/m/Y') }}</strong>
    @elseif ($desde)
        Desde <strong>{{ \Carbon\Carbon::parse($desde)->format('d/m/Y') }}</strong>
    @elseif ($hasta)
        Hasta <strong>{{ \Carbon\Carbon::parse($hasta)->format('d/m/Y') }}</strong>
    @else
        Rango por defecto: últimos 30 días
    @endif
</p>

<table>
    <thead>
        <tr>
            <th>Empleado</th>
            <th>Fecha</th>
            <th>Horas trabajadas</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($resumen as $r)
            @foreach ($r['detalle'] as $dia)
                <tr>
                    <td>{{ $r['empleado'] }}</td>
                    <td>{{ \Carbon\Carbon::parse($dia['fecha'])->format('d/m/Y') }}</td>
                    <td>{{ $dia['horas'] }}</td>
                </tr>
            @endforeach
        @endforeach
    </tbody>
</table>
