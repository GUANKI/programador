<?php
class InstructorModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::Conectar();
    }

    public function getTiposInstructores()
    {
        $query = $this->db->prepare("SELECT * FROM tipos_instructores");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function instructorExiste($nombre, $apellido)
    {
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

    public function agregarInstructor($nombre, $apellido, $tipo_id, $perfil)
    {
        if ($this->instructorExiste($nombre, $apellido)) {
            return false; // Instructor ya existe
        } else {
            $query = $this->db->prepare("
                INSERT INTO instructores (nombre, apellido, tipo_id, perfil) 
                VALUES (:nombre, :apellido, :tipo_id, :perfil)
            ");
            $query->bindParam(':nombre', $nombre);
            $query->bindParam(':apellido', $apellido);
            $query->bindParam(':tipo_id', $tipo_id);
            $query->bindParam(':perfil', $perfil);
            return $query->execute();
        }
    }

    public function getAllInstructores()
    {
        $query = $this->db->prepare("SELECT i.*, ti.descripcion as tipo_nombre FROM instructores i
                                    JOIN tipos_instructores ti ON i.tipo_id = ti.id");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    public function eliminarInstructor($id)
    {
    
        try {
            $sql = "DELETE FROM programaciones WHERE instructor_id = :id";
            $query = $this->db->prepare($sql);
            $query->bindParam(':id', $id, PDO::PARAM_INT);
            $query->execute();
             // Eliminar registros en horas_acumuladas relacionados con el instructor
            $sql = "DELETE FROM horas_acumuladas WHERE instructor_id = :id";
            $query = $this->db->prepare($sql);
            $query->bindParam(':id', $id, PDO::PARAM_INT);
            $query->execute();
    
            // Luego eliminar el instructor
            $sql = "DELETE FROM instructores WHERE id = :id";
            $query = $this->db->prepare($sql);
            $query->bindParam(':id', $id, PDO::PARAM_INT);
            $query->execute();
    
            // Confirmar transacción
    
            return ['success' => true];
        } catch (PDOException $e) {
            // Revertir transacción en caso de error
            $this->db->rollBack();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    public function actualizarInstructor($id, $nombre, $apellido, $tipo_id, $perfil) {
        try {
            $sql = "UPDATE instructores SET nombre = ?, apellido = ?, tipo_id = ?, perfil = ? WHERE id = ?";
            $query = $this->db->prepare($sql);
            $query->execute([$nombre, $apellido, $tipo_id, $perfil, $id]);

            if ($query->rowCount() > 0) {
                return ['success' => true];
            } else {
                return ['success' => false, 'message' => 'No se realizaron cambios.'];
            }
        } catch (PDOException $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
