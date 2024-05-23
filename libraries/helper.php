<?php
require_once "controller/instructor.controller.php";
function plantilla($ruta, $parametros=[]){
    extract($parametros);
    include_once "view/static/header.php";
    include_once "view/$ruta";
    include_once "view/static/footer.php";
}
// function checksession(){
//     return isset($_SESSION["nombreUsuario"]) ? true : false;
// }

function redirect($link, $message = null) {
    if ($message) {
        setcookie("notificacion", $message, time() + 5, "/");
    }
    header("location: $link");
    exit;
}


// function obtenerIdClienteActual()
//     {



//         if (isset($_SESSION['cliente_id'])) {
//             return $_SESSION['cliente_id'];
//         } else {
//             return null;
//         }
//     }