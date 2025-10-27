<?php
/* =========================================================
 * Helpers locales (lowerCamelCase)
 * ========================================================= */

function dividirNombreApellidos(string $nombreCompleto): array
{
    $norm = preg_replace('/\s+/u', ' ', trim($nombreCompleto));
    if ($norm === '') return ['nombre' => '', 'apellido1' => '', 'apellido2' => ''];
    $parts = explode(' ', $norm);

    if (count($parts) >= 3) {
        $apellido2 = array_pop($parts);
        $apellido1 = array_pop($parts);
        $nombre    = implode(' ', $parts);
        return ['nombre' => $nombre, 'apellido1' => $apellido1, 'apellido2' => $apellido2];
    } elseif (count($parts) === 2) {
        return ['nombre' => $parts[0], 'apellido1' => $parts[1], 'apellido2' => ''];
    } else {
        return ['nombre' => $parts[0], 'apellido1' => '', 'apellido2' => ''];
    }
}

/**
 * Guarda imagen PNG en /uploads/imgs/ con base64 (≤ 1MB).
 * Nombre: <idUsuario>_<uniqid>_<YYYYMMDDHHMMSS>.png
 * Retorna: ['ok'=>true,'ruta'=>'/uploads/imgs/xxx.png'] o ['ok'=>false,'msg'=>'...']
 */
function guardarImagenUsuarioBase64Simple(int $idUsuario, string $base64): array
{
    // acepta dataURL o base64 crudo
    if (strpos($base64, 'base64,') !== false) {
        $base64 = explode('base64,', $base64)[1];
    }

    $bytes = base64_decode($base64, true);
    if ($bytes === false) {
        return ['ok' => false, 'msg' => 'Base64 inválido'];
    }

    // límite 1MB (coincide con el frontend)
    if (strlen($bytes) > 1 * 1024 * 1024) {
        return ['ok' => false, 'msg' => 'La imagen supera 1 MB'];
    }

    $dir = realpath(__DIR__ . '/../../');
    if ($dir === false) return ['ok' => false, 'msg' => 'Ruta base inválida'];

    $destDir = $dir . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'imgs';
    if (!is_dir($destDir) && !mkdir($destDir, 0775, true)) {
        return ['ok' => false, 'msg' => 'No se pudo crear la carpeta uploads/imgs'];
    }

    $stamp = gmdate('YmdHis');
    $file  = $idUsuario . '_' . uniqid('', true) . '_' . $stamp . '.png';
    $abs   = $destDir . DIRECTORY_SEPARATOR . $file;

    $ok = file_put_contents($abs, $bytes);
    if ($ok === false) {
        return ['ok' => false, 'msg' => 'No se pudo guardar la imagen'];
    }

    @chmod($abs, 0664);
    return ['ok' => true, 'ruta' => 'uploads/imgs/' . $file];
}

function normalizarBool($v): int
{
    if (is_bool($v)) return $v ? 1 : 0;
    $x = strtolower(trim((string)$v));
    return in_array($x, ['1', 'true', 't', 'si', 'sí', 'on', 'activo'], true) ? 1 : 0;
}
