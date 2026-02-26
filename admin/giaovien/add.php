<?php 
$path = "../";
require_once $path.$path.'commons/utils.php';
 ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>POLY | Thêm tài khoản</title>
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
        Thêm tài khoản
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?= $ADMIN_URL ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>
     <!-- Main content -->
    <section class="content">
      <form action="<?= $ADMIN_URL ?>giaovien/save-add.php" method="post" enctype="multipart/form-data">
        <div class="row">
          <!-- Cột bên trái -->
          <div class="col-md-6">
            <div class="form-group">
              <!-- Email -->
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
            <!-- Họ tên -->
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
            <!-- Số điện thoại -->
            <div class="form-group">
              <label>Số điện thoại</label>
              <input type="text" name="phone" class="form-control">
              <?php 
                if(isset($_GET['ph'])){
                  ?>
                  <span class="text-danger"><?= $_GET['ph'] ?></span>
                  <?php
                }
              ?>
            </div>
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

            <!-- Mật khẩu -->
            <div class="form-group">
              <label>Mật khẩu</label>
              <input type="password" name="password" class="form-control">
              <?php 
                if(isset($_GET['p'])){
                  ?>
                  <span class="text-danger"><?= $_GET['p'] ?></span>
                  <?php
                }
              ?>
            </div>
            <div class="form-group">
              <label>Xác nhận mật khẩu</label>
              <input type="password" name="cfpassword" class="form-control">
              <?php 
                if(isset($_GET['cp'])){
                  ?>
                  <span class="text-danger"><?= $_GET['cp'] ?></span>
                  <?php
                }
              ?>
            </div>

            <div class="text-center">
              <a href="<?= $ADMIN_URL?>giaovien" class="btn btn-danger btn-xs">Huỷ</a>
              <button type="submit" class="btn btn-xs btn-primary">Tạo mới</button>
            </div>
          </div>
          
          <!-- Cột bên phải -->
          <div class="col-md-6">
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
            <!-- Avatar -->
            <img src="<?= SITE_URL ?>img/default.jpg" alt="" style="max-width:220px;" id="showImage">
            <div class="form-group">
                <label for="exampleFormControlFile1">Hình ảnh</label>
                <input type="file" class="form-control" id="exampleFormControlFile1" name="avatar">
                <?php  
                  if(isset($_GET['i'])){
                ?>
                <span class="text-danger"><?= $_GET['i']; ?></span>
                <?php        
                  }
                ?>                      
            </div>
          </div>
        </div>

      </form>
     </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
  <?php include_once $path.'_share/footer.php'; ?>
</div>
<!-- ./wrapper -->
 <?php include_once $path.'_share/script_assets.php'; ?>

<script>
  $(document).ready(function(){

    var img = document.querySelector('[name="avatar"]');

    img.onchange = function(){
      var anh = this.files[0];
      if(anh == undefined){
        document.querySelector('#showImage').src = "<?= SITE_URL ?>img/default.jpg";
      }else{
        getBase64(anh, '#showImage');
      }
    }

    function getBase64(file, selector) {
       var reader = new FileReader();
       reader.readAsDataURL(file);
       reader.onload = function () {
         document.querySelector(selector).src = reader.result;
       };
    }
});
</script>
</body>
</html>