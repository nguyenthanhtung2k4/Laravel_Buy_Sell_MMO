<?php
require_once __DIR__ . '/../../../core/is_user.php';
if (empty($getUser['username'])) {
      include($_SERVER['DOCUMENT_ROOT'] . '/resources/views/errors/404.php');
      die();
}


if ($getUser['level'] != 1) {
      include($_SERVER['DOCUMENT_ROOT'] . '/resources/views/errors/404.php');
      die();
}
?>
<?php
if (!defined('IN_SITE')) {
      die('The Request Not Found');
}
?>

<!doctype html>
<html lang="en" dir="ltr" data-nav-layout="vertical" data-theme-mode="light" data-header-styles="light"
      data-menu-styles="dark" data-toggled="close">

<head>
      <meta charset="UTF-8">
      <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=no'>
      <meta name="robots" content="noindex, nofollow">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <title><?= $title ?></title>


      <!-- Choices JS -->
      <script
            src="<?= BASE_URL('public/tailieu/'); ?>theme/assets/libs/choices.js/public/assets/scripts/choices.min.js"></script>
      <!-- Cute Alert -->
      <link class="main-stylesheet" href="<?= BASE_URL('public/assets/'); ?>cute/cute-alert.css" rel="stylesheet"
            type="text/css">
      <script src="<?= BASE_URL('public/assets/'); ?>cute/cute-alert.js"></script>
      <!-- Main Theme Js -->
      <script src="<?= BASE_URL('public/tailieu/'); ?>theme/assets/js/main.js"></script>

      <!-- Bootstrap Css -->
      <link id="style" href="<?= BASE_URL('public/tailieu/'); ?>theme/assets/libs/bootstrap/css/bootstrap.min.css"
            rel="stylesheet">

      <!-- Style Css -->
      <link href="<?= BASE_URL('public/tailieu/'); ?>theme/assets/css/styles.min.css" rel="stylesheet">

      <!-- Icons Css -->
      <link href="<?= BASE_URL('public/tailieu/'); ?>theme/assets/css/icons.css" rel="stylesheet">

      <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">


      <!-- Node Waves Css -->
      <link href="<?= BASE_URL('public/tailieu/'); ?>theme/assets/libs/node-waves/waves.min.css" rel="stylesheet">

      <!-- Simplebar Css -->
      <link href="<?= BASE_URL('public/tailieu/'); ?>theme/assets/libs/simplebar/simplebar.min.css" rel="stylesheet">

      <!-- Color Picker Css -->
      <link rel="stylesheet" href="<?= BASE_URL('public/tailieu/'); ?>theme/assets/libs/flatpickr/flatpickr.min.css">
      <link rel="stylesheet"
            href="<?= BASE_URL('public/tailieu/'); ?>theme/assets/libs/@simonwep/pickr/themes/nano.min.css">

      <!-- Prism CSS -->
      <link rel="stylesheet"
            href="<?= BASE_URL('public/tailieu/'); ?>theme/assets/libs/prismjs/themes/prism-coy.min.css">

      <!-- Choices Css -->
      <link rel="stylesheet"
            href="<?= BASE_URL('public/tailieu/'); ?>theme/assets/libs/choices.js/public/assets/styles/choices.min.css">

      <link rel="stylesheet"
            href="<?= BASE_URL('public/tailieu/'); ?>theme/assets/libs/glightbox/css/glightbox.min.css">

      <!-- Simple Notify CSS -->
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simple-notify@1.0.4/dist/simple-notify.css" />
      <!-- Simple Notify JS -->
      <script src="https://cdn.jsdelivr.net/npm/simple-notify@1.0.4/dist/simple-notify.min.js"></script>

      <!-- Sweetalerts CSS -->
      <link rel="stylesheet"
            href="<?= BASE_URL('public/tailieu/'); ?>theme/assets/libs/sweetalert2/sweetalert2.min.css">
      <!-- Sweetalerts JS -->
      <script src="<?= BASE_URL('public/tailieu/'); ?>theme/assets/libs/sweetalert2/sweetalert2.min.js"></script>
      <script src="<?= BASE_URL('public/tailieu/'); ?>theme/assets/js/sweet-alerts.js"></script>

      <!-- SwiperJS Css -->
      <link rel="stylesheet" href="<?= BASE_URL('public/tailieu/'); ?>theme/assets/libs/swiper/swiper-bundle.min.css">

      <link rel="stylesheet" href="<?= BASE_URL('public/tailieu/'); ?>theme/assets/css/styles.css">
      <!-- Cute Alert -->
      <link class="main-stylesheet" href="<?= BASE_URL('public/tailieu/'); ?>cute-alert/style.css" rel="stylesheet"
            type="text/css">
      <script src="<?= BASE_URL('public/tailieu/'); ?>cute-alert/cute-alert.js"></script>

      <script src="<?= BASE_URL('public/tailieu/'); ?>js/jquery-3.6.0.js"></script>
      <link rel="stylesheet" href="<?= BASE_URL('public/tailieu/'); ?>fontawesome/css/all.min.css">


      <script src="<?= BASE_URL('public/'); ?>ckeditor/ckeditor.js"></script>

      <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>



      <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.6/clipboard.min.js"></script>

      <!-- Thêm CSS của CodeMirror -->
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/codemirror.min.css">

      <!-- Thêm JavaScript của CodeMirror -->
      <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/codemirror.min.js"></script>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/theme/monokai.min.css">
      <!-- Mode HTML mixed (hỗ trợ HTML, CSS và JS) -->
      <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/mode/htmlmixed/htmlmixed.min.js"></script>
      <!-- Mode cho CSS -->
      <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/mode/css/css.min.js"></script>
      <!-- Mode cho JavaScript -->
      <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/mode/javascript/javascript.min.js"></script>
      <!-- Mode cho XML (cần cho HTML) -->
      <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/mode/xml/xml.min.js"></script>

      <script src="https://cdn.tailwindcss.com"></script>
      <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
      
      <?= $body['header']; ?>



</head>

<style>
      /* Buộc container chính của trang sử dụng Flexbox để đặt sidebar và content cạnh nhau */
.app {
    display: flex;
    /* Có thể cần thay thế .app bằng class container lớn nhất chứa cả sidebar và main-content */
}

/* Đảm bảo phần nội dung chính nằm bên phải */
.main-content.app-content {
    /* Điều chỉnh giá trị này bằng độ rộng sidebar (thanh menu xanh đen) */
    margin-left: 280px !important; 
    
    /* Đảm bảo nó chiếm đủ không gian còn lại */
    flex-grow: 1; 
}


      /* Cho trình duyệt WebKit (Chrome, Safari) */
      ::-webkit-scrollbar {
            width: 15px;
            /* Độ rộng của thanh cuộn */

      }

      ::-webkit-scrollbar-thumb {
            background-color: #c3c3c3;
            /* Màu nền của thanh cuộn */
      }

      .top-filter {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            -webkit-box-pack: justify;
            -ms-flex-pack: justify;
            justify-content: space-between;
            margin-bottom: 25px;
      }

      .filter-show {
            width: 150px;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
      }

      .filter-label {
            font-size: 14px;
            font-weight: 500;
            margin-right: 8px;
            white-space: nowrap;
            text-transform: uppercase;
      }

      .filter-select {
            height: 40px;
            background-color: transparent;
      }

      .filter-short {
            width: 225px;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
      }

      .text-right {
            text-align: right;
      }

      .tab-content .tab-pane {
            padding: 0px;
      }

      .nav.tab-style-1 {
            background-color: var(--white-9);
      }

      .nav.tab-style-1 .nav-link.active {
            box-shadow: 3px 0rem 20px 0px rgb(10 10 10 / 38%);
      }

      .form-check-input {
            background-color: #bdbdbd;
      }

      .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
      }

      /* Customize scrollbar */
      .table-responsive::-webkit-scrollbar {
            width: 10px;
            /* Chiều rộng của thanh trượt */
      }

      /* Track */
      .table-responsive::-webkit-scrollbar-track {
            background: #f1f1f1;
            /* Màu nền của thanh trượt */
      }

      /* Handle */
      .table-responsive::-webkit-scrollbar-thumb {
            background: #888;
            /* Màu của phần cần kéo */
            border-radius: 20px;
            /* Độ cong của phần cần kéo */
      }

      /* Handle on hover */
      .table-responsive::-webkit-scrollbar-thumb:hover {
            background: #555;
            /* Màu của phần cần kéo khi di chuột qua */
      }

      .tab-content .tab-pane {
            border: 0px solid var(--default-border);
      }

      .table-wrapper {
            max-height: 700px;
            /* Đặt chiều cao tối đa của bảng */
            overflow-y: auto;
            /* Kích hoạt thanh cuộn dọc khi nội dung vượt quá chiều cao */
      }

      .table-wrapper table {
            width: 100%;
      }

      th,
      td {
            padding: 8px;
            /* Để tránh lệch về bố cục khi cuộn */
      }

      /* Đảm bảo phần header cố định */
      thead {
            position: sticky;
            top: 0;
            background-color: white;
            z-index: 1;
      }

      /* Đảm bảo phần footer cố định */
      tfoot {
            position: sticky;
            bottom: 0;
            background-color: white;
            z-index: 1;
      }
</style>
<script>
      function showMessage(message, type) {
            const commonOptions = {
                  effect: 'fade',
                  speed: 300,
                  customClass: null,
                  customIcon: null,
                  showIcon: true,
                  showCloseButton: true,
                  autoclose: true,
                  autotimeout: 3000,
                  gap: 20,
                  distance: 20,
                  type: 'outline',
                  position: 'right top'
            };
            const options = {
                  success: {
                        status: 'success',
                        title: 'Thành công!',
                        text: message,
                  },
                  error: {
                        status: 'error',
                        title: 'Thất bại!',
                        text: message,
                  }
            };
            new Notify(Object.assign({}, commonOptions, options[type]));
      }

</script>