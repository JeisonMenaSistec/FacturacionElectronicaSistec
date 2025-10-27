<!-- ===================== SECCIÓN: Cargar Clientes ===================== -->
<section class="container-fluid py-3" id="cargarClientesSection">
    <!-- Encabezado -->
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h3 class="mb-0"><i class="ri-upload-2-line me-2"></i> Cargar Clientes</h3>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-secondary" id="btnDescargarPlantillaClientes">
                <i class="ri-file-download-line me-1"></i> Descargar plantilla (CSV)
            </button>
            <button type="button" class="btn btn-outline-secondary" id="btnLimpiarCargaClientes">
                <i class="ri-refresh-line me-1"></i> Limpiar
            </button>
        </div>
    </div>

    <!-- Zona de carga -->
    <div class="card custom-card shadow-sm mb-3">
        <div class="card-body">
            <div id="dropAreaClientes" class="border border-2 border-dashed rounded-3 p-4 text-center position-relative">
                <input type="file" id="fileInputClientes" class="position-absolute top-0 start-0 w-100 h-100 opacity-0" accept=".xlsx,.xls,.csv" />
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
            <div class="mt-3" id="validacionEstadoClientes" hidden>
                <div class="alert" id="validacionAlertClientes" role="alert"></div>
                <div id="validacionDetalleClientes" class="small"></div>
            </div>
        </div>
    </div>

    <!-- Vista previa -->
    <div class="card custom-card shadow-sm">
        <div class="card-body">
            <div class="d-flex align-items-center justify-content-between">
                <h6 class="text-uppercase mb-3"><strong>Vista previa (máx. 100 filas)</strong></h6>
                <div class="small text-muted" id="resumenCargaClientes">Sin datos cargados</div>
            </div>
            <div class="table-responsive">
                <table class="table table-sm table-hover align-middle" id="tablaPreviewClientes">
                    <thead class="table-light">
                        <tr id="tablaPreviewHeadClientes"></tr>
                    </thead>
                    <tbody id="tablaPreviewBodyClientes"></tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-3">
                <button type="button" class="btn btn-outline-secondary" id="btnDescargarErroresClientes" disabled>
                    <i class="ri-bug-line me-1"></i> Descargar errores
                </button>
                <button type="button" class="btn btn-primary" id="btnProcesarClientes" disabled>
                    <i class="ri-check-line me-1"></i> Procesar carga
                </button>
            </div>
        </div>
    </div>
</section>

<!-- ===================== Scripts de la sección ===================== -->
<script>
    // Cabeceras esperadas (orden y nombres exactos)
    const expectedHeadersClientes = [
        "Codigo",
        "Nombre",
        "Apellido1",
        "Apellido2",
        "TipoCedula",
        "Cedula",
        "Correo",
        "Provincia",
        "Canton",
        "Distrito",
        "Barrio",
        "Direccion",
        "Area",
        "Telefono",
        "Copia",
        "Destinatario",
        "ActividadEconomica",
        "NombreComercial"
    ];

    // Estado
    let parsedRowsClientes = [];
    let headerErrorsClientes = [];
    let rowErrorsClientes = [];

    // Elementos
    const dropAreaClientes = document.getElementById('dropAreaClientes');
    const fileInputClientes = document.getElementById('fileInputClientes');
    const tablaPreviewHeadClientes = document.getElementById('tablaPreviewHeadClientes');
    const tablaPreviewBodyClientes = document.getElementById('tablaPreviewBodyClientes');
    const resumenCargaClientes = document.getElementById('resumenCargaClientes');
    const validacionEstadoClientes = document.getElementById('validacionEstadoClientes');
    const validacionAlertClientes = document.getElementById('validacionAlertClientes');
    const validacionDetalleClientes = document.getElementById('validacionDetalleClientes');
    const btnProcesarClientes = document.getElementById('btnProcesarClientes');
    const btnDescargarPlantillaClientes = document.getElementById('btnDescargarPlantillaClientes');
    const btnDescargarErroresClientes = document.getElementById('btnDescargarErroresClientes');
    const btnLimpiarCargaClientes = document.getElementById('btnLimpiarCargaClientes');

    // Precargar cabeceras visibles
    const renderExpectedHeaderClientes = () => {
        tablaPreviewHeadClientes.innerHTML = expectedHeadersClientes.map(h => `<th>${h}</th>`).join('');
    };
    renderExpectedHeaderClientes();

    // Drag & Drop estilos
    ['dragenter', 'dragover'].forEach(evt => {
        dropAreaClientes.addEventListener(evt, e => {
            e.preventDefault();
            e.stopPropagation();
            dropAreaClientes.classList.add('border-primary');
        });
    });
    ['dragleave', 'drop'].forEach(evt => {
        dropAreaClientes.addEventListener(evt, e => {
            e.preventDefault();
            e.stopPropagation();
            dropAreaClientes.classList.remove('border-primary');
        });
    });
    dropAreaClientes.addEventListener('drop', e => {
        const file = e.dataTransfer.files?.[0];
        if (file) handleFileClientes(file);
    });
    fileInputClientes.addEventListener('change', e => {
        const file = e.target.files?.[0];
        if (file) handleFileClientes(file);
    });

    // Manejo de archivo
    const handleFileClientes = (file) => {
        resetStateClientes();
        const ext = (file.name.split('.').pop() || '').toLowerCase();
        if (['xlsx', 'xls'].includes(ext)) {
            if (typeof XLSX === 'undefined') {
                showAlertClientes(
                    'warning',
                    'Necesita incluir la librería XLSX para leer Excel (.xlsx, .xls).',
                    'Sugerencia: <code>https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js</code>'
                );
                return;
            }
            readExcelClientes(file);
        } else if (ext === 'csv') {
            readCsvClientes(file);
        } else {
            showAlertClientes('danger', 'Formato no soportado.', 'Use .xlsx, .xls o .csv.');
        }
    };

    // Excel con SheetJS
    const readExcelClientes = (file) => {
        const reader = new FileReader();
        reader.onload = (e) => {
            try {
                const data = new Uint8Array(e.target.result);
                const wb = XLSX.read(data, {
                    type: 'array'
                });
                const ws = wb.Sheets[wb.SheetNames[0]];
                const matrix = XLSX.utils.sheet_to_json(ws, {
                    header: 1,
                    raw: false,
                    defval: ''
                });
                processMatrixClientes(matrix);
            } catch (err) {
                showAlertClientes('danger', 'No se pudo leer el archivo Excel.', String(err));
            }
        };
        reader.readAsArrayBuffer(file);
    };

    // CSV (coma o tab)
    const readCsvClientes = (file) => {
        const reader = new FileReader();
        reader.onload = (e) => {
            try {
                const text = e.target.result;
                const sep = text.indexOf('\t') > -1 ? '\t' : ',';
                const rows = text.replace(/\r/g, '').split('\n').filter(r => r.trim().length > 0);
                const matrix = rows.map(r => splitCsvRowClientes(r, sep));
                processMatrixClientes(matrix);
            } catch (err) {
                showAlertClientes('danger', 'No se pudo leer el archivo CSV.', String(err));
            }
        };
        reader.readAsText(file, 'utf-8');
    };

    const splitCsvRowClientes = (row, sep) => {
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

    // Procesar matriz
    const processMatrixClientes = (matrix) => {
        if (!matrix || matrix.length === 0) {
            showAlertClientes('danger', 'El archivo está vacío.');
            return;
        }
        const headers = (matrix[0] || []).map(h => (h || '').trim());
        validateHeadersClientes(headers);

        if (headerErrorsClientes.length > 0) {
            renderHeaderErrorsClientes(headers);
            btnProcesarClientes.disabled = true;
            return;
        }

        const rows = matrix.slice(1).filter(r => r.length && r.some(v => String(v).trim() !== ''));
        const limited = rows.slice(0, 100);

        parsedRowsClientes = limited.map((r, idx) => {
            const obj = {};
            expectedHeadersClientes.forEach((h, i) => obj[h] = (r[i] ?? '').toString().trim());

            const errs = [];
            if (!obj.Codigo) errs.push('Codigo vacío');
            if (!obj.Nombre) errs.push('Nombre vacío');
            if (!obj.TipoCedula) errs.push('TipoCedula vacío');
            if (!obj.Cedula) errs.push('Cedula vacía');
            if (obj.Correo && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(obj.Correo)) errs.push('Correo inválido');
            if (obj.Telefono && !/^[0-9+\-\s()]{6,}$/.test(obj.Telefono)) errs.push('Teléfono inválido');
            if (errs.length) rowErrorsClientes.push({
                fila: idx + 2,
                errores: errs
            }); // +2 por header y base 1
            return obj;
        });

        renderPreviewClientes(parsedRowsClientes);

        const total = rows.length;
        const shown = parsedRowsClientes.length;
        resumenCargaClientes.textContent = `Mostrando ${shown} de ${total} filas | Errores en ${rowErrorsClientes.length} filas`;
        if (rowErrorsClientes.length > 0) {
            showAlertClientes('warning', 'Archivo válido, pero hay filas con observaciones.', 'Puede descargar el detalle de errores.');
            btnDescargarErroresClientes.disabled = false;
        } else {
            showAlertClientes('success', 'Encabezados y datos válidos.', 'Puede proceder con la carga.');
            btnDescargarErroresClientes.disabled = true;
        }
        btnProcesarClientes.disabled = false;
    };

    // Validar encabezados
    const validateHeadersClientes = (headers) => {
        headerErrorsClientes = [];
        if (headers.length !== expectedHeadersClientes.length) {
            headerErrorsClientes.push(`Se esperaban ${expectedHeadersClientes.length} columnas y llegaron ${headers.length}.`);
        }
        expectedHeadersClientes.forEach((h, i) => {
            if ((headers[i] || '') !== h) {
                headerErrorsClientes.push(`Columna ${i + 1}: esperado "<strong>${h}</strong>" y llegó "<strong>${headers[i] || '(vacío)'}"</strong>.`);
            }
        });
    };

    // Render preview
    const renderPreviewClientes = (rows) => {
        renderExpectedHeaderClientes();
        tablaPreviewBodyClientes.innerHTML = rows.map(r => `
      <tr>
        ${expectedHeadersClientes.map(h => `<td>${escapeHtmlClientes(r[h] ?? '')}</td>`).join('')}
      </tr>
    `).join('');
    };

    // Errores encabezado
    const renderHeaderErrorsClientes = (headers) => {
        renderExpectedHeaderClientes();
        tablaPreviewBodyClientes.innerHTML = '';
        const recibido = headers.length ? headers.join(' | ') : '(sin encabezados)';
        showAlertClientes('danger', 'Encabezados inválidos. Deben coincidir exactamente (mismo nombre y orden).',
            `<div class="mt-2"><div><strong>Esperado:</strong> ${expectedHeadersClientes.join(' | ')}</div><div><strong>Recibido:</strong> ${recibido}</div></div>`);
        resumenCargaClientes.textContent = 'Sin datos cargados';
    };

    // Utilidades UI
    const showAlertClientes = (type, title, detail = '') => {
        validacionEstadoClientes.hidden = false;
        validacionAlertClientes.className = `alert alert-${type} mb-2`;
        validacionAlertClientes.innerHTML = `<strong>${title}</strong>`;
        validacionDetalleClientes.innerHTML = Array.isArray(detail) ? detail.join('<br>') : (detail || '');
    };
    const resetStateClientes = () => {
        parsedRowsClientes = [];
        rowErrorsClientes = [];
        headerErrorsClientes = [];
        tablaPreviewBodyClientes.innerHTML = '';
        resumenCargaClientes.textContent = 'Sin datos cargados';
        validacionEstadoClientes.hidden = true;
        validacionAlertClientes.className = 'alert';
        validacionAlertClientes.textContent = '';
        validacionDetalleClientes.textContent = '';
        btnProcesarClientes.disabled = true;
        btnDescargarErroresClientes.disabled = true;
    };
    const escapeHtmlClientes = (s) => String(s)
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');

    // Descargar plantilla CSV
    btnDescargarPlantillaClientes.addEventListener('click', () => {
        const headerLine = expectedHeadersClientes.join(',');
        const sample = [
            'CLI-001,Juan,Campos,Rojas,01,101230456,juan@example.com,San José,Central,Carmen,Amón,"50m norte del parque",506,88888888,copia@example.com,Finanzas,62010,Mi Comercio',
            'CLI-002,Ana,Mora,León,02,3101234567,contacto@empresa.com,Heredia,Heredia,Mercedes,Varablanca,"De la iglesia 200m sur",506,22223333,,Contabilidad,62020,Empresa XYZ'
        ].join('\n');
        const csv = headerLine + '\n' + sample + '\n';
        const blob = new Blob([csv], {
            type: 'text/csv;charset=utf-8;'
        });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'Plantilla_Carga_Clientes.csv';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    });

    // Descargar errores
    btnDescargarErroresClientes.addEventListener('click', () => {
        if (!rowErrorsClientes.length) return;
        const lines = ['Fila,Errores'];
        rowErrorsClientes.forEach(e => lines.push(`${e.fila},"${e.errores.join('; ')}"`));
        const blob = new Blob([lines.join('\n')], {
            type: 'text/csv;charset=utf-8;'
        });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'Errores_Carga_Clientes.csv';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    });

    // Limpiar
    btnLimpiarCargaClientes.addEventListener('click', () => {
        fileInputClientes.value = '';
        resetStateClientes();
    });

    // Procesar (placeholder con manejo de message en 400)
    btnProcesarClientes.addEventListener('click', async () => {
        try {
            // Ejemplo:
            // const res = await fetch('/api/clientes/carga', {
            //   method: 'POST',
            //   headers: { 'Content-Type': 'application/json' },
            //   body: JSON.stringify({ rows: parsedRowsClientes })
            // });
            // const data = await res.json().catch(() => ({}));
            // if (!res.ok) {
            //   const msg = data.message || `Error HTTP ${res.status}`;
            //   showAlertClientes('danger', 'No se pudo procesar la carga.', msg);
            //   return;
            // }
            // showAlertClientes('success', 'Carga procesada correctamente.', data.message || '');
            // resetStateClientes();

            // Demo local:
            showAlertClientes('info', 'Simulación de envío.', `Se enviarían ${parsedRowsClientes.length} filas al backend.`);
        } catch (err) {
            showAlertClientes('danger', 'Error de red al procesar.', String(err));
        }
    });
</script>