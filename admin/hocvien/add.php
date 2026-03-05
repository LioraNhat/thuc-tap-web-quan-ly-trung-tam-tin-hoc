<?php 
    $path = "../";
    require_once $path.$path.'commons/utils.php';
    $listRoomQuery = "select * from courses";
    $cates = getSimpleQuery($listRoomQuery,true);

    $listClassQuery = "select * from classes";
    $classes = getSimpleQuery($listClassQuery,true);
 ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>POLY | Danh mục</title>
  <?php include_once $path.'_share/style_assets.php'; ?>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  <?php include_once $path.'_share/header.php'; ?>
  
  <?php include_once $path.'_share/sidebar.php'; ?>
  

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Dashboard
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Thêm học viên</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      
            <div class="row">
                <div class="col-xs-12">
                <div class="box">
                <div class="box-header">
              <h3 class="box-title">Thêm học viên</h3>

            <!-- /.box-header -->
            <div class="box-body">
            <form action="<?= $ADMIN_URL ?>hocvien/save-add.php" method="post" enctype="multipart/form-data">
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label>Email</label>
            <input type="text" name="email" class="form-control">
            <?php 
              if(isset($_GET['e'])){
                ?>
                <span class="text-danger"><?= $_GET['e'] ?></span>
                <?php
              }
             ?>
          </div>
          <div class="form-group">
            <label>Tên đầy đủ</label>
            <input type="text" name="fullname" class="form-control">
            <?php 
              if(isset($_GET['n'])){
                ?>
                <span class="text-danger"><?= $_GET['n'] ?></span>
                <?php
              }
             ?>
          </div>
          <div class="form-group">
            <label for="">Số điện thoại</label>
            <input type="number" class="form-control" name="phone">
            <?php  
              if(isset($_GET['ph'])){
            ?>
            <span class="text-danger"><?= $_GET['ph']; ?></span>
            <?php        
              }
            ?>
          </div>
          
          <div class="text-center">
            <a name="<?= $ADMIN_URL ?>hocvien" id="" class="btn btn-danger btn-xs" href="<?= $ADMIN_URL ?>hocvien" role="button">Hủy</a>
            <button type="submit" name="" class="btn btn-xs btn-primary">Tạo mới</button>
          </div>
        </div>
        <div class="col-md-6">
          <!-- Địa chỉ -->
            <div class="form-group">
              <label>Địa chỉ</label>
              <input type="text" name="address" class="form-control">
              <?php 
                if(isset($_GET['a'])){
                  ?>
                  <span class="text-danger"><?= $_GET['a'] ?></span>
                  <?php
                }
              ?>
            </div>
            <!-- Ngày sinh -->
             <div class="form-group">
              <label>Ngày sinh</label>
              <input type="date" name="ngaysinh" class="form-control">
              <?php 
                if(isset($_GET['ns'])){
                  ?>
                  <span class="text-danger"><?= $_GET['ns'] ?></span>
                  <?php
                }
              ?>
            </div>
             <!-- Giới tính -->
            <div class="form-group">
              <label>Giới tính</label><br>
              <label>
                <input type="radio" name="gender" value="1" checked> Nam
              </label>
              
              <label style="margin-left: 15px;">
                <input type="radio" name="gender" value="-1"> Nữ
              </label>
              <?php 
                if(isset($_GET['ge'])){
                  ?>
                  <span class="text-danger"><?= $_GET['ge'] ?></span>
                  <?php
                }
              ?>
            </div>
        <div>
                
      </div>
                
        </form>

      <script type="text/javascript">
            $(document).ready(function(){
              $('#course_id').change(function(){
                                var course = $('#course_id').val();
                                $.ajax({
                                    url:"../baitap/xulysubject.php",
                                    method:"post",
                                    data: {
                                      course:course},
                                    dataType:"text",
                                    success: function(kq){
                                        $('#class_id').html(kq);
                                    }
                                  }); 
                })
            });

            // Lấy thông tin học phí
            $('#course_id').change(function(){
                var fee = $('#course_id option:selected').data('fee');
                $('#hocphi').val(fee);
            });
          </script>
            </div>
            <!-- /.box-body -->
          </div>
                </div>
            </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
  <?php include_once $path.'_share/footer.php'; ?>
</div>
<!-- ./wrapper -->

<?php include_once $path.'_share/script_assets.php'; ?>
<script type="text/javascript">
$(document).ready(function(){
    $('[name="des"]').wysihtml5();
  });
    $('.btn-remove').on('click',function(){
      swal({
      title: "Cảnh báo!",
      text: "Bạn có chắc chắn muốn xoá môn học này ?",
      icon: "warning",
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        window.location.href = $(this).attr('linkurl');
      }
      });
    })
</script>
</body>
</html>