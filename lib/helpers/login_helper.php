<?php
function getIntentosPorCedula($cedulaEsc) {
    $res = query("SELECT intento FROM usuario_intentos_fallidos WHERE cedula = '{$cedulaEsc}' LIMIT 1");
    if ($res && $res->num_rows > 0) {
        $r = mysqli_fetch_assoc($res);
        return intval($r['intento']);
    }
    return 0;
}

function setIntentosPorCedula($cedulaEsc, $nuevo) {
    $res = query("SELECT intento FROM usuario_intentos_fallidos WHERE cedula = '{$cedulaEsc}' LIMIT 1");
    if ($res && $res->num_rows > 0) {
        update("UPDATE usuario_intentos_fallidos SET intento = " . intval($nuevo) . " WHERE cedula = '{$cedulaEsc}'");
    } else {
        query("INSERT INTO usuario_intentos_fallidos (cedula, intento) VALUES ('{$cedulaEsc}', " . intval($nuevo) . ")");
    }
}

function resetIntentosPorCedula($cedulaEsc) {
    query("DELETE FROM usuario_intentos_fallidos WHERE cedula = '{$cedulaEsc}'");
}
