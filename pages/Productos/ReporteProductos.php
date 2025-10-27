<!-- ===================== SECCIÓN: Reporte de Productos ===================== -->
<section class="container-fluid py-3" id="reporteProductosSection">
  <!-- Encabezado -->
  <div class="d-flex align-items-center justify-content-between mb-2">
    <h3 class="mb-0">
      <i class="ri-file-list-3-line me-2"></i> Reporte de Productos
    </h3>
    <div class="d-flex gap-2">
      <!-- Exportar actual (CSV, sin dependencias) -->
      <button type="button" class="btn btn-outline-success" id="btnExportCsvProductos">
        <i class="ri-file-excel-2-line me-1"></i> Exportar (CSV)
      </button>
      <!-- Exportar vía PHP (tabla simple para Excel) -->
      <button type="button" class="btn btn-outline-primary" id="btnExportPhpProductos">
        <i class="ri-external-link-line me-1"></i> Exportar vía PHP
      </button>
    </div>
  </div>

  <!-- Filtros -->
  <div class="card custom-card shadow-sm mb-3">
    <div class="card-body">
      <form class="row g-3 align-items-end" id="formFiltrosProductos">
        <div class="col-12 col-md-4">
          <label for="inputBuscarProducto" class="form-label mb-1">
            <i class="ri-search-line me-1"></i> Buscar
          </label>
          <div class="input-group">
            <span class="input-group-text"><i class="ri-search-line"></i></span>
            <input type="text" class="form-control" id="inputBuscarProducto" placeholder="Código, nombre, CABYS, categoría...">
          </div>
        </div>

        <div class="col-6 col-md-2">
          <label for="selCategoriaProducto" class="form-label mb-1">
            <i class="ri-price-tag-3-line me-1"></i> Categoría
          </label>
          <select id="selCategoriaProducto" class="form-select">
            <option value="">Todas</option>
            <option>General</option>
            <option>Alimentos</option>
            <option>Farmacia</option>
            <option>Servicios</option>
          </select>
        </div>

        <div class="col-6 col-md-2">
          <label for="selIvaProducto" class="form-label mb-1">
            <i class="ri-percent-line me-1"></i> IVA
          </label>
          <select id="selIvaProducto" class="form-select">
            <option value="">Todos</option>
            <option value="0">0%</option>
            <option value="1">1%</option>
            <option value="2">2%</option>
            <option value="4">4%</option>
            <option value="13">13%</option>
          </select>
        </div>

        <div class="col-6 col-md-2">
          <label for="selEstadoProducto" class="form-label mb-1">
            <i class="ri-toggle-line me-1"></i> Estado
          </label>
          <select id="selEstadoProducto" class="form-select">
            <option value="">Todos</option>
            <option value="Activo">Activo</option>
            <option value="Inactivo">Inactivo</option>
          </select>
        </div>

        <div class="col-6 col-md-2">
          <label for="inputPrecioMin" class="form-label mb-1">
            <i class="ri-money-dollar-circle-line me-1"></i> Precio mín.
          </label>
          <input type="number" class="form-control" id="inputPrecioMin" min="0" step="0.01" placeholder="0.00">
        </div>

        <div class="col-6 col-md-2">
          <label for="inputPrecioMax" class="form-label mb-1">
            <i class="ri-money-dollar-circle-line me-1"></i> Precio máx.
          </label>
          <input type="number" class="form-control" id="inputPrecioMax" min="0" step="0.01" placeholder="999999">
        </div>

        <div class="col-6 col-md-2">
          <label for="inputCantidadMin" class="form-label mb-1">
            <i class="ri-box-1-line me-1"></i> Cantidad mín.
          </label>
          <input type="number" class="form-control" id="inputCantidadMin" min="0" step="1" placeholder="0">
        </div>

        <div class="col-6 col-md-2">
          <label for="selLimitProductos" class="form-label mb-1">
            <i class="ri-list-ordered-2 me-1"></i> Filas a mostrar
          </label>
          <select id="selLimitProductos" class="form-select">
            <option>10</option><option selected>25</option><option>50</option><option>100</option><option value="0">Todos</option>
          </select>
        </div>

        <div class="col-6 col-md-2 d-grid">
          <label class="form-label mb-1" style="visibility:hidden;">Aplicar</label>
          <button type="button" class="btn btn-primary" id="btnAplicarFiltrosProductos">
            <i class="ri-search-line"></i> Aplicar
          </button>
        </div>

        <div class="col-6 col-md-2 d-grid">
          <label class="form-label mb-1" style="visibility:hidden;">Limpiar</label>
          <button type="button" class="btn btn-outline-secondary" id="btnLimpiarFiltrosProductos">
            <i class="ri-refresh-line"></i> Limpiar
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Tabla -->
  <div class="card custom-card shadow-sm">
    <div class="card-body">
      <div class="d-flex align-items-center justify-content-between">
        <h6 class="text-uppercase mb-3"><strong>Resultados</strong></h6>
        <div class="small text-muted">
          Mostrando <span id="infoDesdeProductos">0</span>–<span id="infoHastaProductos">0</span> de <span id="infoTotalProductos">0</span>
        </div>
      </div>

      <div class="table-responsive">
        <table class="table table-hover table-sm align-middle" id="tablaReporteProductos">
          <thead class="table-light">
            <tr>
              <th>Código</th>
              <th>Nombre</th>
              <th>Unidad</th>
              <th>Categoría</th>
              <th class="text-end">Cantidad</th>
              <th class="text-end">Precio</th>
              <th>CABYS</th>
              <th class="text-end">IVA (%)</th>
              <th>Estado</th>
            </tr>
          </thead>
          <tbody id="tablaReporteBodyProductos">
            <!-- filas dinámicas -->
          </tbody>
        </table>
      </div>

      <!-- Navegación simple -->
      <div class="d-flex justify-content-end align-items-center gap-2 mt-2">
        <button class="btn btn-sm btn-outline-secondary" id="btnPrevPaginaProductos"><i class="ri-arrow-left-s-line"></i></button>
        <span class="small">Página <span id="paginaActualProductos">1</span></span>
        <button class="btn btn-sm btn-outline-secondary" id="btnNextPaginaProductos"><i class="ri-arrow-right-s-line"></i></button>
      </div>
    </div>
  </div>

  <!-- Form oculto para exportar vía PHP (tabla simple sin diseño) -->
  <form id="formExportPhpProductos" action="/reportes/exportar_productos.php" method="post" target="_blank" class="d-none">
    <!-- El backend debe leer este JSON y renderizar una tabla HTML simple y setear headers para Excel -->
    <textarea name="rowsJson" id="rowsJsonProductos"></textarea>
    <!-- Opcional: enviar metadatos de filtros -->
    <input type="hidden" name="filtrosJson" id="filtrosJsonProductos">
  </form>
</section>

<!-- ===================== Scripts Reporte Productos ===================== -->
<script>
  // Dataset de ejemplo (remover y sustituir con fetch al backend)
  const productsData = [
    { codigo:'PRD-0001', nombre:'Acetaminofén 500mg', unidad:'Unid', categoria:'Farmacia', cantidad:150, precio:1250.00, cabys:'101010101', iva:13, estado:'Activo' },
    { codigo:'PRD-0002', nombre:'Bolsa de arroz 1Kg', unidad:'Kg', categoria:'Alimentos', cantidad:45, precio:1050.50, cabys:'101010202', iva:13, estado:'Activo' },
    { codigo:'PRD-0003', nombre:'Servicio técnico básico', unidad:'Unid', categoria:'Servicios', cantidad:0, precio:25000.00, cabys:'201020303', iva:13, estado:'Inactivo' },
    { codigo:'PRD-0004', nombre:'Leche deslactosada 1L', unidad:'Lt', categoria:'Alimentos', cantidad:220, precio:890.00, cabys:'101010404', iva:1, estado:'Activo' },
    { codigo:'PRD-0005', nombre:'Producto exento', unidad:'Unid', categoria:'General', cantidad:10, precio:0.00, cabys:'101010505', iva:0, estado:'Inactivo' },
    // ...
  ];

  // Estado UI
  let filteredProducts = [...productsData];
  let paginaProductos = 1;

  // Elementos
  const inputBuscarProducto = document.getElementById('inputBuscarProducto');
  const selCategoriaProducto = document.getElementById('selCategoriaProducto');
  const selIvaProducto = document.getElementById('selIvaProducto');
  const selEstadoProducto = document.getElementById('selEstadoProducto');
  const inputPrecioMin = document.getElementById('inputPrecioMin');
  const inputPrecioMax = document.getElementById('inputPrecioMax');
  const inputCantidadMin = document.getElementById('inputCantidadMin');
  const selLimitProductos = document.getElementById('selLimitProductos');

  const tablaReporteBodyProductos = document.getElementById('tablaReporteBodyProductos');
  const infoDesdeProductos = document.getElementById('infoDesdeProductos');
  const infoHastaProductos = document.getElementById('infoHastaProductos');
  const infoTotalProductos = document.getElementById('infoTotalProductos');
  const paginaActualProductos = document.getElementById('paginaActualProductos');

  const btnAplicarFiltrosProductos = document.getElementById('btnAplicarFiltrosProductos');
  const btnLimpiarFiltrosProductos = document.getElementById('btnLimpiarFiltrosProductos');
  const btnPrevPaginaProductos = document.getElementById('btnPrevPaginaProductos');
  const btnNextPaginaProductos = document.getElementById('btnNextPaginaProductos');

  const btnExportCsvProductos = document.getElementById('btnExportCsvProductos');
  const btnExportPhpProductos = document.getElementById('btnExportPhpProductos');
  const formExportPhpProductos = document.getElementById('formExportPhpProductos');
  const rowsJsonProductos = document.getElementById('rowsJsonProductos');
  const filtrosJsonProductos = document.getElementById('filtrosJsonProductos');

  // Aplicar filtros
  const applyProductFilters = () => {
    const term = (inputBuscarProducto.value || '').trim().toLowerCase();
    const categoria = selCategoriaProducto.value;
    const iva = selIvaProducto.value;
    const estado = selEstadoProducto.value;
    const pmin = parseFloat(inputPrecioMin.value);
    const pmax = parseFloat(inputPrecioMax.value);
    const qmin = parseFloat(inputCantidadMin.value);

    filteredProducts = productsData.filter(row => {
      if (categoria && row.categoria !== categoria) return false;
      if (iva !== '' && String(row.iva) !== String(iva)) return false;
      if (estado && row.estado !== estado) return false;

      if (!isNaN(pmin) && Number(row.precio) < pmin) return false;
      if (!isNaN(pmax) && Number(row.precio) > pmax) return false;
      if (!isNaN(qmin) && Number(row.cantidad) < qmin) return false;

      if (!term) return true;
      return (
        (row.codigo || '').toLowerCase().includes(term) ||
        (row.nombre || '').toLowerCase().includes(term) ||
        (row.cabys || '').toLowerCase().includes(term) ||
        (row.categoria || '').toLowerCase().includes(term)
      );
    });

    paginaProductos = 1;
    renderProductsTable();
  };

  // Render tabla con limit y paginación simple
  const renderProductsTable = () => {
    const limit = parseInt(selLimitProductos.value, 10);
    const total = filteredProducts.length;
    const pageSize = limit === 0 ? total : limit;

    const startIdx = (paginaProductos - 1) * pageSize;
    const endIdx = Math.min(startIdx + pageSize, total);
    const slice = filteredProducts.slice(startIdx, endIdx);

    tablaReporteBodyProductos.innerHTML = slice.map(r => `
      <tr>
        <td>${escapeHtmlProductos(r.codigo)}</td>
        <td>${escapeHtmlProductos(r.nombre)}</td>
        <td>${escapeHtmlProductos(r.unidad)}</td>
        <td>${escapeHtmlProductos(r.categoria)}</td>
        <td class="text-end">${numFmt(r.cantidad)}</td>
        <td class="text-end">₡ ${moneyFmt(r.precio)}</td>
        <td>${escapeHtmlProductos(r.cabys)}</td>
        <td class="text-end">${numFmt(r.iva)}</td>
        <td>${escapeHtmlProductos(r.estado)}</td>
      </tr>
    `).join('');

    infoDesdeProductos.textContent = total ? (startIdx + 1) : 0;
    infoHastaProductos.textContent = endIdx;
    infoTotalProductos.textContent = total;
    paginaActualProductos.textContent = total ? paginaProductos : 0;

    btnPrevPaginaProductos.disabled = (paginaProductos <= 1) || (pageSize === total);
    btnNextPaginaProductos.disabled = (endIdx >= total) || (pageSize === total);
  };

  // Export CSV (sin dependencias)
  const exportCsvProductos = (rows) => {
    if (!rows.length) return;
    const headers = ['Codigo','Nombre','Unidad','Categoria','Cantidad','Precio','Cabys','Iva','Estado'];
    const lines = [headers.join(',')].concat(
      rows.map(r => [
        r.codigo, r.nombre, r.unidad, r.categoria,
        r.cantidad, r.precio, r.cabys, r.iva, r.estado
      ].map(csvEscapeProductos).join(','))
    );
    const blob = new Blob([lines.join('\n')], { type: 'text/csv;charset=utf-8;' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url; a.download = 'Reporte_Productos.csv';
    document.body.appendChild(a); a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
  };

  // Export vía PHP (envía JSON de filas filtradas, sin diseño)
  const exportPhpProductos = (rows) => {
    const payload = rows.map(r => ({
      Codigo: r.codigo,
      Nombre: r.nombre,
      Unidad: r.unidad,
      Categoria: r.categoria,
      Cantidad: r.cantidad,
      Precio: r.precio,
      Cabys: r.cabys,
      Iva: r.iva,
      Estado: r.estado
    }));
    rowsJsonProductos.value = JSON.stringify(payload);
    filtrosJsonProductos.value = JSON.stringify({
      term: inputBuscarProducto.value,
      categoria: selCategoriaProducto.value,
      iva: selIvaProducto.value,
      estado: selEstadoProducto.value,
      precioMin: inputPrecioMin.value,
      precioMax: inputPrecioMax.value,
      cantidadMin: inputCantidadMin.value,
      limit: selLimitProductos.value
    });
    formExportPhpProductos.submit();
  };

  // Helpers
  const escapeHtmlProductos = (s) => String(s)
    .replaceAll('&','&amp;').replaceAll('<','&lt;')
    .replaceAll('>','&gt;').replaceAll('"','&quot;').replaceAll("'",'&#039;');

  const csvEscapeProductos = (val) => {
    const s = String(val ?? '');
    if (/[",\n]/.test(s)) return `"${s.replaceAll('"','""')}"`;
    return s;
  };

  const moneyFmt = (n) => {
    const num = Number(n || 0);
    return num.toLocaleString('es-CR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    // Si prefieres sin localización, usa: return (Math.round(num*100)/100).toFixed(2);
  };

  const numFmt = (n) => {
    const num = Number(n || 0);
    return num.toLocaleString('es-CR');
  };

  // Eventos
  btnAplicarFiltrosProductos.addEventListener('click', applyProductFilters);
  btnLimpiarFiltrosProductos.addEventListener('click', () => {
    inputBuscarProducto.value = '';
    selCategoriaProducto.value = '';
    selIvaProducto.value = '';
    selEstadoProducto.value = '';
    inputPrecioMin.value = '';
    inputPrecioMax.value = '';
    inputCantidadMin.value = '';
    selLimitProductos.value = '25';
    applyProductFilters();
  });
  selLimitProductos.addEventListener('change', () => { paginaProductos = 1; renderProductsTable(); });

  btnPrevPaginaProductos.addEventListener('click', () => { if (paginaProductos > 1) { paginaProductos--; renderProductsTable(); } });
  btnNextPaginaProductos.addEventListener('click', () => {
    const limit = parseInt(selLimitProductos.value, 10);
    const pageSize = limit === 0 ? filteredProducts.length : limit;
    const maxPage = Math.ceil(filteredProducts.length / (pageSize || 1));
    if (paginaProductos < maxPage) { paginaProductos++; renderProductsTable(); }
  });

  btnExportCsvProductos.addEventListener('click', () => exportCsvProductos(filteredProducts));
  btnExportPhpProductos.addEventListener('click', () => exportPhpProductos(filteredProducts));

  // Cargar inicial
  applyProductFilters();
</script>
