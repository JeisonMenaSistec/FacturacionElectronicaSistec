<?php
require_once '../../system/session.php'; // expone $usuario y helpers: responseApi, query, update, fetchAssoc, lastInsertId, addBitacora, fActual, sanitizeInput

/* =========================================================
 * Helpers locales
 * ========================================================= */
function normalizarBool($v): int
{
    if (is_bool($v)) return $v ? 1 : 0;
    $x = strtolower(trim((string)$v));
    return in_array($x, ['1', 'true', 't', 'si', 'sí', 'on', 'activo'], true) ? 1 : 0;
}

/**
 * Obtiene el siguiente id_producto_categoria para la empresa dada.
 * (Secuencial por empresa: MAX(id_producto_categoria) + 1, o 1 si no hay)
 */
function siguienteIdCategoriaPorEmpresa(int $idEmpresa): int
{
    $row = fetchAssoc("SELECT MAX(id_producto_categoria) AS max_id FROM producto_categoria WHERE id_empresa = " . intval($idEmpresa));
    $max = ($row && isset($row['max_id'])) ? (int)$row['max_id'] : 0;
    return $max > 0 ? $max + 1 : 1;
}

/** Construye WHERE de filtros para listar (siempre filtra por empresa) */
function buildFiltroWhereCategorias(int $empresaId, string $buscar = '', string $estadoTxt = ''): string
{
    $w = [];
    $w[] = "c.id_empresa = " . intval($empresaId);
    if ($buscar !== '') {
        $q = sanitizeInput($buscar);
        $w[] = "(c.nombre LIKE '%$q%' OR c.descripcion LIKE '%$q%')";
    }
    if ($estadoTxt !== '') {
        if (is_numeric($estadoTxt)) {
            $w[] = "c.estado = " . intval($estadoTxt);
        } else {
            if ($estadoTxt === 'Activo')   $w[] = "c.estado = 1";
            if ($estadoTxt === 'Inactivo') $w[] = "c.estado = 2";
            if ($estadoTxt === 'Eliminado') $w[] = "c.estado = 0";
        }
    }else {
        // Por defecto, excluir eliminados
        $w[] = "c.estado <> 0";
    }
    return implode(' AND ', $w);
}

/* =========================================================
 * Validación request / sesión
 * ========================================================= */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    responseApi(405, 'Método no permitido');
}

$raw   = file_get_contents('php://input');
$body  = json_decode($raw, true) ?? [];
$accion = $body['accion'] ?? '';

$usuarioId = isset($usuario->id_usuario) ? (int)$usuario->id_usuario : 0;
$empresaId = isset($usuario->id_empresa) ? (int)$usuario->id_empresa : 0;

if ($usuarioId <= 0 || $empresaId <= 0) {
    responseApi(401, 'Sesión inválida');
}

/* =========================================================
 * Acciones
 * ========================================================= */
switch ($accion) {

    /* --------------------------------------------
     * LISTAR
     * buscar (opcional), estado (opcional)
     * -------------------------------------------- */
    case 'listar': {
            $buscar = isset($body['buscar']) ? trim((string)$body['buscar']) : '';
            $estado = isset($body['estado']) ? trim((string)$body['estado']) : '';

            $where = buildFiltroWhereCategorias($empresaId, $buscar, $estado);
            $sql = "
            SELECT
                c.id,
                c.id_producto_categoria,
                c.id_empresa,
                c.nombre,
                c.descripcion,
                c.estado,
                c.id_usuario_creacion,
                c.fecha_creacion,
                c.id_usuario_modificacion,
                c.fecha_modificacion
            FROM producto_categoria c
            WHERE $where
            ORDER BY c.fecha_creacion DESC, c.id DESC
        ";
            $res = query($sql);
            if ($res === false) responseApi(500, 'Error listando categorías');

            $data = [];
            while ($r = mysqli_fetch_assoc($res)) {
                $data[] = [
                    'id'                    => (int)$r['id'],
                    'idCategoria'           => (int)$r['id_producto_categoria'],
                    'idEmpresa'             => (int)$r['id_empresa'],
                    'nombre'                => $r['nombre'],
                    'descripcion'           => $r['descripcion'],
                    'estado'                => (int)$r['estado'],
                    'idUsuarioCreacion'     => isset($r['id_usuario_creacion']) ? (int)$r['id_usuario_creacion'] : null,
                    'fechaCreacion'         => $r['fecha_creacion'],
                    'idUsuarioModificacion' => isset($r['id_usuario_modificacion']) ? (int)$r['id_usuario_modificacion'] : null,
                    'fechaModificacion'     => $r['fecha_modificacion']
                ];
            }
            responseApi(200, '', $data);
        }

        /* --------------------------------------------
     * OBTENER (por id general)
     * -------------------------------------------- */
    case 'obtener': {
            $id = isset($body['id']) ? (int)$body['id'] : 0;
            if ($id <= 0) responseApi(400, 'id requerido');

            $r = fetchAssoc("
            SELECT *
            FROM producto_categoria
            WHERE id = " . intval($id) . " AND id_empresa = " . intval($empresaId) . "
            LIMIT 1
        ");
            if (!$r) responseApi(404, 'Categoría no encontrada');

            $data = [
                'id'                    => (int)$r['id'],
                'idCategoria'           => (int)$r['id_producto_categoria'],
                'idEmpresa'             => (int)$r['id_empresa'],
                'nombre'                => $r['nombre'],
                'descripcion'           => $r['descripcion'],
                'estado'                => (int)$r['estado'],
                'idUsuarioCreacion'     => isset($r['id_usuario_creacion']) ? (int)$r['id_usuario_creacion'] : null,
                'fechaCreacion'         => $r['fecha_creacion'],
                'idUsuarioModificacion' => isset($r['id_usuario_modificacion']) ? (int)$r['id_usuario_modificacion'] : null,
                'fechaModificacion'     => $r['fecha_modificacion']
            ];
            responseApi(200, '', $data);
        }

        /* --------------------------------------------
     * CREAR
     * Requerido: nombre
     * id_producto_categoria: secuencial por empresa
     * -------------------------------------------- */
    case 'crear': {
            $nombre      = sanitizeInput($body['nombre'] ?? '');
            $descripcion = sanitizeInput($body['descripcion'] ?? '');

            if ($nombre === '') responseApi(400, 'El nombre es requerido');

            // Siguiente secuencial por empresa
            $idCategoria = siguienteIdCategoriaPorEmpresa($empresaId);
            $ahora = fActual();

            $sql = "
            INSERT INTO producto_categoria
            (id_producto_categoria, id_empresa, nombre, descripcion, estado,
             id_usuario_creacion, fecha_creacion, id_usuario_modificacion, fecha_modificacion)
            VALUES
            (
                " . intval($idCategoria) . ",
                " . intval($empresaId) . ",
                '" . $nombre . "',
                " . ($descripcion !== '' ? "'" . $descripcion . "'" : "NULL") . ",
                1,
                " . intval($usuarioId) . ",
                '" . $ahora . "',
                " . intval($usuarioId) . ",
                '" . $ahora . "'
            )
        ";
            $aff = update($sql);
            if ($aff <= 0) responseApi(500, 'No se pudo crear la categoría');

            $nuevoId = lastInsertId();
            addBitacora($usuarioId, 'producto_categoria', $nuevoId, 'create', 'Categoría creada');

            responseApi(200, 'Categoría creada', [
                'id'          => (int)$nuevoId,
                'idCategoria' => (int)$idCategoria
            ]);
        }

        /* --------------------------------------------
     * ACTUALIZAR (por id general)
     * Valida pertenencia por empresa
     * -------------------------------------------- */
    case 'actualizar': {
            $id = isset($body['id']) ? (int)$body['id'] : 0;
            if ($id <= 0) responseApi(400, 'id requerido');

            $existe = fetchAssoc("SELECT id, id_empresa FROM producto_categoria WHERE id = " . intval($id) . " LIMIT 1");
            if (!$existe || (int)$existe['id_empresa'] !== $empresaId) {
                responseApi(404, 'Categoría no encontrada en su empresa');
            }

            $nombre      = array_key_exists('nombre', $body) ? sanitizeInput($body['nombre']) : null;
            $descripcion = array_key_exists('descripcion', $body) ? sanitizeInput($body['descripcion']) : null;

            $campos = [];
            if ($nombre !== null)      $campos[] = "nombre = '" . $nombre . "'";
            if ($descripcion !== null) $campos[] = ($descripcion !== '' ? "descripcion = '" . $descripcion . "'" : "descripcion = NULL");
            $campos[] = "id_usuario_modificacion = " . intval($usuarioId);
            $campos[] = "fecha_modificacion = '" . fActual() . "'";

            if (empty($campos)) responseApi(400, 'No hay cambios para actualizar');

            $sql = "UPDATE producto_categoria SET " . implode(', ', $campos) . " WHERE id = " . intval($id) . " AND id_empresa = " . intval($empresaId) . " LIMIT 1";
            $aff = update($sql);
            if ($aff < 0) responseApi(500, 'Error actualizando categoría');

            addBitacora($usuarioId, 'producto_categoria', $id, 'update', 'Categoría actualizada');
            responseApi(200, 'Categoría actualizada', ['id' => (int)$id]);
        }

        /* --------------------------------------------
     * ACTIVAR (estado = 1) por id general
     * -------------------------------------------- */
    case 'activar': {
            $id = isset($body['id']) ? (int)$body['id'] : 0;
            if ($id <= 0) responseApi(400, 'id requerido');

            $existe = fetchAssoc("SELECT id, id_empresa FROM producto_categoria WHERE id = " . intval($id) . " LIMIT 1");
            if (!$existe || (int)$existe['id_empresa'] !== $empresaId) {
                responseApi(404, 'Categoría no encontrada en su empresa');
            }

            $sql = "
            UPDATE producto_categoria
            SET estado = 1,
                id_usuario_modificacion = " . intval($usuarioId) . ",
                fecha_modificacion = '" . fActual() . "'
            WHERE id = " . intval($id) . " AND id_empresa = " . intval($empresaId) . "
            LIMIT 1
        ";
            $aff = update($sql);
            if ($aff < 0) responseApi(500, 'Error activando categoría');

            addBitacora($usuarioId, 'producto_categoria', $id, 'update', 'Categoría activada (estado=1)');
            responseApi(200, 'Categoría activada');
        }

        /* --------------------------------------------
     * DESACTIVAR (estado = 2) por id general
     * -------------------------------------------- */
    case 'desactivar': {
            $id = isset($body['id']) ? (int)$body['id'] : 0;
            if ($id <= 0) responseApi(400, 'id requerido');

            $existe = fetchAssoc("SELECT id, id_empresa FROM producto_categoria WHERE id = " . intval($id) . " LIMIT 1");
            if (!$existe || (int)$existe['id_empresa'] !== $empresaId) {
                responseApi(404, 'Categoría no encontrada en su empresa');
            }

            $sql = "
            UPDATE producto_categoria
            SET estado = 2,
                id_usuario_modificacion = " . intval($usuarioId) . ",
                fecha_modificacion = '" . fActual() . "'
            WHERE id = " . intval($id) . " AND id_empresa = " . intval($empresaId) . "
            LIMIT 1
        ";
            $aff = update($sql);
            if ($aff < 0) responseApi(500, 'Error desactivando categoría');

            addBitacora($usuarioId, 'producto_categoria', $id, 'update', 'Categoría desactivada (estado=2)');
            responseApi(200, 'Categoría desactivada');
        }

        /* --------------------------------------------
     * ELIMINAR (lógico, estado = 0) por id general
     * -------------------------------------------- */
    case 'eliminar': {
            $id = isset($body['id']) ? (int)$body['id'] : 0;
            if ($id <= 0) responseApi(400, 'id requerido');

            $existe = fetchAssoc("SELECT id, id_empresa FROM producto_categoria WHERE id = " . intval($id) . " LIMIT 1");
            if (!$existe || (int)$existe['id_empresa'] !== $empresaId) {
                responseApi(404, 'Categoría no encontrada en su empresa');
            }

            $sql = "
            UPDATE producto_categoria
            SET estado = 0,
                id_usuario_modificacion = " . intval($usuarioId) . ",
                fecha_modificacion = '" . fActual() . "'
            WHERE id = " . intval($id) . " AND id_empresa = " . intval($empresaId) . "
            LIMIT 1
        ";
            $aff = update($sql);
            if ($aff < 0) responseApi(500, 'Error eliminando categoría');

            addBitacora($usuarioId, 'producto_categoria', $id, 'delete', 'Categoría eliminada (estado=0)');
            responseApi(200, 'Categoría eliminada');
        }

    default:
        responseApi(400, 'Acción inválida');
}
