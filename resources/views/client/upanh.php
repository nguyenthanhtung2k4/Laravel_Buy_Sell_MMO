<?php
$title = 'Lấy URL ảnh - ' . $TN->site('title');
$body['header'] = '
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>
';
$body['footer'] = '

';
require_once __DIR__ . '/../../../core/is_user.php';
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/nav.php';
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
                                    <h4 class="text-18 fw-semibold text-dark-300">UPLOAD ẢNH</h4>
                                </div>
                                <div class="profile-info-body bg-white">
                                    <form method="POST" enctype="multipart/form-data">
                                        <div class="mb-3">
                                            <label for="uploadInput" class="form-label">Chọn ảnh</label>
                                            <input type="file" class="form-control" id="uploadInput" name="image" accept="image/*" multiple required>
                                        </div>
                                        <button type="submit" class="btn btn-sm btn-primary mb-3">Tải lên</button>
                                        <div id="message"></div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-5">
                            <div class="profile-info-card">
                                <div class="profile-info-header">
                                    <h4 class="text-18 fw-semibold text-dark-300">LINK VÀ PREVIEW</h4>
                                </div>
                                <div class="profile-info-body bg-white">
                                    <div class="mb-3">
                                        <label for="image" class="form-label">Link ảnh sau khi tải</label>
                                        <input type="text" id="image" class="form-control mb-2" placeholder="Link ảnh sẽ hiển thị tại đây">
                                        <a href="#" id="copyImageBtn" class="btn btn-sm btn-success">Copy Ảnh</a>
                                        <div id="copyStatus" class="text-success mt-1"></div>
                                    </div>

                                    <div id="imagePreview" class="image-preview border rounded overflow-hidden" style="display: none;">
                                        <img id="previewImage" src="" alt="Preview ảnh" class="img-fluid rounded" style="max-width: 100%; height: auto;" />
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </section>
</main>

<script>
document.getElementById("copyImageBtn").addEventListener("click", function () {
    const copyText = document.getElementById("image");
    const copyStatus = document.getElementById("copyStatus");

    navigator.clipboard.writeText(copyText.value)
        .then(() => {
            copyStatus.textContent = "Đã sao chép!";
        })
        .catch(err => {
            console.error("Lỗi sao chép:", err);
            copyStatus.textContent = "Không thể sao chép!";
        });
});

document.getElementById("image").addEventListener("input", function () {
    const imageURL = this.value.trim();
    const previewImage = document.getElementById("previewImage");
    const previewContainer = document.getElementById("imagePreview");

    if (imageURL) {
        previewImage.src = imageURL;
        previewContainer.style.display = "block";
    } else {
        previewImage.src = "";
        previewContainer.style.display = "none";
    }
});
</script>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['image'])) {
    $file = $_FILES['image']['tmp_name'];

    if ($file) {
        $formData = array('image' => base64_encode(file_get_contents($file)));
        $clientId = '3352c129079eb4b';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.imgur.com/3/image');
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Client-ID ' . $clientId));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $formData);

        $response = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($response, true);

        if (isset($result['data']['link'])) {
            $image_link = $result['data']['link'];
            echo "<script>
                document.getElementById('image').value = '$image_link';
                document.getElementById('message').innerHTML = '<p class=\"text-success\">Tải lên thành công!</p>';
                document.getElementById('previewImage').src = '$image_link';
            </script>";
        } else {
            echo "<script>document.getElementById('message').innerHTML = '<p class=\"text-danger\">Tải lên thất bại</p>';</script>";
        }
    }
}
?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php require_once __DIR__ . '/footer.php'; ?>