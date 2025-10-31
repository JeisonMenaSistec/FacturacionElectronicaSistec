<?php

/* =========================================================
 * Helpers locales (lowerCamelCase)
 * ========================================================= */

function normalizarBool($v): int
{
    if (is_bool($v)) return $v ? 1 : 0;
    $x = strtolower(trim((string)$v));
    return in_array($x, ['1', 'true', 't', 'si', 'sí', 'on', 'activo'], true) ? 1 : 0;
}

function toIntOrNull($v)
{
    if ($v === null) return null;
    if ($v === '') return null;
    if (is_numeric($v)) return (int)$v;
    return null;
}

function toFloatOrNull($v)
{
    if ($v === null) return null;
    if ($v === '') return null;
    if (is_numeric($v)) return (float)$v;
    return null;
}

/** Convierte de time fecha mysql o NULL si vacío/inválido. */
function fechaFormatoOrNull($fecha)
{
    //validar que sea una fecha real y devolver fecha o null
    if ($fecha === null) return null;
    $f = trim((string)$fecha);
    if ($f === '') return null;
    $ts = strtotime($f);
    if ($ts === false) return null;
    return date('Y-m-d', $ts);
}

/** Siguiente id_producto (secuencial por empresa). */
function siguienteIdProductoPorEmpresa(int $idEmpresa): int
{
    $row = fetchAssoc("SELECT MAX(id_producto) AS max_id FROM producto WHERE id_empresa = " . intval($idEmpresa));
    $max = $row && isset($row['max_id']) ? (int)$row['max_id'] : 0;
    return $max > 0 ? $max + 1 : 1;
}

/**
 * Filtros para listar (buscarColumna/buscarTexto + idCategoria + estado).
 * buscarColumna whitelist: nombre, cod_producto, cod_cabys, id_categoria
 */
function buildFiltroWhere(int $idEmpresa, string $buscarColumna = '', string $buscarTexto = '', $idCategoria = '', $estado = ''): string
{
    $w = [];
    $w[] = "p.id_empresa = " . intval($idEmpresa);

    // Filtro columna específica (más eficiente que OR en muchas columnas)
    if ($buscarColumna !== '' && $buscarTexto !== '') {
        $map = [
            'nombre'        => 'p.nombre',
            'cod_producto'  => 'p.cod_producto',
            'cod_cabys'     => 'p.cod_cabys'
        ];
        if (isset($map[$buscarColumna])) {
            if ($buscarColumna === 'id_categoria') {
                if (is_numeric($buscarTexto)) {
                    $w[] = $map[$buscarColumna] . " = " . intval($buscarTexto);
                }
            } else {
                $q = sanitizeInput($buscarTexto);
                $w[] = $map[$buscarColumna] . " LIKE '%{$q}%'";
            }
        }
    }

    // Filtro adicional por categoría
    if ($idCategoria !== '' && $idCategoria !== null && is_numeric($idCategoria)) {
        $w[] = "p.id_categoria = " . intval($idCategoria);
    }

    // Filtro por estado (1 activo, 2 inactivo, 0 eliminado)
    if ($estado !== '' && $estado !== null) {
        if (is_numeric($estado)) {
            $w[] = "p.estado = " . intval($estado);
        } else {
            $e = strtolower($estado);
            if ($e === 'activo')   $w[] = "p.estado = 1";
            if ($e === 'inactivo') $w[] = "p.estado = 2";
            if ($e === 'eliminado') $w[] = "p.estado = 0";
        }
    }else {
        // Por defecto, excluir eliminados
        $w[] = "p.estado <> 0";
    }

    return implode(' AND ', $w);
}
