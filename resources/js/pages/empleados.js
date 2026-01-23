import gridjs from "gridjs/dist/gridjs.umd.js";
import "gridjs/dist/gridjs.umd.js";

document.addEventListener("DOMContentLoaded", function () {
    const passwordInput = document.getElementById("password");
    const confirmPasswordInput = document.getElementById(
        "password_confirmation"
    );
    const errorDiv = document.createElement("div");
    const emailInputs = document.querySelectorAll("#email, #EmpleadoEmailEdit");

    let emailError = document.createElement("div");
    emailError.textContent = "El email ya está en uso";
    emailError.classList.add("text-danger", "mt-1", "hidden");

    function validatePassword() {
        if (confirmPasswordInput.value !== passwordInput.value) {
            errorDiv.textContent = "Las contraseñas no coinciden";
            confirmPasswordInput.classList.add("is-invalid");
            passwordInput.classList.add("is-invalid");
        } else {
            errorDiv.textContent = "";
            confirmPasswordInput.classList.remove("is-invalid");
            passwordInput.classList.remove("is-invalid");
        }
    }

    confirmPasswordInput.addEventListener("input", validatePassword);

    // Función para verificar si el email existe
    function checkEmail(input) {
        const email = input.value.trim();

        if (email) {
            fetch(`/check-email?email=${encodeURIComponent(email)}`)
                .then((response) => response.json())
                .then((data) => {
                    if (data.exists) {
                        emailError.classList.remove("hidden");
                        input.parentElement.appendChild(emailError);
                        input.classList.add("is-invalid");
                    } else {
                        emailError.remove();
                        input.classList.remove("is-invalid");
                    }
                })
                .catch((error) => {
                    console.error("Error en la verificación del email:", error);
                });
        } else {
            emailError.classList.add("hidden");
            input.classList.remove("is-invalid");
        }
    }

    // Agregar el evento 'blur' a ambos inputs
    emailInputs.forEach((input) => {
        input.addEventListener("blur", function () {
            checkEmail(this);
        });
    });

    if (typeof window.empleadosData !== "undefined") {
        const formattedEmpleados = window.empleadosData.map((empleado) => [
            empleado.nombre,
            empleado.telefono,
            empleado.DNI,
            empleado.email,
            empleado.deshabilitado ? "Sí" : "No",
            empleado.geolocalizacion_estricta ? "Sí" : "No",
            empleado.id,
        ]);

        new gridjs.Grid({
            columns: [
                "Nombre",
                "Teléfono",
                "DNI",
                "Email",
                "Deshabilitado",
                "Geo estricta", // NUEVA COLUMNA
                {
                    name: "Acciones",
                    formatter: (_, row) => {
                        const empleadoId = row.cells[6].data;
                        const empleadoNombre = row.cells[0].data;
                        const empleadoTelefono = row.cells[1].data;
                        const empleadoDNI = row.cells[2].data;
                        const empleadoEmail = row.cells[3].data;
                        const empleadoDeshabilitado = row.cells[4].data;
                        const empleadoGeo = row.cells[5].data;

                        return gridjs.html(`
                <div style="text-align: right;">
                    <button type="button" class="btn btn-sm btn-primary mb-2"
                        data-bs-toggle="modal"
                        data-bs-target="#editEmpleadoModal"
                        data-id="${empleadoId}"
                        data-nombre="${empleadoNombre}"
                        data-telefono="${empleadoTelefono}"
                        data-dni="${empleadoDNI}"
                        data-email="${empleadoEmail}"
                        data-deshabilitado="${empleadoDeshabilitado}"
                        data-geo="${empleadoGeo}">
                        Editar
                    </button>
                    <a data-empleado-id-delete="${empleadoId}" class="btn btn-sm btn-danger modal-eliminar mb-2" 
                        data-bs-toggle="modal" data-bs-target="#modalEliminarEmpleado">
                        Eliminar
                    </a>
                </div>
            `);
                    },
                },
            ],

            data: formattedEmpleados,
            search: true,
            pagination: {
                enabled: true,
                limit: 5,
            },
            sort: true,
        }).render(document.getElementById("table-empleados"));
    } else {
        console.error("No se encontraron datos de empleados.");
    }

    // Validación de contraseña en modal de empleado editar
    const errorPass = document.getElementById("errorPassEdit");
    const passwordEdit = document.getElementById("EmpleadoPasswordEdit");
    const passwordConfirmEdit = document.getElementById(
        "EmpleadoPasswordConfirmEdit"
    );

    function validatePasswordEdit() {
        if (passwordEdit.value !== passwordConfirmEdit.value) {
            errorPass.textContent = "Las contraseñas no coinciden";
            passwordEdit.classList.add("is-invalid");
            passwordConfirmEdit.classList.add("is-invalid");
        } else {
            errorPass.textContent = "";
            passwordEdit.classList.remove("is-invalid");
            passwordConfirmEdit.classList.remove("is-invalid");
        }
    }

    passwordConfirmEdit.addEventListener("input", validatePasswordEdit);

    var editEmpleadoModal = document.getElementById("editEmpleadoModal");

    // Escuchamos el evento 'show.bs.modal'
    editEmpleadoModal.addEventListener("show.bs.modal", function (event) {
        var button = event.relatedTarget;
        var editEmpleadoForm = document.getElementById("editEmpleadoForm");

        var empleadoId = button.getAttribute("data-id");
        var empleadoNombre = button.getAttribute("data-nombre");
        var empleadoTelefono = button.getAttribute("data-telefono");
        var empleadoDNI = button.getAttribute("data-dni");
        var empleadoEmail = button.getAttribute("data-email");

        var empleadoDeshabilitado = button.getAttribute("data-deshabilitado");
        empleadoDeshabilitado = empleadoDeshabilitado === "Sí" ? 1 : 0;

        var empleadoGeo = button.getAttribute("data-geo");
        empleadoGeo = empleadoGeo === "Sí" ? 1 : 0;

        editEmpleadoForm.action = `/empleados/update/${empleadoId}`;

        document.getElementById("EmpleadoIdEdit").value = empleadoId;
        document.getElementById("EmpleadoNombreEdit").value = empleadoNombre;
        document.getElementById("EmpleadoTelefonoEdit").value =
            empleadoTelefono;
        document.getElementById("EmpleadoDNIEdit").value = empleadoDNI;
        document.getElementById("EmpleadoEmailEdit").value = empleadoEmail;

        document.getElementById("EmpleadoDeshabilitadoEdit").value =
            empleadoDeshabilitado;
        document.getElementById("EmpleadoGeoEdit").value = empleadoGeo;
    });

    /* Eliminacion de empleado  */

    var modalEliminarEmpleado = document.getElementById(
        "modalEliminarEmpleado"
    );

    modalEliminarEmpleado.addEventListener("show.bs.modal", function (event) {
        var button = event.relatedTarget; // Botón que disparó el modal
        var empleadoId = button.getAttribute("data-empleado-id-delete");

        var formularioEliminar = modalEliminarEmpleado.querySelector(
            ".formularioEliminar"
        );

        // Asignar el ID al campo oculto
        formularioEliminar.querySelector(".user_id").value = empleadoId;

        formularioEliminar.action = `/empleados/delete/${empleadoId}`;
    });
});
