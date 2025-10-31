<?php
session_start();
define('INDEX_PATH', true);

if (!isset($_SESSION['id_usuario'])) {
    require 'system/init.php';
    require 'pages/login.php';
    exit();
}

require 'system/session.php';

$pag = isset($_GET['pag']) ? sanitizeInput($_GET['pag']) : '';
if (empty($pag)) {
    redirect('?pag=' . $config->ruta_default);
    exit();
}

if (!file_exists("pages/{$pag}.php")) {
    require 'pages/404.php';
    exit();
}

$menu = fetchObject("SELECT * FROM menu WHERE ruta = '$pag'");
if (!$menu) {
    require 'pages/404.php';
    exit();
}

$menuPadre = '';
if ($menu->id_menu_padre !== null) {
    $menuPadreObj = fetchObject("SELECT nombre FROM menu WHERE id_menu = $menu->id_menu_padre");
    $menuPadre = $menuPadreObj ? $menuPadreObj->nombre : '';
}

$rol=fetchObject("SELECT * FROM rol WHERE id_rol = $usuario->id_rol LIMIT 1");
if (!$rol) {
    require 'pages/403.php';
    exit();
}

$rolMenu = fetchObject("SELECT * FROM rol_menu WHERE id_rol = $usuario->id_rol AND id_menu = $menu->id_menu AND id_empresa= $usuario->id_empresa LIMIT 1");

if (!$rolMenu || $rolMenu->permiso_ver == 0) {
    if($config->ruta_default!=$pag){    
        require 'pages/403.php';
        exit();
    }
}

$empresa = fetchObject("SELECT * FROM empresa WHERE id_empresa = $usuario->id_empresa LIMIT 1");
if (!$empresa) {
    require 'pages/403.php';
    logout();
    exit();
}

require 'pages/layout/header.php';
require "pages/{$pag}.php";
require 'pages/layout/footer.php';
