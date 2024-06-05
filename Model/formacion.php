<?php
class FormacionModel {
    private $db;

    public function __construct() {
        $this->db = Database::Conectar();
    }

    public function agregarFormacion($nombre, $nivel, $ambiente, $ficha, $horario = null) {
        if ($this->fichaExiste($ficha)){
            return false;
        }else{
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

    public function fichaExiste($numero_ficha) {
        $query = $this->db->prepare("
            SELECT COUNT(*) 
            FROM programas_formacion 
            WHERE LOWER(numero_ficha) = LOWER(:numero_ficha) 
        ");
        $query->bindParam(':numero_ficha', $numero_ficha);
        $query->execute();
        return $query->fetchColumn() > 0;
    }

}

?>