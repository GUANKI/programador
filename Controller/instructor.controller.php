<?php
require_once 'model/instructor.php';

class InstructorController {
    private $model;

    public function __construct() {
        $this->model = new InstructorModel();
    }

    public function agregar() {
        $tiposInstructores = $this->model->getTiposInstructores();
        plantilla("crud/agregar_instructores.php", ['tiposInstructores' => $tiposInstructores]);
    }
    
    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre = $_POST['nombre'];
            $apellido = $_POST['apellido'];
            $tipo_id = $_POST['tipo_id'];

            if ($this->model->agregarInstructor($nombre, $apellido, $tipo_id)) {
                
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
