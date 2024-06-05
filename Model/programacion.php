<?php

class ProgramacionModel {
    private $db;

    public function __construct() {
        $this->db = Database::Conectar();
    }

    public function obtenerProgramacionesPorFicha($ficha) {
        $stmt = $this->db->prepare("SELECT * FROM programaciones WHERE ficha = :ficha");
        $stmt->bindParam(':ficha', $ficha);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function fichaExiste($ficha) {
        $stmt = $this->db->prepare("SELECT * FROM formaciones WHERE ficha = :ficha");
        $stmt->bindParam(':ficha', $ficha);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerConflictosDeInstructor($instructorId, $start, $end) {
        $stmt = $this->db->prepare("SELECT * FROM programaciones WHERE instructor_id = :instructor_id AND (
            (start <= :start AND end >= :start) OR 
            (start <= :end AND end >= :end) OR 
            (start >= :start AND end <= :end)
        )");
        $stmt->bindParam(':instructor_id', $instructorId);
        $stmt->bindParam(':start', $start);
        $stmt->bindParam(':end', $end);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insertarProgramacion($ficha, $instructorId, $start, $end) {
        $stmt = $this->db->prepare("INSERT INTO programaciones (ficha, instructor_id, start, end) VALUES (:ficha, :instructor_id, :start, :end)");
        $stmt->bindParam(':ficha', $ficha);
        $stmt->bindParam(':instructor_id', $instructorId);
        $stmt->bindParam(':start', $start);
        $stmt->bindParam(':end', $end);
        return $stmt->execute();
    }

    public function actualizarHorasAcumuladas($instructorId, $mes, $anio, $horas) {
        $stmt = $this->db->prepare("INSERT INTO horas_acumuladas (instructor_id, mes, año, horas_acumuladas)
                                  VALUES (:instructor_id, :mes, :año, :horas_acumuladas)
                                  ON DUPLICATE KEY UPDATE horas_acumuladas = horas_acumuladas + :horas_acumuladas");
        $stmt->bindParam(':instructor_id', $instructorId);
        $stmt->bindParam(':mes', $mes);
        $stmt->bindParam(':año', $anio);
        $stmt->bindParam(':horas_acumuladas', $horas);
        return $stmt->execute();
    }
}
?>
