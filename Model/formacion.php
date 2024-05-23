<?php
class FormacionModel {
    private $db;

    public function __construct() {
        $this->db = Database::Conectar();
    }

    public function agregarFormacion($nombre, $nivel, $ambiente, $ficha, $horario = null) {
        $query = $this->db->prepare("
            INSERT INTO programas_formacion (nombre, nivel_formacion, ambiente, numero_ficha, horario) 
            VALUES (:nombre, :nivel, :ambiente, :ficha, :horario)
        ");
        $query->bindParam(':nombre', $nombre);
        $query->bindParam(':nivel', $nivel);
        $query->bindParam(':ambiente', $ambiente);
        $query->bindParam(':ficha', $ficha);
        $query->bindParam(':horario', $horario);
        return $query->execute();
    }
}

?>