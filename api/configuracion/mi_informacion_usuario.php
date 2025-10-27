<?php
require_once '../../system/session.php';
require_once LIB_PATH . '/helpers/mi_informacion_usuario.php';

/* =========================================================
 * Seguridad básica de la solicitud
 * ========================================================= */

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    responseApi(405, 'Método no permitido');
}

$raw  = file_get_contents('php://input');
$body = json_decode($raw, true) ?? [];
$accion = $body['accion'] ?? '';

$usuarioId   = isset($usuario->id_usuario) ? (int)$usuario->id_usuario : 0;
$empresaId   = isset($usuario->id_empresa) ? (int)$usuario->id_empresa : 0;

if ($usuarioId <= 0 || $empresaId <= 0) {
    responseApi(401, 'Sesión inválida');
}

/* =========================================================
 * Acciones
 * ========================================================= */

switch ($accion) {

    /* --------------------------------------------
     * PERFIL: Obtiene mis datos
     * -------------------------------------------- */
    case 'perfil': {
            $r = fetchAssoc("
            SELECT u.id_usuario, u.usuario_guid, u.id_empresa, u.id_rol,
                    u.correo_electronico, u.nombre, u.apellido1, u.apellido2,
                   u.telefono_fijo, u.telefono_extension, u.celular, u.fax,
                   u.provincia, u.canton, u.distrito, u.direccion_exacta,
                   u.estado, u.foto_url, u.id_pregunta_seguridad
            FROM usuario u
            WHERE u.id_usuario = " . intval($usuarioId) . " AND u.id_empresa = " . intval($empresaId) . " LIMIT 1
        ");
            if (!$r) responseApi(404, 'No se encontró el usuario');

            $data = [
                'idUsuario'          => (int)$r['id_usuario'],
                'usuarioGuid'        => $r['usuario_guid'],
                'idEmpresa'          => (int)$r['id_empresa'],
                'idRol'              => (int)$r['id_rol'],
                'correoElectronico'  => $r['correo_electronico'],
                'nombre'             => $r['nombre'],
                'apellido1'          => $r['apellido1'],
                'apellido2'          => $r['apellido2'],
                'nombreCompleto'     => trim(($r['nombre'] ?? '') . ' ' . ($r['apellido1'] ?? '') . ' ' . ($r['apellido2'] ?? '')),
                'telefonoFijo'       => $r['telefono_fijo'],
                'telefonoExtension'  => $r['telefono_extension'],
                'celular'            => $r['celular'],
                'fax'                => $r['fax'],
                'provincia'          => $r['provincia'],
                'canton'             => $r['canton'],
                'distrito'           => $r['distrito'],
                'direccionExacta'    => $r['direccion_exacta'],
                'estado'             => (int)$r['estado'],
                'fotoUrl'            => $r['foto_url'] ?? '',
                'preguntaSeguridadId' => $r['id_pregunta_seguridad'] ? (int)$r['id_pregunta_seguridad'] : null
            ];
            responseApi(200, '', $data);
        }

        /* --------------------------------------------
     * PREGUNTAS: lista para combo
     * -------------------------------------------- */
    case 'preguntas': {
            $res = query("SELECT id_pregunta_seguridad, texto, activo FROM pregunta_seguridad ORDER BY texto ASC");
            if ($res === false) responseApi(500, 'Error consultando preguntas');
            $data = [];
            while ($r = mysqli_fetch_assoc($res)) {
                $data[] = [
                    'preguntaSeguridadId' => (int)$r['id_pregunta_seguridad'],
                    'texto'               => $r['texto'],
                    'activo'              => isset($r['activo']) ? (int)$r['activo'] : 1
                ];
            }
            responseApi(200, '', $data);
        }

        /* --------------------------------------------
     * GUARDAR PERFIL: datos personales y contacto
     * Campos esperados:
     * - identificacion (IGNORADO: no se cambia aquí)
     * - nombreCompleto (se divide en nombre, apellido1, apellido2)
     * - correoElectronico, direccionExacta, telefonoFijo, telefonoExtension, celular, fax
     * -------------------------------------------- */
    case 'guardarPerfil': {
            $correoElectronico = isset($body['correoElectronico']) ? sanitizeInput($body['correoElectronico']) : null;
            $nombreCompleto    = isset($body['nombreCompleto']) ? sanitizeInput($body['nombreCompleto']) : null;
            $direccionExacta   = isset($body['direccionExacta']) ? sanitizeInput($body['direccionExacta']) : null;
            $telefonoFijo      = isset($body['telefonoFijo']) ? sanitizeInput($body['telefonoFijo']) : null;
            $telefonoExtension = isset($body['telefonoExtension']) ? sanitizeInput($body['telefonoExtension']) : null;
            $celular           = isset($body['celular']) ? sanitizeInput($body['celular']) : null;
            $fax               = isset($body['fax']) ? sanitizeInput($body['fax']) : null;

            $campos = [];

            if ($correoElectronico !== null)  $campos[] = "correo_electronico = '" . $correoElectronico . "'";
            if ($nombreCompleto !== null) {
                $p = dividirNombreApellidos($nombreCompleto);
                if ($p['nombre'] !== '')   $campos[] = "nombre = '" . sanitizeInput($p['nombre']) . "'";
                if ($p['apellido1'] !== '') $campos[] = "apellido1 = '" . sanitizeInput($p['apellido1']) . "'";
                // apellido2 puede quedar NULL si viene vacío explícitamente
                $campos[] = ($p['apellido2'] !== '') ? "apellido2 = '" . sanitizeInput($p['apellido2']) . "'" : "apellido2 = NULL";
            }
            if ($direccionExacta !== null)    $campos[] = ($direccionExacta !== '' ? "direccion_exacta = '" . $direccionExacta . "'" : "direccion_exacta = NULL");
            if ($telefonoFijo !== null)       $campos[] = ($telefonoFijo !== '' ? "telefono_fijo = '" . $telefonoFijo . "'" : "telefono_fijo = NULL");
            if ($telefonoExtension !== null)  $campos[] = ($telefonoExtension !== '' ? "telefono_extension = '" . $telefonoExtension . "'" : "telefono_extension = NULL");
            if ($celular !== null)            $campos[] = ($celular !== '' ? "celular = '" . $celular . "'" : "celular = NULL");
            if ($fax !== null)                $campos[] = ($fax !== '' ? "fax = '" . $fax . "'" : "fax = NULL");

            // Importante: cédula/identificación NO se cambia desde perfil
            // si te interesa permitirlo, deberías validar unicidad y formato aquí.

            $campos[] = "fecha_modificacion = '" . fActual() . "'";
            $campos[] = "id_usuario_modificacion = " . intval($usuarioId);

            if (empty($campos)) responseApi(400, 'No hay cambios para actualizar.');

            $sql = "UPDATE usuario SET " . implode(', ', $campos) . " WHERE id_usuario = " . intval($usuarioId) . " AND id_empresa = " . intval($empresaId) . " LIMIT 1";
            $aff = update($sql);
            if ($aff < 0) responseApi(500, 'Error actualizando perfil');

            addBitacora($usuarioId, 'usuario', $usuarioId, 'update', 'Perfil actualizado');
            responseApi(200, 'Perfil actualizado');
        }

        /* --------------------------------------------
     * FOTO: guardar/reemplazar foto (base64)
     * Espera: contentBase64 (dataURL o base64 crudo)
     * -------------------------------------------- */
    case 'foto': {
            $contentBase64 = $body['contentBase64'] ?? '';
            if ($contentBase64 === '') responseApi(400, 'Contenido base64 requerido');

            $g = guardarImagenUsuarioBase64Simple($usuarioId, $contentBase64);
            if (!$g['ok']) {
                addBitacora($usuarioId, 'usuario', $usuarioId, 'otros', 'Foto error: ' . ($g['msg'] ?? ''));
                responseApi(400, $g['msg'] ?? 'Error guardando foto');
            }

            $fotoUrl = $g['ruta'];
            $sql = "UPDATE usuario SET foto_url = '" . sanitizeInput($fotoUrl) . "', fecha_modificacion = '" . fActual() . "', id_usuario_modificacion = " . intval($usuarioId) . " WHERE id_usuario = " . intval($usuarioId) . " AND id_empresa = " . intval($empresaId) . " LIMIT 1";
            $aff = update($sql);
            if ($aff < 0) responseApi(500, 'No se pudo actualizar la foto');

            addBitacora($usuarioId, 'usuario', $usuarioId, 'update', 'Foto actualizada');
            responseApi(200, 'Foto actualizada', ['fotoUrl' => $fotoUrl]);
        }

        /* --------------------------------------------
     * ELIMINAR FOTO: deja foto_url = NULL (no borra archivo físico)
     * -------------------------------------------- */
    case 'eliminarFoto': {
            $sql = "UPDATE usuario SET foto_url = NULL, fecha_modificacion = '" . fActual() . "', id_usuario_modificacion = " . intval($usuarioId) . " WHERE id_usuario = " . intval($usuarioId) . " AND id_empresa = " . intval($empresaId) . " LIMIT 1";
            $aff = update($sql);
            if ($aff < 0) responseApi(500, 'No se pudo eliminar la foto');

            addBitacora($usuarioId, 'usuario', $usuarioId, 'update', 'Foto eliminada');
            responseApi(200, 'Foto eliminada');
        }

        /* --------------------------------------------
     * GUARDAR PWD: cambia contraseña validando la actual
     * Espera: contrasenaActual, contrasenaNueva
     * -------------------------------------------- */
    case 'guardarPwd': {
            $contrasenaActual = (string)($body['contrasenaActual'] ?? '');
            $contrasenaNueva  = (string)($body['contrasenaNueva'] ?? '');

            if ($contrasenaActual === '' || $contrasenaNueva === '') {
                responseApi(400, 'Complete la contraseña actual y la nueva');
            }

            // obtener hash actual
            $r = fetchAssoc("SELECT contrasena_hash FROM usuario WHERE id_usuario = " . intval($usuarioId) . " AND id_empresa = " . intval($empresaId) . " LIMIT 1");
            if (!$r) responseApi(404, 'Usuario no encontrado');

            $hashActual = $r['contrasena_hash'] ?? null;
            if (!$hashActual || !comparePassword($contrasenaActual, $hashActual)) {
                responseApi(400, 'La contraseña actual no es correcta');
            }

            $nuevoHash = hashPassword($contrasenaNueva);
            $sql = "UPDATE usuario SET contrasena_hash = '" . $nuevoHash . "', fecha_modificacion = '" . fActual() . "', id_usuario_modificacion = " . intval($usuarioId) . " WHERE id_usuario = " . intval($usuarioId) . " AND id_empresa = " . intval($empresaId) . " LIMIT 1";
            $aff = update($sql);
            if ($aff < 0) responseApi(500, 'No se pudo cambiar la contraseña');

            addBitacora($usuarioId, 'usuario', $usuarioId, 'update', 'Contraseña actualizada');
            responseApi(200, 'Contraseña actualizada');
        }

        /* --------------------------------------------
     * GUARDAR PREGUNTA: actualiza pregunta y respuesta
     * Espera: preguntaSeguridadId, respuestaNueva (opcional), contrasenaActual
     * La respuesta se guarda SIEMPRE en minúscula y hasheada (sha256)
     * -------------------------------------------- */
    case 'guardarPregunta': {
            $preguntaSegId   = isset($body['preguntaSeguridadId']) ? (int)$body['preguntaSeguridadId'] : 0;
            $respuestaNueva  = (string)($body['respuestaNueva'] ?? '');
            $contrasenaActual = (string)($body['contrasenaActual'] ?? '');

            if ($preguntaSegId <= 0) responseApi(400, 'Seleccione una pregunta de seguridad');
            if ($contrasenaActual === '') responseApi(400, 'Ingrese su contraseña actual');

            // validar contraseña actual
            $r = fetchAssoc("SELECT contrasena_hash FROM usuario WHERE id_usuario = " . intval($usuarioId) . " AND id_empresa = " . intval($empresaId) . " LIMIT 1");
            if (!$r) responseApi(404, 'Usuario no encontrado');

            $hashActual = $r['contrasena_hash'] ?? null;
            if (!$hashActual || !comparePassword($contrasenaActual, $hashActual)) {
                responseApi(400, 'La contraseña actual no es correcta');
            }

            // hash de respuesta en minúscula; si respuesta vacía -> NULL
            $respHashSql = "NULL";
            if ($respuestaNueva !== '') {
                $respMin = mb_strtolower($respuestaNueva, 'UTF-8');
                $respHash = hash('sha256', $respMin);
                $respHashSql = "'" . $respHash . "'";
            }

            $sql = "
            UPDATE usuario
            SET id_pregunta_seguridad = " . intval($preguntaSegId) . ",
                respuesta_seguridad_hash = " . $respHashSql . ",
                fecha_modificacion = '" . fActual() . "',
                id_usuario_modificacion = " . intval($usuarioId) . "
            WHERE id_usuario = " . intval($usuarioId) . " AND id_empresa = " . intval($empresaId) . "
            LIMIT 1
        ";
            $aff = update($sql);
            if ($aff < 0) responseApi(500, 'No se pudo actualizar la pregunta de seguridad');

            addBitacora($usuarioId, 'usuario', $usuarioId, 'update', 'Pregunta de seguridad actualizada');
            responseApi(200, 'Pregunta/Respuesta actualizadas');
        }

    default:
        responseApi(400, 'Acción inválida');
}
