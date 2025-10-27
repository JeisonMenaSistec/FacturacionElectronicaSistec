<?php

require LIB_PATH.'/menu.php';
echo renderizarSidebar($_SESSION['id_usuario'], $_SESSION['id_empresa'], $_SESSION['id_rol']);