<script>
    $(function () {
        $("img.lazyLoad").lazyload({
            effect: "fadeIn"
        });
    });
    function displayStars(averageRating) {
        const starsContainer = document.querySelector('.rating');
        const averageRatingElement = document.getElementById('averageRating');
        const roundedRating = Math.round(averageRating);

        averageRatingElement.textContent = averageRating.toFixed(1);

        const allStars = starsContainer.querySelectorAll('input[name="rating"]');
        allStars.forEach(star => (star.checked = false));

        const selectedStar = starsContainer.querySelector(`#stars${roundedRating}`);
        if (selectedStar) {
            selectedStar.checked = true;
        }
    }

    $(document).ready(function () {
        $('.service-filter-btn').on('click', function () {
            $('#loading-indicator').addClass('show');
            setTimeout(function () {
                $('#loading-indicator').removeClass('show');
            }, 300);
        });
    });

    $(document).on('click', '.fav-icon', function () {
        var $this = $(this);
        var productId = $this.data('product-id');
        var $icon = $this.find('i');
        var isFavorite = $icon.hasClass('fa-solid');
        var action = isFavorite ? 'remove' : 'add';

        $.ajax({
            url: '/ajaxs/client/favorite.php',
            type: 'POST',
            data: {
                action: action,
                product_id: productId,
                csrf_token: $('#token').val()
            },
            success: function (response) {
                var result = JSON.parse(response);

                if (result.status === 'added') {
                    $icon.removeClass('fa-regular').addClass('fa-solid');
                } else if (result.status === 'removed') {
                    $icon.removeClass('fa-solid').addClass('fa-regular');
                }

                if (result.fav_count !== undefined) {
                    $('#numFavorites').text(result.fav_count);
                }

                showMessage(result.msg, result.status === 'error' ? 'error' : 'success');
            },
            error: function () {
                showMessage("Có lỗi xảy ra. Vui lòng thử lại sau!", 'error');
            }
        });
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var modal = document.getElementById('modal_notification');
        var dontShowAgainBtn = document.getElementById('dontShowAgainBtn');
        var modalClosedTime = localStorage.getItem('modalClosedTime');
        if (!modalClosedTime || (Date.now() - parseInt(modalClosedTime) > 2 * 60 * 60 * 1000)) {
            var bootstrapModal = new bootstrap.Modal(modal);
            bootstrapModal.show();
        }
        dontShowAgainBtn.addEventListener('click', function () {
            localStorage.setItem('modalClosedTime', Date.now());
            var bootstrapModal = bootstrap.Modal.getInstance(modal);
            bootstrapModal.hide();
        });
    });
    document.addEventListener("DOMContentLoaded", function () {
        const filterButtons = document.querySelectorAll('.service-filter-btn');
        const gridItems = document.querySelectorAll('.grid-item');

        filterButtons.forEach(button => {
            button.addEventListener('click', function () {
                filterButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                const filterValue = this.getAttribute('data-filter');
                gridItems.forEach(item => {
                    if (filterValue === '.category1' || item.classList.contains(filterValue.replace('.', ''))) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });
    });
</script>
<script>
    $(function () {
        $("img.lazyLoad").lazyload({
            effect: "fadeIn"
        });
    });
    function displayStars(averageRating) {
        const starsContainer = document.querySelector('.rating');
        const averageRatingElement = document.getElementById('averageRating');
        const roundedRating = Math.round(averageRating);

        averageRatingElement.textContent = averageRating.toFixed(1);

        const allStars = starsContainer.querySelectorAll('input[name="rating"]');
        allStars.forEach(star => (star.checked = false));

        const selectedStar = starsContainer.querySelector(`#stars${roundedRating}`);
        if (selectedStar) {
            selectedStar.checked = true;
        }
    }

    $(document).ready(function () {
        $('.service-filter-btn').on('click', function () {
            $('#loading-indicator').addClass('show');
            setTimeout(function () {
                $('#loading-indicator').removeClass('show');
            }, 300);
        });
    });
</script>
<script>
    function openModal(modalId) {
        var modal = document.getElementById(modalId);
        modal.classList.remove('hidden');
    }

    function closeModal(modalId) {
        var modal = document.getElementById(modalId);
        modal.classList.add('hidden');
    }
    window.gtranslateSettings = {
        "default_language": "vi",
        "native_language_names": true,
        "globe_color": "#66aaff",
        "wrapper_selector": ".gtranslate_wrapper",
        "flag_size": 28,
        "alt_flags": {
            "en": "usa"
        },
        "globe_size": 24
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<!--
<script>
/*<![CDATA[*/
      const disabledKeys = [ "x", "s", "J", "u", "I"]; 
      const showAlert = e => {
        e.preventDefault();
         return showMessage('F12 làm gì , tự code đi', 'error');
      }
      document.addEventListener("contextmenu", e => {
        showAlert(e);
      });
      document.addEventListener("keydown", e => {
        // calling showAlert() function, if the pressed key matched to disabled keys
        if((e.ctrlKey && disabledKeys.includes(e.key)) || e.key === "F12") {
          showAlert(e);
        }
      });
      window.addEventListener("load",function(){
        try {
          !function t(n) {
            1 === ("" + n / n).length && 0 !== n || function() {}.constructor("debugger")(), t(++n)
          }(0)
        } catch (n) {
          setTimeout(t, 100)
        }
      });
/*]]>*/
</script> 
-->

<style>
    .footer-widget img {
        margin-bottom: auto;
    }

    /* Thiết lập cho toàn bộ danh sách */
    .social-icons-list {
        list-style: none;
        /* Bỏ dấu chấm đầu dòng */
        padding: 0;
        margin: 0;
        display: flex;
        /* Đặt các item nằm ngang hàng */
        align-items: center;
    }

    /* Thiết lập cho mỗi item trong danh sách */
    .social-icons-list li {
        margin-left: 10px;
        /* Khoảng cách giữa các icon */
    }

    /* Thiết lập cho liên kết (khung bao ngoài) */
    .social-icons-list li a {
        /* Khung hình tròn */
        display: flex;
        justify-content: center;
        align-items: center;
        width: 40px;
        /* Kích thước icon (tùy chỉnh) */
        height: 40px;
        /* Kích thước icon (tùy chỉnh) */
        border-radius: 50%;

        /* Màu sắc theo yêu cầu */
        background-color: #000;
        /* Nền đen */
        color: #fff;
        /* Màu icon trắng */
        font-size: 18px;
        /* Kích thước Font Awesome icon (tùy chỉnh) */

        /* Thêm hiệu ứng hover nếu cần */
        transition: background-color 0.3s ease;
    }

    /* Thiết lập cho hình ảnh Zalo để nó khớp với các icon Font Awesome */
    .zalo-icon-item a {
        padding: 5px;
        /* Thêm padding để hình ảnh không dính sát viền */
    }

    .zalo-custom-icon {
        width: 100%;
        /* Chiếm toàn bộ chiều rộng của thẻ <a> */
        height: 100%;
        /* Chiếm toàn bộ chiều cao của thẻ <a> */
        border-radius: 50%;
        object-fit: contain;
        /* Đảm bảo hình ảnh Zalo không bị méo */
    }

    /* Hiệu ứng khi di chuột (tùy chọn) */
    .social-icons-list li a:hover {
        background-color: #333;
        /* Đổi màu nền khi hover */
    }
</style>
<script src="https://cdn.gtranslate.net/widgets/latest/globe.js" defer></script>
</body>
<?= $body['footer']; ?>
<!-- Logout Modal -->
<div class="modal new-modal fade" id="logoutModal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận đăng xuất</h5>
                <button type="button" class="close-btn" data-bs-dismiss="modal"><span>×</span></button>
            </div>
            <div class="modal-body service-modal">
                <div class="row">
                    <div class="col-md-12">
                        Bạn có chắc chắn muốn Đăng xuất không?
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <div class="btn-item">
                    <a href="#" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</a>
                    <a href="/client/logout" class="btn btn-primary" type="submit">Đăng xuất</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Footer  -->
<footer class="footer">
    <div class="container">
        <div class="footer-top">
            <div class="row">
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
                    <div class="footer-widget">
                        <a href="/">
                            <img src="<?= $TN->site('logo'); ?>" width="150" alt="logo">
                        </a>
                        <p>Dịch vụ thiết kế website theo yêu cầu, mua bán mã nguồn, dịch vụ uy tín, hỗ trợ nhiệt tình.
                            Đội ngũ chăm sóc khách hàng 24/24</p>
                        <div class="social-links">
                            <ul>
                                <li><a href="https://www.facebook.com/C25TOOL" target="_blank"><i
                                            class="fa-brands fa-facebook"></i></a></li>

                                <li class="zalo-icon-item">
                                    <a href="https://zalo.me/SỐ_ĐIỆN_THOẠI_CỦA_BẠN" target="_blank" rel="nofollow">
                                        <img src="https://qr.zalo.me/qr/zalo-icon.png" alt="Zalo"
                                            class="zalo-custom-icon">
                                    </a>
                                </li>

                                <li><a href="https://t.me/c25toolbot" target="_blank"><i
                                            class="fa-brands fa-telegram"></i></a></li>

                                <!-- <li><a href="ĐƯỜNG_LINK_INSTAGRAM_CỦA_BẠN" target="_blank"><i
                                            class="fa-brands fa-instagram"></i></a></li> -->
                                <li><a href="https://www.youtube.com/@c25tool/" target="_blank"><i
                                            class="fa-brands fa-youtube"></i></a></li>
                                <li><a href="https://www.tiktok.com/@c25tool.net" target="_blank"><i class="fa-brands fa-tiktok"></i></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                    <div class="footer-widget">
                        <h3>Danh mục nổi bật</h3>
                        <ul class="menu-items">
                            <li><a href="/client/list-code">Mã Nguồn</a></li>
                            <!-- <li><a href="/client/hosting">Hosting</a></li> -->
                            <li><a href="/client/cloudvps">Cloud VPS</a></li>
                            <!-- <li><a href="/client/reg-domain">Tên Miền</a></li> -->
                            <!-- <li><a href="/client/cronjob">CronJob</a></li> -->
                        </ul>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                    <div class="footer-widget">
                        <h3>Dịch vụ khác</h3>
                        <ul class="menu-items">
                            <li><a href="dichvu.c25tool.net">Mạng Xã hội</a></li>
                            <li><a href="https://www.youtube.com/c/Ho%C3%A0ngS%C6%A1nT%C3%B9ngJusst">Đối tác JUSST</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                    <div class="footer-widget">
                        <h3>Thể loại blog</h3>
                        <ul class="menu-items">
                            <li><a href="/client/blogs">Bài Viết</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="contact-widget">
                <div class="row align-items-center">
                    <div class="col-xl-9">
                        <ul class="location-list">
                            <li>
                                <span><i class="fa-brands fa-telegram"></i></span>
                                <div class="location-info">
                                    <h6>Telegram</h6>
                                    <p><?= $TN->site('telegram') ?></p>
                                </div>
                            </li>
                            <li>
                                <span><i class="fa-regular fa-envelope"></i></span>
                                <div class="location-info">
                                    <h6>Email</h6>
                                    <p><?= $TN->site('email') ?></p>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="col-xl-3 text-xl-end">

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="copy-right">
                        <p>Copyright 2025 by C25</p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="footer-bottom-links">
                        <ul>
                            <li><a href="/client/privacy-policy">Chính sách bảo mật</a></li>
                            <li><a href="/client/terms-condition">Điều khoản & Điều kiện</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

<div id="az-chat-btn" onclick="toggleChat()">
    💬 Hỗ trợ
</div>

<div id="az-chat-box">
    <div id="az-chat-header">BOT DEV.C25TOOL.NET</div>
    <div id="az-chat-messages">
        <div class="msg bot">Xin chào! Tôi là BOT C25 – trợ lý hỗ trợ khách hàng tại DEV.C25TOOL.NET Bạn cần hỗ trợ gì?
        </div>
    </div>
    <div id="az-chat-input">
        <input type="text" id="az-user-input" placeholder="Nhập tin nhắn...">
        <button onclick="sendMessage()">Gửi</button>
    </div>
</div>

<style>
    #az-chat-btn {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background: #007bff;
        color: white;
        padding: 10px 15px;
        border-radius: 30px;
        cursor: pointer;
        z-index: 1000;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
    }

    #az-chat-box {
        display: none;
        position: fixed;
        bottom: 80px;
        right: 20px;
        width: 300px;
        height: 400px;
        background: white;
        border: 1px solid #ccc;
        border-radius: 12px;
        flex-direction: column;
        z-index: 1001;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
        overflow: hidden;
        font-family: sans-serif;
    }

    #az-chat-header {
        background: #007bff;
        color: white;
        padding: 10px;
        font-weight: bold;
    }

    #az-chat-messages {
        flex: 1;
        padding: 10px;
        overflow-y: auto;
        height: 300px;
    }

    .msg {
        margin-bottom: 10px;
        line-height: 1.4;
    }

    .msg.bot {
        background: #f1f1f1;
        padding: 8px;
        border-radius: 8px;
    }

    .msg.user {
        text-align: right;
        background: #d1e7ff;
        padding: 8px;
        border-radius: 8px;
    }

    #az-chat-input {
        display: flex;
        border-top: 1px solid #ddd;
    }

    #az-user-input {
        flex: 1;
        padding: 10px;
        border: none;
        outline: none;
    }

    #az-chat-input button {
        background: #007bff;
        color: white;
        border: none;
        padding: 10px 15px;
        cursor: pointer;
    }
</style>
<script>
    function toggleChat() {
        const chatBox = document.getElementById("az-chat-box");
        chatBox.style.display = chatBox.style.display === "none" ? "flex" : "none";
    }

    function sendMessage() {
        const input = document.getElementById("az-user-input");
        const msg = input.value.trim();
        if (!msg) return;

        addMessage("user", msg);
        input.value = "";

        getBotReply(msg.toLowerCase()).then(reply => {
            addMessage("bot", reply);
        }).catch(err => {
            addMessage("bot", "🚫 Có lỗi xảy ra, thử lại sau.");
        });
    }

    function addMessage(sender, text) {
        const msgContainer = document.getElementById("az-chat-messages");
        const div = document.createElement("div");
        div.className = `msg ${sender}`;
        div.innerText = text;
        msgContainer.appendChild(div);
        msgContainer.scrollTop = msgContainer.scrollHeight;
    }

    function getBotReply(msg) {
        return fetch("/ajaxs/client/chatbot.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ message: msg })
        })
            .then(res => res.json())
            .then(data => data.reply || "🤖 Không thể phản hồi lúc này.")
            .catch(() => "🚫 Có lỗi xảy ra, thử lại sau.");
    }
</script>


<script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
<script src="/assets/js/owl.carousel.min.js"></script>
<script src="/assets/plugins/slick/slick.js"></script>
<script src="/assets/js/script.js?khangapi=<? time() ?>"></script>
<script src="/assets/js/jquery-migrate.min.js"></script>
<script src="/assets/js/jquery.counterup.min.js"></script>
<script src="/assets/js/waypoints.min.js"></script>
<script src="/assets/js/jquery.nice-select.min.js"></script>
<script src="/assets/js/isotope.pkgd.min.js"></script>
<script src="/assets/js/imagesloaded.pkgd.min.js"></script>
<script src="/assets/js/aos.js"></script>
<script src="/assets/js/quill.js"></script>
<script src="/assets/js/glightbox.min.js"></script>
<script src="/assets/js/popper.min.js"></script>
<script src="/assets/js/bootstrap.bundle.min.js"></script>
<script src="/assets/js/swiper-bundle.min.js"></script>
<script src="/assets/js/main.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.11/clipboard.min.js"></script>
<script>
    var o = new ClipboardJS(".copy");
    o.on("success", function (e) {
        showMessage('Sao Chép Thành Công', 'success');
    });
    o.on("error", function (e) {
        showMessage('Sao Chép Thất Bại', 'error');
    });
</script>
<?= $TN->site('javascript_footer'); ?>
</body>

</html>