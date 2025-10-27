<?php
require '../../system/session.php';

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
    case 'listar_empresas':
        $empresas = query("SELECT u.id_usuario, u.id_empresa, e.nombre_legal, r.nombre FROM usuario u 
        LEFT JOIN empresa e ON u.id_empresa = e.id_empresa
        LEFT JOIN rol r ON u.id_rol = r.id_rol
        WHERE u.identificacion = '$usuario->identificacion' AND u.contrasena_hash = '$usuario->contrasena_hash' AND u.estado = 1 AND e.estado = 1");
        if (!$empresas) {
            responseApi(404, 'No se encontraron empresas asociadas.');
        }

        $empresasArray = [];
        foreach ($empresas as $fila) {
            $empresasArray[] = [
                'id_empresa'   => (int)$fila['id_empresa'],
                'nombre_legal' => $fila['nombre_legal'],
                'rol'          => $fila['nombre'],
            ];
        }
        responseApi(200, 'Empresas obtenidas correctamente.', $empresasArray);
        break;

    case 'cambiar_empresa':
        $nuevaEmpresaId = isset($body['nueva_empresa_id']) ? (int)$body['nueva_empresa_id'] : 0;

        if ($nuevaEmpresaId <= 0) {
            responseApi(400, 'ID de nueva empresa inválido.');
        }

        if( $nuevaEmpresaId === $empresaId ) {
            responseApi(400, 'Ya estás en la empresa seleccionada.');
        }

        $usuarioNuevaEmpresa = fetchAssoc("SELECT id_usuario, id_empresa, id_rol FROM usuario
        WHERE identificacion = '$usuario->identificacion' AND contrasena_hash = '$usuario->contrasena_hash' AND id_empresa = $nuevaEmpresaId LIMIT 1");
        if (!$usuarioNuevaEmpresa) {
            responseApi(404, 'Usuario no encontrado en la nueva empresa.');
        }

        $_SESSION['id_usuario'] = intval($usuarioNuevaEmpresa['id_usuario']);
        $_SESSION['id_empresa'] = intval($usuarioNuevaEmpresa['id_empresa']);
        $_SESSION['id_rol']     = intval($usuarioNuevaEmpresa['id_rol']);
        
        responseApi(200, 'Empresa cambiada correctamente.');
}


