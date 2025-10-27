<div class="text-end mb-2">
    <button class="btn btn-outline-primary btn-wave" id="btnNuevo">
        <i class="ri-add-line me-1"></i> Nueva categoría
    </button>
</div>

<!-- Filtros -->
<div class="card custom-card shadow-sm mb-3">
    <div class="card-body">
        <div class="row g-2 align-items-end">
            <div class="col-12 col-md-6">
                <label for="inputBuscar" class="form-label mb-1"><i class="ri-search-line me-1"></i> Buscar</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="ri-search-line"></i></span>
                    <input type="text" class="form-control" id="inputBuscar" placeholder="Nombre o descripción...">
                </div>
            </div>
            <div class="col-12 col-md-3">
                <label for="selEstadoFiltro" class="form-label mb-1"><i class="ri-toggle-line me-1"></i> Estado</label>
                <select id="selEstadoFiltro" class="form-select">
                    <option value="">Todos</option>
                    <option value="Activo">Activo</option>
                    <option value="Inactivo">Inactivo</option>
                    <option value="Eliminado">Eliminado</option>
                </select>
            </div>
            <div class="col-12 col-md-3 d-grid">
                <label class="form-label mb-1" style="visibility:hidden;">.</label>
                <button class="btn btn-primary" id="btnBuscar">
                    <i class="ri-search-line"></i> Buscar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Tabla -->
<div class="card custom-card shadow-sm">
    <div class="card-body">
        <h6 class="text-uppercase mb-3"><strong>Categorías registradas</strong></h6>
        <div class="table-responsive">
            <table class="table table-hover align-middle" id="tablaCategorias">
                <thead class="table-light">
                    <tr>
                        <th>ID Cat</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Estado</th>
                        <th class="text-end" style="width:180px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="d-none" id="filaVaciaCategorias">
                        <td colspan="5" class="text-center text-muted">
                            <i class="ri-information-line me-1"></i> Sin categorías
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal: Agregar -->
<div class="modal fade" id="modalAgregar" tabindex="-1" aria-labelledby="modalAgregarLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title text-uppercase" id="modalAgregarLabel"><strong>Nueva categoría</strong></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form id="formAgregar" novalidate>
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label for="addNombre" class="form-label">Nombre</label>
                            <input type="text" id="addNombre" class="form-control" required>
                            <div class="invalid-feedback">Ingrese el nombre.</div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="addDescripcion" class="form-label">Descripción</label>
                            <input type="text" id="addDescripcion" class="form-control" placeholder="Opcional">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline-secondary" data-bs-dismiss="modal"><i class="ri-close-line me-1"></i> Cerrar</button>
                <button class="btn btn-primary" id="btnGuardar"><i class="ri-save-3-line me-1"></i> Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Editar -->
<div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title text-uppercase" id="modalEditarLabel"><strong>Editar categoría</strong></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form id="formEditar" novalidate>
                    <input type="hidden" id="editId">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label for="editNombre" class="form-label">Nombre</label>
                            <input type="text" id="editNombre" class="form-control" required>
                            <div class="invalid-feedback">Ingrese el nombre.</div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="editDescripcion" class="form-label">Descripción</label>
                            <input type="text" id="editDescripcion" class="form-control" placeholder="Opcional">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline-secondary" data-bs-dismiss="modal"><i class="ri-close-line me-1"></i> Cerrar</button>
                <button class="btn btn-primary" id="btnActualizar"><i class="ri-save-3-line me-1"></i> Actualizar</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Tooltips
        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));

        const apiUrl = 'api/productos/categorias.php';

        // Filtros
        const inputBuscar = document.getElementById('inputBuscar');
        const selEstadoFiltro = document.getElementById('selEstadoFiltro');
        const btnBuscar = document.getElementById('btnBuscar');

        // Tabla
        const tbody = document.querySelector('#tablaCategorias tbody');
        const filaVacia = document.getElementById('filaVaciaCategorias');

        // Modales
        const modalAgregar = new bootstrap.Modal(document.getElementById('modalAgregar'));
        const modalEditar = new bootstrap.Modal(document.getElementById('modalEditar'));

        // Inputs Agregar
        const addNombre = document.getElementById('addNombre');
        const addDescripcion = document.getElementById('addDescripcion');
        const btnNuevo = document.getElementById('btnNuevo');
        const btnGuardar = document.getElementById('btnGuardar');

        // Inputs Editar
        const editId = document.getElementById('editId');
        const editNombre = document.getElementById('editNombre');
        const editDescripcion = document.getElementById('editDescripcion');
        const btnActualizar = document.getElementById('btnActualizar');

        // SweetAlert helpers
        const swalOk = (msg) => Swal.fire({
            icon: 'success',
            title: 'Listo',
            text: msg,
            timer: 1500,
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

        // Botón nuevo
        btnNuevo?.addEventListener('click', () => {
            document.getElementById('formAgregar').reset();
            modalAgregar.show();
        });

        // Guardar nueva
        btnGuardar?.addEventListener('click', async () => {
            try {
                if (!addNombre.value.trim()) {
                    addNombre.classList.add('is-invalid');
                    setTimeout(() => addNombre.classList.remove('is-invalid'), 1500);
                    return;
                }
                const payload = {
                    accion: 'crear',
                    nombre: addNombre.value.trim(),
                    descripcion: addDescripcion.value.trim()
                };
                const res = await fetch(apiUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });
                const r = await res.json().catch(() => ({}));
                if (!res.ok || r.status !== 200) throw new Error(r.message || 'No se pudo crear');

                swalOk('Categoría creada');
                modalAgregar.hide();
                await cargarCategorias();
            } catch (e) {
                swalError(e.message || 'Error al crear');
            }
        });

        // Cargar lista
        async function cargarCategorias() {
            try {
                tbody.querySelectorAll('tr:not(#filaVaciaCategorias)').forEach(el => el.remove());
                const payload = {
                    accion: 'listar',
                    buscar: (inputBuscar.value || '').trim(),
                    estado: selEstadoFiltro.value || ''
                };
                const res = await fetch(apiUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });
                const r = await res.json().catch(() => ({}));
                if (!res.ok || r.status !== 200) throw new Error(r.message || 'No se pudo listar');

                const lista = r.data || [];
                if (lista.length === 0) {
                    filaVacia.classList.remove('d-none');
                    return;
                }
                filaVacia.classList.add('d-none');

                const frag = document.createDocumentFragment();
                lista.forEach(c => frag.appendChild(crearFilaCategoria(c)));
                tbody.appendChild(frag);
                document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));
            } catch (e) {
                swalError(e.message || 'Error cargando categorías');
            }
        }

        function crearFilaCategoria(c) {
            const tr = document.createElement('tr');
            tr.dataset.id = c.id;
            const estadoBadge =
                c.estado === 1 ? '<span class="badge bg-success">Activo</span>' :
                c.estado === 2 ? '<span class="badge bg-secondary">Inactivo</span>' :
                '<span class="badge bg-danger">Eliminado</span>';

            const toggleButton = (c.estado === 1) ?
                '<button class="btn btn-outline-warning btn-sm btnDesactivar" data-bs-toggle="tooltip" title="Desactivar"><i class="ri-toggle-line"></i></button>' :
                '<button class="btn btn-outline-success btn-sm btnActivar" data-bs-toggle="tooltip" title="Activar"><i class="ri-toggle-line"></i></button>';

            tr.innerHTML = `
      <td>${c.idCategoria}</td>
      <td>${c.nombre || ''}</td>
      <td>${c.descripcion || ''}</td>
      <td>${estadoBadge}</td>
      <td class="text-end">
        <div class="btn-group btn-group-sm">
          <button class="btn btn-outline-secondary btnEditar" data-bs-toggle="tooltip" title="Editar"><i class="ri-edit-2-line"></i></button>
          ${toggleButton}
          <button class="btn btn-outline-danger btnEliminar" data-bs-toggle="tooltip" title="Eliminar"><i class="ri-delete-bin-line"></i></button>
        </div>
      </td>
    `;
            return tr;
        }

        // Buscar
        btnBuscar?.addEventListener('click', cargarCategorias);

        // Acciones fila
        tbody.addEventListener('click', async (ev) => {
            const btn = ev.target.closest('button');
            if (!btn) return;
            const tr = ev.target.closest('tr');
            const id = parseInt(tr?.dataset?.id || '0', 10);
            if (!id) return;

            // Editar
            if (btn.classList.contains('btnEditar')) {
                await cargarCategoriaEnEdicion(id);
                modalEditar.show();
                return;
            }

            // Activar
            if (btn.classList.contains('btnActivar')) {
                const ok = await swalConfirm('¿Activar categoría?', 'Pasará a estado activo.');
                if (!ok) return;
                await accionSimple('activar', id, 'Categoría activada');
                return;
            }

            // Desactivar
            if (btn.classList.contains('btnDesactivar')) {
                const ok = await swalConfirm('¿Desactivar categoría?', 'Pasará a estado inactivo.');
                if (!ok) return;
                await accionSimple('desactivar', id, 'Categoría desactivada');
                return;
            }

            // Eliminar
            if (btn.classList.contains('btnEliminar')) {
                const ok = await swalConfirm('¿Eliminar categoría?', '', 'Sí, eliminar');
                if (!ok) return;
                await accionSimple('eliminar', id, 'Categoría eliminada');
                return;
            }
        });

        async function cargarCategoriaEnEdicion(id) {
            try {
                const res = await fetch(apiUrl, {
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
                if (!res.ok || r.status !== 200) throw new Error(r.message || 'No se pudo obtener');

                const c = r.data;
                editId.value = c.id;
                editNombre.value = c.nombre || '';
                editDescripcion.value = c.descripcion || '';
            } catch (e) {
                swalError(e.message || 'Error cargando categoría');
            }
        }

        // Actualizar
        btnActualizar?.addEventListener('click', async () => {
            try {
                const id = parseInt(editId.value || '0', 10);
                if (!id) {
                    swalError('ID inválido');
                    return;
                }
                if (!editNombre.value.trim()) {
                    editNombre.classList.add('is-invalid');
                    setTimeout(() => editNombre.classList.remove('is-invalid'), 1500);
                    return;
                }
                const payload = {
                    accion: 'actualizar',
                    id,
                    nombre: editNombre.value.trim(),
                    descripcion: editDescripcion.value.trim()
                };
                const res = await fetch(apiUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });
                const r = await res.json().catch(() => ({}));
                if (!res.ok || r.status !== 200) throw new Error(r.message || 'No se pudo actualizar');

                swalOk('Categoría actualizada');
                modalEditar.hide();
                await cargarCategorias();
            } catch (e) {
                swalError(e.message || 'Error actualizando');
            }
        });

        async function accionSimple(accion, id, okMsg) {
            try {
                const res = await fetch(apiUrl, {
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
                if (!res.ok || r.status !== 200) throw new Error(r.message || `No se pudo ${accion}`);
                swalOk(okMsg);
                await cargarCategorias();
            } catch (e) {
                swalError(e.message || `Error al ${accion}`);
            }
        }

        // Inicio
        cargarCategorias();
    });
</script>