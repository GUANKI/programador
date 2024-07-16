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
                                <a href="?c=instructores&a=editar&id=<?php echo $instructor['id']; ?>" class="btn btn-sm btn-primary">Editar</a>
                                <button class="btn btn-sm btn-danger delete-instructor" data-id="<?php echo $instructor['id']; ?>">Eliminar</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">No hay instructores registrados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Script para manejar la eliminación de instructores -->
    <script>
        $(document).ready(function() {
            $('.delete-instructor').on('click', function() {
                var instructorId = $(this).data('id');

                Swal.fire({
                    title: '¿Está seguro?',
                    text: "No podrás revertir esto",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminarlo',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '?c=instructor&a=eliminar',
                            method: 'POST',
                            data: {
                                id: instructorId
                            },
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
                                    Swal.fire(
                                        'Error!',
                                        'Ocurrió un error inesperado. Por favor, inténtalo de nuevo.',
                                        'error'
                                    );
                                }
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                console.error('AJAX error:', textStatus, errorThrown);
                                Swal.fire(
                                    'Error!',
                                    'Ocurrió un error en la comunicación con el servidor. Por favor, inténtalo de nuevo.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });
        });
    </script>