<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';

use App\Bd\Libro;
use App\Utils\Navegacion;
use Carbon\Carbon;

Carbon::setLocale('es');
$libros = Libro::readDisponibles();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Biblioteca - Inicio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen">
    <?php Navegacion::pintarNav('./'); ?>

    <div class="container mx-auto px-4 pb-12">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Catálogo de Libros Disponibles</h1>

        <?php if (empty($libros)): ?>
            <div class="bg-white p-6 rounded-lg shadow text-center text-gray-500">
                No hay libros disponibles actualmente.
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <?php foreach ($libros as $libro): ?>
                    <div class="bg-white rounded-lg shadow overflow-hidden flex flex-col">
                        <img src="./imagenes/<?= htmlspecialchars($libro->portada ?: 'image.png') ?>" alt="Portada" onerror="this.onerror=null;this.src='./imagenes/image.png'" class="w-full h-56 object-cover">
                        <div class="p-5 flex-1 flex flex-col justify-between">
                            <div>
                                <span class="inline-block text-white text-xs font-semibold px-2.5 py-0.5 rounded mb-2"
                                      style="background-color: <?= htmlspecialchars($libro->color) ?>">
                                    <?= htmlspecialchars($libro->genero) ?>
                                </span>
                                <h2 class="text-xl font-bold text-gray-800 mb-2"><?= htmlspecialchars($libro->titulo) ?></h2>
                                <p class="text-gray-600 text-sm"><?= htmlspecialchars($libro->sinopsis) ?></p>
                            </div>
                            <div class="mt-4 pt-3 border-t border-gray-100 flex justify-between text-xs text-gray-400">
                                <span><i class="fas fa-user mr-1"></i><?= htmlspecialchars($libro->email) ?></span>
                                <span><i class="far fa-clock mr-1"></i><?= Carbon::parse($libro->created_at)->diffForHumans() ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>