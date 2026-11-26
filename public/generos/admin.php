<?php
session_start();
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Bd\Genero;
use App\Utils\Navegacion;

if (!isset($_SESSION['usuario']) || (int)$_SESSION['usuario']->perfil !== 1) {
    header('Location: ../libros/prohibido.php');
    exit;
}

$generos = Genero::read();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Géneros</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen">
    <?php Navegacion::pintarNav('../'); ?>

    <div class="max-w-4xl mx-auto px-4 pb-12">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Gestión de Géneros</h1>
            <a href="crear.php" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                <i class="fas fa-plus mr-1"></i> Nuevo Género
            </a>
        </div>

        <?php if (isset($_SESSION['mensaje'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <?= $_SESSION['mensaje']; unset($_SESSION['mensaje']); ?>
            </div>
        <?php endif; ?>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-800 text-white text-sm">
                        <th class="p-3">ID</th>
                        <th class="p-3">Nombre</th>
                        <th class="p-3">Color</th>
                        <th class="p-3 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-sm">
                    <?php foreach ($generos as $g): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="p-3"><?= $g->id ?></td>
                            <td class="p-3 font-semibold"><?= htmlspecialchars($g->nombre) ?></td>
                            <td class="p-3">
                                <span class="px-3 py-1 rounded text-white text-xs font-mono" style="background-color: <?= htmlspecialchars($g->color) ?>">
                                    <?= htmlspecialchars($g->color) ?>
                                </span>
                            </td>
                            <td class="p-3 text-center space-x-2">
                                <a href="editar.php?id=<?= $g->id ?>" class="text-yellow-600 hover:text-yellow-800">
                                    <i class="fas fa-edit text-lg"></i>
                                </a>
                                <form action="borrar.php" method="POST" class="inline" onsubmit="return confirm('¿Eliminar este género?');">
                                    <input type="hidden" name="id" value="<?= $g->id ?>">
                                    <button type="submit" class="text-red-600 hover:text-red-800">
                                        <i class="fas fa-trash text-lg"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>