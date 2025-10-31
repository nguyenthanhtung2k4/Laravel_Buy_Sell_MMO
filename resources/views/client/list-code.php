<?php
// PHP LOGIC FOR CONFIGURATION AND REQUIREMENTS (GIỮ NGUYÊN)
$title = 'Danh Sách Mã Nguồn | ' . $TN->site('title');
$body['header'] = '
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
';
$body['footer'] = '
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>
';
require_once __DIR__ . '/../../../core/is_user.php';
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/nav.php';
//CheckLogin();

// Định nghĩa mapping cho Category để dùng trong HTML
$categories = [
    '1' => 'TOOL',
    '2' => 'BOT',
    '3' => 'WEB',
    '4' => 'HACK',
];

// TRUY VẤN DỮ LIỆU: Lấy toàn bộ mã nguồn có status = 1. Việc lọc sẽ được thực hiện bằng JS.
$pinned_codes = $TN->get_list("SELECT * FROM `tbl_list_code` WHERE `ghim` = '1' AND `status` = '1' ORDER BY id DESC");
$main_codes = $TN->get_list("SELECT * FROM `tbl_list_code` WHERE `status` = '1' AND `ghim` != '1' ORDER BY id DESC");


// Hàm tính thời gian tương đối (GIỮ NGUYÊN LOGIC)
function time_ago($timestamp) {
    $now = time();
    $diff = $now - $timestamp;

    if ($diff < 0) return 'Trong tương lai';
    if ($diff < 60) return $diff . ' giây trước';
    if ($diff < 3600) return floor($diff / 60) . ' phút trước';
    if ($diff < 86400) return floor($diff / 3600) . ' giờ trước';
    if ($diff < 30 * 86400) return floor($diff / 86400) . ' ngày trước';
    return date('d/m/Y', $timestamp);
}

// Hàm format_cash cần được định nghĩa hoặc bao gồm trong các file require.
// Giả sử nó tồn tại:
if (!function_exists('format_cash')) {
    function format_cash($number) {
        return number_format($number, 0, ',', '.'); // Ví dụ định dạng tiền tệ VNĐ
    }
}
// Giả sử BASE_URL cũng tồn tại
if (!function_exists('BASE_URL')) {
    function BASE_URL($path = '') {
        return '/' . $path; // Giả định
    }
}
// Giả sử getUser cũng tồn tại
if (!function_exists('getUser')) {
    function getUser($userId, $field) {
        // Đây là một hàm giả định, cần được thay thế bằng logic thực tế của bạn
        $mock_users = [
            '1' => ['profile_picture' => 'assets/images/user1.png'],
        ];
        return $mock_users[$userId][$field] ?? null;
    }
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
                                <li class="breadcrumb-item" aria-current="page">Danh Sách Mã Nguồn</li>
                            </ol>
                        </nav>
                        <h2 class="breadcrumb-title">
                            Danh Sách Mã Nguồn
                        </h2>
                    </div>
                </div>
            </div>
        </div>
        <section class="py-110">
            <div class="container">
                <!-- KHÔNG DÙNG FORM SUBMIT NỮA, CHỈ DÙNG ĐỂ CHỨA CÁC TRƯỜNG INPUT -->
                <div id="searchContainer">
                    <div class="row justify-content-between mb-40">
                        <div class="col-xl-auto">
                            <div class="d-flex flex-column flex-wrap flex-md-row gap-3">

                                <div class="">
                                    <input type="text" id="searchInput" class="form-control" placeholder="Tên sản phẩm.." name="name" value="">
                                </div>
                                <div>
                                    <!-- Bỏ onchange submit -->
                                    <select name="category" id="categorySelect" class="custom-style-select select-dropdown">
                                        <option value="">Phân loại</option>
                                        <?php foreach ($categories as $value => $label) { ?>
                                            <option value="<?= $value ?>"><?= $label ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <!-- Các trường ẩn/không dùng đến (GIỮ NGUYÊN) -->
                                <div>
                                    <select name="limit" id="limit" class="custom-style-select select-dropdown" style="display: none;">
                                        <option value="12" selected="">12 sản phẩm</option>
                                        <option value="24">24 sản phẩm</option>
                                        <option value="48">48 sản phẩm</option>
                                    </select>
                                </div>
                                <div><button type="button" id="searchButton" class="shop-widget-btn mb-2"><i class="fas fa-search"></i><span>Tìm kiếm</span></button></div>
                                <div><a href="javascript:void(0);" id="resetButton" class="shop-widget-btn mb-2"><i class="far fa-trash-alt"></i><span>Bỏ lọc</span></a></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row" id="codeListContainer">
                    <!-- LOOP 1: MÃ NGUỒN ĐƯỢC GHIM (PINNED) - SẼ LUÔN HIỂN THỊ -->
                    <?php foreach($pinned_codes as $row) { ?>
                    <!-- Thêm data-pinned="true" để JS biết không lọc element này -->
                    <article class="col-xl-3 col-lg-4 col-md-6 mb-4 grid-item" data-pinned="true">
                        <div class="gigs-grid">
                            <div class="gigs-img">
                                <div class="">
                                    <a href="/client/view-code/<?=$row['code']?>"><img src="/assets/images/lazyload.gif" data-src="<?=$row['images'];?>" class="lazyLoad w-100"
                                            height="180" alt="<?=$row['name'];?>"></a>
                                </div>
                                <div class="card-overlay-badge">
                                    <a href="/client/view-code/<?=$row['code']?>"><span class="badge bg-danger"><i class="fa-solid fa-meteor"></i>Ghim</span></a>
                                </div>
                                <div class="fav-selection">
                                    <a href="javascript:void(0);" class="fav-icon" data-product-id="<?=$row['id']?>">
                                        <i class="fa-regular fa-heart"></i>
                                    </a>
                                </div>
                                <div class="user-thumb">
                                    <a href="/client/seller/<?=$row['user_id']?>">
                                        <img src="/<?= !empty(getUser($row['user_id'], 'profile_picture')) ? getUser($row['user_id'], 'profile_picture') : 'assets/images/avt.png'; ?>" alt="User">
                                    </a>
                                </div>
                            </div>
                            <div class="gigs-content">
                                <div class="gigs-info">
                                    <div class="star-rate">
                                        <span><i class="fa-regular fa-eye"></i><span id="averageRating" class="me-1"><?=$row['view'];?></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="gigs-title">
                                    <h3>
                                        <a href="/client/view-code/<?=$row['code']?>" class="truncate-2-lines">
                                            <?=$row['name'];?>
                                        </a>
                                    </h3>
                                </div>

                                <div class="gigs-card-footer">
                                    <div class="gigs-share">
                                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?=BASE_URL('client/view-code/');?><?=$row['code']?>">
                                            <i class="fa fa-share-alt"></i>
                                        </a>
                                        <span class="badge">
                                        <?= time_ago($row['create_date']); ?>
                                        </span>
                                    </div>

                                    <h5><?=format_cash($row['price'] - $row['price'] * $row['sale'] /100) ?>đ</h5>
                                </div>
                            </div>
                        </div>
                    </article>
                    <?php }?>
                    <!-- KẾT THÚC LOOP 1 -->

                    <!-- LOOP 2: MÃ NGUỒN CHÍNH - THÊM DATA ATTRIBUTES CHO JS FILTERING -->
                    <?php
                    foreach($main_codes as $row) {
                        // Vẫn giữ kiểm tra ghim mặc dù query đã lọc, để đảm bảo.
                        if ($row['ghim'] == 1) {
                            continue;
                        }
                    ?>
                    <!-- Thêm data attributes để JS có thể đọc Tên và Phân loại -->
                    <article class="col-xl-3 col-lg-4 col-md-6 mb-4 grid-item filterable-item"
                        data-name="<?= htmlspecialchars(strtolower($row['name'])) ?>"
                        data-category-id="<?= $row['sevice_code'] ?>">

                        <div class="gigs-grid">
                            <div class="gigs-img">
                                <div class="">
                                    <a href="/client/view-code/<?=$row['code']?>"><img src="/assets/images/lazyload.gif" data-src="<?=$row['images'];?>" class="lazyLoad w-100"
                                            height="180" alt="<?=$row['name'];?>"></a>
                                </div>

                                <div class="fav-selection">
                                    <a href="javascript:void(0);" class="fav-icon" data-product-id="<?=$row['id']?>">
                                        <i class="fa-regular fa-heart"></i>
                                    </a>
                                </div>
                                <div class="user-thumb">
                                    <a href="/client/seller/<?=$row['user_id']?>">
                                        <img src="/<?= !empty(getUser($row['user_id'], 'profile_picture')) ? getUser($row['user_id'], 'profile_picture') : 'assets/images/avt.png'; ?>" alt="User">
                                    </a>
                                </div>
                            </div>
                            <div class="gigs-content">
                                <div class="gigs-info">
                                    <!-- Hiển thị tên phân loại thay vì hardcode 'Php Script' -->
                                    <span class="badge bg-primary-light"><?= $categories[$row['sevice_code']] ?? 'Khác' ?></span>
                                    <div class="star-rate">
                                        <span><i class="fa-regular fa-eye"></i><span id="averageRating" class="me-1"><?=$row['view'];?></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="gigs-title">
                                    <h3>
                                        <a href="/client/view-code/<?=$row['code']?>" class="truncate-2-lines">
                                            <?=$row['name'];?>
                                        </a>
                                    </h3>
                                </div>

                                <div class="gigs-card-footer">
                                    <div class="gigs-share">
                                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?=BASE_URL('client/view-code/');?><?=$row['code']?>">
                                            <i class="fa fa-share-alt"></i>
                                        </a>
                                        <span class="badge">
                                        <?= time_ago($row['create_date']); ?>
                                        </span>
                                    </div>
                                    <h5><?=format_cash($row['price'] - $row['price'] * $row['sale'] /100) ?>đ</h5>
                                </div>
                            </div>
                        </div>
                    </article>
                    <?php }?>
                    <!-- KẾT THÚC LOOP 2 -->
                    
                    <!-- THÔNG BÁO KHI KHÔNG CÓ KẾT QUẢ -->
                    <div id="noResultsMessage" class="col-12 text-center py-5" style="display:none;">
                        <h4 class="text-muted">Không tìm thấy mã nguồn phù hợp với tiêu chí tìm kiếm.</h4>
                    </div>
                    
                </div>
            </div>
        </section>
        <!-- Services End -->
    </main>
    <!-- Main End -->
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const categorySelect = document.getElementById('categorySelect');
            const searchButton = document.getElementById('searchButton');
            const resetButton = document.getElementById('resetButton');
            const filterableItems = document.querySelectorAll('.filterable-item');
            const noResultsMessage = document.getElementById('noResultsMessage');

            function filterItems() {
                const searchTerm = searchInput.value.toLowerCase().trim();
                const selectedCategory = categorySelect.value;
                let resultsFound = false;

                filterableItems.forEach(item => {
                    const itemName = item.getAttribute('data-name');
                    const itemCategory = item.getAttribute('data-category-id');

                    // 1. Lọc theo Tên (Tìm kiếm tương đối)
                    const nameMatch = !searchTerm || itemName.includes(searchTerm);

                    // 2. Lọc theo Phân loại (Chọn từ dropdown)
                    const categoryMatch = !selectedCategory || itemCategory === selectedCategory;

                    // Hiển thị nếu cả hai điều kiện đều khớp
                    if (nameMatch && categoryMatch) {
                        item.style.display = 'block';
                        resultsFound = true;
                    } else {
                        item.style.display = 'none';
                    }
                });

                // Hiển thị thông báo nếu không có kết quả
                if (resultsFound) {
                    noResultsMessage.style.display = 'none';
                } else {
                    // Chỉ kiểm tra tin nhắn không có kết quả trên các item có thể lọc
                    if (filterableItems.length > 0) {
                        noResultsMessage.style.display = 'block';
                    }
                }
            }

            // Gắn sự kiện cho nút Tìm kiếm và ô input
            searchButton.addEventListener('click', filterItems);
            searchInput.addEventListener('keyup', function(event) {
                // Tự động lọc khi người dùng gõ, hoặc ấn Enter
                filterItems();
            });

            // Gắn sự kiện cho dropdown Phân loại
            categorySelect.addEventListener('change', filterItems);

            // Gắn sự kiện cho nút Bỏ lọc
            resetButton.addEventListener('click', function() {
                searchInput.value = '';
                categorySelect.value = '';
                filterItems(); // Chạy lại bộ lọc để hiển thị tất cả
            });
            
            // Chạy bộ lọc lần đầu để đảm bảo hiển thị đúng nếu có tham số GET (Mặc dù đã loại bỏ, nhưng vẫn tốt để có)
            filterItems();
        });
    </script>
<?php require_once __DIR__ . '/footer.php'; ?>
