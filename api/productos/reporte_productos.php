<?php

/**
 * api/productos/reporte_productos.php
 *
 * Acciones soportadas (POST JSON):
 *  - listar:
 *      { accion:"listar", pagina, tamPagina, filtros:{ buscarTexto, categoria, iva, estado, precioMin, precioMax, cantidadMin } }
 *      -> { total, rows[] }  (paginado servidor; tamPagina máx 10000)
 *
 *  - exportar:
 *      { accion:"exportar", filtros:{...}, maxFilas }  (maxFilas máx 10000)
 *      -> rows[] con cabeceras exactas:
 *         Codigo | CodigoCabys | Detalle | Unidad | Cantidad | Precio | TarifaIVA | Categoria | RegistroMedicamento | FormaFarmaceutica | PartidaArancelaria
 *
 *  - categorias:
 *      { accion:"categorias" }
 *      -> rows[] { id, idProductoCategoria, nombre, estado }
 *
 *  - iva:
 *      { accion:"iva" }
 *      -> rows[] { idImpGeneral, codigo, descripcion, porcentaje, activo }
 *
 * Reglas de mapeo:
 *  - Unidad:            producto.id_unidad_medida -> hacienda_unidad_medida.codigo
 *  - TarifaIVA:         producto.id_imp_general   -> hacienda_imp_general.codigo
 *  - Categoria:         producto.id_categoria (PK producto_categoria) -> producto_categoria.id_producto_categoria
 *  - FormaFarmaceutica: producto.med_id_forma_farmaceutica -> hacienda_forma_farmaceutica.codigo
 *  - Precio:            se devuelve float; el frontend lo serializa con punto decimal
 *  - Estado:            1=Activo, 2=Inactivo; excluye estado=0
 *  - Límite back:       10.000 registros como máximo (listar/exportar)
 *  - Nomenclatura:      lowerCamelCase
 */

require_once '../../system/session.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    responseApi(405, 'Método no permitido');
}

$raw    = file_get_contents('php://input');
$body   = json_decode($raw, true) ?? [];
$accion = $body['accion'] ?? '';

$usuarioId = isset($usuario->id_usuario) ? (int)$usuario->id_usuario : 0;
$empresaId = isset($usuario->id_empresa) ? (int)$usuario->id_empresa : 0;

if ($usuarioId <= 0 || $empresaId <= 0) {
    responseApi(401, 'Sesión inválida');
}

/* =========================================================
 * Helpers locales
 * ========================================================= */

function toIntOrNull($v)
{
    if ($v === null || $v === '' || !is_numeric($v)) return null;
    return (int)$v;
}

function toFloatOrNull($v)
{
    if ($v === null || $v === '' || !is_numeric($v)) return null;
    return (float)$v;
}

/** Normaliza códigos para comparar "03" == "3" y quitar espacios. */
function normalizarCodigo($v): string
{
    $s = trim((string)$v);
    if ($s === '') return '';
    if (preg_match('/^\d+$/', $s)) {
        $s = ltrim($s, '0');
        return $s === '' ? '0' : $s;
    }
    return $s;
}

/** Construye WHERE a partir de filtros. Devuelve [whereSql, paramsEcho] */
function buildWhereFiltros(int $empresaId, array $filtros): array
{
    $w = [];
    // por empresa y excluir eliminados
    $w[] = "p.id_empresa = " . intval($empresaId);
    $w[] = "p.estado <> 0";

    $buscarTexto = isset($filtros['buscarTexto']) ? trim((string)$filtros['buscarTexto']) : '';
    $categoria   = isset($filtros['categoria']) ? trim((string)$filtros['categoria']) : '';
    $iva         = isset($filtros['iva']) ? trim((string)$filtros['iva']) : '';
    $estadoTxt   = isset($filtros['estado']) ? trim((string)$filtros['estado']) : '';
    $precioMin   = toFloatOrNull($filtros['precioMin'] ?? null);
    $precioMax   = toFloatOrNull($filtros['precioMax'] ?? null);
    $cantidadMin = toIntOrNull($filtros['cantidadMin'] ?? null);

    if ($buscarTexto !== '') {
        $like = "'%" . sanitizeInput($buscarTexto) . "%'";
        $w[] = "(p.cod_producto LIKE $like 
              OR p.nombre LIKE $like 
              OR p.cod_cabys LIKE $like 
              OR c.nombre LIKE $like 
              OR c.id_producto_categoria LIKE $like)";
    }

    // categoria: acepta nombre o id_producto_categoria (código)
    if ($categoria !== '') {
        $catNorm = normalizarCodigo($categoria);
        $catEsc  = sanitizeInput($categoria);
        $w[] = "(c.id_producto_categoria = '" . $catNorm . "' OR c.nombre = '" . $catEsc . "')";
    }

    // iva: recibe el "codigo" (0|1|2|4|13)
    if ($iva !== '') {
        $ivaNorm = normalizarCodigo($iva);
        $w[] = "ig.codigo = '" . sanitizeInput($ivaNorm) . "'";
    }

    // estado: Activo/Inactivo
    if ($estadoTxt !== '') {
        $est = null;
        if (strcasecmp($estadoTxt, 'Activo') === 0) $est = 1;
        if (strcasecmp($estadoTxt, 'Inactivo') === 0) $est = 2;
        if ($est !== null) $w[] = "p.estado = " . intval($est);
    }

    if ($precioMin !== null) $w[] = "p.precio_unitario >= " . (float)$precioMin;
    if ($precioMax !== null) $w[] = "p.precio_unitario <= " . (float)$precioMax;
    if ($cantidadMin !== null) $w[] = "p.cantidad >= " . (int)$cantidadMin;

    $where = implode(' AND ', $w);
    return [$where, [
        'buscarTexto' => $buscarTexto,
        'categoria'   => $categoria,
        'iva'         => $iva,
        'estado'      => $estadoTxt,
        'precioMin'   => $precioMin,
        'precioMax'   => $precioMax,
        'cantidadMin' => $cantidadMin
    ]];
}

/** SELECT base para listar/exportar (con JOINs de mapeo). */
function sqlSelectBase(string $where, string $extraOrderLimit = ''): string
{
    $sql = "
        SELECT
            p.id,
            p.id_producto,
            p.cod_producto,
            p.cod_cabys,
            p.nombre,
            p.id_unidad_medida,
            p.cantidad,
            p.precio_unitario,
            p.id_imp_general,
            p.id_categoria,
            p.med_registro_sanitario,
            p.med_id_forma_farmaceutica,
            p.partida_arancelaria,
            p.estado,
            um.codigo AS unidad_codigo,
            ig.codigo AS imp_codigo,
            c.id_producto_categoria AS categoria_codigo,
            c.nombre AS categoria_nombre,
            ff.codigo AS forma_codigo
        FROM producto p
        LEFT JOIN hacienda_unidad_medida um
            ON um.id_unidad_medida = p.id_unidad_medida
        LEFT JOIN hacienda_imp_general ig
            ON ig.id_imp_general = p.id_imp_general
        LEFT JOIN producto_categoria c
            ON c.id = p.id_categoria
        LEFT JOIN hacienda_forma_farmaceutica ff
            ON ff.id_forma_farmaceutica = p.med_id_forma_farmaceutica
        WHERE $where
        $extraOrderLimit
    ";
    return $sql;
}

/** Mapea fila SQL -> fila para tabla (listar) */
function mapRowListar(array $r): array
{
    $estadoTxt = ((int)$r['estado'] === 1) ? 'Activo'
        : (((int)$r['estado'] === 2) ? 'Inactivo' : 'Eliminado');

    return [
        'codigo'       => $r['cod_producto'],
        'detalle'      => $r['nombre'],
        'unidad'       => $r['unidad_codigo'] ?? '',
        // si no existe código de catálogo en la categoría, retornamos nombre como fallback en UI
        'categoria'    => ($r['categoria_codigo'] !== null && $r['categoria_codigo'] !== '') ? $r['categoria_codigo'] : ($r['categoria_nombre'] ?? ''),
        'cantidad'     => ($r['cantidad'] !== null ? (float)$r['cantidad'] : 0),
        'precio'       => ($r['precio_unitario'] !== null ? (float)$r['precio_unitario'] : 0.0),
        'codigoCabys'  => $r['cod_cabys'],
        'tarifaIva'    => $r['imp_codigo'] ?? '',
        'estado'       => $estadoTxt
    ];
}

/** Mapea fila SQL -> fila para exportar (cabeceras exactas) */
function mapRowExport(array $r): array
{
    return [
        'Codigo'              => (string)$r['cod_producto'],
        'CodigoCabys'         => (string)$r['cod_cabys'],
        'Detalle'             => (string)$r['nombre'],
        'Unidad'              => (string)($r['unidad_codigo'] ?? ''),
        'Cantidad'            => ($r['cantidad'] !== null ? (float)$r['cantidad'] : ''),
        'Precio'              => ($r['precio_unitario'] !== null ? (float)$r['precio_unitario'] : ''),
        'TarifaIVA'           => (string)($r['imp_codigo'] ?? ''),
        'Categoria'           => (string)(($r['categoria_codigo'] !== null && $r['categoria_codigo'] !== '') ? $r['categoria_codigo'] : ''),
        'RegistroMedicamento' => (string)($r['med_registro_sanitario'] ?? ''),
        'FormaFarmaceutica'   => (string)($r['forma_codigo'] ?? ''),
        'PartidaArancelaria'  => (string)($r['partida_arancelaria'] ?? '')
    ];
}

/* =========================================================
 * Acciones
 * ========================================================= */

switch ($accion) {
    /* --------------------------------------------
     * LISTAR (paginado)
     * -------------------------------------------- */
    case 'listar': {
            $pagina    = isset($body['pagina']) ? max(1, (int)$body['pagina']) : 1;
            $tamPagina = isset($body['tamPagina']) ? (int)$body['tamPagina'] : 25;
            if ($tamPagina < 1)      $tamPagina = 25;
            if ($tamPagina > 10000)  $tamPagina = 10000; // límite hard en backend
            $offset = ($pagina - 1) * $tamPagina;

            $filtros = is_array($body['filtros'] ?? null) ? $body['filtros'] : [];
            [$where, $paramsEcho] = buildWhereFiltros($empresaId, $filtros);

            // total
            $sqlTotal = "
            SELECT COUNT(*) AS total
            FROM producto p
            LEFT JOIN hacienda_unidad_medida um ON um.id_unidad_medida = p.id_unidad_medida
            LEFT JOIN hacienda_imp_general ig   ON ig.id_imp_general   = p.id_imp_general
            LEFT JOIN producto_categoria c      ON c.id = p.id_categoria
            LEFT JOIN hacienda_forma_farmaceutica ff ON ff.id_forma_farmaceutica = p.med_id_forma_farmaceutica
            WHERE $where
        ";
            $rowTotal = fetchAssoc($sqlTotal);
            $total = $rowTotal ? (int)$rowTotal['total'] : 0;

            // ajustar offset si excede total
            if ($offset >= $total && $total > 0) {
                $pagina = (int)ceil($total / $tamPagina);
                $offset = ($pagina - 1) * $tamPagina;
            }

            $orderLimit = " ORDER BY p.fecha_modificacion DESC, p.id DESC LIMIT $tamPagina OFFSET $offset ";
            $sql = sqlSelectBase($where, $orderLimit);
            $res = query($sql);
            if ($res === false) responseApi(500, 'Error consultando productos');

            $rows = [];
            while ($r = mysqli_fetch_assoc($res)) {
                $rows[] = mapRowListar($r);
            }

            responseApi(200, '', ['total' => $total, 'rows' => $rows]);
        }

        /* --------------------------------------------
     * EXPORTAR (máx 10k filas)
     * -------------------------------------------- */
    case 'exportar': {
            $maxFilas = isset($body['maxFilas']) ? (int)$body['maxFilas'] : 10000;
            if ($maxFilas < 1)      $maxFilas = 1;
            if ($maxFilas > 10000)  $maxFilas = 10000;

            $filtros = is_array($body['filtros'] ?? null) ? $body['filtros'] : [];
            [$where, $paramsEcho] = buildWhereFiltros($empresaId, $filtros);

            $orderLimit = " ORDER BY p.fecha_modificacion DESC, p.id DESC LIMIT $maxFilas ";
            $sql = sqlSelectBase($where, $orderLimit);
            $res = query($sql);
            if ($res === false) responseApi(500, 'Error exportando productos');

            $rows = [];
            while ($r = mysqli_fetch_assoc($res)) {
                $rows[] = mapRowExport($r);
            }

            addBitacora($usuarioId, 'producto', null, 'read', 'Exportación de productos (reporte)');

            responseApi(200, '', ['rows' => $rows, 'total' => count($rows)]);
        }

        /* --------------------------------------------
     * CATEGORÍAS (para select dinámico)
     * -------------------------------------------- */
    case 'categorias': {
            $sql = "
            SELECT id, id_producto_categoria, nombre, estado
            FROM producto_categoria
            WHERE id_empresa = " . intval($empresaId) . "
            ORDER BY nombre ASC
        ";
            $res = query($sql);
            if ($res === false) responseApi(500, 'Error consultando categorías');

            $rows = [];
            while ($r = mysqli_fetch_assoc($res)) {
                $rows[] = [
                    'id' => (int)$r['id'],
                    'idProductoCategoria' => (string)$r['id_producto_categoria'],
                    'nombre' => (string)$r['nombre'],
                    'estado' => isset($r['estado']) ? (int)$r['estado'] : 0
                ];
            }
            responseApi(200, '', ['rows' => $rows]);
        }

    default:
        responseApi(400, 'Acción inválida');
}
