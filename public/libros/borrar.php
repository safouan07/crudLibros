<?php
session_start();
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Bd\Libro;

if (!isset($_SESSION['usuario']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../login.php');
    exit;
}

$id = (int) ($_POST['id'] ?? 0);
$libro = Libro::getLibroById($id);

if (!$libro || (int)$libro->usuario_id !== (int)$_SESSION['usuario']->id) {
    header('Location: prohibido.php');
    exit;
}

if ($libro->portada && $libro->portada !== 'image.png' && file_exists(__DIR__ . '/../imagenes/' . $libro->portada)) {
    unlink(__DIR__ . '/../imagenes/' . $libro->portada);
}

Libro::delete($id);
$_SESSION['mensaje'] = 'Libro eliminado correctamente.';
header('Location: admin.php');