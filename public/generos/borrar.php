<?php
session_start();
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Bd\Genero;

if (!isset($_SESSION['usuario']) || (int)$_SESSION['usuario']->perfil !== 1 || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../libros/prohibido.php');
    exit;
}

$id = (int) ($_POST['id'] ?? 0);
if ($id > 0) {
    Genero::delete($id);
    $_SESSION['mensaje'] = 'Género eliminado correctamente.';
}

header('Location: admin.php');