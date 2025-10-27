<!-- Datos del usuario -->
<div class="row g-3" id="miInformacionUsuario">
    <div class="col-12">
        <div class="card custom-card shadow-sm">
            <div class="card-body">
                <h6 class="text-uppercase mb-3"><strong>Datos personales y de contacto</strong></h6>
                <div class="row g-4">
                    <!-- Col izq: avatar -->
                    <div class="col-12 col-xl-3">
                        <div class="border rounded p-3 h-100">
                            <div class="text-center">
                                <div class="ratio ratio-1x1 rounded-circle border bg-light d-flex align-items-center justify-content-center mb-3" style="overflow:hidden;">
                                    <img id="fotoPerfilPreview" src="assets/img/perfil.png" alt="Foto de perfil" class="img-fluid">
                                </div>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="ri-upload-2-line"></i></span>
                                    <input type="file" id="fotoPerfilInput" class="form-control" accept=".jpg,.jpeg,.png">
                                    <button type="button" class="btn btn-outline-danger" id="btnEliminarFoto" data-bs-toggle="tooltip" title="Eliminar foto">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </div>
                                <p class="small text-muted mt-2 mb-0">
                                    <i class="ri-information-line me-1"></i>
                                    Extensiones: jpg, jpeg, png. Máx 1 MB.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Col der: formulario -->
                    <div class="col-12 col-xl-9">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="cedula" class="form-label"><i class="ri-id-card-line me-1"></i> Cédula</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="ri-hashtag"></i></span>
                                    <input type="text" id="cedula" class="form-control" placeholder="Ej. 1-2345-6789" disabled>
                                </div>
                                <div class="form-text">La cédula no puede modificarse.</div>
                            </div>

                            <div class="col-md-8">
                                <label for="nombreCompleto" class="form-label"><i class="ri-user-3-line me-1"></i> Nombre completo</label>
                                <input type="text" id="nombreCompleto" class="form-control" placeholder="Nombre y apellidos">
                            </div>

                            <div class="col-md-6">
                                <label for="correoElectronico" class="form-label"><i class="ri-mail-line me-1"></i> Correo</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="ri-at-line"></i></span>
                                    <input type="email" id="correoElectronico" class="form-control" placeholder="correo@dominio.com">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="direccionExacta" class="form-label"><i class="ri-map-pin-line me-1"></i> Dirección</label>
                                <input type="text" id="direccionExacta" class="form-control" placeholder="Dirección exacta">
                            </div>

                            <div class="col-md-4">
                                <label for="telefonoFijo" class="form-label"><i class="ri-phone-line me-1"></i> Teléfono fijo</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="ri-phone-line"></i></span>
                                    <input type="tel" id="telefonoFijo" class="form-control" placeholder="Ej. 2222-2222">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <label for="telefonoExtension" class="form-label"><i class="ri-hashtag me-1"></i> Ext</label>
                                <input type="text" id="telefonoExtension" class="form-control" placeholder="Ext.">
                            </div>

                            <div class="col-md-3">
                                <label for="celular" class="form-label"><i class="ri-smartphone-line me-1"></i> Celular</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="ri-smartphone-line"></i></span>
                                    <input type="tel" id="celular" class="form-control" placeholder="Ej. 8888-8888">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label for="fax" class="form-label"><i class="ri-printer-line me-1"></i> Fax</label>
                                <input type="text" id="fax" class="form-control" placeholder="Fax">
                            </div>

                            <div class="col-12 d-flex justify-content-end">
                                <button type="button" class="btn btn-primary" id="btnGuardarPerfil">
                                    <i class="ri-save-3-line me-1"></i> Guardar cambios
                                </button>
                            </div>
                        </div>
                    </div>
                </div> <!-- row -->
            </div>
        </div>
    </div>

    <!-- Seguridad: contraseña -->
    <div class="col-12 col-xl-6">
        <div class="card custom-card shadow-sm h-100">
            <div class="card-body">
                <h6 class="text-uppercase mb-3"><strong>Cambiar contraseña</strong></h6>
                <div class="row g-3">
                    <div class="col-12">
                        <label for="pwdActual" class="form-label"><i class="ri-lock-line me-1"></i> Contraseña actual</label>
                        <input type="password" id="pwdActual" class="form-control" placeholder="••••••••">
                    </div>
                    <div class="col-12 col-md-6">
                        <label for="pwdNueva" class="form-label"><i class="ri-lock-password-line me-1"></i> Nueva contraseña</label>
                        <input type="password" id="pwdNueva" class="form-control" placeholder="••••••••">
                    </div>
                    <div class="col-12 col-md-6">
                        <label for="pwdConfirmar" class="form-label"><i class="ri-check-line me-1"></i> Confirmar</label>
                        <input type="password" id="pwdConfirmar" class="form-control" placeholder="••••••••">
                    </div>
                    <div class="col-12">
                        <small class="text-muted">
                            <i class="ri-information-line me-1"></i> Mínimo 8 caracteres, incluir números y mayúsculas.
                        </small>
                    </div>
                    <div class="col-12 d-flex justify-content-end">
                        <button type="button" class="btn btn-outline-primary" id="btnGuardarPwd">
                            <i class="ri-save-3-line me-1"></i> Guardar contraseña
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Seguridad: pregunta -->
    <div class="col-12 col-xl-6">
        <div class="card custom-card shadow-sm h-100">
            <div class="card-body">
                <h6 class="text-uppercase mb-3"><strong>Pregunta de seguridad</strong></h6>
                <div class="row g-3">
                    <div class="col-12">
                        <label for="preguntaSeguridad" class="form-label"><i class="ri-question-line me-1"></i> Pregunta</label>
                        <select id="preguntaSeguridad" class="form-select">
                            <option value="" selected>Seleccione</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="respuestaSeguridad" class="form-label"><i class="ri-edit-2-line me-1"></i> Respuesta</label>
                        <input type="text" id="respuestaSeguridad" class="form-control" placeholder="Escriba su respuesta">
                    </div>
                    <div class="col-12">
                        <label for="pwdActualPregunta" class="form-label"><i class="ri-lock-line me-1"></i> Contraseña actual</label>
                        <input type="password" id="pwdActualPregunta" class="form-control" placeholder="••••••••">
                    </div>
                    <div class="col-12 d-flex justify-content-end">
                        <button type="button" class="btn btn-outline-primary" id="btnGuardarPregunta">
                            <i class="ri-save-3-line me-1"></i> Guardar pregunta
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Seguridad -->
</div>
<!--End::row-1 -->

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tooltips
        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));

        const apiUrl = 'api/configuracion/mi_informacion_usuario.php';

        // Refs
        const imgPreview = document.getElementById('fotoPerfilPreview');
        const inputFile = document.getElementById('fotoPerfilInput');
        const btnEliminarFoto = document.getElementById('btnEliminarFoto');

        const inpCedula = document.getElementById('cedula');
        const inpNombreCompleto = document.getElementById('nombreCompleto');
        const inpCorreoElectronico = document.getElementById('correoElectronico');
        const inpDireccionExacta = document.getElementById('direccionExacta');
        const inpTelefonoFijo = document.getElementById('telefonoFijo');
        const inpTelefonoExtension = document.getElementById('telefonoExtension');
        const inpCelular = document.getElementById('celular');
        const inpFax = document.getElementById('fax');
        const btnGuardarPerfil = document.getElementById('btnGuardarPerfil');

        // Password
        const inpPwdActual = document.getElementById('pwdActual');
        const inpPwdNueva = document.getElementById('pwdNueva');
        const inpPwdConfirmar = document.getElementById('pwdConfirmar');
        const btnGuardarPwd = document.getElementById('btnGuardarPwd');

        // Pregunta seguridad
        const selPregunta = document.getElementById('preguntaSeguridad');
        const inpRespuesta = document.getElementById('respuestaSeguridad');
        const inpPwdActualPregunta = document.getElementById('pwdActualPregunta');
        const btnGuardarPregunta = document.getElementById('btnGuardarPregunta');

        // SweetAlert helpers
        function swalOk(msg) {
            Swal.fire({
                icon: 'success',
                title: 'Listo',
                text: msg,
                timer: 1600,
                showConfirmButton: false
            });
        }

        function swalError(msg) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: msg
            });
        }

        // ------------------ Carga inicial ------------------
        async function cargarPerfil() {
            try {
                const res = await fetch(apiUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        accion: 'perfil'
                    })
                });
                const d = await res.json().catch(() => ({}));
                if (!res.ok || d.status !== 200) throw new Error(d.message || 'No se pudo cargar el perfil');

                inpCedula.value = d.data?.identificacion || '';
                inpNombreCompleto.value = d.data?.nombreCompleto || '';
                inpCorreoElectronico.value = d.data?.correoElectronico || '';
                inpDireccionExacta.value = d.data?.direccionExacta || '';
                inpTelefonoFijo.value = d.data?.telefonoFijo || '';
                inpTelefonoExtension.value = d.data?.telefonoExtension || '';
                inpCelular.value = d.data?.celular || '';
                inpFax.value = d.data?.fax || '';

                if (d.data?.fotoUrl) imgPreview.src = d.data.fotoUrl;

                await cargarPreguntas(d.data?.preguntaSeguridadId || '');
            } catch (e) {
                swalError(e.message || 'Error cargando perfil');
            }
        }

        async function cargarPreguntas(selectedId) {
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
                const r = await res.json().catch(() => ({}));
                if (!res.ok || r.status !== 200) throw new Error(r.message || 'No se pudieron cargar las preguntas');

                const list = r.data || [];
                selPregunta.innerHTML = '<option value="">Seleccione</option>' +
                    list.map(p => `<option value="${p.preguntaSeguridadId}">${p.texto}</option>`).join('');

                if (selectedId) selPregunta.value = String(selectedId);
            } catch (e) {
                swalError(e.message || 'Error cargando preguntas');
            }
        }

        // ------------------ Guardar perfil ------------------
        btnGuardarPerfil?.addEventListener('click', async () => {
            try {
                const payload = {
                    accion: 'guardarPerfil',
                    correoElectronico: (inpCorreoElectronico.value || '').trim(),
                    nombreCompleto: (inpNombreCompleto.value || '').trim(),
                    direccionExacta: (inpDireccionExacta.value || '').trim(),
                    telefonoFijo: (inpTelefonoFijo.value || '').trim(),
                    telefonoExtension: (inpTelefonoExtension.value || '').trim(),
                    celular: (inpCelular.value || '').trim(),
                    fax: (inpFax.value || '').trim()
                };

                const res = await fetch(apiUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });
                const r = await res.json().catch(() => ({}));
                if (!res.ok || r.status !== 200) throw new Error(r.message || 'No se pudo guardar el perfil');

                swalOk('Perfil actualizado.');
            } catch (e) {
                swalError(e.message || 'No se pudo guardar el perfil.');
            }
        });

        // ------------------ Foto: subir ------------------
        inputFile?.addEventListener('change', async function() {
            const file = this.files?.[0];
            if (!file) return;
            const allowed = ['image/jpeg', 'image/png', 'image/jpg'];
            if (!allowed.includes(file.type) || file.size > 1024 * 1024) {
                this.value = '';
                swalError('Archivo inválido. Máx 1 MB y tipo JPG/PNG.');
                return;
            }
            const reader = new FileReader();
            reader.onload = async e => {
                const dataUrl = e.target.result; // data:image/xxx;base64,....
                imgPreview.src = dataUrl;

                try {
                    const res = await fetch(apiUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            accion: 'foto',
                            contentBase64: String(dataUrl) // el backend acepta dataURL o base64 crudo
                        })
                    });
                    const r = await res.json().catch(() => ({}));
                    if (!res.ok || r.status !== 200) throw new Error(r.message || 'No se pudo guardar la foto');

                    swalOk('Foto actualizada.');
                    if (r.data?.fotoUrl) imgPreview.src = r.data.fotoUrl; // usar ruta pública ya almacenada
                } catch (e) {
                    swalError(e.message || 'No se pudo guardar la foto.');
                }
            };
            reader.readAsDataURL(file);
        });

        // ------------------ Foto: eliminar ------------------
        btnEliminarFoto?.addEventListener('click', async () => {
            try {
                const res = await fetch(apiUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        accion: 'eliminarFoto'
                    })
                });
                const r = await res.json().catch(() => ({}));
                if (!res.ok || r.status !== 200) throw new Error(r.message || 'No se pudo eliminar la foto');

                imgPreview.src = 'assets/img/perfil.png';
                if (inputFile) inputFile.value = '';
                swalOk('Foto eliminada.');
            } catch (e) {
                swalError(e.message || 'No se pudo eliminar la foto.');
            }
        });

        // ------------------ Cambiar contraseña ------------------
        btnGuardarPwd?.addEventListener('click', async () => {
            try {
                const actual = inpPwdActual.value || '';
                const nueva = inpPwdNueva.value || '';
                const confirmar = inpPwdConfirmar.value || '';

                if (!actual || !nueva || !confirmar) {
                    swalError('Complete todos los campos de contraseña.');
                    return;
                }
                if (nueva.length < 8 || nueva !== confirmar) {
                    swalError('Valide la nueva contraseña (mín. 8) y la confirmación.');
                    return;
                }

                const res = await fetch(apiUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        accion: 'guardarPwd',
                        contrasenaActual: actual,
                        contrasenaNueva: nueva
                    })
                });
                const r = await res.json().catch(() => ({}));
                if (!res.ok || r.status !== 200) throw new Error(r.message || 'No se pudo cambiar la contraseña');

                swalOk('Contraseña actualizada.');
                inpPwdActual.value = '';
                inpPwdNueva.value = '';
                inpPwdConfirmar.value = '';
            } catch (e) {
                swalError(e.message || 'Error cambiando contraseña.');
            }
        });

        // ------------------ Guardar pregunta seguridad ------------------
        btnGuardarPregunta?.addEventListener('click', async () => {
            try {
                const preguntaId = parseInt(selPregunta.value || '0', 10);
                const respuesta = (inpRespuesta.value || '');
                const pwd = (inpPwdActualPregunta.value || '');
                if (!preguntaId) {
                    swalError('Seleccione una pregunta.');
                    return;
                }
                if (!pwd) {
                    swalError('Ingrese su contraseña actual.');
                    return;
                }

                const res = await fetch(apiUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        accion: 'guardarPregunta',
                        preguntaSeguridadId: preguntaId,
                        respuestaNueva: respuesta,
                        contrasenaActual: pwd
                    })
                });
                const r = await res.json().catch(() => ({}));
                if (!res.ok || r.status !== 200) throw new Error(r.message || 'No se pudo guardar la pregunta');

                swalOk('Pregunta/Respuesta actualizadas.');
                inpRespuesta.value = '';
                inpPwdActualPregunta.value = '';
            } catch (e) {
                swalError(e.message || 'Error guardando pregunta.');
            }
        });

        // Inicio
        (async () => {
            await cargarPerfil();
        })();
    });
</script>