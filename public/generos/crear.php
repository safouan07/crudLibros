<?php
session_start();
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Bd\Genero;
use App\Utils\Navegacion;
use App\Utils\Validacion;

if (!isset($_SESSION['usuario']) || (int)$_SESSION['usuario']->perfil !== 1) {
    header('Location: ../libros/prohibido.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = Validacion::sanearCadenas($_POST['nombre'] ?? '');
    $color  = Validacion::sanearCadenas($_POST['color'] ?? '');

    $ok1 = Validacion::longitudCampoValid('nombre', $nombre, 3, 50);
    $ok2 = Validacion::isColorValido($color);

    if ($ok1 && Genero::existeGenero($nombre)) {
        $_SESSION['err_nombre'] = '*** Error, ya existe un género con ese nombre.';
        $ok1 = false;
    }

    if ($ok1 && $ok2) {
        (new Genero())
            ->setNombre($nombre)
            ->setColor($color)
            ->create();

        $_SESSION['mensaje'] = 'Género creado correctamente.';
        header('Location: admin.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Género</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen">
    <?php Navegacion::pintarNav('../'); ?>

    <div class="max-w-lg mx-auto bg-white p-8 rounded-lg shadow">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Nuevo Género</h1>

        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                <input type="text" name="nombre" class="w-full border border-gray-300 rounded px-3 py-2">
                <?php Validacion::pintarError('err_nombre'); ?>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Color</label>
                <input type="color" name="color" value="#2563EB" class="w-20 h-10 border border-gray-300 rounded">
                <?php Validacion::pintarError('err_color'); ?>
            </div>
            <div class="flex justify-end space-x-3 pt-4">
                <a href="admin.php" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded">Cancelar</a>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded">Guardar</button>
            </div>
        </form>
    </div>
</body>
</html>