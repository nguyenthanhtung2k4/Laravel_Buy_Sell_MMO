<?php
$title = '' . $TN->site('title');
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
<main>
    <section class="py-110">
        <div class="container">
            <div class="row mb-5">
                <div class="col-md-6">
                    <div class="settings-card">
                        <div class="settings-card-head">
                            <h4>NẠP THẺ CÀO</h4>
                        </div>
                        <div class="settings-card-body">
                            <div class="mb-3">
                                <label for="loaithe" class="form-label">Loại thẻ</label>
                                        <select name="paymentmethod" id="loaithe" class="custom-style-select nice-select select-dropdown mb-3">
                                            <option value="">Chọn nhà mạng</option>
                                            <option value="VIETTEL">VIETTEL</option>
                                            <option value="VINAPHONE">VINAPHONE</option>
                                            <option value="MOBIFONE">MOBIFONE</option>
                                            <option value="VIETNAMMOBILE">Vietnammobile</option>
                                            <option value="ZING">Zing</option>
                                            <option value="GARENA">Garena</option>
                                            <option value="GATE">GATE</option>
                                            <option value="VCOIN">Vcoin</option>
                                        </select>
                                    </div>
                            <div class="mb-3">
                                <label for="menhgia" class="form-label">Mệnh giá</label>
                                <select name="menhgia" id="menhgia" class="custom-style-select nice-select select-dropdown mb-3">
                                    <option value="">Chọn mệnh giá</option>
                                    <option value="10000">10.000</option>
                                    <option value="20000">20.000</option>
                                    <option value="30000">30.000</option>
                                    <option value="50000">50.000</option>
                                    <option value="100000">100.000</option>
                                    <option value="200000">200.000</option>
                                    <option value="300000">300.000</option>
                                    <option value="500000">500.000</option>
                                    <option value="1000000">1.000.000</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="serial" class="form-label">Số serial</label>
                                <input type="text" class="form-control shadow-none" id="seri" name="seri" placeholder="Nhập số serial" required="">
                            </div>
                            <div class="mb-3">
                                <label for="code" class="form-label">Mã thẻ</label>
                                <input type="text" class="form-control shadow-none" id="pin" name="pin" placeholder="Nhập mã thẻ" required="">
                            </div>
                            <?php if ($TN->site('status_captcha') == 1) : ?>
                                <div class="d-flex justify-content-center mb-2">
                                    <div id="cf-turnstile" class="cf-turnstile" data-sitekey="<?= $TN->site('site_key') ?>"></div>
                                </div>
                                <?php endif ?>
                            <div class="mb-3">
                            <input id="token" type="hidden" value="<?=$getUser['token'];?>">
                                <button class="btn btn-primary w-100" id="btnNapThe">Nạp thẻ ngay</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="settings-card">
                        <div class="settings-card-head">
                            <h4>LƯU Ý</h4>
                        </div>
                        <div class="settings-card-body">
                            <?=$TN->site('card_notice');?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <h3 class="text-24 fw-bold text-dark-300 mb-2">Lịch sử nạp thẻ</h3>
                <div class="overflow-x-auto">
                    <div class="w-100">
                        <table class="w-100 dashboard-table table text-nowrap">
                            <thead class="pb-3">
                                <tr>
                                    <th scope="col" class="py-2 px-4">NHÀ MẠNG</th>
                                    <th scope="col" class="py-2 px-4">SERIAL</th>
                                    <th scope="col" class="py-2 px-4">PIN</th>
                                    <th scope="col" class="py-2 px-4">MỆNH GIÁ</th>
                                    <th scope="col" class="py-2 px-4">THỰC NHẬN</th>
                                    <th scope="col" class="py-2 px-4">TRẠNG THÁI</th>
                                    <th scope="col" class="py-2 px-4">THỜI GIAN</th>
                                    <th scope="col" class="py-2 px-4">LÝ DO</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1;
                                    foreach ($TN->get_list("SELECT * FROM `cards` WHERE `username` = '" . $getUser['username'] . "' ") as $cards) : 
                                ?>
                                    <tr>
                                        <td class="text-dark">
                                            <?= $cards['loaithe']; ?>
                                        </td>
                                        <td>
                                            <p class="text-dark whitespace-no-wrap"><?= $cards['seri']; ?></p>
                                        </td>
                                        <td>
                                            <p class="text-dark whitespace-no-wrap"><?= $cards['pin']; ?></p>
                                        </td>
                                        <td>
                                            <p class="text-danger whitespace-no-wrap"><?= format_cash($cards['menhgia']); ?>đ</p>
                                        </td>
                                        <td>
                                            <p class="text-success whitespace-no-wrap"><?= format_cash($cards['thucnhan']); ?>đ</p>
                                        </td>
                                        <td>
                                            <p class="text-dark whitespace-no-wrap"><?= card($cards['status']); ?></p>
                                        </td>
                                        <td>
                                            <span class="status-badge pending"><?= $cards['createdate']; ?></span>
                                        </td>
                                        <td>
                                            <p class="text-dark whitespace-no-wrap"><?= $cards['note']; ?></p>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            </table>
                        </div>
                    <div class="d-flex justify-content-end">
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<script type="text/javascript">
    $("#btnNapThe").click(function() {
        $('#btnNapThe').html('<i class="fa fa-spinner fa-spin"></i> Loading...').prop('disabled',
            true);
        $.ajax({
            url: '<?=BASE_URL('')?>model/napthe',
        method: 'POST',
            dataType: "json",
            data: {
            loaithe: $("#loaithe").val(),
            menhgia: $("#menhgia").val(),
            seri: $("#seri").val(),
            pin: $("#pin").val(),
            <?php if ($TN->site('status_captcha') == 1) : ?>
                captcha_response: turnstile.getResponse(),
                <?php endif ?>
            token: $('#token').val()
            },
            success: function(respone) {
                if (respone.status == 'success') {
                    Swal.fire("Thành Công",
                    respone.msg,
                    "success"
                    );
                    setTimeout(function() {
                        window.location = "<?=BASE_URL('client/card')?>"
                    }, 1000);
                } else {
                    Swal.fire("Thất Bại",
                    respone.msg,
                    "error"
                    );
                }
                $('#btnNapThe').html('Nạp thẻ ngay').prop('disabled',
                    false);
            },
            error: function() {
                cuteToast({
                    type: "error",
                    message: 'Không thể xử lý',
                    timer: 5000
                });
                $('#btnNapThe').html('Nạp thẻ ngay').prop('disabled',
                    false);
            }

        });
    });
</script>
<?php require_once(__DIR__ . '/footer.php'); ?>