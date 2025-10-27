<!-- ===================== SECCIÓN: Cargar Productos ===================== -->
<section class="container-fluid py-3" id="cargarProductosSection">
    <!-- Encabezado -->
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h3 class="mb-0"><i class="ri-upload-2-line me-2"></i> Cargar Productos</h3>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-secondary" id="btnDescargarPlantilla">
                <i class="ri-file-download-line me-1"></i> Descargar plantilla (CSV)
            </button>
            <button type="button" class="btn btn-outline-secondary" id="btnLimpiarCarga">
                <i class="ri-refresh-line me-1"></i> Limpiar
            </button>
        </div>
    </div>

    <!-- Zona de carga -->
    <div class="card custom-card shadow-sm mb-3">
        <div class="card-body">
            <div id="dropArea" class="border border-2 border-dashed rounded-3 p-4 text-center position-relative">
                <input type="file" id="fileInput" class="position-absolute top-0 start-0 w-100 h-100 opacity-0" accept=".xlsx,.xls,.csv" />
                <div class="py-3">
                    <i class="ri-upload-cloud-2-line" style="font-size: 48px;"></i>
                    <h5 class="mt-2 mb-1">Arrastra tu archivo aquí</h5>
                    <p class="text-muted mb-2">o haz clic para buscar un archivo Excel/CSV</p>
                    <div class="small">
                        <span class="badge bg-light text-dark">Formatos aceptados: .xlsx, .xls, .csv</span>
                    </div>
                </div>
            </div>

            <!-- Estado de validación -->
            <div class="mt-3" id="validacionEstado" hidden>
                <div class="alert" id="validacionAlert" role="alert"></div>
                <div id="validacionDetalle" class="small"></div>
            </div>
        </div>
    </div>

    <!-- Vista previa -->
    <div class="card custom-card shadow-sm">
        <div class="card-body">
            <div class="d-flex align-items-center justify-content-between">
                <h6 class="text-uppercase mb-3"><strong>Vista previa (máx. 100 filas)</strong></h6>
                <div class="small text-muted" id="resumenCarga">Sin datos cargados</div>
            </div>
            <div class="table-responsive">
                <table class="table table-sm table-hover align-middle" id="tablaPreview">
                    <thead class="table-light">
                        <tr id="tablaPreviewHead">
                            <!-- Cabeceras esperadas (precargadas) -->
                        </tr>
                    </thead>
                    <tbody id="tablaPreviewBody">
                        <!-- Filas dinámicas -->
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-3">
                <button type="button" class="btn btn-outline-secondary" id="btnDescargarErrores" disabled>
                    <i class="ri-bug-line me-1"></i> Descargar errores
                </button>
                <button type="button" class="btn btn-primary" id="btnProcesar" disabled>
                    <i class="ri-check-line me-1"></i> Procesar carga
                </button>
            </div>
        </div>
    </div>
</section>

<!-- ===================== Scripts de la sección ===================== -->
<script>
    // Cabeceras esperadas (orden y nombres exactos)
    const expectedHeaders = [
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

    // Estado en memoria
    let parsedRows = []; // Array de objetos válidos
    let headerErrors = []; // Diferencias de encabezados
    let rowErrors = []; // Errores por fila

    // Elementos
    const dropArea = document.getElementById('dropArea');
    const fileInput = document.getElementById('fileInput');
    const tablaPreviewHead = document.getElementById('tablaPreviewHead');
    const tablaPreviewBody = document.getElementById('tablaPreviewBody');
    const resumenCarga = document.getElementById('resumenCarga');
    const validacionEstado = document.getElementById('validacionEstado');
    const validacionAlert = document.getElementById('validacionAlert');
    const validacionDetalle = document.getElementById('validacionDetalle');
    const btnProcesar = document.getElementById('btnProcesar');
    const btnDescargarPlantilla = document.getElementById('btnDescargarPlantilla');
    const btnDescargarErrores = document.getElementById('btnDescargarErrores');
    const btnLimpiarCarga = document.getElementById('btnLimpiarCarga');

    // Precargar cabeceras visibles
    const renderExpectedHeader = () => {
        tablaPreviewHead.innerHTML = expectedHeaders.map(h => `<th>${h}</th>`).join('');
    };
    renderExpectedHeader();

    // Estilos drag & drop
    ['dragenter', 'dragover'].forEach(evt => {
        dropArea.addEventListener(evt, e => {
            e.preventDefault();
            e.stopPropagation();
            dropArea.classList.add('border-primary');
            dropArea.classList.remove('border-light');
        });
    });
    ['dragleave', 'drop'].forEach(evt => {
        dropArea.addEventListener(evt, e => {
            e.preventDefault();
            e.stopPropagation();
            dropArea.classList.remove('border-primary');
        });
    });
    dropArea.addEventListener('drop', e => {
        const file = e.dataTransfer.files?.[0];
        if (file) handleFile(file);
    });
    fileInput.addEventListener('change', e => {
        const file = e.target.files?.[0];
        if (file) handleFile(file);
    });

    // Manejo de archivo
    const handleFile = (file) => {
        resetState();
        const ext = (file.name.split('.').pop() || '').toLowerCase();
        if (['xlsx', 'xls'].includes(ext)) {
            if (typeof XLSX === 'undefined') {
                showAlert(
                    'warning',
                    'Necesita incluir la librería XLSX en el <head> para leer archivos de Excel (.xlsx, .xls).',
                    'Sugerencia: <code>https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js</code>'
                );
                return;
            }
            readExcel(file);
        } else if (ext === 'csv') {
            readCsv(file);
        } else {
            showAlert('danger', 'Formato no soportado.', 'Use .xlsx, .xls o .csv.');
        }
    };

    // Lectura de Excel con SheetJS (si está disponible en el proyecto)
    const readExcel = (file) => {
        const reader = new FileReader();
        reader.onload = (e) => {
            try {
                const data = new Uint8Array(e.target.result);
                const wb = XLSX.read(data, {
                    type: 'array'
                });
                const ws = wb.Sheets[wb.SheetNames[0]];
                const json = XLSX.utils.sheet_to_json(ws, {
                    header: 1,
                    raw: false,
                    defval: ''
                }); // matriz [filas][celdas]
                processMatrix(json);
            } catch (err) {
                showAlert('danger', 'No se pudo leer el archivo Excel.', String(err));
            }
        };
        reader.readAsArrayBuffer(file);
    };

    // Lectura de CSV (simple) con autodetección de separador (coma o tabulador)
    const readCsv = (file) => {
        const reader = new FileReader();
        reader.onload = (e) => {
            try {
                const text = e.target.result;
                const sep = text.indexOf('\t') > -1 ? '\t' : ','; // manejar CSV/TSV
                const rows = text.replace(/\r/g, '').split('\n').filter(r => r.trim().length > 0);
                const matrix = rows.map(r => splitCsvRow(r, sep));
                processMatrix(matrix);
            } catch (err) {
                showAlert('danger', 'No se pudo leer el archivo CSV.', String(err));
            }
        };
        reader.readAsText(file, 'utf-8');
    };

    // Split CSV/TSV respetando comillas
    const splitCsvRow = (row, sep) => {
        const out = [];
        let cur = '';
        let inQuotes = false;
        for (let i = 0; i < row.length; i++) {
            const ch = row[i];
            if (ch === '"') {
                if (inQuotes && row[i + 1] === '"') {
                    cur += '"';
                    i++;
                } else {
                    inQuotes = !inQuotes;
                }
            } else if (ch === sep && !inQuotes) {
                out.push(cur);
                cur = '';
            } else {
                cur += ch;
            }
        }
        out.push(cur);
        return out;
    };

    // Procesar matriz [ [header...], [row1...], ... ]
    const processMatrix = (matrix) => {
        if (!matrix || matrix.length === 0) {
            showAlert('danger', 'El archivo está vacío.');
            return;
        }
        const headers = (matrix[0] || []).map(h => (h || '').trim());
        validateHeaders(headers);

        if (headerErrors.length > 0) {
            renderHeaderErrors(headers);
            btnProcesar.disabled = true;
            return;
        }

        // Mapear filas a objetos
        const rows = matrix.slice(1).filter(r => r.length && r.some(v => String(v).trim() !== ''));
        const limited = rows.slice(0, 100);
        parsedRows = limited.map((r, idx) => {
            const obj = {};
            expectedHeaders.forEach((h, i) => obj[h] = (r[i] ?? '').toString().trim());
            // Validaciones básicas de ejemplo
            const errs = [];
            if (!obj.Codigo) errs.push('Codigo vacío');
            if (!obj.Detalle) errs.push('Detalle vacío');
            if (obj.Cantidad && isNaN(Number(obj.Cantidad))) errs.push('Cantidad no numérica');
            if (obj.Precio && isNaN(Number(obj.Precio))) errs.push('Precio no numérico');
            if (obj.TarifaIVA && isNaN(Number(obj.TarifaIVA))) errs.push('TarifaIVA no numérica');
            if (errs.length) rowErrors.push({
                fila: idx + 2,
                errores: errs
            }); // +2 por header y base 1
            return obj;
        });

        // Render preview
        renderPreview(parsedRows);
        // Mostrar resumen y errores
        const total = rows.length;
        const shown = parsedRows.length;
        resumenCarga.textContent = `Mostrando ${shown} de ${total} filas | Errores en ${rowErrors.length} filas`;
        if (rowErrors.length > 0) {
            showAlert('warning', 'Archivo válido, pero hay filas con observaciones.', 'Puede descargar el detalle de errores.');
            btnDescargarErrores.disabled = false;
        } else {
            showAlert('success', 'Encabezados y datos válidos.', 'Puede proceder con la carga.');
            btnDescargarErrores.disabled = true;
        }
        btnProcesar.disabled = false;
    };

    // Validar encabezados (orden y nombre exactos)
    const validateHeaders = (headers) => {
        headerErrors = [];
        if (headers.length !== expectedHeaders.length) {
            headerErrors.push(`Se esperaban ${expectedHeaders.length} columnas y llegaron ${headers.length}.`);
        }
        expectedHeaders.forEach((h, i) => {
            if ((headers[i] || '') !== h) {
                headerErrors.push(`Columna ${i + 1}: esperado "<strong>${h}</strong>" y llegó "<strong>${headers[i] || '(vacío)'}"</strong>.`);
            }
        });
    };

    // Renderizar preview
    const renderPreview = (rows) => {
        // Cabecera (asegurar orden esperado)
        renderExpectedHeader();
        // Body
        tablaPreviewBody.innerHTML = rows.map(r => `
      <tr>
        ${expectedHeaders.map(h => `<td>${escapeHtml(r[h] ?? '')}</td>`).join('')}
      </tr>
    `).join('');
    };

    // Errores de encabezado
    const renderHeaderErrors = (headers) => {
        renderExpectedHeader();
        tablaPreviewBody.innerHTML = '';
        const recibido = headers.length ? headers.join(' | ') : '(sin encabezados)';
        showAlert('danger', 'Encabezados inválidos. Deben coincidir exactamente (mismo nombre y orden).',
            `<div class="mt-2"><div><strong>Esperado:</strong> ${expectedHeaders.join(' | ')}</div><div><strong>Recibido:</strong> ${recibido}</div></div>`);
        resumenCarga.textContent = 'Sin datos cargados';
    };

    // Utilidades UI
    const showAlert = (type, title, detail = '') => {
        validacionEstado.hidden = false;
        validacionAlert.className = `alert alert-${type} mb-2`;
        validacionAlert.innerHTML = `<strong>${title}</strong>`;
        validacionDetalle.innerHTML = Array.isArray(detail) ? detail.join('<br>') : (detail || '');
    };
    const resetState = () => {
        parsedRows = [];
        rowErrors = [];
        headerErrors = [];
        tablaPreviewBody.innerHTML = '';
        resumenCarga.textContent = 'Sin datos cargados';
        validacionEstado.hidden = true;
        validacionAlert.className = 'alert';
        validacionAlert.textContent = '';
        validacionDetalle.textContent = '';
        btnProcesar.disabled = true;
        btnDescargarErrores.disabled = true;
    };
    const escapeHtml = (s) => String(s)
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');

    // Descargar plantilla CSV
    btnDescargarPlantilla.addEventListener('click', () => {
        const headerLine = expectedHeaders.join(',');
        const sample = [
            'P001,101010101,Producto de ejemplo,Unid,10,12500,13,General,,,',
            'P002,101010102,Otro producto,Kg,2,3500,13,Alimentos,,,',
        ].join('\n');
        const csv = headerLine + '\n' + sample + '\n';
        const blob = new Blob([csv], {
            type: 'text/csv;charset=utf-8;'
        });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'Plantilla_Carga_Productos.csv';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    });

    // Descargar errores
    btnDescargarErrores.addEventListener('click', () => {
        if (!rowErrors.length) return;
        const lines = ['Fila,Errores'];
        rowErrors.forEach(e => lines.push(`${e.fila},"${e.errores.join('; ')}"`));
        const blob = new Blob([lines.join('\n')], {
            type: 'text/csv;charset=utf-8;'
        });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'Errores_Carga_Productos.csv';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    });

    // Limpiar
    btnLimpiarCarga.addEventListener('click', () => {
        fileInput.value = '';
        resetState();
    });

    // Procesar (placeholder)
    btnProcesar.addEventListener('click', async () => {
        // Ejemplo de envío (ajusta a tu endpoint):
        // Muestra cómo manejar mensajes del backend cuando venga 400 con "message"
        try {
            // Descomenta y ajusta:
            // const res = await fetch('/api/productos/carga', {
            //   method: 'POST',
            //   headers: { 'Content-Type': 'application/json' },
            //   body: JSON.stringify({ rows: parsedRows })
            // });
            // const data = await res.json().catch(() => ({}));
            // if (!res.ok) {
            //   const msg = data.message || `Error HTTP ${res.status}`;
            //   showAlert('danger', 'No se pudo procesar la carga.', msg);
            //   return;
            // }
            // showAlert('success', 'Carga procesada correctamente.', data.message || '');
            // Opcional: resetState();

            // Placeholder para demo
            showAlert('info', 'Simulación de envío.', `Se enviarían ${parsedRows.length} filas al backend.`);
        } catch (err) {
            showAlert('danger', 'Error de red al procesar.', String(err));
        }
    });
</script>