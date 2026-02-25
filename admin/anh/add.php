<?php 
$path = "../";
require_once $path.$path.'commons/utils.php';
 ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>AdminLTE 2 | Dashboard</title>
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
        DANH SÁCH
        <small>Ảnh</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Trang chủ</a></li>
        <li class="active">Danh sách</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

        <form action="<?= $ADMIN_URL ?>anh/save_add.php" method="post"  enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="">URL</label>
                    <input type="text" class="form-control" name="url">
                    <?php  
                        if(isset($_GET['erU'])){
                    ?>
                        <span class="text-danger"><?= $_GET['erU']; ?></span>
                    <?php        
                        }
                    ?>
                </div>
                <div class="form-group">
                    <label for="">Số thứ tự</label>
                    <input type="text" class="form-control" name="order">
                    <?php  
                        if(isset($_GET['erO'])){
                    ?>
                        <span class="text-danger"><?= $_GET['erO']; ?></span>
                    <?php        
                        }
                    ?>
                </div>
                <div class="form-group">
                                    <label>Trạng thái</label>
                                    <br>
                                    <input type="radio" name="status" value="1"> Active &nbsp;
                                    <input type="radio" name="status" value="-1"> Inactive
                                    <?php  
                                        if(isset($_GET['erS'])){
                                    ?>
                                        <span class="text-danger"><?= $_GET['erS']; ?></span>
                                    <?php        
                                        }
                                    ?>
                                    </div>
            </div>
            <div class="col-md-6">
            <img src="<?= SITE_URL ?>img/default.jpg" alt="" style="max-width:130px;" id="showImage">
                <div class="form-group">
                    <label for="exampleFormControlFile1">Hình ảnh</label>
                    <input type="file" class="form-control" id="exampleFormControlFile1" name="image">
                    <?php  
                        if(isset($_GET['erI'])){
                    ?>
                        <span class="text-danger"><?= $_GET['erI']; ?></span>
                    <?php        
                        }
                    ?>
                </div>
            </div>
            </div>
                <div>
                    <a name="<?= $ADMIN_URL ?>anh" id="" class="btn btn-danger btn-xs" href="#" role="button">Hủy</a>
                    <button type="submit" name="" class="btn btn-xs btn-primary">Tạo mới</button>
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
<script type="text/javascript">
  $(document).ready(function(){
    
    var img = document.querySelector('[name="image"]');
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
       reader.onerror = function (error) {
         console.log('Error: ', error);
       };
    }
  });
</script>
</body>
</html>