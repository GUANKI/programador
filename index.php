<?php
require_once 'libraries/helper.php';
require_once "Model/database.php";


$controller = 'inicio';
function showErrorPage($message) {
    require "view/error/error.php";
}
session_start();

if (!isset($_REQUEST['c'])) {
    require_once "Controller/$controller.controller.php";
    $controller = ucwords($controller) . 'Controller';
    $controller = new $controller;
    $controller->Index();
} else {
    $controller = strtolower($_REQUEST['c']);
    $accion = isset($_REQUEST['a']) ? $_REQUEST['a'] : 'Index';

    // Ruta del controlador
    $controllerFile = "Controller/$controller.controller.php";

    if (file_exists($controllerFile)) {
        // Incluye el archivo del controlador
        require_once $controllerFile;

        // Construye el nombre de la clase del controlador
        $controllerClassName = ucfirst($controller) . 'Controller';

        // Verifica si la clase del controlador existe
        if (class_exists($controllerClassName)) {
            // Instancia el controlador
            $controller = new $controllerClassName;

            // Verifica si el método de la acción existe en el controlador
            if (method_exists($controller, $accion)) {
                // Ejecuta la acción
                $controller->$accion();
            } else {
                // Manejo de error: acción no válida
                showErrorPage("Error 404: Página no encontrada");
            }
        } else {
            // Manejo de error: controlador no válido
            showErrorPage("Error 404: Página no encontrada");
        }
    } else {
        // Manejo de error: archivo de controlador no encontrado
        showErrorPage("Error 404: Página no encontrada");
    }
    // Redirección a index.php si ocurre un error o la página no se encuentra
if (!isset($_REQUEST['c']) || !file_exists($controllerFile)) {
    header("Location: index.php");
    exit();
}
}