<?php
require_once 'model/instructor.php';

class InstructorController {
    private $model;

    public function __construct() {
        $this->model = new InstructorModel();
    }
    public function instructorview(){
        $instructores = $this->model->getAllInstructores();
        plantilla("instructor/inicio.php",['instructores' => $instructores]);
    }

    public function agregar() {
        $tiposInstructores = $this->model->getTiposInstructores();
        plantilla("crud/agregar_instructores.php", ['tiposInstructores' => $tiposInstructores]);
    }
    public function eliminar()
    {
        if (isset($_POST['id'])) {
            $instructorId = $_POST['id'];
    
            try {
                $resultado = $this->model->eliminarInstructor($instructorId);
                if ($resultado['success']) {
                    echo json_encode(['success' => true, 'message' => 'Instructor y registros relacionados eliminados exitosamente.']);
                } else {
                    echo json_encode(['success' => false, 'message' => $resultado['message']]);
                }
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'message' => 'Error al eliminar el instructor y registros relacionados: ' . $e->getMessage()]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'ID de instructor no proporcionado.']);
        }
    }
       // Método para actualizar los datos del instructor
       public function actualizar() {
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            $nombre = $_POST['nombre'];
            $apellido = $_POST['apellido'];
            $tipo_id = $_POST['tipo']; // Asegúrate de que 'tipo' es el ID real del tipo de instructor
            $perfil = $_POST['perfil'];

            try {
                $resultado = $this->model->actualizarInstructor($id, $nombre, $apellido, $tipo_id, $perfil);
                if ($resultado['success']) {
                    echo json_encode(['success' => true, 'message' => 'Instructor actualizado exitosamente.']);
                } else {
                    echo json_encode(['success' => false, 'message' => $resultado['message']]);
                }
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'message' => 'Error al actualizar el instructor: ' . $e->getMessage()]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'ID de instructor no proporcionado.']);
        }
    }

    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre = $_POST['nombre'];
            $apellido = $_POST['apellido'];
            $tipo_id = $_POST['tipo_id'];
            $perfil = $_POST['perfil'];

            if ($this->model->agregarInstructor($nombre, $apellido, $tipo_id, $perfil)) {
                
                redirect("?c=instructor&a=agregar", "Exito-Instructor Agregado");
                
            } else {
                
                redirect("?c=instructor&a=agregar", "Error-Instructor ya Existe");
            }
        } else {

            plantilla("crud/agregar_instructores.php");
        }
    
    }

    public function buscarinstructor(){
        $db = Database::Conectar();
        $popo = $_GET["search"];
        $quety = $db->prepare("SELECT id, nombre, apellido FROM instructores WHERE nombre LIKE :search OR apellido LIKE :search");
        $popo = "%".$popo."%";
        $quety->bindParam(":search", $popo);
        $quety->execute();
        $data = $quety->fetchAll(PDO::FETCH_OBJ);
        
        $data = array_map(function ($d) {
            return [
                "id" => $d->id,
                "nombre" => $d->nombre . " " . $d->apellido,
            ];
        }, $data);

        echo (json_encode($data));
        exit;
    }

    public function buscarinstructor2(){
        $db = Database::Conectar();
        $search = $_GET["search"];
        $query = $db->prepare("SELECT id, nombre, apellido FROM instructores WHERE nombre LIKE :search OR apellido LIKE :search");
        $search = "%".$search."%";
        $query->bindParam(":search", $search);
        $query->execute();
        $data = $query->fetchAll(PDO::FETCH_OBJ);
        
        $data = array_map(function ($d) {
            return [
                "id" => $d->id,
                "nombre" => $d->nombre . " " . $d->apellido,
            ];
        }, $data);
    
        echo json_encode($data);
        exit;
    }
    
    
}
