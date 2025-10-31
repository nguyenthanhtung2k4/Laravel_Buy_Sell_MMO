<?php
include(realpath($_SERVER["DOCUMENT_ROOT"]) . '/config.php');
?>
<!-- Footer Start -->
    <footer class="footer mt-auto py-3 bg-white text-center">
  <div class="container">
    <span class="text-muted"> Copyright © <span id="year"></span> 
    <a href="#" class="text-dark fw-semibold">Nguyễn Thành An</a>. Software by <a href="https://t.me/">
        <span class="fw-semibold text-primary text-decoration-underline"></span> 🇻🇳
    </a> All rights reserved
    </span>
        <div class="gtranslate_wrapper"></div>
        <script>
        window.gtranslateSettings = {
            "default_language": "vi",
            "languages": ["vi", "en", "th", "ms", "zh-CN", "tl", "de", "km", "ru", "my", "lo", "tr", "uk", "ko",
                "zh-TW", "it", "fr", "ar"
            ],
            "wrapper_selector": ".gtranslate_wrapper"
        }
        </script>
        <script src="https://cdn.gtranslate.net/widgets/latest/flags.js" defer></script>
    </div>
</footer>

</div>

<div class="scrollToTop">
    <span class="arrow"><i class="ri-arrow-up-s-fill fs-20"></i></span>
</div>
<div id="responsive-overlay"></div>


<!-- Popper JS -->
<script src="<?=BASE_URL('public/tailieu/');?>theme/assets/libs/@popperjs/core/umd/popper.min.js"></script>

<!-- Bootstrap JS -->
<script src="<?=BASE_URL('public/tailieu/');?>theme/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Defaultmenu JS -->
<script src="<?=BASE_URL('public/tailieu/');?>theme/assets/js/defaultmenu.min.js"></script>

<!-- Node Waves JS-->
<script src="<?=BASE_URL('public/tailieu/');?>theme/assets/libs/node-waves/waves.min.js"></script>

<!-- Sticky JS -->
<script src="<?=BASE_URL('public/tailieu/');?>theme/assets/js/sticky.js"></script>

<!-- Simplebar JS -->
<script src="<?=BASE_URL('public/tailieu/');?>theme/assets/libs/simplebar/simplebar.min.js"></script>
<script src="<?=BASE_URL('public/tailieu/');?>theme/assets/js/simplebar.js"></script>

<!-- Color Picker JS -->
<script src="<?=BASE_URL('public/tailieu/');?>theme/assets/libs/@simonwep/pickr/pickr.es5.min.js"></script>

<!-- Custom-Switcher JS -->
<script src="<?=BASE_URL('public/tailieu/');?>theme/assets/js/custom-switcher.min.js"></script>
<!-- Internal Swiper JS -->
<script src="<?=BASE_URL('public/tailieu/');?>theme/assets/js/swiper.js"></script>

<!-- Custom JS -->
<script src="<?=BASE_URL('public/tailieu/');?>theme/assets/js/custom.js"></script>


<!-- Prism JS -->
<script src="<?=BASE_URL('public/tailieu/');?>theme/assets/libs/prismjs/prism.js"></script>
<script src="<?=BASE_URL('public/tailieu/');?>theme/assets/js/prism-custom.js"></script>

<!-- Modal JS -->
<script src="<?=BASE_URL('public/tailieu/');?>theme/assets/js/modal.js"></script>

<!-- Date & Time Picker JS -->
<script src="<?=BASE_URL('public/tailieu/');?>theme/assets/libs/flatpickr/flatpickr.min.js"></script>
<script src="<?=BASE_URL('public/tailieu/');?>theme/assets/js/date&time_pickers.js"></script>

<!-- Chartjs Chart JS -->
<script src="<?=BASE_URL('public/tailieu/');?>theme/assets/libs/chart.js/chart.min.js"></script>

<!-- Gallery JS -->
<script src="<?=BASE_URL('public/tailieu/');?>theme/assets/libs/glightbox/js/glightbox.min.js"></script>

<!-- Choices JS -->
<script src="<?=BASE_URL('public/tailieu/');?>theme/assets/libs/choices.js/public/assets/scripts/choices.min.js"></script>

<!-- Swiper JS -->
<script src="<?=BASE_URL('public/tailieu/');?>theme/assets/libs/swiper/swiper-bundle.min.js"></script>


 
 

<script>
$(document).ready(function() {
    $('[data-toggle="tooltip"]').tooltip();
});
</script>
</body>

</html><script type="text/javascript">
new ClipboardJS(".copy");

function copy() {
    showMessage('Đã sao chép vào bộ nhớ tạm', 'success');
}
</script>