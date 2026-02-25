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
        Dashboard
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

        <form action="<?= $ADMIN_URL ?>cau-hinh/save-add.php" method="post" enctype="multipart/form-data">
        <div class="row">
                 <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleFormControlSelect1">Hotline</label>
                                                <input type="text" class="form-control" name="hotline" value="">
                                                <?php  
                                                        if(isset($_GET['HL'])){
                                                    ?>
                                                        <span class="text-danger"><?= $_GET['HL']; ?></span>
                                                    <?php        
                                                        }
                                                    ?>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Maps</label>
                                                <textarea rows="6" class="form-control" name="map"></textarea>
                                                <?php  
                                                        if(isset($_GET['m'])){
                                                    ?>
                                                        <span class="text-danger"><?= $_GET['m']; ?></span>
                                                    <?php        
                                                        }
                                                    ?>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Email</label>
                                                <input type="text" class="form-control" name="email" value="">
                                                <?php  
                                                        if(isset($_GET['e'])){
                                                    ?>
                                                        <span class="text-danger"><?= $_GET['e']; ?></span>
                                                    <?php        
                                                        }
                                                    ?>
                                            </div> 
                                                    <div class="form-group">
                                                <label for="">Timework</label>
                                                <input type="text" class="form-control" name="timework" value="">
                                                <?php  
                                                        if(isset($_GET['t'])){
                                                    ?>
                                                        <span class="text-danger"><?= $_GET['t']; ?></span>
                                                    <?php        
                                                        }
                                                    ?>
                                            </div>
                                            </div>
               
                <div class="col-md-6">
                <img src="<?= SITE_URL ?>img/default.jpg" alt="" style="max-width:130px;" id="showImage">
                                <div class="form-group">
                                    <label for="exampleFormControlFile1">Logo</label>
                                    <input type="file" class="form-control" id="exampleFormControlFile1" name="logo">
                                    <?php        
                                        if(isset($_GET['erI'])){
                                    ?> 
                                        <span class="text-danger"><?= $_GET['erI']; ?></span>
                                    <?php        
                                        }?>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Facebook</label>
                                        <textarea rows="5" class="form-control" name="fb"></textarea>
                                    </div>
                                    <?php  
                                            if(isset($_GET['f'])){
                                        ?>
                                            <span class="text-danger"><?= $_GET['f']; ?></span>
                                        <?php        
                                            }
                                        ?>
                </div>
                </div>
                <div>
                    <input type="hidden" name="id" value="<?= $web['id']; ?>">
                    <input type="hidden" name="old_logo" value="<?= $web['logo']; ?>">
                    <a name="<?= $ADMIN_URL ?>cau-hinh" id="" class="btn btn-danger btn-xs" href="#" role="button">Hủy</a>
                    <button type="submit" name="sua" class="btn btn-xs btn-primary">Thêm</button>
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
    
    var img = document.querySelector('[name="logo"]');
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