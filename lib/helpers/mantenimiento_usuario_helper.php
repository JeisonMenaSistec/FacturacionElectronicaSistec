<?php
// -------------------------------------------------------------
// Utilidades locales (lowerCamelCase) – compatibles con tus helpers
// -------------------------------------------------------------
function generarGuidV4(): string {
    $data = random_bytes(16);
    $data[6] = chr((ord($data[6]) & 0x0f) | 0x40);
    $data[8] = chr((ord($data[8]) & 0x3f) | 0x80);
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

function normalizarBoolLc($v): int {
    if (is_bool($v)) return $v ? 1 : 0;
    $x = strtolower(trim((string)$v));
    return in_array($x, ['1','true','t','si','sí','on','activo'], true) ? 1 : 0;
}

/**
 * Divide "NOMBRE(S) APELLIDO1 APELLIDO2" en partes.
 */
function dividirNombreApellidos(string $nombreCompleto): array {
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
 * Guardar imagen de usuario desde base64 (≤ 2MB) como PNG en uploads/imgs/
 * Nombre: <idUsuario>_<md5>_<YYYYMMDDHHMMSS>.png
 */
function guardarImagenUsuarioBase64Lc(int $idUsuario, string $base64): array {
    // Si viene con encabezado data:image/png;base64, lo eliminamos
    if (strpos($base64, 'base64,') !== false) {
        $base64 = explode('base64,', $base64)[1];
    }

    // Decodificar el base64
    $imagen = base64_decode($base64);
    if ($imagen === false) {
        return ['ok' => false, 'msg' => 'Base64 inválido'];
    }

    // Crear carpeta si no existe
    $directorio = __DIR__ . '/../../uploads/imgs/';
    if (!is_dir($directorio)) {
        mkdir($directorio, 0775, true);
    }

    // Generar nombre único con el id de usuario
    $timestamp = date('YmdHis');
    $nombreArchivo = $idUsuario . '_' . uniqid() . '_' . $timestamp . '.png';
    $rutaCompleta = $directorio . $nombreArchivo;

    // Guardar la imagen como .png
    $guardado = file_put_contents($rutaCompleta, $imagen);
    if ($guardado === false) {
        return ['ok' => false, 'msg' => 'No se pudo guardar la imagen'];
    }

    // Ruta relativa para usar en el sistema
    $rutaRelativa = 'uploads/imgs/' . $nombreArchivo;
    return ['ok' => true, 'ruta' => $rutaRelativa];
}

function eliminarFotosUsuarioLc(int $idUsuario): void {
    $dir = rtrim(__DIR__ . '/../../uploads/imgs', '/\\');
    if (!is_dir($dir)) return;
    foreach (glob($dir . DIRECTORY_SEPARATOR . $idUsuario . '_*.png') as $f) {
        @unlink($f);
    }
}
