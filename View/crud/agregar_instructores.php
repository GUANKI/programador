
<form action="?c=instructor&a=guardar" method="post">
  <div class="mb-3">
    <label for="nombre" class="form-label">Nombres del instructor</label>
    <input type="text" class="form-control" id="nombre" name="nombre" required>
    <div class="form-text">Asegúrese de escribirlo correctamente. Letras inicales con Mayuscula</div>
  </div>
  <div class="mb-3">
    <label for="apellido" class="form-label">Apellidos del instructor</label>
    <input type="text" class="form-control" id="apellido" name="apellido" required>
    <div class="form-text">Asegúrese de escribirlo correctamente. Letras inicales con Mayuscula</div>
  </div>
  <div class="mb-3">
    <label for="tipo_id" class="form-label">El Instructor es:</label>
    <select class="form-select" id="tipo_id" name="tipo_id" required>
      <option value="">Seleccione el tipo de instructor</option>
      <?php foreach ($tiposInstructores as $tipo): ?>
        <option value="<?php echo $tipo['id']; ?>"><?php echo $tipo['descripcion']; ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <button type="submit" class="btn btn-primary">Agregar Instructor</button>
</form>
