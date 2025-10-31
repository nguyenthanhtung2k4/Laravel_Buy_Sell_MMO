<?php
$title = 'Mã Hoá Ioncube Miễn Phí - ' . $TN->site('title');
$body['header'] = '
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>
';
$body['footer'] = '

';
require_once __DIR__ . '/../../../core/is_user.php';
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/nav.php';
//CheckLogin();
?>
<main>
    <section class="py-110 bg-offWhite">
        <div class="container">
            <div class="rounded-3">

                <section class="space-y-6">
                    <div class="row">
                        <div class="col-md-6 mb-5">
                            <div class="profile-info-card">
                                <div class="profile-info-header">
                                    <h4 class="text-18 fw-semibold text-dark-300">
                                        MÃ HOÁ IONCUBE
                                    </h4>
                                </div>
                                <div class="profile-info-body bg-white">
                                    <div class="mb-3">
                                        <label for="code_default" class="form-label">Đoạn Code Cần Mã Hoá</label>
                                    <textarea class="form-control" id="code_default" rows="10"></textarea>
                                </div>
                                <div class="mb-3">
                                        <label for="ioncube" class="form-label">Phiên bản ioncube</label>
                                        <select class="form-select shadow-none" id="ioncube" onchange="AiMonney()" required>
                                       <option value="">---- vui lòng chọn phiên bản ioncube ----</option>
                                            <option value="10.3">ionCube 10.3 </option>
                                        </select>
                                    </div>
                                <div class="mb-3">
                                        <label for="php" class="form-label">Phiên bản PHP</label>
                                        <select class="form-select shadow-none" id="php" required>
                                        </select>
                                    </div>
                                    <button type="button" class="btn btn-primary" id="btn_submit">Mã Hoá Ngay</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-5">
                            <div class="profile-info-card">
                                <div class="profile-info-header">
                                    <h4 class="text-18 fw-semibold text-dark-300">
                                        KẾT QUẢ
                                    </h4>
                                </div>
                                <div class="profile-info-body bg-white">
                                    <textarea class="form-control" id="code_encode" rows="10" readonly></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </section>
</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php require_once __DIR__ . '/footer.php'; ?>
<script src="/assets/js/ioncube.js"></script>