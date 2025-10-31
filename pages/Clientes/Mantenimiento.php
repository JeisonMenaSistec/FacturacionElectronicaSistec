<!-- ===================== SECCIÓN: Mantenimiento de Clientes ===================== -->
<section class="container-fluid py-3">

    <!-- Encabezado y botón Agregar -->
    <div class="d-flex align-items-center justify-content-between mb-2">
        <h3 class="mb-0"><i class="ri-user-3-line me-2"></i> Mantenimiento de Clientes</h3>
        <button class="btn btn-outline-primary btn-wave waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#modalAgregarCliente">
            <i class="ri-add-line me-1"></i> Nuevo cliente
        </button>
    </div>

    <!-- Buscador / Filtros -->
    <div class="card custom-card shadow-sm mb-3">
        <div class="card-body">
            <form class="row g-2 align-items-end" id="formFiltroClientes">
                <div class="col-12 col-md-5">
                    <label for="inputBuscarCliente" class="form-label mb-1">
                        <i class="ri-search-line me-1"></i> Buscar
                    </label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="ri-search-line"></i></span>
                        <input type="text" class="form-control" id="inputBuscarCliente" placeholder="Nombre, cédula, correo, código...">
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <label for="selTipoBusqueda" class="form-label mb-1">
                        <i class="ri-filter-3-line me-1"></i> Tipo de búsqueda
                    </label>
                    <select id="selTipoBusqueda" class="form-select">
                        <option value="">Todos</option>
                        <option value="nombre">Nombre</option>
                        <option value="cedula">Cédula</option>
                        <option value="codigo">Código</option>
                        <option value="correo">Correo</option>
                    </select>
                </div>
                <div class="col-12 col-md-2 d-grid">
                    <label class="form-label mb-1" style="visibility:hidden;">Buscar</label>
                    <button type="button" class="btn btn-primary" id="btnBuscarClientes">
                        <i class="ri-search-line"></i> Buscar
                    </button>
                </div>
                <div class="col-12 col-md-2 d-grid">
                    <label class="form-label mb-1" style="visibility:hidden;">Limpiar</label>
                    <button type="button" class="btn btn-outline-secondary" id="btnLimpiarClientes">
                        <i class="ri-close-line"></i> Limpiar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de clientes -->
    <div class="card custom-card shadow-sm">
        <div class="card-body">
            <h6 class="text-uppercase mb-3"><strong>Clientes registrados</strong></h6>
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="tablaClientes">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Código</th>
                            <th scope="col">Cédula</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Correo</th>
                            <th scope="col">Tipo de cédula</th>
                            <th scope="col" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tbodyClientes">
                        <!-- Filas dinámicas -->
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="d-flex align-items-center justify-content-between mt-3">
                <div class="small text-muted">
                    Mostrando <span id="paginacionDesde">0</span>–<span id="paginacionHasta">0</span> de <span id="paginacionTotal">0</span> clientes
                </div>
                <nav aria-label="Paginación de clientes">
                    <ul class="pagination mb-0" id="paginacionClientes">
                        <li class="page-item disabled"><button class="page-link" id="btnPrevPagina"><i class="ri-arrow-left-s-line"></i></button></li>
                        <li class="page-item"><span class="page-link" id="paginaActual">1</span></li>
                        <li class="page-item"><button class="page-link" id="btnNextPagina"><i class="ri-arrow-right-s-line"></i></button></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</section>
<!-- ===================== /SECCIÓN ===================== -->


<!-- ===================== MODAL: Agregar Cliente ===================== -->
<div class="modal fade" id="modalAgregarCliente" tabindex="-1" aria-labelledby="modalAgregarClienteLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAgregarClienteLabel"><i class="ri-user-add-line me-2"></i>Agregar cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body">
                <form id="formAgregarCliente" novalidate>
                    <div class="row g-3">

                        <!-- Identificación -->
                        <div class="col-md-4">
                            <label for="addTipoIdentificacion" class="form-label">Tipo de identificación</label>
                            <select id="addTipoIdentificacion" class="form-select" required>
                                <option value="">-- Seleccione --</option>
                                <option value="01">Física</option>
                                <option value="02">Jurídica</option>
                                <option value="03">DIMEX</option>
                                <option value="04">NITE</option>
                            </select>
                            <div class="invalid-feedback">Seleccione el tipo de identificación.</div>
                        </div>

                        <div class="col-md-5">
                            <label for="addNumeroIdentificacion" class="form-label">N° de identificación</label>
                            <input type="text" class="form-control" id="addNumeroIdentificacion" required>
                            <div class="invalid-feedback">Ingrese el número de identificación.</div>
                        </div>

                        <div class="col-md-3 d-grid">
                            <label class="form-label" style="visibility:hidden;">Acción</label>
                            <button type="button" class="btn btn-outline-secondary" id="btnConsultarHaciendaAdd">
                                <i class="ri-government-line me-1"></i> Consultar Hacienda
                            </button>
                        </div>

                        <!-- Nombre completo y comercial -->
                        <div class="col-md-4">
                            <label for="addNombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="addNombre" required>
                            <div class="invalid-feedback">Ingrese el nombre.</div>
                        </div>
                        <div class="col-md-4">
                            <label for="addPrimerApellido" class="form-label">Primer apellido</label>
                            <input type="text" class="form-control" id="addPrimerApellido" required>
                            <div class="invalid-feedback">Ingrese el primer apellido.</div>
                        </div>
                        <div class="col-md-4">
                            <label for="addSegundoApellido" class="form-label">Segundo apellido</label>
                            <input type="text" class="form-control" id="addSegundoApellido" required>
                            <div class="invalid-feedback">Ingrese el segundo apellido.</div>
                        </div>

                        <div class="col-md-6">
                            <label for="addNombreComercial" class="form-label">Nombre comercial (opcional)</label>
                            <input type="text" class="form-control" id="addNombreComercial">
                        </div>

                        <!-- Contacto -->
                        <div class="col-md-6">
                            <label for="addCorreo" class="form-label">Correo</label>
                            <input type="email" class="form-control" id="addCorreo" required>
                            <div class="invalid-feedback">Ingrese un correo válido.</div>
                        </div>

                        <!-- Actividad Económica (con buscador por botón) -->
                        <div class="col-md-4">
                            <label for="addActividadEconomica" class="form-label">Actividad económica</label>
                            <input class="form-control" list="dlActividadEcoAdd" id="addActividadEconomica" placeholder="Ej. 62010">
                            <datalist id="dlActividadEcoAdd"></datalist>
                            <div class="form-text">Escriba un código o texto y luego presione “Buscar”.</div>
                        </div>
                        <div class="col-md-2 d-grid">
                            <label class="form-label" style="visibility:hidden;">Acción</label>
                            <button type="button" class="btn btn-outline-primary" id="btnBuscarActividadEcoAdd">
                                <i class="ri-search-line me-1"></i> Buscar
                            </button>
                        </div>

                        <div class="col-md-2">
                            <label for="addCodigoPais" class="form-label">Código país</label>
                            <input type="text" class="form-control" id="addCodigoPais" placeholder="506" value="506">
                        </div>
                        <div class="col-md-4">
                            <label for="addTelefono" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" id="addTelefono" placeholder="8888-8888">
                        </div>

                        <!-- Ubicación (idealmente poblar desde tu API de ubicaciones) -->
                        <div class="col-md-3">
                            <label for="addProvincia" class="form-label">Provincia</label>
                            <select id="addProvincia" class="form-select">
                                <option value="">-- Seleccione --</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="addCanton" class="form-label">Cantón</label>
                            <select id="addCanton" class="form-select">
                                <option value="">-- Seleccione --</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="addDistrito" class="form-label">Distrito</label>
                            <select id="addDistrito" class="form-select">
                                <option value="">-- Seleccione --</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="addBarrio" class="form-label">Barrio</label>
                            <select id="addBarrio" class="form-select">
                                <option value="">-- Seleccione --</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label for="addDireccionDetallada" class="form-label">Dirección detallada</label>
                            <textarea id="addDireccionDetallada" class="form-control" rows="2"></textarea>
                        </div>

                        <!-- Envío / notificaciones -->
                        <div class="col-md-4">
                            <label for="addDestinatario" class="form-label">Destinatario (opcional)</label>
                            <input type="text" class="form-control" id="addDestinatario">
                        </div>
                        <div class="col-md-8">
                            <label for="addCorreoCopiaCortesia" class="form-label">Correo copia de cortesía</label>
                            <input type="email" class="form-control" id="addCorreoCopiaCortesia" placeholder="copia1@ejemplo.com; copia2@ejemplo.com">
                        </div>

                        <!-- Proveedor FE (solo UI, sin lógica por ahora) -->
                        <div class="col-md-6">
                            <label for="addProveedorFactura" class="form-label">Proveedor de factura electrónica</label>
                            <select id="addProveedorFactura" class="form-select">
                                <option value="">-- Seleccione --</option>
                                <option value="interno">Sistema Interno</option>
                                <option value="hacienda">Hacienda Directo</option>
                                <option value="proveedorA">Proveedor A</option>
                                <option value="proveedorB">Proveedor B</option>
                            </select>
                        </div>

                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnGuardarCliente">Guardar</button>
            </div>
        </div>
    </div>
</div>
<!-- ===================== /MODAL Agregar ===================== -->


<!-- ===================== MODAL: Editar Cliente ===================== -->
<div class="modal fade" id="modalEditarCliente" tabindex="-1" aria-labelledby="modalEditarClienteLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarClienteLabel"><i class="ri-edit-2-line me-2"></i>Editar cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body">
                <form id="formEditarCliente" novalidate>
                    <div class="row g-3">

                        <!-- Identificación -->
                        <div class="col-md-4">
                            <label for="editTipoIdentificacion" class="form-label">Tipo de identificación</label>
                            <select id="editTipoIdentificacion" class="form-select" required>
                                <option value="">-- Seleccione --</option>
                                <option value="01">Física</option>
                                <option value="02">Jurídica</option>
                                <option value="03">DIMEX</option>
                                <option value="04">NITE</option>
                            </select>
                            <div class="invalid-feedback">Seleccione el tipo de identificación.</div>
                        </div>

                        <div class="col-md-5">
                            <label for="editNumeroIdentificacion" class="form-label">N° de identificación</label>
                            <input type="text" class="form-control" id="editNumeroIdentificacion" required>
                            <div class="invalid-feedback">Ingrese el número de identificación.</div>
                        </div>

                        <div class="col-md-3 d-grid">
                            <label class="form-label" style="visibility:hidden;">Acción</label>
                            <button type="button" class="btn btn-outline-secondary" id="btnConsultarHaciendaEdit">
                                <i class="ri-government-line me-1"></i> Consultar Hacienda
                            </button>
                        </div>

                        <!-- Nombre completo y comercial -->
                        <div class="col-md-4">
                            <label for="editNombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="editNombre" required>
                            <div class="invalid-feedback">Ingrese el nombre.</div>
                        </div>
                        <div class="col-md-4">
                            <label for="editPrimerApellido" class="form-label">Primer apellido</label>
                            <input type="text" class="form-control" id="editPrimerApellido" required>
                            <div class="invalid-feedback">Ingrese el primer apellido.</div>
                        </div>
                        <div class="col-md-4">
                            <label for="editSegundoApellido" class="form-label">Segundo apellido</label>
                            <input type="text" class="form-control" id="editSegundoApellido" required>
                            <div class="invalid-feedback">Ingrese el segundo apellido.</div>
                        </div>

                        <div class="col-md-6">
                            <label for="editNombreComercial" class="form-label">Nombre comercial (opcional)</label>
                            <input type="text" class="form-control" id="editNombreComercial">
                        </div>

                        <!-- Contacto -->
                        <div class="col-md-6">
                            <label for="editCorreo" class="form-label">Correo</label>
                            <input type="email" class="form-control" id="editCorreo" required>
                            <div class="invalid-feedback">Ingrese un correo válido.</div>
                        </div>

                        <!-- Actividad Económica (con buscador por botón) -->
                        <div class="col-md-4">
                            <label for="editActividadEconomica" class="form-label">Actividad económica</label>
                            <input class="form-control" list="dlActividadEcoEdit" id="editActividadEconomica" placeholder="Ej. 62010">
                            <datalist id="dlActividadEcoEdit"></datalist>
                        </div>
                        <div class="col-md-2 d-grid">
                            <label class="form-label" style="visibility:hidden;">Acción</label>
                            <button type="button" class="btn btn-outline-primary" id="btnBuscarActividadEcoEdit">
                                <i class="ri-search-line me-1"></i> Buscar
                            </button>
                        </div>

                        <div class="col-md-2">
                            <label for="editCodigoPais" class="form-label">Código país</label>
                            <input type="text" class="form-control" id="editCodigoPais" placeholder="506">
                        </div>
                        <div class="col-md-4">
                            <label for="editTelefono" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" id="editTelefono">
                        </div>

                        <!-- Ubicación -->
                        <div class="col-md-3">
                            <label for="editProvincia" class="form-label">Provincia</label>
                            <select id="editProvincia" class="form-select">
                                <option value="">-- Seleccione --</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="editCanton" class="form-label">Cantón</label>
                            <select id="editCanton" class="form-select">
                                <option value="">-- Seleccione --</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="editDistrito" class="form-label">Distrito</label>
                            <select id="editDistrito" class="form-select">
                                <option value="">-- Seleccione --</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="editBarrio" class="form-label">Barrio</label>
                            <select id="editBarrio" class="form-select">
                                <option value="">-- Seleccione --</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label for="editDireccionDetallada" class="form-label">Dirección detallada</label>
                            <textarea id="editDireccionDetallada" class="form-control" rows="2"></textarea>
                        </div>

                        <!-- Envío / notificaciones -->
                        <div class="col-md-4">
                            <label for="editDestinatario" class="form-label">Destinatario (opcional)</label>
                            <input type="text" class="form-control" id="editDestinatario">
                        </div>
                        <div class="col-md-8">
                            <label for="editCorreoCopiaCortesia" class="form-label">Correo copia de cortesía</label>
                            <input type="email" class="form-control" id="editCorreoCopiaCortesia">
                        </div>

                        <!-- Proveedor FE (solo UI) -->
                        <div class="col-md-6">
                            <label for="editProveedorFactura" class="form-label">Proveedor de factura electrónica</label>
                            <select id="editProveedorFactura" class="form-select">
                                <option value="">-- Seleccione --</option>
                                <option value="interno">Sistema Interno</option>
                                <option value="hacienda">Hacienda Directo</option>
                                <option value="proveedorA">Proveedor A</option>
                                <option value="proveedorB">Proveedor B</option>
                            </select>
                        </div>

                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="btnActualizarCliente">Guardar cambios</button>
            </div>
        </div>
    </div>
</div>
<!-- ===================== /MODAL Editar ===================== -->


<!-- ===================== Scripts UI ===================== -->
<script>
    // ====== Helpers de toast mapeados a tus utilidades ======
    const toastOk = (m) => swalOk(m);
    const toastWarn = (m) => notify(m, 'warning');
    const toastErr = (m) => swalError(m);

    // ====== Tooltips ======
    const initTooltips = () => {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.forEach(el => new bootstrap.Tooltip(el));
    };
    document.addEventListener('DOMContentLoaded', initTooltips);

    // ====== Validación Bootstrap ======
    (() => {
        const forms = document.querySelectorAll('form[novalidate]');
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', evt => {
                if (!form.checkValidity()) {
                    evt.preventDefault();
                    evt.stopPropagation();
                }
                form.classList.add('was-validated');
            });
        });
    })();

    // ===================== LÓGICA: Consultar Hacienda por cédula =====================
    async function buscarEnHaciendaPorCedula(cedulaRaw, destino) {
        const cedula = (cedulaRaw || '').trim();
        if (!cedula) {
            toastWarn('Ingrese la cédula para consultar en Hacienda');
            return;
        }
        try {
            swalLoading('Consultando en Hacienda…');
            const url = 'https://api.hacienda.go.cr/fe/ae?identificacion=' + encodeURIComponent(cedula);
            const res = await fetch(url, {
                method: 'GET'
            });
            if (!res.ok) throw new Error('No se pudo contactar a Hacienda');
            const data = await res.json();

            const nombreCompleto = (data && data.nombre) ? String(data.nombre).trim() : '';
            if (!nombreCompleto) {
                toastWarn('Sin resultados en Hacienda');
                return;
            }

            // Heurística simple: primer token = nombre, último = segundo apellido, lo del medio = primer apellido
            const partes = nombreCompleto.split(/\s+/).filter(Boolean);
            let nombre = '',
                apellido1 = '',
                apellido2 = '';
            if (partes.length === 1) {
                nombre = partes[0];
            } else if (partes.length === 2) {
                nombre = partes[0];
                apellido1 = partes[1];
            } else {
                nombre = partes[0];
                apellido2 = partes.pop();
                apellido1 = partes.slice(1).join(' ');
            }

            // Rellenar en el formulario destino (add|edit)
            if (destino === 'add') {
                document.getElementById('addNombre').value = nombre;
                document.getElementById('addPrimerApellido').value = apellido1;
                document.getElementById('addSegundoApellido').value = apellido2;
                if (!document.getElementById('addNombreComercial').value) {
                    document.getElementById('addNombreComercial').value = nombreCompleto;
                }
            } else if (destino === 'edit') {
                document.getElementById('editNombre').value = nombre;
                document.getElementById('editPrimerApellido').value = apellido1;
                document.getElementById('editSegundoApellido').value = apellido2;
                if (!document.getElementById('editNombreComercial').value) {
                    document.getElementById('editNombreComercial').value = nombreCompleto;
                }
            }

            toastOk('Datos cargados desde Hacienda');
        } catch (e) {
            toastErr('Cédula no encontrada en el registro civil.');
        } finally {
            swal.close();
        }
    }

    // Botones de consulta a Hacienda
    document.getElementById('btnConsultarHaciendaAdd')?.addEventListener('click', () => {
        const ced = document.getElementById('addNumeroIdentificacion').value;
        buscarEnHaciendaPorCedula(ced, 'add');
    });
    document.getElementById('btnConsultarHaciendaEdit')?.addEventListener('click', () => {
        const ced = document.getElementById('editNumeroIdentificacion').value;
        buscarEnHaciendaPorCedula(ced, 'edit');
    });

    // ===================== LÓGICA: Actividad económica (por botón) =====================
    const apiHacienda = 'api/hacienda/index.php';

    async function buscarActividadEconomica(inputId, datalistId) {
        const q = (document.getElementById(inputId).value || '').trim();
        if (!q) {
            toastWarn('Ingrese un texto o código para buscar la actividad económica');
            return;
        }
        try {
            swalLoading('Buscando actividades…');
            const res = await fetch(apiHacienda, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    accion: 'actividadesEconomicas',
                    q
                })
            });
            const data = await res.json().catch(() => null);
            if (!res.ok) {
                const msg = (data && data.message) ? data.message : `Error HTTP ${res.status}`;
                swalError(msg);
                return;
            }
            const lista = Array.isArray(data) ? data : [];
            const dl = document.getElementById(datalistId);
            dl.innerHTML = '';
            if (!lista.length) {
                toastWarn('Sin resultados de actividad económica');
                return;
            }
            // Cada opción guarda "codigo - descripcion"
            lista.forEach(it => {
                const opt = document.createElement('option');
                opt.value = it.codigo;
                opt.label = `${it.codigo} - ${it.descripcion}`;
                dl.appendChild(opt);
            });
            toastOk('Resultados cargados. Elija una opción en el campo.');
        } catch (err) {
            swalError(String(err));
        } finally {
            swal.close();
        }
    }

    document.getElementById('btnBuscarActividadEcoAdd')?.addEventListener('click', () => {
        buscarActividadEconomica('addActividadEconomica', 'dlActividadEcoAdd');
    });
    document.getElementById('btnBuscarActividadEcoEdit')?.addEventListener('click', () => {
        buscarActividadEconomica('editActividadEconomica', 'dlActividadEcoEdit');
    });

    // ===================== LÓGICA: Cargar ubicaciones (por botón o al abrir modal) =====================
    async function cargarProvincias(selectId) {
        try {
            const res = await fetch(apiHacienda, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    accion: 'provincias'
                })
            });
            const data = await res.json().catch(() => []);
            const sel = document.getElementById(selectId);
            sel.innerHTML = '<option value="">-- Seleccione --</option>';
            (Array.isArray(data) ? data : []).forEach(p => {
                const opt = document.createElement('option');
                opt.value = p.provinciaCod;
                opt.textContent = p.provinciaNombre;
                sel.appendChild(opt);
            });
        } catch {}
    }
    async function cargarCantones(provCod, selectId) {
        const sel = document.getElementById(selectId);
        sel.innerHTML = '<option value="">-- Seleccione --</option>';
        if (!provCod) return;
        try {
            const res = await fetch(apiHacienda, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    accion: 'cantones',
                    provinciaCod: String(provCod)
                })
            });
            const data = await res.json().catch(() => []);
            (Array.isArray(data) ? data : []).forEach(c => {
                const opt = document.createElement('option');
                opt.value = c.cantonCod;
                opt.textContent = c.cantonNombre;
                sel.appendChild(opt);
            });
        } catch {}
    }
    async function cargarDistritos(provCod, cantonCod, selectId) {
        const sel = document.getElementById(selectId);
        sel.innerHTML = '<option value="">-- Seleccione --</option>';
        if (!provCod || !cantonCod) return;
        try {
            const res = await fetch(apiHacienda, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    accion: 'distritos',
                    provinciaCod: String(provCod),
                    cantonCod: String(cantonCod)
                })
            });
            const data = await res.json().catch(() => []);
            (Array.isArray(data) ? data : []).forEach(d => {
                const opt = document.createElement('option');
                opt.value = d.distritoCod;
                opt.textContent = d.distritoNombre;
                sel.appendChild(opt);
            });
        } catch {}
    }
    async function cargarBarrios(provCod, cantonCod, distritoCod, selectId) {
        const sel = document.getElementById(selectId);
        sel.innerHTML = '<option value="">-- Seleccione --</option>';
        if (!provCod || !cantonCod || !distritoCod) return;
        try {
            const res = await fetch(apiHacienda, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    accion: 'barrios',
                    provinciaCod: String(provCod),
                    cantonCod: String(cantonCod),
                    distritoCod: String(distritoCod)
                })
            });
            const data = await res.json().catch(() => []);
            (Array.isArray(data) ? data : []).forEach(b => {
                const opt = document.createElement('option');
                opt.value = b.barrioCod;
                opt.textContent = b.barrioNombre;
                sel.appendChild(opt);
            });
        } catch {}
    }

    // Encadenar selects (Agregar)
    document.getElementById('addProvincia')?.addEventListener('change', async (e) => {
        const prov = e.target.value;
        await cargarCantones(prov, 'addCanton');
        document.getElementById('addDistrito').innerHTML = '<option value="">-- Seleccione --</option>';
        document.getElementById('addBarrio').innerHTML = '<option value="">-- Seleccione --</option>';
    });
    document.getElementById('addCanton')?.addEventListener('change', async (e) => {
        const prov = document.getElementById('addProvincia').value;
        const canton = e.target.value;
        await cargarDistritos(prov, canton, 'addDistrito');
        document.getElementById('addBarrio').innerHTML = '<option value="">-- Seleccione --</option>';
    });
    document.getElementById('addDistrito')?.addEventListener('change', async (e) => {
        const prov = document.getElementById('addProvincia').value;
        const canton = document.getElementById('addCanton').value;
        const distrito = e.target.value;
        await cargarBarrios(prov, canton, distrito, 'addBarrio');
    });

    // Encadenar selects (Editar)
    document.getElementById('editProvincia')?.addEventListener('change', async (e) => {
        const prov = e.target.value;
        await cargarCantones(prov, 'editCanton');
        document.getElementById('editDistrito').innerHTML = '<option value="">-- Seleccione --</option>';
        document.getElementById('editBarrio').innerHTML = '<option value="">-- Seleccione --</option>';
    });
    document.getElementById('editCanton')?.addEventListener('change', async (e) => {
        const prov = document.getElementById('editProvincia').value;
        const canton = e.target.value;
        await cargarDistritos(prov, canton, 'editDistrito');
        document.getElementById('editBarrio').innerHTML = '<option value="">-- Seleccione --</option>';
    });
    document.getElementById('editDistrito')?.addEventListener('change', async (e) => {
        const prov = document.getElementById('editProvincia').value;
        const canton = document.getElementById('editCanton').value;
        const distrito = e.target.value;
        await cargarBarrios(prov, canton, distrito, 'editBarrio');
    });

    // ====== Listado básico (placeholder: conecta con tu mantenimiento.php si quieres) ======
    const apiClientes = 'api/clientes/mantenimiento.php';
    let pagina = 1,
        tamPagina = 10,
        total = 0;

    async function listarClientes() {
        try {
            swalLoading('Cargando clientes…');
            const res = await fetch(apiClientes, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    accion: 'listar',
                    valor: (document.getElementById('inputBuscarCliente').value || '').trim(),
                    tipo: (document.getElementById('selTipoBusqueda').value || '').trim(),
                    pagina,
                    tamPagina
                })
            });
            const data = await res.json().catch(() => null);
            if (!res.ok) {
                const msg = data?.message || `Error HTTP ${res.status}`;
                swalError(msg);
                return;
            }
            const rows = data?.data?.rows || [];
            total = data?.data?.total || 0;

            const tbody = document.getElementById('tbodyClientes');
            tbody.innerHTML = rows.map(r => `
        <tr>
          <td>${r.codigoInterno ?? '-'}</td>
          <td>${r.identificacionFormatted ?? r.identificacion ?? '-'}</td>
          <td>${r.nombreCompleto ?? '-'}</td>
          <td>${r.correo ?? '-'}</td>
          <td>${r.tipoCedulaDesc ?? r.tipoCedula ?? '-'}</td>
          <td class="text-center">
            <div class="btn-group btn-group-sm" role="group">
              <button class="btn btn-outline-success btnFacturar" title="Facturar"><i class="ri-bill-line"></i></button>
              <button class="btn btn-outline-secondary btnEditar" title="Editar"><i class="ri-edit-2-line"></i></button>
              <button class="btn btn-outline-danger btnEliminar" title="Eliminar"><i class="ri-delete-bin-line"></i></button>
            </div>
          </td>
        </tr>
      `).join('');

            const desde = rows.length ? ((pagina - 1) * tamPagina + 1) : 0;
            const hasta = rows.length ? ((pagina - 1) * tamPagina + rows.length) : 0;
            document.getElementById('paginacionDesde').textContent = desde;
            document.getElementById('paginacionHasta').textContent = hasta;
            document.getElementById('paginacionTotal').textContent = total;
            document.getElementById('paginaActual').textContent = String(pagina);

            const maxPage = Math.max(1, Math.ceil(total / tamPagina));
            document.getElementById('btnPrevPagina').parentElement.classList.toggle('disabled', pagina <= 1);
            document.getElementById('btnNextPagina').parentElement.classList.toggle('disabled', pagina >= maxPage);

        } catch (err) {
            swalError(String(err));
        } finally {
            swal.close();
        }
    }

    // Paginación
    document.getElementById('btnPrevPagina')?.addEventListener('click', () => {
        if (pagina > 1) {
            pagina--;
            listarClientes();
        }
    });
    document.getElementById('btnNextPagina')?.addEventListener('click', () => {
        const maxPage = Math.max(1, Math.ceil(total / tamPagina));
        if (pagina < maxPage) {
            pagina++;
            listarClientes();
        }
    });

    // Buscar con botón (no onchange)
    document.getElementById('btnBuscarClientes')?.addEventListener('click', () => {
        pagina = 1;
        listarClientes();
    });
    document.getElementById('btnLimpiarClientes')?.addEventListener('click', () => {
        document.getElementById('inputBuscarCliente').value = '';
        document.getElementById('selTipoBusqueda').value = '';
        pagina = 1;
        listarClientes();
    });

    // Arranque: cargar ubicaciones y listar
    (async () => {
        await cargarProvincias('addProvincia');
        await cargarProvincias('editProvincia');
        listarClientes();
    })();
</script>