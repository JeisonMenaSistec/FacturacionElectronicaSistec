</div>
</div>
<!-- End::app-content -->

<!-- Footer Start -->
<footer class="footer mt-auto py-3 bg-white text-center">
    <div class="container">
        <span class="text-muted">
            Copyright © <span id="year"></span> <a href="https://www.sisteccr.com/" class="text-dark fw-medium">Sistec CR</a>. All rights reserved
        </span>
    </div>
</footer>
<!-- Footer End -->
</div>

<!-- Modal: Cambiar Empresa -->
<div class="modal fade" id="ModalCambiarEmpresa" tabindex="-1" aria-labelledby="ModalCambiarEmpresaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="ModalCambiarEmpresaLabel"><strong>Cambiar empresa</strong></h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <input type="text" class="form-control mb-3" id="buscadorEmpresas" placeholder="Buscar empresa...">
                <div class="list-group" id="empresasAsociadas">
                    <!-- Empresas aquí -->
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Scroll To Top -->
<div class="scrollToTop">
    <span class="arrow lh-1"><i class="ti ti-caret-up fs-20"></i></span>
</div>
<div id="responsive-overlay"></div>
<!-- Scroll To Top -->

<!-- Popper JS -->
<script src="<?= DOMAIN ?>/assets/template/libs/popperjs/core/umd/popper.min.js"></script>

<!-- Bootstrap JS -->
<script src="<?= DOMAIN ?>/assets/template/libs/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Defaultmenu JS -->
<script src="<?= DOMAIN ?>/assets/template/js/defaultmenu.min.js"></script>

<!-- Node Waves JS-->
<script src="<?= DOMAIN ?>/assets/template/libs/node-waves/waves.min.js"></script>

<!-- Sticky JS -->
<script src="<?= DOMAIN ?>/assets/template/js/sticky.js"></script>

<!-- Simplebar JS -->
<script src="<?= DOMAIN ?>/assets/template/libs/simplebar/simplebar.min.js"></script>
<script src="<?= DOMAIN ?>/assets/template/js/simplebar.js"></script>

<!-- Auto Complete JS -->
<script src="<?= DOMAIN ?>/assets/template/libs/tarekraafat/autocomplete.js/autoComplete.min.js"></script>

<!-- Color Picker JS -->
<script src="<?= DOMAIN ?>/assets/template/libs/simonwep/pickr/pickr.es5.min.js"></script>

<!-- Date & Time Picker JS -->
<script src="<?= DOMAIN ?>/assets/template/libs/flatpickr/flatpickr.min.js"></script>

<!-- Custom JS -->
<script src="<?= DOMAIN ?>/assets/template/js/custom.js"></script>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>