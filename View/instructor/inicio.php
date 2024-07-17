<div class="container">
    <h1>Listado de Instructores</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Tipo</th>
                <th>Perfil</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($instructores)): ?>
                <?php foreach ($instructores as $instructor): ?>
                    <tr>
                        <td><?php echo $instructor['id']; ?></td>
                        <td><?php echo $instructor['nombre']; ?></td>
                        <td><?php echo $instructor['apellido']; ?></td>
                        <td><?php echo $instructor['tipo_nombre']; ?></td>
                        <td><?php echo $instructor['perfil']; ?></td>
                        <td>
                            <button class="btn btn-sm btn-primary edit-instructor" data-id="<?php echo $instructor['id']; ?>" data-nombre="<?php echo $instructor['nombre']; ?>" data-apellido="<?php echo $instructor['apellido']; ?>" data-tipo="<?php echo $instructor['tipo_id']; ?>" data-perfil="<?php echo $instructor['perfil']; ?>">Editar</button>
                            <button class="btn btn-sm btn-danger delete-instructor" data-id="<?php echo $instructor['id']; ?>">Eliminar</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">No hay instructores registrados.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal para editar instructor -->
<div class="modal fade" id="editInstructorModal" tabindex="-1" role="dialog" aria-labelledby="editInstructorModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editInstructorModalLabel">Editar Instructor</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editInstructorForm">
                    <input type="hidden" id="edit-id" name="id">
                    <div class="form-group">
                        <label for="edit-nombre">Nombre:</label>
                        <input type="text" class="form-control" id="edit-nombre" name="nombre" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-apellido">Apellido:</label>
                        <input type="text" class="form-control" id="edit-apellido" name="apellido" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-tipo">Tipo:</label>
                        <select class="form-control" id="edit-tipo" name="tipo" required>
                                <option value="1">Contratista</option>
                                <option value="2">Planta</option>

                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit-perfil">Perfil:</label>
                        <textarea class="form-control" id="edit-perfil" name="perfil" required></textarea>
                    </div>
                    <button type="button" class="btn btn-primary" id="updateInstructorButton">Actualizar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Script para manejar la edición de instructores -->
<script>
    $(document).ready(function() {
        // Mostrar el modal con los datos del instructor
        $('.edit-instructor').on('click', function() {
            var instructorId = $(this).data('id');
            var nombre = $(this).data('nombre');
            var apellido = $(this).data('apellido');
            var tipo = $(this).data('tipo');
            var perfil = $(this).data('perfil');

            $('#edit-id').val(instructorId);
            $('#edit-nombre').val(nombre);
            $('#edit-apellido').val(apellido);
            $('#edit-tipo').val(tipo);
            $('#edit-perfil').val(perfil);

            $('#editInstructorModal').modal('show');
        });

        // Manejar la actualización del instructor
        $('#updateInstructorButton').on('click', function() {
            var formData = $('#editInstructorForm').serialize();

            $.ajax({
                url: '?c=instructor&a=actualizar',
                method: 'POST',
                data: formData,
                success: function(response) {
                    // Manejar la respuesta del servidor
                    try {
                        var result = JSON.parse(response);
                        Swal.fire(
                            'Actualizado!',
                            result.message,
                            'success'
                        ).then(() => {
                            location.reload(); // Recargar la página después de actualizar
                        });
                    } catch (e) {
                        console.error('Error parsing JSON response:', e);
                        console.error('Response:', response);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error en la solicitud AJAX:', error);
                }
            });
        });

        // Manejar la eliminación del instructor
        $('.delete-instructor').on('click', function() {
            var instructorId = $(this).data('id');
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡No podrás revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminarlo!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '?c=instructor&a=eliminar',
                        method: 'POST',
                        data: { id: instructorId },
                        success: function(response) {
                            // Manejar la respuesta del servidor
                            try {
                                var result = JSON.parse(response);
                                Swal.fire(
                                    'Eliminado!',
                                    result.message,
                                    'success'
                                ).then(() => {
                                    location.reload(); // Recargar la página después de eliminar
                                });
                            } catch (e) {
                                console.error('Error parsing JSON response:', e);
                                console.error('Response:', response);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error en la solicitud AJAX:', error);
                        }
                    });
                }
            });
        });
    });
</script>
