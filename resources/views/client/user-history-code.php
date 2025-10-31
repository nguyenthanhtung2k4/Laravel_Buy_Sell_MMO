<?php
$title = 'Lịch Sử Mua Mã Nguồn | ' . $TN->site('title');
$body['header'] = '
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
     <div class="row">
                <div class="col-md-12">
                    <h3 class="text-24 fw-bold text-dark-300 mb-2">LỊCH SỬ MUA MÃ NGUỒN</h3>
                    <div class="overflow-x-auto">
                        <div class="w-100">
                            <table id="codeTable" class="w-100 dashboard-table table text-nowrap">
                                <thead class="pb-3">
                                    <tr>
                                        <th scope="col" class="py-2 px-4">Sản phẩm</th>
                                        <th scope="col" class="py-2 px-4">Thanh toán</th>
                                        <th scope="col" class="py-2 px-4">Vào lúc</th>
                                        <th scope="col" class="py-2 px-4">Code Kích hoạt</th>
                                        <th scope="col" class="py-2 px-4">Thao tác</th>
                                    </tr>
                                                </thead>
                                                     <?php $i=0; foreach ($TN->get_list("SELECT * FROM `tbl_his_code` WHERE `user_id` = '" . $getUser['id'] . "' ORDER BY `id` DESC") as $row) {?>                                         <tbody>
                                                         <tr>
                                                             <td>
                                                <div class="d-flex gap-3 align-items-center project-name">
                                                    <div class="rounded-3 admin-job-icon">
                                                        <img src="<?=getCode($row['product_id'], 'images');?>" alt="">
                                                    </div>
                                                    <div>
                                                        <p class="text-dark" role="button" onclick="location.href='/client/view-code/<?=getCode($row['product_id'], 'code');?>';">
                                                            <?=getCode($row['product_id'], 'name');?>                   </p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-dark"><?= format_cash($row['price']); ?>đ</td>
                                            <td>
                                                <span class="status-badge pending">
                                                    <?=format_date($row['create_date']);?>
                                                </span>
                                            </td>
                                            <td>


    <span id="magd_<?=$row['id'];?>" class="text-dark">
        <?=$row['magd'];?>
    </span>
    
    <button 
        class="btn btn-sm btn-outline-primary"
        onclick="copyMagd('magd_<?=$row['id'];?>')"
        title="Sao chép mã giao dịch"
    >
        <i class="fas fa-copy"></i> Copy 
    </button>
</td>



                                            <td class="text-dark text-nowrap">
                                                <div class="d-flex flex-wrap gap-1">
                                                    <a href="<?=buiducthanh_dec(getCode($row['product_id'], 'link_down'));?>" class="btn btn-outline--base btn--sm">
                                                        <i class="fa fa-download"></i> Tải xuống </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php }?>
                                </tbody>
                                </table>
                                        </div>
                                        <div class="d-flex justify-content-end">
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        flatpickr("#purchase_date", {
            mode: "range",
            dateFormat: "Y-m-d"
        });
    });
    document.addEventListener('DOMContentLoaded', function() {
        $('#codeTable').DataTable();
    });


    /**
 * Hàm copy nội dung từ một phần tử HTML (dựa vào ID)
 * và hiển thị thông báo.
 * @param {string} elementId - ID của phần tử chứa nội dung cần sao chép.
 */
function copyMagd(elementId) {
    // 1. Lấy nội dung cần sao chép
    const textToCopy = document.getElementById(elementId).textContent.trim();

    // 2. Sử dụng Clipboard API (phương pháp hiện đại và được khuyến nghị)
    navigator.clipboard.writeText(textToCopy)
        .then(() => {
            // Sao chép thành công
            alert('Đã sao chép mã giao dịch: ' + textToCopy);
            console.log('Mã giao dịch đã được sao chép thành công!');
        })
        .catch(err => {
            // Xảy ra lỗi (ví dụ: trình duyệt không cho phép hoặc lỗi khác)
            console.error('Không thể sao chép:', err);
            // Có thể dùng alert thay thế hoặc hiển thị thông báo lỗi
            alert('Lỗi: Không thể sao chép tự động. Vui lòng sao chép thủ công.');
        });
}

// Lưu ý: Đảm bảo trang web của bạn đang chạy qua HTTPS (rất quan trọng)
// vì API navigator.clipboard thường chỉ hoạt động trên HTTPS hoặc localhost.
</script>



<?php require_once __DIR__ . '/footer.php'; ?>