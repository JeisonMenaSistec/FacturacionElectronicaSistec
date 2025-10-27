<!DOCTYPE html>
<html lang="en" dir="ltr" data-nav-layout="vertical" data-theme-mode="light" data-header-styles="light"
    data-width="fullwidth" data-menu-styles="dark" data-toggled="close">

<head>
    <!-- Meta Data -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>SISTEC FE</title>
    <meta name="Description" content="Facturacion Electronica">
    <meta name="Author" content="SISTEC CR">
    <!-- Favicon -->
    <link rel="icon" href="<?= DOMAIN ?>/assets/template/images/brand-logos/favicon.ico" type="image/x-icon">

    <!-- Choices JS -->
    <script src="<?= DOMAIN ?>/assets/template/libs/choices.js/public/assets/scripts/choices.min.js"></script>


    <!-- Bootstrap Css -->
    <link id="style" href="<?= DOMAIN ?>/assets/template/libs/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Style Css -->
    <link href="<?= DOMAIN ?>/assets/template/css/styles.css" rel="stylesheet">

    <!-- Icons Css -->
    <link href="<?= DOMAIN ?>/assets/template/css/icons.css" rel="stylesheet">

    <!-- Node Waves Css -->
    <link href="<?= DOMAIN ?>/assets/template/libs/node-waves/waves.min.css" rel="stylesheet">

    <!-- Simplebar Css -->
    <link href="<?= DOMAIN ?>/assets/template/libs/simplebar/simplebar.min.css" rel="stylesheet">

    <!-- Color Picker Css -->
    <link rel="stylesheet" href="<?= DOMAIN ?>/assets/template/libs/flatpickr/flatpickr.min.css">
    <link rel="stylesheet" href="<?= DOMAIN ?>/assets/template/libs/simonwep/pickr/themes/nano.min.css">

    <!-- Choices Css -->
    <link rel="stylesheet" href="<?= DOMAIN ?>/assets/template/libs/choices.js/public/assets/styles/choices.min.css">

    <!-- FlatPickr CSS -->
    <link rel="stylesheet" href="<?= DOMAIN ?>/assets/template/libs/flatpickr/flatpickr.min.css">

    <!-- Auto Complete CSS -->
    <link rel="stylesheet" href="<?= DOMAIN ?>/assets/template/libs/tarekraafat/autocomplete.js/css/autoComplete.css">


    <!-- Alertas -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Estilos Custom -->
    <link rel="stylesheet" href="<?= DOMAIN ?>/assets/css/Custom.css" asp-append-version="true">

    <!-- Pages CSS -->

    <meta name="site_url" content="<?= htmlspecialchars(DOMAIN, ENT_QUOTES) ?>">

</head>

<body>

    <!-- Loader -->
    <div id="loader">
        <img src="<?= DOMAIN ?>/assets/template/images/media/loader.svg" alt="">
    </div>
    <!-- Loader -->

    <div class="page">
        <!-- app-header -->
        <header class="app-header sticky" id="header">

            <!-- Start::main-header-container -->
            <div class="main-header-container container-fluid px-0">

                <!-- Start::header-content-left -->
                <div class="header-content-left">

                    <!-- Start::header-element -->
                    <div class="header-element">
                        <div class="horizontal-logo">
                            <a href="index.html" class="header-logo">
                                <img src="<?= DOMAIN ?>/assets/template/images/brand-logos/desktop-logo.png" alt="logo"
                                    class="desktop-logo">
                                <img src="<?= DOMAIN ?>/assets/template/images/brand-logos/toggle-logo.png" alt="logo" class="toggle-logo">
                                <img src="<?= DOMAIN ?>/assets/template/images/brand-logos/desktop-dark.png" alt="logo"
                                    class="desktop-dark">
                                <img src="<?= DOMAIN ?>/assets/template/images/brand-logos/toggle-dark.png" alt="logo" class="toggle-dark">
                            </a>
                        </div>
                    </div>
                    <!-- End::header-element -->

                    <!-- Start::header-element -->
                    <div class="header-element mx-lg-0 mx-2">
                        <a aria-label="Hide Sidebar"
                            class="sidemenu-toggle header-link animated-arrow hor-toggle horizontal-navtoggle"
                            data-bs-toggle="sidebar" href="javascript:void(0);"><span></span></a>
                    </div>
                    <!-- End::header-element -->

                </div>
                <!-- End::header-content-left -->

                <!-- Start::header-content-right -->
                <ul class="header-content-right">

                    <!-- Start::header-element -->
                    <li class="header-element country-selector dropdown">
                        <!-- Start::header-link|dropdown-toggle -->
                        <a href="javascript:void(0);" class="header-link dropdown-toggle" data-bs-auto-close="outside"
                            data-bs-toggle="dropdown">
                            <svg xmlns="http://www.w3.org/2000/svg" class="header-link-icon" width="1em" height="1em"
                                viewBox="0 0 24 24">
                                <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="1.5" color="currentColor">
                                    <path
                                        d="M7 8.38h4.5m5.5 0h-2.5m-3 0h3m-3 0V7m3 1.38c-.527 1.886-1.632 3.669-2.893 5.236M8.393 17c1.019-.937 2.17-2.087 3.214-3.384m0 0c-.643-.754-1.543-1.973-1.8-2.525m1.8 2.525l1.929 2.005" />
                                    <path
                                        d="M2.5 12c0-4.478 0-6.718 1.391-8.109S7.521 2.5 12 2.5c4.478 0 6.718 0 8.109 1.391S21.5 7.521 21.5 12c0 4.478 0 6.718-1.391 8.109S16.479 21.5 12 21.5c-4.478 0-6.718 0-8.109-1.391S2.5 16.479 2.5 12" />
                                </g>
                            </svg>
                        </a>
                        <!-- End::header-link|dropdown-toggle -->
                        <ul class="main-header-dropdown dropdown-menu dropdown-menu-end" data-popper-placement="none">
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="javascript:void(0);">
                                    <span class="avatar avatar-rounded avatar-xs lh-1 me-2">
                                        <img src="<?= DOMAIN ?>/assets/template/images/flags/spain_flag.jpg" alt="img">
                                    </span>
                                    español
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="javascript:void(0);">
                                    <span class="avatar avatar-rounded avatar-xs lh-1 me-2">
                                        <img src="<?= DOMAIN ?>/assets/template/images/flags/us_flag.jpg" alt="img">
                                    </span>
                                    English
                                </a>
                            </li>
                        </ul>
                    </li>
                    <!-- End::header-element -->

                    <!-- Start::header-element -->
                    <li class="header-element header-theme-mode">
                        <!-- Start::header-link|layout-setting -->
                        <a href="javascript:void(0);" class="header-link layout-setting">
                            <span class="light-layout">
                                <!-- Start::header-link-icon -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="header-link-icon" width="1em"
                                    height="1em" viewBox="0 0 24 24">
                                    <path fill="none" stroke="currentColor" stroke-linecap="round"
                                        stroke-linejoin="round" stroke-width="1.5"
                                        d="M21.5 14.078A8.557 8.557 0 0 1 9.922 2.5C5.668 3.497 2.5 7.315 2.5 11.873a9.627 9.627 0 0 0 9.627 9.627c4.558 0 8.376-3.168 9.373-7.422"
                                        color="currentColor" />
                                </svg>
                                <!-- End::header-link-icon -->
                            </span>
                            <span class="dark-layout">
                                <!-- Start::header-link-icon -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="header-link-icon" width="1em"
                                    height="1em" viewBox="0 0 24 24">
                                    <path fill="none" stroke="currentColor" stroke-linecap="round"
                                        stroke-linejoin="round" stroke-width="1.5"
                                        d="M17 12a5 5 0 1 1-10 0a5 5 0 0 1 10 0M12 2v1.5m0 17V22m7.07-2.929l-1.06-1.06M5.99 5.989L4.928 4.93M22 12h-1.5m-17 0H2m17.071-7.071l-1.06 1.06M5.99 18.011l-1.06 1.06"
                                        color="currentColor" />
                                </svg>
                                <!-- End::header-link-icon -->
                            </span>
                        </a>
                        <!-- End::header-link|layout-setting -->
                    </li>
                    <!-- End::header-element -->

                    <!-- Start::header-element -->
                    <li class="header-element notifications-dropdown d-xl-block d-none dropdown">
                        <!-- Start::header-link|dropdown-toggle -->
                        <a href="javascript:void(0);" class="header-link dropdown-toggle" data-bs-toggle="dropdown"
                            data-bs-auto-close="outside" id="messageDropdown" aria-expanded="false">
                            <svg xmlns="http://www.w3.org/2000/svg" class="header-link-icon" width="1em" height="1em"
                                viewBox="0 0 24 24">
                                <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="1.5" color="currentColor">
                                    <path
                                        d="M2.53 14.77c-.213 1.394.738 2.361 1.902 2.843c4.463 1.85 10.673 1.85 15.136 0c1.164-.482 2.115-1.45 1.902-2.843c-.13-.857-.777-1.57-1.256-2.267c-.627-.924-.689-1.931-.69-3.003C19.525 5.358 16.157 2 12 2S4.475 5.358 4.475 9.5c0 1.072-.062 2.08-.69 3.003c-.478.697-1.124 1.41-1.255 2.267" />
                                    <path d="M8 19c.458 1.725 2.076 3 4 3c1.925 0 3.541-1.275 4-3" />
                                </g>
                            </svg>
                            <span class="header-icon-pulse bg-pink rounded pulse pulse-pink"></span>
                        </a>
                        <!-- End::header-link|dropdown-toggle -->
                        <!-- Start::main-header-dropdown -->
                        <div class="main-header-dropdown dropdown-menu dropdown-menu-end" data-popper-placement="none">
                            <div class="p-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <p class="mb-0 fs-16">Notifications <span
                                            class="badge bg-secondary-transparent ms-1" id="notifiation-data">20</span>
                                    </p>
                                    <a href="javascript:void(0);" class="text-primary text-decoration-underline">Mark As
                                        Read<i class="ri-arrow-right-line ms-1"></i></a>
                                </div>
                            </div>
                            <div class="dropdown-divider"></div>
                            <ul class="nav nav-tabs nav-tabs-header m-3 p-2 rounded bg-light" role="tablist">
                                <li class="nav-item border-0" role="presentation">
                                    <a class="nav-link active" data-bs-toggle="tab" role="tab" aria-current="page"
                                        href="#tabpane1" aria-selected="true">View All</a>
                                </li>
                                <li class="nav-item border-0" role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" role="tab" aria-current="page"
                                        href="#tabpane2" aria-selected="false" tabindex="-1">Mentions</a>
                                </li>
                            </ul>
                            <div class="tab-content border-top">
                                <div class="tab-pane show active text-muted p-0 border-0" id="tabpane1" role="tabpanel">
                                    <ul class="list-unstyled mb-0" id="header-notification-scroll">
                                        <li class="dropdown-item">
                                            <div class="d-flex align-items-start">
                                                <div class="pe-2 lh-1">
                                                    <span class="avatar avatar-md bg-light text-default">
                                                        <img src="<?= DOMAIN ?>/assets/template/images/faces/1.jpg" alt="img">
                                                    </span>
                                                </div>
                                                <div
                                                    class="flex-grow-1 d-flex align-items-start justify-content-between">
                                                    <div>
                                                        <p class="mb-1"><a href="javascript:void(0);"><span
                                                                    class="fw-semibold me-1">Williams
                                                                    Jhon</span>Following
                                                                You</a></p>
                                                        <div class="text-muted fs-13">
                                                            <span><i class="ri-time-line me-1"></i>Wedenesday 3:42
                                                                PM</span>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <p
                                                            class="mb-0 fs-12 text-muted d-inline-flex align-items-center">
                                                            <i
                                                                class="ri-checkbox-blank-circle-fill me-1 fs-8 text-danger"></i>4
                                                            Sec
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="dropdown-item">
                                            <div class="d-flex align-items-start">
                                                <div class="pe-2 lh-1">
                                                    <span class="avatar avatar-md bg-light text-default">
                                                        <img src="<?= DOMAIN ?>/assets/template/images/faces/2.jpg" alt="img">
                                                    </span>
                                                </div>
                                                <div
                                                    class="flex-grow-1 d-flex align-items-start justify-content-between">
                                                    <div>
                                                        <p class="mb-1"><a href="javascript:void(0);"><span
                                                                    class="fw-semibold me-1">JasonSam</span>Commented
                                                                On your
                                                                post.</a></p>
                                                        <div class="text-muted fs-13">
                                                            <span><i class="ri-time-line me-1"></i>Wedenesday 3:42
                                                                PM</span>
                                                        </div>
                                                        <div class="p-2 mt-2 rounded bg-light border text-default">
                                                            Amazing! Fast, to the point,really amazing to work with
                                                            them!!!
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <p
                                                            class="mb-0 fs-12 text-muted text-nowrap d-inline-flex align-items-center">
                                                            <i
                                                                class="ri-checkbox-blank-circle-fill me-1 fs-8 text-primary"></i>12
                                                            Sec
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="dropdown-item">
                                            <div class="d-flex align-items-start">
                                                <div class="pe-2 lh-1">
                                                    <span class="avatar avatar-md bg-secondary-transparent">
                                                        <i class="ri-shopping-bag-3-line fs-18"></i>
                                                    </span>
                                                </div>
                                                <div
                                                    class="flex-grow-1 d-flex align-items-start justify-content-between">
                                                    <div>
                                                        <p class="mb-1"><a href="javascript:void(0);">Successfully
                                                                purchages a
                                                                business plan for <span
                                                                    class="text-danger fw-medium">$19,269</span></a></p>
                                                        <div class="text-muted fs-13">
                                                            <span><i class="ri-time-line me-1"></i>Monday 11.30
                                                                AM</span>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <p
                                                            class="mb-0 fs-12 text-muted d-inline-flex text-nowrap ms-4 align-items-center">
                                                            <i
                                                                class="ri-checkbox-blank-circle-fill me-1 fs-8 text-danger"></i>15
                                                            Min
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="dropdown-item">
                                            <div class="d-flex align-items-start">
                                                <div class="pe-2 lh-1">
                                                    <span class="avatar avatar-md bg-light text-default">
                                                        <img src="<?= DOMAIN ?>/assets/template/images/faces/4.jpg" alt="img">
                                                    </span>
                                                </div>
                                                <div
                                                    class="flex-grow-1 d-flex align-items-start justify-content-between">
                                                    <div>
                                                        <p class="mb-1"><a href="javascript:void(0);"><span
                                                                    class="fw-semibold me-1">Khabib Hussain</span>Liked
                                                                Your
                                                                Post.</a></p>
                                                        <div class="text-muted fs-13">
                                                            <span><i class="ri-time-line me-1"></i>Saterday 9.30
                                                                PM</span>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <p
                                                            class="mb-0 fs-12 text-muted d-inline-flex align-items-center">
                                                            <i
                                                                class="ri-checkbox-blank-circle-fill me-1 fs-8 text-primary"></i>2
                                                            Days
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <div class="tab-pane text-muted p-0 border-0" id="tabpane2" role="tabpanel">
                                    <ul class="list-unstyled mb-0" id="header-notification-scroll1">
                                        <li class="dropdown-item">
                                            <div class="d-flex align-items-start">
                                                <div class="pe-2 lh-1">
                                                    <span class="avatar avatar-md bg-light text-default">
                                                        <img src="<?= DOMAIN ?>/assets/template/images/faces/6.jpg" alt="img">
                                                    </span>
                                                </div>
                                                <div
                                                    class="flex-grow-1 d-flex align-items-start justify-content-between">
                                                    <div>
                                                        <p class="mb-1"><a href="javascript:void(0);"><span
                                                                    class="fw-semibold me-1">Meisha Kerr</span>Liked
                                                                Your
                                                                Post</a></p>
                                                        <div class="text-muted fs-13">
                                                            <span><i class="ri-time-line me-1"></i>Friday 11.19
                                                                PM</span>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <p
                                                            class="mb-0 fs-12 text-muted d-inline-flex align-items-center">
                                                            <i
                                                                class="ri-checkbox-blank-circle-fill me-1 fs-8 text-danger"></i>5
                                                            Sec
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="dropdown-item">
                                            <div class="d-flex align-items-start">
                                                <div class="pe-2 lh-1">
                                                    <span class="avatar avatar-md bg-light text-default">
                                                        <img src="<?= DOMAIN ?>/assets/template/images/faces/7.jpg" alt="img">
                                                    </span>
                                                </div>
                                                <div
                                                    class="flex-grow-1 d-flex align-items-start justify-content-between">
                                                    <div>
                                                        <p class="mb-1"><a href="javascript:void(0);"><span
                                                                    class="fw-semibold me-1">
                                                                    Jessica</span>Commented On your post.</a></p>
                                                        <div class="text-muted fs-13">
                                                            <span><i class="ri-time-line me-1"></i>Thursday 4.55
                                                                PM</span>
                                                        </div>
                                                        <div class="p-2 mt-2 rounded bg-light border text-default">
                                                            It sounds like you had a great experience!What specifically?
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <p
                                                            class="mb-0 fs-12 text-muted text-nowrap d-inline-flex align-items-center">
                                                            <i
                                                                class="ri-checkbox-blank-circle-fill me-1 fs-8 text-primary"></i>12
                                                            Sec
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="dropdown-item">
                                            <div class="d-flex align-items-start">
                                                <div class="pe-2 lh-1">
                                                    <span class="avatar avatar-md bg-light text-default">
                                                        <img src="<?= DOMAIN ?>/assets/template/images/faces/8.jpg" alt="img">
                                                    </span>
                                                </div>
                                                <div
                                                    class="flex-grow-1 d-flex align-items-start justify-content-between">
                                                    <div>
                                                        <p class="mb-1"><a href="javascript:void(0);"><span
                                                                    class="fw-semibold me-1">Khabib Hussain</span>Added
                                                                You on
                                                                Story..</a></p>
                                                        <div class="text-muted fs-13">
                                                            <span><i class="ri-time-line me-1"></i>Monday 8.30 AM</span>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <p
                                                            class="mb-0 fs-12 text-muted d-inline-flex align-items-center">
                                                            <i
                                                                class="ri-checkbox-blank-circle-fill me-1 fs-8 text-danger"></i>2
                                                            Hours
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="dropdown-item">
                                            <div class="d-flex align-items-start">
                                                <div class="pe-2 lh-1">
                                                    <span class="avatar avatar-md bg-light text-default">
                                                        <img src="<?= DOMAIN ?>/assets/template/images/faces/8.jpg" alt="img">
                                                    </span>
                                                </div>
                                                <div
                                                    class="flex-grow-1 d-flex align-items-start justify-content-between">
                                                    <div>
                                                        <p class="mb-1"><a href="javascript:void(0);"><span
                                                                    class="fw-semibold me-1">Simon Cowall</span>Reply
                                                                Your Story.</a></p>
                                                        <div class="text-muted fs-13">
                                                            <span><i class="ri-time-line me-1"></i>Tuesday 12.17
                                                                PM</span>
                                                        </div>
                                                        <div class="p-2 mt-2 rounded bg-light border text-default">
                                                            Shall We Meet On Meeting Today?
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <p
                                                            class="mb-0 fs-12 text-muted d-inline-flex align-items-center">
                                                            <i
                                                                class="ri-checkbox-blank-circle-fill me-1 fs-8 text-danger"></i>1
                                                            Day
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="p-3 empty-header-item1 border-top">
                                <div class="d-grid">
                                    <a href="javascript:void(0);" class="btn btn-primary btn-wave">View All</a>
                                </div>
                            </div>
                            <div class="p-5 empty-item1 d-none">
                                <div class="text-center">
                                    <span class="avatar avatar-xl avatar-rounded bg-secondary-transparent">
                                        <i class="ri-notification-off-line fs-2"></i>
                                    </span>
                                    <h6 class="fw-medium mt-3">No New Notifications</h6>
                                </div>
                            </div>
                        </div>
                        <!-- End::main-header-dropdown -->
                    </li>
                    <!-- End::header-element -->

                    <!-- Start::header-element -->
                    <li class="header-element header-fullscreen">
                        <!-- Start::header-link -->
                        <a onclick="openFullscreen();" href="javascript:void(0);" class="header-link">
                            <svg xmlns="http://www.w3.org/2000/svg" class=" full-screen-open header-link-icon"
                                width="1em" height="1em" viewBox="0 0 24 24">
                                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="1.5"
                                    d="m12.567 7.934l2.742.1c.407.015.73.35.73.758v2.615m-5.5 2.027l5.044-5.018M2 17c0-1.886 0-2.828.586-3.414S4.114 13 6 13h1c1.886 0 2.828 0 3.414.586S11 15.114 11 17v1c0 1.886 0 2.828-.586 3.414S8.886 22 7 22H6c-1.886 0-2.828 0-3.414-.586S2 19.886 2 18zm0-8.5v2M14 2h-4m12 12v-4m-8.5 12h2M2.06 5.5c.154-1.066.453-1.821 1.036-2.404S4.434 2.214 5.5 2.06m13 0c1.066.154 1.821.453 2.404 1.036c.582.583.882 1.338 1.036 2.404m0 13c-.154 1.066-.454 1.821-1.036 2.404c-.583.582-1.338.882-2.404 1.036"
                                    color="currentColor" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="full-screen-close header-link-icon d-none"
                                width="1em" height="1em" viewBox="0 0 24 24">
                                <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="1.5" color="currentColor">
                                    <path
                                        d="M2 10.002c.029-3.414.218-5.296 1.46-6.537C4.924 2 7.282 2 11.997 2s7.073 0 8.538 1.465S22 7.287 22 12.003c0 4.715 0 7.073-1.465 8.537c-1.241 1.242-3.123 1.431-6.537 1.46" />
                                    <path
                                        d="M4.999 13c-1.17.035-1.868.165-2.351.648s-.613 1.18-.648 2.35M8.001 13c1.17.035 1.868.165 2.351.648s.613 1.18.648 2.35m0 3.003c-.035 1.17-.165 1.868-.648 2.351s-1.18.613-2.35.648m-3.003 0c-1.17-.035-1.868-.165-2.351-.648s-.613-1.18-.648-2.35m14.413-7.996l-2.903-.066c-.432-.01-.777-.345-.782-.757l-.031-2.644m7.331-3.773l-6.747 6.601" />
                                </g>
                            </svg>
                        </a>
                        <!-- End::header-link -->
                    </li>
                    <!-- End::header-element -->

                    <!-- Start::header-element -->
                    <li class="header-element dropdown">
                        <!-- Start::header-link|dropdown-toggle -->
                        <a href="javascript:void(0);" class="header-link dropdown-toggle" id="mainHeaderProfile"
                            data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                            <div class="d-flex align-items-center">
                                <div>
                                    <img src="<?=$usuario->foto_url!=""? DOMAIN."/".$usuario->foto_url : "https://via.placeholder.com/300x300?text=Foto"?>" alt="img" class="avatar avatar-xs">
                                </div>
                            </div>
                        </a>
                        <!-- End::header-link|dropdown-toggle -->
                        <ul class="main-header-dropdown dropdown-menu pt-0 overflow-hidden header-profile-dropdown dropdown-menu-end"
                            aria-labelledby="mainHeaderProfile">
                            <li><a class="dropdown-item d-flex align-items-center" href="#"><i
                                        class="ti ti-user me-2 fs-16"></i>Profile</a></li>
                            <li><a class="dropdown-item d-flex align-items-center" href="#"><i
                                        class="ti ti-headset me-2 fs-16"></i>Support</a></li>
                            <li><a class="dropdown-item d-flex align-items-center" href="./logout.php"><i
                                        class="ti ti-logout me-2 fs-16"></i>Cerrar Sesión</a></li>
                            <li>
                        </ul>
                    </li>
                    <!-- End::header-element -->


                </ul>
                <!-- End::header-content-right -->

            </div>
            <!-- End::main-header-container -->

        </header>
        <!-- /app-header -->
        <!-- Start::app-sidebar -->
        <aside class="app-sidebar sticky" id="sidebar">

            <!-- Start::main-sidebar-header -->
            <div class="main-sidebar-header">
                <a href="Dashboard/Index" class="header-logo">
                    <img src="<?= DOMAIN ?>/assets/template/images/logo.png" alt="logo" class="desktop-logo">
                    <img src="<?= DOMAIN ?>/assets/template/images/logo.png" alt="logo" class="toggle-dark">
                    <img src="<?= DOMAIN ?>/assets/template/images/logo.png" alt="logo" class="desktop-dark">
                    <img src="<?= DOMAIN ?>/assets/template/images/logo.png" alt="logo" class="toggle-logo">
                </a>
            </div>
            <!-- End::main-sidebar-header -->

            <!-- Start::main-sidebar -->
            <div class="main-sidebar" id="sidebar-scroll">

                <!-- Start::nav -->
                <nav class="main-menu-container nav nav-pills flex-column sub-open">
                    <div class="slide-left" id="slide-left">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24"
                            viewBox="0 0 24 24">
                            <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"></path>
                        </svg>
                    </div>

                    <?php include 'sidebar.php'; ?>

                    <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191"
                            width="24" height="24" viewBox="0 0 24 24">
                            <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"></path>
                        </svg></div>
                </nav>
                <!-- End::nav -->

            </div>
            <!-- End::main-sidebar -->

        </aside>
        <!-- End::app-sidebar -->
        <!-- Start::app-content -->
        <div class="main-content app-content">
            <div class="container-fluid">

                <!-- Page Header -->
                <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h1 class="page-title fw-medium fs-18 mb-2"><?= $menu->nombre ?></h1>
                        <nav>
                            <ol class="breadcrumb mb-0">

                                <li class="breadcrumb-item"><a href="javascript:void(0);"><?= $menuPadre != '' ? $menuPadre : $menu->nombre ?></a></li>
                                <li class="breadcrumb-item active" aria-current="page"><?= $menuPadre == '' ? '' : $menu->nombre ?></li>

                            </ol>
                        </nav>
                    </div>

                    <div class="d-flex flex-wrap gap-2">
                        <div class="d-flex align-items-center p-2 border rounded-2 bg-body">
                            <i class="ri-building-4-line me-2 fs-18"></i>
                            <div class="small lh-sm text-truncate">
                                <div class="text-muted">Empresa</div>
                                <div class="text-truncate"><?= $empresa->nombre_legal ?></div>
                            </div>
                            <button class="btn btn-outline-primary btn-sm ms-2" onclick="cargarEmpresasAsociadas();" data-bs-toggle="tooltip" title="Cambiar empresa">
                                <i class="ri-exchange-line"></i>
                            </button>
                        </div>

                        <div class="d-flex align-items-center p-2 border rounded-2 bg-body">
                            <i class="ri-user-3-line me-2 fs-18"></i>
                            <div class="small lh-sm text-truncate">
                                <div class="text-muted">Usuario</div>
                                <div class="text-truncate"><?= $usuario->nombre . " " . $usuario->apellido1 . " " . $usuario->apellido2 ?></div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center p-2 border rounded-2 bg-body">
                            <i class="ri-file-list-2-line me-2 fs-18"></i>
                            <div class="small lh-sm">
                                <div class="text-muted">Facturas disponibles</div>
                                <div>22..?</div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center p-2 border rounded-2 bg-body">
                            <i class="ri-shield-user-line me-2 fs-18"></i>
                            <div class="small lh-sm text-truncate">
                                <div class="text-muted">Rol del sistema</div>
                                <div class="text-truncate"><?= $rol->nombre ?></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Page Header Close -->