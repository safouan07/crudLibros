<?php
session_start();
require_once __DIR__ . '/../../vendor/autoload.php';
use App\Utils\Navegacion;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acceso Prohibido</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen">
    <?php Navegacion::pintarNav('../'); ?>
    <div class="max-w-lg mx-auto mt-16 bg-white p-8 rounded-lg shadow text-center">
        <i class="fas fa-ban text-5xl text-red-600 mb-4"></i>
        <h1 class="text-2xl font-bold text-gray-800 mb-2">Acceso Prohibido</h1>
        <p class="text-gray-600 mb-6">No tienes permisos para acceder o modificar este libro.</p>
        <a href="../index.php" class="bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700">Volver al Inicio</a>
    </div>
</body>
</html>