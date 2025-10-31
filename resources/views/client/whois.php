<?php
$title = 'Kiểm Tra Tên Miền - ' . $TN->site('title');
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
function jsonMsg($status, $msg, $data = [])
{
    return array(
        'status' => $status,
        'message' => $msg,
        'data' => $data,
    );
}

function str($string)
{
    return trim(htmlspecialchars(addslashes($string)));
}

$result = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $url = "https://whois.inet.vn/api/whois/domainspecify/";
    if (isset($_POST['domain_check'])) {
        $domain_check = str($_POST['domain_check']);
        if (empty($domain_check)) {
            $result = jsonMsg('error', 'Vui lòng nhập tên miền cần kiểm tra');
        } else {
            $url .= $domain_check;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $output = curl_exec($ch);
            curl_close($ch);

            $data = json_decode($output, true);
            if ($data && isset($data['code']) && $data['code'] == 0) {
                $result = jsonMsg('success', 'Tìm thấy tên miền', $data);
            } else {
                $result = jsonMsg('error', $data['message'] ?? 'Không tìm thấy thông tin tên miền');
            }
        }
    }
}
?>
<main>
    <section class="py-110 bg-offWhite">
        <div class="container">
            <div class="rounded-3">

                <section class="space-y-6">
                    <div class="row">
                        <div class="col-md-12 mb-5">
                            <div class="profile-info-card">
                                <!-- Header -->
                                <div class="profile-info-header">
                                    <h4 class="text-18 fw-semibold text-dark-300">
                                        KIỂM TRA TÊN MIỀN
                                    </h4>
                                </div>
                                <div class="profile-info-body bg-white">
                                    <div class="mb-3">
                                    <form method="POST" action="">
                                        <label for="url" class="form-label">Nhập tên miền</label>
                                        <input type="text" class="form-control shadow-none" id="domain_check" name="domain_check" placeholder="VD: cmstdev.com" required>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Kiểm Tra</button>
                                    </form>
                                    
                                </div>
                            </div>
                        </div>
                        <?php if ($result): ?>
                        <div class="col-md-12 mb-5">
                            <div class="profile-info-card">
                                <div class="profile-info-header">
                                    <h4 class="text-18 fw-semibold text-dark-300">
                                        KẾT QUẢ
                                    </h4>
                                </div>
                                <div class="profile-info-body bg-white">
                                    <?php if ($result['status'] == 'success'): ?>
                                    <p><strong>Trạng thái:</strong> <?php echo $result['message']; ?></p>
                                    <p><strong>Tên miền:</strong> <?php echo $result['data']['domainName']; ?></p>
                                    <p><strong>Ngày đăng ký:</strong> <?php echo $result['data']['creationDate']; ?></p>
                                    <p><strong>Ngày hết hạn:</strong> <?php echo $result['data']['expirationDate']; ?></p>
                                    <p><strong>Chủ sở hữu:</strong> <?php echo $result['data']['registrantName']; ?></p>
                                    <p><strong>Trạng thái:</strong> <?php echo implode(', ', $result['data']['status']); ?></p>
                                    <p><strong>Nhà đăng ký:</strong> <?php echo $result['data']['registrar']; ?></p>
                                    <p><strong>Nameservers:</strong> <?php echo implode(', ', $result['data']['nameServer']); ?></p>
                                <?php else: ?>
                                    <p><strong>Lỗi:</strong> <?php echo $result['message']; ?></p>
                                <?php endif; ?>
                                </div>
                       <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </section>
</main>
<?php require_once __DIR__ . '/footer.php'; ?>
