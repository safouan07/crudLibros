<?php
namespace App\Bd;

use PDO;

class Usuario
{
    public static function login(string $email, string$password): ?object
    {
        $con = Conexion::getConexion();
        $stmt =$con->prepare("SELECT * FROM usuarios WHERE email = :e");
        $stmt->execute([':e' =>$email]);
        $usuario =$stmt->fetch(PDO::FETCH_OBJ);

        if ($usuario && password_verify($password,$usuario->password)) {
            return $usuario;
        }
        return null;
    }
}