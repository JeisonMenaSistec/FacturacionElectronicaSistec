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
                <h6 class="text-uppercase mb-3"><strong>Vista previa (máx. 10.000 filas)</strong></h6>
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
                        <tr>
                            <td colspan="11" class="text-center text-muted py-4">
                                <i class="ri-file-list-3-line" style="font-size: 32px;"></i>
                                <div class="mt-2">No hay datos para mostrar</div>
                            </td>
                        </tr>
                    </tbody>
                </table>
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
    </div>
</section>

<!-- ===================== Scripts de la sección ===================== -->
<script>
    const apiCargarProductos = 'api/productos/cargar_productos.php';
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

    // Helpers de validación
    const isInt = (v) => /^\d+$/.test(String(v).trim());
    const isDecimalDot = (v) => /^\d+(\.\d+)?$/.test(String(v).trim());

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
        e.target.value = '';
    });

    // Manejo de archivo
    const handleFile = (file) => {
        resetState();
        const ext = (file.name.split('.').pop() || '').toLowerCase();
        if (['xlsx', 'xls'].includes(ext)) {
            if (typeof XLSX === 'undefined') {
                showAlert('warning', 'Librería SheetJS no cargada. Contacta con soporte técnico.');
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
        swalLoading('Procesando Excel. Espera por favor...');
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
                Swal.close();
            } catch (err) {
                Swal.close();
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
        const limited = rows.slice(0, 10000); // soporta hasta 10.000 filas
        parsedRows = limited.map((r, idx) => {
            const obj = {};
            expectedHeaders.forEach((h, i) => obj[h] = (r[i] ?? '').toString().trim());

            // Validaciones por fila
            const errs = [];
            if (!obj.CodigoCabys) errs.push('CodigoCabys vacio');
            if (!obj.Detalle) errs.push('Detalle vacio');

            if (obj.Unidad !== '' && !isInt(obj.Unidad)) errs.push('Unidad no entera');
            if (obj.Cantidad !== '' && !isInt(obj.Cantidad)) errs.push('Cantidad no entera');
            if (obj.Precio !== '' && !isDecimalDot(obj.Precio)) errs.push('Precio inválido (usar punto decimal)');
            if (obj.TarifaIVA !== '' && !isInt(obj.TarifaIVA)) errs.push('TarifaIVA no entera');
            if (obj.FormaFarmaceutica !== '' && !isInt(obj.FormaFarmaceutica)) errs.push('FormaFarmaceutica no entera');

            // Opcional que ya tenías
            if (obj.Categoria !== '' && !isInt(obj.Categoria)) errs.push('Categoria no numérica');

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
        renderExpectedHeader();
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
        fileInput.value = '';
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
            'P001,101010101,Producto de ejemplo,1,10,12500,13,1,,2,',
            'P002,101010102,Otro producto,2,2,3500,13,2,,3,',
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

    btnProcesar.addEventListener('click', async () => {
        swalLoading('Procesando carga de productos. Espera por favor.');
        // Prevalidación estricta antes de enviar
        const faltantesCabys = [];
        const faltantesDetalle = [];
        const unidadNoEntera = [];
        const cantidadNoEntera = [];
        const precioInvalido = [];
        const tarifaNoEntera = [];
        const formaNoEntera = [];
        const categoriaNoEntera = [];

        parsedRows.forEach((r, i) => {
            const fila = i + 2; // +2 por encabezado y base 1
            if (!r.CodigoCabys || String(r.CodigoCabys).trim() === '') {
                faltantesCabys.push(`Fila ${fila} (Codigo: ${r.Codigo || 's/d'})`);
            }
            if (!r.Detalle || String(r.Detalle).trim() === '') {
                faltantesDetalle.push(`Fila ${fila} (Codigo: ${r.Codigo || 's/d'})`);
            }
            if (String(r.Unidad).trim() !== '' && !isInt(r.Unidad)) {
                unidadNoEntera.push(`Fila ${fila} (Valor: "${r.Unidad}")`);
            }
            if (String(r.Cantidad).trim() !== '' && !isInt(r.Cantidad)) {
                cantidadNoEntera.push(`Fila ${fila} (Valor: "${r.Cantidad}")`);
            }
            if (String(r.Precio).trim() !== '' && !isDecimalDot(r.Precio)) {
                precioInvalido.push(`Fila ${fila} (Valor: "${r.Precio}")`);
            }
            if (String(r.TarifaIVA).trim() !== '' && !isInt(r.TarifaIVA)) {
                tarifaNoEntera.push(`Fila ${fila} (Valor: "${r.TarifaIVA}")`);
            }
            if (String(r.FormaFarmaceutica).trim() !== '' && !isInt(r.FormaFarmaceutica)) {
                formaNoEntera.push(`Fila ${fila} (Valor: "${r.FormaFarmaceutica}")`);
            }
            if (String(r.Categoria).trim() !== '' && !isInt(r.Categoria)) {
                categoriaNoEntera.push(`Fila ${fila} (Codigo: ${r.Codigo || 's/d'})`);
            }
        });

        if (
            faltantesCabys.length || faltantesDetalle.length ||
            unidadNoEntera.length || cantidadNoEntera.length ||
            precioInvalido.length || tarifaNoEntera.length ||
            formaNoEntera.length || categoriaNoEntera.length
        ) {
            const detalle = [
                faltantesCabys.length ? `<div><strong>CodigoCabys vacio:</strong><br>${faltantesCabys.join('<br>')}</div>` : '',
                faltantesDetalle.length ? `<div class="mt-2"><strong>Detalle vacio:</strong><br>${faltantesDetalle.join('<br>')}</div>` : '',
                unidadNoEntera.length ? `<div class="mt-2"><strong>Unidad no numerica:</strong><br>${unidadNoEntera.join('<br>')}</div>` : '',
                cantidadNoEntera.length ? `<div class="mt-2"><strong>Cantidad no numerica:</strong><br>${cantidadNoEntera.join('<br>')}</div>` : '',
                precioInvalido.length ? `<div class="mt-2"><strong>Precio invalido (use punto decimal):</strong><br>${precioInvalido.join('<br>')}</div>` : '',
                tarifaNoEntera.length ? `<div class="mt-2"><strong>TarifaIVA no numerica:</strong><br>${tarifaNoEntera.join('<br>')}</div>` : '',
                formaNoEntera.length ? `<div class="mt-2"><strong>FormaFarmaceutica no numerica:</strong><br>${formaNoEntera.join('<br>')}</div>` : '',
                categoriaNoEntera.length ? `<div class="mt-2"><strong>Categoria no numerica:</strong><br>${categoriaNoEntera.join('<br>')}</div>` : ''
            ].join('');

            showAlert('danger', 'No se puede procesar la carga.', detalle);
            Swal.close();
            setTimeout(() => {
                swalError('Corrige los campos obligatorios y numéricos en el exel y vuelva a adjuntarlo nuevamente.');
            }, 300);
            return;
        }

        try {
            const res = await fetch(apiCargarProductos, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    rows: parsedRows
                })
            });

            // Intenta leer JSON siempre (éxito o error)
            const data = await res.json().catch(() => ({}));

            // Construye un mensaje claro desde el backend, si existe
            const serverMsg = (data && (data.message || data.msg || data.error)) || '';
            const detalleErrores = (() => {
                // Soporte para formatos de detalle comunes del backend
                const parts = [];

                if (data && data.data && Array.isArray(data.data.errores) && data.data.errores.length) {
                    // Lista de errores por fila (validaciones)
                    const lines = data.data.errores.map(e =>
                        `Fila ${e.fila}${e.codigo ? ` (Codigo: ${e.codigo})` : ''}: ${Array.isArray(e.errores) ? e.errores.join('; ') : e.errores}`
                    );
                    parts.push(`<div class="mt-2"><strong>Detalle de errores:</strong><br>${lines.join('<br>')}</div>`);
                }

                if (data && data.data && data.data.resumen) {
                    const r = data.data.resumen;
                    parts.push(
                        `<div class="mt-2"><strong>Resumen:</strong> Total=${r.total ?? '-'}, Insertados=${r.insertados ?? '-'}, Actualizados=${r.actualizados ?? '-'}, Errores=${r.errores ?? '-'}</div>`
                    );
                }

                if (data && data.data && Array.isArray(data.data.resultados) && data.data.resultados.length) {
                    const first10 = data.data.resultados.slice(0, 10).map(x =>
                        `Fila ${x.fila}${x.codigo ? ` (Codigo: ${x.codigo})` : ''}: ${x.status}`
                    );
                    parts.push(`<div class="mt-2"><strong>Resultados (primeros 10):</strong><br>${first10.join('<br>')}</div>`);
                }

                return parts.join('');
            })();

            if (!res.ok) {
                const msg = serverMsg || `Error HTTP ${res.status}`;
                Swal.close();
                swalError(msg);
                showAlert('danger', 'No se pudo procesar la carga.', [msg, detalleErrores].filter(Boolean).join('<br>'));
                return;
            }

            // Éxito
            const okMsg = serverMsg || 'Carga procesada correctamente.';
            Swal.close();
            swalOk(okMsg);
            showAlert('success', 'Proceso completado.', detalleErrores || okMsg);
            resetState();

        } catch (err) {
            Swal.close();
            swalError('Error de red al procesar.');
            showAlert('danger', 'Error de red al procesar.', String(err));
        }

    });
</script>

<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>