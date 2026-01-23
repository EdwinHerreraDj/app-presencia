import { Grid } from "gridjs/dist/gridjs.umd.js";
import gridjs from 'gridjs/dist/gridjs.umd.js'
import 'gridjs/dist/gridjs.umd.js'


document.addEventListener("DOMContentLoaded", function () {
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('password_confirmation');
    const passwordConfirmationError = document.getElementById('passwordConfirmationError');
    const userForm = document.getElementById('userForm');

    const emailInput = document.getElementById('email');
    const emailError = document.getElementById('emailError');


    confirmPasswordInput.addEventListener('blur', validatePasswords);


    userForm.addEventListener('submit', function (e) {
        if (!validatePasswords()) {
            e.preventDefault();
        }
    });

    function validatePasswords() {
        if (confirmPasswordInput.value !== passwordInput.value) {
            passwordInput.classList.add('is-invalid');
            confirmPasswordInput.classList.add('is-invalid');
            passwordConfirmationError.textContent = "Las contraseñas no coinciden";
            return false;
        } else {
            passwordInput.classList.remove('is-invalid');
            confirmPasswordInput.classList.remove('is-invalid');
            passwordConfirmationError.textContent = "";
            return true;
        }
    }


    emailInput.addEventListener('blur', function () {
        const email = this.value.trim();

        if (email) {
            fetch(`/check-email?email=${encodeURIComponent(email)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.exists) {
                        emailError.textContent = "El email ya está en uso";
                        emailInput.classList.add('is-invalid');
                    } else {
                        emailError.textContent = "";
                        emailInput.classList.remove('is-invalid');
                    }
                })
                .catch(error => {
                    console.error('Error en la verificación del email:', error);
                });
        }
    });

    /* Renderizado de datos en la tabla */

    if (typeof window.usersData !== "undefined") {
        const formattedUsers = window.usersData.map(user => [
            user.name,
            user.email,
            user.rol,
            user.id
        ]);

        new gridjs.Grid({
            columns: [
                "Nombre",
                "Email",
                "Rol",
                {
                    name: "Acciones",
                    formatter: (_, row) => {
                        // row.cells[3].data contiene el id del usuario
                        const userId = row.cells[3].data;
                        const userName = row.cells[0].data;
                        const userEmail = row.cells[1].data;
                        const userRol = row.cells[2].data;
                        return gridjs.html(`
                            <div style="text-align: right;">
                                <button type="button" class="btn btn-sm btn-primary mb-2" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#editModal"
                                    data-user-id="${userId}"
                                    data-user-name="${userName}"
                                    data-user-email="${userEmail}"
                                    data-user-rol="${userRol}">
                                    Editar
                                </button>
                                <a data-user-id-delete="${userId}" class="btn btn-sm btn-danger modal-eliminar mb-2" data-bs-toggle="modal"
                                data-bs-target="#modalEliminar">Eliminar</a>
                            </div>
                    `);
                    }
                }
            ],
            data: formattedUsers,
            search: true,
            pagination: {
                enabled: true,
                limit: 5
            },
            sort: true
        }).render(document.getElementById("table-gridjs"));
    } else {
        console.error("No se encontraron datos de usuarios.");
    }


    /* Evento para abrir el modal de edicion */

    var editModal = document.getElementById('editModal');

    // Escuchamos el evento 'show.bs.modal'
    editModal.addEventListener('show.bs.modal', function (event) {
        // El botón que disparó el modal
        var button = event.relatedTarget;

        //Seleccionamos el formulario
        var editForm = document.getElementById('editForm');

        // Extraemos la información del usuario de los data attributes
        var userId = button.getAttribute('data-user-id');
        var userName = button.getAttribute('data-user-name');
        var userEmail = button.getAttribute('data-user-email');
        var userRol = button.getAttribute('data-user-rol');


        // Asignamos el action al formulario
        editForm.action = `/users/${userId}`;

        // Actualizamos el contenido del modal
        document.getElementById('modalUserId').value = userId;
        document.getElementById('modalUserName').value = userName;
        document.getElementById('modalUserEmail').value = userEmail;
        document.getElementById('modalUserRol').value = userRol;

        // Opcional: Actualizar el título del modal
        var modalTitle = editModal.querySelector('.modal-title');
        modalTitle.textContent = 'Editar Usuario: ' + userName;


        const passwordEditInput = editModal.querySelector('#password_edit');
        const confirmPasswordEditInput = editModal.querySelector('#password_confirmation_edit');
        const passwordConfirmationErrorEdit = editModal.querySelector('#passwordConfirmationErrorEdit');

        // Función para validar la confirmación de contraseña
        function validateEditPasswords() {
            // Si ambos campos están vacíos, no se está cambiando la contraseña
            if (passwordEditInput.value === '' && confirmPasswordEditInput.value === '') {
                confirmPasswordEditInput.classList.remove('is-invalid');
                passwordConfirmationErrorEdit.textContent = "";
                return true;
            }

            if (passwordEditInput.value !== confirmPasswordEditInput.value) {
                passwordEditInput.classList.add('is-invalid');
                confirmPasswordEditInput.classList.add('is-invalid');
                passwordConfirmationErrorEdit.textContent = "Las contraseñas no coinciden";
                return false;
            } else {
                passwordEditInput.classList.remove('is-invalid');
                confirmPasswordEditInput.classList.remove('is-invalid');
                passwordConfirmationErrorEdit.textContent = "";
                return true;
            }
        }

        // Validar cuando se pierda el foco del campo de confirmación
        confirmPasswordEditInput.addEventListener('blur', validateEditPasswords);

        // También puedes validar al enviar el formulario del modal
        var editForm = editModal.querySelector('form');
        if (editForm) {
            editForm.addEventListener('submit', function (e) {
                if (!validateEditPasswords()) {
                    e.preventDefault();
                }
            });
        }
    });

    // Obtener el modal de eliminación
    var eliminarModal = document.getElementById('modalEliminar');

    // Escuchar el evento cuando el modal está a punto de mostrarse
    eliminarModal.addEventListener('show.bs.modal', function (event) {
        // El botón que disparó el modal
        var button = event.relatedTarget;

        // Obtener el ID del usuario desde el atributo personalizado
        var userId = button.getAttribute('data-user-id-delete');

        // Actualizar el formulario con el ID del usuario
        var formularioEliminar = eliminarModal.querySelector('.formularioEliminar');
        formularioEliminar.querySelector('.user_id').value = userId;

        // Configurar la acción del formulario con la URL correcta
        formularioEliminar.action = `/users/delete/${userId}`;
    });


});