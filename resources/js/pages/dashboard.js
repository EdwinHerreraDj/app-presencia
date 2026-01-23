import { Grid } from "gridjs/dist/gridjs.umd.js";
import gridjs from "gridjs/dist/gridjs.umd.js";
import "gridjs/dist/gridjs.umd.js";

document.addEventListener("DOMContentLoaded", function () {
    /* =========================
       VALIDACIÓN DATOS
    ========================= */
    if (typeof window.empresasData === "undefined") {
        console.error("No se encontraron datos de empresas.");
        return;
    }

    /* =========================
       GRIDJS DATA
    ========================= */
    const formattedEmpresas = window.empresasData.map((e) => [
        e.nombre, // 0
        e.direccion, // 1
        e.descripcion, // 2
        e.latitud, // 3
        e.longitud, // 4
        e.radio, // 5
        e.fichaje_activo, // 6
        e.id, // 7
    ]);

    /* =========================
       GRIDJS
    ========================= */
    new gridjs.Grid({
        columns: [
            "Nombre",
            "Dirección",
            "Descripción",
            "Latitud",
            "Longitud",
            "Radio",
            {
                name: "Fichaje activo",
                formatter: (_, row) => (row.cells[6].data == 1 ? "Sí" : "No"),
            },
            {
                name: "Acciones",
                formatter: (_, row) => {
                    const id = row.cells[7].data;
                    return gridjs.html(`
                        <div class="text-end">
                            <button
                                class="btn btn-sm btn-primary mb-2"
                                data-bs-toggle="modal"
                                data-bs-target="#editEmpresaModal"
                                data-id="${id}"
                                data-nombre="${row.cells[0].data}"
                                data-direccion="${row.cells[1].data}"
                                data-descripcion="${row.cells[2].data}"
                                data-latitud="${row.cells[3].data}"
                                data-longitud="${row.cells[4].data}"
                                data-radio="${row.cells[5].data}"
                                data-fichaje="${row.cells[6].data}">
                                Editar
                            </button>

                            <button
                                class="btn btn-sm btn-danger mb-2"
                                data-bs-toggle="modal"
                                data-bs-target="#modalEliminarEmpresa"
                                data-empresa-id-delete="${id}">
                                Eliminar
                            </button>

                            <a href="/registrosFichajes/${id}" class="btn btn-sm btn-info mb-2">
                                Ver empleados
                            </a>
                        </div>
                    `);
                },
            },
        ],
        data: formattedEmpresas,
        search: true,
        sort: true,
        pagination: { enabled: true, limit: 5 },
    }).render(document.getElementById("table-empresas"));

    /* =========================
       MAPA (LEAFLET)
    ========================= */
    let map = null;
    let marker = null;
    let latInput = null;
    let lngInput = null;

    function initMapa(containerId, latId, lngId, lat = 40.4168, lng = -3.7038) {
        const container = document.getElementById(containerId);
        if (!container) return;

        if (map) {
            map.remove();
            map = null;
        }

        latInput = document.getElementById(latId);
        lngInput = document.getElementById(lngId);

        map = L.map(containerId).setView([lat, lng], 15);

        L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
            attribution: "© OpenStreetMap",
        }).addTo(map);

        marker = L.marker([lat, lng], { draggable: true }).addTo(map);

        updateLatLng(lat, lng);

        map.on("click", (e) => {
            marker.setLatLng(e.latlng);
            updateLatLng(e.latlng.lat, e.latlng.lng);
        });

        marker.on("dragend", (e) => {
            const p = e.target.getLatLng();
            updateLatLng(p.lat, p.lng);
        });

        setTimeout(() => map.invalidateSize(), 300);
    }

    function updateLatLng(lat, lng) {
        if (latInput && lngInput) {
            latInput.value = lat.toFixed(6);
            lngInput.value = lng.toFixed(6);
        }
    }

    /* =========================
       BUSCADOR DIRECCIÓN
    ========================= */
    async function buscarDireccion(inputId) {
        const input = document.getElementById(inputId);
        if (!input || !input.value.trim()) return;

        const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(input.value)}`;
        const res = await fetch(url);
        const data = await res.json();

        if (!data.length) {
            alert("No se encontraron resultados");
            return;
        }

        const lat = parseFloat(data[0].lat);
        const lng = parseFloat(data[0].lon);

        map.setView([lat, lng], 17);
        marker.setLatLng([lat, lng]);
        updateLatLng(lat, lng);
    }

    /* =========================
       MODAL CREAR
    ========================= */
    const modalCrear = document.getElementById("agregarEmpresa");
    if (modalCrear) {
        modalCrear.addEventListener("shown.bs.modal", () => {
            initMapa("crearMapa", "latitud", "longitud");
        });

        document
            .getElementById("crearBuscarDireccion")
            ?.addEventListener("click", () =>
                buscarDireccion("crearDireccionBusqueda"),
            );
    }

    /* =========================
       MODAL EDITAR
    ========================= */
    const editEmpresaModal = document.getElementById("editEmpresaModal");
    if (editEmpresaModal) {
        editEmpresaModal.addEventListener("show.bs.modal", (e) => {
            const b = e.relatedTarget;

            document.getElementById("modalEmpresaId").value = b.dataset.id;
            document.getElementById("modalEmpresaNombre").value =
                b.dataset.nombre;
            document.getElementById("modalEmpresaDireccion").value =
                b.dataset.direccion;
            document.getElementById("modalEmpresaDescripcion").value =
                b.dataset.descripcion;
            document.getElementById("modalEmpresaLatitud").value =
                b.dataset.latitud;
            document.getElementById("modalEmpresaLongitud").value =
                b.dataset.longitud;
            document.getElementById("modalEmpresaRadio").value =
                b.dataset.radio;

            document.getElementById("editEmpresaFichajeActivo").checked =
                b.dataset.fichaje == 1;

            document.getElementById("editEmpresaForm").action =
                `/empresas/update/${b.dataset.id}`;
        });

        editEmpresaModal.addEventListener("shown.bs.modal", () => {
            initMapa(
                "editarMapa",
                "modalEmpresaLatitud",
                "modalEmpresaLongitud",
                parseFloat(
                    document.getElementById("modalEmpresaLatitud").value,
                ),
                parseFloat(
                    document.getElementById("modalEmpresaLongitud").value,
                ),
            );
        });

        document
            .getElementById("editarBuscarDireccion")
            ?.addEventListener("click", () =>
                buscarDireccion("editarDireccionBusqueda"),
            );
    }

    /* =========================
       MODAL ELIMINAR
    ========================= */
    const deleteEmpresaModal = document.getElementById("modalEliminarEmpresa");
    if (deleteEmpresaModal) {
        deleteEmpresaModal.addEventListener("show.bs.modal", (e) => {
            const id = e.relatedTarget.dataset.empresaIdDelete;
            document.getElementById("deleteEmpresaId").value = id;
            document.getElementById("deleteEmpresaForm").action =
                `/empresas/delete/${id}`;
        });
    }
});
