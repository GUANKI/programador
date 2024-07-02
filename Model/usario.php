<?php

class UsuarioModel{
    private $db;

    public function __construct() {
        $this->db = Database::Conectar();
    }

    // public function agregarusuario($nombre, $apellido, $cedula, $contraseña, $privilegio){
    //     $stmt = $db->prepare("INSERT INTO usuarios (nombre, apellido, cedula, contrasena, privilegio) VALUES (:nombre, :apellido, :cedula, :contraseña, :privilegio)");
    //         $stmt->bindParam(':nombre', $nombre);
    //         $stmt->bindParam(':apellido', $apellido);
    //         $stmt->bindParam(':cedula', $cedula);
    //         $stmt->bindParam(':contraseña', $contraseña);
    //         $stmt->bindParam(':privilegio', $privilegio);
            
    //         if($stmt->execute()) {
    //             header("Location: ?c=usuario&a=login");
    //         }else {
    //             echo "<script>alert('Error al agregar usuario');</script>";
    //             plantilla("admin/agregar_usuario.php");
    //         }
    // }

}

?>