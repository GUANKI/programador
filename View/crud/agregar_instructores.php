<!-- View/admin/agregar_instructor.php -->
<section class="h-100 gradient-form" style="background-color: #eee;">
  <div class="container py-2 h-100 d-flex flex-column justify-content-between">
    <div class="row d-flex justify-content-center align-items-center my-4">
      <div class="col-xl-10">
        <div class="card rounded-3 text-black">
          <div class="row g-0">
            <div class="col-lg-12 mx-auto">
              <div class="card-body p-md-5 mx-md-4">
                <div class="text-center">
                  <img src="Assets/img/logo.png" style="width: 90px;" alt="logo">
                  <h4 class="mt-1 mb-5 pb-1">Añadir Nuevo Instructor a la Base de Datos</h4>
                </div>
                <form method="POST" action="?c=instructor&a=guardar">
                  <div class="form-outline mb-4">
                    <label class="form-label" for="nombre">Nombres del Instructor</label>
                    <input type="text" id="nombre" name="nombre" class="form-control" required />
                    <div class="form-text">Asegúrese de escribirlo correctamente. Letras iniciales con Mayúscula</div>
                  </div>
                  <div class="form-outline mb-4">
                    <label class="form-label" for="apellido">Apellidos del Instructor</label>
                    <input type="text" id="apellido" name="apellido" class="form-control" required />
                    <div class="form-text">Asegúrese de escribirlo correctamente. Letras iniciales con Mayúscula</div>
                  </div>
                  <div class="form-outline mb-4">
                    <label class="form-label" for="tipo_id">El Instructor es:</label>
                    <select id="tipo_id" name="tipo_id" class="form-select" required>
                      <option value="">Seleccione el tipo de instructor</option>
                      <?php foreach ($tiposInstructores as $tipo) : ?>
                        <option value="<?php echo $tipo['id']; ?>"><?php echo $tipo['descripcion']; ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="form-outline mb-4">
                    <label class="form-label" for="perfil">Perfil del Instructor</label>
                    <textarea id="perfil" name="perfil" class="form-control" rows="4" required></textarea>
                  </div>
                  <div class="text-center pt-1 mb-5 pb-1">
                    <button class="btn btn-primary btn-block fa-lg mb-3" type="submit">Agregar Instructor</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="mt-auto"></div>
  </div>
</section>