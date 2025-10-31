<main>
    <section class="py-110">
        <div class="container">
            <div class="settings-page-lists">
    <ul class="settings-head">
        <li>
            <a href="/client/user-dashboard" class="menu-item">Dashboard</a>
        </li>
                <li>
            <a href="/client/user-profile" class="menu-item">Hồ sơ</a>
        </li>
        <li>
            <a href="/client/user-change-password" class="menu-item">Đổi mật khẩu</a>
        </li>
        <li>
            <a href="/client/user-balance" class="menu-item">Biến động số dư</a>
        </li>
        <li>
            <a href="/client/user-log" class="menu-item">Nhật ký hoạt động</a>
        </li>
        <li>
            <a href="/client/user-history-code" class="menu-item">Lịch sử mua mã nguồn</a>
        </li>
        <li>
            <!-- <a href="/client/user-history-hosting" class="menu-item">Lịch sử mua hosting</a> -->
        </li>
        <li>
            <!-- <a href="/client/user-history-domain" class="menu-item">Lịch sử mua miền</a> -->
        </li>
        <li>
            <!-- <a href="/client/user-history-cron" class="menu-item">Lịch sử thuê cron</a> -->
        </li>
    </ul>
</div>
<script>
    $(document).ready(function() {
        var url = window.location.pathname;
        var urlRegExp = new RegExp(url.replace(/\/$/, '') + "$");
        $('.menu-item').each(function() {
            if (urlRegExp.test(this.href.replace(/\/$/, ''))) {
                $(this).addClass('active');
            }
        });
    });
</script>