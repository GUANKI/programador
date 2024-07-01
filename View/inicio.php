<!-- <body>
    <div class="container">
        <center>
            <h1>Programador Instructores CDATH</h1>
        </center>
    </div>
    <div class="container">
        <a href="?c=programar&a=index" class="btn btn-info">Ir a programar</a>
        <a href="?c=instructor&a=agregar" class="btn btn-success">Agregar Instructores</a>
        <a href="?c=formacion&a=inicio" class="btn btn-primary">Agregar Programas de Formación</a>
        <a href="?c=programar&a=indexInstructor" class="btn btn-danger">Buscar Programación Instructor</a>
    </div>
</body>

</html> -->

<?php


if (isset($_SESSION['user_id'])) {
    if ($_SESSION['user_privilege'] == 1 || $_SESSION['user_privilege'] == 2) {
        $programarLink = "?c=programar&a=indexInstructor";
        $textheader="Administrador"; // Enlace para administradores
    }
} else {
  
    $programarLink = "?c=usuario&a=login";
    $textheader="Programar";
}


if (isset($_SESSION['user_id'])) {
  if ($_SESSION["user_privilege"] == 1){
    $adminlik = "?c=usuario&a=adminview";
  }else{
    $adminlik = "?c=programar&a=indexInstructor";
  }
}else{
  $adminlik= "?c=usuario&a=login";
}
?>

<section id="hero" class="hero section py-4">

<div id="hero-carousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000">

  <div class="carousel-item active">
    <img src="assets/img/hero-carousel/hero-carousel-1.jpg" alt="">
    <div class="carousel-container">
      <h2>BIENVENIDO AL <span>PROGRAMADOR CDATH</span></h2>
      <p>Consulta su Programador Fácil y Rápidamente.</p>
      <a href="?c=aprendiz&a=aprendizview" class="btn-get-started">Ver mi Programador</a>
    </div>
  </div><!-- End Carousel Item -->

  <div class="carousel-item">
    <img src="assets/img/hero-carousel/hero-carousel-2.jpg" alt="">
    <div class="carousel-container">
      <h2>CONSULTA LA PROGRAMACIÓN</h2>
      <p>Consulta la programación de instructores de CDATH fácilmente. Accede a horarios actualizados de manera rapida.</p>
      <a href="about.html" class="btn-get-started">Ver Programación</a>
    </div>
  </div><!-- End Carousel Item -->

  <div class="carousel-item">
    <img src="assets/img/hero-carousel/hero-carousel-3.jpg" alt="">
    <div class="carousel-container">
      <h2>DINAMICO Y ACTUALIZABLE</h2>
      <p>Cualquier cambio que se realice se vera reflejado de manera inmediata</p>
      <a href="about.html" class="btn-get-started">Añadir Instructor</a>
    </div>
  </div><!-- End Carousel Item -->

  <a class="carousel-control-prev" href="#hero-carousel" role="button" data-bs-slide="prev">
    <span class="carousel-control-prev-icon bi bi-chevron-left" aria-hidden="true"></span>
  </a>

  <a class="carousel-control-next" href="#hero-carousel" role="button" data-bs-slide="next">
    <span class="carousel-control-next-icon bi bi-chevron-right" aria-hidden="true"></span>
  </a>

</div>

<div class="featured container">

  <div class="row gy-4">

    <div class="col-lg-4 d-flex" data-aos="fade-up" data-aos-delay="100">
      <div class="featured-item position-relative">
        <div class="icon"><i class="bi bi-calendar3"></i></div>
        <h4><a href="<?php echo $programarLink ?>" class="stretched-link">Consultar Programación Instructor</a></h4>
        <p>Apreciado instructor, ingrese con su usario y conozca su programador</p>
      </div>
    </div><!-- End Featured Item -->

    <div class="col-lg-4 d-flex" data-aos="fade-up" data-aos-delay="200">
      <div class="featured-item position-relative">
        <div class="icon"><i class="bi bi-calendar4-week icon"></i></div>
        <h4><a href="?c=aprendiz&a=aprendizview" class="stretched-link">Consultar Programación Ficha</a></h4>
        <p>Apreciado Aprendiz, ingrese el número de ficha para consultar la programación.</p>
      </div>
    </div><!-- End Featured Item -->

    <div class="col-lg-4 d-flex" data-aos="fade-up" data-aos-delay="300">
      <div class="featured-item position-relative">
        <div class="icon"><i class="bi bi-calendar-plus"></i></div>
        <h4><a href="<?php echo $adminlik  ?>" class="stretched-link">Ir a Programar</a></h4>
        <p>Ir a Programar</p>
      </div>
    </div><!-- End Featured Item -->

  </div>

</div>

</section><!-- /Hero Section -->

<!-- <section id="about" class="section about">

<div class="container" data-aos="fade-up" data-aos-delay="100">

  <div class="row gy-4">
    <div class="col-lg-6 order-1 order-lg-2">
      <img src="assets/img/about.jpg" class="img-fluid" alt="">
    </div>
    <div class="col-lg-6 order-2 order-lg-1 content">
      <h3>Voluptatem dignissimos provident</h3>
      <p class="fst-italic">
        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore
        magna aliqua.
      </p>
      <ul>
        <li><i class="bi bi-check2-all"></i> <span>Ullamco laboris nisi ut aliquip ex ea commodo consequat.</span></li>
        <li><i class="bi bi-check2-all"></i> <span>Duis aute irure dolor in reprehenderit in voluptate velit.</span></li>
        <li><i class="bi bi-check2-all"></i> <span>Ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate trideta storacalaperda mastiro dolore eu fugiat nulla pariatur.</span></li>
      </ul>
      <p>
        Ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate
        velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident
      </p>
    </div>
  </div>

</div>

</section> -->