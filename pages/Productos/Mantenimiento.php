<!-- Botón nuevo producto -->
<div class="text-end mb-2">
    <button class="btn btn-outline-primary btn-wave waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#modalAgregarProducto" id="btnNuevoProducto">
        <i class="ri-add-line me-1"></i> Nuevo producto
    </button>
</div>

<!-- Buscador / Filtros -->
<div class="card custom-card shadow-sm mb-3">
    <div class="card-body">
        <form class="row g-2 align-items-end" onsubmit="return false;">
            <div class="col-12 col-md-3">
                <label for="selBuscarEn" class="form-label mb-1">
                    <i class="ri-filter-2-line me-1"></i> Buscar en
                </label>
                <select id="selBuscarEn" class="form-select">
                    <option value="">Seleccione...</option>
                    <option value="nombre">Nombre</option>
                    <option value="cod_producto">Código</option>
                    <option value="cod_cabys">CABYS</option>
                </select>
            </div>
            <div class="col-12 col-md-3">
                <label for="inputBuscar" class="form-label mb-1">
                    <i class="ri-search-line me-1"></i> Texto a buscar
                </label>
                <div class="input-group">

                    <input type="text" class="form-control" id="inputBuscar" placeholder="Escriba el texto...">
                    <button class="btn btn-primary" type="button" id="btnBuscarProductos">
                        <i class="ri-search-line"></i>
                    </button>
                </div>
            </div>
            <div class="col-12 col-md-3">
                <label for="selCategoriaFiltro" class="form-label mb-1">
                    <i class="ri-price-tag-3-line me-1"></i> Categoría
                </label>
                <select id="selCategoriaFiltro" class="form-select">
                    <option value="">Todas</option>
                    <!-- dinámico -->
                </select>
            </div>
            <div class="col-6 col-md-2">
                <label for="selEstadoFiltro" class="form-label mb-1">
                    <i class="ri-toggle-line me-1"></i> Estado
                </label>
                <select id="selEstadoFiltro" class="form-select">
                    <option value="">Todos</option>
                    <option value="1">Activo</option>
                    <option value="2">Inactivo</option>
                </select>
            </div>

            <!-- Tamaño de página -->
            <div class="col-6 col-md-1">
                <label for="selTamPagina" class="form-label mb-1">
                    <i class="ri-list-unordered me-1"></i> Filas
                </label>
                <select id="selTamPagina" class="form-select">
                    <option>10</option>
                    <option selected>25</option>
                    <option>50</option>
                    <option>100</option>
                    <option>250</option>
                    <option>500</option>
                    <option>1000</option>
                    <option>3000</option>
                </select>
            </div>

            <!-- <div class="col-12 col-md-1 d-grid">
                <label class="form-label mb-1" style="visibility:hidden;">Buscar</label>
                <button type="button" class="btn btn-primary" id="btnBuscarProductos">
                    <i class="ri-search-line"></i>
                </button>
            </div> -->
        </form>
    </div>
</div>

<!-- Tabla de productos -->
<div class="card custom-card shadow-sm">
    <div class="card-body">
        <h6 class="text-uppercase mb-3"><strong>Productos registrados</strong></h6>
        <div class="table-responsive">
            <table class="table table-hover align-middle" id="tablaProductos">
                <thead class="table-light">
                    <tr>
                        <th scope="col"><i class="ri-barcode-line me-1"></i>Código</th>
                        <th scope="col"><i class="ri-product-hunt-line me-1"></i>Nombre</th>
                        <th scope="col"><i class="ri-ruler-line me-1"></i>U. Medida</th>
                        <th scope="col"><i class="ri-price-tag-3-line me-1"></i>Categoría</th>
                        <th scope="col" class="text-end"><i class="ri-stack-line me-1"></i>Cantidad</th>
                        <th scope="col" class="text-end"><i class="ri-money-dollar-circle-line me-1"></i>Precio</th>
                        <th scope="col"><i class="ri-qr-code-line me-1"></i>CABYS</th>
                        <th scope="col"><i class="ri-toggle-line me-1"></i>Estado</th>
                        <th scope="col" class="text-center" style="width:200px;"><i class="ri-settings-3-line me-1"></i>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr id="filaVaciaProductos" class="d-none">
                        <td colspan="9" class="text-center text-muted"><i class="ri-information-line me-1"></i> Sin productos</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Footer de tabla: rango + paginación -->
        <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-2 mt-2">
            <div class="text-muted small" id="lblRango">Mostrando 0–0 de 0</div>
            <nav aria-label="Paginación productos">
                <ul class="pagination pagination-sm mb-0 flex-wrap" id="paginacionProductos">
                    <!-- botones dinámicos -->
                </ul>
            </nav>
        </div>
    </div>
</div>

<!-- ===================== MODAL: Agregar Producto ===================== -->
<div class="modal fade" id="modalAgregarProducto" tabindex="-1" aria-labelledby="modalAgregarProductoLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAgregarProductoLabel"><i class="ri-add-line me-2"></i>Agregar producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body">
                <form id="formAgregarProducto" novalidate>
                    <div class="row g-3">

                        <!-- CABYS -->
                        <div class="col-md-6">
                            <label for="addCabys" class="form-label">Código CABYS</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="addCabys" name="cabys" placeholder="Ej. 101010101" autocomplete="off" required>
                                <!-- Abrir CABYS sobrepuesto -->
                                <button class="btn btn-outline-secondary btnOpenCabys" type="button">
                                    <i class="ri-search-line"></i>
                                </button>
                            </div>
                            <div class="invalid-feedback">Ingrese el código CABYS.</div>
                        </div>

                        <!-- Código interno -->
                        <div class="col-md-6">
                            <label for="addCodigo" class="form-label">Código del producto</label>
                            <input type="text" class="form-control" id="addCodigo" name="codigo">
                        </div>

                        <!-- Nombre -->
                        <div class="col-md-8">
                            <label for="addNombre" class="form-label">Nombre del producto</label>
                            <input type="text" class="form-control" id="addNombre" name="nombre" required>
                            <div class="invalid-feedback">Ingrese el nombre del producto.</div>
                        </div>

                        <!-- Categoría -->
                        <div class="col-md-4">
                            <label for="addCategoria" class="form-label">Categoría</label>
                            <select class="form-select" id="addCategoria" name="idCategoria">
                                <!-- dinámico -->
                            </select>
                        </div>

                        <!-- Switch Medicamento -->
                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="addEsMedicamento">
                                <label class="form-check-label" for="addEsMedicamento">¿Este es un medicamento con registro sanitario?</label>
                            </div>
                            <div class="form-text">Si se habilita, se mostrarán los campos requeridos por Hacienda (v4.4).</div>
                        </div>

                        <!-- Campos de medicamento (Hacienda v4.4) -->
                        <div class="col-12 d-none" id="addCamposMedicamento">
                            <div class="row g-3 border rounded p-3">
                                <div class="col-md-4">
                                    <label for="addRegSanitario" class="form-label">Registro sanitario</label>
                                    <input type="text" class="form-control" id="addRegSanitario" name="registroSanitario">
                                </div>
                                <div class="col-md-4">
                                    <label for="addRegVence" class="form-label">Vencimiento del registro</label>
                                    <input type="date" class="form-control" id="addRegVence" name="registroVence">
                                </div>
                                <div class="col-md-4">
                                    <label for="addTipoMedicamento" class="form-label">Tipo de medicamento</label>
                                    <select class="form-select" id="addTipoMedicamento" name="tipoMedicamento">
                                        <!-- dinámico -->
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="addPrincipioActivo" class="form-label">Principio activo</label>
                                    <input type="text" class="form-control" id="addPrincipioActivo" name="principioActivo">
                                </div>
                                <div class="col-md-4">
                                    <label for="addConcentracion" class="form-label">Concentración</label>
                                    <input type="text" class="form-control" id="addConcentracion" name="concentracion" placeholder="Ej. 500 mg">
                                </div>
                                <div class="col-md-4">
                                    <label for="addFormaFarmaceutica" class="form-label">Forma farmacéutica</label>
                                    <input type="text" class="form-control" id="addFormaFarmaceutica" name="formaFarmaceutica" placeholder="Tableta, jarabe, etc.">
                                </div>
                            </div>
                        </div>

                        <!-- Unidad, Cantidad, Precio, IVA -->
                        <div class="col-md-3">
                            <label for="addUmedida" class="form-label">Unidad de medida</label>
                            <select class="form-select" id="addUmedida" name="idUnidadMedida">
                                <!-- dinámico -->
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="addCantidad" class="form-label">Cantidad</label>
                            <input type="number" class="form-control" id="addCantidad" name="cantidad" min="0" step="0.01">
                        </div>

                        <div class="col-md-3">
                            <label for="addPrecio" class="form-label">Precio unitario</label>
                            <div class="input-group">
                                <span class="input-group-text">₡</span>
                                <input type="number" class="form-control" id="addPrecio" name="precioUnitario" min="0" step="0.01">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <label for="addIva" class="form-label">Tarifa de IVA</label>
                            <select class="form-select" id="addIva" name="idImpGeneral">
                                <!-- dinámico -->
                            </select>
                        </div>

                        <!-- Descripción -->
                        <div class="col-12">
                            <label for="addDescripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="addDescripcion" name="descripcion" rows="3" placeholder="Detalle o características del producto"></textarea>
                        </div>

                        <!-- Mostrar más opciones -->
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="addMostrarMas">
                                <label class="form-check-label" for="addMostrarMas">Mostrar más opciones</label>
                            </div>
                        </div>

                        <!-- Opciones avanzadas -->
                        <div class="col-12 d-none" id="addMasOpciones">
                            <div class="row g-3 border rounded p-3">
                                <div class="col-md-4">
                                    <label for="addBarras" class="form-label">Código de barras</label>
                                    <input type="text" class="form-control" id="addBarras" name="codigoBarras">
                                </div>
                                <div class="col-md-4">
                                    <label for="addMarca" class="form-label">Marca</label>
                                    <input type="text" class="form-control" id="addMarca" name="marca">
                                </div>
                                <div class="col-md-4">
                                    <label for="addModelo" class="form-label">Modelo</label>
                                    <input type="text" class="form-control" id="addModelo" name="modelo">
                                </div>
                                <div class="col-md-6">
                                    <label for="addNota" class="form-label">Notas</label>
                                    <input type="text" class="form-control" id="addNota" name="notas">
                                </div>
                                <div class="col-md-6">
                                    <label for="addSku" class="form-label">SKU</label>
                                    <input type="text" class="form-control" id="addSku" name="sku">
                                </div>
                            </div>
                        </div>

                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnGuardarProducto">Guardar</button>
            </div>
        </div>
    </div>
</div>
<!-- ===================== /MODAL Agregar ===================== -->

<!-- ===================== MODAL: Editar Producto ===================== -->
<div class="modal fade" id="modalEditarProducto" tabindex="-1" aria-labelledby="modalEditarProductoLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarProductoLabel"><i class="ri-edit-2-line me-2"></i>Editar producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body">
                <form id="formEditarProducto" novalidate>
                    <input type="hidden" id="editId">
                    <div class="row g-3">

                        <!-- CABYS -->
                        <div class="col-md-6">
                            <label for="editCabys" class="form-label">Código CABYS</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="editCabys" name="cabys" placeholder="Ej. 101010101" autocomplete="off" required>
                                <!-- Abrir CABYS sobrepuesto -->
                                <button class="btn btn-outline-secondary btnOpenCabys" type="button">
                                    <i class="ri-search-line"></i>
                                </button>
                            </div>
                            <div class="invalid-feedback">Ingrese el código CABYS.</div>
                        </div>

                        <!-- Código interno -->
                        <div class="col-md-6">
                            <label for="editCodigo" class="form-label">Código del producto</label>
                            <input type="text" class="form-control" id="editCodigo" name="codigo">
                        </div>

                        <!-- Nombre -->
                        <div class="col-md-8">
                            <label for="editNombre" class="form-label">Nombre del producto</label>
                            <input type="text" class="form-control" id="editNombre" name="nombre" required>
                            <div class="invalid-feedback">Ingrese el nombre del producto.</div>
                        </div>

                        <!-- Categoría -->
                        <div class="col-md-4">
                            <label for="editCategoria" class="form-label">Categoría</label>
                            <select class="form-select" id="editCategoria" name="idCategoria">
                                <!-- dinámico -->
                            </select>
                        </div>

                        <!-- Switch Medicamento -->
                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="editEsMedicamento">
                                <label class="form-check-label" for="editEsMedicamento">¿Este es un medicamento con registro sanitario?</label>
                            </div>
                        </div>

                        <!-- Campos de medicamento -->
                        <div class="col-12 d-none" id="editCamposMedicamento">
                            <div class="row g-3 border rounded p-3">
                                <div class="col-md-4">
                                    <label for="editRegSanitario" class="form-label">Registro sanitario</label>
                                    <input type="text" class="form-control" id="editRegSanitario" name="registroSanitario">
                                </div>
                                <div class="col-md-4">
                                    <label for="editRegVence" class="form-label">Vencimiento del registro</label>
                                    <input type="date" class="form-control" id="editRegVence" name="registroVence">
                                </div>
                                <div class="col-md-4">
                                    <label for="editTipoMedicamento" class="form-label">Tipo de medicamento</label>
                                    <select class="form-select" id="editTipoMedicamento" name="tipoMedicamento">
                                        <!-- dinámico -->
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="editPrincipioActivo" class="form-label">Principio activo</label>
                                    <input type="text" class="form-control" id="editPrincipioActivo" name="principioActivo">
                                </div>
                                <div class="col-md-4">
                                    <label for="editConcentracion" class="form-label">Concentración</label>
                                    <input type="text" class="form-control" id="editConcentracion" name="concentracion" placeholder="Ej. 500 mg">
                                </div>
                                <div class="col-md-4">
                                    <label for="editFormaFarmaceutica" class="form-label">Forma farmacéutica</label>
                                    <input type="text" class="form-control" id="editFormaFarmaceutica" name="formaFarmaceutica" placeholder="Tableta, jarabe, etc.">
                                </div>
                            </div>
                        </div>

                        <!-- Unidad, Cantidad, Precio, IVA -->
                        <div class="col-md-3">
                            <label for="editUmedida" class="form-label">Unidad de medida</label>
                            <select class="form-select" id="editUmedida" name="idUnidadMedida">
                                <!-- dinámico -->
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="editCantidad" class="form-label">Cantidad</label>
                            <input type="number" class="form-control" id="editCantidad" name="cantidad" min="0" step="0.01">
                        </div>

                        <div class="col-md-3">
                            <label for="editPrecio" class="form-label">Precio unitario</label>
                            <div class="input-group">
                                <span class="input-group-text">₡</span>
                                <input type="number" class="form-control" id="editPrecio" name="precioUnitario" min="0" step="0.01">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <label for="editIva" class="form-label">Tarifa de IVA</label>
                            <select class="form-select" id="editIva" name="idImpGeneral">
                                <!-- dinámico -->
                            </select>
                        </div>

                        <!-- Descripción -->
                        <div class="col-12">
                            <label for="editDescripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="editDescripcion" name="descripcion" rows="3"></textarea>
                        </div>

                        <!-- Mostrar más opciones -->
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="editMostrarMas">
                                <label class="form-check-label" for="editMostrarMas">Mostrar más opciones</label>
                            </div>
                        </div>

                        <!-- Opciones avanzadas -->
                        <div class="col-12 d-none" id="editMasOpciones">
                            <div class="row g-3 border rounded p-3">
                                <div class="col-md-4">
                                    <label for="editBarras" class="form-label">Código de barras</label>
                                    <input type="text" class="form-control" id="editBarras" name="codigoBarras">
                                </div>
                                <div class="col-md-4">
                                    <label for="editMarca" class="form-label">Marca</label>
                                    <input type="text" class="form-control" id="editMarca" name="marca">
                                </div>
                                <div class="col-md-4">
                                    <label for="editModelo" class="form-label">Modelo</label>
                                    <input type="text" class="form-control" id="editModelo" name="modelo">
                                </div>
                                <div class="col-md-6">
                                    <label for="editNota" class="form-label">Notas</label>
                                    <input type="text" class="form-control" id="editNota" name="notas">
                                </div>
                                <div class="col-md-6">
                                    <label for="editSku" class="form-label">SKU</label>
                                    <input type="text" class="form-control" id="editSku" name="sku">
                                </div>
                            </div>
                        </div>

                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="btnActualizarProducto">Actualizar</button>
            </div>
        </div>
    </div>
</div>
<!-- ===================== /MODAL Editar ===================== -->

<!-- ===================== MODAL: Buscar CABYS (UI) ===================== -->
<div class="modal fade" id="modalBuscarCabys" tabindex="-1" aria-labelledby="modalBuscarCabysLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalBuscarCabysLabel"><i class="ri-search-line me-2"></i>Buscar código CABYS</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="ri-search-line"></i></span>
                    <input type="text" class="form-control" id="cabysQuery" placeholder="Descripción o código CABYS...">
                    <button class="btn btn-primary" type="button" id="btnBuscarCabys">
                        <i class="ri-search-line"></i> Buscar
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Código</th>
                                <th>Descripción</th>
                                <th class="text-center">Seleccionar</th>
                            </tr>
                        </thead>
                        <tbody id="cabysResultados">
                            <!-- Resultados dinámicos -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <span class="text-muted small me-auto">Seleccione un registro para llenar el campo CABYS.</span>
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!-- ===================== /MODAL Buscar CABYS ===================== -->

<!-- ============== Estilos para 2do modal sobrepuesto ============== -->
<style>
    .modal.modal-top {
        z-index: 1065;
    }

    .modal-backdrop.modal-backdrop-top {
        z-index: 1060;
    }
</style>

<!-- ===================== Scripts de UI y lógica ===================== -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const apiProductos = 'api/productos/mantenimiento.php';
        const apiHacienda = 'api/hacienda/index.php';

        // Tooltips
        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));

        // Filtros
        const selBuscarEn = document.getElementById('selBuscarEn');
        const inputBuscar = document.getElementById('inputBuscar');
        const selCategoriaFiltro = document.getElementById('selCategoriaFiltro');
        const selEstadoFiltro = document.getElementById('selEstadoFiltro');
        const selTamPagina = document.getElementById('selTamPagina');
        const btnBuscarProductos = document.getElementById('btnBuscarProductos');

        // Tabla
        const tabla = document.getElementById('tablaProductos');
        const tbody = tabla.querySelector('tbody');
        const filaVacia = document.getElementById('filaVaciaProductos');

        // Paginación
        const pag = document.getElementById('paginacionProductos');
        const lblRango = document.getElementById('lblRango');
        let currentPage = 1;
        let pageSize = parseInt(selTamPagina.value, 10) || 25;
        const maxPageSize = 3000;
        let totalItems = 0;
        let totalPages = 1;

        // Modales principales
        const modalAgregar = new bootstrap.Modal(document.getElementById('modalAgregarProducto'));
        const modalEditar = new bootstrap.Modal(document.getElementById('modalEditarProducto'));

        // Modal CABYS (sobrepuesto)
        const cabysEl = document.getElementById('modalBuscarCabys');
        const modalCabys = new bootstrap.Modal(cabysEl, {
            backdrop: 'static',
            keyboard: false
        });

        // Ajuste de z-index al mostrar CABYS
        cabysEl.addEventListener('show.bs.modal', () => {
            cabysEl.classList.add('modal-top');
            setTimeout(() => {
                const backs = document.querySelectorAll('.modal-backdrop');
                if (backs.length) backs[backs.length - 1].classList.add('modal-backdrop-top');
            }, 0);
        });

        // Inputs Agregar
        const addCabys = document.getElementById('addCabys');
        const addCodigo = document.getElementById('addCodigo');
        const addNombre = document.getElementById('addNombre');
        const addCategoria = document.getElementById('addCategoria');
        const addEsMedicamento = document.getElementById('addEsMedicamento');
        const addCamposMedicamento = document.getElementById('addCamposMedicamento');
        const addRegSanitario = document.getElementById('addRegSanitario');
        const addRegVence = document.getElementById('addRegVence');
        const addTipoMedicamento = document.getElementById('addTipoMedicamento');
        const addPrincipioActivo = document.getElementById('addPrincipioActivo');
        const addConcentracion = document.getElementById('addConcentracion');
        const addFormaFarmaceutica = document.getElementById('addFormaFarmaceutica');
        const addUmedida = document.getElementById('addUmedida');
        const addCantidad = document.getElementById('addCantidad');
        const addPrecio = document.getElementById('addPrecio');
        const addIva = document.getElementById('addIva');
        const addDescripcion = document.getElementById('addDescripcion');
        const addMostrarMas = document.getElementById('addMostrarMas');
        const addMasOpciones = document.getElementById('addMasOpciones');
        const addBarras = document.getElementById('addBarras');
        const addMarca = document.getElementById('addMarca');
        const addModelo = document.getElementById('addModelo');
        const addNota = document.getElementById('addNota');
        const addSku = document.getElementById('addSku');
        const btnGuardarProducto = document.getElementById('btnGuardarProducto');

        // Inputs Editar
        const editId = document.getElementById('editId');
        const editCabys = document.getElementById('editCabys');
        const editCodigo = document.getElementById('editCodigo');
        const editNombre = document.getElementById('editNombre');
        const editCategoria = document.getElementById('editCategoria');
        const editEsMedicamento = document.getElementById('editEsMedicamento');
        const editCamposMedicamento = document.getElementById('editCamposMedicamento');
        const editRegSanitario = document.getElementById('editRegSanitario');
        const editRegVence = document.getElementById('editRegVence');
        const editTipoMedicamento = document.getElementById('editTipoMedicamento');
        const editPrincipioActivo = document.getElementById('editPrincipioActivo');
        const editConcentracion = document.getElementById('editConcentracion');
        const editFormaFarmaceutica = document.getElementById('editFormaFarmaceutica');
        const editUmedida = document.getElementById('editUmedida');
        const editCantidad = document.getElementById('editCantidad');
        const editPrecio = document.getElementById('editPrecio');
        const editIva = document.getElementById('editIva');
        const editDescripcion = document.getElementById('editDescripcion');
        const editMostrarMas = document.getElementById('editMostrarMas');
        const editMasOpciones = document.getElementById('editMasOpciones');
        const editBarras = document.getElementById('editBarras');
        const editMarca = document.getElementById('editMarca');
        const editModelo = document.getElementById('editModelo');
        const editNota = document.getElementById('editNota');
        const editSku = document.getElementById('editSku');
        const btnActualizarProducto = document.getElementById('btnActualizarProducto');

        // CABYS UI
        const cabysQueryInput = document.getElementById('cabysQuery');
        const cabysResultados = document.getElementById('cabysResultados');
        const btnBuscarCabys = document.getElementById('btnBuscarCabys');

        // SweetAlert helpers
        const swalOk = (msg) => Swal.fire({
            icon: 'success',
            title: 'Listo',
            text: msg,
            timer: 1600,
            showConfirmButton: false
        });
        const swalError = (msg) => Swal.fire({
            icon: 'error',
            title: 'Error',
            text: msg
        });
        const swalConfirm = async (title, text, confirmText = 'Sí, continuar') => {
            const r = await Swal.fire({
                icon: 'warning',
                title,
                text,
                showCancelButton: true,
                confirmButtonText: confirmText,
                cancelButtonText: 'Cancelar'
            });
            return r.isConfirmed;
        };

        // UI togglers
        addEsMedicamento?.addEventListener('change', () => {
            addCamposMedicamento.classList.toggle('d-none', !addEsMedicamento.checked);
        });
        addMostrarMas?.addEventListener('change', () => {
            addMasOpciones.classList.toggle('d-none', !addMostrarMas.checked);
        });
        editEsMedicamento?.addEventListener('change', () => {
            editCamposMedicamento.classList.toggle('d-none', !editEsMedicamento.checked);
        });
        editMostrarMas?.addEventListener('change', () => {
            editMasOpciones.classList.toggle('d-none', !editMostrarMas.checked);
        });

        // Helpers
        const formatCurrency = (v) => Number(v || 0).toLocaleString('es-CR', {
            style: 'currency',
            currency: 'CRC',
            minimumFractionDigits: 2
        });
        const toEpoch = (yyyyMmDd) => {
            if (!yyyyMmDd) return null;
            const dt = new Date(yyyyMmDd + 'T00:00:00');
            return Math.floor(dt.getTime() / 1000);
        };
        const epochToYyyyMmDd = (ts) => {
            if (!ts) return '';
            const d = new Date(ts * 1000);
            const y = d.getFullYear();
            const m = String(d.getMonth() + 1).padStart(2, '0');
            const dd = String(d.getDate()).padStart(2, '0');
            return `${y}-${m}-${dd}`;
        };

        // ==== CARGA DE CATÁLOGOS ====
        async function fetchJson(url, bodyObj) {
            const res = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(bodyObj || {})
            });
            if (!res.ok) throw new Error(`HTTP ${res.status}`);
            return res.json();
        }
        var arrUnidadesMedidaNombres = [];
        async function cargarUnidadesMedida() {
            const list = await fetchJson(apiHacienda, {
                accion: 'unidad_medida'
            });
            const opts = ['<option value="">-- Seleccione --</option>'].concat(
                (list || []).map(u => `<option value="${u.idUnidadMedida}">${u.descripcion} (${u.simbolo})</option>`)
            ).join('');
            arrUnidadesMedidaNombres = list.reduce((acc, u) => {
                acc[u.idUnidadMedida] = u.descripcion;
                return acc;
            }, {});
            addUmedida.innerHTML = opts;
            editUmedida.innerHTML = opts;
        }

        async function cargarImpuestos() {
            const list = await fetchJson(apiHacienda, {
                accion: 'imp_general'
            });
            const opts = ['<option value="">-- Seleccione --</option>'].concat(
                (list || []).map(i => `<option value="${i.idImpGeneral}">${i.descripcion}</option>`)
            ).join('');
            addIva.innerHTML = opts;
            editIva.innerHTML = opts;
        }

        async function cargarTiposMedicamento() {
            const list = await fetchJson(apiHacienda, {
                accion: 'tipo_medicamentos'
            });
            const opts = ['<option value="">-- Seleccione --</option>'].concat(
                (list || []).map(t => `<option value="${t.idTipoMedicamento}">${t.descripcion}</option>`)
            ).join('');
            addTipoMedicamento.innerHTML = opts;
            editTipoMedicamento.innerHTML = opts;
        }

        var arrCategoriasNombres = [];
        async function cargarCategorias() {
            const r = await fetchJson(apiProductos, {
                accion: 'categorias'
            });
            const list = r?.data || [];
            arrCategoriasNombres = list.reduce((acc, c) => {
                acc[c.id] = c.nombre;
                return acc;
            }, {});
            const optsFiltro = ['<option value="">Todas</option>'].concat(
                list.filter(x => x.estado !== 0).map(c => `<option value="${c.id}">${c.nombre}</option>`)
            ).join('');
            selCategoriaFiltro.innerHTML = optsFiltro;

            const optsForm = ['<option value="">-- Seleccione --</option>'].concat(
                list.filter(x => x.estado === 1).map(c => `<option value="${c.id}">${c.nombre}</option>`)
            ).join('');
            addCategoria.innerHTML = optsForm;
            editCategoria.innerHTML = optsForm;
        }

        // ==== TABLA ====
        function crearFilaProducto(p) {
            const tr = document.createElement('tr');
            tr.dataset.id = p.id;

            const estadoBadge =
                p.estado === 1 ? '<span class="badge bg-success">Activo</span>' :
                p.estado === 2 ? '<span class="badge bg-secondary">Inactivo</span>' :
                '<span class="badge bg-danger">Eliminado</span>';

                    tr.innerHTML = `
            <td>${p.codProducto || ''}</td>
            <td>${p.nombre || ''}</td>
            <td>${arrUnidadesMedidaNombres[p.idUnidadMedida] || ''}</td>
            <td>${arrCategoriasNombres[p.idCategoria] || ''}</td>
            <td class="text-end">${p.cantidad != null ? Number(p.cantidad).toLocaleString('es-CR') : ''}</td>
            <td class="text-end">${p.precioUnitario != null ? formatCurrency(p.precioUnitario) : ''}</td>
            <td>${p.codCabys || ''}</td>
            <td>${estadoBadge}</td>
            <td class="text-center">
                <div class="btn-group btn-group-sm">
                <button class="btn btn-outline-secondary btnEditar" data-bs-toggle="tooltip" title="Editar"><i class="ri-edit-2-line"></i></button>
                <button class="btn btn-outline-warning btnToggleEstado" data-bs-toggle="tooltip" title="Activar/Inactivar"><i class="ri-toggle-line"></i></button>
                <button class="btn btn-outline-danger btnEliminar" data-bs-toggle="tooltip" title="Eliminar"><i class="ri-delete-bin-line"></i></button>
                </div>
            </td>
            `;
            return tr;
        }

        function actualizarRango() {
            if (totalItems === 0) {
                lblRango.textContent = 'Mostrando 0–0 de 0';
                return;
            }
            const desde = (currentPage - 1) * pageSize + 1;
            const hasta = Math.min(currentPage * pageSize, totalItems);
            lblRango.textContent = `Mostrando ${desde.toLocaleString('es-CR')}–${hasta.toLocaleString('es-CR')} de ${totalItems.toLocaleString('es-CR')}`;
        }

        function renderPagination() {
            pag.innerHTML = '';
            if (totalPages <= 1) return;

            const mkLi = (label, disabled, onClick, isActive = false, ariaLabel = '') => {
                const li = document.createElement('li');
                li.className = `page-item${disabled ? ' disabled' : ''}${isActive ? ' active' : ''}`;
                const a = document.createElement('a');
                a.className = 'page-link';
                if (ariaLabel) a.setAttribute('aria-label', ariaLabel);
                a.href = '#';
                a.innerHTML = label;
                if (!disabled) {
                    a.addEventListener('click', (e) => {
                        e.preventDefault();
                        onClick();
                    });
                }
                li.appendChild(a);
                return li;
            };

            // Primera / Anterior
            pag.appendChild(mkLi('&laquo;', currentPage === 1, () => irAPagina(1), false, 'Primera'));
            pag.appendChild(mkLi('&lsaquo;', currentPage === 1, () => irAPagina(currentPage - 1), false, 'Anterior'));

            // Ventana de 5 páginas
            const windowSize = 5;
            let start = Math.max(1, currentPage - Math.floor(windowSize / 2));
            let end = start + windowSize - 1;
            if (end > totalPages) {
                end = totalPages;
                start = Math.max(1, end - windowSize + 1);
            }

            for (let p = start; p <= end; p++) {
                pag.appendChild(mkLi(String(p), false, () => irAPagina(p), p === currentPage));
            }

            // Siguiente / Última
            pag.appendChild(mkLi('&rsaquo;', currentPage === totalPages, () => irAPagina(currentPage + 1), false, 'Siguiente'));
            pag.appendChild(mkLi('&raquo;', currentPage === totalPages, () => irAPagina(totalPages), false, 'Última'));
        }

        async function irAPagina(n) {
            if (n < 1 || n > totalPages) return;
            currentPage = n;
            await cargarProductos();
        }

        async function cargarProductos() {
            try {
                // limpiar
                tbody.querySelectorAll('tr:not(#filaVaciaProductos)').forEach(el => el.remove());

                const payload = {
                    accion: 'listar',
                    buscarColumna: (selBuscarEn.value || '').trim(),
                    buscarTexto: (inputBuscar.value || '').trim(),
                    idCategoria: (selCategoriaFiltro.value || '').trim(),
                    estado: (selEstadoFiltro.value || '').trim(),
                    pagina: currentPage,
                    tamPagina: Math.min(pageSize, maxPageSize)
                };

                const res = await fetch(apiProductos, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });

                const r = await res.json().catch(() => ({}));
                if (!res.ok || r.status !== 200) throw new Error(r.message || 'No se pudieron obtener los productos');

                const payloadData = r.data || {};
                const lista = Array.isArray(payloadData.data) ? payloadData.data : [];
                if (typeof payloadData.total === 'number') {
                    totalItems = payloadData.total;
                } else {
                    totalItems = (currentPage - 1) * pageSize + lista.length;
                }
                totalPages = Math.max(1, Math.ceil(totalItems / pageSize));

                if (lista.length === 0) {
                    filaVacia.classList.remove('d-none');
                } else {
                    filaVacia.classList.add('d-none');
                    const frag = document.createDocumentFragment();
                    lista.forEach(p => frag.appendChild(crearFilaProducto(p)));
                    tbody.appendChild(frag);
                    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));
                }

                actualizarRango();
                renderPagination();
            } catch (e) {
                swalError(e.message || 'Error cargando productos');
            }
        }

        // Buscar con Enter
        inputBuscar.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                currentPage = 1;
                cargarProductos();
            }
        });
        // Botón buscar
        btnBuscarProductos?.addEventListener('click', () => {
            currentPage = 1;
            cargarProductos();
        });
        // Cambios de filtros reinician a página 1
        [selBuscarEn, selCategoriaFiltro, selEstadoFiltro].forEach(ctrl => {
            ctrl.addEventListener('change', () => {
                currentPage = 1;
                cargarProductos();
            });
        });
        // Tamaño de página
        selTamPagina.addEventListener('change', () => {
            const v = parseInt(selTamPagina.value, 10) || 25;
            pageSize = Math.min(Math.max(1, v), maxPageSize);
            currentPage = 1;
            cargarProductos();
        });

        // ==== CREAR ====
        btnGuardarProducto?.addEventListener('click', async () => {
            try {
                if (!addCabys.value.trim() || !addNombre.value.trim()) {
                    swalError('Ingrese al menos CABYS y nombre.');
                    return;
                }
                const payload = {
                    accion: 'crear',
                    codCabys: addCabys.value.trim(),
                    codProducto: addCodigo.value.trim(),
                    nombre: addNombre.value.trim(),
                    idCategoria: addCategoria.value ? parseInt(addCategoria.value, 10) : null,
                    idUnidadMedida: addUmedida.value ? parseInt(addUmedida.value, 10) : null,
                    cantidad: addCantidad.value ? Number(addCantidad.value) : null,
                    precioUnitario: addPrecio.value ? Number(addPrecio.value) : null,
                    idImpGeneral: addIva.value ? parseInt(addIva.value, 10) : null,
                    descripcion: addDescripcion.value.trim() || null,
                    esMedicamento: !!addEsMedicamento.checked,
                    medRegistroSanitario: addRegSanitario.value.trim() || null,
                    medFechaVRegistro: addRegVence.value ? toEpoch(addRegVence.value) : null,
                    medIdTipoMedicamento: addTipoMedicamento.value ? parseInt(addTipoMedicamento.value, 10) : null,
                    medPrincipioActivo: addPrincipioActivo.value.trim() || null,
                    medConcentracion: addConcentracion.value.trim() || null,
                    medFormaFarmaceutica: addFormaFarmaceutica.value.trim() || null,
                    codigoBarras: addBarras.value.trim() || null,
                    marca: addMarca.value.trim() || null,
                    modelo: addModelo.value.trim() || null,
                    notas: addNota.value.trim() || null,
                    sku: addSku.value.trim() || null
                };

                const res = await fetch(apiProductos, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });
                const r = await res.json().catch(() => ({}));
                if (!res.ok || r.status !== 200) throw new Error(r.message || 'No se pudo crear el producto');

                swalOk('Producto creado');
                modalAgregar.hide();
                limpiarFormularioAgregar();
                await cargarProductos();
            } catch (e) {
                swalError(e.message || 'Error creando producto');
            }
        });

        function limpiarFormularioAgregar() {
            document.getElementById('formAgregarProducto').reset();
            addCamposMedicamento.classList.add('d-none');
            addMasOpciones.classList.add('d-none');
        }

        // ==== ACCIONES DE FILA ====
        tbody.addEventListener('click', async (ev) => {
            const btn = ev.target.closest('button');
            if (!btn) return;
            const tr = ev.target.closest('tr');
            const id = parseInt(tr?.dataset?.id || '0', 10);
            if (!id) return;

            // Editar
            if (btn.classList.contains('btnEditar')) {
                await cargarProductoEnEdicion(id);
                modalEditar.show();
                return;
            }

            // Toggle Estado
            if (btn.classList.contains('btnToggleEstado')) {
                const badge = tr.querySelector('td:nth-child(8) .badge');
                const txt = badge?.textContent?.trim() || '';
                let accion, msg;
                if (txt === 'Activo') {
                    accion = 'desactivar';
                    msg = 'El producto pasará a estado inactivo.';
                } else if (txt === 'Inactivo') {
                    accion = 'activar';
                    msg = 'El producto pasará a estado activo.';
                } else {
                    Swal.fire({
                        icon: 'info',
                        title: 'Aviso',
                        text: 'No puede cambiar estado a un producto eliminado.'
                    });
                    return;
                }

                const ok = await swalConfirm('¿Confirmar cambio de estado?', msg);
                if (!ok) return;

                try {
                    const res = await fetch(apiProductos, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            accion,
                            id
                        })
                    });
                    const r = await res.json().catch(() => ({}));
                    if (!res.ok || r.status !== 200) throw new Error(r.message || 'No se pudo aplicar el cambio');

                    swalOk('Estado actualizado');
                    await cargarProductos();
                } catch (e) {
                    swalError(e.message || 'Error cambiando estado');
                }
                return;
            }

            // Eliminar (estado = 0)
            if (btn.classList.contains('btnEliminar')) {
                const ok = await swalConfirm('¿Eliminar producto?', 'Se realizará eliminación lógica (estado = 0).', 'Sí, eliminar');
                if (!ok) return;
                try {
                    const res = await fetch(apiProductos, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            accion: 'eliminar',
                            id
                        })
                    });
                    const r = await res.json().catch(() => ({}));
                    if (!res.ok || r.status !== 200) throw new Error(r.message || 'No se pudo eliminar');

                    swalOk('Producto eliminado');
                    await cargarProductos();
                } catch (e) {
                    swalError(e.message || 'Error eliminando');
                }
                return;
            }
        });

        // Cargar producto en modal edición
        async function cargarProductoEnEdicion(id) {
            try {
                const res = await fetch(apiProductos, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        accion: 'obtener',
                        id
                    })
                });
                const r = await res.json().catch(() => ({}));
                if (!res.ok || r.status !== 200) throw new Error(r.message || 'No se pudo obtener el producto');

                const p = r.data;
                editId.value = p.id;
                editCabys.value = p.codCabys || '';
                editCodigo.value = p.codProducto || '';
                editNombre.value = p.nombre || '';

                if (p.idCategoria != null) editCategoria.value = String(p.idCategoria);
                if (p.idUnidadMedida != null) editUmedida.value = String(p.idUnidadMedida);
                if (p.idImpGeneral != null) editIva.value = String(p.idImpGeneral);

                editEsMedicamento.checked = p.esMedicamento === 1;
                editCamposMedicamento.classList.toggle('d-none', !editEsMedicamento.checked);
                editRegSanitario.value = p.medRegistroSanitario || '';
                editRegVence.value = p.medFechaVRegistro ? epochToYyyyMmDd(p.medFechaVRegistro) : '';
                if (p.medIdTipoMedicamento != null) editTipoMedicamento.value = String(p.medIdTipoMedicamento);
                editPrincipioActivo.value = p.medPrincipioActivo || '';
                editConcentracion.value = p.medConcentracion || '';
                editFormaFarmaceutica.value = p.medFormaFarmaceutica || '';
                editCantidad.value = (p.cantidad != null ? p.cantidad : '');
                editPrecio.value = (p.precioUnitario != null ? p.precioUnitario : '');
                editDescripcion.value = p.descripcion || '';
                editMostrarMas.checked = !!(p.codigoBarras || p.marca || p.modelo || p.notas || p.sku);
                editMasOpciones.classList.toggle('d-none', !editMostrarMas.checked);
                editBarras.value = p.codigoBarras || '';
                editMarca.value = p.marca || '';
                editModelo.value = p.modelo || '';
                editNota.value = p.notas || '';
                editSku.value = p.sku || '';
            } catch (e) {
                swalError(e.message || 'Error cargando para edición');
            }
        }

        // Actualizar
        btnActualizarProducto?.addEventListener('click', async () => {
            try {
                const id = parseInt(editId.value || '0', 10);
                if (!id) {
                    swalError('ID inválido');
                    return;
                }
                if (!editCabys.value.trim() || !editNombre.value.trim()) {
                    swalError('Ingrese al menos CABYS y nombre.');
                    return;
                }

                const payload = {
                    accion: 'actualizar',
                    id,
                    codCabys: editCabys.value.trim(),
                    codProducto: editCodigo.value.trim(),
                    nombre: editNombre.value.trim(),
                    idCategoria: editCategoria.value ? parseInt(editCategoria.value, 10) : null,
                    idUnidadMedida: editUmedida.value ? parseInt(editUmedida.value, 10) : null,
                    cantidad: editCantidad.value ? Number(editCantidad.value) : null,
                    precioUnitario: editPrecio.value ? Number(editPrecio.value) : null,
                    idImpGeneral: editIva.value ? parseInt(editIva.value, 10) : null,
                    descripcion: editDescripcion.value.trim() || null,
                    esMedicamento: !!editEsMedicamento.checked,
                    medRegistroSanitario: editRegSanitario.value.trim() || null,
                    medFechaVRegistro: editRegVence.value ? toEpoch(editRegVence.value) : null,
                    medIdTipoMedicamento: editTipoMedicamento.value ? parseInt(editTipoMedicamento.value, 10) : null,
                    medPrincipioActivo: editPrincipioActivo.value.trim() || null,
                    medConcentracion: editConcentracion.value.trim() || null,
                    medFormaFarmaceutica: editFormaFarmaceutica.value.trim() || null,
                    codigoBarras: editBarras.value.trim() || null,
                    marca: editMarca.value.trim() || null,
                    modelo: editModelo.value.trim() || null,
                    notas: editNota.value.trim() || null,
                    sku: editSku.value.trim() || null
                };

                const res = await fetch(apiProductos, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });
                const r = await res.json().catch(() => ({}));
                if (!res.ok || r.status !== 200) throw new Error(r.message || 'No se pudo actualizar');

                swalOk('Producto actualizado');
                modalEditar.hide();
                await cargarProductos();
            } catch (e) {
                swalError(e.message || 'Error actualizando');
            }
        });

        // ================= CABYS: abrir sobrepuesto + buscar API oficial =================
        // Abrir CABYS sin cerrar modal padre
        document.querySelectorAll('.btnOpenCabys').forEach(btn => {
            btn.addEventListener('click', () => {
                modalCabys.show();
                // Enfocar input al abrir
                setTimeout(() => cabysQueryInput?.focus(), 200);
            });
        });

        // Limpiar contenido CABYS
        function clearCabysModal() {
            cabysQueryInput.value = '';
            cabysResultados.innerHTML = '';
        }

        // Al cerrar CABYS, limpiar y mantener bloqueo scroll si un modal padre sigue abierto
        cabysEl.addEventListener('hidden.bs.modal', () => {
            clearCabysModal();
            if (document.querySelector('#modalAgregarProducto.show, #modalEditarProducto.show')) {
                document.body.classList.add('modal-open');
            }
        });

        async function buscarCabys() {
            const q = (cabysQueryInput.value || '').trim();
            cabysResultados.innerHTML = '';
            if (!q) return;

            // UI feedback
            const prevHTML = btnBuscarCabys.innerHTML;
            btnBuscarCabys.disabled = true;
            btnBuscarCabys.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Buscando';

            try {
                const res = await fetch(`https://api.hacienda.go.cr/fe/cabys?q=${encodeURIComponent(q)}`);
                if (!res.ok) throw new Error(`HTTP ${res.status}`);
                const data = await res.json();

                const items = Array.isArray(data?.cabys) ? data.cabys : [];
                if (items.length === 0) {
                    cabysResultados.innerHTML = `<tr><td colspan="3" class="text-center text-muted">Sin resultados</td></tr>`;
                    return;
                }

                const frag = document.createDocumentFragment();
                items.forEach(item => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
          <td>${item.codigo}</td>
          <td>${item.descripcion}</td>
          <td class="text-center">
            <button class="btn btn-sm btn-outline-success"><i class="ri-check-line"></i></button>
          </td>
        `;
                    tr.querySelector('button').addEventListener('click', () => {
                        const isAddOpen = document.getElementById('modalAgregarProducto')?.classList.contains('show');
                        if (isAddOpen) addCabys.value = item.codigo;
                        else editCabys.value = item.codigo;

                        modalCabys.hide(); // se limpia en hidden
                    });
                    frag.appendChild(tr);
                });
                cabysResultados.appendChild(frag);
            } catch (err) {
                swalError('Error consultando CABYS');
            } finally {
                btnBuscarCabys.disabled = false;
                btnBuscarCabys.innerHTML = prevHTML;
            }
        }

        // Buscar por botón y por Enter
        btnBuscarCabys?.addEventListener('click', buscarCabys);
        cabysQueryInput?.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                buscarCabys();
            }
        });

        // ================== Inicial ==================
        (async () => {
            try {
                await Promise.all([
                    cargarUnidadesMedida(),
                    cargarImpuestos(),
                    cargarTiposMedicamento(),
                    cargarCategorias()
                ]);
                await cargarProductos();
            } catch (e) {
                swalError('Error cargando catálogos');
            }
        })();
    });
</script>