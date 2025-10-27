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
                    <tbody>
                        <!-- Filas dinámicas desde backend -->
                        <!-- Ejemplo estático (remover en producción) -->
                        <tr>
                            <td>CLI-001</td>
                            <td>1-1234-5678</td>
                            <td>Juan Pérez</td>
                            <td>juan.perez@ejemplo.com</td>
                            <td>Física</td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <button class="btn btn-outline-success btnFacturar" data-bs-toggle="tooltip" title="Facturar">
                                        <i class="ri-bill-line"></i>
                                    </button>
                                    <button class="btn btn-outline-secondary btnEditar" data-bs-toggle="tooltip" title="Editar" data-bs-target="#modalEditarCliente" data-bs-toggle="modal">
                                        <i class="ri-edit-2-line"></i>
                                    </button>
                                    <button class="btn btn-outline-danger btnEliminar" data-bs-toggle="tooltip" title="Eliminar">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <!-- /Ejemplo -->
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="d-flex align-items-center justify-content-between mt-3">
                <div class="small text-muted">
                    Mostrando <span id="paginacionDesde">1</span>–<span id="paginacionHasta">10</span> de <span id="paginacionTotal">0</span> clientes
                </div>
                <nav aria-label="Paginación de clientes">
                    <ul class="pagination mb-0" id="paginacionClientes">
                        <li class="page-item disabled"><button class="page-link" id="btnPrevPagina"><i class="ri-arrow-left-s-line"></i></button></li>
                        <li class="page-item active"><button class="page-link">1</button></li>
                        <li class="page-item"><button class="page-link">2</button></li>
                        <li class="page-item"><button class="page-link">3</button></li>
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
                        <div class="col-md-4">
                            <label for="addNumeroIdentificacion" class="form-label">N° de identificación</label>
                            <input type="text" class="form-control" id="addNumeroIdentificacion" required>
                            <div class="invalid-feedback">Ingrese el número de identificación.</div>
                        </div>
                        <div class="col-md-4">
                            <label for="addCodigoInterno" class="form-label">Código interno (opcional)</label>
                            <input type="text" class="form-control" id="addCodigoInterno" placeholder="Ej. CLI-001">
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
                        <div class="col-md-4">
                            <label for="addActividadEconomica" class="form-label">Actividad económica</label>
                            <input type="text" class="form-control" id="addActividadEconomica" placeholder="Ej. 62010">
                        </div>
                        <div class="col-md-2">
                            <label for="addCodigoPais" class="form-label">Código país</label>
                            <input type="text" class="form-control" id="addCodigoPais" placeholder="506" value="506">
                        </div>
                        <div class="col-md-6">
                            <label for="addTelefono" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" id="addTelefono" placeholder="8888-8888">
                        </div>

                        <!-- Ubicación -->
                        <div class="col-md-3">
                            <label for="addProvincia" class="form-label">Provincia</label>
                            <select id="addProvincia" class="form-select">
                                <option value="">-- Seleccione --</option>
                                <option>San José</option>
                                <option>Alajuela</option>
                                <option>Cartago</option>
                                <option>Heredia</option>
                                <option>Guanacaste</option>
                                <option>Puntarenas</option>
                                <option>Limón</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="addCanton" class="form-label">Cantón</label>
                            <input type="text" class="form-control" id="addCanton">
                        </div>
                        <div class="col-md-3">
                            <label for="addDistrito" class="form-label">Distrito</label>
                            <input type="text" class="form-control" id="addDistrito">
                        </div>
                        <div class="col-md-3">
                            <label for="addBarrio" class="form-label">Barrio</label>
                            <input type="text" class="form-control" id="addBarrio">
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

                        <!-- Proveedor FE -->
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
                        <div class="col-md-4">
                            <label for="editNumeroIdentificacion" class="form-label">N° de identificación</label>
                            <input type="text" class="form-control" id="editNumeroIdentificacion" required>
                            <div class="invalid-feedback">Ingrese el número de identificación.</div>
                        </div>
                        <div class="col-md-4">
                            <label for="editCodigoInterno" class="form-label">Código interno (opcional)</label>
                            <input type="text" class="form-control" id="editCodigoInterno">
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
                        <div class="col-md-4">
                            <label for="editActividadEconomica" class="form-label">Actividad económica</label>
                            <input type="text" class="form-control" id="editActividadEconomica">
                        </div>
                        <div class="col-md-2">
                            <label for="editCodigoPais" class="form-label">Código país</label>
                            <input type="text" class="form-control" id="editCodigoPais" placeholder="506">
                        </div>
                        <div class="col-md-6">
                            <label for="editTelefono" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" id="editTelefono">
                        </div>

                        <!-- Ubicación -->
                        <div class="col-md-3">
                            <label for="editProvincia" class="form-label">Provincia</label>
                            <select id="editProvincia" class="form-select">
                                <option value="">-- Seleccione --</option>
                                <option>San José</option>
                                <option>Alajuela</option>
                                <option>Cartago</option>
                                <option>Heredia</option>
                                <option>Guanacaste</option>
                                <option>Puntarenas</option>
                                <option>Limón</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="editCanton" class="form-label">Cantón</label>
                            <input type="text" class="form-control" id="editCanton">
                        </div>
                        <div class="col-md-3">
                            <label for="editDistrito" class="form-label">Distrito</label>
                            <input type="text" class="form-control" id="editDistrito">
                        </div>
                        <div class="col-md-3">
                            <label for="editBarrio" class="form-label">Barrio</label>
                            <input type="text" class="form-control" id="editBarrio">
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

                        <!-- Proveedor FE -->
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
    // Tooltips
    const initTooltips = () => {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.forEach(el => new bootstrap.Tooltip(el));
    };
    document.addEventListener('DOMContentLoaded', initTooltips);

    // Validación Bootstrap
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

    // (Opcional) Placeholders para acciones de UI
    const onBuscarClientes = () => {
        // TODO: implementar fetch al backend con:
        // valor = document.getElementById('inputBuscarCliente').value
        // tipo  = document.getElementById('selTipoBusqueda').value
        // número de página y tamaño desde la paginación
        // Manejar respuestas HTTP 4xx/5xx mostrando message del backend si existe.
    };

    document.getElementById('btnBuscarClientes')?.addEventListener('click', onBuscarClientes);
    document.getElementById('btnLimpiarClientes')?.addEventListener('click', () => {
        document.getElementById('inputBuscarCliente').value = '';
        document.getElementById('selTipoBusqueda').value = '';
    });
</script>