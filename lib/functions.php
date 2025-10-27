<?php

function hashPassword($password)
{
    $hashed = sha1($password);
    $hashed = md5($hashed);
    $hashed = hash('sha256', $hashed);
    return $hashed;
}

function comparePassword($inputPassword, $storedHash)
{
    return hashPassword($inputPassword) === $storedHash;
}

function ipUser()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}

//verificar recaptcha v2 con ip
function recaptchaVerify($recaptchaResponse)
{
    if (!RECAPTCHA_ACTIVE || empty($recaptchaResponse)) {
        return true;
    }
    $ip = ipUser();
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = [
        'secret' => RECAPTCHA_SECRET_KEY,
        'response' => $recaptchaResponse,
        'remoteip' => $ip
    ];
    $options = [
        'http' => [
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data),
        ],
    ];
    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    $resultJson = json_decode($result);
    return $resultJson->success;
}

function redirect($url)
{
    header("Location: $url");
    exit();
}

function sanitizeInput($data)
{
    global $conn;
    $data = trim($data);
    $data = strip_tags($data);
    $data = mysqli_real_escape_string($conn, $data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

function showData($data)
{
    return html_entity_decode($data);
}

function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

function requireLogin()
{
    if (!isLoggedIn()) {
        redirect(DOMAIN . '/login.php');
    }
}

function requireAdmin()
{
    if (!isLoggedIn() || $_SESSION['role'] !== 'admin') {
        redirect(DOMAIN . '/login.php');
    }
}

function query($query)
{
    global $conn;
    try {
        $result = mysqli_query($conn, $query);
        if ($result === false) {
            throw new Exception(mysqli_error($conn));
        }
        return $result;
    } catch (Exception $e) {
        echo "Error en la consulta SQL: " . $e->getMessage();
        return false;
    }
}

function update($query)
{
    global $conn;
    $result = query($query);
    if ($result === false) {
        return 0;
    }
    return mysqli_affected_rows($conn);
}

function fetchAssoc($result)
{
    return mysqli_fetch_assoc(query($result));
}

function fetchObject($result)
{
    return mysqli_fetch_object(query($result));
}


function numRows($result)
{
    return mysqli_num_rows(query($result));
}

function lastInsertId()
{
    global $conn;
    return mysqli_insert_id($conn);
}

function logout($redirectUrl = null)
{
    session_unset();
    session_destroy();
    if ($redirectUrl) {
        redirect($redirectUrl);
    }
    exit();
}

function responseApi($status, $message = '', $data = null)
{
    header('Content-Type: application/json; charset=utf-8');

    http_response_code($status);
    $response = ['status' => $status, 'message' => $message];
    if ($data !== null) {
        $response['data'] = $data;
    }
    echo json_encode($response);
    exit();
}

function addBitacora($usuarioId, $tabla = null, $idTabla = null, $accion = 'crear', $comentario = null)
{
    $usuarioId = intval($usuarioId);
    $tabla = $tabla ? "'" . sanitizeInput($tabla) . "'" : "NULL";
    $idTabla = $idTabla ? intval($idTabla) : "NULL";
    $accion = in_array($accion, ['create', 'read', 'update', 'delete', 'login', 'error', 'otros']) ? $accion : 'otros';
    $comentario = $comentario ? "'" . sanitizeInput($comentario) . "'" : "NULL";
    $ip = ipUser();
    $fecha = (new DateTime('now', new DateTimeZone('UTC')))->format('Y-m-d H:i:s');

    $query = "INSERT INTO bitacora (id_usuario, tabla, id_tabla, accion, comentario, ip, fecha) 
              VALUES ($usuarioId, $tabla, $idTabla, '$accion', $comentario, '$ip', '$fecha')";
    return query($query);
}

function fActual()
{
    return gmdate('Y-m-d H:i:s');
}

// ---------------------------
// Helpers
// ---------------------------

function sanitizeDigits(string $v, int $max): string
{
    $x = preg_replace('/\D+/', '', $v);
    return substr($x, 0, $max);
}

function sanitizeText(string $v, int $max): string
{
    $x = preg_replace('/<.*?>/u', '', $v);
    return substr(trim($x), 0, $max);
}

// ---------------------------
// Imagen (logo)
// ---------------------------
function decodeDataUrl(string $data): array
{
    if (strpos($data, 'data:') === 0) {
        if (!preg_match('/^data:(.*?);base64,(.+)$/', $data, $m)) {
            return ['ok' => false, 'msg' => 'Data URL inválida'];
        }
        $mime = $m[1];
        $b64  = $m[2];
    } else {
        $mime = null;
        $b64  = $data;
    }
    $bytes = base64_decode($b64, true);
    if ($bytes === false) return ['ok' => false, 'msg' => 'Base64 inválido'];
    return ['ok' => true, 'mime' => $mime, 'bytes' => $bytes];
}

function imageFromString(string $bytes)
{
    $im = @imagecreatefromstring($bytes);
    return $im ?: null;
}

function resizeMaxWidth($img, int $maxWidth = 1366)
{
    $w = imagesx($img);
    $h = imagesy($img);
    if ($w <= $maxWidth) return $img;
    $ratio = $h / $w;
    $nw = $maxWidth;
    $nh = (int)round($nw * $ratio);

    $dst = imagecreatetruecolor($nw, $nh);
    imagealphablending($dst, false);
    imagesavealpha($dst, true);
    $transparent = imagecolorallocatealpha($dst, 255, 255, 255, 127);
    imagefilledrectangle($dst, 0, 0, $nw, $nh, $transparent);
    imagecopyresampled($dst, $img, 0, 0, 0, 0, $nw, $nh, $w, $h);
    imagedestroy($img);
    return $dst;
}

/**
 * guardarImagen:
 * - Recibe base64 (dataURL o base64 crudo)
 * - Rechaza si el archivo de ENTRADA pesa > 1 MB
 * - Redimensiona a ancho máximo 1366
 * - Convierte a PNG y guarda en uploads/img/<sha1>.png
 * - Retorna ['ok'=>true,'ruta'=> '/uploads/img/xxx.png'] o ['ok'=>false,'msg'=>'...']
 */
function guardarImagen(string $contentBase64): array
{
    $dec = decodeDataUrl($contentBase64);
    if (!$dec['ok']) return ['ok' => false, 'msg' => $dec['msg'] ?? 'Error decodificando base64'];

    // 1 MB máximo de entrada
    if (strlen($dec['bytes']) > 1024 * 1024) {
        return ['ok' => false, 'msg' => 'La imagen supera 1 MB'];
    }

    $src = imageFromString($dec['bytes']);
    if (!$src) return ['ok' => false, 'msg' => 'Imagen inválida o no soportada'];

    $dst = resizeMaxWidth($src, 1366);

    $hash = sha1($dec['bytes']);
    $fileName = $hash . '.png';
    $absPath  = rtrim(DIR_UPLOAD_IMG, '/\\') . DIRECTORY_SEPARATOR . $fileName;

    $ok = imagepng($dst, $absPath, 6);
    imagedestroy($dst);

    if (!$ok) return ['ok' => false, 'msg' => 'No se pudo guardar la imagen'];

    return ['ok' => true, 'ruta' => '/uploads/img/' . $fileName];
}


function guardarLlavePlano(string $bytes, string $nombreOriginal)
{
    $ext = strtolower(pathinfo($nombreOriginal, PATHINFO_EXTENSION));
    if ($ext === '') $ext = 'bin';
    $hash = sha1($bytes . microtime(true) . random_int(0, PHP_INT_MAX));
    $fileName = $hash . '.' . $ext;
    $absPath  = rtrim(DIR_UPLOAD_KEYS, '/\\') . DIRECTORY_SEPARATOR . $fileName;

    $ok = file_put_contents($absPath, $bytes);
    if ($ok === false) return ['ok' => false, 'msg' => 'No se pudo guardar la llave'];
    return ['ok' => true, 'ruta' => '/uploads/keys/' . $fileName, 'size' => strlen($bytes), 'nombre' => $nombreOriginal];
}


function obtenerLlaveContenido(string $rutaRelativa)
{
    $abs = realpath(__DIR__ . '/../../' . ltrim($rutaRelativa, '/\\'));
    if ($abs === false || !is_file($abs)) {
        return ['ok' => false, 'msg' => 'Ruta inválida'];
    }
    $bytes = file_get_contents($abs);
    if ($bytes === false) return ['ok' => false, 'msg' => 'No se pudo leer la llave'];
    return ['ok' => true, 'bytes' => $bytes];
}

//generar contra aleatoria
function generarClaveAleatoria($longitud = 8)
{
    $caracteres = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_=+[]{}|;:,.<>?';
    $contrasena = '';
    $maxIndex = strlen($caracteres) - 1;
    for ($i = 0; $i < $longitud; $i++) {
        $index = random_int(0, $maxIndex);
        $contrasena .= $caracteres[$index];
    }
    return $contrasena;
}
