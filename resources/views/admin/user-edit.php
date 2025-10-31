<?php 
if (!defined('IN_SITE')) {
    die('The Request Not Found');
}
$title = 'Chỉnh Sửa Thành Viên | ' . $TN->site('title');
$body = [
    'title' => 'Chỉnh sửa thành viên'
];
$body['header'] = '
';
$body['footer'] = '
';
require_once(__DIR__.'/Header.php');
require_once(__DIR__.'/Sidebar.php');
require_once(__DIR__ . '/../../../core/is_user.php');
CheckLogin();
CheckAdmin();
?>
<?php
if(isset($_GET['id']) && $getUser['level'] == 1)
{
    $row = $TN->get_row(" SELECT * FROM `users` WHERE `id` = '".check_string($_GET['id'])."'  ");
    if(!$row)
    {
        
    }
}
else
{
    
}
if(isset($_POST['Save']) && $getUser['level'] == 1)
{
    if($row['money'] != check_string($_POST['money']))
    {
    $TN->insert("log_balance", array(
        'money_before' => getUser($row['id'], 'money'),
        'money_change' => check_string($_POST['money']) - getUser($row['id'], 'money'),
        'money_after' => check_string($_POST['money']),
        'time' => time(),
        'content' => 'Admin thay đổi số dư ',
        'user_id' => $row['id']
    ));
    }
    $isInsert= $TN->update("users", array(
        'username'       => check_string($_POST['username']),
        'level'       => check_string($_POST['admin']),
        'seller'       => check_string($_POST['seller']),
        'money'       => check_string($_POST['money']),
        'discount'       => check_string($_POST['discount']),
        'banned'       => check_string($_POST['banned']),
    ), " `id` = '".$row['id']."' ");
    
    if ($isInsert) {
        die('<script type="text/javascript">if(!alert("Lưu thành công!")){window.history.back().location.reload();}</script>');
    } else {
        die('<script type="text/javascript">if(!alert("Lưu thất bại!")){window.history.back().location.reload();}</script>');
    }
}
if (isset($_POST['cong_tien']) && $getUser['level'] == 1) {
    if ($_POST['amount'] <= 0) {
        die('<script type="text/javascript">if(!alert("Amount không hợp lệ !")){window.history.back().location.reload();}</script>');
    }
    $amount = check_string($_POST['amount']);
    $reason = check_string($_POST['reason']);
    /* Xử lý cộng tiền */
    PlusCredits($row['id'], $amount, $reason);
    die('<script type="text/javascript">if(!alert("Cộng tiền thành công !")){window.history.back().location.reload();}</script>');
}
if (isset($_POST['tru_tien']) && $getUser['level'] == 1) {
    if ($_POST['amount'] <= 0) {
        die('<script type="text/javascript">if(!alert("Amount không hợp lệ !")){window.history.back().location.reload();}</script>');
    }
    $amount = check_string($_POST['amount']);
    $reason = check_string($_POST['reason']);
    /* Xử lý trừ tiền */
    RemoveCredits($row['id'], $amount, $reason);
    die('<script type="text/javascript">if(!alert("Trừ tiền thành công !")){window.history.back().location.reload();}</script>');
}
?>
<div class="main-content app-content">
    <div class="container-fluid">
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0"><a type="button"
                    class="btn btn-dark btn-raised-shadow btn-wave btn-sm me-1" href="<?=BASE_URL('admin/ListUsers')?>"><i
                        class="fa-solid fa-arrow-left"></i></a> Chỉnh sửa thành viên <?=$row['username']?></h1>
        </div>
        <div class="row gx-5">
            <div class="col-12">
                <div class="mt-4 mt-md-0">
                    <button type="button" data-bs-toggle="modal" data-bs-target="#modal-addCredit"
                        class="btn btn-sm btn-wave btn-success me-1 mb-3 push">
                        <i class="fa fa-fw fa-plus"></i> Cộng số dư
                    </button>
                    <button type="button" data-bs-toggle="modal" data-bs-target="#modal-removeCredit"
                        class="btn btn-sm btn-wave btn-danger me-1 mb-3 push">
                        <i class="fa fa-fw fa-minus"></i> Trừ số dư
                    </button>
                </div>
            </div>
            <div class="col-12">
                <div class="card custom-card shadow-none mb-4">
                    <div class="card-body">
                        <form action="" method="POST" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label class="form-label">Username (<span class="text-danger">*</span>)</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fa-solid fa-user"></i>
                                            </span>
                                            <input type="text" class="form-control" value="<?=$row['username']?>"
                                                name="username" required>
                                        </div>
                                    </div>
                                </div>
                            <div class="col-md-6">
                                    <div class="mb-4">
                                        <label class="form-label">Email (<span class="text-danger">*</span>)</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fa-solid fa-envelope"></i>
                                            </span>
                                            <input type="email" class="form-control" value="<?=$row['email']?>"
                                                name="email" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-4">
                                        <label class="form-label">Token (<span class="text-danger">*</span>)</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fa-solid fa-key"></i>
                                            </span>
                                            <input type="password" class="form-control" id="token_input"
                                                value="<?=$row['token']?>" name="token" required>
                                            <button type="button" id="show_token" class="btn btn-danger"
                                                onclick="toggleTokenVisibility()">Show</button>
                                        </div>
                                        <script>
                                        function toggleTokenVisibility() {
                                            var input = document.getElementById('token_input');
                                            var button = document.getElementById('show_token');
                                            if (input.type === 'password') {
                                                input.type = 'text';
                                                button.textContent = 'Hide';
                                            } else {
                                                input.type = 'password';
                                                button.textContent = 'Show';
                                            }
                                        }
                                        </script>
                                        <small>Bảo mật thông tin này vì kẻ xấu có thể thực hiện đăng nhập tài khoản bằng
                                            Token</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-4">
                                        <label class="form-label">Số Tiền</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fa-solid fa-wallet"></i>
                                            </span>
                                            <input type="text" class="form-control" placeholder="**********"
                                                name="money" value="<?=$row['money']?>">
                                        </div>
                                        <small>nhập số tiền để thay đổi hệ thống sẽ ghi lại log khi admin thực hiện</small>
                                    </div>
                                </div>
                              <div class="col-md-4">
                                    <div class="mb-4">
                                        <label class="form-label">Chiết khấu giảm giá (<span
                                                class="text-danger">*</span>)</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fa-solid fa-percent"></i>
                                            </span>
                                            <input type="text" class="form-control" value="<?=$row['discount']?>"
                                                name="discount">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-4">
                                        <label class="form-label">Chức Vụ Role (<span
                                                class="text-danger">*</span>)</label>
                                        <select class="form-control select2bs4" name="admin">
                                            <option value="0" <?= ($row['level'] == '0') ? 'selected' : ''; ?>>User (Khách
                                                hàng)
                                            </option>
                                                                                        <option value="1"
                                                <?= ($row['level'] == '1') ? 'selected' : ''; ?>>
                                                Super Admin
                                            </option>
                                                                                                                                </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-4">
                                        <label class="form-label">Người Bán Hàng (<span
                                                class="text-danger">*</span>)</label>
                                        <select class="form-control select2bs4" name="seller">
                                            <option value="0" <?= ($row['seller'] == '0') ? 'selected' : ''; ?>>Không
                                            </option>
                                                                                        <option value="1"
                                                <?= ($row['seller'] == '1') ? 'selected' : ''; ?>>
                                                Đúng
                                            </option>
                                                                                                                                </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-4">
                                        <div class="mb-4">
                                            <label class="form-label">Banned (<span
                                                    class="text-danger">*</span>)</label>
                                            <select class="form-control select2bs4" name="banned">
                                                <option  value="1" <?= ($row['banned'] == '1') ? 'selected' : ''; ?>>
                                                    Banned</option>
                                                <option <?= ($row['banned'] == '0') ? 'selected' : ''; ?> value="0">Live
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                             
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-4">
                                        <label class="form-label">Ví chính</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fa-solid fa-wallet"></i>
                                            </span>
                                            <input type="text" class="form-control"
                                                value="<?=format_cash($row['money'])?>đ" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-4">
                                        <label class="form-label">Tổng tiền nạp</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fa-solid fa-money-bill"></i>
                                            </span>
                                            <input type="text" class="form-control"
                                                value="<?=format_cash($row['total_money'])?>đ" disabled>
                                        </div>
                                    </div>
                                </div>
                            <div class="col-md-4">
                                    <div class="mb-4">
                                        <label class="form-label">Số dư đã sử dụng</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class='bx bxs-wallet-alt'></i>
                                            </span>
                                            <input type="text" class="form-control"
                                                value="<?=format_cash($getUser['total_money']-$getUser['money']);?>đ"
                                                disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label class="form-label">Địa chỉ IP dùng để đăng nhập</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fa-solid fa-wifi"></i>
                                            </span>
                                            <input type="text" class="form-control" value="<?=$row['ip']?>" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label class="form-label">Thiết bị đăng nhập</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fa-solid fa-desktop"></i>
                                            </span>
                                            <input type="text" class="form-control" value="<?=$row['device']?>"
                                                disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label class="form-label">Đăng ký tài khoản vào lúc</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fa-solid fa-calendar-days"></i>
                                            </span>
                                            <input type="text" class="form-control" value="<?=format_date($row['create_date'])?>"
                                                disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label class="form-label">Đăng nhập gần nhất vào lúc</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fa-solid fa-calendar-days"></i>
                                            </span>
                                            <input type="text" class="form-control" value="<?=date('H:i:s d-m-Y',$row['time_session'])?>"
                                                disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <a type="button" class="btn btn-danger" href="<?=BASE_URL('admin/ListUsers')?>"><i
                                    class="fa fa-fw fa-undo"></i> Back</a>
                            <button type="submit" name="Save" class="btn btn-primary"><i class="bi bi-download"></i>
                                Save</button>
                        </form>
                    </div>
                </div>
            </div>
             

        </div>

    </div>
</div>
<br>
<?php 
    require_once(__DIR__."/Footer.php");
?>
<div class="modal fade" id="modal-addCredit" tabindex="-1" aria-labelledby="modal-block-popout" role="dialog"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h6 class="modal-title" id="staticBackdropLabel2"><i class="fa fa-plus"></i> CỘNG SỐ DƯ
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning alert-dismissible fade show custom-alert-icon shadow-sm"
                      role="alert">
                        Khi Bạn <b>Cộng Tiền</b>, số dư sẽ được cộng vào tài khoản user nhưng khi bạn chạy auto bank thì nó sẽ cộng thêm 1 lần nữa vui lòng cân nhắc trước khi sử.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i
                                class="bi bi-x"></i></button>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-4 col-form-label" for="example-hf-email">Loại ví:</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="wallet" id="walletSelect">
                                <option value="1">VÍ CHÍNH</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <label class="col-sm-4 col-form-label" for="example-hf-email">Amount:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="amount" id="amountInput"
                                placeholder="Nhập số tiền cần cộng" required>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-4 col-form-label"
                            for="example-hf-email">Lý do (nếu có):</label>
                        <div class="col-sm-8">
                            <textarea class="form-control" name="reason"></textarea>
                        </div>
                    </div>
                    <center>Nhấn vào nút Submit để thực hiện cộng <b id="amountDisplay" style="color:red;">0</b> vào <b
                            id="walletDisplay">VÍ CHÍNH</b></center>
                </div>
                <script>
                document.addEventListener("DOMContentLoaded", function() {
                    var selectWallet = document.getElementById('walletSelect');
                    var amountInput = document.getElementById('amountInput');
                    var amountDisplay = document.getElementById('amountDisplay');
                    var walletDisplay = document.getElementById('walletDisplay');
                    var noticeDebit = document.getElementById('notice_debit');

                    // Hiển thị giá trị mặc định cho số tiền và loại ví
                    updateAmountDisplay();
                    updateWalletDisplay();

                    // Lắng nghe sự kiện input trên input số tiền
                    amountInput.addEventListener('input', function() {
                        updateAmountDisplay();
                    });


                    function updateAmountDisplay() {
                        // Lấy giá trị từ input
                        var inputValue = amountInput.value;

                        // Kiểm tra nếu giá trị rỗng hoặc không phải là số
                        if (!inputValue || isNaN(inputValue)) {
                            amountDisplay.textContent =
                                '0'; // Hiển thị 0 nếu không có giá trị hoặc giá trị không hợp lệ
                            return;
                        }

                        // Định dạng số tiền và hiển thị vào amountDisplay
                        var formattedAmount = formatNumber(inputValue);
                        amountDisplay.textContent = formattedAmount;
                    }

                    function formatNumber(value) {
                        return parseFloat(value).toLocaleString('vi-VN');
                    }

                    function updateWalletDisplay() {
                        // Hiển thị loại ví được chọn
                        walletDisplay.textContent = selectWallet.options[selectWallet.selectedIndex].text;
                    }
                });
                </script>



                <div class="modal-footer">
                    <button type="button" class="btn btn-hero btn-danger" data-bs-dismiss="modal"><i
                            class="fa fa-fw fa-times me-1"></i> Close</button>
                    <button type="submit" name="cong_tien" class="btn btn-hero btn-success"><i
                            class="fa fa-fw fa-plus me-1"></i> Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-removeCredit" tabindex="-1" aria-labelledby="modal-block-popout" role="dialog"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h6 class="modal-title" id="staticBackdropLabel2"><i class="fa fa-minus"></i> Balance                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-4">
                        <label class="col-sm-4 col-form-label" for="example-hf-email">Loại ví:</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="wallet" id="walletSelect2">
                                <option value="1">VÍ CHÍNH</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-4 col-form-label" for="example-hf-email">Amount</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="amount" id="amountInput2"
                                placeholder="Nhập số tiền cần trừ" required>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-sm-4 col-form-label" for="example-hf-email">Reason</label>
                        <div class="col-sm-8">
                            <textarea class="form-control" name="reason" id="reasonInput"></textarea>
                        </div>
                    </div>
                    <center>Nhấn vào nút Submit để thực hiện trừ <b id="amountDisplay2" style="color:red;">0</b> trong
                        <b id="walletDisplay2">VÍ CHÍNH</b>
                    </center>
                </div>

                <script>
                document.addEventListener("DOMContentLoaded", function() {
                    var selectWallet = document.getElementById('walletSelect2');
                    var amountInput = document.getElementById('amountInput2');
                    var amountDisplay = document.getElementById('amountDisplay2');
                    var walletDisplay = document.getElementById('walletDisplay2');

                    // Hiển thị giá trị mặc định cho số tiền
                    updateAmountDisplay();

                    // Lắng nghe sự kiện input trên input số tiền
                    amountInput.addEventListener('input', function() {
                        updateAmountDisplay();
                    });
                    // Lắng nghe sự kiện change trên select box chọn loại ví
                    selectWallet.addEventListener('change', function() {
                        updateWalletDisplay();
                    });

                    function updateAmountDisplay() {
                        // Lấy giá trị từ input
                        var inputValue = amountInput.value;

                        // Kiểm tra nếu giá trị rỗng hoặc không phải là số
                        if (!inputValue || isNaN(inputValue)) {
                            amountDisplay.textContent =
                                '0'; // Hiển thị 0 nếu không có giá trị hoặc giá trị không hợp lệ
                            return;
                        }

                        // Định dạng số tiền và hiển thị vào amountDisplay
                        var formattedAmount = formatNumber(inputValue);
                        amountDisplay.textContent = formattedAmount;
                    }

                    function updateWalletDisplay() {
                        // Hiển thị loại ví được chọn
                        walletDisplay.textContent = selectWallet.options[selectWallet.selectedIndex].text;
                    }

                    function formatNumber(value) {
                        return parseFloat(value).toLocaleString('vi-VN');
                    }
                });
                </script>
                <div class="modal-footer">
                    <button type="button" class="btn btn-hero btn-danger" data-bs-dismiss="modal"><i
                            class="fa fa-fw fa-times me-1"></i> Close</button>
                    <button type="submit" name="tru_tien" class="btn btn-hero btn-success"><i
                            class="fa fa-fw fa-minus me-1"></i> Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>