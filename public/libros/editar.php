<?php
session_start();
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Bd\Libro;
use App\Bd\Genero;
use App\Utils\Navegacion;
use App\Utils\Validacion;

if (!isset($_SESSION['usuario'])) {
    header('Location: ../login.php');
    exit;
}

$id = (int) ($_GET['id'] ?? 0);
$libro = Libro::getLibroById($id);

if (!$libro || (int)$libro->usuario_id !== (int)$_SESSION['usuario']->id) {
    header('Location: prohibido.php');
    exit;
}

$generos = Genero::read();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo     = Validacion::sanearCadenas($_POST['titulo'] ?? '');
    $sinopsis   = Validacion::sanearCadenas($_POST['sinopsis'] ?? '');
    $disponible = ($_POST['disponible'] ?? 'No') === 'Si' ? 'Si' : 'No';
    $generoId   = (int) ($_POST['genero_id'] ?? 0);

    $ok1 = Validacion::longitudCampoValid('titulo', $titulo, 2, 200);
    $ok2 = Validacion::longitudCampoValid('sinopsis', $sinopsis, 5, 3000);
    $ok3 = ($generoId > 0 && Genero::getGeneroById($generoId) !== null);
    if (!$ok3) {
        $_SESSION['err_genero'] = '*** Error, selecciona un género válido.';
    }

    $nombrePortada = $libro->portada ?: 'image.png';
    $okImg = true;

    if (isset($_FILES['portada']) && $_FILES['portada']['error'] === UPLOAD_ERR_OK) {
        if (Validacion::isImagenValida($_FILES['portada']['type'])) {
            if ($nombrePortada !== 'image.png' && file_exists(__DIR__ . '/../imagenes/' . $nombrePortada)) {
                unlink(__DIR__ . '/../imagenes/' . $nombrePortada);
            }
            $ext = pathinfo($_FILES['portada']['name'], PATHINFO_EXTENSION);
            $nombrePortada = uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['portada']['tmp_name'], __DIR__ . '/../imagenes/' . $nombrePortada);
        } else {
            $_SESSION['err_portada'] = '*** Error, el archivo debe ser una imagen válida.';
            $okImg = false;
        }
    }

    if ($ok1 && $ok2 && $ok3 && $okImg) {
        (new Libro())
            ->setTitulo($titulo)
            ->setSinopsis($sinopsis)
            ->setDisponible($disponible)
            ->setPortada($nombrePortada)
            ->setGeneroId($generoId)
            ->update($id);

        $_SESSION['mensaje'] = 'Libro actualizado correctamente.';
        header('Location: admin.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Libro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen">
    <?php Navegacion::pintarNav('../'); ?>

    <div class="max-w-xl mx-auto bg-white p-8 rounded-lg shadow">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Editar Libro</h1>

        <form method="POST" enctype="multipart/form-data" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Título</label>
                <input type="text" name="titulo" value="<?= htmlspecialchars($libro->titulo) ?>" class="w-full border border-gray-300 rounded px-3 py-2">
                <?php Validacion::pintarError('err_titulo'); ?>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Género</label>
                <select name="genero_id" class="w-full border border-gray-300 rounded px-3 py-2">
                    <?php foreach ($generos as $g): ?>
                        <option value="<?= $g->id ?>" <?= $g->id == $libro->genero_id ? 'selected' : '' ?>>
                            <?= htmlspecialchars($g->nombre) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php Validacion::pintarError('err_genero'); ?>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Sinopsis</label>
                <textarea name="sinopsis" rows="4" class="w-full border border-gray-300 rounded px-3 py-2"><?= htmlspecialchars($libro->sinopsis) ?></textarea>
                <?php Validacion::pintarError('err_sinopsis'); ?>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Disponible</label>
                <div class="flex space-x-4 text-sm">
                    <label><input type="radio" name="disponible" value="Si" <?= $libro->disponible === 'Si' ? 'checked' : '' ?>> Sí</label>
                    <label><input type="radio" name="disponible" value="No" <?= $libro->disponible === 'No' ? 'checked' : '' ?>> No</label>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Portada</label>
                <img src="../imagenes/<?= htmlspecialchars($libro->portada ?: 'image.png') ?>" alt="Portada" onerror="this.onerror=null;this.src='../imagenes/image.png'" class="w-24 h-32 object-cover rounded mb-2">
                <input type="file" name="portada" accept="image/*" class="w-full text-sm">
                <?php Validacion::pintarError('err_portada'); ?>
            </div>
            <div class="flex justify-end space-x-3 pt-4">
                <a href="admin.php" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded">Cancelar</a>
                <button type="submit" class="bg-yellow-600 hover:bg-yellow-700 text-white px-5 py-2 rounded">Actualizar</button>
            </div>
        </form>
    </div>
</body>
</html>