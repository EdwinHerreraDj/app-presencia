import{g as n}from"./gridjs.umd-V6YQZSHU.js";import"./_commonjsHelpers-BosuxZz1.js";if(typeof window.fichajesData<"u"){const r=window.fichajesData.slice().reverse().map(t=>{const e=new Date(t.fecha_hora),a=e.toLocaleDateString("es-ES"),o=e.toLocaleTimeString("es-ES",{hour:"2-digit",minute:"2-digit",second:"2-digit",hour12:!1}),i=n.html(`
            <div class="d-flex justify-content-center align-items-center">
                <button class="btn btn-sm btn-primary me-1"
                    data-bs-toggle="modal"
                    data-bs-target="#modalEditarFichaje"
                    data-nombre="${t.empleado.nombre}"
                    data-id="${t.id}"
                    data-fecha="${a}"
                    data-hora="${o.slice(0,5)}"
                    data-tipo="${t.tipo}">
                    Editar
                </button>
                <button class="btn btn-sm btn-danger" onclick="eliminarFichaje('${t.id}')">Eliminar</button>
            </div>
        `);return[t.empleado.nombre,t.empleado.DNI,a,o,t.tipo,t.ip,t.dentro_rango?"Sí":"No",i]});new n.Grid({columns:["Empleado","DNI","Fecha","Hora","Tipo","IP","Dentro del Rango","Acciones"],data:r,search:!0,pagination:{enabled:!0,limit:5},sort:!0}).render(document.getElementById("table-fichajes"))}else console.error("No se encontraron datos de fichajes.");
