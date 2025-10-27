<div class="card-body">
    <div class="tab-content" id="docTabsContent">

        <!-- TAB: Diseno del documento -->
        <div class="tab-pane fade show active" id="tab-diseno" role="tabpanel" aria-labelledby="tab-diseno-tab">
            <div class="row g-4">
                <div class="col-12 col-lg-4">
                    <div class="border rounded p-3 h-100">
                        <h6 class="text-uppercase mb-3"><strong>Opciones generales</strong></h6>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="ChkHabilitarPorFacturacion">
                            <label class="form-check-label" for="ChkHabilitarPorFacturacion">
                                <i class="ri-checkbox-multiple-blank-line me-1"></i>
                                habilitar la configuración por medio de facturación
                            </label>
                        </div>

                        <div class="mb-3">
                            <label for="SelFormatoDocumento" class="form-label">
                                <i class="ri-file-text-line me-1"></i> Formato de documento
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="ri-layout-2-line"></i></span>
                                <select id="SelFormatoDocumento" class="form-select">
                                    <option value="" selected>Seleccione</option>
                                    <option value="ticket_58">IVA Detallado</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-8">
                    <div class="border rounded p-3 h-100">
                        <h6 class="text-uppercase mb-3"><strong>Color principal del diseño</strong></h6>
                        <div class="row g-3 align-items-center">
                            <div class="col-12">
                                <div class="d-flex flex-wrap gap-2" role="radiogroup" aria-label="Color principal">
                                    <input class="btn-check" type="radio" name="ColorDoc" id="ColorPrimary" value="#0d6efd" checked>
                                    <label class="btn border rounded-pill px-3 py-2" for="ColorPrimary" data-bs-toggle="tooltip" title="Primario">
                                        <i class="ri-checkbox-blank-circle-fill me-1" style="color:#0d6efd;"></i> Primario
                                    </label>

                                    <input class="btn-check" type="radio" name="ColorDoc" id="ColorSuccess" value="#198754">
                                    <label class="btn border rounded-pill px-3 py-2" for="ColorSuccess" data-bs-toggle="tooltip" title="Exito">
                                        <i class="ri-checkbox-blank-circle-fill me-1" style="color:#198754;"></i> Exito
                                    </label>

                                    <input class="btn-check" type="radio" name="ColorDoc" id="ColorWarning" value="#ffc107">
                                    <label class="btn border rounded-pill px-3 py-2" for="ColorWarning" data-bs-toggle="tooltip" title="Advertencia">
                                        <i class="ri-checkbox-blank-circle-fill me-1" style="color:#ffc107;"></i> Advertencia
                                    </label>

                                    <input class="btn-check" type="radio" name="ColorDoc" id="ColorDanger" value="#dc3545">
                                    <label class="btn border rounded-pill px-3 py-2" for="ColorDanger" data-bs-toggle="tooltip" title="Peligro">
                                        <i class="ri-checkbox-blank-circle-fill me-1" style="color:#dc3545;"></i> Peligro
                                    </label>

                                    <input class="btn-check" type="radio" name="ColorDoc" id="ColorDark" value="#212529">
                                    <label class="btn border rounded-pill px-3 py-2" for="ColorDark" data-bs-toggle="tooltip" title="Oscuro">
                                        <i class="ri-checkbox-blank-circle-fill me-1" style="color:#212529;"></i> Oscuro
                                    </label>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mt-3 p-3 border rounded bg-light d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <i class="ri-file-list-3-line fs-4 me-2"></i>
                                        <div>
                                            <div class="small text-muted">Vista previa</div>
                                            <div id="PreviewEtiqueta" class="fw-semibold">Encabezado del tiquete</div>
                                        </div>
                                    </div>
                                    <span id="PreviewColorSwatch" class="badge" style="background:#0d6efd;">&nbsp;</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB: Informacion por defecto -->
        <div class="tab-pane fade" id="tab-info-defecto" role="tabpanel" aria-labelledby="tab-info-defecto-tab">
            <div class="row g-4">
                <div class="col-12">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="ChkHabilitarDefecto">
                        <label class="form-check-label" for="ChkHabilitarDefecto">
                            <i class="ri-settings-3-line me-1"></i>
                            Habilitar configuración por defecto
                        </label>
                    </div>
                    <hr>
                </div>

                <div class="col-12 col-xl-6">
                    <div class="border rounded p-3 h-100">
                        <h6 class="text-uppercase mb-3"><strong>Parametros</strong></h6>

                        <div class="mb-3">
                            <label for="SelMoneda" class="form-label">
                                <i class="ri-exchange-dollar-line me-1"></i> Tipo de moneda
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="ri-money-dollar-circle-line"></i></span>
                                <select id="SelMoneda" class="form-select">
                                    <option value="">Seleccione</option>
                                    <option>CRC</option>
                                    <option>USD</option>
                                    <option>EUR</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="SelMedioPago" class="form-label">
                                <i class="ri-bank-card-2-line me-1"></i> Medio de pago
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="ri-wallet-3-line"></i></span>
                                <select id="SelMedioPago" class="form-select">
                                    <option value="">Seleccione</option>
                                    <option>Efectivo</option>
                                    <option>Tarjeta</option>
                                    <option>Transferencia</option>
                                    <option>Sinpe</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="SelDecimales" class="form-label">
                                <i class="ri-number-9 mr-1"></i> Cantidad de decimales
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="ri-hashtag"></i></span>
                                <select id="SelDecimales" class="form-select">
                                    <option>0</option>
                                    <option selected>2</option>
                                    <option>3</option>
                                    <option>4</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="SelImpuestoRenta" class="form-label">
                                <i class="ri-pie-chart-2-line me-1"></i> Impuesto renta
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="ri-percent-line"></i></span>
                                <select id="SelImpuestoRenta" class="form-select">
                                    <option value="">Seleccione</option>
                                    <option>Exento</option>
                                    <option>1%</option>
                                    <option>2%</option>
                                    <option>5%</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-0">
                            <label for="SelCondicionVenta" class="form-label">
                                <i class="ri-article-line me-1"></i> Condicion de venta
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="ri-hand-coin-line"></i></span>
                                <select id="SelCondicionVenta" class="form-select">
                                    <option value="">Seleccione</option>
                                    <option>Contado</option>
                                    <option>Crédito</option>
                                    <option>Apartado</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-xl-6">
                    <div class="border rounded p-3 h-100">
                        <h6 class="text-uppercase mb-3"><strong>Otros</strong></h6>

                        <div class="mb-3">
                            <label for="TxtNotas" class="form-label">
                                <i class="ri-chat-1-line me-1"></i> Notas
                            </label>
                            <textarea id="TxtNotas" rows="3" class="form-control" placeholder="Notas que apareceran en el documento"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="SelImpuestoGeneral" class="form-label">
                                <i class="ri-bar-chart-line me-1"></i> Impuesto general
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="ri-percent-line"></i></span>
                                <select id="SelImpuestoGeneral" class="form-select">
                                    <option value="">Seleccione</option>
                                    <option>0%</option>
                                    <option>1%</option>
                                    <option>4%</option>
                                    <option>13%</option>
                                </select>
                            </div>
                        </div>

                        <div class="d-flex align-items-center gap-2">
                            <button type="button" class="btn btn-outline-primary btn-sm" id="BtnAgregarImpuesto">
                                <i class="ri-add-line me-1"></i> Agregar impuesto
                            </button>
                            <small class="text-muted">
                                <i class="ri-information-line me-1"></i> Agregue combinaciones para la tabla inferior.
                            </small>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="border rounded p-3">
                        <h6 class="text-uppercase mb-3"><strong>Impuestos agregados</strong></h6>
                        <div class="table-responsive">
                            <table class="table table-sm align-middle mb-0" id="TablaImpuestos">
                                <thead class="table-light">
                                    <tr>
                                        <th><i class="ri-hashtag me-1"></i> #</th>
                                        <th><i class="ri-exchange-dollar-line me-1"></i> Moneda</th>
                                        <th><i class="ri-wallet-3-line me-1"></i> Medio pago</th>
                                        <th><i class="ri-percent-line me-1"></i> Imp. renta</th>
                                        <th><i class="ri-bar-chart-line me-1"></i> Imp. general</th>
                                        <th><i class="ri-article-line me-1"></i> Cond. venta</th>
                                        <th class="text-end"><i class="ri-settings-3-line me-1"></i> Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="text-muted">
                                        <td colspan="7"><i class="ri-information-line me-1"></i> Sin registros</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-end mt-3">
                            <button type="button" class="btn btn-success">
                                <i class="ri-save-3-line me-1"></i> Guardar configuracion
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- /TAB: Informacion por defecto -->

    </div>
</div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.forEach(function(el) {
            new bootstrap.Tooltip(el);
        });

        // Preview color
        const colorRadios = document.querySelectorAll('input[name="ColorDoc"]');
        const swatch = document.getElementById('PreviewColorSwatch');
        colorRadios.forEach(r => r.addEventListener('change', function() {
            if (swatch) swatch.style.background = this.value;
        }));

        // Tabla: agregar impuesto (demo)
        const btnAgregar = document.getElementById('BtnAgregarImpuesto');
        const tabla = document.getElementById('TablaImpuestos').querySelector('tbody');
        let idx = 0;
        btnAgregar?.addEventListener('click', () => {
            const moneda = document.getElementById('SelMoneda')?.value || '';
            const medio = document.getElementById('SelMedioPago')?.value || '';
            const renta = document.getElementById('SelImpuestoRenta')?.value || '';
            const ig = document.getElementById('SelImpuestoGeneral')?.value || '';
            const cond = document.getElementById('SelCondicionVenta')?.value || '';

            if (!moneda || !medio || !renta || !ig || !cond) {
                return;
            }

            if (tabla.querySelectorAll('tr').length === 1 && tabla.querySelector('tr.text-muted')) {
                tabla.innerHTML = '';
            }

            idx += 1;
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${idx}</td>
                <td>${moneda}</td>
                <td>${medio}</td>
                <td>${renta}</td>
                <td>${ig}</td>
                <td>${cond}</td>
                <td class="text-end">
                    <button type="button" class="btn btn-outline-danger btn-sm BtnEliminarFila" data-bs-toggle="tooltip" title="Eliminar">
                        <i class="ri-delete-bin-line"></i>
                    </button>
                </td>`;
            tabla.appendChild(tr);

            tabla.querySelectorAll('.BtnEliminarFila').forEach(b => {
                b.onclick = () => b.closest('tr').remove();
                new bootstrap.Tooltip(b);
            });
        });
    });
</script>