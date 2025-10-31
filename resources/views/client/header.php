<?php if (!defined('IN_SITE')) {
    die('The Request Not Found');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="turbolinks-cache-control" content="no-cache">
    <meta name="zalo-platform-site-verification" content="OVwFEx7J9W10sF9UjQOXUK_PjrsZhsG0CJCt" />
    <title><?=$title?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="title" content="<?=$TN->site('title');?>">
    <meta name="keywords" content="<?=$TN->site('keywords');?>">
    <meta name="description" content="<?=$TN->site('description');?>">

    <meta property="og:type" content="website">
    <meta property="og:url" content="<?=BASE_URL('');?>">
    <meta property="og:title" content="<?=$TN->site('title');?>">
    <meta property="og:description" content="<?=$TN->site('description');?>">
    <meta property="og:image" content="<?=$TN->site('anhbia');?>">

    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?=BASE_URL('');?>">
    <meta property="twitter:title" content="<?=$TN->site('title');?>">
    <meta property="twitter:description" content="<?=$TN->site('description');?>">
    <meta property="twitter:image" content="<?=$TN->site('anhbia');?>">

    <meta name="author" content="<?= $TN->site('author') ?>">
    <link rel="icon" href="<?=$TN->site('favicon');?>" type="image/x-icon" />   
    <link href="/public/assets/cute/cute-alert.css" rel="stylesheet">
    <script src="/public/assets/cute/cute-alert.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/glightbox.min.css" />
    <link rel="stylesheet" href="/assets/css/aos.css" />
    <link rel="stylesheet" href="/assets/css/nice-select.css" />
    <link href="/assets/css/quill.core.css" rel="stylesheet" />
    <link href="/assets/css/quill.snow.css" rel="stylesheet" />
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="/assets/css/font-awesome-all.min.css" rel="stylesheet" />
    <link href="/assets/css/fontawesome.css" rel="stylesheet" />
    <link href="/assets/css/swiper-bundle.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link href="/assets/css/style.css" rel="stylesheet" />
    <link href="/assets/css/job_post.css" rel="stylesheet" />
    <link href="/assets/css/resposive.css" rel="stylesheet" />
    <link rel="stylesheet" href="/assets/css/styles.css?time=<?time()?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simple-notify@1.0.4/dist/simple-notify.css" />
    <script src="https://cdn.jsdelivr.net/npm/simple-notify@1.0.4/dist/simple-notify.min.js"></script>
    <script src="/assets/js/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/lazyload@2.0.0-rc.2/lazyload.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@2.1.0/css/boxicons.min.css">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@1,600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Signika:wght@600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Play:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">
    <script>
        var csrf_token = "<?= CreateToken(); ?>";
    </script>
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
<?=$body['header'];?>
<?= $TN->site('javascript_header'); ?>
</head>