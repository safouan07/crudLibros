<?php
namespace App\Bd;

use PDO;

class Libro
{
    private string $titulo;
    private string $sinopsis;
    private string $disponible;
    private string $portada;
    private int $genero_id;
    private int $usuario_id;

    public function create(): void
    {
        $con = Conexion::getConexion();$sql = "INSERT INTO libros (titulo, sinopsis, disponible, portada, genero_id, usuario_id)
                VALUES (:t, :s, :d, :p, :g, :u)";
        $stmt =$con->prepare($sql);$stmt->execute([
            ':t' => $this->titulo,
            ':s' => $this->sinopsis,
            ':d' => $this->disponible,
            ':p' => $this->portada,
            ':g' => $this->genero_id,
            ':u' => $this->usuario_id
        ]);
    }

    public static function readDisponibles(): array
    {
        $con = Conexion::getConexion();$sql = "SELECT libros.*, generos.nombre AS genero, generos.color, usuarios.email
                FROM libros
                INNER JOIN generos ON libros.genero_id = generos.id
                INNER JOIN usuarios ON libros.usuario_id = usuarios.id
                WHERE libros.disponible = 'Si'
                ORDER BY libros.created_at DESC";
        $stmt = $con->query($sql);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function readByUsuario(int $usuarioId): array
    {
        $con = Conexion::getConexion();$sql = "SELECT libros.*, generos.nombre AS genero, generos.color
                FROM libros
                INNER JOIN generos ON libros.genero_id = generos.id
                WHERE libros.usuario_id = :uid
                ORDER BY libros.created_at DESC";
        $stmt = $con->prepare($sql);
        $stmt->execute([':uid' =>$usuarioId]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function getLibroById(int $id): ?object
    {
        $con = Conexion::getConexion();
        $stmt =$con->prepare("SELECT * FROM libros WHERE id = :id");
        $stmt->execute([':id' =>$id]);
        $res =$stmt->fetch(PDO::FETCH_OBJ);
        return $res ?: null;
    }

    public function update(int $id): void
    {
        $con = Conexion::getConexion();$sql = "UPDATE libros
                SET titulo = :t, sinopsis = :s, disponible = :d, portada = :p, genero_id = :g
                WHERE id = :id";
        $stmt =$con->prepare($sql);$stmt->execute([
            ':t'  => $this->titulo,
            ':s'  => $this->sinopsis,
            ':d'  => $this->disponible,
            ':p'  => $this->portada,
            ':g'  => $this->genero_id,
            ':id' => $id
        ]);
    }

    public static function delete(int $id): void
    {
        $con = Conexion::getConexion();
        $stmt =$con->prepare("DELETE FROM libros WHERE id = :id");
        $stmt->execute([':id' =>$id]);
    }

    public function setTitulo(string $titulo): self {$this->titulo = $titulo; return$this; }
    public function setSinopsis(string $sinopsis): self {$this->sinopsis = $sinopsis; return$this; }
    public function setDisponible(string $disponible): self {$this->disponible = $disponible; return$this; }
    public function setPortada(string $portada): self {$this->portada = $portada; return$this; }
    public function setGeneroId(int $genero_id): self {$this->genero_id = $genero_id; return$this; }
    public function setUsuarioId(int $usuario_id): self {$this->usuario_id = $usuario_id; return$this; }
}