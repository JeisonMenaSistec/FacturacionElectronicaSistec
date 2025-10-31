<?php
require_once '../../system/session.php';
require_once LIB_PATH . '/helpers/mantenimiento_productos_herlper.php';


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
     * CATEGORÍAS (para selects y filtros)
     * -------------------------------------------- */
    case 'categorias': {
            $sql = "
            SELECT id, id_producto_categoria, nombre, descripcion, estado
            FROM producto_categoria
            WHERE id_empresa = " . intval($empresaId) . "
            ORDER BY nombre
        ";
            $res = query($sql);
            if ($res === false) responseApi(500, 'Error consultando categorías');

            $out = [];
            while ($r = mysqli_fetch_assoc($res)) {
                $out[] = [
                    'id' => (int)$r['id'],
                    'idProductoCategoria' => (int)$r['id_producto_categoria'],
                    'nombre' => $r['nombre'],
                    'descripcion' => $r['descripcion'],
                    'estado' => (int)$r['estado']
                ];
            }
            responseApi(200, '', $out);
        }


        /* --------------------------------------------
     * FORMA FARMACEUTICA (para selects y filtros)
     * -------------------------------------------- */
    case 'formaFarmaceutica': {
            $sql = "
            SELECT id_forma_farmaceutica, codigo, nombre
            FROM hacienda_forma_farmaceutica
            ORDER BY nombre ASC
        ";
            $res = query($sql);
            if ($res === false) responseApi(500, 'Error consultando formas farmacéuticas');

            $out = [];
            while ($r = mysqli_fetch_assoc($res)) {
                $out[] = [
                    'idFormaFarmaceutica' => (int)$r['id_forma_farmaceutica'],
                    'codigo' => $r['codigo'],
                    'nombre' => $r['nombre']
                ];
            }
            responseApi(200, '', $out);
        }

        /* --------------------------------------------
     * LISTAR
     * Parámetros opcionales:
     *  - buscarColumna, buscarTexto, idCategoria, estado
     * -------------------------------------------- */
    case 'listar': {
            $buscarColumna = isset($body['buscarColumna']) ? trim((string)$body['buscarColumna']) : '';
            $buscarTexto   = isset($body['buscarTexto']) ? trim((string)$body['buscarTexto']) : '';
            $idCategoria   = isset($body['idCategoria']) ? $body['idCategoria'] : '';
            $estado        = isset($body['estado']) ? $body['estado'] : '';

            // --- NUEVO: paginación segura ---
            $pagina    = isset($body['pagina']) ? max(1, (int)$body['pagina']) : 1;
            $tamPagina = isset($body['tamPagina']) ? (int)$body['tamPagina'] : 25;
            if ($tamPagina < 1)   $tamPagina = 25;
            if ($tamPagina > 3000) $tamPagina = 3000;
            $offset = ($pagina - 1) * $tamPagina;

            $where = buildFiltroWhere($empresaId, $buscarColumna, $buscarTexto, $idCategoria, $estado);

            // --- NUEVO: total de filas para la paginación ---
            $sqlTotal = "SELECT COUNT(*) AS total FROM producto p WHERE $where";
            $rowTotal = fetchAssoc($sqlTotal);
            $total = $rowTotal ? (int)$rowTotal['total'] : 0;

            // Ajustar offset si se va fuera de rango (p.ej. al borrar filtros)
            if ($offset >= $total && $total > 0) {
                $pagina = (int)ceil($total / $tamPagina);
                $offset = ($pagina - 1) * $tamPagina;
            }

            // SELECT paginado
            $sql = "
                SELECT 
                    p.id, p.id_producto, p.id_empresa, p.cod_producto, p.cod_cabys, p.nombre,
                    p.id_categoria, p.id_unidad_medida, p.cantidad, p.precio_unitario, p.id_imp_general,
                    p.descripcion, p.es_medicamento,
                    p.med_registro_sanitario, p.med_fecha_v_registro, p.med_id_tipo_medicamento,
                    p.med_principio_activo, p.med_concentracion, p.med_id_forma_farmaceutica,
                    p.codigo_barras, p.marca, p.modelo, p.notas, p.sku,
                    p.estado, p.id_usuario_creacion, p.fecha_creacion, p.id_usuario_modificacion, p.fecha_modificacion
                FROM producto p
                WHERE $where
                ORDER BY p.id DESC, p.id DESC
                LIMIT $tamPagina OFFSET $offset
            ";
            $res = query($sql);
            if ($res === false) responseApi(500, 'Error consultando productos');

            $data = [];
            while ($r = mysqli_fetch_assoc($res)) {
                $data[] = [
                    'id'             => (int)$r['id'],
                    'idProducto'     => (int)$r['id_producto'],
                    'codProducto'    => $r['cod_producto'],
                    'nombre'         => $r['nombre'],
                    'idUnidadMedida' => isset($r['id_unidad_medida']) ? (int)$r['id_unidad_medida'] : null,
                    'idCategoria'    => (int)$r['id_categoria'],
                    'cantidad'       => isset($r['cantidad']) ? (float)$r['cantidad'] : null,
                    'precioUnitario' => isset($r['precio_unitario']) ? (float)$r['precio_unitario'] : null,
                    'codCabys'       => $r['cod_cabys'],
                    'estado'         => (int)$r['estado']
                ];
            }


            responseApi(200, '', ['total' => $total, 'data' => $data]);
        }

        /* --------------------------------------------
     * OBTENER
     * Parámetros: id (PK)
     * -------------------------------------------- */
    case 'obtener': {
            $id = isset($body['id']) ? (int)$body['id'] : 0;
            if ($id <= 0) responseApi(400, 'id requerido');

            $r = fetchAssoc("
            SELECT * FROM producto 
            WHERE id = " . intval($id) . " AND id_empresa = " . intval($empresaId) . " AND estado <> 0
            LIMIT 1
        ");
            if (!$r) responseApi(404, 'Producto no encontrado');

            $data = [
                'id'                   => (int)$r['id'],
                'idProducto'           => (int)$r['id_producto'],
                'idEmpresa'            => (int)$r['id_empresa'],
                'codProducto'          => $r['cod_producto'],
                'codCabys'             => $r['cod_cabys'],
                'nombre'               => $r['nombre'],
                'idCategoria'          => (int)$r['id_categoria'],
                'idUnidadMedida'       => isset($r['id_unidad_medida']) ? (int)$r['id_unidad_medida'] : null,
                'cantidad'             => isset($r['cantidad']) ? (float)$r['cantidad'] : null,
                'precioUnitario'       => isset($r['precio_unitario']) ? (float)$r['precio_unitario'] : null,
                'idImpGeneral'         => isset($r['id_imp_general']) ? (int)$r['id_imp_general'] : null,
                'descripcion'          => $r['descripcion'],
                'partidaArancelaria' => $r['partida_arancelaria'],
                'esMedicamento'        => (int)$r['es_medicamento'],
                'medRegistroSanitario' => $r['med_registro_sanitario'],
                'medFechaVRegistro'    => isset($r['med_fecha_v_registro']) ? (string)$r['med_fecha_v_registro'] : null,
                'medIdTipoMedicamento' => isset($r['med_id_tipo_medicamento']) ? (int)$r['med_id_tipo_medicamento'] : null,
                'medPrincipioActivo'   => $r['med_principio_activo'],
                'medConcentracion'     => $r['med_concentracion'],
                'medFormaFarmaceutica' => isset($r['med_id_forma_farmaceutica']) ? (int)$r['med_id_forma_farmaceutica'] : null,
                'codigoBarras'         => $r['codigo_barras'],
                'marca'                => $r['marca'],
                'modelo'               => $r['modelo'],
                'notas'                => $r['notas'],
                'sku'                  => $r['sku'],
                'estado'               => (int)$r['estado'],
                'fechaCreacion'        => $r['fecha_creacion'],
                'fechaModificacion'    => $r['fecha_modificacion']
            ];
            responseApi(200, '', $data);
        }

        /* --------------------------------------------
     * CREAR
     * -------------------------------------------- */
    case 'crear': {
            $codCabys        = sanitizeInput($body['codCabys'] ?? '');
            $codProducto     = sanitizeInput($body['codProducto'] ?? '');
            $nombre          = sanitizeInput($body['nombre'] ?? '');
            $idCategoria     = toIntOrNull($body['idCategoria'] ?? 0) ?? 0;
            $idUnidadMedida  = toIntOrNull($body['idUnidadMedida'] ?? null);
            $cantidad        = toFloatOrNull($body['cantidad'] ?? null);
            $precioUnitario  = toFloatOrNull($body['precioUnitario'] ?? null);
            $idImpGeneral    = toIntOrNull($body['idImpGeneral'] ?? null);
            $descripcion     = sanitizeInput($body['descripcion'] ?? '');
            $partidaArancelaria = sanitizeInput($body['partidaArancelaria'] ?? '');

            $esMedicamento   = normalizarBool($body['esMedicamento'] ?? 0);
            $medReg          = sanitizeInput($body['medRegistroSanitario'] ?? '');
            $medVence        = fechaFormatoOrNull($body['medFechaVRegistro'] ?? '');
            $medTipo         = toIntOrNull($body['medIdTipoMedicamento'] ?? null);
            $medPrincipio    = sanitizeInput($body['medPrincipioActivo'] ?? '');
            $medConc         = sanitizeInput($body['medConcentracion'] ?? '');
            $medForma        = toIntOrNull($body['medFormaFarmaceutica'] ?? '');

            $codigoBarras    = sanitizeInput($body['codigoBarras'] ?? '');
            $marca           = sanitizeInput($body['marca'] ?? '');
            $modelo          = sanitizeInput($body['modelo'] ?? '');
            $notas           = sanitizeInput($body['notas'] ?? '');
            $sku             = sanitizeInput($body['sku'] ?? '');

            if ($codCabys === '' || $nombre === '') {
                responseApi(400, 'Faltan campos obligatorios: CABYS y nombre.');
            }

            //validacion para no permitir el mismo codigo en la misma empresa
            if ($codProducto !== '') {
                $existeCod = fetchAssoc("SELECT id FROM producto WHERE id_empresa = " . intval($empresaId) . " AND cod_producto = '" . $codProducto . "' LIMIT 1");
                if ($existeCod) {
                    responseApi(400, 'El código de producto ya existe en la empresa.');
                }
            }


            $idProducto = siguienteIdProductoPorEmpresa($empresaId);
            $ahora = fActual();

            $sql = "
            INSERT INTO producto
            (
                id_producto, id_empresa, cod_producto, cod_cabys, nombre,
                id_categoria, id_unidad_medida, cantidad, precio_unitario, id_imp_general,
                descripcion, es_medicamento,
                med_registro_sanitario, med_fecha_v_registro, med_id_tipo_medicamento,
                med_principio_activo, med_concentracion, med_id_forma_farmaceutica,
                codigo_barras, marca, modelo, notas, sku,
                estado, id_usuario_creacion, fecha_creacion, id_usuario_modificacion, fecha_modificacion
            )
            VALUES
            (
                " . intval($idProducto) . ",
                " . intval($empresaId) . ",
                " . ($codProducto !== '' ? "'" . $codProducto . "'" : "NULL") . ",
                '" . $codCabys . "',
                '" . $nombre . "',
                " . intval($idCategoria) . ",
                " . ($idUnidadMedida !== null ? intval($idUnidadMedida) : "NULL") . ",
                " . ($cantidad !== null ? $cantidad : "NULL") . ",
                " . ($precioUnitario !== null ? $precioUnitario : "NULL") . ",
                " . ($idImpGeneral !== null ? intval($idImpGeneral) : "NULL") . ",
                " . ($descripcion !== '' ? "'" . $descripcion . "'" : "NULL") . ",
                " . ($partidaArancelaria !== '' ? "'" . $partidaArancelaria . "'" : "NULL") . ",
                " . intval($esMedicamento) . ",
                " . ($medReg !== '' ? "'" . $medReg . "'" : "NULL") . ",
                " . ($medVence !== null ?  "'" . $medVence . "'" : "NULL") . ",
                " . ($medTipo !== null ? intval($medTipo) : "NULL") . ",
                " . ($medPrincipio !== '' ? "'" . $medPrincipio . "'" : "NULL") . ",
                " . ($medConc !== '' ? "'" . $medConc . "'" : "NULL") . ",
                " . ($medForma !== '' ? "'" . $medForma . "'" : "NULL") . ",
                " . ($codigoBarras !== '' ? "'" . $codigoBarras . "'" : "NULL") . ",
                " . ($marca !== '' ? "'" . $marca . "'" : "NULL") . ",
                " . ($modelo !== '' ? "'" . $modelo . "'" : "NULL") . ",
                " . ($notas !== '' ? "'" . $notas . "'" : "NULL") . ",
                " . ($sku !== '' ? "'" . $sku . "'" : "NULL") . ",
                1,
                " . intval($usuarioId) . ",
                '" . $ahora . "',
                " . intval($usuarioId) . ",
                '" . $ahora . "'
            )
        ";
            $aff = update($sql);
            if ($aff <= 0) responseApi(500, 'No se pudo crear el producto');

            $nuevoId = lastInsertId();
            addBitacora($usuarioId, 'producto', $nuevoId, 'create', 'Producto creado');

            responseApi(200, 'Producto creado', [
                'id' => (int)$nuevoId,
                'idProducto' => (int)$idProducto
            ]);
        }

        /* --------------------------------------------
     * ACTUALIZAR
     * -------------------------------------------- */
    case 'actualizar': {
            $id = isset($body['id']) ? (int)$body['id'] : 0;
            if ($id <= 0) responseApi(400, 'id requerido');

            $existe = fetchAssoc("SELECT id, id_empresa FROM producto WHERE id = " . intval($id) . " LIMIT 1");
            if (!$existe || (int)$existe['id_empresa'] !== $empresaId) {
                responseApi(404, 'Producto no encontrado en su empresa');
            }

            $codCabys        = array_key_exists('codCabys', $body) ? sanitizeInput($body['codCabys']) : null;
            $codProducto     = array_key_exists('codProducto', $body) ? sanitizeInput($body['codProducto']) : null;
            $nombre          = array_key_exists('nombre', $body) ? sanitizeInput($body['nombre']) : null;
            $idCategoria     = array_key_exists('idCategoria', $body) ? toIntOrNull($body['idCategoria']) : null;
            $idUnidadMedida  = array_key_exists('idUnidadMedida', $body) ? toIntOrNull($body['idUnidadMedida']) : null;
            $cantidad        = array_key_exists('cantidad', $body) ? toFloatOrNull($body['cantidad']) : null;
            $precioUnitario  = array_key_exists('precioUnitario', $body) ? toFloatOrNull($body['precioUnitario']) : null;
            $idImpGeneral    = array_key_exists('idImpGeneral', $body) ? toIntOrNull($body['idImpGeneral']) : null;
            $descripcion     = array_key_exists('descripcion', $body) ? sanitizeInput($body['descripcion']) : null;
            $partidaArancelaria = array_key_exists('partidaArancelaria', $body) ? sanitizeInput($body['partidaArancelaria']) : null;

            $esMedicamento   = array_key_exists('esMedicamento', $body) ? normalizarBool($body['esMedicamento']) : null;
            $medReg          = array_key_exists('medRegistroSanitario', $body) ? sanitizeInput($body['medRegistroSanitario']) : null;
            $medVence        = array_key_exists('medFechaVRegistro', $body) ? fechaFormatoOrNull($body['medFechaVRegistro']) : null;
            $medTipo         = array_key_exists('medIdTipoMedicamento', $body) ? toIntOrNull($body['medIdTipoMedicamento']) : null;
            $medPrincipio    = array_key_exists('medPrincipioActivo', $body) ? sanitizeInput($body['medPrincipioActivo']) : null;
            $medConc         = array_key_exists('medConcentracion', $body) ? sanitizeInput($body['medConcentracion']) : null;
            $medForma        = array_key_exists('medFormaFarmaceutica', $body) ? toIntOrNull($body['medFormaFarmaceutica']) : null;

            $codigoBarras    = array_key_exists('codigoBarras', $body) ? sanitizeInput($body['codigoBarras']) : null;
            $marca           = array_key_exists('marca', $body) ? sanitizeInput($body['marca']) : null;
            $modelo          = array_key_exists('modelo', $body) ? sanitizeInput($body['modelo']) : null;
            $notas           = array_key_exists('notas', $body) ? sanitizeInput($body['notas']) : null;
            $sku             = array_key_exists('sku', $body) ? sanitizeInput($body['sku']) : null;

            $campos = [];
            if ($codCabys !== null)        $campos[] = "cod_cabys = '" . $codCabys . "'";
            if ($codProducto !== null)     $campos[] = ($codProducto !== '' ? "cod_producto = '" . $codProducto . "'" : "cod_producto = NULL");
            if ($nombre !== null)          $campos[] = "nombre = '" . $nombre . "'";
            if ($idCategoria !== null)     $campos[] = "id_categoria = " . (int)$idCategoria;
            if ($idUnidadMedida !== null)  $campos[] = ($idUnidadMedida !== null ? "id_unidad_medida = " . (int)$idUnidadMedida : "id_unidad_medida = NULL");
            if ($cantidad !== null)        $campos[] = ($cantidad !== null ? "cantidad = " . $cantidad : "cantidad = NULL");
            if ($precioUnitario !== null)  $campos[] = ($precioUnitario !== null ? "precio_unitario = " . $precioUnitario : "precio_unitario = NULL");
            if ($idImpGeneral !== null)    $campos[] = ($idImpGeneral !== null ? "id_imp_general = " . (int)$idImpGeneral : "id_imp_general = NULL");
            if ($descripcion !== null)     $campos[] = ($descripcion !== '' ? "descripcion = '" . $descripcion . "'" : "descripcion = NULL");
            if ($partidaArancelaria !== null) $campos[] = ($partidaArancelaria !== '' ? "partida_arancelaria = '" . $partidaArancelaria . "'" : "partida_arancelaria = NULL");

            if ($esMedicamento !== null)   $campos[] = "es_medicamento = " . (int)$esMedicamento;
            if ($medReg !== null)          $campos[] = ($medReg !== '' ? "med_registro_sanitario = '" . $medReg . "'" : "med_registro_sanitario = NULL");
            if ($medVence !== null)        $campos[] = ($medVence !== null ? "med_fecha_v_registro = '" . $medVence . "'" : "med_fecha_v_registro = NULL");
            if ($medTipo !== null)         $campos[] = ($medTipo !== null ? "med_id_tipo_medicamento = " . (int)$medTipo : "med_id_tipo_medicamento = NULL");
            if ($medPrincipio !== null)    $campos[] = ($medPrincipio !== '' ? "med_principio_activo = '" . $medPrincipio . "'" : "med_principio_activo = NULL");
            if ($medConc !== null)         $campos[] = ($medConc !== '' ? "med_concentracion = '" . $medConc . "'" : "med_concentracion = NULL");
            if ($medForma !== null)        $campos[] = ($medForma !== '' ? "med_id_forma_farmaceutica = '" . $medForma . "'" : "med_id_forma_farmaceutica = NULL");

            if ($codigoBarras !== null)    $campos[] = ($codigoBarras !== '' ? "codigo_barras = '" . $codigoBarras . "'" : "codigo_barras = NULL");
            if ($marca !== null)           $campos[] = ($marca !== '' ? "marca = '" . $marca . "'" : "marca = NULL");
            if ($modelo !== null)          $campos[] = ($modelo !== '' ? "modelo = '" . $modelo . "'" : "modelo = NULL");
            if ($notas !== null)           $campos[] = ($notas !== '' ? "notas = '" . $notas . "'" : "notas = NULL");
            if ($sku !== null)             $campos[] = ($sku !== '' ? "sku = '" . $sku . "'" : "sku = NULL");

            $campos[] = "id_usuario_modificacion = " . intval($usuarioId);
            $campos[] = "fecha_modificacion = '" . fActual() . "'";

            if (empty($campos)) responseApi(400, 'No hay cambios para actualizar');

            $sql = "UPDATE producto SET " . implode(', ', $campos) .
                " WHERE id = " . intval($id) . " AND id_empresa = " . intval($empresaId) . " LIMIT 1";

            $aff = update($sql);
            if ($aff < 0) responseApi(500, 'Error actualizando producto');

            addBitacora($usuarioId, 'producto', $id, 'update', 'Producto actualizado');
            responseApi(200, 'Producto actualizado', ['id' => (int)$id]);
        }

        /* --------------------------------------------
     * ACTIVAR (estado = 1)
     * -------------------------------------------- */
    case 'activar': {
            $id = isset($body['id']) ? (int)$body['id'] : 0;
            if ($id <= 0) responseApi(400, 'id requerido');

            $existe = fetchAssoc("SELECT id, id_empresa FROM producto WHERE id = " . intval($id) . " LIMIT 1");
            if (!$existe || (int)$existe['id_empresa'] !== $empresaId) {
                responseApi(404, 'Producto no encontrado en su empresa');
            }

            $sql = "
            UPDATE producto
            SET estado = 1,
                id_usuario_modificacion = " . intval($usuarioId) . ",
                fecha_modificacion = '" . fActual() . "'
            WHERE id = " . intval($id) . " AND id_empresa = " . intval($empresaId) . "
            LIMIT 1
        ";
            $aff = update($sql);
            if ($aff < 0) responseApi(500, 'Error activando producto');

            addBitacora($usuarioId, 'producto', $id, 'update', 'Producto activado (estado=1)');
            responseApi(200, 'Producto activado');
        }

        /* --------------------------------------------
     * DESACTIVAR (estado = 2)
     * -------------------------------------------- */
    case 'desactivar': {
            $id = isset($body['id']) ? (int)$body['id'] : 0;
            if ($id <= 0) responseApi(400, 'id requerido');

            $existe = fetchAssoc("SELECT id, id_empresa FROM producto WHERE id = " . intval($id) . " LIMIT 1");
            if (!$existe || (int)$existe['id_empresa'] !== $empresaId) {
                responseApi(404, 'Producto no encontrado en su empresa');
            }

            $sql = "
            UPDATE producto
            SET estado = 2,
                id_usuario_modificacion = " . intval($usuarioId) . ",
                fecha_modificacion = '" . fActual() . "'
            WHERE id = " . intval($id) . " AND id_empresa = " . intval($empresaId) . "
            LIMIT 1
        ";
            $aff = update($sql);
            if ($aff < 0) responseApi(500, 'Error desactivando producto');

            addBitacora($usuarioId, 'producto', $id, 'update', 'Producto desactivado (estado=2)');
            responseApi(200, 'Producto desactivado');
        }

        /* --------------------------------------------
     * ELIMINAR (lógico, estado = 0)
     * -------------------------------------------- */
    case 'eliminar': {
            $id = isset($body['id']) ? (int)$body['id'] : 0;
            if ($id <= 0) responseApi(400, 'id requerido');

            $existe = fetchAssoc("SELECT id, id_empresa FROM producto WHERE id = " . intval($id) . " LIMIT 1");
            if (!$existe || (int)$existe['id_empresa'] !== $empresaId) {
                responseApi(404, 'Producto no encontrado en su empresa');
            }

            $sql = "
            UPDATE producto
            SET estado = 0,
                id_usuario_modificacion = " . intval($usuarioId) . ",
                fecha_modificacion = '" . fActual() . "'
            WHERE id = " . intval($id) . " AND id_empresa = " . intval($empresaId) . "
            LIMIT 1
        ";
            $aff = update($sql);
            if ($aff < 0) responseApi(500, 'Error eliminando producto');

            addBitacora($usuarioId, 'producto', $id, 'delete', 'Producto eliminado (estado=0)');
            responseApi(200, 'Producto eliminado');
        }

    default:
        responseApi(400, 'Acción inválida');
}
