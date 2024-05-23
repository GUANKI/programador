<?php
class Database
{
    private static $host = "localhost";
    private static $dbname = "programador_bd";
    private static $charset = "utf8";
    private static $username = "root"; 
    private static $password = ""; 

    public static function Conectar()
    {
        try {
            $pdo = new PDO("mysql:host=" . self::$host . ";dbname=" . self::$dbname . ";charset=" . self::$charset, self::$username, self::$password);
            
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            
            die("Error de conexión: " . $e->getMessage());
        }
    }
}