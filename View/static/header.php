<?php

$textheader= "";
// Verificar si el usuario ha iniciado sesión y tiene el privilegio adecuado
if (isset($_SESSION['user_id'])) {
    // Determinar el enlace según el privilegio del usuario
    if ($_SESSION['user_privilege'] == 1) {
        $programarLink = "?c=usuario&a=adminview";
        $textheader="Administrador"; // Enlace para administradores
    } else {
        $programarLink = "?c=programar&a=index"; // Enlace para usuarios normales
        $textheader ="Ver Horario Instructor";
    }
} else {
  
    $programarLink = "?c=usuario&a=login";
    $textheader="Programar";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script> -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <title>Programador SENA</title>
    <link rel="stylesheet" href="Assets/css/happyfox.devs.css" />
    <script src="Assets/js/happyfox.devs.js"></script>


     <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">


</head>
<body class="index-page">

  <header id="header" class="header sticky-top">

    

    <div class="branding">

      <div class="container position-relative d-flex align-items-center justify-content-between">
        <a href="?" class="logo d-flex align-items-center">
          <!-- Uncomment the line below if you also wish to use an image logo -->
          <img src="Assets/img/logoSena.png" alt="">
          <h1 class="sitename">Programador CDATH<br></h1>
        </a>

        <nav id="navmenu" class="navmenu">
          <ul>
            <li><a href="?" class="active">Inicio</a></li>
            <li><a href="about.html">Consultar Programación</a></li>
            <li><a href="<?php echo $programarLink; ?>"><?php
            echo $textheader
            ?></a></li>
            <?php
            if (isset($_SESSION['user_id'])) {
              // Mostrar el botón de Cerrar Sesión
              echo '<a href="?c=usuario&a=logout" class="text-danger">Cerrar Sesión</a>';
          }
            ?>
          </ul>
          <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>

      </div>

    </div>

  </header>

  <main class="main">