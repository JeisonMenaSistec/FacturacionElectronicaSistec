<?php
require_once '../../system/session.php';
header('Content-Type: application/json; charset=utf-8');


// Rutas de almacenamiento
const DIR_UPLOAD_IMG  = __DIR__ . '/../../uploads/img';
const DIR_UPLOAD_KEYS = __DIR__ . '/../../uploads/keys';


// Body
$raw  = file_get_contents('php://input');
$body = json_decode($raw, true) ?? [];
$accion = isset($body['accion']) ? trim((string)$body['accion']) : '';
if ($accion === '') {
    responseApi(400, 'Acción requerida');
}



// ---------------------------
// Acciones
// ---------------------------
switch ($accion) {

    // OBTENER EMPRESA
    case 'obtenerEmpresa': {
            $sql = "
            SELECT 
                e.id_empresa                     AS empresaId,
                e.nombre_legal                   AS nombreLegal,
                e.nombre_comercial               AS nombreComercial,
                e.identificacion                 AS identificacion,
                e.telefono                       AS telefono,
                e.correo_electronico             AS correoElectronico,
                e.direccion_exacta               AS direccionExacta,
                e.id_tipo_identificacion         AS tipoIdentificacionId,
                e.telefono2                      AS telefono2,
                e.fax                            AS fax,
                e.apartado_postal                AS apartadoPostal,
                e.codigo_area                    AS codigoArea,
                e.convertir_tiquete_a_factura    AS convertirTiqueteAFactura,
                e.regimen_agropecuario           AS regimenAgropecuario,
                e.cargo_automatico_plan          AS cargoAutomaticoPlan,
                e.visto_bueno_facturas           AS vistoBuenoFacturas,
                e.reporte_mensual                AS reporteMensual,
                e.provincia_cod                  AS provinciaCod,
                e.canton_cod                     AS cantonCod,
                e.distrito_cod                   AS distritoCod,
                e.barrio_cod                     AS barrioCod,
                e.estado                         AS estado,
                e.logo_url                       AS logoUrl
            FROM empresa e
            WHERE e.id_empresa = " . intval($usuario->id_empresa) . "
            LIMIT 1";
            $row = fetchAssoc($sql);
            if (!$row) responseApi(404, 'Empresa no encontrada');
            echo json_encode($row);
            exit;
        }

        // GUARDAR EMPRESA
    case 'guardarEmpresa': {
            $empresa = $body['empresa'] ?? null;
            if (!$empresa || !is_array($empresa)) responseApi(400, 'Payload empresa inválido');

            $tipoIdentificacionId = isset($empresa['tipoIdentificacionId']) && $empresa['tipoIdentificacionId'] !== ''
                ? (int)$empresa['tipoIdentificacionId'] : 'NULL';

            $identificacion   = sanitizeInput(sanitizeText((string)($empresa['identificacion'] ?? ''), 20));
            $nombreComercial  = sanitizeInput(sanitizeText((string)($empresa['nombreComercial'] ?? ''), 200));
            $nombreLegal      = sanitizeInput(sanitizeText((string)($empresa['nombreLegal'] ?? ''), 200));
            $correoElectronico = sanitizeInput(sanitizeText((string)($empresa['correoElectronico'] ?? ''), 255));
            $codigoArea       = sanitizeInput(sanitizeDigits((string)($empresa['codigoArea'] ?? ''), 4));
            $telefono         = sanitizeInput(sanitizeDigits((string)($empresa['telefono'] ?? ''), 25));
            $telefono2        = sanitizeInput(sanitizeDigits((string)($empresa['telefono2'] ?? ''), 25));
            $fax              = sanitizeInput(sanitizeDigits((string)($empresa['fax'] ?? ''), 25));
            $apartadoPostal   = sanitizeInput(sanitizeText((string)($empresa['apartadoPostal'] ?? ''), 50));
            $direccionExacta  = sanitizeInput(sanitizeText((string)($empresa['direccionExacta'] ?? ''), 300));

            $provinciaCod     = sanitizeInput((string)($empresa['provinciaCod'] ?? ''));
            $cantonCod        = sanitizeInput((string)($empresa['cantonCod'] ?? ''));
            $distritoCod      = sanitizeInput((string)($empresa['distritoCod'] ?? ''));
            $barrioCod        = sanitizeInput((string)($empresa['barrioCod'] ?? ''));

            $convertirTiq     = !empty($empresa['convertirTiqueteAFactura']) ? 1 : 0;
            $regimenAgro      = !empty($empresa['regimenAgropecuario']) ? 1 : 0;
            $cargoAuto        = !empty($empresa['cargoAutomaticoPlan']) ? 1 : 0;
            $vistoBueno       = !empty($empresa['vistoBuenoFacturas']) ? 1 : 0;
            $reporteMensual   = !empty($empresa['reporteMensual']) ? 1 : 0;

            $sql = "
            UPDATE empresa SET
                id_tipo_identificacion = {$tipoIdentificacionId},
                identificacion         = '{$identificacion}',
                nombre_comercial       = '{$nombreComercial}',
                nombre_legal           = '{$nombreLegal}',
                correo_electronico     = '{$correoElectronico}',
                codigo_area            = '{$codigoArea}',
                telefono               = '{$telefono}',
                telefono2              = '{$telefono2}',
                fax                    = '{$fax}',
                apartado_postal        = '{$apartadoPostal}',
                direccion_exacta       = '{$direccionExacta}',
                provincia_cod          = '{$provinciaCod}',
                canton_cod             = '{$cantonCod}',
                distrito_cod           = '{$distritoCod}',
                barrio_cod             = '{$barrioCod}',
                convertir_tiquete_a_factura = {$convertirTiq},
                regimen_agropecuario   = {$regimenAgro},
                cargo_automatico_plan  = {$cargoAuto},
                visto_bueno_facturas   = {$vistoBueno},
                reporte_mensual        = {$reporteMensual},
                id_usuario_modificacion= " . ($usuario->id_usuario !== null ? intval($usuario->id_usuario) : 'NULL') . ",
                fecha_modificacion     = '" . fActual() . "'
            WHERE id_empresa = " . intval($usuario->id_empresa) . "
            LIMIT 1";
            update($sql);
            responseApi(200, 'Empresa actualizada', null);
        }

        // LOGO: SUBIR
    case 'logoSubir': {
            $contentBase64 = $body['contentBase64'] ?? '';
            $sizeBytes     = (int)($body['sizeBytes'] ?? 0);

            if (!$contentBase64) responseApi(400, 'Imagen requerida');
            if ($sizeBytes > 1024 * 1024) responseApi(400, 'Tamaño > 1MB no permitido');

            $res = guardarImagen($contentBase64);
            if (!$res['ok']) responseApi(400, $res['msg'] ?? 'No se pudo guardar la imagen');

            $logoUrl = sanitizeInput($res['ruta']);
            update("
            UPDATE empresa
            SET logo_url = '{$logoUrl}',
                id_usuario_modificacion = " . ($usuario->id_usuario !== null ? intval($usuario->id_usuario) : 'NULL') . ",
                fecha_modificacion = '" . fActual() . "'
            WHERE id_empresa = " . intval($usuario->id_empresa) . "
            LIMIT 1
        ");

            echo json_encode(['ok' => true, 'logoUrl' => $res['ruta']]);
            exit;
        }

        // LOGO: ELIMINAR (solo limpia logo_url)
    case 'logoEliminar': {
            update("
            UPDATE empresa
            SET logo_url = NULL,
                id_usuario_modificacion = " . ($usuario->id_usuario !== null ? intval($usuario->id_usuario) : 'NULL') . ",
                fecha_modificacion = '" . fActual() . "'
            WHERE id_empresa = " . intval($usuario->id_empresa) . "
            LIMIT 1
        ");
            echo json_encode(['ok' => true]);
            exit;
        }

        // CERTIFICADO: OBTENER (sin ocultar campos)
    case 'obtenerCertificado': {
            $sql = "
            SELECT 
                id_empresa_certificado_digital                 AS idCert,
                id_empresa              AS idEmpresa,
                usuario_atv             AS usuarioAtv,
                contrasena_atv          AS contrasenaAtv,
                pin_llave_cripto        AS pinLlaveCripto,
                llave_nombre_original   AS llaveNombreOriginal,
                llave_mime              AS llaveMime,
                llave_size              AS llaveSize,
                llave_path_enc          AS llavePathEnc,
                fecha_creacion          AS fechaCreacion,
                fecha_registro          AS fechaRegistro,
                fecha_vencimiento       AS fechaVencimiento,
                estado,
                id_usuario_creacion     AS idUsuarioCreacion,
                id_usuario_modificacion AS idUsuarioModificacion,
                fecha_modificacion      AS fechaModificacion
            FROM empresa_certificado_digital
            WHERE id_empresa = " . intval($usuario->id_empresa) . "
            ORDER BY id_empresa_certificado_digital DESC
            LIMIT 1";
            $row = fetchAssoc($sql);
            echo json_encode($row ?: null);
            exit;
        }

        // CERTIFICADO: GUARDAR (sin fechas desde frontend)
    case 'guardarCertificado': {
            $cert = $body['cert'] ?? null;
            if (!$cert || !is_array($cert)) responseApi(400, 'Payload de certificado inválido');

            $usuarioAtv    = sanitizeInput(sanitizeText((string)($cert['usuarioAtv'] ?? ''), 100));
            $contrasenaAtv = sanitizeInput(sanitizeText((string)($cert['contrasenaAtv'] ?? ''), 128));
            $pinLlave      = sanitizeInput(sanitizeText((string)($cert['pinLlave'] ?? ''), 128));

            $llaveNombreOriginal = null;
            $llaveMime           = null;
            $llaveSize           = null;
            $llavePathRel        = null;

            if (!empty($cert['llaveBase64'])) {
                $dec = decodeDataUrl((string)$cert['llaveBase64']);
                if (!$dec['ok']) responseApi(400, $dec['msg'] ?? 'Llave inválida');

                $nombreOri = (string)($cert['llaveNombre'] ?? 'key.pun');
                $mime      = (string)($cert['llaveMime']   ?? 'application/octet-stream');

                $save = guardarLlavePlano($dec['bytes'], $nombreOri);
                if (!$save['ok']) responseApi(400, $save['msg'] ?? 'Error al guardar la llave');

                $llaveNombreOriginal = sanitizeInput(sanitizeText($save['nombre'], 255));
                $llaveMime           = sanitizeInput(sanitizeText($mime, 100));
                $llaveSize           = (int)$save['size'];
                $llavePathRel        = sanitizeInput($save['ruta']); // /uploads/keys/xxx.ext
            }

            $existe = fetchAssoc("SELECT id_empresa_certificado_digital FROM empresa_certificado_digital WHERE id_empresa=" . intval($usuario->id_empresa) . " ORDER BY id_empresa_certificado_digital DESC LIMIT 1");

            if ($existe) {
                $setLlave = '';
                if ($llavePathRel !== null) {
                    $setLlave = ",
                    llave_nombre_original = '{$llaveNombreOriginal}',
                    llave_mime            = '{$llaveMime}',
                    llave_size            = " . (int)$llaveSize . ",
                    llave_path_enc        = '{$llavePathRel}'";
                }
                $sql = "
                UPDATE empresa_certificado_digital
                SET usuario_atv = '{$usuarioAtv}',
                    contrasena_atv = '{$contrasenaAtv}',
                    pin_llave_cripto = '{$pinLlave}',
                    id_usuario_modificacion = " . ($usuario->id_usuario !== null ? intval($usuario->id_usuario) : 'NULL') . ",
                    fecha_modificacion = '" . fActual() . "'
                    {$setLlave}
                WHERE id_empresa_certificado_digital = " . intval($existe['id_empresa_certificado_digital']) . "
                LIMIT 1";
                update($sql);
            } else {
                $sql = "
                INSERT INTO empresa_certificado_digital (
                    id_empresa, usuario_atv, contrasena_atv, pin_llave_cripto,
                    llave_nombre_original, llave_mime, llave_size, llave_path_enc,
                    fecha_creacion, fecha_registro, fecha_vencimiento, estado,
                    id_usuario_creacion
                ) VALUES (
                    " . intval($usuario->id_empresa) . ",
                    '{$usuarioAtv}',
                    '{$contrasenaAtv}',
                    '{$pinLlave}',
                    " . ($llaveNombreOriginal !== null ? "'{$llaveNombreOriginal}'" : "NULL") . ",
                    " . ($llaveMime !== null ? "'{$llaveMime}'" : "NULL") . ",
                    " . ($llaveSize !== null ? (int)$llaveSize : "NULL") . ",
                    " . ($llavePathRel !== null ? "'{$llavePathRel}'" : "NULL") . ",
                    '" . fActual() . "', NULL, NULL, 1,
                    " . ($usuario->id_usuario !== null ? intval($usuario->id_usuario) : 'NULL') . "
                )";
                update($sql);
            }

            echo json_encode(['ok' => true]);
            exit;
        }

        // CERTIFICADO: ELIMINAR LLAVE (poner archivo en cero; no borrar fila)
    case 'eliminarLlaveCripto': {
            $row = fetchAssoc("
            SELECT id_empresa_certificado_digital, llave_path_enc
            FROM empresa_certificado_digital
            WHERE id_empresa = " . intval($usuario->id_empresa) . "
            AND estado = 1 
            ORDER BY id_empresa_certificado_digital DESC LIMIT 1
            ");
            if (!$row) responseApi(404, 'No hay certificado');

            update("
            UPDATE empresa_certificado_digital
            SET llave_nombre_original = NULL,
                llave_mime = NULL,
                llave_size = 0,
                estado=0, 
                id_usuario_modificacion = " . $usuario->id_usuario . ",
                fecha_modificacion = '" . fActual() . "'
            WHERE id_empresa_certificado_digital = " . intval($row['id_empresa_certificado_digital']) . "
            LIMIT 1
            ");

            echo json_encode(['ok' => true]);
            exit;
        }

    default:
        responseApi(400, 'Acción no soportada');
}
