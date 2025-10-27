<?php
require_once '../../system/session.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    responseApi(405, 'Método no permitido');
}

$raw = file_get_contents('php://input');
$body = json_decode($raw, true) ?? [];

$accion      = $body['accion']      ?? '';
$rolId       = isset($body['rolId']) ? intval($body['rolId']) : 0;
$empresaId   = isset($usuario->id_empresa) ? intval($usuario->id_empresa) : 0;


/* Seguridad mínima: empresa obligatoria para operar permisos */
if (in_array($accion, ['permisos', 'guardar'], true) && $empresaId <= 0) {
    responseApi(400, 'Empresa no definida en sesión ni en el payload.');
}

switch ($accion) {

    /* =========================================================
     * 1) Listado de roles
     * ========================================================= */
    case 'roles': {
            $sql   = "SELECT id_rol, nombre FROM rol ORDER BY nombre ASC";
            $res   = query($sql);
            $roles = [];
            while ($r = mysqli_fetch_assoc($res)) {
                $roles[] = [
                    'rolId'  => (int)$r['id_rol'],
                    'nombre' => $r['nombre'],
                ];
            }
            echo json_encode($roles);
            exit;
        }

        /* =========================================================
     * 2) Permisos por rol (solo hojas: ruta no nula/ni vacía y es_visible=1)
     *    Si no hay rol_menu => todo en 0; flags can* según p_* del menú
     * ========================================================= */
    case 'permisos': {
            if ($rolId <= 0) responseApi(400, 'rolId requerido.');

            //verificar que exista el id del rol
            $rolExist = fetchAssoc("SELECT id_rol FROM rol WHERE id_rol = " . intval($rolId) . " LIMIT 1");
            if (!$rolExist) {
                responseApi(400, 'El rolId no existe.');
            }

            $sql = "
            SELECT
                m.id_menu,
                m.nombre,
                m.ruta,
                m.es_visible,
                m.p_crear, m.p_editar, m.p_eliminar,
                COALESCE(rm.permiso_ver, 0)      AS ver,
                COALESCE(rm.permiso_crear, 0)    AS crear,
                COALESCE(rm.permiso_editar, 0)   AS editar,
                COALESCE(rm.permiso_eliminar, 0) AS eliminar
            FROM menu m
            LEFT JOIN rol_menu rm
                ON rm.id_menu = m.id_menu
            AND rm.id_rol = " . intval($rolId) . "
            AND rm.id_empresa = " . intval($empresaId) . "
            WHERE m.es_visible = 1
            AND m.ruta IS NOT NULL AND m.ruta <> ''
            ORDER BY m.orden ASC, m.nombre ASC";


            $res = query($sql);
            $data = [];
            while ($r = mysqli_fetch_assoc($res)) {
                // Habilitadores por definición del menú
                $canAgregar = (int)$r['p_crear']  === 1 ? 1 : 0;
                $canEditar  = (int)$r['p_editar'] === 1 ? 1 : 0;
                $canEliminar = (int)$r['p_eliminar'] === 1 ? 1 : 0;

                // Si el menú no soporta la acción, forzar valor a 0
                $agregar = $canAgregar ? (int)$r['crear']   : 0;
                $editar  = $canEditar  ? (int)$r['editar']  : 0;
                $eliminar = $canEliminar ? (int)$r['eliminar'] : 0;

                $data[] = [
                    'menuId'      => (int)$r['id_menu'],
                    'seccion'     => $r['ruta'],
                    'ver'         => (int)$r['ver'],
                    'agregar'     => $agregar,
                    'editar'      => $editar,
                    'eliminar'    => $eliminar,
                    'canAgregar'  => $canAgregar,
                    'canEditar'   => $canEditar,
                    'canEliminar' => $canEliminar,
                    'esContenedor' => false // Para que tu frontend filtre correcto
                ];
            }

            echo json_encode($data);
            exit;
        }

        /* =========================================================
     * 3) Guardar permisos (UPSERT por cada fila enviada)
     *    - Respeta capacidades del menú (p_*). Si el menú no lo soporta, guarda 0.
     *    - Solo afecta los menús incluidos en el payload (filtrados en UI).
     * ========================================================= */
    case 'guardar': {
            if ($rolId <= 0) responseApi(400, 'rolId requerido.');
            $permisos = $body['permisos'] ?? [];
            if (!is_array($permisos) || count($permisos) === 0) {
                responseApi(400, 'No se enviaron filas de permisos.');
            }

            // Traer capacidades del menú para validar server-side
            $capSql = "SELECT id_menu, p_crear, p_editar, p_eliminar
                   FROM menu
                   WHERE es_visible = 1 AND ruta IS NOT NULL AND ruta <> ''";
            $capRes = query($capSql);
            $caps = [];
            while ($m = mysqli_fetch_assoc($capRes)) {
                $caps[(int)$m['id_menu']] = [
                    'p_crear'   => (int)$m['p_crear']   === 1,
                    'p_editar'  => (int)$m['p_editar']  === 1,
                    'p_eliminar' => (int)$m['p_eliminar'] === 1,
                ];
            }

            foreach ($permisos as $p) {
                $menuId   = intval($p['menuId'] ?? 0);
                $ver      = intval($p['ver']      ?? 0);
                $agregar  = intval($p['agregar']  ?? 0);
                $editar   = intval($p['editar']   ?? 0);
                $eliminar = intval($p['eliminar'] ?? 0);

                if ($menuId <= 0) {
                    continue;
                }

                // Aplicar límites por capacidades del menú
                $cap = $caps[$menuId] ?? ['p_crear' => false, 'p_editar' => false, 'p_eliminar' => false];
                if (!$cap['p_crear'])   $agregar  = 0;
                if (!$cap['p_editar'])  $editar   = 0;
                if (!$cap['p_eliminar']) $eliminar = 0;

                // ¿Existe rol_menu?
                $exSql = "SELECT id_rol_menu
                      FROM rol_menu
                      WHERE id_rol = " . intval($rolId) . "
                        AND id_menu = " . intval($menuId) . "
                        AND id_empresa = " . intval($empresaId) . "
                      LIMIT 1";
                $exist = fetchAssoc($exSql);

                if ($exist) {
                    $upd = "
                    UPDATE rol_menu SET
                        permiso_ver = " . ($ver ? 1 : 0) . ",
                        permiso_crear = " . ($agregar ? 1 : 0) . ",
                        permiso_editar = " . ($editar ? 1 : 0) . ",
                        permiso_eliminar = " . ($eliminar ? 1 : 0) . "
                    WHERE id_rol_menu = " . intval($exist['id_rol_menu']) . "
                    ";
                    update($upd);
                } else {
                    $ins = "
                    INSERT INTO rol_menu
                        (id_rol, id_menu, id_empresa, permiso_ver, permiso_crear, permiso_editar, permiso_eliminar, fecha_creacion)
                    VALUES
                        (" . intval($rolId) . ", " . intval($menuId) . ", " . intval($empresaId) . ",
                         " . ($ver ? 1 : 0) . ", " . ($agregar ? 1 : 0) . ", " . ($editar ? 1 : 0) . ", " . ($eliminar ? 1 : 0) . ",
                         '" . fActual() . "')
                    ";
                    update($ins);
                }
            }

            responseApi(200, 'Permisos guardados');
        }

    default:
        responseApi(400, 'Acción inválida');
}
