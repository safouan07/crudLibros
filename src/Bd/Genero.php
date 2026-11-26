<?php
namespace App\Bd;

use PDO;

class Genero
{
    private string $nombre;
    private string $color;

    public function create(): void
    {
        $con = Conexion::getConexion();
        $stmt = $con->prepare("INSERT INTO generos (nombre, color) VALUES (:n, :c)");
        $stmt->execute([
            ':n' => $this->nombre,
            ':c' => $this->color
        ]);
    }

    public static function read(): array
    {
        $con = Conexion::getConexion();
        $stmt = $con->query("SELECT * FROM generos ORDER BY nombre");
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function getGeneroById(int $id): ?object
    {
        $con = Conexion::getConexion();
        $stmt = $con->prepare("SELECT * FROM generos WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $res = $stmt->fetch(PDO::FETCH_OBJ);
        return $res ?: null;
    }

    public function update(int $id): void
    {
        $con = Conexion::getConexion();
        $stmt = $con->prepare("UPDATE generos SET nombre = :n, color = :c WHERE id = :id");
        $stmt->execute([
            ':n'  => $this->nombre,
            ':c'  => $this->color,
            ':id' => $id
        ]);
    }

    public static function delete(int $id): void
    {
        $con = Conexion::getConexion();
        $stmt = $con->prepare("DELETE FROM generos WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }

    public static function existeGenero(string $nombre, ?int $id = null): bool
    {
        $con = Conexion::getConexion();
        if ($id !== null) {
            $stmt = $con->prepare("SELECT id FROM generos WHERE nombre = :n AND id != :id");
            $stmt->execute([':n' => $nombre, ':id' => $id]);
        } else {
            $stmt = $con->prepare("SELECT id FROM generos WHERE nombre = :n");
            $stmt->execute([':n' => $nombre]);
        }
        return (bool) $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function setNombre(string $nombre): self { $this->nombre = $nombre; return $this; }
    public function setColor(string $color): self { $this->color = $color; return $this; }
}