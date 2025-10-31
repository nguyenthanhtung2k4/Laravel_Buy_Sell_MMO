<?php
$title = 'Thông Tin Tác Giả - ' . $TN->site('title');
$body['header'] = '

';
$body['footer'] = '

';
require_once __DIR__ . '/../../../core/is_user.php';
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/nav.php';
CheckLogin();
if (isset($_POST['Save'])) {
    $team = xss($_POST['team']);
    $teamMembers = xss($_POST['teamMembers']);
    $otherAccount = xss($_POST['otherAccount']);
    $marketAccount = xss($_POST['marketAccount']);
    $workCategory = isset($_POST['workCategory']) ? implode(", ", $_POST['workCategory']) : '';
    
    $user_id = $getUser['id']; 

    $isInsert = $TN->insert("author_info", array(
        'user_id'         => $user_id,      
        'team'            => $team,
        'team_members'    => $teamMembers,
        'other_account'   => $otherAccount,
        'market_account'  => $marketAccount,
        'work_category'   => $workCategory,
        'created_at'      => time(),  
    ));
    
sendTele("
<b>🔔 AZVIET.NET - THÔNG BÁO</b>\n[" . gettime() . "]\n
<b>👤 Tài Khoản:</b> <code>" . getUser($user_id, 'username') . "</code>\n
<b>📝 Hành Động:</b> <code>Đã Đăng Ký Trở Thành Người Bán. Admin Vui Lòng Xem Xét</code>\n
<b>🌐 IP:</b> <code>" . myip() . "</code>");
    
    if ($isInsert) {
        die('<script type="text/javascript">if(!alert("Biểu mẫu đã được gửi, vui lòng chờ.")){window.history.back().location.reload();}</script>');
    } else {
        die('<script type="text/javascript">if(!alert("Biểu mẫu đã được gửi thất bại.")){window.history.back().location.reload();}</script>');
    }
}
?>

    <main>
    <section class="py-110">

        <div class="container">
            <div class="row">
                <div class="col-md-12 m-auto">
                    <div class="settings-card">
                        <div class="settings-card-head">
                            <h4>Thông tin tác giả</h4>
                        </div>
                        <form action="" method="POST">
                            <div class="settings-card-body">

                                <div class="mb-3">
                                    <label class="form-label">Bạn có đội nào không?</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="team" id="teamYes" value="yes" checked>
                                        <label class="form-check-label" for="teamYes">Đúng</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="team" id="teamNo" value="no">
                                        <label class="form-check-label" for="teamNo">KHÔNG</label>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="teamMembers">Nhóm của bạn có bao nhiêu thành viên?</label>
                                    <select class="form-select" id="teamMembers" name="teamMembers">
                                        <option selected>Chọn một</option>
                                        <option value="1-5">1-5</option>
                                        <option value="6-10">6-10</option>
                                        <option value="11-20">11-20</option>
                                        <option value="20+">20+</option>
                                    </select>
                                </div>

                                <!-- Radio buttons for other account on the platform -->
                                <div class="mb-3">
                                    <label class="form-label">Bạn có tài khoản nào khác trên nền tảng này không?</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="otherAccount" id="accountYes" value="yes">
                                        <label class="form-check-label" for="accountYes">Đúng</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="otherAccount" id="accountNo" value="no">
                                        <label class="form-check-label" for="accountNo">KHÔNG</label>
                                    </div>
                                </div>

                                <!-- Radio buttons for account in other markets -->
                                <div class="mb-3">
                                    <label class="form-label">Bạn có tài khoản ở thị trường khác không?</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="marketAccount" id="marketYes" value="yes">
                                        <label class="form-check-label" for="marketYes">Đúng</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="marketAccount" id="marketNo" value="no">
                                        <label class="form-check-label" for="marketNo">KHÔNG</label>
                                    </div>
                                </div>

                                <!-- Checkboxes for work preferences -->
                                <div class="mb-3">
                                    <label class="form-label">Bạn thích làm việc ở hạng mục nào?</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="phpCommands" name="workCategory[]" value="PHP Commands">
                                        <label class="form-check-label" for="phpCommands">Các tập lệnh PHP</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="wordpress" name="workCategory[]" value="Wordpress">
                                        <label class="form-check-label" for="wordpress">Wordpress</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="vibration" name="workCategory[]" value="Vibration">
                                        <label class="form-check-label" for="vibration">Vibration API</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="http5" name="workCategory[]" value="HTTP5">
                                        <label class="form-check-label" for="http5">HTTP5</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="graphics" name="workCategory[]" value="Graphics">
                                        <label class="form-check-label" for="graphics">Đồ họa</label>
                                    </div>
                                </div>


                            </div>
                            <div class="settings-card-footer">
                                <div class="btn-item">
                                    <button class="btn btn-primary w-100" name="Save">Nộp Đơn</button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </section>
</main>
<?php require_once __DIR__ . '/footer.php'; ?>