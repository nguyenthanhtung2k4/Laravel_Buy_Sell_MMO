<?php
$title = 'Tin Tức - ' . $TN->site('title');
$body['header'] = '
<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.6/clipboard.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>
';
$body['footer'] = '
    
';
require_once __DIR__ . '/../../../core/is_user.php';
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/nav.php';
?>
<link rel="stylesheet" href="/assets/css/styles.css">
<main>
    <div class="w-breadcrumb-area">
        <div class="breadcrumb-img">
            <div class="breadcrumb-left">
                <img src="/assets/images/banner-bg-03.png" alt="img">
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-12">
                    <nav aria-label="breadcrumb" class="page-breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="/">Trang chủ</a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">Blog</li>
                        </ol>
                    </nav>
                    <h2 class="breadcrumb-title">
                        Blog
                    </h2>
                </div>
            </div>
        </div>
    </div>


    <div class="page-content">
        <div class="container">

            <div class="row">
                <div class="col-lg-8">
                    <div class="blog">
                        <div class="row">
                        <?php foreach($TN->get_list("SELECT * FROM `news` WHERE `status` = '1' ORDER BY id DESC") as $row) { ?>
                                                            <div class="col-lg-6">
                                    <div class="blog-grid">
                                        <div class="blog-img">
                                            <a href="/assets/images/avt.png"><img src="<?=$row['images']; ?>" class="img-fluid" alt="img"></a>
                                            
                                        </div>
                                        <div class="blog-content">
                                            <div class="user-head">
                                                <div class="user-info">
                                                    <a href="javascript:void(0);"><img src="/assets/images/avt.png" alt="img"></a>
                                                    <h6><a href="javascript:void(0);"><?= $TN->site('author') ?></a><span><?=format_date($row['create_date']); ?></span></h6>
                                                </div>
                                            </div>
                                            <div class="blog-title">
                                                <h3><a href="/client/blog/<?=$row['code']; ?>"><?=$row['tieude']; ?></a></h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php }?>
                                                    </div>

                        <div class="d-flex justify-content-center">
                            <div class="pagination">
                            </div>
                        </div>

                    </div>

                </div>

                <div class="col-lg-4 theiaStickySidebar">
                    <div class="blog-sidebar mb-0">
                        <div class="card recent-widget">
                            <div class="card-header">
                                <h6><img src="/assets/images/blog-icon.svg" alt="icon"> Bài viết nổi bật</h6>
                            </div>
                            <div class="card-body">
                                <ul class="latest-posts">
                                <?php foreach($TN->get_list("SELECT * FROM `news` WHERE `status` = '1' ORDER BY id DESC") as $row) { ?>
                                                                        <li>
                                        <div class="post-thumb">
                                            <a href="/client/blog/<?=$row['code']; ?>">
                                                <img class="img-fluid" src="<?=$row['images']; ?>" alt="blog-image">
                                            </a>
                                        </div>
                                        <div class="post-info">
                                            <h6>
                                                <a href="/client/blog/<?=$row['code']; ?>"><?=$row['tieude']; ?></a>
                                            </h6>
                                            <div class="blog-user">
                                                <img src="/assets/images/avt.png" alt="user">
                                                <div class="blog-user-info">
                                                    <p><?= $TN->site('author') ?></p>
                                                    <p class="xs-text"><?=format_date($row['create_date']); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <?php }?>
                                                                   </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php require_once(__DIR__ . '/footer.php'); ?>