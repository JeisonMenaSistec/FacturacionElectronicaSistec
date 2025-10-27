<?php
function obtenerPermitidos($idEmpresa, $idRol) {
    global $conn;
    $idEmpresa = intval($idEmpresa);
    $idRol = intval($idRol);
    $sql = "SELECT id_menu
            FROM rol_menu
            WHERE id_empresa = $idEmpresa AND id_rol = $idRol AND permiso_ver = 1";
    $res = mysqli_query($conn, $sql);
    $ids = [];
    if ($res) {
        while ($r = mysqli_fetch_assoc($res)) $ids[] = (int)$r['id_menu'];
    }
    return $ids;
}

function obtenerTodosMenus() {
    global $conn;
    $sql = "SELECT id_menu, nombre, ruta, icono, orden, id_menu_padre, es_visible
            FROM menu
            ORDER BY orden ASC, nombre ASC";
    $res = mysqli_query($conn, $sql);
    $lista = [];
    if ($res) {
        while ($r = mysqli_fetch_assoc($res)) {
            $r['id_menu'] = (int)$r['id_menu'];
            $r['orden'] = (int)$r['orden'];
            $r['id_menu_padre'] = $r['id_menu_padre'] !== null ? (int)$r['id_menu_padre'] : null;
            $r['es_visible'] = (int)$r['es_visible'];
            $lista[] = $r;
        }
    }
    return $lista;
}

function asegurarAncestros($todos, $permitidos) {
    $porId = [];
    foreach ($todos as $m) $porId[$m['id_menu']] = $m;
    $allowed = array_fill_keys($permitidos, true);

    foreach ($permitidos as $id) {
        $cur = $porId[$id] ?? null;
        while ($cur && $cur['id_menu_padre'] !== null) {
            $pid = $cur['id_menu_padre'];
            if (isset($allowed[$pid])) break;
            $allowed[$pid] = true;
            $cur = $porId[$pid] ?? null;
        }
    }
    return array_map('intval', array_keys($allowed));
}

function filtrarVisibles($todos, $allowed) {
    $allowedSet = array_fill_keys($allowed, true);
    $out = [];
    foreach ($todos as $m) {
        if ($m['es_visible'] == 1 && isset($allowedSet[$m['id_menu']])) $out[] = $m;
    }
    return $out;
}

function construirArbol($filtrados) {
    $hijos = [];
    foreach ($filtrados as $m) {
        $hijos[$m['id_menu_padre']][] = $m; // clave null para raíz también sirve
    }
    foreach ($hijos as &$lst) {
        usort($lst, function($a, $b) {
            if ($a['orden'] === $b['orden']) return strcmp($a['nombre'], $b['nombre']);
            return $a['orden'] <=> $b['orden'];
        });
    }
    unset($lst);

    $build = function($menu) use (&$build, &$hijos) {
        $n = [
            'id_menu' => $menu['id_menu'],
            'nombre'  => $menu['nombre'],
            'ruta'    => $menu['ruta'],
            'icono'   => $menu['icono'],
            'orden'   => $menu['orden'],
            'hijos'   => []
        ];
        $childs = $hijos[$menu['id_menu']] ?? [];
        foreach ($childs as $c) $n['hijos'][] = $build($c);
        return $n;
    };

    $arbol = [];
    $raiz = $hijos[null] ?? [];
    foreach ($raiz as $r) $arbol[] = $build($r);
    return $arbol;
}

function esExterna($ruta) {
    return is_string($ruta) && (stripos($ruta, 'http://') === 0 || stripos($ruta, 'https://') === 0);
}

function encodeRutaKeepSlashes(string $ruta): string {
    $ruta = ltrim($ruta, '/');
    if ($ruta === '') return '';
    $parts = preg_split('~/+~', $ruta, -1, PREG_SPLIT_NO_EMPTY);
    $parts = array_map('rawurlencode', $parts);
    return implode('/', $parts);
}

function normalizarRuta($ruta) {
    if ($ruta === null || $ruta === '') return 'javascript:void(0);';
    if (esExterna($ruta)) return $ruta;
    return '?pag=' . encodeRutaKeepSlashes($ruta);
}

function esActiva($ruta) {
    if (!$ruta || esExterna($ruta)) return false;
    $actualRaw = isset($_GET['pag']) ? (string)$_GET['pag'] : '';
    $actual = ltrim(rawurldecode($actualRaw), '/');
    $rutaNorm = ltrim((string)$ruta, '/');
    return $actual === $rutaNorm || strpos($actual, $rutaNorm . '/') === 0;
}

function construirHtmlMenu($arbol) {
    $renderNode = function($n, $nivel) use (&$renderNode) {
        $tieneHijos = !empty($n['hijos']);
        $icono = $n['icono'] ? htmlspecialchars($n['icono']) : '';
        $href = normalizarRuta($n['ruta']);
        $isExternal = esExterna($href);
        $active = esActiva($n['ruta']) ? ' active' : '';

        // Nodo con hijos
        if ($tieneHijos) {
            $html = '<li class="slide has-sub' . $active . '">';
            $html .= '<a href="javascript:void(0);" class="side-menu__item">';
            if ($icono) $html .= '<i class="' . $icono . ' side-menu__icon" aria-hidden="true"></i>';
            $html .= '<span class="side-menu__label">' . htmlspecialchars($n['nombre']) . '</span>';
            $html .= '<i class="ri-arrow-down-s-line side-menu__angle"></i>';
            $html .= '</a>';

            // Submenú
            $html .= '<ul class="slide-menu child' . ($nivel + 1) . '">';
            $html .= '<li class="slide side-menu__label1"><a href="javascript:void(0)">' . htmlspecialchars($n['nombre']) . '</a></li>';

            foreach ($n['hijos'] as $hijo) {
                $tieneNietos = !empty($hijo['hijos']);
                $hHref = normalizarRuta($hijo['ruta']);
                $hIcono = $hijo['icono'] ? htmlspecialchars($hijo['icono']) : '';
                $hActive = esActiva($hijo['ruta']) ? ' active' : '';
                if ($tieneNietos) {
                    // Subnivel anidado: vuelve a abrir li padre y su ul recursivo
                    $html .= $renderNode($hijo, $nivel + 1);
                } else {
                    $target = esExterna($hHref) ? ' target="_blank"' : '';
                    $html .= '<li class="slide' . $hActive . '">';
                    $html .= '<a href="' . htmlspecialchars($hHref) . '" class="side-menu__item"' . $target . '>';
                    if ($hIcono) $html .= '<i class="' . $hIcono . ' side-menu__icon" aria-hidden="true"></i>';
                    $html .= htmlspecialchars($hijo['nombre']);
                    $html .= '</a></li>';
                }
            }
            $html .= '</ul>';
            $html .= '</li>';
            return $html;
        }

        // Nodo hoja
        $target = $isExternal ? ' target="_blank"' : '';
        $html = '<li class="slide' . $active . '">';
        $html .= '<a href="' . htmlspecialchars($href) . '" class="side-menu__item"' . $target . '>';
        if ($icono) $html .= '<i class="' . $icono . ' side-menu__icon" aria-hidden="true"></i>';
        $html .= '<span class="side-menu__label">' . htmlspecialchars($n['nombre']) . '</span>';
        $html .= '</a></li>';
        return $html;
    };

    $html = '<ul class="main-menu">';
    foreach ($arbol as $n) {
        $html .= $renderNode($n, 0);
    }
    $html .= '</ul>';
    return $html;
}

function renderizarSidebar($idUsuario, $idEmpresa, $idRol) {
    $idUsuario = intval($idUsuario);
    $idEmpresa = intval($idEmpresa);

    $permitidos = obtenerPermitidos($idEmpresa, $idRol);
    if (empty($permitidos)) return '<ul class="main-menu"></ul>';

    $todos = obtenerTodosMenus();
    $allowedConPadres = asegurarAncestros($todos, $permitidos);
    $filtrados = filtrarVisibles($todos, $allowedConPadres);
    $arbol = construirArbol($filtrados);

    return construirHtmlMenu($arbol);
}
