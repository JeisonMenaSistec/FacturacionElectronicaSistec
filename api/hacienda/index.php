<?php
// api/hacienda/index.php
require_once '../../system/session.php';
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    responseApi(405, 'Método no permitido');
}

$raw = file_get_contents('php://input');
$body = json_decode($raw, true) ?? [];

$accion       = $body['accion'] ?? '';
$provinciaCod = trim((string)($body['provinciaCod'] ?? ''));
$cantonCod    = trim((string)($body['cantonCod'] ?? ''));
$distritoCod  = trim((string)($body['distritoCod'] ?? ''));

switch ($accion) {
    case 'provincias': {
            $sql = "SELECT provincia_cod, provincia_nombre
                FROM hacienda_cod_ubicacion
                GROUP BY provincia_cod, provincia_nombre
                ORDER BY LPAD(provincia_cod, 4, '0')";
            $res = query($sql);
            $out = [];
            while ($r = mysqli_fetch_assoc($res)) {
                $out[] = [
                    'provinciaCod' => $r['provincia_cod'],
                    'provinciaNombre' => $r['provincia_nombre']
                ];
            }
            echo json_encode($out);
            exit;
        }
    case 'cantones': {
            if ($provinciaCod === '') responseApi(400, 'provinciaCod requerido');
            $sql = "SELECT canton_cod, canton_nombre
                FROM hacienda_cod_ubicacion
                WHERE provincia_cod = '" . mysqli_real_escape_string($conn, $provinciaCod) . "'
                GROUP BY canton_cod, canton_nombre
                ORDER BY LPAD(canton_cod, 4, '0')";
            $res = query($sql);
            $out = [];
            while ($r = mysqli_fetch_assoc($res)) {
                $out[] = [
                    'cantonCod' => $r['canton_cod'],
                    'cantonNombre' => $r['canton_nombre']
                ];
            }
            echo json_encode($out);
            exit;
        }
    case 'distritos': {
            if ($provinciaCod === '' || $cantonCod === '') responseApi(400, 'provinciaCod y cantonCod requeridos');
            $sql = "SELECT distrito_cod, distrito_nombre
                FROM hacienda_cod_ubicacion
                WHERE provincia_cod = '" . mysqli_real_escape_string($conn, $provinciaCod) . "'
                  AND canton_cod = '" . mysqli_real_escape_string($conn, $cantonCod) . "'
                GROUP BY distrito_cod, distrito_nombre
                ORDER BY LPAD(distrito_cod, 4, '0')";
            $res = query($sql);
            $out = [];
            while ($r = mysqli_fetch_assoc($res)) {
                $out[] = [
                    'distritoCod' => $r['distrito_cod'],
                    'distritoNombre' => $r['distrito_nombre']
                ];
            }
            echo json_encode($out);
            exit;
        }
    case 'barrios': {
            if ($provinciaCod === '' || $cantonCod === '' || $distritoCod === '') {
                responseApi(400, 'provinciaCod, cantonCod y distritoCod requeridos');
            }
            $sql = "SELECT barrio_cod, barrio_nombre
                FROM hacienda_cod_ubicacion
                WHERE provincia_cod = '" . mysqli_real_escape_string($conn, $provinciaCod) . "'
                  AND canton_cod = '" . mysqli_real_escape_string($conn, $cantonCod) . "'
                  AND distrito_cod = '" . mysqli_real_escape_string($conn, $distritoCod) . "'
                ORDER BY LPAD(barrio_cod, 4, '0')";
            $res = query($sql);
            $out = [];
            while ($r = mysqli_fetch_assoc($res)) {
                $out[] = [
                    'barrioCod' => $r['barrio_cod'],
                    'barrioNombre' => $r['barrio_nombre']
                ];
            }
            echo json_encode($out);
            exit;
        }
    case 'imp_general': {
            $sql = "SELECT id_imp_general, codigo, descripcion, porcentaje, activo
                FROM hacienda_imp_general
                ORDER BY descripcion";
            $res = query($sql);
            $out = [];
            while ($r = mysqli_fetch_assoc($res)) {
                $out[] = [
                    'idImpGeneral' => (int)$r['id_imp_general'],
                    'codigo'       => $r['codigo'],
                    'descripcion'  => $r['descripcion'],
                    'porcentaje'   => (float)$r['porcentaje'],
                    'activo'       => (int)$r['activo']
                ];
            }
            echo json_encode($out);
            exit;
        }
    case 'unidad_medida': {
            $sql = "SELECT id_unidad_medida, simbolo, descripcion
                FROM hacienda_unidad_medida
                ORDER BY descripcion";
            $res = query($sql);
            $out = [];
            while ($r = mysqli_fetch_assoc($res)) {
                $out[] = [
                    'idUnidadMedida' => (int)$r['id_unidad_medida'],
                    'simbolo'        => $r['simbolo'],
                    'descripcion'   => $r['descripcion']
                ];
            }
            echo json_encode($out);
            exit;
        }
    case 'tipo_medicamentos': {
            $sql = "SELECT id_tipo_medicamento, codigo, descripcion
                FROM hacienda_tipo_medicamento
                ORDER BY descripcion";
            $res = query($sql);
            $out = [];
            while ($r = mysqli_fetch_assoc($res)) {
                $out[] = [
                    'idTipoMedicamento' => (int)$r['id_tipo_medicamento'],
                    'codigo'           => $r['codigo'],
                    'descripcion'      => $r['descripcion']
                ];
            }
            echo json_encode($out);
            exit;
    }
    case 'tiposCedula': {
            // Opcional: soportar búsqueda por idTipoCedula para traer 1 registro
            $idTipoCedula = isset($body['idTipoCedula']) ? (int)$body['idTipoCedula'] : 0;

            if ($idTipoCedula > 0) {
                $sql = "SELECT id_tipo_cedula, codigo, descripcion
                FROM hacienda_tipo_cedula
                WHERE id_tipo_cedula = " . intval($idTipoCedula) . "
                LIMIT 1";
                $row = fetchAssoc($sql);
                if (!$row) {
                    echo json_encode(null);
                    exit;
                }
                echo json_encode([
                    'idTipoCedula' => (int)$row['id_tipo_cedula'],
                    'codigo'       => $row['codigo'],
                    'descripcion'  => $row['descripcion']
                ]);
                exit;
            }

            // Listado completo (ordena por código y luego descripción)
            $sql = "SELECT id_tipo_cedula, codigo, descripcion
            FROM hacienda_tipo_cedula
            ORDER BY LPAD(codigo, 4, '0'), descripcion";
            $res = query($sql);

            $out = [];
            while ($r = mysqli_fetch_assoc($res)) {
                $out[] = [
                    'idTipoCedula' => (int)$r['id_tipo_cedula'],
                    'codigo'       => $r['codigo'],
                    'descripcion'  => $r['descripcion']
                ];
            }
            echo json_encode($out);
            exit;
        }
    default:
        responseApi(400, 'Acción inválida');
}
