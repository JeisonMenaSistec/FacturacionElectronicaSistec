<!-- ===================== SECCIÓN: Reporte de Productos ===================== -->
<section class="container-fluid py-3" id="reporteProductosSection">
  <!-- Encabezado -->
  <div class="d-flex align-items-center justify-content-between mb-2">
    <h3 class="mb-0">
      <i class="ri-file-list-3-line me-2"></i> Reporte de Productos
    </h3>
    <div class="d-flex gap-2">
      <button type="button" class="btn btn-outline-success" id="btnExportExcelProductos">
        <i class="ri-file-excel-2-line me-1"></i> Exportar (Excel)
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
          </select>
        </div>

        <div class="col-6 col-md-2">
          <label for="selIvaProducto" class="form-label mb-1">
            <i class="ri-percent-line me-1"></i> IVA
          </label>
          <select id="selIvaProducto" class="form-select">
            <option value="">Todos</option>
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
            <option>10</option>
            <option selected>25</option>
            <option>50</option>
            <option>100</option>
            <option>500</option>
            <option>1000</option>
            <option>5000</option>
            <option>10000</option>
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

      <div id="alertaProductos" class="alert mt-3 d-none"></div>
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
          <tbody id="tablaReporteBodyProductos"></tbody>
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
</section>

<!-- ===================== Scripts Reporte Productos ===================== -->
<script>
  const apiReporteProductos = 'api/productos/reporte_productos.php';
  const apiHacienda = 'api/hacienda/index.php';
  const maxFilasPermitidas = 10000;

  // Estado
  let paginaProductos = 1;
  let tamPaginaProductos = 25;
  let totalProductos = 0;
  let rowsPaginaActual = [];

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

  const btnExportExcelProductos = document.getElementById('btnExportExcelProductos');
  const alertaProductos = document.getElementById('alertaProductos');

  // Helpers SweetAlert seguros
  const safeClose = () => {
    try {
      if (window.Swal) Swal.close();
    } catch (e) {}
    const fb = document.getElementById('loader-fallback');
    if (fb) fb.remove();
  };

  // UI helpers
  const escapeHtml = (s) => String(s ?? '')
    .replaceAll('&', '&amp;').replaceAll('<', '&lt;')
    .replaceAll('>', '&gt;').replaceAll('"', '&quot;').replaceAll("'", '&#039;');

  const moneyFmt = (n) => {
    const num = Number(n ?? 0);
    return num.toLocaleString('es-CR', {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2
    });
  };
  const numFmt = (n) => {
    const num = Number(n ?? 0);
    return isNaN(num) ? '' : num.toLocaleString('es-CR');
  };

  const showAlertProductos = (type, title, detail = '') => {
    alertaProductos.className = `alert alert-${type} mt-3`;
    alertaProductos.innerHTML = `<strong>${title}</strong>${detail ? '<div class="small mt-1">'+detail+'</div>' : ''}`;
    alertaProductos.classList.remove('d-none');
  };
  const clearAlertProductos = () => {
    alertaProductos.classList.add('d-none');
    alertaProductos.className = 'alert mt-3 d-none';
    alertaProductos.innerHTML = '';
  };

  const setLoadingUi = (isLoading) => {
    btnAplicarFiltrosProductos.disabled = isLoading;
    btnLimpiarFiltrosProductos.disabled = isLoading;
    btnExportExcelProductos.disabled = isLoading || (totalProductos <= 0);
  };

  // Filtros -> payload
  const buildFiltros = () => ({
    buscarTexto: (inputBuscarProducto.value || '').trim(),
    categoria: (selCategoriaProducto.value || '').trim(), // id_producto_categoria (código) o nombre
    iva: (selIvaProducto.value || '').trim(), // codigo de hacienda_imp_general
    estado: (selEstadoProducto.value || '').trim(),
    precioMin: (inputPrecioMin.value || '').trim(),
    precioMax: (inputPrecioMax.value || '').trim(),
    cantidadMin: (inputCantidadMin.value || '').trim()
  });

  // Cargar categorías para select (desde backend de productos)
  const cargarCategorias = async () => {
    const res = await fetch(apiReporteProductos, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        accion: 'categorias'
      })
    });
    const data = await res.json().catch(() => ({}));
    if (!res.ok) {
      const msg = data.message || `Error HTTP ${res.status}`;
      showAlertProductos('danger', 'No se pudieron cargar categorías.', msg);
      if (window.swalError) swalError(msg);
      return;
    }
    const categorias = Array.isArray(data?.data?.rows) ? data.data.rows : [];
    const options = ['<option value="">Todas</option>'].concat(
      categorias.map(c => {
        const codigo = c.idProductoCategoria ?? c.codigo ?? '';
        const nombre = c.nombre ?? '';
        return `<option value="${escapeHtml(codigo)}">${escapeHtml(nombre)} (${escapeHtml(codigo)})</option>`;
      })
    );
    selCategoriaProducto.innerHTML = options.join('');
  };

  // Cargar IVA para select (desde HACIENDA: imp_general)
  const cargarIva = async () => {
    const res = await fetch(apiHacienda, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        accion: 'imp_general'
      })
    });
    const data = await res.json().catch(() => ({}));
    if (!res.ok) {
      const msg = (data && data.message) ? data.message : `Error HTTP ${res.status}`;
      showAlertProductos('danger', 'No se pudo cargar IVA.', msg);
      if (window.swalError) swalError(msg);
      return;
    }
    const ivas = Array.isArray(data) ? data : [];
    const options = ['<option value="">Todos</option>'].concat(
      ivas.map(i => `<option value="${escapeHtml(i.codigo)}">${escapeHtml(i.codigo)}% - ${escapeHtml(i.descripcion || '')}</option>`)
    );
    selIvaProducto.innerHTML = options.join('');
  };

  // Carga selects dinámicos (NO dispara búsqueda)
  const cargarFiltrosDinamicos = async () => {
    try {
      if (window.swalLoading) swalLoading('Cargando filtros…');
      await Promise.all([cargarCategorias(), cargarIva()]);
      clearAlertProductos();
    } catch (err) {
      const msg = String(err);
      showAlertProductos('danger', 'Error de red al cargar filtros.', msg);
      if (window.swalError) swalError(msg);
    } finally {
      safeClose();
    }
  };

  // Fetch listar
  const fetchProductos = async (pagina, tamPagina) => {
    // clamp del tamaño de página al máximo permitido
    let pageSize = Math.max(1, Number(tamPagina) || 25);
    if (pageSize > maxFilasPermitidas) pageSize = maxFilasPermitidas;

    const payload = {
      accion: 'listar',
      pagina: Math.max(1, Number(pagina) || 1),
      tamPagina: pageSize,
      filtros: buildFiltros()
    };

    try {
      setLoadingUi(true);
      // if (window.swalLoading) swalLoading('Cargando…');

      const res = await fetch(apiReporteProductos, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(payload)
      });

      const data = await res.json().catch(() => ({}));
      if (!res.ok) {
        const msg = data.message || `Error HTTP ${res.status}`;
        showAlertProductos('danger', 'No se pudieron cargar los productos.', msg);
        if (window.swalError) swalError(msg);
        return {
          total: 0,
          data: []
        };
      }

      clearAlertProductos();
      return {
        total: Number(data?.data?.total || 0),
        data: Array.isArray(data?.data?.rows) ? data.data.rows : []
      };
    } catch (err) {
      const msg = String(err);
      showAlertProductos('danger', 'Error de red al consultar.', msg);
      if (window.swalError) swalError(msg);
      return {
        total: 0,
        data: []
      };
    } finally {
      safeClose();
      setLoadingUi(false);
    }
  };

  // Render
  const renderProductsTable = () => {
    const rows = rowsPaginaActual;
    tablaReporteBodyProductos.innerHTML = rows.map(r => `
      <tr>
        <td>${escapeHtml(r.codigo)}</td>
        <td>${escapeHtml(r.detalle ?? r.nombre ?? '')}</td>
        <td>${escapeHtml(r.unidad ?? '')}</td>
        <td>${escapeHtml(r.categoria ?? '')}</td>
        <td class="text-end">${numFmt(r.cantidad)}</td>
        <td class="text-end">₡ ${moneyFmt(r.precio)}</td>
        <td>${escapeHtml(r.codigoCabys ?? r.cabys ?? '')}</td>
        <td class="text-end">${escapeHtml(r.tarifaIva ?? r.iva ?? '')}</td>
        <td>${escapeHtml(r.estado ?? '')}</td>
      </tr>
    `).join('');

    const desde = rows.length ? ((paginaProductos - 1) * tamPaginaProductos + 1) : 0;
    const hasta = rows.length ? ((paginaProductos - 1) * tamPaginaProductos + rows.length) : 0;

    infoDesdeProductos.textContent = desde;
    infoHastaProductos.textContent = hasta;
    infoTotalProductos.textContent = totalProductos;
    paginaActualProductos.textContent = totalProductos ? paginaProductos : 0;

    const maxPage = Math.max(1, Math.ceil(totalProductos / tamPaginaProductos));
    btnPrevPaginaProductos.disabled = (paginaProductos <= 1);
    btnNextPaginaProductos.disabled = (paginaProductos >= maxPage);

    btnExportExcelProductos.disabled = (totalProductos <= 0);
  };

  // Acciones de consulta (solo con botón Aplicar o Limpiar)
  const applyProductFilters = async () => {
    // tomar el tamaño actual, pero NO disparar al cambiar, solo aquí:
    let v = parseInt(selLimitProductos.value, 10) || 25;
    if (v > maxFilasPermitidas) {
      v = maxFilasPermitidas;
      selLimitProductos.value = String(maxFilasPermitidas);
      if (window.notify) notify('Máximo 10 000 filas por página.', 'warning');
    }
    tamPaginaProductos = v;

    paginaProductos = 1;
    const {
      total,
      data
    } = await fetchProductos(paginaProductos, tamPaginaProductos);
    totalProductos = total;
    rowsPaginaActual = data;
    renderProductsTable();
  };

  const goToPage = async (pagina) => {
    paginaProductos = Math.max(1, pagina);
    const {
      total,
      data
    } = await fetchProductos(paginaProductos, tamPaginaProductos);
    totalProductos = total;
    rowsPaginaActual = data;
    renderProductsTable();
  };

  const exportExcelProductos = async () => {
    try {
      setLoadingUi(true);
      if (window.swalLoading) swalLoading('Preparando Excel…');

      const payload = {
        accion: 'exportar',
        filtros: buildFiltros(),
        maxFilas: maxFilasPermitidas
      };

      const res = await fetch(apiReporteProductos, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(payload)
      });
      const data = await res.json().catch(() => ({}));

      if (!res.ok) {
        const msg = data.message || `Error HTTP ${res.status}`;
        showAlertProductos('danger', 'No se pudo exportar.', msg);
        if (window.swalError) swalError(msg);
        return;
      }

      const rows = Array.isArray(data?.data?.rows) ? data.data.rows : [];
      if (!rows.length) {
        showAlertProductos('warning', 'Sin datos para exportar', 'Ajusta los filtros e inténtalo nuevamente.');
        if (window.notify) notify('No hay datos para exportar', 'warning');
        return;
      }

      const headers = [
        'Codigo', 'CodigoCabys', 'Detalle', 'Unidad', 'Cantidad', 'Precio',
        'TarifaIVA', 'Categoria', 'RegistroMedicamento', 'FormaFarmaceutica', 'PartidaArancelaria'
      ];

      // Normalización Precio con punto decimal
      const normalizedRows = rows.map(r => ({
        Codigo: String(r.Codigo ?? r.codigo ?? ''),
        CodigoCabys: String(r.CodigoCabys ?? r.codigoCabys ?? r.Cabys ?? ''),
        Detalle: String(r.Detalle ?? r.detalle ?? r.Nombre ?? r.nombre ?? ''),
        Unidad: String(r.Unidad ?? r.unidad ?? ''),
        Cantidad: r.Cantidad ?? r.cantidad ?? '',
        Precio: (() => {
          const v = Number(r.Precio ?? r.precio ?? 0);
          return (Math.round(v * 100) / 100).toFixed(2).replace(',', '.');
        })(),
        TarifaIVA: String(r.TarifaIVA ?? r.tarifaIva ?? r.iva ?? ''),
        Categoria: String(r.Categoria ?? r.categoria ?? ''),
        RegistroMedicamento: String(r.RegistroMedicamento ?? r.registroMedicamento ?? r.medRegistroSanitario ?? ''),
        FormaFarmaceutica: String(r.FormaFarmaceutica ?? r.formaFarmaceutica ?? ''),
        PartidaArancelaria: String(r.PartidaArancelaria ?? r.partidaArancelaria ?? '')
      }));

      const ws = XLSX.utils.json_to_sheet(normalizedRows, {
        header: headers
      });
      ws['!cols'] = headers.map(() => ({
        wch: 20
      }));
      const wb = XLSX.utils.book_new();
      XLSX.utils.book_append_sheet(wb, ws, 'Productos');

      const now = new Date();
      const fecha = now.toISOString().slice(0, 10);
      const hora = now.toTimeString().slice(0, 8).replace(/:/g, '-');
      XLSX.writeFile(wb, `Reporte_Productos_${fecha}_${hora}.xlsx`);

      if (window.swalOk) swalOk('Archivo generado correctamente.');
    } catch (err) {
      const msg = String(err);
      showAlertProductos('danger', 'Error exportando.', msg);
      if (window.swalError) swalError(msg);
    } finally {
      safeClose();
      setLoadingUi(false);
    }
  };

  // Eventos (no buscamos en onchange; solo con botones)
  btnAplicarFiltrosProductos.addEventListener('click', applyProductFilters);
  btnLimpiarFiltrosProductos.addEventListener('click', async () => {
    inputBuscarProducto.value = '';
    selCategoriaProducto.value = '';
    selIvaProducto.value = '';
    selEstadoProducto.value = '';
    inputPrecioMin.value = '';
    inputPrecioMax.value = '';
    inputCantidadMin.value = '';
    selLimitProductos.value = '25';
    await applyProductFilters();
  });


  selLimitProductos.addEventListener('change', () => {
    let v = parseInt(selLimitProductos.value, 10) || 25;
    if (v > maxFilasPermitidas) {
      v = maxFilasPermitidas;
      selLimitProductos.value = String(maxFilasPermitidas);
      if (window.notify) notify('Máximo 10 000 filas por página.', 'warning');
    }
    tamPaginaProductos = v; // se aplicará hasta que presionen "Aplicar"
  });

  btnPrevPaginaProductos.addEventListener('click', async () => {
    if (paginaProductos > 1) await goToPage(paginaProductos - 1);
  });

  btnNextPaginaProductos.addEventListener('click', async () => {
    const maxPage = Math.max(1, Math.ceil(totalProductos / tamPaginaProductos));
    if (paginaProductos < maxPage) await goToPage(paginaProductos + 1);
  });

  btnExportExcelProductos.addEventListener('click', exportExcelProductos);

  // Arranque: carga de selects sin buscar, luego primera consulta manual
  (async () => {
    await cargarFiltrosDinamicos();
    await applyProductFilters();
  })();
</script>
<!-- SheetJS para exportar a Excel -->
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>