<!DOCTYPE html>
<html lang="es" dir="ltr" data-nav-layout="vertical" data-vertical-style="overlay" data-theme-mode="light"
    data-header-styles="light" data-menu-styles="light" data-toggled="close">

<head>
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= $config->site_name ?> | Iniciar Sesión</title>
    <meta name="Author" content="SISTEC CR">

    <link rel="icon" href="<?= DOMAIN ?>/assets/template/images/brand-logos/favicon.ico" type="image/x-icon">
    <script src="<?= DOMAIN ?>/assets/template/js/authentication-main.js"></script>
    <link id="style" href="<?= DOMAIN ?>/assets/template/libs/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= DOMAIN ?>/assets/template/css/styles.css" rel="stylesheet">
    <link href="<?= DOMAIN ?>/assets/template/css/icons.css" rel="stylesheet">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Bootstrap JS -->
    <script src="<?= DOMAIN ?>/assets/template/libs/bootstrap/js/bootstrap.bundle.min.js"></script>

    <?php if (RECAPTCHA_ACTIVE) { ?>
        <!-- Recaptcha API v2 -->
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <?php } ?>

    <style>
        .select-empresa-item {
            cursor: pointer;
        }

        .select-empresa-item.active {
            border: 1px solid var(--primary);
        }
    </style>
</head>

<body>
    <div class="authentication-background">
        <div class="container-lg">
            <div class="snow-container"></div>
            <div class="row justify-content-center align-items-center authentication authentication-basic h-100">
                <div class="col-xl-11">
                    <div class="row justify-content-center">
                        <div class="col-xxl-5 col-xl-5 col-lg-5 col-md-6 col-sm-8 col-12">
                            <div class="my-5 d-flex justify-content-center">
                                <a href="#">
                                    <img src="<?= $config->url_desktop_logo ?>" alt="" class="authentication-brand desktop-logo">
                                    <img src="<?= $config->url_desktop_logo ?>" alt="img" class="authentication-brand desktop-dark">
                                </a>
                            </div>

                            <div class="card custom-card my-4">
                                <form class="card-body p-5" id="LoginForm">
                                    <p class="h4 mb-2 fw-semibold">Inicio de Sesión</p>
                                    <p class="mb-4 text-muted fw-normal">Bienvenidos a Sistec FE.</p>

                                    <div class="row gy-3">
                                        <div class="col-xl-12">
                                            <label for="Username" class="form-label text-default">Identificación</label>
                                            <input type="text" class="form-control form-control-lg" id="Username"
                                                placeholder="123456789" name="username" autocomplete="username" autofocus required>
                                        </div>

                                        <div class="col-xl-12 mb-2">
                                            <label for="CurrentPassword" class="form-label text-default d-block">
                                                Contraseña
                                                <a href="#" class="float-end  link-danger op-5 fw-medium fs-12">
                                                    ¿Olvidaste la contraseña?
                                                </a>
                                            </label>
                                            <div class="position-relative">
                                                <input type="password" class="form-control form-control-lg" name="current-password"
                                                    id="CurrentPassword" placeholder="Ingresa tu contraseña" autocomplete="current-password" required>
                                                <a href="javascript:void(0);" class="show-password-button text-muted"
                                                    onclick="createpassword('CurrentPassword',this)" id="button-addon2">
                                                    <i class="ri-eye-off-line align-middle"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <?php if (RECAPTCHA_ACTIVE) { ?>
                                        <center class="mb-2">
                                            <div class="g-recaptcha" data-sitekey="<?= RECAPTCHA_SITE_KEY; ?>"></div>
                                        </center>
                                    <?php } ?>

                                    <div class="d-grid mt-4">
                                        <button class="btn btn-lg btn-primary" type="submit">Ingresar</button>
                                    </div>
                                </form>
                            </div>

                            <!-- Modal Selección de Empresa -->
                            <div class="modal fade" id="SelectEmpresaModal" tabindex="-1" aria-labelledby="SelectEmpresaLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="SelectEmpresaLabel">Selecciona la empresa</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div id="EmpresasList" class="list-group"></div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                                            <button type="button" class="btn btn-primary" id="ConfirmEmpresaBtn" disabled>Continuar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /Modal -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function() {
            const loginForm = document.getElementById("LoginForm");
            const selectEmpresaModal = new bootstrap.Modal(document.getElementById('SelectEmpresaModal'));
            let empresasOpciones = [];
            let empresaSeleccionada = null;
            let credencialesCache = {
                identificacion: "",
                pass: "",
                recaptcha: ""
            };

            async function DoLogin(payload) {
                const loadingAlert = Swal.fire({
                    title: 'Iniciando sesión...',
                    html: '<div class="spinner-border" role="status"><span class="visually-hidden">Cargando...</span></div>',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                });

                try {
                    const resp = await fetch("<?= DOMAIN ?>/api/auth/login.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify(payload)
                    });

                    // Aun si resp.status HTTP != 200, el backend regresa JSON con 'message'
                    const result = await resp.json();
                    Swal.close();

                    if (result.status === 200) {
                        // Login OK
                        window.location.href = "<?= DOMAIN ?>/";
                        return;
                    }

                    if (result.status === 206 && result.data && Array.isArray(result.data.opciones)) {
                        <?php if (RECAPTCHA_ACTIVE && !empty(RECAPTCHA_SITE_KEY)) { ?>
                            grecaptcha.reset();
                        <?php } ?>
                        // Necesita selección de empresa
                        empresasOpciones = result.data.opciones;
                        renderEmpresas(empresasOpciones);
                        selectEmpresaModal.show();
                        return;
                    }

                    // Cualquier otro status -> mostrar mensaje recibido
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: result.message || "Error al iniciar sesión"
                    });

                    <?php if (RECAPTCHA_ACTIVE && !empty(RECAPTCHA_SITE_KEY)) { ?>
                        grecaptcha.reset();
                    <?php } ?>

                } catch (e) {
                    Swal.close();
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error de conexión'
                    });
                    <?php if (RECAPTCHA_ACTIVE && !empty(RECAPTCHA_SITE_KEY)) { ?>
                        grecaptcha.reset();
                    <?php } ?>
                }
            }

            function renderEmpresas(lista) {
                const cont = document.getElementById("EmpresasList");
                cont.innerHTML = "";
                empresaSeleccionada = null;
                document.getElementById("ConfirmEmpresaBtn").disabled = true;

                lista.forEach(op => {
                    const a = document.createElement("a");
                    a.href = "javascript:void(0)";
                    a.className = "list-group-item list-group-item-action d-flex justify-content-between align-items-center select-empresa-item";
                    a.dataset.id_usuario = op.id_usuario;
                    a.innerHTML = `
                        <div>
                            <div class="fw-semibold">${op.empresa_nombre}</div>
                            <div class="small">Rol: ${op.rol}</div>
                        </div>
                        <div><i class="ri-arrow-right-s-line fs-4"></i></div>
                    `;
                    a.addEventListener("click", () => {
                        document.querySelectorAll(".select-empresa-item").forEach(el => el.classList.remove("active"));
                        a.classList.add("active");
                        empresaSeleccionada = parseInt(a.dataset.id_usuario);
                        document.getElementById("ConfirmEmpresaBtn").disabled = false;
                    });
                    cont.appendChild(a);
                });
            }

            document.getElementById("ConfirmEmpresaBtn").addEventListener("click", async () => {
                if (!empresaSeleccionada) return;

                const payload = {
                    identificacion: credencialesCache.identificacion,
                    pass: credencialesCache.pass,
                    id_usuario: empresaSeleccionada
                };
                <?php if (RECAPTCHA_ACTIVE && !empty(RECAPTCHA_SITE_KEY)) { ?>
                    payload.recaptcha = credencialesCache.recaptcha;
                <?php } ?>

                selectEmpresaModal.hide();
                await DoLogin(payload);
            });

            loginForm.addEventListener("submit", async (e) => {
                e.preventDefault();
                const fd = new FormData(loginForm);
                const payload = {
                    identificacion: fd.get("username"),
                    pass: fd.get("current-password"),
                };
                <?php if (RECAPTCHA_ACTIVE && !empty(RECAPTCHA_SITE_KEY)) { ?>
                    payload.recaptcha = grecaptcha.getResponse();
                <?php } ?>

                credencialesCache = {
                    ...payload
                };

                await DoLogin(payload);
            });
        })();
    </script>

    <!-- Show Password JS -->
    <script src="<?= DOMAIN ?>/assets/template/js/show-password.js"></script>
</body>

</html>