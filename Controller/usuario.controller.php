<?php

class UsuarioController {

    

    public function index(){
        plantilla("sesion/login.php");
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];
    
            $db = Database::Conectar();
            $stmt = $db->prepare("SELECT * FROM usuarios WHERE cedula = :email AND contraseña = :password");
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password);
            $stmt->execute();
    
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($user) {
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_privilege'] = $user['privilegio'];
    
                // Redireccionar según el privilegio del usuario
                if ($user['privilegio'] == 1) {
                    redirect("?c=usuario&a=adminview", "Exito-Sesión Iniciada");
                } else {
                    redirect("?c=programar&a=indexInstructor", "Exito-Sesión Iniciada");
                }
                exit; // Terminar la ejecución después de redirigir
            } else {
                // Mostrar mensaje de error en caso de login fallido
                echo "<script>alert('Usuario o contraseña incorrectos');</script>";
                plantilla("sesion/login.php");
                exit; // Terminar la ejecución si hay error
            }
        } else {
            plantilla("sesion/login.php");
        }
    }
    
    
    
    
    public function logout() {
        session_start();
        session_unset();
        session_destroy();
        redirect("?c=usuario&a=login", "Exito-Sesión Cerrada");
    }
    

    public function adminview(){
        plantilla("admin/inicio.php");
    }

    public function agregar_usuario() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'];
            $apellido = $_POST['apellido'];
            $cedula = $_POST['cedula'];
            $contraseña = $_POST['contraseña'];
            $privilegio = $_POST['privilegio'];
    
            $db = Database::Conectar();
            $stmt = $db->prepare("INSERT INTO usuarios (nombre, apellido, cedula, contraseña, privilegio) VALUES (:nombre, :apellido, :cedula, :contraseña, :privilegio)");
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':apellido', $apellido);
            $stmt->bindParam(':cedula', $cedula);
            $stmt->bindParam(':contraseña', $contraseña);
            $stmt->bindParam(':privilegio', $privilegio);
    
            if ($stmt->execute()) {
                echo "<script>alert('Usuario agregado exitosamente');</script>";
            } else {
                echo "<script>alert('Error al agregar usuario');</script>";
            }
    
            // Redirigir a la vista de administración después de agregar el usuario
            header("Location: ?c=admin&a=index");
            exit;
        } else {
            // Mostrar el formulario si no se ha enviado
            plantilla("admin/agregar_usuario.php");
        }
    }
    
}




?>