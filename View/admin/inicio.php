<section id="services" class="services section">

      <div class="container">

        <div class="row gy-4">

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
            <div class="service-item  position-relative">
              <div class="icon">
                <i class="bi bi-calendar4-week"></i>
              </div>
              <a href="?c=programar&a=index" class="stretched-link">
                <h3>Programar Instructores</h3>
              </a>
              <p>Añadir nuevos registros a la programación de los instructores</p>
            </div>
          </div><!-- End Service Item -->

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
            <div class="service-item position-relative">
              <div class="icon">
                <i class="bi bi-broadcast"></i>
              </div>
              <a href="?c=programar&a=indexInstructor" class="stretched-link">
                <h3>Consultar Programación por Instructor</h3>
              </a>
              <p>Visulizar la programación del instructor seleccionado</p>
            </div>
          </div><!-- End Service Item -->

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
            <div class="service-item position-relative">
              <div class="icon">
                <i class="bi bi-easel"></i>
              </div>
              <a href="?c=instructor&a=agregar" class="stretched-link">
                <h3>Añadir Instructores</h3>
              </a>
              <p>Agregar nuevos instructores a la base de datos para tenerlo habilitado para programarlo</p>
            </div>
          </div><!-- End Service Item -->

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="400">
            <div class="service-item position-relative">
              <div class="icon">
                <i class="bi bi-bounding-box-circles"></i>
              </div>
              <a href="#" class="stretched-link">
                <h3>Añadir Usuarios</h3>
              </a>
              <p>Designe un nuevo administrador o Instructor para que pueda interactuar con el programador.</p>
              <a href="?c=usuario&a=agregar_usuario" class="stretched-link"></a>
            </div>
          </div><!-- End Service Item -->

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="500">
            <div class="service-item position-relative">
              <div class="icon">
                <i class="bi bi-activity"></i>
              </div>
              <a href="#" class="stretched-link">
                <h3>Consultar Horas Programadas</h3>
              </a>
              <p>Descargue el reporte de los horas porgramadas de los instructores</p>
              <a href="?c=generar&a=inicio" class="stretched-link"></a>
            </div>
          </div><!-- End Service Item -->

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="600">
            <div class="service-item position-relative">
              <div class="icon">
                <i class="bi bi-chat-square-text"></i>
              </div>
              <a href="#" class="stretched-link">
                <h3>Instructores</h3>
              </a>
              <p>Lista de los instructores disponibles para ser programados</p>
              <a href="?c=instructor&a=instructorview" class="stretched-link"></a>
            </div>
          </div><!-- End Service Item -->

        </div>

      </div>

    </section><!-- /Services Section -->
    <script>
    // Esperar a que el documento esté completamente cargado
    document.addEventListener("DOMContentLoaded", function() {
        // Capturar el evento de clic en el botón
        document.getElementById("btn-generar-excel").addEventListener("click", function(event) {
            event.preventDefault(); // Prevenir el comportamiento predeterminado del enlace

            // Realizar una petición AJAX para generar el archivo Excel
            var xhr = new XMLHttpRequest();
            xhr.open('GET', '?c=generar&a=excel', true);
            xhr.responseType = 'blob'; // Importante: solicitar una respuesta de tipo blob (archivo binario)

            xhr.onload = function() {
                if (xhr.status === 200) {
                    // Crear un objeto URL con la respuesta del servidor
                    var blob = xhr.response;
                    var url = window.URL.createObjectURL(blob);

                    // Crear un enlace invisible para descargar el archivo
                    var a = document.createElement('a');
                    a.style.display = 'none';
                    a.href = url;
                    a.download = 'reporte_mensual_instructores_<?php echo date('Y'); ?>.xlsx'; // Nombre del archivo sugerido para descarga
                    document.body.appendChild(a);
                    a.click();

                    // Liberar el objeto URL después de la descarga
                    window.URL.revokeObjectURL(url);
                }
            };

            xhr.send();
        });
    });
</script>