<?php 
$path = "../";
require_once $path.$path.'commons/utils.php';

if($_SESSION['login']['role']==0){
  header("Location:../lop/xemdiem.php");
}
$i = 0;
$search = "";
if(isset($_POST['tk'])){
  if($_POST['search'] != ""){
    $search = " email like '%".$_POST['search']."%' and ";
    $i = 1;
  }
}
$sql1 = "SELECT * FROM student where ".$search." status = 1 ORDER BY id DESC";
$users = getSimpleQuery($sql1, true);
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>POLY | Tài khoản</title>
  <?php include_once $path.'_share/style_assets.php'; ?>
  <style>
    /* Custom modal override */
    #modalChiTietHV .modal-dialog {
      width: 90vw;
      max-width: 860px;
      margin: 30px auto;
    }
    #modalChiTietHV .modal-content {
      border: none;
      border-radius: 14px;
      overflow: hidden;
      box-shadow: 0 20px 60px rgba(0,0,0,0.25);
    }
    #modalChiTietHV .modal-header {
      background: #0f2544;
      border: none;
      padding: 14px 20px;
      display: flex;
      align-items: center;
    }
    #modalChiTietHV .modal-title {
      color: #fff;
      font-size: 14px;
      font-weight: 600;
      letter-spacing: 0.3px;
    }
    #modalChiTietHV .modal-header .close {
      color: rgba(255,255,255,0.7);
      opacity: 1;
      font-size: 22px;
      margin-top: -2px;
      text-shadow: none;
      transition: color 0.2s;
    }
    #modalChiTietHV .modal-header .close:hover {
      color: #fff;
    }
    #modalChiTietHV .modal-body {
      padding: 0;
      background: #f0f4f8;
      max-height: 78vh;
      overflow-y: auto;
      overflow-x: hidden;
    }
    #modalChiTietHV .modal-footer {
      background: #f7fafc;
      border-top: 1px solid #e2e8f0;
      padding: 12px 20px;
      text-align: right;
    }
    /* Loading spinner */
    .hv-loading {
      text-align: center;
      padding: 60px 20px;
      color: #7a8fa6;
    }
    .hv-loading i {
      font-size: 32px;
      display: block;
      margin-bottom: 10px;
      animation: spin 1s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }

    /* Clickable row style */
    .hv-row-click {
      cursor: pointer;
      transition: background 0.15s;
    }
    .hv-row-click:hover {
      background: #eef4ff !important;
    }
    .hv-row-click td:first-child {
      border-left: 3px solid transparent;
      transition: border-color 0.15s;
    }
    .hv-row-click:hover td:first-child {
      border-left-color: #2b6cb0;
    }
    /* Bảng học phí không bị cắt - scroll ngang nếu cần */
    #modalChiTietHV .hv-table-wrap {
      overflow-x: auto;
      -webkit-overflow-scrolling: touch;
    }
    #modalChiTietHV .hv-table {
      min-width: 540px;
      width: 100%;
    }
  </style>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  <?php include_once $path.'_share/header.php'; ?>
  <?php include_once $path.'_share/sidebar.php'; ?>

  <div class="content-wrapper">
    <section class="content-header">
      <h1>Dashboard <small>Danh sách học viên</small></h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Danh sách học viên</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Danh sách học viên</h3>
              <div class="box-tools">
                <form class="form-inline" action="" method="post">
                  <div class="input-group input-group-sm" style="width: 200px; margin-top:5px;">
                    <input type="text" name="search" class="form-control" placeholder="Search">
                  </div>
                  <button style="margin-top:5px;" type="submit" name="tk" class="btn btn-primary btn-sm">
                    <i class="fa fa-search"></i> Tìm kiếm
                  </button>
                </form>
              </div>
            </div>

            <div class="box-body">
              <?php if($i == 1 && $_POST['search'] != ""): ?>
                <p>Kết quả tìm kiếm cho <strong><em><?= htmlspecialchars($_POST['search']) ?></em></strong></p>
              <?php endif; ?>

              <p class="text-muted" style="font-size:12px; margin-bottom:8px;">
                <i class="fa fa-info-circle"></i> Click vào tên học viên để xem chi tiết.
              </p>

              <table class="table table-bordered">
                <tbody></tbody>
              </table>
              <input type="hidden" id="sql1" value="<?= htmlspecialchars($sql1) ?>">
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <!-- ===== MODAL CHI TIẾT HỌC VIÊN ===== -->
  <div id="modalChiTietHV" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title">
            <i class="fa fa-user-o" style="margin-right:8px;"></i>Chi tiết học viên
          </h4>
        </div>
        <div class="modal-body" id="hvDetailBody">
          <div class="hv-loading">
            <i class="fa fa-circle-o-notch"></i>
            Đang tải...
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">
            <i class="fa fa-times"></i> Đóng
          </button>
        </div>
      </div>
    </div>
  </div>
  <!-- ===== END MODAL ===== -->

  <?php include_once $path.'_share/footer.php'; ?>
</div>

<?php include_once $path.'_share/script_assets.php'; ?>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
$(document).ready(function(){

  // Load danh sách học viên qua pagination
  load_data();
  function load_data(page){
    var sql = $('#sql1').val();
    $.ajax({
      url: "pagination.php",
      method: "POST",
      data: { page: page, sql: sql },
      success: function(data){
        $('tbody').html(data);
      }
    });
  }
  $(document).on('click', '.pagination_link', function(){
    load_data($(this).attr("id"));
  });

  // Mở modal chi tiết khi click vào dòng học viên
  $(document).on('click', '.hv-row-click', function(){
    var id = $(this).data('id');
    // Reset nội dung về loading
    $('#hvDetailBody').html(
      '<div class="hv-loading"><i class="fa fa-circle-o-notch"></i>Đang tải...</div>'
    );
    $('#modalChiTietHV').modal('show');

    // Load nội dung detail.php
    $.ajax({
      url: 'detail.php',
      method: 'GET',
      data: { id: id },
      success: function(html){
        $('#hvDetailBody').html(html);
      },
      error: function(){
        $('#hvDetailBody').html(
          '<div class="hv-loading" style="color:#e53e3e;">' +
          '<i class="fa fa-exclamation-circle"></i>Không thể tải thông tin.</div>'
        );
      }
    });
  });

  // Xóa học viên
  $(document).on('click', '.btn-remove', function(e){
    e.stopPropagation(); // Không trigger click dòng
    var url = $(this).attr('linkurl');
    swal({
      title: "Cảnh báo!",
      text: "Bạn có chắc chắn muốn xoá học viên này?",
      icon: "warning",
      buttons: true,
      dangerMode: true,
    }).then(function(willDelete){
      if(willDelete){
        window.location.href = url;
      }
    });
  });

});

<?php if(isset($_GET['success']) && $_GET['success']): ?>
  swal('Tạo mới học viên thành công!').then(function(){
    window.history.replaceState(null, null, window.location.pathname);
  });
<?php elseif(isset($_GET['editsuccess']) && $_GET['editsuccess']): ?>
  swal('Sửa học viên thành công!').then(function(){
    window.history.replaceState(null, null, window.location.pathname);
  });
<?php endif; ?>
</script>
</body>
</html>