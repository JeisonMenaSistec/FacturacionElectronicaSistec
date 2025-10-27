<!-- Acciones -->
<div class="text-end mb-2">
    <button class="btn btn-outline-primary btn-wave" id="btnAgregarUsuario">
        <i class="ri-user-add-line align-middle me-1"></i> Nuevo usuario
    </button>
    <button class="btn btn-outline-secondary btn-wave" id="btnExportarExcel">
        <i class="ri-file-excel-2-line align-middle me-1"></i> Exportar Excel
    </button>
</div>

<!-- Filtros -->
<div class="card custom-card shadow-sm mb-3">
    <div class="card-body">
        <div class="row g-2 align-items-center">
            <div class="col-12 col-md-6">
                <label for="inputBuscar" class="form-label mb-1"><i class="ri-search-line me-1"></i> Buscar</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="ri-search-line"></i></span>
                    <input type="text" class="form-control" id="inputBuscar" placeholder="Nombre, correo, rol...">
                </div>
            </div>
            <div class="col-12 col-md-3">
                <label for="selRolFiltro" class="form-label mb-1"><i class="ri-shield-user-line me-1"></i> Rol</label>
                <select id="selRolFiltro" class="form-select">
                    <option value="">Todos</option>
                </select>
            </div>
            <div class="col-12 col-md-3">
                <label for="selEstadoFiltro" class="form-label mb-1"><i class="ri-toggle-line me-1"></i> Estado</label>
                <select id="selEstadoFiltro" class="form-select">
                    <option value="">Todos</option>
                    <option value="Activo">Activo</option>
                    <option value="Inactivo">Inactivo</option>
                </select>
            </div>
        </div>
    </div>
</div>

<!-- Tabla -->
<div class="card custom-card shadow-sm">
    <div class="card-body">
        <h6 class="text-uppercase mb-3"><strong>Usuarios registrados</strong></h6>
        <div class="table-responsive">
            <table class="table table-sm align-middle mb-0" id="tablaUsuarios">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width:48px;"><i class="ri-image-line me-1"></i></th>
                        <th><i class="ri-user-3-line me-1"></i> Nombre</th>
                        <th><i class="ri-mail-line me-1"></i> Correo</th>
                        <th><i class="ri-phone-line me-1"></i> Teléfono</th>
                        <th><i class="ri-shield-user-line me-1"></i> Rol</th>
                        <th><i class="ri-toggle-line me-1"></i> Estado</th>
                        <th class="text-end" style="width:180px;"><i class="ri-settings-3-line me-1"></i> Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="d-none" id="filaVacia">
                        <td colspan="7" class="text-center text-muted">
                            <i class="ri-information-line me-1"></i> Sin usuarios
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal: Crear/Editar -->
<div class="modal fade" id="modalUsuario" tabindex="-1" aria-labelledby="modalUsuarioLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title text-uppercase" id="modalUsuarioLabel"><strong>Usuario</strong></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">

                    <!-- Foto -->
                    <div class="col-12 col-lg-3">
                        <div class="border rounded p-3 h-100">
                            <h6 class="text-uppercase mb-3"><strong>Foto</strong></h6>
                            <div class="text-center">
                                <div class="ratio ratio-1x1 rounded-circle border bg-light d-flex align-items-center justify-content-center mb-3" style="overflow:hidden;">
                                    <img id="usrFotoPreview" src="assets/img/perfil.png" class="img-fluid" alt="foto">
                                </div>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="ri-upload-2-line"></i></span>
                                    <input type="file" id="usrFotoInput" class="form-control" accept=".jpg,.jpeg,.png">
                                    <button class="btn btn-outline-danger" type="button" id="usrFotoEliminar" data-bs-toggle="tooltip" title="Eliminar">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </div>
                                <p class="small text-muted mt-2 mb-0"><i class="ri-information-line me-1"></i> Ext: jpg, jpeg, png. Máx 2 MB.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Datos -->
                    <div class="col-12 col-lg-9">
                        <h6 class="text-uppercase mb-3"><strong>Datos del usuario</strong></h6>
                        <div class="row g-3">

                            <!-- Cédula (bloqueable en edición) -->
                            <div class="col-md-6">
                                <label for="cedula" class="form-label"><i class="ri-id-card-line me-1"></i> Cédula</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="ri-hashtag"></i></span>
                                    <input type="text" id="cedula" class="form-control" placeholder="1-2345-6789">
                                    <button class="btn btn-outline-secondary" type="button" id="btnBuscarHacienda" data-bs-toggle="tooltip" title="Autocompletar Hacienda">
                                        <i class="ri-search-line"></i>
                                    </button>
                                </div>
                                <div class="form-text" id="helpCedula"></div>
                            </div>

                            <div class="col-md-6">
                                <label for="nombreCompleto" class="form-label"><i class="ri-user-3-line me-1"></i> Nombre completo</label>
                                <input type="text" id="nombreCompleto" class="form-control" placeholder="Nombre y apellidos">
                            </div>

                            <div class="col-md-6">
                                <label for="correo" class="form-label"><i class="ri-mail-line me-1"></i> Correo</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="ri-at-line"></i></span>
                                    <input type="email" id="correo" class="form-control" placeholder="correo@dominio.com">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="direccion" class="form-label"><i class="ri-map-pin-line me-1"></i> Dirección</label>
                                <input type="text" id="direccion" class="form-control" placeholder="Dirección exacta">
                            </div>

                            <div class="col-md-4">
                                <label for="telefonoFijo" class="form-label"><i class="ri-phone-line me-1"></i> Teléfono fijo</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="ri-phone-line"></i></span>
                                    <input type="tel" id="telefonoFijo" class="form-control" placeholder="2222-2222">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label for="extension" class="form-label"><i class="ri-hashtag me-1"></i> Ext</label>
                                <input type="text" id="extension" class="form-control" placeholder="Ext.">
                            </div>
                            <div class="col-md-3">
                                <label for="celular" class="form-label"><i class="ri-smartphone-line me-1"></i> Celular</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="ri-smartphone-line"></i></span>
                                    <input type="tel" id="celular" class="form-control" placeholder="8888-8888">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="fax" class="form-label"><i class="ri-printer-line me-1"></i> Fax</label>
                                <input type="text" id="fax" class="form-control" placeholder="Fax">
                            </div>

                            <div class="col-md-6">
                                <label for="rol" class="form-label"><i class="ri-shield-user-line me-1"></i> Rol</label>
                                <select id="rol" class="form-select">
                                    <option value="">Seleccione</option>
                                </select>
                            </div>
                            <div class="col-md-6 d-flex align-items-end">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="estado">
                                    <label class="form-check-label" for="estado"><i class="ri-toggle-line me-1"></i> Activo</label>
                                </div>
                            </div>
                        </div>

                        <hr class="my-3">
                        <h6 class="text-uppercase mb-3"><strong>Seguridad</strong></h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="preguntaSeguridad" class="form-label"><i class="ri-question-line me-1"></i> Pregunta de seguridad</label>
                                <select id="preguntaSeguridad" class="form-select">
                                    <option value="">Seleccione</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="respuestaSeguridad" class="form-label"><i class="ri-edit-2-line me-1"></i> Respuesta (se guardará en minúscula)</label>
                                <input type="text" id="respuestaSeguridad" class="form-control" placeholder="Escriba su respuesta">
                            </div>
                        </div>

                        <div class="border rounded p-3 mt-3">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="chkCambiarContrasena">
                                <label class="form-check-label" for="chkCambiarContrasena"><i class="ri-key-2-line me-1"></i> Asignar / Cambiar contraseña</label>
                            </div>
                            <div id="panelContrasena" class="row g-3 d-none">
                                <div class="col-md-6">
                                    <label for="nuevaContrasena" class="form-label">Nueva contraseña</label>
                                    <input type="password" id="nuevaContrasena" class="form-control" autocomplete="new-password">
                                </div>
                                <div class="col-md-6">
                                    <label for="confirmarContrasena" class="form-label">Confirmar contraseña</label>
                                    <input type="password" id="confirmarContrasena" class="form-control" autocomplete="new-password">
                                </div>
                            </div>
                        </div>

                    </div><!-- col-9 -->
                </div><!-- row -->
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline-secondary" data-bs-dismiss="modal"><i class="ri-close-line me-1"></i> Cerrar</button>
                <button class="btn btn-primary" id="btnGuardarUsuario"><i class="ri-save-3-line me-1"></i> Guardar</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Tooltips
        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));

        // Refs
        const modalEl = document.getElementById('modalUsuario');
        const modal = new bootstrap.Modal(modalEl);
        const btnNuevo = document.getElementById('btnAgregarUsuario');
        const apiUrl = 'api/configuracion/mantenimiento_usuario.php';

        let rolesCache = [];
        let preguntasCache = [];

        // --- SweetAlert helpers ---
        function toastOk(msg) {
            Swal.fire({
                icon: 'success',
                title: msg,
                timer: 1600,
                showConfirmButton: false
            });
        }

        function toastWarn(msg) {
            Swal.fire({
                icon: 'warning',
                title: msg,
                timer: 1800,
                showConfirmButton: false
            });
        }

        function toastErr(msg) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: msg
            });
        }
        async function confirmAsk(texto) {
            const r = await Swal.fire({
                icon: 'question',
                title: '¿Confirmar?',
                text: texto,
                showCancelButton: true,
                confirmButtonText: 'Sí',
                cancelButtonText: 'No'
            });
            return r.isConfirmed;
        }

        // -------- Helpers UI --------
        function setCedulaEditable(isEditable) {
            const ced = document.getElementById('cedula');
            const btnH = document.getElementById('btnBuscarHacienda');
            const helpCedula = document.getElementById('helpCedula');
            ced.disabled = !isEditable;
            btnH.disabled = !isEditable;
            if (!isEditable) {
                helpCedula.textContent = 'La cédula no puede modificarse.';
            } else {
                helpCedula.textContent = '';
            }
        }

        function crearFilaUsuario(u) {
            const estadoBadge = u.estado ? '<span class="badge bg-success">Activo</span>' : '<span class="badge bg-secondary">Inactivo</span>';
            const rolNombre = u.rolNombre || (rolesCache.find(r => r.idRol === u.idRol)?.nombre || '');
            const fotoSrc = u.fotoUrl || 'assets/img/perfil.png';
            const tr = document.createElement('tr');
            tr.dataset.idUsuario = u.idUsuario;
            tr.innerHTML = `
                    <td><img src="${fotoSrc}" class="rounded-circle" alt="foto" width="40" height="40"></td>
                    <td data-col="Nombre">${(u.nombre || '')} ${(u.apellido1 || '')} ${(u.apellido2 || '')}</td>
                    <td data-col="Correo">${u.correoElectronico || ''}</td>
                    <td data-col="Telefono">${u.celular || u.telefonoFijo || ''}</td>
                    <td data-col="Rol">${rolNombre}</td>
                    <td data-col="Estado">${estadoBadge}</td>
                    <td class="text-end">
                        <div class="btn-group btn-group-sm">
                        <button class="btn btn-outline-secondary btnEditar" data-bs-toggle="tooltip" title="Editar"><i class="ri-edit-2-line"></i></button>
                        <button class="btn btn-outline-warning btnToggleEstado" data-bs-toggle="tooltip" title="Activar/Inactivar"><i class="ri-toggle-line"></i></button>
                        <button class="btn btn-outline-danger btnEliminar" data-bs-toggle="tooltip" title="Eliminar"><i class="ri-delete-bin-line"></i></button>
                        </div>
                    </td>
            `;
            return tr;
        }

        function filtrarTabla() {
            const q = (document.getElementById('inputBuscar').value || '').toLowerCase();
            const r = document.getElementById('selRolFiltro').value;
            const e = document.getElementById('selEstadoFiltro').value;
            let visibles = 0;

            document.querySelectorAll('#tablaUsuarios tbody tr').forEach(tr => {
                if (tr.id === 'filaVacia') return;
                const nombre = tr.querySelector('[data-col="Nombre"]')?.textContent.toLowerCase() || '';
                const correo = tr.querySelector('[data-col="Correo"]')?.textContent.toLowerCase() || '';
                const rol = tr.querySelector('[data-col="Rol"]')?.textContent || '';
                const estadoTxt = tr.querySelector('[data-col="Estado"] .badge')?.textContent || '';
                const matchQ = !q || nombre.includes(q) || correo.includes(q) || rol.toLowerCase().includes(q);
                const matchR = !r || rol === (rolesCache.find(x => x.idRol == r)?.nombre || '');
                const matchE = !e || estadoTxt === e;
                const show = matchQ && matchR && matchE;
                tr.classList.toggle('d-none', !show);
                if (show) visibles++;
            });
            document.getElementById('filaVacia').classList.toggle('d-none', visibles !== 0);
        }

        function limpiarFormulario() {
            document.querySelectorAll('#modalUsuario input, #modalUsuario select').forEach(el => {
                if (el.type === 'checkbox') el.checked = false;
                else if (el.type === 'file') el.value = '';
                else el.value = '';
            });
            document.getElementById('usrFotoPreview').src = 'assets/img/perfil.png';
            document.querySelectorAll('#tablaUsuarios tbody tr').forEach(tr => tr.classList.remove('tr-editando'));
            // Estado inicial: creación -> cédula editable
            setCedulaEditable(true);
            document.getElementById('panelContrasena').classList.add('d-none');
            document.getElementById('chkCambiarContrasena').checked = false;
        }

        function leerFormulario() {
            return {
                cedula: document.getElementById('cedula').value.trim(),
                nombreCompleto: document.getElementById('nombreCompleto').value.trim(),
                correo: document.getElementById('correo').value.trim(),
                direccion: document.getElementById('direccion').value.trim(),
                telefonoFijo: document.getElementById('telefonoFijo').value.trim(),
                extension: document.getElementById('extension').value.trim(),
                celular: document.getElementById('celular').value.trim(),
                fax: document.getElementById('fax').value.trim(),
                rol: document.getElementById('rol').value,
                estado: document.getElementById('estado').checked,
                preguntaSeguridad: document.getElementById('preguntaSeguridad').value,
                respuestaSeguridad: (document.getElementById('respuestaSeguridad').value || '').toLowerCase(),
                nuevaContrasena: document.getElementById('nuevaContrasena').value,
                confirmarContrasena: document.getElementById('confirmarContrasena').value,
                cambiarContrasena: document.getElementById('chkCambiarContrasena').checked
            };
        }

        // -------- Cargar combos/datos --------
        async function cargarRoles() {
            try {
                const res = await fetch(apiUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        accion: 'roles'
                    })
                });
                const data = await res.json().catch(() => ({}));
                if (!res.ok) throw new Error(data?.message || `Error HTTP ${res.status}`);
                rolesCache = data.data || [];
                const selRolFiltro = document.getElementById('selRolFiltro');
                const selRol = document.getElementById('rol');
                selRolFiltro.innerHTML = `<option value="">Todos</option>` + rolesCache.map(r => `<option value="${r.idRol}">${r.nombre}</option>`).join('');
                selRol.innerHTML = `<option value="">Seleccione</option>` + rolesCache.map(r => `<option value="${r.idRol}">${r.nombre}</option>`).join('');
            } catch (e) {
                toastErr(e.message || 'No se pudieron cargar los roles');
            }
        }

        async function cargarPreguntas() {
            try {
                const res = await fetch(apiUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        accion: 'preguntas'
                    })
                });
                const data = await res.json().catch(() => ({}));
                if (!res.ok) throw new Error(data?.message || `Error HTTP ${res.status}`);
                preguntasCache = data.data || [];
                const sel = document.getElementById('preguntaSeguridad');
                sel.innerHTML = `<option value="">Seleccione</option>` + preguntasCache.map(p => `<option value="${p.idPreguntaSeguridad}">${p.texto}</option>`).join('');
            } catch (e) {
                toastErr(e.message || 'No se pudieron cargar las preguntas de seguridad');
            }
        }

        async function cargarUsuarios() {
            try {
                const tbody = document.querySelector('#tablaUsuarios tbody');
                tbody.querySelectorAll('tr:not(#filaVacia)').forEach(tr => tr.remove());
                const res = await fetch(apiUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        accion: 'listar'
                    })
                });
                const data = await res.json().catch(() => ({}));
                if (!res.ok) throw new Error(data?.message || `Error HTTP ${res.status}`);

                const lista = data.data || [];
                if (lista.length === 0) {
                    document.getElementById('filaVacia').classList.remove('d-none');
                    return;
                }
                document.getElementById('filaVacia').classList.add('d-none');
                const frag = document.createDocumentFragment();
                lista.forEach(u => frag.appendChild(crearFilaUsuario(u)));
                tbody.appendChild(frag);
                document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));
            } catch (e) {
                toastErr(e.message || 'No se pudieron cargar los usuarios');
            }
        }

        // -------- Filtros --------
        document.getElementById('inputBuscar').addEventListener('input', filtrarTabla);
        document.getElementById('selRolFiltro').addEventListener('input', filtrarTabla);
        document.getElementById('selEstadoFiltro').addEventListener('input', filtrarTabla);

        // -------- Nuevo --------
        btnNuevo?.addEventListener('click', () => {
            limpiarFormulario();
            document.getElementById('modalUsuarioLabel').textContent = 'Nuevo usuario';
            modal.show();
        });

        // -------- Cambio de contraseña - toggle panel --------
        document.getElementById('chkCambiarContrasena')?.addEventListener('change', (e) => {
            document.getElementById('panelContrasena').classList.toggle('d-none', !e.target.checked);
        });

        // -------- Buscar en Hacienda (directo desde frontend) --------
        async function buscarEnHaciendaPorCedula() {
            const cedula = (document.getElementById('cedula').value || '').trim();
            if (!cedula) {
                toastWarn('Ingrese la cédula para consultar en Hacienda');
                return;
            }
            try {
                const url = 'https://api.hacienda.go.cr/fe/ae?identificacion=' + encodeURIComponent(cedula);
                const res = await fetch(url, {
                    method: 'GET'
                });
                if (!res.ok) throw new Error('No se pudo contactar a Hacienda');
                const data = await res.json();
                const nombreCompleto = data?.nombre || '';
                if (nombreCompleto) {
                    document.getElementById('nombreCompleto').value = nombreCompleto;
                    toastOk('Datos cargados desde Hacienda');
                } else {
                    toastWarn('Sin resultados en Hacienda');
                }
            } catch (e) {
                toastErr('Cedula no encontrada en el registro civil.');
            }
        }
        document.getElementById('btnBuscarHacienda')?.addEventListener('click', buscarEnHaciendaPorCedula);
        // opcional: auto al cambiar
        // document.getElementById('cedula')?.addEventListener('change', buscarEnHaciendaPorCedula);

        // -------- Acciones por fila (editar / estado / eliminar) --------
        document.querySelector('#tablaUsuarios')?.addEventListener('click', async (ev) => {
            const btn = ev.target.closest('button');
            if (!btn) return;
            const tr = ev.target.closest('tr');
            const idUsuario = parseInt(tr?.dataset?.idUsuario || '0', 10);
            if (!idUsuario) return;

            // EDITAR: ahora pide al backend el detalle real
            if (btn.classList.contains('btnEditar')) {
                try {
                    const res = await fetch(apiUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            accion: 'obtener',
                            idUsuario
                        })
                    });
                    const data = await res.json().catch(() => ({}));
                    if (!res.ok) throw new Error(data?.message || `Error HTTP ${res.status}`);

                    const u = data.data || {};
                    limpiarFormulario();
                    tr.classList.add('tr-editando');

                    // Cédula bloqueada
                    document.getElementById('cedula').value = u.identificacion || '';
                    setCedulaEditable(false);

                    // Nombre completo para visual
                    const nombreComp = [u.nombre, u.apellido1, u.apellido2].filter(Boolean).join(' ');
                    document.getElementById('nombreCompleto').value = nombreComp;

                    document.getElementById('correo').value = u.correoElectronico || '';
                    document.getElementById('direccion').value = u.direccionExacta || '';
                    document.getElementById('telefonoFijo').value = u.telefonoFijo || '';
                    document.getElementById('extension').value = u.telefonoExtension || '';
                    document.getElementById('celular').value = u.celular || '';
                    document.getElementById('fax').value = u.fax || '';
                    document.getElementById('rol').value = u.idRol || '';
                    document.getElementById('estado').checked = !!u.estado;

                    // Foto
                    if (u.fotoUrl) document.getElementById('usrFotoPreview').src = u.fotoUrl;

                    // Seguridad: seleccionar pregunta guardada y vaciar respuesta
                    if (u.idPreguntaSeguridad) {
                        document.getElementById('preguntaSeguridad').value = u.idPreguntaSeguridad;
                    } else {
                        document.getElementById('preguntaSeguridad').value = '';
                    }
                    document.getElementById('respuestaSeguridad').value = '';

                    document.getElementById('modalUsuarioLabel').textContent = 'Editar usuario';
                    modal.show();
                } catch (e) {
                    toastErr(e.message || 'No se pudo cargar el usuario');
                }
                return;
            }

            // Toggle estado
            if (btn.classList.contains('btnToggleEstado')) {
                const ok = await confirmAsk('¿Desea cambiar el estado de este usuario?');
                if (!ok) return;
                const badge = tr.querySelector('[data-col="Estado"] .badge');
                const activo = badge.classList.contains('bg-success');
                try {
                    const res = await fetch(apiUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            accion: 'actualizar',
                            idUsuario,
                            estado: !activo
                        })
                    });
                    const data = await res.json().catch(() => ({}));
                    if (!res.ok) throw new Error(data?.message || `Error HTTP ${res.status}`);

                    badge.classList.toggle('bg-success', !activo);
                    badge.classList.toggle('bg-secondary', activo);
                    badge.textContent = !activo ? 'Activo' : 'Inactivo';
                    toastOk('Estado actualizado');
                } catch (e) {
                    toastErr(e.message || 'No se pudo actualizar el estado');
                }
                return;
            }

            // Eliminar (desactivar)
            if (btn.classList.contains('btnEliminar')) {
                const ok = await confirmAsk('¿Eliminar (desactivar) este usuario?');
                if (!ok) return;
                try {
                    const res = await fetch(apiUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            accion: 'eliminar',
                            idUsuario
                        })
                    });
                    const data = await res.json().catch(() => ({}));
                    if (!res.ok) throw new Error(data?.message || `Error HTTP ${res.status}`);

                    tr.remove();
                    if (!document.querySelector('#tablaUsuarios tbody tr:not(#filaVacia)')) {
                        document.getElementById('filaVacia').classList.remove('d-none');
                    }
                    toastOk('Usuario eliminado');
                } catch (e) {
                    toastErr(e.message || 'No se pudo eliminar');
                }
            }
        });

        // -------- Guardar (crear/actualizar) --------
        document.getElementById('btnGuardarUsuario')?.addEventListener('click', async () => {
            const datos = leerFormulario();

            if (!datos.correo || !datos.nombreCompleto || !datos.rol) {
                toastWarn('Complete al menos: nombre, correo y rol.');
                return;
            }

            // validación cambio contraseña
            if (datos.cambiarContrasena) {
                if (!datos.nuevaContrasena || !datos.confirmarContrasena) {
                    toastWarn('Debe ingresar y confirmar la nueva contraseña.');
                    return;
                }
                if (datos.nuevaContrasena !== datos.confirmarContrasena) {
                    toastWarn('Las contraseñas no coinciden.');
                    return;
                }
            }

            // nombre/apellidos
            const partes = (datos.nombreCompleto || '').split(' ').filter(Boolean);
            let nombre = '',
                apellido1 = '',
                apellido2 = '';
            if (partes.length >= 1) nombre = partes.shift();
            if (partes.length >= 1) apellido1 = partes.shift();
            if (partes.length >= 1) apellido2 = partes.join(' ');

            const payloadBase = {
                idRol: datos.rol ? parseInt(datos.rol, 10) : 0,
                identificacion: datos.cedula, // en edición ya viene bloqueada
                correoElectronico: datos.correo,
                nombre,
                apellido1,
                apellido2,
                direccionExacta: datos.direccion,
                telefonoFijo: datos.telefonoFijo,
                telefonoExtension: datos.extension,
                celular: datos.celular,
                fax: datos.fax,
                estado: !!datos.estado,
                preguntaSeguridadId: datos.preguntaSeguridad ? parseInt(datos.preguntaSeguridad, 10) : undefined,
                // siempre minúscula
                respuestaSeguridad: datos.respuestaSeguridad ? datos.respuestaSeguridad.toLowerCase() : undefined
            };

            // contraseña nueva (opcional)
            if (datos.cambiarContrasena) {
                payloadBase.contrasena = datos.nuevaContrasena;
            }

            // foto
            const fotoSrc = document.getElementById('usrFotoPreview')?.getAttribute('src');
            if (fotoSrc && fotoSrc.startsWith('data:')) payloadBase.fotoBase64 = fotoSrc;

            const filaEdit = document.querySelector('#tablaUsuarios tbody tr.tr-editando');
            const accion = filaEdit ? 'actualizar' : 'crear';
            if (filaEdit) payloadBase.idUsuario = parseInt(filaEdit.dataset.idUsuario, 10);

            try {
                const res = await fetch(apiUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        accion,
                        ...payloadBase
                    })
                });
                const data = await res.json().catch(() => ({}));
                if (!res.ok) throw new Error(data?.message || `Error HTTP ${res.status}`);

                toastOk(filaEdit ? 'Usuario actualizado' : 'Usuario creado');
                modal.hide();
                limpiarFormulario();
                await cargarUsuarios();
                filtrarTabla();
            } catch (e) {
                toastErr(e.message || 'No se pudo guardar');
            }
        });

        // -------- Exportar CSV --------
        document.getElementById('btnExportarExcel')?.addEventListener('click', () => {
            const rows = [
                ['Nombre', 'Correo', 'Teléfono', 'Rol', 'Estado']
            ];
            document.querySelectorAll('#tablaUsuarios tbody tr').forEach(tr => {
                if (tr.id === 'filaVacia' || tr.classList.contains('d-none')) return;
                const nombre = tr.querySelector('[data-col="Nombre"]')?.textContent.trim() || '';
                const correo = tr.querySelector('[data-col="Correo"]')?.textContent.trim() || '';
                const tel = tr.querySelector('[data-col="Telefono"]')?.textContent.trim() || '';
                const rol = tr.querySelector('[data-col="Rol"]')?.textContent.trim() || '';
                const estado = tr.querySelector('[data-col="Estado"] .badge')?.textContent.trim() || '';
                rows.push([nombre, correo, tel, rol, estado]);
            });
            const csv = rows.map(r => r.map(v => `"${(v || '').replace(/"/g,'""')}"`).join(',')).join('\n');
            const blob = new Blob([csv], {
                type: 'text/csv;charset=utf-8;'
            });
            const a = document.createElement('a');
            a.href = URL.createObjectURL(blob);
            a.download = 'usuarios.csv';
            a.click();
            URL.revokeObjectURL(a.href);
        });

        // -------- Foto (preview) ≤ 2 MB --------
        const fileInput = document.getElementById('usrFotoInput');
        const fotoPrev = document.getElementById('usrFotoPreview');
        const fotoDel = document.getElementById('usrFotoEliminar');

        fileInput?.addEventListener('change', function() {
            const f = this.files?.[0];
            if (!f) return;
            const ok = ['image/jpeg', 'image/png', 'image/jpg'].includes(f.type) && f.size <= 2 * 1024 * 1024;
            if (!ok) {
                this.value = '';
                toastWarn('Imagen inválida (solo jpg/png, máx 2 MB)');
                return;
            }
            const r = new FileReader();
            r.onload = e => fotoPrev.src = e.target.result;
            r.readAsDataURL(f);
        });

        fotoDel?.addEventListener('click', async () => {
            const ok = await confirmAsk('¿Quitar foto actual?');
            if (!ok) return;

            // Si estamos editando un usuario existente, enviar petición al API
            const filaEdit = document.querySelector('#tablaUsuarios tbody tr.tr-editando');
            if (filaEdit) {
                const idUsuario = parseInt(filaEdit.dataset.idUsuario, 10);
                try {
                    const res = await fetch(apiUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            accion: 'eliminar_foto',
                            idUsuario
                        })
                    });
                    const data = await res.json().catch(() => ({}));
                    if (!res.ok) throw new Error(data?.message || `Error HTTP ${res.status}`);

                    toastOk('Foto eliminada correctamente');
                    await cargarUsuarios();
                } catch (e) {
                    toastErr(e.message || 'No se pudo eliminar la foto');
                    return;
                }
            }

            // Resetear la vista de la foto
            fotoPrev.src = 'assets/img/perfil.png';
            if (fileInput) fileInput.value = '';
        });

        // -------- Inicio --------
        (async () => {
            await cargarRoles();
            await cargarPreguntas();
            await cargarUsuarios();
            filtrarTabla();
        })();
    });
</script>