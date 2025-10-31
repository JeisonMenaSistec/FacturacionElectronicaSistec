<?php

/**
 * API: Mantenimiento de Clientes
 * Ruta sugerida: api/clientes/mantenimiento.php
 */
require_once '../../system/session.php';
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    responseApi(405, 'Método no permitido');
}

$raw  = file_get_contents('php://input');
$body = json_decode($raw, true) ?? [];
$accion = trim((string)($body['accion'] ?? ''));

// =====================================================================================
// Helpers locales
// =====================================================================================
function getEmpresaId(array $body): int
{
    // Ajusta estos nombres a como guardas la empresa en sesión
    if (isset($_SESSION['empresa_id'])) return (int)$_SESSION['empresa_id'];
    if (isset($_SESSION['id_empresa'])) return (int)$_SESSION['id_empresa'];
    if (!empty($body['idEmpresa']))     return (int)$body['idEmpresa'];
    return 0; // Si 0, obligamos a enviar idEmpresa en el body o a tenerlo en sesión
}

function getUserId(): int
{
    if (isset($_SESSION['user_id'])) return (int)$_SESSION['user_id'];
    if (isset($_SESSION['id_usuario'])) return (int)$_SESSION['id_usuario'];
    return 0;
}

function clampInt($v, int $min, int $max): int
{
    $n = (int)$v;
    if ($n < $min) return $min;
    if ($n > $max) return $max;
    return $n;
}

function onlyDigitsMax(string $v, int $max): string
{
    return sanitizeDigits($v, $max); // usa tu helper global
}

function strMax(string $v, int $max): string
{
    return sanitizeText($v, $max); // usa tu helper global
}

function findIdTipoCedulaByCodigo(string $codigo): int
{
    $codigo = strMax($codigo, 8);
    $sql = "SELECT id_tipo_cedula FROM hacienda_tipo_cedula WHERE codigo = '" . mysqli_real_escape_string($GLOBALS['conn'], $codigo) . "' LIMIT 1";
    $row = fetchAssoc($sql);
    return $row ? (int)$row['id_tipo_cedula'] : 0;
}

function nextIdCliente(int $idEmpresa): int
{
    $sql = "SELECT IFNULL(MAX(id_cliente),0)+1 AS nextId FROM cliente WHERE id_empresa = " . intval($idEmpresa);
    $row = fetchAssoc($sql);
    return (int)($row ? $row['nextId'] : 1);
}

function formatIdentificacion(string $tipoCodigo, string $ident): string
{
    // Simple format placeholder. Ajústalo si quieres 1-1111-1111 etc. por tipo.
    $dig = preg_replace('/\D+/', '', $ident);
    return $dig;
}

function rowToListOutput(array $r): array
{
    // Prepara campos para la tabla del frontend
    $nombre = trim(implode(' ', array_filter([$r['nombre'] ?? '', $r['apellido1'] ?? '', $r['apellido2'] ?? ''])));
    $identFmt = formatIdentificacion($r['tipoCedula'] ?? '', (string)($r['identificacion'] ?? ''));

    return [
        'id'                    => (int)($r['id'] ?? 0),
        'idCliente'             => (int)($r['id_cliente'] ?? 0),
        'codigoInterno'         => $r['codigo_interno'] ?? null,
        'identificacion'        => $r['identificacion'] ?? null,
        'identificacionFormatted' => $identFmt,
        'nombre'                => $r['nombre'] ?? null,
        'apellido1'             => $r['apellido1'] ?? null,
        'apellido2'             => $r['apellido2'] ?? null,
        'nombreCompleto'        => $nombre,
        'correo'                => $r['correo'] ?? null,
        'tipoCedula'            => $r['tipoCedula'] ?? null,
        'tipoCedulaDesc'        => $r['tipoCedulaDesc'] ?? null,
        'nombreComercial'       => $r['nombre_comercial'] ?? null,
        'codPais'               => $r['cod_pais'] ?? null,
        'telefono'              => $r['telefono'] ?? null,
        'codProvincia'          => $r['cod_provincia'] ?? null,
        'codCanton'             => $r['cod_canton'] ?? null,
        'codDistrito'           => $r['cod_distrito'] ?? null,
        'codBarrio'             => $r['cod_barrio'] ?? null,
        'direccionDetallada'    => $r['direccion_detallada'] ?? null,
        'destinatario'          => $r['destinatario'] ?? null,
        'correoCopiaCortesia'   => $r['correo_copia_cortesia'] ?? null,
        'idActividadEconomica'  => isset($r['id_actividad_economica']) ? (int)$r['id_actividad_economica'] : null,
        'fechaCreacion'         => $r['fecha_creacion'] ?? null,
        'fechaModificacion'     => $r['fecha_modificacion'] ?? null,
    ];
}

// =====================================================================================
// Acciones
// =====================================================================================
switch ($accion) {
    // -----------------------------------------------------------------------------
    // LISTAR
    // body: {valor, tipo, pagina, tamPagina}
    // -----------------------------------------------------------------------------
    case 'listar': {
            $idEmpresa = getEmpresaId($body);
            if ($idEmpresa <= 0) responseApi(400, 'idEmpresa inválido o no encontrado en sesión.');

            $valor    = strMax((string)($body['valor'] ?? ''), 128);
            $tipo     = strMax((string)($body['tipo'] ?? ''), 16); // nombre|cedula|codigo|correo|'' (todos)
            $pagina   = clampInt(($body['pagina'] ?? 1), 1, PHP_INT_MAX);
            $tamMax   = 10000;
            $tamPagina = clampInt(($body['tamPagina'] ?? 10), 1, $tamMax);
            $off      = ($pagina - 1) * $tamPagina;

            $where = "c.id_empresa = " . intval($idEmpresa);
            if ($valor !== '') {
                $valEsc = mysqli_real_escape_string($conn, $valor);
                switch ($tipo) {
                    case 'nombre':
                        $where .= " AND (CONCAT_WS(' ', c.nombre, c.apellido1, c.apellido2) LIKE '%$valEsc%')";
                        break;
                    case 'cedula':
                        $dig = mysqli_real_escape_string($conn, preg_replace('/\D+/', '', $valor));
                        $where .= " AND c.identificacion LIKE '%$dig%'";
                        break;
                    case 'codigo':
                        $where .= " AND c.codigo_interno LIKE '%$valEsc%'";
                        break;
                    case 'correo':
                        $where .= " AND c.correo LIKE '%$valEsc%'";
                        break;
                    default:
                        $where .= " AND (
                        c.codigo_interno LIKE '%$valEsc%' OR
                        c.correo LIKE '%$valEsc%' OR
                        CONCAT_WS(' ', c.nombre, c.apellido1, c.apellido2) LIKE '%$valEsc%' OR
                        c.identificacion LIKE '%" . mysqli_real_escape_string($conn, preg_replace('/\D+/', '', $valor)) . "%'
                    )";
                }
            }

            // total
            $sqlTotal = "SELECT COUNT(*) AS t
                     FROM cliente c
                     WHERE $where";
            $rowT = fetchAssoc($sqlTotal);
            $total = (int)($rowT ? $rowT['t'] : 0);

            // rows
            $sql = "SELECT 
                    c.*,
                    tc.codigo AS tipoCedula,
                    tc.descripcion AS tipoCedulaDesc
                FROM cliente c
                LEFT JOIN hacienda_tipo_cedula tc ON tc.id_tipo_cedula = c.id_tipo_cedula
                WHERE $where
                ORDER BY c.fecha_modificacion DESC, c.id DESC
                LIMIT $off, $tamPagina";
            $res = query($sql);
            if ($res === false) responseApi(500, 'Error consultando clientes');

            $rows = [];
            while ($r = mysqli_fetch_assoc($res)) {
                $rows[] = rowToListOutput($r);
            }

            responseApi(200, 'OK', ['total' => $total, 'rows' => $rows]);
        }

        // -----------------------------------------------------------------------------
        // CREAR
        // body: { cliente: {tipoCedula(codigo), identificacion, codigoInterno?, nombre, apellido1, apellido2, nombreComercial?, correo, actividadEconomica?, codPais?, telefono?, codProvincia?, codCanton?, codDistrito?, codBarrio?, direccionDetallada?, destinatario?, correoCopiaCortesia?, proveedorFactura? } }
        // -----------------------------------------------------------------------------
    case 'crear': {
            $idEmpresa = getEmpresaId($body);
            if ($idEmpresa <= 0) responseApi(400, 'idEmpresa inválido o no encontrado en sesión.');

            $uId = getUserId();

            $cli = $body['cliente'] ?? [];
            if (!is_array($cli)) responseApi(400, 'Payload cliente inválido');

            $tipoCedulaCodigo = strMax((string)($cli['tipoCedula'] ?? ''), 8);
            $identificacion   = onlyDigitsMax((string)($cli['identificacion'] ?? ''), 20);
            $codigoInterno    = strMax((string)($cli['codigoInterno'] ?? ''), 64);

            $nombre           = strMax((string)($cli['nombre'] ?? ''), 128);
            $apellido1        = strMax((string)($cli['apellido1'] ?? ''), 128);
            $apellido2        = strMax((string)($cli['apellido2'] ?? ''), 128);
            $nombreComercial  = strMax((string)($cli['nombreComercial'] ?? ''), 256);
            $correo           = strMax((string)($cli['correo'] ?? ''), 256);

            $actividadEco     = trim((string)($cli['actividadEconomica'] ?? ''));
            $codPais          = strMax((string)($cli['codPais'] ?? ''), 4);
            $telefono         = onlyDigitsMax((string)($cli['telefono'] ?? ''), 15);

            $codProvincia     = strMax((string)($cli['codProvincia'] ?? ''), 4);
            $codCanton        = strMax((string)($cli['codCanton'] ?? ''), 4);
            $codDistrito      = strMax((string)($cli['codDistrito'] ?? ''), 4);
            $codBarrio        = strMax((string)($cli['codBarrio'] ?? ''), 4);
            $direccionDet     = strMax((string)($cli['direccionDetallada'] ?? ''), 256);

            $destinatario     = strMax((string)($cli['destinatario'] ?? ''), 256);
            $correoCopia      = strMax((string)($cli['correoCopiaCortesia'] ?? ''), 256);

            // Requeridos mínimos
            if ($tipoCedulaCodigo === '') responseApi(400, 'tipoCedula es requerido');
            if ($identificacion   === '') responseApi(400, 'identificacion es requerida');
            if ($nombre           === '') responseApi(400, 'nombre es requerido');
            if ($correo           === '') responseApi(400, 'correo es requerido');

            $idTipoCedula = findIdTipoCedulaByCodigo($tipoCedulaCodigo);
            if ($idTipoCedula <= 0) responseApi(400, 'tipoCedula no válido');

            // Duplicidad por identificacion en misma empresa
            $sqlDup = "SELECT id FROM cliente WHERE id_empresa = " . intval($idEmpresa) . " AND identificacion = '" . mysqli_real_escape_string($conn, $identificacion) . "' LIMIT 1";
            $dup = fetchAssoc($sqlDup);
            if ($dup) responseApi(409, 'Ya existe un cliente con esa identificación en la empresa');

            $idCliente = nextIdCliente($idEmpresa);
            $fnow = fActual();
            $idUsuario = $uId;

            // id_actividad_economica: guardamos como entero si viene numérico, sino NULL
            $idActEco = (ctype_digit((string)$actividadEco) ? (int)$actividadEco : null);
            $idActEcoSql = is_null($idActEco) ? "NULL" : intval($idActEco);

            $sql = "INSERT INTO cliente (
                    id_cliente, id_empresa, id_tipo_cedula, identificacion, codigo_interno,
                    nombre, apellido1, apellido2, correo, nombre_comercial,
                    cod_pais, telefono,
                    cod_provincia, cod_canton, cod_distrito, cod_barrio, direccion_detallada,
                    destinatario, correo_copia_cortesia,
                    id_actividad_economica,
                    id_usuario_creacion, fecha_creacion, id_usuario_modificacion, fecha_modificacion
                ) VALUES (
                    " . intval($idCliente) . ",
                    " . intval($idEmpresa) . ",
                    " . intval($idTipoCedula) . ",
                    '" . mysqli_real_escape_string($conn, $identificacion) . "',
                    " . ($codigoInterno !== '' ? "'" . mysqli_real_escape_string($conn, $codigoInterno) . "'" : "NULL") . ",
                    '" . mysqli_real_escape_string($conn, $nombre) . "',
                    " . ($apellido1 !== '' ? "'" . mysqli_real_escape_string($conn, $apellido1) . "'" : "NULL") . ",
                    " . ($apellido2 !== '' ? "'" . mysqli_real_escape_string($conn, $apellido2) . "'" : "NULL") . ",
                    '" . mysqli_real_escape_string($conn, $correo) . "',
                    " . ($nombreComercial !== '' ? "'" . mysqli_real_escape_string($conn, $nombreComercial) . "'" : "NULL") . ",
                    " . ($codPais !== '' ? intval($codPais) : "NULL") . ",
                    " . ($telefono !== '' ? intval($telefono) : "NULL") . ",
                    " . ($codProvincia !== '' ? "'" . mysqli_real_escape_string($conn, $codProvincia) . "'" : "NULL") . ",
                    " . ($codCanton    !== '' ? "'" . mysqli_real_escape_string($conn, $codCanton) . "'"     : "NULL") . ",
                    " . ($codDistrito  !== '' ? "'" . mysqli_real_escape_string($conn, $codDistrito) . "'"   : "NULL") . ",
                    " . ($codBarrio    !== '' ? "'" . mysqli_real_escape_string($conn, $codBarrio) . "'"     : "NULL") . ",
                    " . ($direccionDet !== '' ? "'" . mysqli_real_escape_string($conn, $direccionDet) . "'"  : "NULL") . ",
                    " . ($destinatario !== '' ? "'" . mysqli_real_escape_string($conn, $destinatario) . "'"  : "NULL") . ",
                    " . ($correoCopia  !== '' ? "'" . mysqli_real_escape_string($conn, $correoCopia) . "'"   : "NULL") . ",
                    $idActEcoSql,
                    " . intval($idUsuario) . ",
                    '" . mysqli_real_escape_string($conn, $fnow) . "',
                    " . intval($idUsuario) . ",
                    '" . mysqli_real_escape_string($conn, $fnow) . "'
                )";

            $ok = query($sql);
            if ($ok === false) responseApi(500, 'No se pudo crear el cliente');

            addBitacora($idUsuario, 'cliente', $idCliente, 'create', 'Crear cliente');

            responseApi(201, 'Cliente creado correctamente', [
                'idCliente' => $idCliente
            ]);
        }

        // -----------------------------------------------------------------------------
        // ACTUALIZAR
        // body: { id | idCliente, cliente:{... mismos campos que crear ...} }
        // -----------------------------------------------------------------------------
    case 'actualizar': {
            $idEmpresa = getEmpresaId($body);
            if ($idEmpresa <= 0) responseApi(400, 'idEmpresa inválido o no encontrado en sesión.');
            $uId = getUserId();

            $id       = (int)($body['id'] ?? 0);
            $idCliente = (int)($body['idCliente'] ?? 0);
            if ($id <= 0 && $idCliente <= 0) responseApi(400, 'id o idCliente requerido');

            $cli = $body['cliente'] ?? [];
            if (!is_array($cli)) responseApi(400, 'Payload cliente inválido');

            // Cargar existente
            $whereId = $id > 0 ? "id = " . intval($id) : "id_cliente = " . intval($idCliente);
            $row = fetchAssoc("SELECT * FROM cliente WHERE $whereId AND id_empresa = " . intval($idEmpresa) . " LIMIT 1");
            if (!$row) responseApi(404, 'Cliente no encontrado');

            // Campos editables
            $tipoCedulaCodigo = isset($cli['tipoCedula']) ? strMax((string)$cli['tipoCedula'], 8) : null;
            $idTipoCedula = $row['id_tipo_cedula'];
            if ($tipoCedulaCodigo !== null) {
                $tmp = findIdTipoCedulaByCodigo($tipoCedulaCodigo);
                if ($tmp <= 0) responseApi(400, 'tipoCedula no válido');
                $idTipoCedula = $tmp;
            }

            $identificacion   = array_key_exists('identificacion', $cli) ? onlyDigitsMax((string)$cli['identificacion'], 20) : $row['identificacion'];

            // Verificar duplicidad identificacion (si la cambian)
            if ($identificacion !== $row['identificacion']) {
                $sqlDup = "SELECT id FROM cliente 
                       WHERE id_empresa = " . intval($idEmpresa) . " 
                         AND identificacion = '" . mysqli_real_escape_string($conn, $identificacion) . "'
                         AND id <> " . intval($row['id']) . "
                       LIMIT 1";
                $dup = fetchAssoc($sqlDup);
                if ($dup) responseApi(409, 'Ya existe un cliente con esa identificación en la empresa');
            }

            $codigoInterno    = array_key_exists('codigoInterno', $cli) ? strMax((string)$cli['codigoInterno'], 64) : $row['codigo_interno'];

            $nombre           = array_key_exists('nombre', $cli) ? strMax((string)$cli['nombre'], 128) : $row['nombre'];
            $apellido1        = array_key_exists('apellido1', $cli) ? strMax((string)$cli['apellido1'], 128) : $row['apellido1'];
            $apellido2        = array_key_exists('apellido2', $cli) ? strMax((string)$cli['apellido2'], 128) : $row['apellido2'];
            $nombreComercial  = array_key_exists('nombreComercial', $cli) ? strMax((string)$cli['nombreComercial'], 256) : $row['nombre_comercial'];
            $correo           = array_key_exists('correo', $cli) ? strMax((string)$cli['correo'], 256) : $row['correo'];

            $actividadEco     = array_key_exists('actividadEconomica', $cli) ? trim((string)$cli['actividadEconomica']) : $row['id_actividad_economica'];
            $idActEco         = (is_null($actividadEco) || $actividadEco === '') ? null : (ctype_digit((string)$actividadEco) ? (int)$actividadEco : null);
            $idActEcoSql      = is_null($idActEco) ? "NULL" : intval($idActEco);

            $codPais          = array_key_exists('codPais', $cli) ? strMax((string)$cli['codPais'], 4) : $row['cod_pais'];
            $telefono         = array_key_exists('telefono', $cli) ? onlyDigitsMax((string)$cli['telefono'], 15) : $row['telefono'];

            $codProvincia     = array_key_exists('codProvincia', $cli) ? strMax((string)$cli['codProvincia'], 4) : $row['cod_provincia'];
            $codCanton        = array_key_exists('codCanton', $cli) ? strMax((string)$cli['codCanton'], 4) : $row['cod_canton'];
            $codDistrito      = array_key_exists('codDistrito', $cli) ? strMax((string)$cli['codDistrito'], 4) : $row['cod_distrito'];
            $codBarrio        = array_key_exists('codBarrio', $cli) ? strMax((string)$cli['codBarrio'], 4) : $row['cod_barrio'];
            $direccionDet     = array_key_exists('direccionDetallada', $cli) ? strMax((string)$cli['direccionDetallada'], 256) : $row['direccion_detallada'];

            $destinatario     = array_key_exists('destinatario', $cli) ? strMax((string)$cli['destinatario'], 256) : $row['destinatario'];
            $correoCopia      = array_key_exists('correoCopiaCortesia', $cli) ? strMax((string)$cli['correoCopiaCortesia'], 256) : $row['correo_copia_cortesia'];

            if ($nombre === '' || $correo === '' || $identificacion === '') {
                responseApi(400, 'Campos requeridos vacíos (nombre, correo, identificación)');
            }

            $fnow = fActual();
            $sql = "UPDATE cliente SET
                    id_tipo_cedula = " . intval($idTipoCedula) . ",
                    identificacion = '" . mysqli_real_escape_string($conn, $identificacion) . "',
                    codigo_interno = " . ($codigoInterno !== '' ? "'" . mysqli_real_escape_string($conn, $codigoInterno) . "'" : "NULL") . ",
                    nombre         = '" . mysqli_real_escape_string($conn, $nombre) . "',
                    apellido1      = " . ($apellido1 !== '' ? "'" . mysqli_real_escape_string($conn, $apellido1) . "'" : "NULL") . ",
                    apellido2      = " . ($apellido2 !== '' ? "'" . mysqli_real_escape_string($conn, $apellido2) . "'" : "NULL") . ",
                    correo         = '" . mysqli_real_escape_string($conn, $correo) . "',
                    nombre_comercial = " . ($nombreComercial !== '' ? "'" . mysqli_real_escape_string($conn, $nombreComercial) . "'" : "NULL") . ",
                    cod_pais       = " . ($codPais !== '' ? intval($codPais) : "NULL") . ",
                    telefono       = " . ($telefono !== '' ? intval($telefono) : "NULL") . ",
                    cod_provincia  = " . ($codProvincia !== '' ? "'" . mysqli_real_escape_string($conn, $codProvincia) . "'" : "NULL") . ",
                    cod_canton     = " . ($codCanton    !== '' ? "'" . mysqli_real_escape_string($conn, $codCanton) . "'"     : "NULL") . ",
                    cod_distrito   = " . ($codDistrito  !== '' ? "'" . mysqli_real_escape_string($conn, $codDistrito) . "'"   : "NULL") . ",
                    cod_barrio     = " . ($codBarrio    !== '' ? "'" . mysqli_real_escape_string($conn, $codBarrio) . "'"     : "NULL") . ",
                    direccion_detallada = " . ($direccionDet !== '' ? "'" . mysqli_real_escape_string($conn, $direccionDet) . "'" : "NULL") . ",
                    destinatario   = " . ($destinatario !== '' ? "'" . mysqli_real_escape_string($conn, $destinatario) . "'"  : "NULL") . ",
                    correo_copia_cortesia = " . ($correoCopia  !== '' ? "'" . mysqli_real_escape_string($conn, $correoCopia) . "'" : "NULL") . ",
                    id_actividad_economica = $idActEcoSql,
                    id_usuario_modificacion = " . intval($uId) . ",
                    fecha_modificacion = '" . mysqli_real_escape_string($conn, $fnow) . "'
                WHERE $whereId AND id_empresa = " . intval($idEmpresa) . "
                LIMIT 1";
            $ok = query($sql);
            if ($ok === false) responseApi(500, 'No se pudo actualizar el cliente');

            addBitacora($uId, 'cliente', $row['id_cliente'], 'update', 'Actualizar cliente');

            responseApi(200, 'Cliente actualizado correctamente');
        }

        // -----------------------------------------------------------------------------
        // ELIMINAR
        // body: { id | idCliente }
        // -----------------------------------------------------------------------------
    case 'eliminar': {
            $idEmpresa = getEmpresaId($body);
            if ($idEmpresa <= 0) responseApi(400, 'idEmpresa inválido o no encontrado en sesión.');
            $uId = getUserId();

            $id       = (int)($body['id'] ?? 0);
            $idCliente = (int)($body['idCliente'] ?? 0);
            if ($id <= 0 && $idCliente <= 0) responseApi(400, 'id o idCliente requerido');

            $whereId = $id > 0 ? "id = " . intval($id) : "id_cliente = " . intval($idCliente);
            $row = fetchAssoc("SELECT id, id_cliente FROM cliente WHERE $whereId AND id_empresa = " . intval($idEmpresa) . " LIMIT 1");
            if (!$row) responseApi(404, 'Cliente no encontrado');

            $sql = "DELETE FROM cliente WHERE id = " . intval($row['id']) . " AND id_empresa = " . intval($idEmpresa) . " LIMIT 1";
            $ok  = query($sql);
            if ($ok === false) responseApi(500, 'No se pudo eliminar el cliente');

            addBitacora($uId, 'cliente', $row['id_cliente'], 'delete', 'Eliminar cliente');

            responseApi(200, 'Cliente eliminado correctamente');
        }

        // -----------------------------------------------------------------------------
        // VER (opcional)
        // body: { id | idCliente }
        // -----------------------------------------------------------------------------
    case 'ver': {
            $idEmpresa = getEmpresaId($body);
            if ($idEmpresa <= 0) responseApi(400, 'idEmpresa inválido o no encontrado en sesión.');

            $id       = (int)($body['id'] ?? 0);
            $idCliente = (int)($body['idCliente'] ?? 0);
            if ($id <= 0 && $idCliente <= 0) responseApi(400, 'id o idCliente requerido');

            $whereId = $id > 0 ? "c.id = " . intval($id) : "c.id_cliente = " . intval($idCliente);

            $sql = "SELECT 
                    c.*,
                    tc.codigo AS tipoCedula,
                    tc.descripcion AS tipoCedulaDesc
                FROM cliente c
                LEFT JOIN hacienda_tipo_cedula tc ON tc.id_tipo_cedula = c.id_tipo_cedula
                WHERE $whereId AND c.id_empresa = " . intval($idEmpresa) . "
                LIMIT 1";
            $row = fetchAssoc($sql);
            if (!$row) responseApi(404, 'Cliente no encontrado');

            responseApi(200, 'OK', rowToListOutput($row));
        }

    default:
        responseApi(400, 'Acción inválida');
}
