<?php
require_once '../../system/session.php';
require_once LIB_PATH . '/helpers/mantenimiento_usuario_helper.php';

// -------------------------------------------------------------
// Entrada y validaciones iniciales
// -------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    responseApi(405, 'Método no permitido');
}

$raw  = file_get_contents('php://input');
$body = json_decode($raw, true) ?? [];

$accion    = $body['accion'] ?? '';
$empresaId = isset($usuario->id_empresa) ? (int)$usuario->id_empresa : 0;
if ($empresaId <= 0) responseApi(400, 'Empresa no definida en la sesión.');

// -------------------------------------------------------------
// Acciones
// -------------------------------------------------------------
switch ($accion) {

    // ---------------------------------------------------------
    // Listar usuarios de mi empresa
    // ---------------------------------------------------------
    case 'listar': {
        $sql = "
            SELECT u.id_usuario, u.usuario_guid, u.id_empresa, u.id_rol,
                   u.identificacion, u.correo_electronico, u.nombre, u.apellido1, u.apellido2,
                   u.telefono_fijo, u.telefono_extension, u.celular, u.fax,
                   u.provincia, u.canton, u.distrito, u.direccion_exacta,
                   u.estado, u.foto_url,
                   u.id_pregunta_seguridad,
                   r.nombre AS rol_nombre
            FROM usuario u
            LEFT JOIN rol r ON r.id_rol = u.id_rol
            WHERE u.id_empresa = " . intval($empresaId) . "
            ORDER BY u.fecha_creacion DESC, u.id_usuario DESC
        ";
        $res = query($sql);
        if ($res === false) responseApi(500, 'Error consultando usuarios');

        $data = [];
        while ($r = mysqli_fetch_assoc($res)) {
            $idUsuario = (int)$r['id_usuario'];
            $data[] = [
                'idUsuario'          => $idUsuario,
                'usuarioGuid'        => $r['usuario_guid'],
                'idEmpresa'          => (int)$r['id_empresa'],
                'idRol'              => (int)$r['id_rol'],
                'rolNombre'          => $r['rol_nombre'] ?? '',
                'identificacion'     => $r['identificacion'],
                'correoElectronico'  => $r['correo_electronico'],
                'nombre'             => $r['nombre'],
                'apellido1'          => $r['apellido1'],
                'apellido2'          => $r['apellido2'],
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
                'idPreguntaSeguridad'=> $r['id_pregunta_seguridad'] ? (int)$r['id_pregunta_seguridad'] : null
            ];
        }
        responseApi(200, '', $data);
    }

    // ---------------------------------------------------------
    // OBTENER (detalle de un usuario) para edición
    // ---------------------------------------------------------
    case 'obtener': {
        $idUsuario = intval($body['idUsuario'] ?? 0);
        if ($idUsuario <= 0) responseApi(400, 'idUsuario requerido');

        $r = fetchAssoc("
            SELECT u.id_usuario, u.usuario_guid, u.id_empresa, u.id_rol,
                   u.identificacion, u.correo_electronico, u.nombre, u.apellido1, u.apellido2,
                   u.telefono_fijo, u.telefono_extension, u.celular, u.fax,
                   u.provincia, u.canton, u.distrito, u.direccion_exacta,
                   u.estado, u.foto_url,
                   u.id_pregunta_seguridad
            FROM usuario u
            WHERE u.id_usuario = " . intval($idUsuario) . " LIMIT 1
        ");
        if (!$r) responseApi(404, 'Usuario no encontrado');
        if ((int)$r['id_empresa'] !== $empresaId) responseApi(400, 'Usuario no pertenece a su empresa.');

        $data = [
            'idUsuario'          => (int)$r['id_usuario'],
            'usuarioGuid'        => $r['usuario_guid'],
            'idEmpresa'          => (int)$r['id_empresa'],
            'idRol'              => (int)$r['id_rol'],
            'identificacion'     => $r['identificacion'],
            'correoElectronico'  => $r['correo_electronico'],
            'nombre'             => $r['nombre'],
            'apellido1'          => $r['apellido1'],
            'apellido2'          => $r['apellido2'],
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
            'idPreguntaSeguridad'=> $r['id_pregunta_seguridad'] ? (int)$r['id_pregunta_seguridad'] : null
        ];
        responseApi(200, '', $data);
    }

    // ---------------------------------------------------------
    // Crear usuario
    // ---------------------------------------------------------
    case 'crear': {
        if (isset($body['recaptcha']) && !recaptchaVerify($body['recaptcha'])) {
            responseApi(400, 'Por favor, verifica el reCAPTCHA.');
        }

        $idRol              = intval($body['idRol'] ?? 0);
        $identificacion     = sanitizeInput($body['identificacion'] ?? '');
        $correoElectronico  = sanitizeInput($body['correoElectronico'] ?? '');
        $nombre             = sanitizeInput($body['nombre'] ?? '');
        $apellido1          = sanitizeInput($body['apellido1'] ?? '');
        $apellido2          = sanitizeInput($body['apellido2'] ?? '');
        $provincia          = sanitizeInput($body['provincia'] ?? '');
        $canton             = sanitizeInput($body['canton'] ?? '');
        $distrito           = sanitizeInput($body['distrito'] ?? '');
        $direccionExacta    = sanitizeInput($body['direccionExacta'] ?? '');
        $telefonoFijo       = sanitizeInput($body['telefonoFijo'] ?? '');
        $telefonoExtension  = sanitizeInput($body['telefonoExtension'] ?? '');
        $celular            = sanitizeInput($body['celular'] ?? '');
        $fax                = sanitizeInput($body['fax'] ?? '');
        $estado             = normalizarBoolLc($body['estado'] ?? 1);
        $contrasena         = (string)($body['contrasena'] ?? '');
        $preguntaSegId      = isset($body['preguntaSeguridadId']) ? intval($body['preguntaSeguridadId']) : null;
        $respuestaSeg       = (string)($body['respuestaSeguridad'] ?? '');
        $fotoBase64         = $body['fotoBase64'] ?? null;

        if ($idRol <= 0 || $identificacion === '' || $correoElectronico === '') {
            responseApi(400, 'Faltan campos obligatorios: idRol, identificación y correo.');
        }

        // Validar rol existe
        $rolExiste = fetchAssoc("SELECT id_rol FROM rol WHERE id_rol = " . $idRol . " LIMIT 1");
        if (!$rolExiste) responseApi(400, 'El rol indicado no existe.');

        // Si no mandan nombre/apellidos, intenta deducirlos
        if ($nombre === '' && $apellido1 === '' && $identificacion !== '') {
            $auto = @file_get_contents('https://api.hacienda.go.cr/fe/ae?identificacion=' . urlencode($identificacion));
            if ($auto !== false) {
                $j = json_decode($auto, true);
                if (!empty($j['nombre'])) {
                    $parts = dividirNombreApellidos($j['nombre']);
                    $nombre    = $nombre    ?: sanitizeInput($parts['nombre']);
                    $apellido1 = $apellido1 ?: sanitizeInput($parts['apellido1']);
                    $apellido2 = $apellido2 ?: sanitizeInput($parts['apellido2']);
                }
            }
        }

        if ($nombre === '' || $apellido1 === '') {
            responseApi(400, 'Nombre y primer apellido requeridos.');
        }

        $contrasenaHash = $contrasena !== '' ? hashPassword($contrasena) : null;
        $guid           = generarGuidV4();
        $ahora          = fActual();
        $ip             = ipUser();
        $idUsuarioCreador = isset($usuario->id_usuario) ? (int)$usuario->id_usuario : null;

        // Respuesta seguridad en minúscula antes de hashear
        $respSegHash = ($respuestaSeg !== '') ? hash('sha256', mb_strtolower($respuestaSeg, 'UTF-8')) : null;

        $sql = "
            INSERT INTO usuario
            (usuario_guid, id_empresa, id_rol, identificacion, correo_electronico, contrasena_hash,
             nombre, apellido1, apellido2, provincia, canton, distrito, direccion_exacta,
             ip_registro, fecha_creacion, telefono_fijo, telefono_extension, celular, fax,
             id_pregunta_seguridad, respuesta_seguridad_hash, id_usuario_creacion, estado)
            VALUES
            (
                '" . $guid . "',
                " . intval($empresaId) . ",
                " . intval($idRol) . ",
                '" . $identificacion . "',
                '" . $correoElectronico . "',
                " . ($contrasenaHash ? "'" . $contrasenaHash . "'" : "NULL") . ",
                '" . $nombre . "',
                '" . $apellido1 . "',
                " . ($apellido2 !== '' ? "'" . $apellido2 . "'" : "NULL") . ",
                " . ($provincia !== '' ? "'" . $provincia . "'" : "NULL") . ",
                " . ($canton !== '' ? "'" . $canton . "'" : "NULL") . ",
                " . ($distrito !== '' ? "'" . $distrito . "'" : "NULL") . ",
                " . ($direccionExacta !== '' ? "'" . $direccionExacta . "'" : "NULL") . ",
                '" . $ip . "',
                '" . $ahora . "',
                " . ($telefonoFijo !== '' ? "'" . $telefonoFijo . "'" : "NULL") . ",
                " . ($telefonoExtension !== '' ? "'" . $telefonoExtension . "'" : "NULL") . ",
                " . ($celular !== '' ? "'" . $celular . "'" : "NULL") . ",
                " . ($fax !== '' ? "'" . $fax . "'" : "NULL") . ",
                " . ($preguntaSegId ? intval($preguntaSegId) : "NULL") . ",
                " . ($respSegHash ? "'" . $respSegHash . "'" : "NULL") . ",
                " . ($idUsuarioCreador ? $idUsuarioCreador : "NULL") . ",
                " . intval($estado) . "
            )
        ";
        $aff = update($sql);
        if ($aff <= 0) responseApi(500, 'No se pudo crear el usuario');

        $nuevoId = lastInsertId();

        // Guardar imagen si viene
        $fotoUrl = null;
        if ($fotoBase64) {
            $g = guardarImagenUsuarioBase64Lc((int)$nuevoId, $fotoBase64);
            if (!$g['ok']) {
                addBitacora($idUsuarioCreador, 'usuario', $nuevoId, 'otros', 'Advertencia: ' . $g['msg']);
            } else {
                $fotoUrl = $g['ruta'];
                update("UPDATE usuario SET foto_url = '" . $fotoUrl . "' WHERE id_usuario = " . intval($nuevoId) . " AND id_empresa = " . intval($empresaId) . " LIMIT 1");
            }
        }

        addBitacora($idUsuarioCreador, 'usuario', $nuevoId, 'create', 'Usuario creado');
        responseApi(200, 'Usuario creado', ['idUsuario' => (int)$nuevoId, 'fotoUrl' => $fotoUrl]);
    }

    // ---------------------------------------------------------
    // Actualizar usuario (cédula NO se cambia)
    // ---------------------------------------------------------
    case 'actualizar': {
        $idUsuario = intval($body['idUsuario'] ?? 0);
        if ($idUsuario <= 0) responseApi(400, 'idUsuario requerido');

        $existe = fetchAssoc("SELECT id_usuario, id_empresa FROM usuario WHERE id_usuario = " . $idUsuario . " LIMIT 1");
        if (!$existe || intval($existe['id_empresa']) !== $empresaId) {
            responseApi(400, 'Usuario no pertenece a su empresa.');
        }

        $idRol              = isset($body['idRol']) ? intval($body['idRol']) : null;
        // $identificacion IGNORADA en actualización (cédula inmutable)
        $correoElectronico  = isset($body['correoElectronico']) ? sanitizeInput($body['correoElectronico']) : null;
        $nombre             = isset($body['nombre']) ? sanitizeInput($body['nombre']) : null;
        $apellido1          = isset($body['apellido1']) ? sanitizeInput($body['apellido1']) : null;
        $apellido2          = array_key_exists('apellido2', $body) ? sanitizeInput($body['apellido2']) : null;
        $provincia          = array_key_exists('provincia', $body) ? sanitizeInput($body['provincia']) : null;
        $canton             = array_key_exists('canton', $body) ? sanitizeInput($body['canton']) : null;
        $distrito           = array_key_exists('distrito', $body) ? sanitizeInput($body['distrito']) : null;
        $direccionExacta    = array_key_exists('direccionExacta', $body) ? sanitizeInput($body['direccionExacta']) : null;
        $telefonoFijo       = array_key_exists('telefonoFijo', $body) ? sanitizeInput($body['telefonoFijo']) : null;
        $telefonoExtension  = array_key_exists('telefonoExtension', $body) ? sanitizeInput($body['telefonoExtension']) : null;
        $celular            = array_key_exists('celular', $body) ? sanitizeInput($body['celular']) : null;
        $fax                = array_key_exists('fax', $body) ? sanitizeInput($body['fax']) : null;
        $estado             = array_key_exists('estado', $body) ? normalizarBoolLc($body['estado']) : null;
        $contrasena         = isset($body['contrasena']) ? (string)$body['contrasena'] : null;
        $preguntaSegId      = array_key_exists('preguntaSeguridadId', $body) ? intval($body['preguntaSeguridadId']) : null;
        $respuestaSeg       = array_key_exists('respuestaSeguridad', $body) ? (string)$body['respuestaSeguridad'] : null;
        $fotoBase64         = $body['fotoBase64'] ?? null;

        $campos = [];
        if ($idRol !== null)               $campos[] = "id_rol = " . intval($idRol);
        // identificacion (cédula) NO se actualiza
        if ($correoElectronico !== null)   $campos[] = "correo_electronico = '" . $correoElectronico . "'";
        if ($nombre !== null)              $campos[] = "nombre = '" . $nombre . "'";
        if ($apellido1 !== null)           $campos[] = "apellido1 = '" . $apellido1 . "'";
        if ($apellido2 !== null)           $campos[] = ($apellido2 !== '' ? "apellido2 = '" . $apellido2 . "'" : "apellido2 = NULL");
        if ($provincia !== null)           $campos[] = ($provincia !== '' ? "provincia = '" . $provincia . "'" : "provincia = NULL");
        if ($canton !== null)              $campos[] = ($canton !== '' ? "canton = '" . $canton . "'" : "canton = NULL");
        if ($distrito !== null)            $campos[] = ($distrito !== '' ? "distrito = '" . $distrito . "'" : "distrito = NULL");
        if ($direccionExacta !== null)     $campos[] = ($direccionExacta !== '' ? "direccion_exacta = '" . $direccionExacta . "'" : "direccion_exacta = NULL");
        if ($telefonoFijo !== null)        $campos[] = ($telefonoFijo !== '' ? "telefono_fijo = '" . $telefonoFijo . "'" : "telefono_fijo = NULL");
        if ($telefonoExtension !== null)   $campos[] = ($telefonoExtension !== '' ? "telefono_extension = '" . $telefonoExtension . "'" : "telefono_extension = NULL");
        if ($celular !== null)             $campos[] = ($celular !== '' ? "celular = '" . $celular . "'" : "celular = NULL");
        if ($fax !== null)                 $campos[] = ($fax !== '' ? "fax = '" . $fax . "'" : "fax = NULL");
        if ($estado !== null)              $campos[] = "estado = " . intval($estado);

        // Cambio de contraseña opcional
        if ($contrasena !== null) {
            $campos[] = $contrasena !== '' ? "contrasena_hash = '" . hashPassword($contrasena) . "'" : "contrasena_hash = NULL";
        }

        // Pregunta de seguridad (mantener seleccionada) y respuesta SIEMPRE en minúscula
        if ($preguntaSegId !== null)       $campos[] = $preguntaSegId > 0 ? "id_pregunta_seguridad = " . intval($preguntaSegId) : "id_pregunta_seguridad = NULL";
        if ($respuestaSeg !== null) {
            $respSegHash = ($respuestaSeg !== '') ? hash('sha256', mb_strtolower($respuestaSeg, 'UTF-8')) : null;
            $campos[] = $respSegHash ? "respuesta_seguridad_hash = '" . $respSegHash . "'" : "respuesta_seguridad_hash = NULL";
        }

        $campos[] = "fecha_modificacion = '" . fActual() . "'";
        $campos[] = "id_usuario_modificacion = " . (isset($usuario->id_usuario) && $usuario->id_usuario > 0 ? (int)$usuario->id_usuario : "NULL");

        if (empty($campos)) responseApi(400, 'No hay cambios para actualizar.');

        $sql = "UPDATE usuario SET " . implode(", ", $campos) . " WHERE id_usuario = " . intval($idUsuario) . " AND id_empresa = " . intval($empresaId) . " LIMIT 1";
        $aff = update($sql);
        if ($aff < 0) responseApi(500, 'Error actualizando usuario');

        // Foto (si llega, la guarda y actualiza foto_url)
        $fotoUrl = null;
        if ($fotoBase64) {
            $g = guardarImagenUsuarioBase64Lc((int)$idUsuario, $fotoBase64);
            if (!$g['ok']) {
                addBitacora($usuario->id_usuario ?? null, 'usuario', $idUsuario, 'otros', 'Advertencia: ' . $g['msg']);
            } else {
                $fotoUrl = $g['ruta'];
                update("UPDATE usuario SET foto_url = '" . $fotoUrl . "' WHERE id_usuario = " . intval($idUsuario) . " AND id_empresa = " . intval($empresaId) . " LIMIT 1");
            }
        }

        addBitacora($usuario->id_usuario ?? null, 'usuario', $idUsuario, 'update', 'Usuario actualizado');
        responseApi(200, 'Usuario actualizado', ['idUsuario' => (int)$idUsuario, 'fotoUrl' => $fotoUrl]);
    }

    // ---------------------------------------------------------
    // Eliminar (desactivar estado=2)
    // ---------------------------------------------------------
    case 'eliminar': {
        $idUsuario = intval($body['idUsuario'] ?? 0);
        if ($idUsuario <= 0) responseApi(400, 'idUsuario requerido');

        $existe = fetchAssoc("SELECT id_usuario, id_empresa FROM usuario WHERE id_usuario = " . $idUsuario . " LIMIT 1");
        if (!$existe || intval($existe['id_empresa']) !== $empresaId) {
            responseApi(400, 'Usuario no pertenece a su empresa.');
        }

        $sql = "
            UPDATE usuario
            SET estado = 0,
                fecha_modificacion = '" . fActual() . "',
                id_usuario_modificacion = " . (isset($usuario->id_usuario) ? (int)$usuario->id_usuario : "NULL") . "
            WHERE id_usuario = " . intval($idUsuario) . " AND id_empresa = " . intval($empresaId) . " LIMIT 1
        ";
        $aff = update($sql);
        if ($aff < 0) responseApi(500, 'Error desactivando usuario');

        // No se eliminan imágenes en desactivación lógica
        addBitacora($usuario->id_usuario ?? null, 'usuario', $idUsuario, 'delete', 'Usuario desactivado (estado=2)');
        responseApi(200, 'Usuario desactivado');
    }

    // ---------------------------------------------------------
    // Roles (para combos)
    // ---------------------------------------------------------
    case 'roles': {
        $res = query("SELECT id_rol, nombre FROM rol ORDER BY nombre ASC");
        if ($res === false) responseApi(500, 'Error consultando roles');
        $roles = [];
        while ($r = mysqli_fetch_assoc($res)) {
            $roles[] = ['idRol' => (int)$r['id_rol'], 'nombre' => $r['nombre']];
        }
        responseApi(200, '', $roles);
    }

    // ---------------------------------------------------------
    // Preguntas de seguridad (para combos)
    // ---------------------------------------------------------
    case 'preguntas': {
        $res = query("SELECT id_pregunta_seguridad, texto, activo, fecha_creacion FROM pregunta_seguridad ORDER BY texto ASC");
        if ($res === false) responseApi(500, 'Error consultando preguntas de seguridad');
        $pregs = [];
        while ($r = mysqli_fetch_assoc($res)) {
            $pregs[] = [
                'idPreguntaSeguridad' => (int)$r['id_pregunta_seguridad'],
                'texto'               => $r['texto'],
                'activo'              => isset($r['activo']) ? (int)$r['activo'] : 1
            ];
        }
        responseApi(200, '', $pregs);
    }
    case 'eliminar_foto': {
        $idUsuario = intval($body['idUsuario'] ?? 0);
        if ($idUsuario <= 0) responseApi(400, 'idUsuario requerido');

        $existe = fetchAssoc("SELECT id_usuario, id_empresa FROM usuario WHERE id_usuario = " . $idUsuario . " LIMIT 1");
        if (!$existe || intval($existe['id_empresa']) !== $empresaId) {
            responseApi(400, 'Usuario no pertenece a su empresa.');
        }

        $sql = "UPDATE usuario SET foto_url = NULL WHERE id_usuario = " . intval($idUsuario) . " AND id_empresa = " . intval($empresaId) . " LIMIT 1";
        $aff = update($sql);
        if ($aff < 0) responseApi(500, 'Error eliminando foto de usuario');
        eliminarFotosUsuarioLc($idUsuario);
        addBitacora($usuario->id_usuario ?? null, 'usuario', $idUsuario, 'update', 'Foto de usuario eliminada');
        responseApi(200, 'Foto de usuario eliminada');

    }


    default:
        responseApi(400, 'Acción inválida');
}
