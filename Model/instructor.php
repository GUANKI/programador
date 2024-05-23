<?php
class InstructorModel {
    private $db;

    public function __construct() {
        $this->db = Database::Conectar();
    }

    public function getTiposInstructores() {
        $query = $this->db->prepare("SELECT * FROM tipos_instructores");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function instructorExiste($nombre, $apellido) {
        $query = $this->db->prepare("
            SELECT COUNT(*) 
            FROM instructores 
            WHERE LOWER(nombre) = LOWER(:nombre) 
            AND LOWER(apellido) = LOWER(:apellido)
        ");
        $query->bindParam(':nombre', $nombre);
        $query->bindParam(':apellido', $apellido);
        $query->execute();
        return $query->fetchColumn() > 0;
    }

    public function agregarInstructor($nombre, $apellido, $tipo_id) {
        if ($this->instructorExiste($nombre, $apellido)) {
            return false; // Instructor ya existe
        } else {
            $query = $this->db->prepare("
                INSERT INTO instructores (nombre, apellido, tipo_id) 
                VALUES (:nombre, :apellido, :tipo_id)
            ");
            $query->bindParam(':nombre', $nombre);
            $query->bindParam(':apellido', $apellido);
            $query->bindParam(':tipo_id', $tipo_id);
            return $query->execute();
        }
    }
}
