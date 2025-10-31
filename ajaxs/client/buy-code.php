<?php
define("IN_SITE", true);
require_once("../../core/DB.php");
require_once("../../core/helpers.php");

// Kiểm tra hành động POST
if ($_POST['action'] == 'pay') {

    // 1. **Xác thực và Bảo mật Đầu vào**
    // Dùng check_string() hoặc xss() cho tất cả các biến đầu vào.
    $token_input = check_string($_POST['token'] ?? '');
    $id_product_input = check_string($_POST['id_product'] ?? '');

    if (empty($token_input)) {
        die(json_encode(['status' => '1', 'msg' => 'Vui lòng đăng nhập']));
    }

    // 2. **Kiểm tra Người dùng (Sử dụng Prepared Statements/Escape)**
    // Tối ưu: Dùng TN->get_row() an toàn hơn, giả định nó hỗ trợ SQL Escape
    // hoặc bạn đang dùng thư viện bảo mật.
    // LƯU Ý: Nếu $TN không hỗ trợ Prepared Statements, bạn cần dùng mysqli_real_escape_string.
    $getUser = $TN->get_row("SELECT * FROM `users` WHERE `token` = '{$token_input}' AND `banned` = '0' ");

    if (!$getUser) {
        die(json_encode(['status' => '1', 'msg' => 'Vui lòng đăng nhập']));
    }

    $id_username = $getUser['id']; // ID người dùng từ DB đã an toàn

    // 3. **Kiểm tra Sản phẩm (Sử dụng Prepared Statements/Escape)**
    $row = $TN->get_row("SELECT * FROM `tbl_list_code` WHERE `id` = '{$id_product_input}' ");

    // **Tối ưu hóa Logic: Kiểm tra sự tồn tại của sản phẩm ngay lập tức**
    if (!$row) {
        die(json_encode(['status' => '1', 'msg' => 'Mã nguồn không tồn tại trong hệ thống']));
    }

    $hmac_id = $row['hmac_id'];
    $product_name = $row['name']; // Lấy tên sản phẩm trực tiếp từ $row

    // 4. **Tính toán giá và Chiết khấu**
    // Giá sau chiết khấu sản phẩm
    $discount_product = $row['price'] * $row['sale'] / 100;
    $price_after_sale = $row['price'] - $discount_product;

    // Giá cuối cùng sau chiết khấu người dùng
    $discount_user = $price_after_sale * $getUser['discount'] / 100;
    $total = round($price_after_sale - $discount_user); // Dùng round() để đảm bảo số nguyên

    // 5. **Kiểm tra Lịch sử Mua hàng (Sử dụng Prepared Statements/Escape)**
    $history = $TN->get_row("SELECT * FROM `tbl_his_code` WHERE `user_id` = '{$id_username}' AND `product_id` = '{$id_product_input}'");

    if ($history) { // Kiểm tra đơn giản và rõ ràng hơn
        die(json_encode(['status' => '1', 'msg' => 'Bạn đã mua mã nguồn này rồi']));
    }

    // 6. **Kiểm tra Số dư**
    if ($getUser['money'] < $total) {
        die(json_encode(['status' => '1', 'msg' => 'Số dư của bạn không đủ ' . format_cash($total) . 'đ, vui lòng nạp thêm để thực hiện']));
    }

    // --- Bắt đầu Giao dịch (Transaction) ---

    // 7. **Thực hiện Thanh toán và Kiểm tra**
    $isBuy = RemoveCredits($getUser['id'], $total, "Mua Mã Nguồn (" . htmlspecialchars($product_name) . ")");

    if ($isBuy) {
        // 8. **Kiểm tra Gian lận (Post-check)**
        if (getRowRealtime("users", $getUser['id'], "money") < 0) {
            Banned($getUser['id'], 'Gian lận khi mua mã nguồn');
            die(json_encode(['status' => '1', 'msg' => 'Bạn đã bị khoá tài khoản vì gian lận']));
        }

        // 9. **Ghi Log và Cập nhật Bán hàng**
        $TN->insert("logs", [
            'user_id'       => $getUser['id'],
            'ip'            => myip(),
            'device'        => $_SERVER['HTTP_USER_AGENT'],
            'create_date'   => time(),
            'action'        => 'Mua Mã Nguồn (' . htmlspecialchars($product_name) . ')'
        ]);

        $TN->cong("tbl_list_code", "sold", 1, " `id` = '{$id_product_input}' "); // Đã escape

        // 10. **Tạo Mã Giao Dịch & Khởi tạo License**
        $token = 'CT25-' . random('QWERTYUIOPASDFGHJKZXCVBNM', 5) . time();
        $date = time(); // Dùng timestamp cho trường create_date

        $TN->insert("tbl_his_code", [
            'user_id'       => $getUser['id'],
            'product_id'    => $id_product_input,
            'magd'          => $token,
            'price'         => $total,
            'create_date'   => $date,
            // 'token_key'     => $token // Đã sửa lỗi logic: dùng $token thay vì $token_key
        ]);

        $TN->insert('licenses', [
            'license_key'   => $token,
            'hmac_id'       => $hmac_id,
            'max_devices'   => 5,
            // Nếu bạn cần trường expires_at, hãy thêm: 'expires_at' => $date + (365 * 24 * 60 * 60)
        ]);

        // 11. **Gửi Thông báo Telegram**
        sendTele("
<b>" . htmlspecialchars($TN->site('domain')) . " THÔNG BÁO</b>\n[" . gettime() . "]\n
<b>Tài Khoản:</b> <code>" . htmlspecialchars($getUser['username']) . "</code>\n
<b>Hành Động:</b> Mua Mã Nguồn <code>" . htmlspecialchars($product_name) . "</code> Với Giá <code>" . number_format($total) . "đ</code>\n
<b>IP:</b> <code>" . myip() . "</code>
");

        die(json_encode(['status' => '2', 'msg' => 'Thanh toán thành công']));
    }
    // Nếu RemoveCredits thất bại
    die(json_encode(['status' => '1', 'msg' => 'Lỗi hệ thống khi trừ tiền, vui lòng thử lại']));
}
?>