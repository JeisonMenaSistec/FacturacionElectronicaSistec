<?php
if(!defined('INDEX_PATH')){
    session_start();
}

if(!isset($_SESSION['id_usuario'])) {
    exit();
}
require 'config.php';
require LIB_PATH.'/functions.php';
$usuario=fetchObject("SELECT * FROM usuario WHERE id_usuario = {$_SESSION['id_usuario']} AND id_empresa = {$_SESSION['id_empresa']} AND estado=1 LIMIT 1");
if(!$usuario) {
    session_destroy();
    exit();
}