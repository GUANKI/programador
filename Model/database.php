<?php
class Database
{
    private static $host = "localhost";
    private static $dbname = "programador";
    private static $charset = "utf8";
    private static $username = "root"; 
    private static $password = ""; 

    public static function Conectar()
    {
        try {
            $pdo = new PDO("mysql:host=" . self::$host . ";dbname=" . self::$dbname . ";charset=" . self::$charset, self::$username, self::$password);
            // Configuración de PDO para manejar errores
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            // Manejo de errores de conexión
            die("Error de conexión: " . $e->getMessage());
        }
    }
}