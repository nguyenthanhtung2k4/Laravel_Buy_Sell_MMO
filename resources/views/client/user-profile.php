<?php
$title = 'Thông Tin Tài Khoản - ' . $TN->site('title');
$body['header'] = '

';
$body['footer'] = '

';
require_once __DIR__ . '/../../../core/is_user.php';
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/nav.php';
CheckLogin();
if(isset($_POST['Save']))
{
    $url_image = $getUser['profile_picture'];
    if (check_img('profile_picture') == true) {
        $rand = random('0123456789QWERTYUIOPASDGHJKLZXCVBNM', 3);
        $uploads_dir = 'upload/avatars/avatar_'.$rand.'.png';
        $tmp_name = $_FILES['profile_picture']['tmp_name'];
        $addlogo = move_uploaded_file($tmp_name,$uploads_dir);
        if ($addlogo) {
            $url_image = 'upload/avatars/avatar_'.$rand.'.png';
        }
    }
    $isUpdate= $TN->update("users", array(
        'name'       => xss($_POST['name']),
        'address'       => xss($_POST['address']),
        'profile_picture'         => $url_image,
        'skill'       => xss($_POST['skill']),
        'description'       => xss($_POST['description']),
        'update_date'       => time(),
        'time_session'       => time(),
    ), " `id` = '".$getUser['id']."' ");
    if ($isUpdate) {
        die('<script type="text/javascript">if(!alert("Cập nhật thành công!")){window.history.back().location.reload();}</script>');
    } else {
        die('<script type="text/javascript">if(!alert("Cập nhật thất bại!")){window.history.back().location.reload();}</script>');
    }
}
?>
        <?php require_once('sidebar.php');?>

        <div class="row">
                <div class="col-lg-15">
                    <div class="settings-card">
                        <div class="settings-card-head">
                            <h4>THÔNG TIN TÀI KHOẢN</h4>
                        </div>
                        <input type="hidden" class="form-control" id="token" value="<?= $getUser['token'] ?>" readonly>
                        <div class="settings-card-body">
                            <form method="POST" action="" enctype="multipart/form-data" class="row g-4">
                            <div class="col-md-12">
                                    <div>
                                        <label for="profile_picture" class="form-label">Chọn ảnh đại
                                            diện mới</label>
                                        <input type="file" class="form-control shadow-none"
                                            id="profile_picture" name="profile_picture" accept="image/*">
                                        <i>Chỉ cho phép các định dạng như: jpeg,png,gif. Kích thước ảnh
                                            tối đa 2MB</i>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div>
                                        <label for="fname" class="form-label">Tài khoản</label>
                                        <input type="text" class="form-control shadow-none"
                                            value="<?=$getUser['username'];?>" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div>
                                        <label for="fname" class="form-label">Họ Và Tên</label>
                                        <input type="text" name="name" class="form-control shadow-none"
                                            value="<?=$getUser['name'];?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div>
                                        <label for="fname" class="form-label">Email</label>
                                        <input type="text" class="form-control shadow-none"
                                            value="<?=$getUser['email'];?>" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div>
                                        <label for="fname" class="form-label">Chiết Khấu (%)</label>
                                        <input type="text" class="form-control shadow-none"
                                            value="<?=$getUser['discount'];?>" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div>
                                        <label for="fname" class="form-label">Loại tài khoản</label>
                                        <input type="text" class="form-control shadow-none"
                                            value="<?=$getUser['type'];?>" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div>
                                        <label for="fname" class="form-label">Cấp bậc</label>
                                        <input type="text" class="form-control shadow-none"
                                            value="
                                            <?php 
                                            if($getUser['level'] == '1'){
                                                echo 'Quản trị viên';
                                            } else {
                                                echo 'Thành viên';
                                                }
                                            ?>  
                                            " readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div>
                                        <label for="fname" class="form-label">Ngày đăng ký</label>
                                        <input type="text" class="form-control shadow-none"
                                            value="<?=format_date($getUser['create_date']);?>" readonly>
                                  </div>
                              </div>
                              <div class="col-md-4">
                                    <div>
                                        <label for="fname" class="form-label">Hoạt động gần đây</label>
                                        <input type="text" class="form-control shadow-none"
                                            value="<?=format_date($getUser['time_session'])?>" readonly>
                                            </div>
                              </div>
                              <div class="col-md-12">
                                    <div>
                                        <label for="fname" class="form-label">Địa chỉ</label>
                                        <input type="text" class="form-control shadow-none"
                                            value="<?=$getUser['address'];?>" name="address" placeholder="Địa chỉ">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div>
                                        <label for="fname" class="form-label">Kỹ năng</label>
                                        <input type="text" class="form-control shadow-none"
                                            value="<?=$getUser['skill'];?>" name="skill"
                                            placeholder="Mỗi kỹ năng cách nhau bởi dấu phẩy">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div>
                                        <label for="fname" class="form-label">Mô ta về bản thân</label>
                                        <textarea type="text" name="description" class="form-control shadow-none" rows="5"
                                            placeholder="Mô tả ngắn"><?=$getUser['description'];?></textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button name="Save" class="btn btn-primary">
                                        Cập Nhật
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<?php require_once __DIR__ . '/footer.php'; ?>