<?php
// Configuración en constantes
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'fe_sistec');

define('DOMAIN', 'http://localhost/FacturacionElectronicaSistec'); // Ejemplo: DOMAIN
define('BASE_PATH', dirname(__DIR__)); // Esto apunta a la carpeta principal /SistecFE
define('LIB_PATH', BASE_PATH . '/lib'); // Ejemplo: LIB_PATH

//KEYS DE RECAPTCHA V2
define('RECAPTCHA_ACTIVE', true);
define('RECAPTCHA_SITE_KEY', '6LdWD5wrAAAAAOHrJj030tsigV_gR8tBTC1gd4gL');
define('RECAPTCHA_SECRET_KEY', '6LdWD5wrAAAAAMjw5u4EUk-4H8DpfDejdHO27JsC');



$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}

//configuracion general en db
$config = new stdClass();
$result = mysqli_query($conn, "SELECT nombre, valor FROM config");
while ($row = mysqli_fetch_assoc($result)) {
    $config->{$row['nombre']} = $row['valor'];
}