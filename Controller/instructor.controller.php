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
                // Redirigir o mostrar mensaje de éxito
                redirect("?c=instructor&a=agregar", "Exito-Instructor Agregado");
                // echo "Instructor agregado con éxito";
            } else {
                // Redirigir o mostrar mensaje de error
                redirect("?c=instructor&a=agregar", "Error-Instructor ya Existe");
            }
        } else {
            // Mostrar formulario
            plantilla("crud/agregar_instructores.php");
        }
    
    }
}
