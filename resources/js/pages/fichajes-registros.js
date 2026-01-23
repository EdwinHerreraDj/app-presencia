import gridjs from 'gridjs/dist/gridjs.umd.js'
import 'gridjs/dist/gridjs.umd.js'

if (typeof window.fichajesData !== "undefined") {
    const formattedFichajes = window.fichajesData.slice().reverse().map(fichaje => {
        const fechaHora = new Date(fichaje.fecha_hora);
        const fechaFormateada = fechaHora.toLocaleDateString('es-ES');
        const horaFormateada = fechaHora.toLocaleTimeString('es-ES', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: false,
        });

        const acciones = gridjs.html(`
            <div class="d-flex justify-content-center align-items-center">
                <button class="btn btn-sm btn-primary me-1"
                    data-bs-toggle="modal"
                    data-bs-target="#modalEditarFichaje"
                    data-nombre="${fichaje.empleado.nombre}"
                    data-id="${fichaje.id}"
                    data-fecha="${fechaFormateada}"
                    data-hora="${horaFormateada.slice(0, 5)}"
                    data-tipo="${fichaje.tipo}">
                    Editar
                </button>
                <button class="btn btn-sm btn-danger" onclick="eliminarFichaje('${fichaje.id}')">Eliminar</button>
            </div>
        `);

        return [
            fichaje.empleado.nombre,
            fichaje.empleado.DNI,
            fechaFormateada,
            horaFormateada,
            fichaje.tipo,
            fichaje.ip,
            fichaje.dentro_rango ? "Sí" : "No",
            acciones,
        ];
    });


    new gridjs.Grid({
        columns: [
            "Empleado",
            "DNI",
            "Fecha",
            "Hora",
            "Tipo",
            "IP",
            "Dentro del Rango",
            "Acciones"
        ],
        data: formattedFichajes,
        search: true,
        pagination: {
            enabled: true,
            limit: 5
        },
        sort: true
    }).render(document.getElementById("table-fichajes"));
} else {
    console.error("No se encontraron datos de fichajes.");
}


