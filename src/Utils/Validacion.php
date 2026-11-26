<?php
namespace App\Utils;

class Validacion
{
    public static function sanearCadenas(string $cad): string
    {
        return htmlspecialchars(trim($cad));
    }

    public static function longitudCampoValid(string $nomCampo, string $valor, int $min, int $max): bool
    {
        if (mb_strlen($valor) < $min || mb_strlen($valor) > $max) {
            $_SESSION["err_$nomCampo"] = "*** Error, el campo $nomCampo debe tener entre $min y $max caracteres.";
            return false;
        }
        return true;
    }

    public static function isImagenValida(string $tipo): bool
    {
        $imagenesMime = [
            'image/gif',
            'image/png',
            'image/jpeg',
            'image/bmp',
            'image/webp'
        ];
        return in_array($tipo, $imagenesMime);
    }

    public static function isColorValido(string $color): bool
    {
        if (!preg_match('/^#[0-9A-Fa-f]{6}$/', $color)) {
            $_SESSION['err_color'] = "*** Error, el color seleccionado no es válido.";
            return false;
        }
        return true;
    }

    public static function pintarError(string $nombreError): void
    {
        if (isset($_SESSION[$nombreError])) {
            echo "<p class='text-red-600 italic text-xs mt-1'>{$_SESSION[$nombreError]}</p>";
            unset($_SESSION[$nombreError]);
        }
    }
}