<div class="card custom-card shadow-sm">
    <div class="card-body">
        <form id="formRolesId" method="post">
            <div class="row g-3 align-items-end mb-2">
                <div class="col-12 col-md-4">
                    <label for="rolSelectId" class="form-label">
                        <i class="ri-shield-user-line me-1"></i> Seleccionar rol
                    </label>
                    <select id="rolSelectId" class="form-select"></select>
                </div>

                <div class="col-12 col-md-5">
                    <label for="filtroSeccionId" class="form-label">
                        <i class="ri-search-line me-1"></i> Buscar sección
                    </label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="ri-search-line"></i></span>
                        <input id="filtroSeccionId" type="text" class="form-control" placeholder="Filtrar por nombre de sección o módulo">
                    </div>
                </div>

                <div class="col-12 col-md-3 d-flex gap-2">
                    <button type="button" class="btn btn-outline-secondary" id="seleccionarTodoId">
                        <i class="ri-checkbox-multiple-line me-1"></i> Seleccionar todo
                    </button>
                    <button type="button" class="btn btn-outline-secondary" id="limpiarTodoId">
                        <i class="ri-eraser-line me-1"></i> Limpiar todo
                    </button>
                </div>
            </div>

            <div class="table-responsive border rounded">
                <table class="table table-sm align-middle mb-0" id="tablaPermisosId">
                    <thead class="table-light">
                        <tr>
                            <th style="min-width:260px;"><i class="ri-pages-line me-1"></i> Sección / Módulo</th>
                            <th class="text-center">
                                <div class="m-0">
                                    <label class="form-check-label"><i class="ri-eye-line me-1"></i> Ver</label>
                                    <input class="form-check-input seleccionarColId" type="checkbox" data-col="ver">
                                </div>
                            </th>
                            <th class="text-center">
                                <div class="m-0">
                                    <label class="form-check-label"><i class="ri-add-line me-1"></i> Agregar</label>
                                    <input class="form-check-input seleccionarColId" type="checkbox" data-col="agregar">
                                </div>
                            </th>
                            <th class="text-center">
                                <div class="m-0">
                                    <label class="form-check-label"><i class="ri-edit-2-line me-1"></i> Editar</label>
                                    <input class="form-check-input seleccionarColId" type="checkbox" data-col="editar">
                                </div>
                            </th>
                            <th class="text-center">
                                <div class="m-0">
                                    <label class="form-check-label"><i class="ri-delete-bin-line me-1"></i> Eliminar</label>
                                    <input class="form-check-input seleccionarColId" type="checkbox" data-col="eliminar">
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="tbodyPermisosId"></tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="small text-muted">
                    <i class="ri-information-line me-1"></i> Los botones aplicar/limpiar actúan solo sobre filas visibles por el filtro.
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-primary" id="guardarInferiorId">
                        <i class="ri-save-3-line me-1"></i> Guardar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const apiUrl = 'api/configuracion/roles.php';

        const rolSelectId = document.getElementById('rolSelectId');
        const filtroSeccionId = document.getElementById('filtroSeccionId');
        const tablaPermisosId = document.getElementById('tablaPermisosId');
        const tbodyPermisosId = document.getElementById('tbodyPermisosId');
        const seleccionarTodoId = document.getElementById('seleccionarTodoId');
        const limpiarTodoId = document.getElementById('limpiarTodoId');
        const guardarInferiorId = document.getElementById('guardarInferiorId');
        const token = document.querySelector('input[name="__RequestVerificationToken"]')?.value || '';

        let cacheFilas = []; // [{menuId,seccion,ver,agregar,editar,eliminar,canAgregar,canEditar,canEliminar}]
        let filtro = '';

        const postJson = async (payload) => {
            const resp = await fetch(apiUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'RequestVerificationToken': token
                },
                credentials: 'same-origin',
                body: JSON.stringify(payload)
            });
            if (!resp.ok) {
                const t = await resp.text().catch(() => '');
                throw new Error(t || 'Error HTTP');
            }
            return resp.json();
        };

        function actualizarChecksColumnas() {
            tablaPermisosId.querySelectorAll('.seleccionarColId').forEach(h => {
                const col = h.dataset.col;
                const visibles = Array.from(
                    tbodyPermisosId.querySelectorAll(`tr:not(.d-none) .chkPermId[data-perm="${col}"]`)
                );
                const marcados = visibles.filter(chk => chk.checked);
                h.indeterminate = marcados.length > 0 && marcados.length < visibles.length;
                h.checked = visibles.length > 0 && marcados.length === visibles.length;
            });
        }

        function aplicarFiltro() {
            const q = (filtro || '').toLowerCase();
            Array.from(tbodyPermisosId.querySelectorAll('tr')).forEach(tr => {
                const s = (tr.dataset.seccion || '').toLowerCase();
                tr.classList.toggle('d-none', q && !s.includes(q));
            });
            actualizarChecksColumnas();
        }

        function cellSwitch(checked, permName) {
            return `<input class="form-check-input chkPermId" type="checkbox" data-perm="${permName}" ${checked ? 'checked' : ''}>`;
        }

        function cellDash() {
            return `<span class="text-muted">—</span>`;
        }

        function renderFilas(filas) {
            tbodyPermisosId.innerHTML = '';
            const frag = document.createDocumentFragment();

            filas.forEach(f => {
                const tr = document.createElement('tr');
                tr.dataset.seccion = f.seccion;
                tr.dataset.menuId = f.menuId;

                const td0 = document.createElement('td');
                td0.textContent = f.seccion;

                const tdVer = document.createElement('td');
                tdVer.className = 'text-center';
                const tdAdd = document.createElement('td');
                tdAdd.className = 'text-center';
                const tdEdit = document.createElement('td');
                tdEdit.className = 'text-center';
                const tdDel = document.createElement('td');
                tdDel.className = 'text-center';

                tdVer.innerHTML = cellSwitch(!!f.ver, 'ver');
                tdAdd.innerHTML = f.canAgregar ? cellSwitch(!!f.agregar, 'agregar') : cellDash();
                tdEdit.innerHTML = f.canEditar ? cellSwitch(!!f.editar, 'editar') : cellDash();
                tdDel.innerHTML = f.canEliminar ? cellSwitch(!!f.eliminar, 'eliminar') : cellDash();

                tr.appendChild(td0);
                tr.appendChild(tdVer);
                tr.appendChild(tdAdd);
                tr.appendChild(tdEdit);
                tr.appendChild(tdDel);
                frag.appendChild(tr);
            });

            tbodyPermisosId.appendChild(frag);
            aplicarFiltro();
        }

        async function cargarRoles() {
            const data = await postJson({
                accion: 'roles'
            });
            rolSelectId.innerHTML = data.map(r => `<option value="${r.rolId}">${r.nombre}</option>`).join('');
        }

        async function cargarPermisos(rolId) {
            const all = await postJson({
                accion: 'permisos',
                rolId: parseInt(rolId, 10)
            });
            cacheFilas = Array.isArray(all) ? all : [];
            renderFilas(cacheFilas);
        }

        function recolectarPermisos() {
            const rolId = parseInt(rolSelectId.value, 10);
            const permisos = [];

            Array.from(tbodyPermisosId.querySelectorAll('tr')).forEach(tr => {
                if (tr.classList.contains('d-none')) return;
                const menuId = parseInt(tr.dataset.menuId, 10);
                const get = p => tr.querySelector(`.chkPermId[data-perm="${p}"]`);
                const ver = get('ver')?.checked ? 1 : 0;
                const agregar = get('agregar') ? (get('agregar').checked ? 1 : 0) : 0;
                const editar = get('editar') ? (get('editar').checked ? 1 : 0) : 0;
                const eliminar = get('eliminar') ? (get('eliminar').checked ? 1 : 0) : 0;

                permisos.push({
                    menuId,
                    ver,
                    agregar,
                    editar,
                    eliminar
                });
            });

            return {
                rolId,
                permisos
            };
        }

        filtroSeccionId.addEventListener('input', () => {
            filtro = filtroSeccionId.value;
            aplicarFiltro();
        });

        tablaPermisosId.querySelectorAll('.seleccionarColId').forEach(colChk => {
            colChk.addEventListener('change', () => {
                const col = colChk.dataset.col;
                Array.from(tbodyPermisosId.querySelectorAll(`tr:not(.d-none) .chkPermId[data-perm="${col}"]`))
                    .forEach(chk => {
                        chk.checked = colChk.checked;
                    });
                actualizarChecksColumnas();
            });
        });

        tablaPermisosId.addEventListener('change', (e) => {
            if (e.target.classList.contains('chkPermId')) actualizarChecksColumnas();
        });

        seleccionarTodoId.addEventListener('click', () => {
            Array.from(tbodyPermisosId.querySelectorAll('tr:not(.d-none) .chkPermId'))
                .forEach(chk => {
                    chk.checked = true;
                });
            actualizarChecksColumnas();
        });

        limpiarTodoId.addEventListener('click', () => {
            Array.from(tbodyPermisosId.querySelectorAll('tr:not(.d-none) .chkPermId'))
                .forEach(chk => {
                    chk.checked = false;
                });
            actualizarChecksColumnas();
        });

        rolSelectId.addEventListener('change', async () => {
            await cargarPermisos(parseInt(rolSelectId.value, 10));
        });

        guardarInferiorId.addEventListener('click', async () => {
            try {
                const payload = recolectarPermisos();
                const res = await postJson({
                    accion: 'guardar',
                    ...payload
                });
                if (window.Swal) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Guardado',
                        text: 'Permisos guardados correctamente.',
                        showConfirmButton: false,
                        timer: 1800
                    });
                } else {
                    alert('Permisos guardados correctamente.');
                }
            } catch (err) {
                if (window.Swal) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al guardar.'
                    });
                } else {
                    alert('Error al guardar.');
                }
                console.error(err);
            }
        });

        (async () => {
            try {
                await cargarRoles();
                if (rolSelectId.options.length > 0) {
                    await cargarPermisos(parseInt(rolSelectId.value, 10));
                }
            } catch (e) {
                console.error(e);
            }
        })();
    });
</script>