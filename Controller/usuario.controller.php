<?php
require_once "model/usario.php";

class UsuarioController {
    private $model;

    public function __construct() {
        $this->model = new UsuarioModel();
    }

    public function index(){
        plantilla("sesion/login.php");
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];
    
            $db = Database::Conectar();
            $stmt = $db->prepare("SELECT * FROM usuarios WHERE cedula = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
    
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($user && password_verify($password, $user['contrasena'])) {
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_privilege'] = $user['privilegio'];
    
                // Redireccionar según el privilegio del usuario
                if ($user['privilegio'] == 1) {
                    header("Location: ?c=usuario&a=adminview");
                } else {
                    header("Location: ?c=programar&a=indexInstructor");
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
            $contraseña = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);
            $privilegio = $_POST['privilegio'];
            $db = Database::Conectar();
            $stmt = $db->prepare("INSERT INTO usuarios (nombre, apellido, cedula, contrasena, privilegio) VALUES (:nombre, :apellido, :cedula, :contrasena, :privilegio)");
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':apellido', $apellido);
            $stmt->bindParam(':cedula', $cedula);
            $stmt->bindParam(':contrasena', $contraseña);
            $stmt->bindParam(':privilegio', $privilegio);

            if($stmt->execute()) {
                redirect("?c=usuario&a=agregar_usuario", "Exito-Usuario Agregado Correctamente a la Base de datos");
            } else {
                redirect("?c=usuario&a=agregar_usuario", "Error-Usuario No Agregado Correctamente a la Base de datos");
            }
        } else {
            plantilla("admin/agregar_usuario.php");
        }
    }
    

    
}




?>