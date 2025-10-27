<?php
require_once '../../system/init.php';
require_once LIB_PATH . '/helpers/login_helper.php';
// -----------------------------------------------------------------------------

const BLOQUEO_MINUTOS = 15;     // minutos de bloqueo al superar 5 intentos
const INTENTOS_MAX    = 5;      // intentos fallidos por cédula antes de bloquear
// -----------------------------------------------------------------------------

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    responseApi(405, "Método no permitido");
}

$ahora_ts  = time();
$ahora_utc = gmdate('Y-m-d H:i:s', $ahora_ts);

// Entrada JSON
$jsonData = file_get_contents("php://input");
$data = json_decode($jsonData, true);

$identificacion = sanitizeInput($data["identificacion"] ?? "");
$password       = $data["pass"] ?? "";
$recaptcha      = $data["recaptcha"] ?? "";
$usuarioSelect  = isset($data["id_usuario"]) ? intval($data["id_usuario"]) : null;

if (empty($identificacion) || empty($password)) {
    responseApi(400, "Identificación y contraseña requeridas");
}

if ($usuarioSelect !== null && is_int($usuarioSelect) && $usuarioSelect > 0) {
    session_start();
    if (!in_array($usuarioSelect, $_SESSION['users_ids'] ?? [])) {
        responseApi(401, "Selección inválida.");
    }
} else {
    // reCAPTCHA si aplica
    if (defined('RECAPTCHA_ACTIVE') && RECAPTCHA_ACTIVE) {
        if (!recaptchaVerify($recaptcha ?? "")) {
            responseApi(400, "Por favor, verifica el reCAPTCHA.");
        }
    }
}

$cedulaEsc = sanitizeInput($identificacion);

// 1) Verificación de bloqueo por cédula en usuario_bloqueo (bloqueo general por cédula)
//    - Si tiene algún registro vigente (fecha_bloqueo > ahora), bloquear y devolver motivo.
//    - Si solo hay registros vencidos, limpiar (delete) todos por esa cédula.
$bloqSql = "
    SELECT id_usuario_bloqueo, motivo, fecha_bloqueo
    FROM usuario_bloqueo
    WHERE cedula = '{$cedulaEsc}' AND id_usuario IS NULL
    ORDER BY fecha_bloqueo DESC
";
$bloqRes = query($bloqSql);

$bloqueoVigente = null;
if ($bloqRes && $bloqRes->num_rows > 0) {
    while ($bloq = mysqli_fetch_assoc($bloqRes)) {
        $bloqueoTs = strtotime($bloq['fecha_bloqueo'] . ' UTC');
        if ($bloqueoTs > $ahora_ts) {
            $bloqueoVigente = $bloq;
            break;
        }
    }
    if ($bloqueoVigente) {
        responseApi(403, "Cuenta bloqueada: " . ($bloqueoVigente['motivo'] ?: "intentos fallidos") . ". Intenta más tarde.");
    } else {
        // Limpiar bloqueos vencidos
        update("DELETE FROM usuario_bloqueo WHERE cedula = '{$cedulaEsc}' AND id_usuario IS NULL");
    }
}

// 2) Buscar usuarios activos por cédula
$usuariosSql = "
    SELECT id_usuario, identificacion, nombre, contrasena_hash, id_empresa, id_rol, estado
    FROM usuario
    WHERE identificacion = '{$cedulaEsc}'
      AND estado = 1
";
$usuariosRes = query($usuariosSql);

if (!$usuariosRes || $usuariosRes->num_rows === 0) {
    // No existe cédula activa
    // -> También garantizamos que exista fila en intentos para esta cédula
    responseApi(401, "Identificación o contraseña incorrecta");
}

// 3) Si viene id_usuario (selección explícita), filtrar a ese usuario
$usuarios = [];
while ($row = mysqli_fetch_assoc($usuariosRes)) {
    if ($usuarioSelect === null || $usuarioSelect === intval($row['id_usuario'])) {
        $usuarios[] = $row;
    }
}

// Si aplicó filtro y no quedó nadie, es selección inválida
if ($usuarioSelect !== null && count($usuarios) === 0) {
    responseApi(401, "Selección inválida. Vuelve a intentar.");
}

// 4) Validar password contra todos los candidatos y reunir coincidencias
$coinciden = [];
foreach ($usuarios as $u) {
    if (comparePassword($password, $u['contrasena_hash'])) {
        $coinciden[] = $u;
    }
}

if (count($coinciden) === 0) {
    // Ningún usuario con esa cédula coincide en contraseña:
    //   - Incrementar intentos en usuario_intentos_fallidos por cédula.
    //   - Si supera INTENTOS_MAX, crear bloqueo por cédula.
    $actual = getIntentosPorCedula($conn, $cedulaEsc);
    $nuevo  = $actual + 1;
    setIntentosPorCedula($conn, $cedulaEsc, $nuevo);

    if ($nuevo >= INTENTOS_MAX) {
        $hasta  = gmdate('Y-m-d H:i:s', $ahora_ts + BLOQUEO_MINUTOS * 60);
        $motivo = "Intentos fallidos (>= " . INTENTOS_MAX . ")";
        update("
            INSERT INTO usuario_bloqueo (id_usuario, cedula, motivo, fecha_bloqueo, fecha_creacion)
            VALUES (NULL, '{$cedulaEsc}', '" . mysqli_real_escape_string($conn, $motivo) . "', '{$hasta}', '{$ahora_utc}')
        ");
        // Reset de contador al bloquear
        resetIntentosPorCedula($cedulaEsc);
        responseApi(403, "Contraseña incorrecta. Cuenta bloqueada temporalmente por intentos fallidos.");
    }

    responseApi(401, "Identificación o contraseña incorrecta");
}

// 5) Si hay una única coincidencia -> login directo
if (count($coinciden) === 1) {
    $usuario = $coinciden[0];

    // Reset de intentos por cédula al acertar
    resetIntentosPorCedula($cedulaEsc);

    // Iniciar sesión
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $_SESSION['id_usuario'] = intval($usuario['id_usuario']);
    $_SESSION['id_empresa'] = intval($usuario['id_empresa']);
    $_SESSION['id_rol']     = intval($usuario['id_rol']);

    responseApi(200, "Login exitoso");
}

// 6) Varias coincidencias (misma cédula + misma contraseña en varias empresas)
//    -> devolver opciones para que el usuario elija una.
$ids = array_map(fn($r) => intval($r['id_usuario']), $coinciden);
$in  = implode(',', array_map('intval', $ids));

$empresasRes = query("
    SELECT 
        u.id_usuario, 
        u.id_empresa, 
        r.nombre AS rol_nombre,
        e.nombre_legal AS empresa_nombre
    FROM usuario u
    LEFT JOIN empresa e ON e.id_empresa = u.id_empresa
    LEFT JOIN rol r ON r.id_rol = u.id_rol
    WHERE u.id_usuario IN ($in)
    ORDER BY u.id_empresa ASC
");

$opciones = [];
session_start();
while ($r = mysqli_fetch_assoc($empresasRes)) {
    $opciones[] = [
        "id_usuario"     => intval($r['id_usuario']),
        "empresa_nombre" => $r['empresa_nombre'],
        "rol"         => $r['rol_nombre']
    ];
    $_SESSION['users_ids'][] = intval($r['id_usuario']);
}

// Importante: vaciar contador de intentos ya que la contraseña fue correcta
resetIntentosPorCedula($cedulaEsc);

// Respuesta especial para forzar selección (status 206)
responseApi(206, "Selecciona la empresa para continuar", [
    "opciones" => $opciones
]);
