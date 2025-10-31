<?php
$title = 'Nạp Ngân Hàng Tự Động - ' . $TN->site('title');
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
$bank = $TN->get_row("SELECT * FROM `bank`");

if ($bank['short_name'] == 'MOMO') {
         $qr_code_url = "https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=2|99|" . $bank['accountNumber'] . "|||0|0||" . $TN->site('noidungnap').$getUser['id'] . "|transfer_myqr"; 
    } 
    elseif ($bank['short_name'] == 'THESIEURE') {
        $qr_code_url = "https://imgur.com/GEHuS50.png";
    } 
?>
<main>
    <section class="py-110">
        <div class="container">
            <div class="row mb-5">
                <div class="overflow-x-auto">
                    <div class="w-100"></div>
                </div>
                <?php foreach($TN->get_list("SELECT * FROM `bank` ") as $bank) { ?>
                    <div class="col-md-4">
                        <div class="bg-white shadow-sm rounded border">
                            <div class="border-b border-blue-500 ">
                                <div class="py-3 text-center">
                                    <img src="https://api.vietqr.io/<?=$bank['short_name'];?>/<?=$bank['accountNumber'];?>/0/<?=$TN->site('noidungnap').$getUser['id'];?>/qronly2.jpg?accountName= <?=$bank['accountName'];?>"
                                        class="w-100">
                                </div>
                                <div class="p-4 text-zinc-900">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>STK  <?=$bank['short_name'];?>:</span>
                                        <span class="copy cursor-pointer text-success" data-clipboard-text="<?=$bank['accountNumber'];?>"><?=$bank['accountNumber'];?> <i class="bx bx-copy"></i></span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Chủ TK:</span>
                                        <span> <?=$bank['accountName'];?></span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>Nội Dung:</span>
                                        <span
                                            class="copy cursor-pointer text-danger" data-clipboard-text="<?=$TN->site('noidungnap').$getUser['id'];?>"><?=$TN->site('noidungnap').$getUser['id'];?>                                            <i class="bx bx-copy"></i> </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php }?>
            </div>
            <div class="row">
                <h3 class="text-24 fw-bold text-dark-300 mb-2">Lịch sử nạp tiền</h3>
                <div class="overflow-x-auto">
                    <div class="w-100">
                        <table class="w-100 dashboard-table table text-nowrap">
                            <thead class="pb-3">
                                <tr>
                                    <th scope="col" class="py-2 px-4">PHƯƠNG THỨC NẠP</th>
                                    <th scope="col" class="py-2 px-4">MÃ GD</th>
                                    <th scope="col" class="py-2 px-4">SỐ TIỀN</th>
                                    <th scope="col" class="py-2 px-4">NỘI DUNG</th>
                                    <th scope="col" class="py-2 px-4">THỜI GIAN</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php $i = 0; foreach($TN->get_list("SELECT * FROM `invoices` WHERE `user_id` = '".$getUser['id']."' ") as $row) { ?>
                                <tr>
                                    <td class="text-dark"><?=$row['payment_method'];?></td>
                                    <td class="text-dark"><?=$row['trans_id'];?></td>
                                    <td class="text-dark"><?=format_cash($row['amount']);?></td>
                                    <td class="text-dark"><?=$TN->site('noidungnap').$getUser['id'];?> </td>
                                    <td>
                                    <span class="status-badge in-active">
                                        <?=date('d-m-Y H:i:s',$row['create_time'])?>
                                    </span>
                                    </td>
                                </tr>
                            <?php }?>
                            </tbody>
                        </table>
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
</script>
<?php require_once(__DIR__ . '/footer.php'); ?>