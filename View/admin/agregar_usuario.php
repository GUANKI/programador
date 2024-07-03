<!-- View/admin/agregar_usuario.php -->
<section class="h-100 gradient-form" style="background-color: #eee;">
    <div class="container py-2 h-100 d-flex flex-column justify-content-between">
        <div class="row d-flex justify-content-center align-items-center" style="margin: -2.5rem;">
            <div class="col-xl-10">
                <div class="card rounded-3 text-black">
                    <div class="row g-0">
                        <div class="col-lg-6 mx-auto">
                            <div class="card-body p-md-5 mx-md-4">
                                <div class="text-center">
                                    <img src="Assets/img/logo.png" style="width: 90px;" alt="logo">
                                    <h4 class="mt-1 mb-5 pb-1">Agregar Nuevo Usuario</h4>
                                </div>
                                <form method="POST" action="?c=usuario&a=agregar_usuario">
                                    <div class="form-outline mb-4">
                                        <label class="form-label" for="nombre">Nombre</label>
                                        <input type="text" id="nombre" name="nombre" class="form-control" required />
                                    </div>
                                    <div class="form-outline mb-4">
                                        <label class="form-label" for="apellido">Apellido</label>
                                        <input type="text" id="apellido" name="apellido" class="form-control" required />
                                    </div>
                                    <div class="form-outline mb-4">
                                        <label class="form-label" for="cedula">Cédula</label>
                                        <input type="number" id="cedula" name="cedula" class="form-control" required />
                                    </div>
                                    <div class="form-outline mb-4">
                                        <label class="form-label" for="contraseña">Contraseña</label>
                                        <input type="password" id="contraseña" name="contrasena" class="form-control" required />
                                    </div>
                                    <div class="form-outline mb-4">
                                        <label class="form-label" for="privilegio">Privilegio</label>
                                        <select id="privilegio" name="privilegio" class=" form-select" required>
                                            <option selected>Elija una opción del menú</option>
                                            <option value="1">Administrador</option>
                                            <option value="2">Instructor</option>
                                        </select>
                                    </div>
                                    <div class="text-center pt-1 mb-5 pb-1">
                                        <button class="btn btn-primary btn-block fa-lg mb-3" type="submit">Agregar Usuario</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
   

</section>
