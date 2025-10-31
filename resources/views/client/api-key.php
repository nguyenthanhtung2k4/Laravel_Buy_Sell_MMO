<?php
$title = 'Api Document - ' . $TN->site('title');
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
CheckLogin();
?>

        <?php require_once('sidebar.php');?>       
            <div class="row justify-content-center gy-3">
                <div class="col-xl-3 col-lg-4">
                    <div class="common-sidebar__item api-sidebar-menu shadow-sm">
                        <div class="common-sidebar__content">
                            <ul>
                                <li>
                                    <a href="" class="api-sidebar-menu__link">Bắt đầu</a>
                                    <ul class="api-sidebar-submenu">
                                        <li class="api-sidebar-submenu__item">
                                            <a href="#introduction" class="api-sidebar-submenu__link">Giới thiệu</a>
                                        </li>
                                        <li class="api-sidebar-submenu__item">
                                            <a href="#api-key" class="api-sidebar-submenu__link">API key</a>
                                        </li>
                                        <li class="api-sidebar-submenu__item">
                                            <a href="#purchase-code" class="api-sidebar-submenu__link">Mã mua hàng</a>
                                        </li>
                                        <li class="api-sidebar-submenu__item">
                                            <a href="#php-code" class="api-sidebar-submenu__link">PHP Code</a>
                                        </li>
                                        <li class="api-sidebar-submenu__item">
                                            <a href="#error-response" class="api-sidebar-submenu__link">Phản hồi lỗi</a>
                                        </li>
                                        <li class="api-sidebar-submenu__item">
                                            <a href="#success-response" class="api-sidebar-submenu__link">Phản hồi thành công</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-xl-9 col-lg-8">
                    <div class="common-sidebar__item">
                        <div class="common-sidebar__content">
                            <div class="api-docs-section mb-5" id="introduction">
                                <div class="api-content">
                                    <h6>Giới thiệu</h6>
                                    <p class="api-content__desc">
                                        Sử dụng API <span class="text--base fw-bold"><?=$TN->site('domain');?></span> khá đơn giản. 
                                        Bạn có thể dễ dàng xác nhận việc mua sản phẩm của người dùng bằng tích hợp đơn giản của chúng tôi. 
                                        API của chúng tôi được thiết kế để triển khai liền mạch vào bất kỳ ứng dụng web & di động nào, hỗ trợ cả yêu cầu GET và POST 
                                        trong khi cung cấp phản hồi ở định dạng JSON. Hãy nhớ rằng, URL phân biệt chữ hoa chữ thường để tương tác chính xác.
                                    </p>
                                </div>
                            </div>
                            <div class="api-docs-section mb-5" id="apikey">
                                <div class="api-content">
                                    <h6>Khóa API</h6>
                                        <p class="api-content__desc">
                                            Lấy khóa API của bạn từ bên dưới. Khóa API được sử dụng để xác thực yêu cầu và xác định xem yêu cầu có hợp lệ hay không. Nếu bạn muốn tạo lại khóa API từ biểu tượng đồng bộ bên dưới.</p>
                                        <div class="form-group">
                                            <label for="" class="form-group-label mb-2">API Key</label>
                                            <div class="input-group">
                                                <button class="input-group-text  c--p confirmationBtn" data-question="Bạn có chắc chắn muốn tạo lại khóa API của mình không? Khóa API cũ của bạn sẽ ngừng hoạt động ở đây nếu bạn thực hiện!" data-bs-toggle="tooltip"
                                                    title="Regenerate API Key">
                                                    <i class="fa fa-sync-alt"></i>
                                                </button>
                                                <input type="text" class="form-control" id="apikey" readonly value="<?=$getUser['apikey'];?>">
                                                <button class="input-group-text c--p copy" data-clipboard-target="#apikey">
                                                    <i class="fa fa-copy"></i>
                                                </button>
                                            </div>
                                        </div>
                                    
                                </div>
                            </div>

                            <div class="api-docs-section mb-5" id="purchase-code">
                                <div class="api-content">
                                    <h6>Xác minh mã mua hàng</h6>
                                    <p class="mb-3">
                                        Xác minh dễ dàng việc mua sản phẩm của người dùng thông qua điểm cuối này, kèm theo các ví dụ phản hồi thành công và lỗi dưới đây để bạn tham khảo.</p>
                                    <p class="api-content__desc mb-0">
                                        URL: <span class="text--primary">https://dev.c25tool.net/api/verify-purchase-code</span>
                                    </p>
                                    <p class="api-content__desc mb-0">
                                        METHOD: <span class="text--primary">POST</span>
                                    </p>
                                    <p class="api-content__desc">
                                        HEADER: <span class="text--primary">apikey</span>
                                    </p>

                                    <div class="row">
                                        <div class="col-lg-12">
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item d-flex justify-content-between align-items-center gap-4 ps-0 text-muted">
                                                    <span>Param Name</span>
                                                    <span>purchase_code</span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between align-items-center gap-4 ps-0 text-muted">
                                                    <span>Param Type</span>
                                                    <span>string</span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between align-items-center gap-4 ps-0 text-muted">
                                                    <span>Validate</span>
                                                    <span class="badge bg-danger">Required</span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between align-items-center gap-4 ps-0 text-muted">
                                                    <span>Description</span>
                                                    <span>Đây là mã định danh duy nhất liên quan đến một mặt hàng cụ thể khi mua.</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="api-docs-section mb-5" id="php-code">
                                <div class="api-content">
                                    <h6>PHP Code</h6>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="right-highlited">
                                                <div class="code mb">
                                                    <button class="right-highlited__button  clipboard-btn" data-clipboard-target="#php">
                                                        <i class="las la-copy"></i>
                                                    </button>
                                                    <pre class="m-0 rounded-0">
                                                <code class="language-php" id="php">
&lt;?php
    $parameters = [
        'purchase_code' => 'Product purchase code',
    ];

    $header = [
        'apikey:your api key'
    ];

    $url ='https://dev.c25tool.net/api/verify-purchase-code';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_POSTFIELDS,  $parameters);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);
?&gt;
                                                </code>
                                            </pre>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>



                            <div class="api-docs-section mb-5" id="error-response">
                                <div class="api-content">
                                    <h6>Error Response</h6>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="code">
                                                <pre class="">
                                            <code class="language-php h-100">
{
    "status": "error",
    "status_code": 422,
    "message": [
        "error" : [
            "The purchase code field is required.",
            "The selected purchase code is invalid.",
        ]
    ]
}
                                            </code>
                                        </pre>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="api-docs-section" id="success-response">
                                <div class="api-content">
                                    <h6>Success Response</h6>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="code">
                                                <pre class="">
                                            <code class="language-php h-100">

{
    "status": "success",
    "status_code": 200,
    "message": [
        "success" : [
            "Purchase code matched.",
        ]
    ]
}

                                            </code>
                                        </pre>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>

            <div id="confirmationModal" class="modal fade custom--modal" tabindex="-1" role="dialog">
                <div class="modal-dialog  modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Xác nhận!</h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <i class="las la-times"></i>
                            </button>
                        </div>
                        <form action="" method="POST">
                            <div class="modal-body">
                                <p class="question"></p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-dark btn-sm" data-bs-dismiss="modal">No</button>
                                <button type="submit" class="btn btn-sm btn-primary" name="generate">Yes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<script>
    $(document).on('click', '.confirmationBtn', function() {
        var modal = $('#confirmationModal');
        let data = $(this).data();
        modal.find('.question').text(`${data.question}`);
        modal.modal('show');
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        flatpickr("#purchase_date", {
            mode: "range",
            dateFormat: "Y-m-d"
        });
    });
</script>
<?php require_once(__DIR__ . '/footer.php'); ?>