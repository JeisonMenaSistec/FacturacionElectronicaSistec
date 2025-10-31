<?php
require_once '../../system/session.php';

// ---------------------------------------------------------
// Configuración
// ---------------------------------------------------------
$expectedHeaders = [
    "Codigo",
    "CodigoCabys",
    "Detalle",
    "Unidad",
    "Cantidad",
    "Precio",
    "TarifaIVA",
    "Categoria",
    "RegistroMedicamento",
    "FormaFarmaceutica",
    "PartidaArancelaria"
];
$maxFilas = 10000;
$insertBatchSize = 400; // lote eficiente y seguro para grandes volúmenes

// ---------------------------------------------------------
// Helpers locales (lowerCamelCase)
// ---------------------------------------------------------
function isIntSafe($v): bool
{
    return preg_match('/^\d+$/', trim((string)$v)) === 1;
}
function isDecimalDotSafe($v): bool
{
    return preg_match('/^\d+(\.\d+)?$/', trim((string)$v)) === 1;
}

/** Normaliza códigos para comparar "03" == "3" y quitar espacios. */
function normalizarCodigo($v): string
{
    $s = trim((string)$v);
    if ($s === '') return '';
    if (preg_match('/^\d+$/', $s)) {
        $s = ltrim($s, '0');
        return $s === '' ? '0' : $s;
    }
    return $s;
}

/** Verifica que TODAS las filas tengan exactamente las mismas llaves que $expectedHeaders. */
function llavesCoinciden(array $fila, array $expectedHeaders): bool
{
    $a = array_keys($fila);
    sort($a);
    $b = $expectedHeaders;
    sort($b);
    return $a === $b;
}

/** Obtiene la semilla de id_producto: MAX(id_producto) + 1 para la empresa dada. */
function siguienteIdProductoPorEmpresa(int $idEmpresa): int
{
    $row = fetchAssoc("SELECT MAX(id_producto) AS max_id FROM producto WHERE id_empresa = " . intval($idEmpresa));
    $max = $row && isset($row['max_id']) ? (int)$row['max_id'] : 0;
    return $max > 0 ? $max + 1 : 1;
}

/** Carga catálogo simple a mapa: norm(codigo) => id */
function cargarMapaCodigoId(string $sqlCodigoId, string $campoCodigo, string $campoId): array
{
    $map = [];
    $res = query($sqlCodigoId);
    if ($res === false) return $map;
    while ($r = mysqli_fetch_assoc($res)) {
        $map[normalizarCodigo($r[$campoCodigo])] = (int)$r[$campoId];
    }
    return $map;
}

// ---------------------------------------------------------
// Verificaciones base
// ---------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    responseApi(405, 'Método no permitido');
}

$raw  = file_get_contents('php://input');
$body = json_decode($raw, true) ?? [];

$usuarioId = isset($usuario->id_usuario) ? (int)$usuario->id_usuario : 0;
$empresaId = isset($usuario->id_empresa) ? (int)$usuario->id_empresa : 0;

if ($usuarioId <= 0 || $empresaId <= 0) {
    responseApi(401, 'Sesión inválida');
}

if (!isset($body['rows']) || !is_array($body['rows'])) {
    responseApi(400, 'Faltan "rows" en el payload');
}

$rows = $body['rows'];
$totalFilas = count($rows);
if ($totalFilas === 0)         responseApi(400, 'No hay filas para procesar');
if ($totalFilas > $maxFilas)   responseApi(400, "Se permiten máximo {$maxFilas} filas por carga");

// Validación de llaves exactas
foreach ($rows as $idx => $r) {
    if (!is_array($r) || !llavesCoinciden($r, $expectedHeaders)) {
        responseApi(
            400,
            'Estructura inválida de filas: las llaves deben coincidir exactamente con los encabezados.',
            ['fila' => $idx + 2, 'esperado' => $expectedHeaders]
        );
    }
}

// ---------------------------------------------------------
// Precarga de catálogos
// ---------------------------------------------------------
$unidadMap = cargarMapaCodigoId(
    "SELECT id_unidad_medida, codigo FROM hacienda_unidad_medida",
    'codigo',
    'id_unidad_medida'
);

$impMap = cargarMapaCodigoId(
    "SELECT id_imp_general, codigo FROM hacienda_imp_general",
    'codigo',
    'id_imp_general'
);

// Categorías por empresa: id_producto_categoria (código) -> id (PK)
$categoriaMap = [];
$resCat = query("SELECT id, id_producto_categoria FROM producto_categoria WHERE id_empresa = " . intval($empresaId));
if ($resCat === false) responseApi(500, 'Error consultando categorías de la empresa');
while ($r = mysqli_fetch_assoc($resCat)) {
    $categoriaMap[normalizarCodigo($r['id_producto_categoria'])] = (int)$r['id'];
}

$formaMap = cargarMapaCodigoId(
    "SELECT id_forma_farmaceutica, codigo FROM hacienda_forma_farmaceutica",
    'codigo',
    'id_forma_farmaceutica'
);

// ---------------------------------------------------------
// Validación + Normalización + Mapeo (igual que frontend)
// ---------------------------------------------------------
$rowErrors      = [];   // Para devolver en 400 si aplica
$cleanRows      = [];   // Filas listas para insertar
$codigosPayload = [];   // Para detectar duplicados en el mismo archivo por "Codigo"

foreach ($rows as $i => $r) {
    $fila = $i + 2;

    $codigo              = trim((string)$r['Codigo']);            // cod_producto
    $codigoCabys         = trim((string)$r['CodigoCabys']);       // obligatorio
    $detalle             = trim((string)$r['Detalle']);           // obligatorio -> nombre
    $unidadCode          = trim((string)$r['Unidad']);            // catálogo -> id_unidad_medida
    $cantidad            = trim((string)$r['Cantidad']);          // entero opcional
    $precio              = trim((string)$r['Precio']);            // decimal opcional
    $tarifaIvaCode       = trim((string)$r['TarifaIVA']);         // catálogo -> id_imp_general
    $categoriaCode       = trim((string)$r['Categoria']);         // id_producto_categoria (código) -> id (PK)
    $registroMed         = trim((string)$r['RegistroMedicamento']);
    $formaCode           = trim((string)$r['FormaFarmaceutica']); // catálogo -> id_forma_farmaceutica
    $partidaArancelaria  = trim((string)$r['PartidaArancelaria']);

    $err = [];
    if ($codigoCabys === '') $err[] = 'CodigoCabys vacío';
    if ($detalle === '')     $err[] = 'Detalle vacío';

    if ($unidadCode !== ''    && !isIntSafe($unidadCode))    $err[] = 'Unidad no entera';
    if ($cantidad !== ''      && !isIntSafe($cantidad))      $err[] = 'Cantidad no entera';
    if ($precio !== ''        && !isDecimalDotSafe($precio)) $err[] = 'Precio inválido (use punto decimal)';
    if ($tarifaIvaCode !== '' && !isIntSafe($tarifaIvaCode)) $err[] = 'TarifaIVA no entera';
    if ($formaCode !== ''     && !isIntSafe($formaCode))     $err[] = 'FormaFarmaceutica no entera';
    if ($categoriaCode !== '' && !isIntSafe($categoriaCode)) $err[] = 'Categoria no numérica';

    // Duplicado en el mismo payload por "Codigo"
    $dupKey = ($codigo !== '') ? $codigo : "__VACIO__{$fila}";
    if (isset($codigosPayload[$dupKey])) {
        $err[] = 'Código duplicado en el archivo';
    } else {
        $codigosPayload[$dupKey] = true;
    }

    // Mapeo códigos -> IDs (normalizados)
    $idUnidadMedida = null;
    if ($unidadCode !== '') {
        $norm = normalizarCodigo($unidadCode);
        $idUnidadMedida = array_key_exists($norm, $unidadMap) ? $unidadMap[$norm] : null;
    }

    $idImpGeneral = null;
    if ($tarifaIvaCode !== '') {
        $norm = normalizarCodigo($tarifaIvaCode);
        $idImpGeneral = array_key_exists($norm, $impMap) ? $impMap[$norm] : null;
    }

    $idCategoria = 0; // si no mapea, 0 (consistente con mantenimientos)
    if ($categoriaCode !== '') {
        $norm = normalizarCodigo($categoriaCode);
        $idCategoria = array_key_exists($norm, $categoriaMap) ? $categoriaMap[$norm] : 0;
    }

    $idFormaFarmaceutica = null;
    if ($formaCode !== '') {
        $norm = normalizarCodigo($formaCode);
        $idFormaFarmaceutica = array_key_exists($norm, $formaMap) ? $formaMap[$norm] : null;
    }

    // es_medicamento si llega cualquiera de los dos campos con dato
    $esMedicamento = ($registroMed !== '' || $idFormaFarmaceutica !== null) ? 1 : 0;

    if (!empty($err)) {
        $rowErrors[] = ['fila' => $fila, 'codigo' => $codigo, 'errores' => $err];
    }

    $cleanRows[] = [
        'codigo'               => $codigo,
        'codigoCabys'          => $codigoCabys,
        'nombre'               => $detalle,
        'idUnidadMedida'       => $idUnidadMedida,                         // NULL|int
        'cantidad'             => ($cantidad === '' ? null : (float)$cantidad),
        'precioUnitario'       => ($precio === '' ? null : (float)$precio),
        'idImpGeneral'         => $idImpGeneral,                           // NULL|int
        'idCategoria'          => (int)$idCategoria,                       // 0|int (PK real)
        'registroMed'          => ($registroMed === '' ? null : $registroMed),
        'idFormaFarmaceutica'  => $idFormaFarmaceutica,                    // NULL|int
        'partidaArancelaria'   => ($partidaArancelaria === '' ? null : $partidaArancelaria),
        'esMedicamento'        => $esMedicamento
    ];
}

// Si ya hay errores de validación, devolvemos 400 (el frontend mostrará el detalle)
if (!empty($rowErrors)) {
    responseApi(400, 'Validación de filas fallida. Corrige y vuelve a intentar.', ['errores' => $rowErrors]);
}

// ---------------------------------------------------------
// Verificar cod_producto repetidos en BD (rechazar existentes)
// ---------------------------------------------------------
$codigosParaBuscar = array_values(array_unique(array_filter(array_map(fn($r) => $r['codigo'], $cleanRows))));
$existentes = []; // cod_producto => id (PK producto)
if (!empty($codigosParaBuscar)) {
    $in = implode(',', array_map(fn($c) => "'" . sanitizeInput($c) . "'", $codigosParaBuscar));
    $sqlExiste = "
        SELECT id, cod_producto
        FROM producto
        WHERE id_empresa = " . intval($empresaId) . "
          AND cod_producto IN ($in)
    ";
    $resExiste = query($sqlExiste);
    if ($resExiste === false) responseApi(500, 'Error verificando productos existentes');
    while ($e = mysqli_fetch_assoc($resExiste)) {
        $existentes[$e['cod_producto']] = (int)$e['id'];
    }
}

// Si algún "Codigo" ya existe en la empresa, marcamos error (NO se permite actualizar)
if (!empty($existentes)) {
    foreach ($cleanRows as $i => $r) {
        if ($r['codigo'] !== '' && isset($existentes[$r['codigo']])) {
            $rowErrors[] = [
                'fila'    => $i + 2,
                'codigo'  => $r['codigo'],
                'errores' => ['El código ya existe en la empresa']
            ];
        }
    }
    responseApi(400, 'Existen códigos repetidos en la empresa. La carga no fue procesada.', ['errores' => $rowErrors]);
}

// ---------------------------------------------------------
// Semilla de id_producto para nuevos inserts (por empresa)
// ---------------------------------------------------------
$idProductoSeed = siguienteIdProductoPorEmpresa($empresaId);

// ---------------------------------------------------------
// Construcción de INSERTs por lotes
// ---------------------------------------------------------
$ahora = fActual();

$insertValues   = [];  // cada item: ['values' => '(...)', 'fila'=>X, 'codigo'=>Y]
$insertCount    = 0;
$insertErrores  = [];
$resultados     = [];

foreach ($cleanRows as $i => $r) {
    $fila = $i + 2;

    $codigo               = $r['codigo'];
    $codigoCabys          = $r['codigoCabys'];
    $nombre               = $r['nombre'];
    $idCategoria          = (int)$r['idCategoria'];
    $idUnidadMedida       = $r['idUnidadMedida'];         // NULL|int
    $cantidad             = $r['cantidad'];               // NULL|float
    $precioUnitario       = $r['precioUnitario'];         // NULL|float
    $idImpGeneral         = $r['idImpGeneral'];           // NULL|int
    $partidaArancelaria   = $r['partidaArancelaria'];     // NULL|string
    $registroMed          = $r['registroMed'];            // NULL|string
    $idFormaFarmaceutica  = $r['idFormaFarmaceutica'];    // NULL|int
    $esMedicamento        = $r['esMedicamento'];          // 0|1 calculado

    // id_producto autogenerado en memoria (semilla por empresa)
    $idProducto = $idProductoSeed;
    $idProductoSeed++;

    $vals = "(" .
        intval($idProducto) . "," .
        intval($empresaId) . "," .
        ($codigo !== '' ? "'" . sanitizeInput($codigo) . "'" : "NULL") . "," .
        "'" . sanitizeInput($codigoCabys) . "'," .
        "'" . sanitizeInput($nombre) . "'," .
        intval($idCategoria) . "," .
        ($idUnidadMedida !== null ? intval($idUnidadMedida) : "NULL") . "," .
        ($cantidad !== null ? (float)$cantidad : "NULL") . "," .
        ($precioUnitario !== null ? (float)$precioUnitario : "NULL") . "," .
        ($idImpGeneral !== null ? intval($idImpGeneral) : "NULL") . "," .
        /* descripcion */ "NULL," .
        intval($esMedicamento) . "," .
        ($registroMed !== null ? "'" . sanitizeInput($registroMed) . "'" : "NULL") . "," .
        /* med_fecha_v_registro */ "NULL," .
        /* med_id_tipo_medicamento */ "NULL," .
        /* med_principio_activo */ "NULL," .
        /* med_concentracion */ "NULL," .
        ($idFormaFarmaceutica !== null ? intval($idFormaFarmaceutica) : "NULL") . "," .
        /* codigo_barras */ "NULL," .
        /* marca */ "NULL," .
        /* modelo */ "NULL," .
        /* notas */ "NULL," .
        /* sku */ "NULL," .
        "1," . // estado
        intval($usuarioId) . ",'" . $ahora . "'," .
        intval($usuarioId) . ",'" . $ahora . "'," .
        ($partidaArancelaria !== null ? "'" . sanitizeInput($partidaArancelaria) . "'" : "NULL") .
        ")";

    $insertValues[] = [
        'values'     => $vals,
        'fila'       => $fila,
        'codigo'     => $codigo,
        'idProducto' => $idProducto
    ];
}

// ---------------------------------------------------------
// Ejecutar INSERTs por lotes (eficiente para 10k)
// ---------------------------------------------------------
if (!empty($insertValues)) {
    $colNames = "
        id_producto, id_empresa, cod_producto, cod_cabys, nombre,
        id_categoria, id_unidad_medida, cantidad, precio_unitario, id_imp_general,
        descripcion, es_medicamento,
        med_registro_sanitario, med_fecha_v_registro, med_id_tipo_medicamento,
        med_principio_activo, med_concentracion, med_id_forma_farmaceutica,
        codigo_barras, marca, modelo, notas, sku,
        estado, id_usuario_creacion, fecha_creacion, id_usuario_modificacion, fecha_modificacion,
        partida_arancelaria
    ";

    for ($i = 0; $i < count($insertValues); $i += $insertBatchSize) {
        $slice = array_slice($insertValues, $i, $insertBatchSize);
        $valuesSql = implode(',', array_map(fn($x) => $x['values'], $slice));
        $sqlIns = "INSERT INTO producto ($colNames) VALUES $valuesSql";
        $aff = update($sqlIns);

        if ($aff < 0) {
            // Fallback fila-a-fila para identificar registros problemáticos
            foreach ($slice as $one) {
                $sqlOne = "INSERT INTO producto ($colNames) VALUES " . $one['values'];
                $affOne = update($sqlOne);
                if ($affOne < 0) {
                    $insertErrores[] = ['fila' => $one['fila'], 'codigo' => $one['codigo'], 'error' => 'Error insertando'];
                    $resultados[]    = ['fila' => $one['fila'], 'codigo' => $one['codigo'], 'status' => 'error-insert'];
                } else {
                    $insertCount++;
                    addBitacora($usuarioId, 'producto', null, 'create', 'Carga masiva: insertado');
                    $resultados[] = ['fila' => $one['fila'], 'codigo' => $one['codigo'], 'status' => 'inserted'];
                }
            }
        } else {
            // Éxito del lote completo
            $insertCount += count($slice);
            foreach ($slice as $one) {
                addBitacora($usuarioId, 'producto', null, 'create', 'Carga masiva: insertado (lote)');
                $resultados[] = ['fila' => $one['fila'], 'codigo' => $one['codigo'], 'status' => 'inserted'];
            }
        }
    }
}

// ---------------------------------------------------------
// Respuesta
// ---------------------------------------------------------
$resumen = [
    'total'        => $totalFilas,
    'insertados'   => $insertCount,
    'actualizados' => 0,
    'errores'      => count($insertErrores)
];

$dataOut = [
    'resumen'       => $resumen,
    'resultados'    => $resultados,
    'erroresInsert' => $insertErrores
];

if ($insertCount === 0) {
    responseApi(400, 'No se insertó ninguna fila.', $dataOut);
}

responseApi(200, 'Carga procesada correctamente.', $dataOut);
