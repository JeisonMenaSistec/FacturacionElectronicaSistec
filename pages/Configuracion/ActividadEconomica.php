<!-- Page Header Close -->
<!-- Intro -->
<div class="alert alert-info d-flex align-items-start gap-2" role="alert">
    <i class="ri-information-line fs-4"></i>
    <div>
        En este apartado puede configurar las actividades económicas registradas ante el Ministerio de Hacienda para usarlas en la facturación y la recepción de documentos. El nombre comercial y el logo son opcionales; si no se indican, se usarán los configurados en Información de la Cuenta.
    </div>
</div>
<div class="btn-list">
    <a class="btn btn-primary btn-wave me-2" href="https://www.hacienda.go.cr/" target="_blank" rel="noopener">
        <i class="ri-external-link-line align-middle me-1"></i> Consultar actividades económicas
    </a>
</div>
<!-- Start::row-1 -->
<div class="row g-3">


    <div class="col-12">
        <div class="card custom-card shadow-sm">
            <div class="card-body">
                <h6 class="text-uppercase mb-3">
                    <i class="ri-store-2-line me-1"></i>
                    <strong>Identificación comercial</strong>
                </h6>

                <div class="mb-3">
                    <label for="NombreComercial" class="form-label">
                        <i class="ri-edit-2-line me-1"></i> Nombre comercial (opcional)
                    </label>
                    <input type="text" id="NombreComercial" class="form-control" placeholder="Ingrese nombre comercial">
                </div>

                <h6 class="text-uppercase mb-3">
                    <i class="ri-briefcase-2-line me-1"></i>
                    <strong>Configuración de actividad</strong>
                </h6>

                <div class="row g-3">
                    <label for="choices-single-default" class="form-label">
                        <i class="ri-list-unordered me-1"></i> Código de actividad (Hacienda)
                    </label>
                    <select class="form-control" data-trigger name="choices-single-default" id="choices-single-default">
                        <option value="" selected disabled>Selecciona una Actividad</option>
                        <option value="471100">471100 - Comercio al por menor en establecimientos no especializados</option>
                        <option value="561011">561011 - Servicio de restaurantes</option>
                        <option value="620101">620101 - Desarrollo de software</option>
                        <option value="620201">620201 - Consultoría en informática</option>
                    </select>

                    <div class="col-12 d-flex justify-content-end">
                        <button type="button" class="btn btn-primary" id="BtnGuardarActividad">
                            <i class="ri-save-3-line me-1"></i> Guardar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla -->
        <div class="card custom-card shadow-sm mt-3">
            <div class="card-body">
                <h6 class="text-uppercase mb-3">
                    <i class="ri-table-line me-1"></i>
                    <strong>Actividades configuradas</strong>
                </h6>

                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0" id="TablaActividades">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 80px;"><i class="ri-hashtag me-1"></i> Código</th>
                                <th><i class="ri-file-text-line me-1"></i> Descripción</th>
                                <th><i class="ri-store-2-line me-1"></i> Nombre comercial</th>
                                <th><i class="ri-image-line me-1"></i> Logo</th>
                                <th><i class="ri-toggle-line me-1"></i> Estado</th>
                                <th class="text-end" style="width: 140px;"><i class="ri-settings-3-line me-1"></i> Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="text-muted">
                                <td colspan="6"><i class="ri-information-line me-1"></i> Sin registros</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-end mt-3 gap-2">
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="BtnExportar">
                        <i class="ri-download-2-line me-1"></i> Exportar
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>
<!--End::row-1 -->

<!-- End::app-content -->
<!-- Scripts: tooltips, preview logo, demo tabla -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.forEach(el => new bootstrap.Tooltip(el));

        // Preview logo
        const inputLogo = document.getElementById('InputLogo');
        const previewLogo = document.getElementById('PreviewLogo');
        const btnEliminarLogo = document.getElementById('BtnEliminarLogo');
        inputLogo?.addEventListener('change', function() {
            const file = this.files?.[0];
            if (!file) return;
            const allowed = ['image/jpeg', 'image/png', 'image/jpg'];
            if (!allowed.includes(file.type) || file.size > 1024 * 1024) {
                this.value = '';
                return;
            }
            const reader = new FileReader();
            reader.onload = e => previewLogo.src = e.target.result;
            reader.readAsDataURL(file);
        });
        btnEliminarLogo?.addEventListener('click', function() {
            previewLogo.src = 'https://via.placeholder.com/300x300?text=Logo';
            const input = document.getElementById('InputLogo');
            if (input) input.value = '';
        });

        // Tabla demo: agregar al guardar
        const btnGuardar = document.getElementById('BtnGuardarActividad');
        const tablaBody = document.querySelector('#TablaActividades tbody');
        let idx = 0;
        btnGuardar?.addEventListener('click', function() {
            const codigo = document.getElementById('ActividadCodigo').value;
            const codigoText = document.getElementById('ActividadCodigo').selectedOptions[0]?.text || '';
            const nombreComercial = document.getElementById('NombreComercial').value || '';
            const logoSrc = document.getElementById('PreviewLogo').getAttribute('src') || '';

            if (!codigo) return;

            if (tablaBody.querySelector('tr.text-muted')) tablaBody.innerHTML = '';

            idx += 1;
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${codigo}</td>
                <td>${codigoText.replace(/^[0-9\\-\\s]+\\-\\s?/, '')}</td>
                <td>${nombreComercial || '-'}</td>
                <td>
                    ${logoSrc.includes('placeholder') ? '-' : `<img src="${logoSrc}" alt="logo" class="rounded" style="width:32px;height:32px;object-fit:cover;">`}
                </td>
                <td>
                    <span class="badge bg-success d-inline-flex align-items-center gap-1">
                        <i class="ri-check-line"></i> Activo
                    </span>
                </td>
                <td class="text-end">
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Editar">
                            <i class="ri-edit-2-line"></i>
                        </button>
                        <button type="button" class="btn btn-outline-warning BtnToggleEstado" data-bs-toggle="tooltip" title="Inactivar/Activar">
                            <i class="ri-toggle-line"></i>
                        </button>
                        <button type="button" class="btn btn-outline-danger BtnEliminarFila" data-bs-toggle="tooltip" title="Eliminar">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </div>
                </td>
            `;
            tablaBody.appendChild(tr);

            tr.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));

            tr.querySelector('.BtnToggleEstado').addEventListener('click', function() {
                const badge = tr.querySelector('.badge');
                const activo = badge.classList.contains('bg-success');
                badge.classList.toggle('bg-success', !activo);
                badge.classList.toggle('bg-secondary', activo);
                badge.innerHTML = activo ?
                    `<i class="ri-subtract-line"></i> Inactivo` :
                    `<i class="ri-check-line"></i> Activo`;
            });

            tr.querySelector('.BtnEliminarFila').addEventListener('click', function() {
                tr.remove();
                if (!tablaBody.children.length) {
                    tablaBody.innerHTML = `<tr class="text-muted"><td colspan="6"><i class="ri-information-line me-1"></i> Sin registros</td></tr>`;
                }
            });
        });
    });
</script>