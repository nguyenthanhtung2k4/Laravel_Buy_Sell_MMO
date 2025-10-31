<?php
function callGeminiAPI($message) {
    $API_KEY = "AIzaSyATxLqniWL35JR2KokHfQ95mJGZTdhxuek";
    $API_URL = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=$API_KEY";

    $data = [
        "contents" => [
            [
                "parts" => [
                    ["text" => $message]
                ]
            ]
        ]
    ];

    $ch = curl_init($API_URL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    $response = curl_exec($ch);
    curl_close($ch);

    $responseData = json_decode($response, true);
    return $responseData['candidates'][0]['content']['parts'][0]['text'] ?? "⚠️ Xin lỗi, hệ thống đang bận. Vui lòng thử lại sau.";
}

$input = json_decode(file_get_contents("php://input"), true);
if (!$input || !isset($input['message'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Thiếu nội dung yêu cầu']);
    exit;
}

$message = $input['message'];

$reply = "🛒 **AZViet.net cung cấp các dịch vụ sau**:\n
- **Mã nguồn**: Mã nguồn cho các ứng dụng web, CMS, plugin và các công cụ khác.\n
- **Hosting**: Hosting chia sẻ và các gói lưu trữ mạnh mẽ, phù hợp với nhu cầu của bạn.\n
- **VPS**: Dịch vụ máy chủ ảo, cấu hình mạnh mẽ, ổn định.\n
- **Tên miền**: Cung cấp các tên miền chất lượng cao, dễ dàng quản lý.\n
- **Cronjob**: Dịch vụ lập lịch tác vụ tự động trên máy chủ của bạn.\n
- **Nạp tiền**: Bạn có thể nạp tiền qua **chuyển khoản ngân hàng** hoặc **thẻ cào**.\n
Vui lòng lựa chọn dịch vụ bạn cần tư vấn thêm!";

if (strpos(strtolower($message), 'nạp tiền') !== false) {
    $reply = "💳 Để nạp tiền, bạn vào mục **Nạp tiền** tại AZViet.net, chọn phương thức **chuyển khoản ngân hàng** hoặc **thẻ cào**. Hệ thống sẽ tự cộng sau ~30 giây.";

    if (strpos(strtolower($message), 'tiền lâu') !== false || strpos(strtolower($message), 'chưa nhận tiền') !== false) {
        $reply .= "\n\nCảm ơn bạn đã sử dụng dịch vụ, hệ thống sẽ gửi đơn xét duyệt và thông báo lại sau. Vui lòng chờ trong ít phút.";
    }
}

elseif (strpos(strpos(strtolower($message), 'lỗi') !== false || strtolower($message), 'lỗi hosting') !== false || strpos(strtolower($message), 'lỗi vps') !== false || strpos(strtolower($message), 'lỗi tên miền') !== false || strpos(strtolower($message), 'lỗi cronjob') !== false) {
    $reply = "⚠️ Có vẻ bạn gặp sự cố với dịch vụ. Để được hỗ trợ nhanh chóng, vui lòng liên hệ qua Telegram @BuiDucThanh để được hỗ trợ ngay lập tức.";
}

else {
    $reply = callGeminiAPI($message);
}

echo json_encode(['reply' => $reply]);
?>
