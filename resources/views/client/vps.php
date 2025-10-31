<?php
$title = 'Thanh Toán VPS - ' . $TN->site('title');
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
//CheckLogin();
?>
<?php
 if (isset($_GET['id'])) {
     $id = xss($_GET['id']);
     $row = $TN->get_row("SELECT * FROM `tbl_list_vps` WHERE `id` = '$id'");

     if (!$row) {
         echo '<script>alert("Không tồn tại!"); window.history.back();</script>';
         exit;
     }
 } else {
     echo '<script>alert("Không tồn tại!"); window.history.back();</script>';
     exit;
 }
?>

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
                            <li class="breadcrumb-item" aria-current="page">Cloud VPS</li>
                        </ol>
                    </nav>
                    <h2 class="breadcrumb-title">
                        Đăng ký dịch vụ VPS - <?=$row['namevps']?>
                    </h2>
                </div>
            </div>
        </div>
    </div>

    <section class="py-110">
        <div class="container">

            <div class="row">
                <div class="col-md-8">
                    <div class="shadow-sm p-2">
                        <div class="row">
                            <label class="form-label w-100">Mua thêm</label>

                            <div class="col-12 col-sm-4 mb-1 text-center">
                                <label class="d-block">CPU (1ĐV = 1Core)</label>
                                <div class="touchspin-wrapper d-flex align-items-center justify-content-center">
                                    <button class="decrement-touchspin btn btn-primary px-2 py-1 rounded"><i class='bx bx-minus'></i></button>
                                    <input class="input-touchspin form-control text-center border-primary mx-1" id="cpu" type="number" value="0" onkeyup="totalPayment()" readonly>
                                    <button class="increment-touchspin btn btn-primary px-2 py-1 rounded"><i class='bx bx-plus'></i></button>
                                </div>
                            </div>

                            <div class="col-12 col-sm-4 mb-1 text-center">
                                <label class="d-block">RAM (1ĐV = 1GB)</label>
                                <div class="touchspin-wrapper d-flex align-items-center justify-content-center">
                                    <button class="decrement-touchspin btn btn-success px-2 py-1 rounded"><i class='bx bx-minus'></i></button>
                                    <input class="input-touchspin form-control text-center border-success mx-1" id="ram" type="number" value="0" onkeyup="totalPayment()" readonly>
                                    <button class="increment-touchspin btn btn-success px-2 py-1 rounded"><i class='bx bx-plus'></i></button>
                                </div>
                            </div>

                            <div class="col-12 col-sm-4 mb-1 text-center">
                                <label class="d-block">DISK (1ĐV = 10GB)</label>
                                <div class="touchspin-wrapper d-flex align-items-center justify-content-center">
                                    <button class="decrement-touchspin btn btn-danger px-2 py-1 rounded"><i class='bx bx-minus'></i></button>
                                    <input class="input-touchspin form-control text-center border-danger mx-1" id="disk" type="number" value="0" onkeyup="totalPayment()" readonly>
                                    <button class="increment-touchspin btn btn-danger px-2 py-1 rounded"><i class='bx bx-plus'></i></button>
                                </div>
                            </div>

                            <label class="form-label mt-3 w-100">Thời gian</label>

                           <?php
                           $price = $row['price'];
                           $cycles = [1, 2, 3, 6, 9, 12];
                           ?>
                           <div class="row g-2 mb-3" id="paymentCycles">
                                <?php foreach ($cycles as $month): ?>
                                   <div class="col-6 col-md-4">
                                <button class="btn border w-100 payment-cycle"
                                               data-month="<?= $month ?>"
                                                data-price="<?= $price * $month ?>">
                                            <div><?= $month ?> Tháng</div>
                                            <div class="text-danger"><?= format_cash($price * $month) ?> đ</div>
                                        </button>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <label class="form-label mt-3 w-100">Hệ điều hành</label>

                            <!-- OS Section -->
                                <div class="col-6 col-sm-6 col-md-4 mb-2">
                                    <div role="button" class="card-wrapper border rounded os p-3 text-center" data-osid="1" id="os1">
                                        <div class="d-flex justify-content-center align-items-center mb-2">
                                            <img src="/assets/images/windows.png" alt="Windows Server 2012 R2" width="50">
                                        </div>
                                        <p class="m-0">Windows Server 2012 R2</p>
                                    </div>
                                </div>
                                <div class="col-6 col-sm-6 col-md-4 mb-2">
                                    <div role="button" class="card-wrapper border rounded os p-3 text-center" data-osid="10" id="os10">
                                        <div class="d-flex justify-content-center align-items-center mb-2">
                                            <img src="/assets/images/windows.png" alt="Windows Server 2012r2 MD" width="50">
                                        </div>
                                        <p class="m-0">Windows Server 2012r2 MD</p>
                                    </div>
                                </div>
                                <div class="col-6 col-sm-6 col-md-4 mb-2">
                                    <div role="button" class="card-wrapper border rounded os p-3 text-center" data-osid="2" id="os2">
                                        <div class="d-flex justify-content-center align-items-center mb-2">
                                            <img src="/assets/images/windows.png" alt="Windows Server 2016" width="50">
                                        </div>
                                        <p class="m-0">Windows Server 2016</p>
                                    </div>
                                </div>
                                <div class="col-6 col-sm-6 col-md-4 mb-2">
                                    <div role="button" class="card-wrapper border rounded os p-3 text-center" data-osid="4" id="os4">
                                        <div class="d-flex justify-content-center align-items-center mb-2">
                                            <img src="/assets/images/windows.png" alt="Windows Server 2019" width="50">
                                        </div>
                                        <p class="m-0">Windows Server 2019</p>
                                    </div>
                                </div>
                                <div class="col-6 col-sm-6 col-md-4 mb-2">
                                    <div role="button" class="card-wrapper border rounded os p-3 text-center" data-osid="5" id="os5">
                                        <div class="d-flex justify-content-center align-items-center mb-2">
                                            <img src="/assets/images/windows.png" alt="Windows 10 64bit" width="50">
                                        </div>
                                        <p class="m-0">Windows 10 64bit</p>
                                    </div>
                                </div>
                                <div class="col-6 col-sm-6 col-md-4 mb-2">
                                    <div role="button" class="card-wrapper border rounded os p-3 text-center" data-osid="3" id="os3">
                                        <div class="d-flex justify-content-center align-items-center mb-2">
                                            <img src="/assets/images/centos.png" alt="Linux CentOS 7 64bit" width="50">
                                        </div>
                                        <p class="m-0">Linux CentOS 7 64bit</p>
                                    </div>
                                </div>
                                <div class="col-6 col-sm-6 col-md-4 mb-2">
                                    <div role="button" class="card-wrapper border rounded os p-3 text-center" data-osid="6" id="os6">
                                        <div class="d-flex justify-content-center align-items-center mb-2">
                                            <img src="/assets/images/ubuntu.png" alt="Linux Ubuntu-20.04" width="50">
                                        </div>
                                        <p class="m-0">Linux Ubuntu-20.04</p>
                                    </div>
                                </div>
                                <div class="col-6 col-sm-6 col-md-4 mb-2">
                                    <div role="button" class="card-wrapper border rounded os p-3 text-center" data-osid="7" id="os7">
                                        <div class="d-flex justify-content-center align-items-center mb-2">
                                            <img src="/assets/images/ubuntu.png" alt="Linux Ubuntu-22.04" width="50">
                                        </div>
                                        <p class="m-0">Linux Ubuntu-22.04</p>
                                    </div>
                                </div>
                                <div class="col-6 col-sm-6 col-md-4 mb-2">
                                    <div role="button" class="card-wrapper border rounded os p-3 text-center" data-osid="8" id="os8">
                                        <div class="d-flex justify-content-center align-items-center mb-2">
                                            <img src="/assets/images/debian.png" alt="Debian 11" width="50">
                                        </div>
                                        <p class="m-0">Debian 11</p>
                                    </div>
                                </div>
                                <div class="col-6 col-sm-6 col-md-4 mb-2">
                                    <div role="button" class="card-wrapper border rounded os p-3 text-center" data-osid="9" id="os9">
                                        <div class="d-flex justify-content-center align-items-center mb-2">
                                            <img src="/assets/images/almalinux.png" alt="AlmaLinux 8" width="50">
                                        </div>
                                        <p class="m-0">AlmaLinux 8</p>
                                    </div>
                                </div>
                            </div>
                    </div>

                </div>
                <div class="col-md-4">
                    <div class="shadow-sm rounded-lg p-2 sticky-top" style="top: 20px;">
                        <div class="card-header">
                            <h4 class="h5 font-semibold">Thông Tin Thanh Toán</h4>
                        </div>
                        <div class="card-body">
                            <p class="mb-1">GÓI: <?=$row['namevps']?></p>

                            <div class="d-flex justify-content-between mb-1">
                                <span>Chu Kỳ Thanh Toán</span>
                                <span class="text-danger" id="cycle">1 Tháng</span>
                            </div>

                            <div class="d-flex justify-content-between mb-1">
                                <span>CPU (Mua Thêm)</span>
                                <span class="text-danger" id="totalcpu">0 Core</span>
                            </div>

                            <div class="d-flex justify-content-between mb-1">
                                <span>Ram (Mua Thêm)</span>
                                <span class="text-danger" id="totalram">0 GB</span>
                            </div>

                            <!-- Additional Disk -->
                            <div class="d-flex justify-content-between mb-1">
                                <span>Disk (Mua Thêm)</span>
                                <span class="text-danger" id="totaldisk">0 GB</span>
                            </div>

                            <!-- Coupon Code Input -->
                            <label class="mt-3 mb-1">Mã giảm giá</label>
                            <input type="text" class="form-control mb-3" id="coupon" onchange="totalPayment()" onkeyup="totalPayment()" placeholder="Mã giảm giá nếu có">

                            <!-- Total Payment -->
                            <h4 class="mb-1">Tổng thanh toán</h4>
                            <h3 class="mb-1 fw-bold text-danger"><span id="total"><?= format_cash($row['price']); ?></span>₫</h3>

                            <!-- Order Button -->
                            <button onclick="confirmAction(<?=$row['id']?>)" class="btn btn-primary w-100" id="btnOrder">Thanh Toán</button>

                            <!-- Back Link -->
                            <a href="/cloudvps" class="d-block text-center mt-2 text-primary">Quay lại</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
</main>
<script>
    var vpsid = <?=$row['id']?>;
    let billingcycle = "monthly";
    let osid = "";
    let cpuPrice = 0;
    let ramPrice = 0;
    let diskPrice = 0;

    const items = document.querySelectorAll('.item');
    items.forEach(item => {
        item.addEventListener('click', () => {
            const duration = item.dataset.duration;
            const cycle = item.dataset.cycle;
            items.forEach(i => {
                i.classList.remove('border-2', 'active-select');
            });
            item.classList.add('border-2', 'active-select');
            billingcycle = duration;
            document.getElementById("cycle").innerHTML = cycle;
            totalPayment();
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.payment-cycle').forEach(button => {
            button.addEventListener('click', () => {
                document.querySelectorAll('.payment-cycle').forEach(btn => btn.classList.remove('active-select'));
                button.classList.add('active-select');
                billingcycle = button.dataset.month;
                totalPayment();
            });
        });

        const firstBtn = document.querySelector('.payment-cycle');
        if (firstBtn) firstBtn.classList.add('active-select');

        // Gọi API lấy giá cấu hình VPS
        fetch("/ajaxs/client/buy-vps.php", {
            method: "POST",
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: "action=cloudvps&priceOnly=1"
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === "success") {
                cpuPrice = data.cpu_price;
                ramPrice = data.ram_price;
                diskPrice = data.disk_price;
                totalPayment();
            } else {
                console.error("Không thể lấy giá cấu hình VPS.");
            }
        });
    });

    function updateos(value) {
        osid = value;
    }

    function format_cash(number) {
        return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    let getInputByClass = document.getElementsByClassName("input-touchspin");

    (function () {
        Array.from(getInputByClass).forEach((elem, i) => {
            let inputData = parseInt(elem.getAttribute("value"));

            let isIncrement = elem.parentNode.querySelectorAll(".increment-touchspin");
            let isDecrement = elem.parentNode.querySelectorAll(".decrement-touchspin");

            if (isIncrement.length > 0) {
                isIncrement[0].addEventListener("click", () => {
                    if (inputData < 10) {
                        inputData++;
                        elem.setAttribute("value", inputData);
                        updateElements(elem.getAttribute("id"), inputData);
                        totalPayment();
                    }
                });
            }

            if (isDecrement.length > 0) {
                isDecrement[0].addEventListener("click", () => {
                    if (inputData > 0) {
                        inputData--;
                        elem.setAttribute("value", inputData);
                        updateElements(elem.getAttribute("id"), inputData);
                        totalPayment();
                    }
                });
            }
        });
    })();

    function updateElements(type, value) {
        document.getElementById("total" + type).innerHTML = value + (type === "disk" ? "0" : "") + (type === "cpu" ? " Core" : " GB");
    }

    function totalPayment() {
        const cpu = parseInt($("#cpu").val());
        const ram = parseInt($("#ram").val());
        const disk = parseInt($("#disk").val());
        const coupon = $("#coupon").val();

        const cpuTotal = cpu * cpuPrice;
        const ramTotal = ram * ramPrice;
        const diskTotal = parseInt(disk / 10) * diskPrice;

        // Gửi yêu cầu AJAX để tính lại giá chính xác từ server (bao gồm khuyến mãi nếu có)
        $('#total').html('<i class="fa fa-spinner fa-spin"></i> Đang xử lý...');

        $.ajax({
            url: "/ajaxs/client/buy-vps.php",
            method: "POST",
            dataType: "JSON",
            data: {
                csrf_token: csrf_token,
                vpsid: vpsid,
                billingcycle: billingcycle,
                cpu: cpu,
                ram: ram,
                disk: disk,
                coupon: coupon,
                action: 'cloudvps'
            },
            success: function (respone) {
                if (respone.total) {
                    $("#total").html(format_cash(respone.total) + 'đ');
                } else {
                    $("#total").html('<span class="text-danger">Không xác định</span>');
                }
            },
            error: function () {
                showMessage('Không thể tính kết quả thanh toán', 'error');
                $("#total").html('<span class="text-danger">Lỗi</span>');
            }
        });
    }

    const confirmAction = (id) => {
        Swal.fire({
            title: 'Xác Nhận!',
            text: "Bạn đồng ý thực hiện thanh toán vps?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Đồng ý',
            cancelButtonText: 'Hủy'
        }).then(async (confirm) => {
            if (confirm.isConfirmed) {
                await Item(id);
            }
        });
    }

    const Item = async (id) => {
        Swal.fire({
            icon: "info",
            title: "Đang xử lý!",
            html: "Không được tắt trang này, vui lòng đợi trong giây lát!",
            timerProgressBar: true,
            allowOutsideClick: false,
            allowEscapeKey: false,
            allowEnterKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: '/ajaxs/client/buy-vps.php',
            method: "POST",
            dataType: "JSON",
            data: {
                csrf_token: csrf_token,
                vpsId: id,
                os: osid,
                billingcycle: billingcycle,
                cpu: $("#cpu").val(),
                ram: $("#ram").val(),
                disk: $("#disk").val(),
                coupon: $("#coupon").val(),
            },
            success: function (result) {
                if (result.status == 'success') {
                    Swal.fire('Thành công',
                        `${result.msg}`,
                        'success').then(() => {
                        window.location.href = '/user/history/vps';
                    });
                } else {
                    Swal.fire('Thất Bại', result.msg, 'error');
                }
            },
            error: function (xhr, status, error) {
                Swal.fire('Thất Bại', xhr.responseText, 'error');
            }
        });
    }
</script>


<?php require_once(__DIR__ . '/footer.php'); ?>