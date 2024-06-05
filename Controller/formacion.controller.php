<?php
require_once 'model/formacion.php';
class FormacionController {
    private $model;

    public function __construct() {
        $this->model = new FormacionModel();
    }

    public function inicio(){
        plantilla("formaciones/agregar_formacion.php");
    }

    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre = $_POST['nombre'];
            $nivel = $_POST['nivel'];
            $ambiente = $_POST['ambiente'];
            $ficha = $_POST['ficha'];
            $horario = isset($_POST['horario']) ? $_POST['horario'] : null;

            if ($this->model->agregarFormacion($nombre, $nivel, $ambiente, $ficha, $horario)) {
                // Redirigir o mostrar mensaje de éxito
                redirect("?c=formacion&a=inicio", "Exito-Formación Agregada Correctamente");
            } else {
                // Redirigir o mostrar mensaje de error
                redirect("?c=formacion&a=inicio", "Error-Ficha ya Existe");
            }
        } else {
            // Mostrar formulario
            plantilla("crud/agregar_formacion.php");
        }
    }
}

?>