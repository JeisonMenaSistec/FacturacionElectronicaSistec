<!DOCTYPE html>
<html lang="en" dir="ltr" data-nav-layout="vertical" data-vertical-style="overlay" data-theme-mode="light"
    data-header-styles="light" data-menu-styles="light" data-toggled="close">

<head>

    <!-- Meta Data -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title> Error - <?=$config->site_name?> </title>
    <meta name="Description" content="Bootstrap Responsive Admin Web Dashboard HTML5 Template">
    <meta name="Author" content="Spruko Technologies Private Limited">
    <meta name="keywords"
        content="dashboard template,dashboard html,bootstrap admin,dashboard admin,admin template,sales dashboard,crypto dashboard,projects dashboard,html template,html,html css,admin dashboard template,html css bootstrap,dashboard html css,pos system,bootstrap dashboard">
    <!-- Favicon -->
    <link rel="icon" href="<?= DOMAIN ?>/assets/template/images/brand-logos/favicon.ico" type="image/x-icon">

    <!-- Main Theme Js -->
    <script src="<?= DOMAIN ?>/assets/template/js/authentication-main.js"></script>

    <!-- Bootstrap Css -->
    <link id="style" href="<?= DOMAIN ?>/assets/template/libs/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Style Css -->
    <link href="<?= DOMAIN ?>/assets/template/css/styles.css" rel="stylesheet">

    <!-- Icons Css -->
    <link href="<?= DOMAIN ?>/assets/template/css/icons.css" rel="stylesheet">


</head>

<body>

    <div class="page error-bg">
        <!-- Start::error-page -->
        <div class="error-page">
            <div class="container">
                <div class="my-auto">
                    <div class="row justify-content-center">
                        <div class="col-xl-7">
                            <div class="bg-white p-5 error-img text-center rounded primary-dash-border">
                                <div class="row align-items-center mx-0 g-0">
                                    <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                        <div class="row align-items-center justify-content-center text-center h-100">
                                            <div class="col-xl-10 col-lg-10 col-md-12 col-12">
                                                <img src="<?= $config->url_desktop_logo ?>" alt="" class="error-main-img">
                                                <p class="error-text mb-4">403</p>
                                                <p class="fs-5 fw-medium mb-2">Acceso Prohibido</p>
                                                <p class="fs-15 mb-4 text-muted">No tienes permisos para acceder a esta página. 
                                                    Si crees que esto es un error, contacta con el administrador del sistema.</p>
                                                <a href="<?= DOMAIN ?>" class="btn btn-lg btn-w-lg mb-2 border-0 btn-primary me-3">
                                                    Página Principal
                                                </a> 
                                                <a href="<?= DOMAIN ?>" class="btn btn-lg btn-w-lg mb-2 border-0 btn-secondary">
                                                    Contactar Soporte
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="<?= DOMAIN ?>/assets/template/libs/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>

</html>