<?php
namespace App\Utils;

class Navegacion
{
    public static function pintarNav(string $ruta = './'): void
    {
        $usuario = $_SESSION['usuario'] ?? null;
        $esAdmin = $usuario && (int)$usuario->perfil === 1;
        ?>
        <nav class="bg-gray-800 text-white p-4 mb-6 shadow">
            <div class="container mx-auto flex justify-between items-center">
                <div class="flex space-x-4 items-center">
                    <a href="<?= $ruta ?>index.php" class="font-bold text-lg text-blue-400">
                        <i class="fas fa-book-open mr-1"></i> Biblioteca
                    </a>
                    <?php if ($usuario): ?>
                        <a href="<?= $ruta ?>libros/admin.php" class="hover:text-gray-300">
                            <i class="fas fa-book mr-1"></i> Mis Libros
                        </a>
                    <?php endif; ?>
                    <?php if ($esAdmin): ?>
                        <a href="<?= $ruta ?>generos/admin.php" class="hover:text-gray-300">
                            <i class="fas fa-tags mr-1"></i> Géneros
                        </a>
                    <?php endif; ?>
                </div>
                <div class="flex items-center space-x-4">
                    <?php if ($usuario): ?>
                        <span class="text-sm text-gray-300">
                            <i class="fas fa-user mr-1"></i><?= htmlspecialchars($usuario->email) ?>
                        </span>
                        <a href="<?= $ruta ?>cerrar.php" class="bg-red-600 hover:bg-red-700 px-3 py-1 rounded text-sm">
                            <i class="fas fa-sign-out-alt mr-1"></i> Salir
                        </a>
                    <?php else: ?>
                        <a href="<?= $ruta ?>login.php" class="bg-blue-600 hover:bg-blue-700 px-3 py-1 rounded text-sm">
                            <i class="fas fa-sign-in-alt mr-1"></i> Login
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
        <?php
    }
}