<!-- Start::row-1 -->
<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-body">
                <ul class="nav nav-tabs" id="tabsEmpresa" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="tabInfoGeneralBtn" data-bs-toggle="tab" data-bs-target="#tabInfoGeneral" type="button" role="tab" aria-controls="tabInfoGeneral" aria-selected="true">Información General</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tabCertificadoBtn" data-bs-toggle="tab" data-bs-target="#tabCertificado" type="button" role="tab" aria-controls="tabCertificado" aria-selected="false">Certificado Digital</button>
                    </li>
                </ul>

                <div class="tab-content" id="tabsEmpresaContent">
                    <!-- ============== TAB: INFORMACIÓN GENERAL ============== -->
                    <div class="tab-pane fade show active" id="tabInfoGeneral" role="tabpanel" aria-labelledby="tabInfoGeneralBtn">
                        <form class="mt-3" id="formularioEmpresa" onsubmit="return manejarSubmitEmpresa(event)" autocomplete="off">
                            <div class="row g-3">
                                <!-- Columna izquierda -->
                                <div class="col-12 col-lg-3">
                                    <div class="card h-100 shadow-sm">
                                        <div class="card-body">
                                            <h6 class="text-uppercase mb-3">
                                                <i class="ri-bank-card-2-line me-1"></i>
                                                <strong>Empresa</strong>
                                            </h6>

                                            <div class="mb-3">
                                                <label for="inputNumeroEmpresa" class="form-label">N° de Empresa</label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="ri-hashtag"></i></span>
                                                    <input type="text" id="inputNumeroEmpresa" class="form-control" placeholder="0000" disabled>
                                                </div>
                                            </div>

                                            <div class="mb-4">
                                                <label for="inputEstadoCuenta" class="form-label">
                                                    <i class="ri-shield-check-line"></i> Estado
                                                </label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="ri-traffic-light-line"></i></span>
                                                    <input type="text" id="inputEstadoCuenta" class="form-control" placeholder="Activo" disabled>
                                                </div>
                                            </div>

                                            <h6 class="text-uppercase mb-3">
                                                <i class="ri-image-line me-1"></i>
                                                <strong>Logo</strong>
                                            </h6>

                                            <div class="mb-2 text-center">
                                                <div class="ratio ratio-1x1 border rounded d-flex align-items-center justify-content-center bg-light">
                                                    <img id="imgLogo" src="https://placehold.jp/3d4070/ffffff/240x240.png?text=logo" alt="Logo de la empresa" class="img-fluid p-2">
                                                </div>
                                            </div>

                                            <div class="input-group">
                                                <span class="input-group-text"><i class="ri-upload-2-line"></i></span>
                                                <input type="file" class="form-control" id="inputArchivoLogo" accept=".jpg,.jpeg,.png" onchange="manejarCambioArchivoLogo(this)">
                                                <button type="button" class="btn btn-outline-danger" id="btnEliminarLogo" title="Eliminar logo" onclick="manejarClickEliminarLogo()">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </div>

                                            <p class="small text-muted m-0">
                                                <i class="ri-information-line me-1"></i>
                                                Extensiones válidas: jpg, jpeg, png. Tamaño máx. 1 MB. Ancho máx. 1366 px.
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Columna derecha -->
                                <div class="col-12 col-lg-9">
                                    <div class="card shadow-sm">
                                        <div class="card-body">
                                            <h6 class="text-uppercase mb-3">
                                                <i class="ri-file-list-3-line me-1"></i>
                                                <strong>Información general</strong>
                                            </h6>

                                            <div class="row g-3">
                                                <div class="col-md-4">
                                                    <label for="selectTipoCedula" class="form-label">
                                                        <i class="ri-list-unordered"></i> Tipo de cédula
                                                    </label>
                                                    <select id="selectTipoCedula" class="form-select">
                                                        <option value="" selected>Seleccione</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="inputIdentificacion" class="form-label">
                                                        <i class="ri-id-card-line"></i> N° Identificación
                                                    </label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="ri-hashtag"></i></span>
                                                        <input type="text" id="inputIdentificacion" class="form-control" placeholder="Número de identificación">
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="inputNombreComercial" class="form-label">
                                                        <i class="ri-store-2-line"></i> Nombre Comercial
                                                    </label>
                                                    <input type="text" id="inputNombreComercial" class="form-control" placeholder="Nombre comercial">
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="inputRazonSocial" class="form-label">
                                                        <i class="ri-building-2-line"></i> Razón Social
                                                    </label>
                                                    <input type="text" id="inputRazonSocial" class="form-control" placeholder="Razón social">
                                                </div>
                                            </div>

                                            <hr class="my-4">

                                            <h6 class="text-uppercase mb-3">
                                                <i class="ri-contacts-book-2-line me-1"></i>
                                                <strong>Información de Contacto</strong>
                                            </h6>

                                            <div class="row g-3">
                                                <div class="col-md-4">
                                                    <label for="inputCorreo" class="form-label">
                                                        <i class="ri-mail-line"></i> Correo
                                                    </label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="ri-at-line"></i></span>
                                                        <input type="email" id="inputCorreo" class="form-control" placeholder="correo@dominio.com">
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="inputApartadoPostal" class="form-label">
                                                        <i class="ri-mail-open-line"></i> Apartado postal
                                                    </label>
                                                    <input type="text" id="inputApartadoPostal" class="form-control" placeholder="Apartado postal">
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="inputCodigoArea" class="form-label">
                                                        <i class="ri-global-line"></i> Código de área
                                                    </label>
                                                    <input type="text" id="inputCodigoArea" class="form-control" maxlength="4" inputmode="numeric" placeholder="ej. 506" oninput="this.value=this.value.replace(/\\D+/g,'').slice(0,4)">
                                                    <div class="form-text">Solo dígitos, máx. 4 (sin +).</div>
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="inputTelefono1" class="form-label">
                                                        <i class="ri-phone-line"></i> Teléfono 1
                                                    </label>
                                                    <input type="tel" id="inputTelefono1" class="form-control" placeholder="Teléfono principal" oninput="this.value=this.value.replace(/\\D+/g,'')">
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="inputTelefono2" class="form-label">
                                                        <i class="ri-phone-fill"></i> Teléfono 2
                                                    </label>
                                                    <input type="tel" id="inputTelefono2" class="form-control" placeholder="Teléfono alterno" oninput="this.value=this.value.replace(/\\D+/g,'')">
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="inputFax" class="form-label">
                                                        <i class="ri-printer-line"></i> Fax
                                                    </label>
                                                    <input type="text" id="inputFax" class="form-control" placeholder="Fax" oninput="this.value=this.value.replace(/\\D+/g,'')">
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="selectProvincia" class="form-label">
                                                        <i class="ri-map-pin-line"></i> Provincia
                                                    </label>
                                                    <select id="selectProvincia" class="form-select" onchange="manejarCambioProvincia(this.value)">
                                                        <option selected value="">Seleccione</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="selectCanton" class="form-label">
                                                        <i class="ri-map-pin-2-line"></i> Cantón
                                                    </label>
                                                    <select id="selectCanton" class="form-select" onchange="manejarCambioCanton(this.value)">
                                                        <option selected value="">Seleccione</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="selectDistrito" class="form-label">
                                                        <i class="ri-compass-3-line"></i> Distrito
                                                    </label>
                                                    <select id="selectDistrito" class="form-select" onchange="manejarCambioDistrito(this.value)">
                                                        <option selected value="">Seleccione</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="selectBarrio" class="form-label">
                                                        <i class="ri-community-line"></i> Barrio
                                                    </label>
                                                    <select id="selectBarrio" class="form-select">
                                                        <option selected value="">Seleccione</option>
                                                    </select>
                                                </div>

                                                <div class="col-12">
                                                    <label for="textareaDireccion" class="form-label">
                                                        <i class="ri-road-map-line"></i> Dirección
                                                    </label>
                                                    <textarea id="textareaDireccion" class="form-control" rows="2" placeholder="Dirección exacta"></textarea>
                                                </div>
                                            </div>

                                            <hr class="my-4">

                                            <h6 class="text-uppercase mb-3">
                                                <i class="ri-service-line me-1"></i>
                                                <strong>Servicios Adicionales</strong>
                                            </h6>

                                            <div class="row g-2">
                                                <div class="col-12">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="chkConvertirTiquete">
                                                        <label class="form-check-label" for="chkConvertirTiquete">
                                                            <i class="ri-recycle-line me-1"></i> Convertir tiquete electrónico a factura electrónica
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="chkRegimenAgropecuario">
                                                        <label class="form-check-label" for="chkRegimenAgropecuario">
                                                            <i class="ri-leaf-line me-1"></i> Régimen especial agropecuario
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="chkCargoAutomatico">
                                                        <label class="form-check-label" for="chkCargoAutomatico">
                                                            <i class="ri-bank-card-line me-1"></i> Suscripción a cargo automático de mi plan 5685
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="chkVistoBueno">
                                                        <label class="form-check-label" for="chkVistoBueno">
                                                            <i class="ri-checkbox-circle-line me-1"></i> Visto bueno de facturas aceptadas
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="chkReporteMensual">
                                                        <label class="form-check-label" for="chkReporteMensual">
                                                            <i class="ri-bar-chart-2-line me-1"></i> Reporte mensual
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="d-flex justify-content-end mt-4">
                                                <button type="submit" class="btn btn-primary" id="btnGuardarEmpresa">
                                                    <i class="ri-save-3-line me-1"></i> Guardar
                                                </button>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- ============== TAB: CERTIFICADO DIGITAL ============== -->
                    <div class="tab-pane fade" id="tabCertificado" role="tabpanel" aria-labelledby="tabCertificadoBtn">
                        <form class="mt-3" id="formularioCertificado" onsubmit="return manejarSubmitCertificado(event)" autocomplete="off">
                            <div class="row g-3">

                                <!-- Panel izquierdo: Certificado actual -->
                                <div class="col-12 col-lg-4 col-xl-3">
                                    <div class="card h-100 shadow-sm">
                                        <div class="card-body">
                                            <h6 class="text-uppercase mb-3 d-flex align-items-center gap-2">
                                                <i class="ri-certificate-line"></i>
                                                <strong>Certificado actual</strong>
                                            </h6>

                                            <div class="mb-3">
                                                <label class="form-label"><i class="ri-user-3-line me-1"></i> Usuario (ATV)</label>
                                                <input type="text" id="inputCertUsuario" class="form-control" value="" disabled>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label"><i class="ri-lock-2-line me-1"></i> Contraseña (ATV)</label>
                                                <input type="text" id="inputCertContrasena" class="form-control" value="" disabled>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label"><i class="ri-shield-keyhole-line me-1"></i> PIN de llave</label>
                                                <input type="text" id="inputCertPin" class="form-control" value="" disabled>
                                            </div>

                                            <div class="row g-2">
                                                <div class="col-12 col-md-6">
                                                    <label class="form-label"><i class="ri-calendar-line me-1"></i> Creación</label>
                                                    <input type="text" id="inputCertCreacion" class="form-control" value="" disabled>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <label class="form-label"><i class="ri-calendar-check-line me-1"></i> Registro</label>
                                                    <input type="text" id="inputCertRegistro" class="form-control" value="" disabled>
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label"><i class="ri-timer-flash-line me-1"></i> Vencimiento</label>
                                                    <input type="text" id="inputCertVencimiento" class="form-control" value="" disabled>
                                                </div>
                                            </div>

                                            <div class="d-grid mt-3">
                                                <button type="button" class="btn btn-outline-danger" id="btnEliminarLlave" onclick="eliminarLlaveCripto()">
                                                    <i class="ri-delete-bin-7-line me-1"></i> Eliminar llave
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Panel derecho: Nuevo certificado -->
                                <div class="col-12 col-lg-8 col-xl-9">
                                    <div class="card shadow-sm h-100">
                                        <div class="card-body">
                                            <h6 class="text-uppercase mb-3 d-flex align-items-center gap-2">
                                                <i class="ri-file-shield-2-line"></i>
                                                <strong>Nuevo certificado</strong>
                                            </h6>

                                            <div class="row g-3">
                                                <div class="col-12 col-md-6">
                                                    <label for="inputUsuarioAtvNuevo" class="form-label">
                                                        <i class="ri-user-add-line me-1"></i> Usuario de ingreso (ATV)
                                                    </label>
                                                    <input type="text" id="inputUsuarioAtvNuevo" class="form-control" placeholder="ej. cpj-1234-5678" autocomplete="username">
                                                </div>

                                                <div class="col-12 col-md-6">
                                                    <label for="inputContrasenaAtvNuevo" class="form-label">
                                                        <i class="ri-lock-password-line me-1"></i> Contraseña de ingreso (ATV)
                                                    </label>
                                                    <input type="password" id="inputContrasenaAtvNuevo" class="form-control" placeholder="Ingrese contraseña" autocomplete="new-password">
                                                </div>

                                                <div class="col-12 col-md-6">
                                                    <label for="inputPinLlaveNuevo" class="form-label">
                                                        <i class="ri-shield-keyhole-line me-1"></i> PIN de la llave criptográfica
                                                    </label>
                                                    <input type="password" id="inputPinLlaveNuevo" class="form-control" placeholder="PIN de la llave" autocomplete="off">
                                                </div>

                                                <div class="col-12 col-md-6">
                                                    <label for="inputArchivoLlaveNuevo" class="form-label">
                                                        <i class="ri-usb-line me-1"></i> Llave criptográfica (.pun / .pen)
                                                    </label>
                                                    <input type="file" id="inputArchivoLlaveNuevo" class="form-control" accept=".pun,.pen">
                                                    <div class="form-text">
                                                        Se almacenará en <code>/uploads/keys/</code> en <strong>texto plano</strong>.
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="d-flex justify-content-end mt-4">
                                                <button type="submit" class="btn btn-primary" id="btnGuardarCertificado">
                                                    <i class="ri-save-3-line me-1"></i> Guardar
                                                </button>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>
                    <!-- ============== /TAB: CERTIFICADO ============== -->
                </div> <!-- tab-content -->
            </div>
        </div>
    </div>
</div>
<!-- End::row-1 -->

<script>
    /* ============================
     Configuración y utilidades
  ============================ */
    const urlApiHacienda = 'api/hacienda/index.php';
    const urlApiEmpresa = 'api/configuracion/info_empresa.php';

    document.addEventListener('DOMContentLoaded', () => {
        inicializarPagina();
    });

    function limpiarDigitos(valor, max = null) {
        const x = (valor || '').replace(/\D+/g, '');
        return max ? x.slice(0, max) : x;
    }

    function sanitizarTexto(valor, max) {
        return (valor || '').replace(/<.*?>/g, '').trim().slice(0, max);
    }

    function establecerOpcionesSelect(elSelect, arreglo, propValor, propTexto, seleccionado) {
        elSelect.innerHTML = '<option value="">Seleccione</option>';
        (arreglo || []).forEach(item => {
            const opt = document.createElement('option');
            opt.value = item[propValor];
            opt.textContent = item[propTexto];
            elSelect.appendChild(opt);
        });
        if (seleccionado !== undefined && seleccionado !== null && seleccionado !== '') {
            elSelect.value = String(seleccionado);
        }
    }

    function inicializarTooltips() {
        const lista = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        lista.forEach(el => {
            try {
                new bootstrap.Tooltip(el);
            } catch {}
        });
    }

    // Lee la respuesta (ok o error) y extrae mensaje si viene en JSON o texto.
    async function leerRespuesta(res) {
        const texto = await res.text().catch(() => '');
        let json = null;
        try {
            json = texto ? JSON.parse(texto) : null;
        } catch {}
        return {
            ok: res.ok,
            status: res.status,
            json,
            texto
        };
    }

    function mostrarMensajeDeError(payload, mensajePorDefecto = 'Ocurrió un error') {
        const msg = (payload.json && (payload.json.message || payload.json.msg || payload.json.error)) || payload.texto || mensajePorDefecto;
        notify(msg, 'error');
    }

    /* ============================
       Inicialización
    ============================ */
    async function inicializarPagina() {
        try {
            await cargarTiposCedula();
            await cargarProvincias();
            await obtenerEmpresa();
            await obtenerCertificado();
            inicializarTooltips();
        } catch (e) {
            console.error(e);
        }
    }

    /* ============================
       Tipos de cédula (Hacienda)
       Espera: POST {accion:'tiposCedula'}
       Respuesta: [{idTipoCedula,codigo,descripcion}]
    ============================ */
    async function cargarTiposCedula(seleccion = '') {
        const res = await fetch(urlApiHacienda, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',

            },
            credentials: 'same-origin',
            body: JSON.stringify({
                accion: 'tiposCedula'
            })
        });
        const payload = await leerRespuesta(res);
        if (!payload.ok) {
            mostrarMensajeDeError(payload, 'No se pudo cargar tipos de cédula');
            return;
        }

        const datos = payload.json || [];
        const mapeo = datos.map(r => ({
            id: String(r.idTipoCedula ?? r.id_tipo_cedula ?? ''),
            codigo: r.codigo,
            descripcion: r.descripcion
        }));
        establecerOpcionesSelect(document.getElementById('selectTipoCedula'), mapeo, 'id', 'descripcion', seleccion);
    }

    /* ============================
       Ubicaciones (Hacienda)
    ============================ */
    async function cargarProvincias(seleccion = '') {
        const res = await fetch(urlApiHacienda, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',

            },
            credentials: 'same-origin',
            body: JSON.stringify({
                accion: 'provincias'
            })
        });
        const payload = await leerRespuesta(res);
        if (!payload.ok) {
            mostrarMensajeDeError(payload, 'No se pudieron cargar provincias');
            return;
        }
        establecerOpcionesSelect(document.getElementById('selectProvincia'), payload.json || [], 'provinciaCod', 'provinciaNombre', seleccion);
    }

    async function cargarCantones(provinciaCod, seleccion = '') {
        const selectCanton = document.getElementById('selectCanton');
        const selectDistrito = document.getElementById('selectDistrito');
        const selectBarrio = document.getElementById('selectBarrio');
        establecerOpcionesSelect(selectCanton, [], 'x', 'y', null);
        establecerOpcionesSelect(selectDistrito, [], 'x', 'y', null);
        establecerOpcionesSelect(selectBarrio, [], 'x', 'y', null);
        if (!provinciaCod) return;

        const res = await fetch(urlApiHacienda, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',

            },
            credentials: 'same-origin',
            body: JSON.stringify({
                accion: 'cantones',
                provinciaCod
            })
        });
        const payload = await leerRespuesta(res);
        if (!payload.ok) {
            mostrarMensajeDeError(payload, 'No se pudieron cargar cantones');
            return;
        }
        establecerOpcionesSelect(selectCanton, payload.json || [], 'cantonCod', 'cantonNombre', seleccion);
    }

    async function cargarDistritos(provinciaCod, cantonCod, seleccion = '') {
        const selectDistrito = document.getElementById('selectDistrito');
        const selectBarrio = document.getElementById('selectBarrio');
        establecerOpcionesSelect(selectDistrito, [], 'x', 'y', null);
        establecerOpcionesSelect(selectBarrio, [], 'x', 'y', null);
        if (!provinciaCod || !cantonCod) return;

        const res = await fetch(urlApiHacienda, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',

            },
            credentials: 'same-origin',
            body: JSON.stringify({
                accion: 'distritos',
                provinciaCod,
                cantonCod
            })
        });
        const payload = await leerRespuesta(res);
        if (!payload.ok) {
            mostrarMensajeDeError(payload, 'No se pudieron cargar distritos');
            return;
        }
        establecerOpcionesSelect(selectDistrito, payload.json || [], 'distritoCod', 'distritoNombre', seleccion);
    }

    async function cargarBarrios(provinciaCod, cantonCod, distritoCod, seleccion = '') {
        const selectBarrio = document.getElementById('selectBarrio');
        establecerOpcionesSelect(selectBarrio, [], 'x', 'y', null);
        if (!provinciaCod || !cantonCod || !distritoCod) return;

        const res = await fetch(urlApiHacienda, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',

            },
            credentials: 'same-origin',
            body: JSON.stringify({
                accion: 'barrios',
                provinciaCod,
                cantonCod,
                distritoCod
            })
        });
        const payload = await leerRespuesta(res);
        if (!payload.ok) {
            mostrarMensajeDeError(payload, 'No se pudieron cargar barrios');
            return;
        }
        establecerOpcionesSelect(selectBarrio, payload.json || [], 'barrioCod', 'barrioNombre', seleccion);
    }

    async function manejarCambioProvincia(provinciaCod) {
        await cargarCantones(provinciaCod, '');
    }
    async function manejarCambioCanton(cantonCod) {
        const provinciaCod = document.getElementById('selectProvincia').value || '';
        await cargarDistritos(provinciaCod, cantonCod, '');
    }
    async function manejarCambioDistrito(distritoCod) {
        const provinciaCod = document.getElementById('selectProvincia').value || '';
        const cantonCod = document.getElementById('selectCanton').value || '';
        await cargarBarrios(provinciaCod, cantonCod, distritoCod, '');
    }

    /* ============================
       Empresa: Obtener / Guardar
    ============================ */
    async function obtenerEmpresa() {
        const res = await fetch(urlApiEmpresa, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',

            },
            credentials: 'same-origin',
            body: JSON.stringify({
                accion: 'obtenerEmpresa'
            })
        });
        const payload = await leerRespuesta(res);
        if (!payload.ok) {
            mostrarMensajeDeError(payload, 'No se pudo obtener la empresa');
            return;
        }
        const d = payload.json || {};

        await cargarTiposCedula(String(d.tipoIdentificacionId ?? ''));

        document.getElementById('inputIdentificacion').value = d.identificacion || '';
        document.getElementById('inputNombreComercial').value = d.nombreComercial || '';
        document.getElementById('inputRazonSocial').value = d.nombreLegal || '';
        document.getElementById('inputCorreo').value = d.correoElectronico || '';
        document.getElementById('inputCodigoArea').value = d.codigoArea || '';
        document.getElementById('inputTelefono1').value = d.telefono || '';
        document.getElementById('inputTelefono2').value = d.telefono2 || '';
        document.getElementById('inputFax').value = d.fax || '';
        document.getElementById('inputApartadoPostal').value = d.apartadoPostal || '';
        document.getElementById('textareaDireccion').value = d.direccionExacta || '';
        document.getElementById('inputNumeroEmpresa').value = d.empresaId || '';
        document.getElementById('inputEstadoCuenta').value = (d.estado == 1 ? 'Activo' : 'Inactivo');

        document.getElementById('chkConvertirTiquete').checked = !!d.convertirTiqueteAFactura;
        document.getElementById('chkRegimenAgropecuario').checked = !!d.regimenAgropecuario;
        document.getElementById('chkCargoAutomatico').checked = !!d.cargoAutomaticoPlan;
        document.getElementById('chkVistoBueno').checked = !!d.vistoBuenoFacturas;
        document.getElementById('chkReporteMensual').checked = !!d.reporteMensual;

        if (d.logoUrl) {
            document.getElementById('imgLogo').src = d.logoUrl;
        } else {
            document.getElementById('imgLogo').src = 'https://placehold.jp/3d4070/ffffff/240x240.png?text=logo';
        }

        const prov = d.provinciaCod || '';
        const canton = d.cantonCod || '';
        const dist = d.distritoCod || '';
        const barrio = d.barrioCod || '';

        await cargarProvincias(prov);
        if (prov) {
            await cargarCantones(prov, canton);
            if (canton) {
                await cargarDistritos(prov, canton, dist);
                if (dist) await cargarBarrios(prov, canton, dist, barrio);
            }
        }
    }

    async function manejarSubmitEmpresa(ev) {
        ev.preventDefault();

        const cuerpo = {
            tipoIdentificacionId: document.getElementById('selectTipoCedula').value || null,
            identificacion: sanitizarTexto(document.getElementById('inputIdentificacion').value, 20),
            nombreComercial: sanitizarTexto(document.getElementById('inputNombreComercial').value, 200),
            nombreLegal: sanitizarTexto(document.getElementById('inputRazonSocial').value, 200),
            correoElectronico: sanitizarTexto(document.getElementById('inputCorreo').value, 255),
            codigoArea: limpiarDigitos(document.getElementById('inputCodigoArea').value, 4),
            telefono: limpiarDigitos(document.getElementById('inputTelefono1').value, 25),
            telefono2: limpiarDigitos(document.getElementById('inputTelefono2').value, 25),
            fax: limpiarDigitos(document.getElementById('inputFax').value, 25),
            apartadoPostal: sanitizarTexto(document.getElementById('inputApartadoPostal').value, 50),
            direccionExacta: sanitizarTexto(document.getElementById('textareaDireccion').value, 300),

            provinciaCod: document.getElementById('selectProvincia').value || null,
            cantonCod: document.getElementById('selectCanton').value || null,
            distritoCod: document.getElementById('selectDistrito').value || null,
            barrioCod: document.getElementById('selectBarrio').value || null,

            convertirTiqueteAFactura: document.getElementById('chkConvertirTiquete').checked,
            regimenAgropecuario: document.getElementById('chkRegimenAgropecuario').checked,
            cargoAutomaticoPlan: document.getElementById('chkCargoAutomatico').checked,
            vistoBuenoFacturas: document.getElementById('chkVistoBueno').checked,
            reporteMensual: document.getElementById('chkReporteMensual').checked
        };

        if (cuerpo.codigoArea && !/^\d{1,4}$/.test(cuerpo.codigoArea)) {
            notify('Código de área inválido (solo dígitos, máx. 4).', 'error');
            return false;
        }

        const res = await fetch(urlApiEmpresa, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',

            },
            credentials: 'same-origin',
            body: JSON.stringify({
                accion: 'guardarEmpresa',
                empresa: cuerpo
            })
        });
        const payload = await leerRespuesta(res);
        if (!payload.ok) {
            mostrarMensajeDeError(payload, 'Error al guardar la empresa');
            return false;
        }

        notify('Información de empresa guardada correctamente.', 'success');
        await obtenerEmpresa();
        return false;
    }

    /* ============================
       Logo
    ============================ */
    async function manejarCambioArchivoLogo(inputEl) {
        const archivo = inputEl.files?.[0];
        if (!archivo) return;

        const tiposOk = ['image/jpeg', 'image/jpg', 'image/png'];
        if (!tiposOk.includes((archivo.type || '').toLowerCase())) {
            notify('Solo se permiten JPG/JPEG/PNG.', 'error');
            inputEl.value = '';
            return;
        }
        if (archivo.size > 1048576) {
            notify('El logo supera el tamaño máximo (1 MB).', 'error');
            inputEl.value = '';
            return;
        }

        const base64 = await new Promise((res, rej) => {
            const fr = new FileReader();
            fr.onload = () => res(fr.result?.toString() || '');
            fr.onerror = rej;
            fr.readAsDataURL(archivo);
        });

        const dimensiones = await new Promise((res, rej) => {
            const img = new Image();
            img.onload = () => res({
                w: img.naturalWidth,
                h: img.naturalHeight
            });
            img.onerror = rej;
            img.src = base64;
        });
        if (dimensiones.w > 1366) {
            notify('Ancho máximo permitido: 1366 px.', 'error');
            inputEl.value = '';
            return;
        }

        const res = await fetch(urlApiEmpresa, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',

            },
            credentials: 'same-origin',
            body: JSON.stringify({
                accion: 'logoSubir',
                fileName: archivo.name,
                contentBase64: base64,
                contentType: archivo.type,
                sizeBytes: archivo.size
            })
        });
        const payload = await leerRespuesta(res);
        if (!payload.ok) {
            mostrarMensajeDeError(payload, 'Error al subir el logo');
            inputEl.value = '';
            return;
        }

        notify('Logo actualizado.', 'success');
        inputEl.value = '';
        await obtenerEmpresa();
    }

    async function manejarClickEliminarLogo() {
        const res = await fetch(urlApiEmpresa, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',

            },
            credentials: 'same-origin',
            body: JSON.stringify({
                accion: 'logoEliminar'
            })
        });
        const payload = await leerRespuesta(res);
        if (!payload.ok) {
            mostrarMensajeDeError(payload, 'Error al eliminar el logo');
            return;
        }

        document.getElementById('imgLogo').src = 'https://placehold.jp/3d4070/ffffff/240x240.png?text=logo';
        notify('Logo eliminado correctamente.', 'success');
        await obtenerEmpresa();
    }

    /* ============================
       Certificado: Obtener / Guardar / Eliminar llave
    ============================ */
    async function obtenerCertificado() {
        const res = await fetch(urlApiEmpresa, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',

            },
            credentials: 'same-origin',
            body: JSON.stringify({
                accion: 'obtenerCertificado'
            })
        });
        const payload = await leerRespuesta(res);
        if (!payload.ok) {
            mostrarMensajeDeError(payload, 'No se pudo obtener el certificado');
            return;
        }

        const d = payload.json || {};
        const vacio = Object.keys(d).length === 0;

        document.getElementById('inputCertUsuario').value = vacio ? '' : (d.usuarioAtv || '');
        document.getElementById('inputCertContrasena').value = vacio ? '' : (d.contrasenaAtv || '');
        document.getElementById('inputCertPin').value = vacio ? '' : (d.pinLlaveCripto || '');
        document.getElementById('inputCertCreacion').value = vacio ? '' : (d.fechaCreacion || '');
        document.getElementById('inputCertRegistro').value = vacio ? '' : (d.fechaRegistro || '');
        document.getElementById('inputCertVencimiento').value = vacio ? '' : (d.fechaVencimiento || '');
        document.getElementById('inputCertLlaveNombre').value = vacio ? '' : (d.llaveNombreOriginal || '');
        document.getElementById('inputCertLlaveMime').value = vacio ? '' : (d.llaveMime || '');
        document.getElementById('inputCertLlaveTam').value = vacio ? '' : (d.llaveSize || '');
        document.getElementById('inputCertLlaveRuta').value = vacio ? '' : (d.llavePathEnc || '');
        document.getElementById('enlaceCertLlaveAbrir').href = vacio ? '#' : (d.llavePathEnc || '#');
    }

    async function manejarSubmitCertificado(ev) {
        ev.preventDefault();

        const cuerpo = {
            usuarioAtv: document.getElementById('inputUsuarioAtvNuevo').value.trim(),
            contrasenaAtv: document.getElementById('inputContrasenaAtvNuevo').value,
            pinLlave: document.getElementById('inputPinLlaveNuevo').value
            // sin fechas (se manejan en backend si aplica)
        };

        const elArchivo = document.getElementById('inputArchivoLlaveNuevo');
        const archivo = elArchivo.files?.[0] || null;
        if (archivo) {
            const ext = (archivo.name.split('.').pop() || '').toLowerCase();
            if (!['pun', 'pen'].includes(ext)) {
                notify('La llave debe ser .pun o .pen', 'error');
                return false;
            }
            cuerpo.llaveNombre = archivo.name;
            cuerpo.llaveMime = archivo.type || 'application/octet-stream';
            cuerpo.llaveSize = archivo.size || 0;
            cuerpo.llaveBase64 = await new Promise((res, rej) => {
                const fr = new FileReader();
                fr.onload = () => res(fr.result?.toString() || '');
                fr.onerror = rej;
                fr.readAsDataURL(archivo);
            });
        }

        const res = await fetch(urlApiEmpresa, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',

            },
            credentials: 'same-origin',
            body: JSON.stringify({
                accion: 'guardarCertificado',
                cert: cuerpo
            })
        });
        const payload = await leerRespuesta(res);
        if (!payload.ok) {
            mostrarMensajeDeError(payload, 'Error al guardar el certificado');
            return false;
        }

        notify('Certificado guardado correctamente.', 'success');
        if (elArchivo) elArchivo.value = '';
        document.getElementById('inputContrasenaAtvNuevo').value = '';
        document.getElementById('inputPinLlaveNuevo').value = '';
        await obtenerCertificado();
        return false;
    }

    async function eliminarLlaveCripto() {
        const res = await fetch(urlApiEmpresa, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',

            },
            credentials: 'same-origin',
            body: JSON.stringify({
                accion: 'eliminarLlaveCripto'
            })
        });
        const payload = await leerRespuesta(res);
        if (!payload.ok) {
            mostrarMensajeDeError(payload, 'Error al eliminar la llave');
            return;
        }

        notify('Llave eliminada correctamente.', 'success');
        await obtenerCertificado();
    }
</script>