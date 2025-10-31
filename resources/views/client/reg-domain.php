<?php
$title = 'Đăng Ký Tên Miền - ' . $TN->site('title');
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
?>
<style>
.domain-scroll-wrapper {
    overflow: hidden;
    white-space: nowrap;
    width: 100%;
    background-color: rgba(255, 255, 255, 0.23);
    border-radius: 8px;
    padding: 16px;
    min-height: 51px;
    position: relative;
}

.domain-scroll-content {
    display: inline-block;
    white-space: nowrap;
    animation: scrollLeft 25s linear infinite;
}

.domain-item {
    display: inline-flex;
    align-items: center;
    margin-right: 32px;
    font-weight: bold;
    font-size: 16px;
    color: #000;
}

@keyframes scrollLeft {
    0% {
        transform: translateX(100%);
    }
    100% {
        transform: translateX(-100%);
    }
}

</style>
<main>
    <div class="breadcrumb-bar breadcrumb-bar-info">
        <div class="breadcrumb-img">
            <div class="breadcrumb-left">
                <img src="/assets/images/banner-bg-03.png" alt="img">
            </div>
        </div>
        <div class="container">
            <div class="row mt-3">
                <div class="col-md-12 col-12">

                    <div class="slide-title-wrap">
                        <div class="row align-items-center">
                            
   </div>
    </div>   </div>
    </div>
<div class="bg-image" style="background-image: url('/assets/images/hero-decor-img.96a8c9f17475388c3f3fe32ec3516e90.svg'); border-radius: 15px; overflow: hidden;">
  <div class="bg-black-25" style="border-radius: 15px;">
    <div class="content content-full content-top">
      <div class="py-4 text-center">
        <h1 class="fw-bold text-white mb-2">
          Đăng Ký Tên Miền 
        </h1>
        <h2 class="h3 fw-normal text-white">
          Tìm kiếm tên miền của bạn ngay bây giờ
        </h2>

        <center>
          <div class="form-container col-10">
            <input class="form-control shadow-none" placeholder="Nhập tên miền" id="domain" value="">
            <br>
            <div class="domain-scroll-wrapper">
                <div class="domain-scroll-content">
                    <?php foreach($TN->get_list("SELECT * FROM `tbl_list_domain` ORDER BY id DESC") as $row) { ?>
                        <div class="domain-item">
                            <img src="<?= $row['image'] ?>" alt="<?= $row['name'] ?>" height="19" class="mr12">
                            <span><?= format_cash($row['price']); ?>đ</span>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <br>
            <a id="WhoisDomain" class="btn btn-hero btn-primary js-click-ripple-enabled" data-toggle="click-ripple" style="overflow: hidden; position: relative; z-index: 1;" onclick="whoiGet()">
          <i class="fa fa-fw opacity-50 fa-search"></i> Kiểm tra
        </a>
          </div>
        </center>
      </div>
    </div>
  </div>
</div>



<div class="content content-boxed">
 <br> <br> 
 
 <div class="block block-rounded" id="result-domain">
  </div>
  
  
 <div class="row" id="pricingdomain">
            <?php foreach($TN->get_list("SELECT * FROM `tbl_list_domain` ORDER BY id DESC") as $row) { ?>
            <div class="col-xl-3 col-md-6 mb-4">
                        <div class="p-3 d-flex align-items-center dashobard-widget justify-content-between bg-white rounded shadow-sm">
                            <div>
                                <h3 class="dashboard-widget-title fw-bold text-dark-300">
                                   <?= format_cash($row['price']); ?>đ
                                </h3>
                                <p class="text-18 text-dark-200">Tên Miền .<?=$row['name']?></p>
                            </div>
                            <div>
                                <img src="<?=$row['image']?>" class="domain-image">
                            </div>
                        </div>
                    </div>
            <?php }?>
                </div>
            </div>
        </div>
    </div>
</main>
  <script>
      function whoiGet(){
       $('#WhoisDomain').html('Đang tìm kiếm <img src="/assets/images/Spinner@4x-1.0s-200px-200px.gif" alt="loading" style="width: 30px; height: 30px;">').prop('disabled', true);
           $.ajax({
                url: "/ajaxs/client/whoidomain.php",
                method: "POST",
                data: {
                    domain: $("#domain").val()
                },
                success: function(response) {
                    document.getElementById('result-domain').innerHTML = `<div class="block-header block-header-default">
                          <h3 class="block-title"> Kết quả kiểm tra </h3>
                        </div>
                    
                        <div class="block-content">
                          <div class="table-responsive">
                            <table class="table table-borderless table-striped table-vcenter fs-sm">
                              <thead>
                                <tr>
                                  <th> Trạng Thái </th>
                                  <th class="text-center" style="width: 100px;"> Tên Miền </th>
                                  <th class="text-center"> Giá Tiền </th>
                                  <th class="text-center"> Hành Động </th>
                                </tr>
                              </thead>
                              <tbody id="listdomain">
                              </tbody>
                            </table>

                          </div>
                          
                        </div>
                        `;
                        
                        $("#listdomain").html(response);
                        $("#pricingdomain").html('');
                        
                    $('#WhoisDomain').html('<i class="fa fa-fw opacity-50 fa-search"></i> Kiểm tra').prop('disabled', false);
                }
            });
      }
  </script>
  
  <style>
      .domain-image {
            width: 85px;
            height: 40px;
        }
  </style>
  
<?php require_once __DIR__ . '/footer.php'; ?>
