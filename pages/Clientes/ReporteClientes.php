<!-- ===================== SECCIÓN: Reporte de Clientes ===================== -->
<section class="container-fluid py-3" id="reporteClientesSection">
  <!-- Encabezado -->
  <div class="d-flex align-items-center justify-content-between mb-2">
    <h3 class="mb-0">
      <i class="ri-file-list-3-line me-2"></i> Reporte de Clientes
    </h3>
    <div class="d-flex gap-2">
      <!-- Exportar actual (CSV, sin dependencias) -->
      <button type="button" class="btn btn-outline-success" id="btnExportCsv">
        <i class="ri-file-excel-2-line me-1"></i> Exportar (CSV)
      </button>
      <!-- Exportar vía PHP (tabla simple, para Excel) -->
      <button type="button" class="btn btn-outline-primary" id="btnExportPhp">
        <i class="ri-external-link-line me-1"></i> Exportar vía PHP
      </button>
    </div>
  </div>

  <!-- Filtros -->
  <div class="card custom-card shadow-sm mb-3">
    <div class="card-body">
      <form class="row g-3 align-items-end" id="formFiltrosClientes">
        <div class="col-12 col-md-4">
          <label for="inputBuscarReporte" class="form-label mb-1">
            <i class="ri-search-line me-1"></i> Buscar
          </label>
          <div class="input-group">
            <span class="input-group-text"><i class="ri-search-line"></i></span>
            <input type="text" class="form-control" id="inputBuscarReporte" placeholder="Nombre, cédula, correo, código...">
          </div>
        </div>

        <div class="col-6 col-md-2">
          <label for="selTipoBusquedaReporte" class="form-label mb-1">
            <i class="ri-filter-3-line me-1"></i> Tipo
          </label>
          <select id="selTipoBusquedaReporte" class="form-select">
            <option value="">Todos</option>
            <option value="nombre">Nombre</option>
            <option value="cedula">Cédula</option>
            <option value="codigo">Código</option>
            <option value="correo">Correo</option>
          </select>
        </div>

        <div class="col-6 col-md-2">
          <label for="selProvinciaReporte" class="form-label mb-1">
            <i class="ri-map-pin-2-line me-1"></i> Provincia
          </label>
          <select id="selProvinciaReporte" class="form-select">
            <option value="">Todas</option>
            <option>San José</option><option>Alajuela</option><option>Cartago</option>
            <option>Heredia</option><option>Guanacaste</option><option>Puntarenas</option><option>Limón</option>
          </select>
        </div>

        <div class="col-6 col-md-2">
          <label for="selEstadoReporte" class="form-label mb-1">
            <i class="ri-toggle-line me-1"></i> Estado
          </label>
          <select id="selEstadoReporte" class="form-select">
            <option value="">Todos</option>
            <option value="Activo">Activo</option>
            <option value="Inactivo">Inactivo</option>
          </select>
        </div>

        <div class="col-6 col-md-2">
          <label for="selLimit" class="form-label mb-1">
            <i class="ri-list-ordered-2 me-1"></i> Filas a mostrar
          </label>
          <select id="selLimit" class="form-select">
            <option>10</option><option selected>25</option><option>50</option><option>100</option><option value="0">Todos</option>
          </select>
        </div>

        <div class="col-6 col-md-2 d-grid">
          <label class="form-label mb-1" style="visibility:hidden;">Buscar</label>
          <button type="button" class="btn btn-primary" id="btnAplicarFiltros">
            <i class="ri-search-line"></i> Aplicar
          </button>
        </div>

        <div class="col-6 col-md-2 d-grid">
          <label class="form-label mb-1" style="visibility:hidden;">Limpiar</label>
          <button type="button" class="btn btn-outline-secondary" id="btnLimpiarFiltros">
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
          Mostrando <span id="infoDesde">0</span>–<span id="infoHasta">0</span> de <span id="infoTotal">0</span>
        </div>
      </div>

      <div class="table-responsive">
        <table class="table table-hover table-sm align-middle" id="tablaReporteClientes">
          <thead class="table-light">
            <tr>
              <th>Código</th>
              <th>Cédula</th>
              <th>Nombre</th>
              <th>Correo</th>
              <th>Provincia</th>
              <th>Estado</th>
            </tr>
          </thead>
          <tbody id="tablaReporteBody">
            <!-- filas dinámicas -->
          </tbody>
        </table>
      </div>

      <!-- Navegación simple (opcional, client-side) -->
      <div class="d-flex justify-content-end align-items-center gap-2 mt-2">
        <button class="btn btn-sm btn-outline-secondary" id="btnPrevPagina"><i class="ri-arrow-left-s-line"></i></button>
        <span class="small">Página <span id="paginaActual">1</span></span>
        <button class="btn btn-sm btn-outline-secondary" id="btnNextPagina"><i class="ri-arrow-right-s-line"></i></button>
      </div>
    </div>
  </div>

  <!-- Form oculto para exportar vía PHP (tabla simple sin diseño) -->
  <form id="formExportPhp" action="/reportes/exportar_clientes.php" method="post" target="_blank" class="d-none">
    <!-- El backend debe leer este JSON y renderizar una tabla HTML simple y setear headers para Excel -->
    <textarea name="rowsJson" id="rowsJson"></textarea>
    <!-- Opcional: enviar metadatos de filtros -->
    <input type="hidden" name="filtrosJson" id="filtrosJson">
  </form>
</section>

<!-- ===================== Scripts Reporte ===================== -->
<script>
  // Dataset de ejemplo (remover y sustituir con fetch al backend)
  const clientesData = [
    { codigo:'CLI-001', cedula:'1-1234-5678', nombre:'Juan Pérez', correo:'juan.perez@ejemplo.com', provincia:'San José', estado:'Activo' },
    { codigo:'CLI-002', cedula:'3-101-456789', nombre:'María Gómez', correo:'maria.gomez@ejemplo.com', provincia:'Heredia', estado:'Inactivo' },
    { codigo:'CLI-003', cedula:'3-101-222222', nombre:'Carlos Rojas', correo:'carlos@empresa.com', provincia:'Cartago', estado:'Activo' },
    // ...
  ];

  // Estado UI
  let filteredRows = [...clientesData];
  let pagina = 1;

  // Elementos
  const inputBuscarReporte = document.getElementById('inputBuscarReporte');
  const selTipoBusquedaReporte = document.getElementById('selTipoBusquedaReporte');
  const selProvinciaReporte = document.getElementById('selProvinciaReporte');
  const selEstadoReporte = document.getElementById('selEstadoReporte');
  const selLimit = document.getElementById('selLimit');

  const tablaReporteBody = document.getElementById('tablaReporteBody');
  const infoDesde = document.getElementById('infoDesde');
  const infoHasta = document.getElementById('infoHasta');
  const infoTotal = document.getElementById('infoTotal');
  const paginaActual = document.getElementById('paginaActual');

  const btnAplicarFiltros = document.getElementById('btnAplicarFiltros');
  const btnLimpiarFiltros = document.getElementById('btnLimpiarFiltros');
  const btnPrevPagina = document.getElementById('btnPrevPagina');
  const btnNextPagina = document.getElementById('btnNextPagina');

  const btnExportCsv = document.getElementById('btnExportCsv');
  const btnExportPhp = document.getElementById('btnExportPhp');
  const formExportPhp = document.getElementById('formExportPhp');
  const rowsJson = document.getElementById('rowsJson');
  const filtrosJson = document.getElementById('filtrosJson');

  // Aplicar filtros
  const applyFilters = () => {
    const term = (inputBuscarReporte.value || '').trim().toLowerCase();
    const tipo = selTipoBusquedaReporte.value;
    const provincia = selProvinciaReporte.value;
    const estado = selEstadoReporte.value;

    filteredRows = clientesData.filter(row => {
      // provincia / estado
      if (provincia && row.provincia !== provincia) return false;
      if (estado && row.estado !== estado) return false;

      if (!term) return true;

      // tipo de búsqueda
      const checks = {
        nombre: () => (row.nombre || '').toLowerCase().includes(term),
        cedula: () => (row.cedula || '').toLowerCase().includes(term),
        codigo: () => (row.codigo || '').toLowerCase().includes(term),
        correo: () => (row.correo || '').toLowerCase().includes(term),
        '':       () => (
          (row.nombre||'').toLowerCase().includes(term) ||
          (row.cedula||'').toLowerCase().includes(term) ||
          (row.codigo||'').toLowerCase().includes(term) ||
          (row.correo||'').toLowerCase().includes(term)
        )
      };
      return (checks[tipo] || checks[''])();
    });

    pagina = 1;
    renderTable();
  };

  // Render tabla con limit y paginación simple
  const renderTable = () => {
    const limit = parseInt(selLimit.value, 10);
    const total = filteredRows.length;
    const pageSize = limit === 0 ? total : limit;

    const startIdx = (pagina - 1) * pageSize;
    const endIdx = Math.min(startIdx + pageSize, total);
    const slice = filteredRows.slice(startIdx, endIdx);

    tablaReporteBody.innerHTML = slice.map(r => `
      <tr>
        <td>${escapeHtml(r.codigo)}</td>
        <td>${escapeHtml(r.cedula)}</td>
        <td>${escapeHtml(r.nombre)}</td>
        <td>${escapeHtml(r.correo)}</td>
        <td>${escapeHtml(r.provincia)}</td>
        <td>${escapeHtml(r.estado)}</td>
      </tr>
    `).join('');

    infoDesde.textContent = total ? (startIdx + 1) : 0;
    infoHasta.textContent = endIdx;
    infoTotal.textContent = total;
    paginaActual.textContent = total ? pagina : 0;

    // deshabilitar botones si no hay más
    btnPrevPagina.disabled = (pagina <= 1) || (pageSize === total);
    btnNextPagina.disabled = (endIdx >= total) || (pageSize === total);
  };

  // Export CSV (sin dependencias)
  const exportCsv = (rows) => {
    if (!rows.length) return;
    const headers = ['Codigo','Cedula','Nombre','Correo','Provincia','Estado'];
    const lines = [headers.join(',')].concat(
      rows.map(r => headers.map(h => csvEscape(r[h.toLowerCase()] ?? r[h] ?? '')).join(','))
    );
    const blob = new Blob([lines.join('\n')], { type: 'text/csv;charset=utf-8;' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url; a.download = 'Reporte_Clientes.csv';
    document.body.appendChild(a); a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
  };

  // Export vía PHP (envía JSON de filas filtradas, sin diseño)
  const exportPhp = (rows) => {
    const payload = rows.map(r => ({
      Codigo: r.codigo, Cedula: r.cedula, Nombre: r.nombre,
      Correo: r.correo, Provincia: r.provincia, Estado: r.estado
    }));
    rowsJson.value = JSON.stringify(payload);
    filtrosJson.value = JSON.stringify({
      term: inputBuscarReporte.value,
      tipo: selTipoBusquedaReporte.value,
      provincia: selProvinciaReporte.value,
      estado: selEstadoReporte.value,
      limit: selLimit.value
    });
    formExportPhp.submit();
  };

  // Helpers
  const escapeHtml = (s) => String(s)
    .replaceAll('&','&amp;').replaceAll('<','&lt;')
    .replaceAll('>','&gt;').replaceAll('"','&quot;').replaceAll("'",'&#039;');

  const csvEscape = (val) => {
    const s = String(val ?? '');
    if (/[",\n]/.test(s)) return `"${s.replaceAll('"','""')}"`;
    return s;
  };

  // Eventos
  btnAplicarFiltros.addEventListener('click', applyFilters);
  btnLimpiarFiltros.addEventListener('click', () => {
    inputBuscarReporte.value = '';
    selTipoBusquedaReporte.value = '';
    selProvinciaReporte.value = '';
    selEstadoReporte.value = '';
    selLimit.value = '25';
    applyFilters();
  });
  selLimit.addEventListener('change', () => { pagina = 1; renderTable(); });

  btnPrevPagina.addEventListener('click', () => { if (pagina > 1) { pagina--; renderTable(); } });
  btnNextPagina.addEventListener('click', () => {
    const limit = parseInt(selLimit.value, 10);
    const pageSize = limit === 0 ? filteredRows.length : limit;
    const maxPage = Math.ceil(filteredRows.length / (pageSize || 1));
    if (pagina < maxPage) { pagina++; renderTable(); }
  });

  btnExportCsv.addEventListener('click', () => exportCsv(filteredRows));
  btnExportPhp.addEventListener('click', () => exportPhp(filteredRows));

  // Cargar inicial
  applyFilters();
</script>
