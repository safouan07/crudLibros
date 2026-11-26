<?php
namespace App\Bd;

use PDO;
use PDOException;
use Dotenv\Dotenv;

class Conexion
{
    private static ?PDO $conexion = null;

    public static function getConexion(): PDO
    {
        if (self::$conexion === null) {
            self::setConexion();
        }
        return self::$conexion;
    }

    private static function setConexion(): void
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();

        $usuario = $_ENV['USUARIO'];
        $pass    = $_ENV['PASSWORD'];
        $bd      = $_ENV['DBNAME'];
        $host    = $_ENV['HOST'];
        $puerto  = $_ENV['PUERTO'];

        $dsn = "mysql:host=$host;port=$puerto;dbname=$bd;charset=utf8mb4";
        $opciones = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_PERSISTENT => true
        ];

        try {
            self::$conexion = new PDO($dsn, $usuario, $pass, $opciones);
        } catch (PDOException $ex) {
            die("Error en la conexión: " . $ex->getMessage());
        }
    }
}