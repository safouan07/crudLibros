<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';

use App\Bd\Usuario;
use App\Utils\Navegacion;
use App\Utils\Validacion;

if (isset($_SESSION['usuario'])) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = Validacion::sanearCadenas($_POST['email'] ?? '');
    $pass  = $_POST['password'] ?? '';

    $usuario = Usuario::login($email, $pass);
    if ($usuario) {
        unset($usuario->password);
        $_SESSION['usuario'] = $usuario;
        header('Location: libros/admin.php');
        exit;
    } else {
        $_SESSION['err_login'] = "*** Error, email o contraseña incorrectos.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen">
    <?php Navegacion::pintarNav('./'); ?>

    <div class="max-w-md mx-auto mt-10 bg-white p-8 rounded-lg shadow">
        <h1 class="text-2xl font-bold text-gray-800 mb-6 text-center">Iniciar Sesión</h1>

        <?php Validacion::pintarError('err_login'); ?>

        <form method="POST" class="space-y-4 mt-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" required class="w-full border border-gray-300 rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
                <input type="password" name="password" required class="w-full border border-gray-300 rounded px-3 py-2">
            </div>
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded">
                Entrar
            </button>
        </form>
    </div>
</body>
</html>