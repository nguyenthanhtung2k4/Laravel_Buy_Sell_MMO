<?php
$title = 'Nhật Ký Hoạt Động | ' . $TN->site('title');
$body['header'] = '
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>
';
$body['footer'] = '

';
require_once __DIR__ . '/../../../core/is_user.php';
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/nav.php';
CheckLogin();
?>
        <?php require_once('sidebar.php');?>
     <div class="row">
                <div class="col-md-12">
                    <div class="overflow-x-auto">
                        <div class="w-100">
                            <table id="logTable" class="w-100 dashboard-table table text-dark">
                                <thead class="pb-3">
                                    <tr>
                                        <th scope="col" class="ps-4">STT</th>
                                        <th scope="col">Hành động</th>
                                        <th scope="col">IP</th>
                                        <th scope="col">Thiết bị</th>
                                        <th scope="col">Vào lúc</th>

                                    </tr>
                                </thead>
                                <tbody>
                                <?php $i=0; foreach ($TN->get_list("SELECT * FROM `logs` WHERE `user_id` = '" . $getUser['id'] . "' ORDER BY `id` DESC") as $row) {?>
                                                                            <tr>
                                            <td class="text-dark"><?=$i++?></td>
                                            <td class="text-dark"><?= $row['action']; ?></td>
                                            <td class="text-dark"><?= $row['ip']; ?></td>
                                            <td class="text-dark"><?= $row['device']; ?></td>
                                            <td class="text-dark"><?= format_date($row['create_date']); ?></td>
                                        </tr>
                                <?php }?>
                                </tbody>
                            </table>
                                                    </div>
                        <div class="d-flex justify-content-end">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        flatpickr("#purchase_date", {
            mode: "range",
            dateFormat: "Y-m-d"
        });
    });
    document.addEventListener('DOMContentLoaded', function() {
        $('#logTable').DataTable();
    });
</script>
<?php require_once __DIR__ . '/footer.php'; ?>