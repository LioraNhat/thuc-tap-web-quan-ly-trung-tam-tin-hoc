<?php 
$path = "../";
require_once $path.$path.'commons/utils.php';
$id = $_GET['id'];
$listWebQuery = "select *
                    from web_settings
                    where id = $id";
$web = getSimpleQuery($listWebQuery,false);
if(isset($_POST['sua'])){
    $id = $_POST['id'];
    $hotline = $_POST['hotline'];
    $map = $_POST['map'];
    $email = $_POST['email'];
    $fb = $_POST['fb'];

    $hl = $m = $e = $f = "";
    if($hotline == ""){
        $hl = "Nhập sdt";
    }else{
        $hl = "";
    }

    if($map == ""){
        $m = "Nhập địa chỉ map";
    }else{
        $m = "";
    }

    if($email == ""){
        $e = "Nhập email";
    }else{
        $e = "";
    }
    if($fb == ""){
        $f = "Nhập fb";
    }else{
        $f = "";
    }


    $logo='img/'.$_FILES['logo']['name'];
    if($logo == "img/"){
        $logo = $_POST['old_logo'];
    }else{
    $size = $_FILES['logo']['size'];
    $logo_tmp= $_FILES['logo']['tmp_name'];
    $duoianh=strtolower(end(explode('.',$_FILES['logo']['name'])));

    /*$dang= array("jpeg","jpg","png");
    if($logo == "img/"){
       echo $erI ='&&erI=Chọn ảnh.';
    }
    else if(in_array($duoianh,$dang)=== false){
      echo  $erJ ='&&erJ=Chỉ hỗ trợ upload file JPEG hoặc PNG.';
    }else if($size > 2097152) {
      echo  $erS ='&&erQ=Kích thước file không được lớn hơn 2MB';
    } */
    move_uploaded_file($image_tmp,'../../'.$image);
    }

   /* if($name=="" || $image == "" || $sell_price=="" || $quantity=="" || $erJ !="" || $erS !=""){
        $erN = 'errName=Vui lòng không để trống tên sản phẩm';
        $erP = '&&errPrice=Vui lòng nhập giá sản phẩm';
        $erQ = '&&errQty=Vui lòng nhập số lượng sản phẩm';
        header('location: '.$ADMIN_URL.'san-pham/edit.php?id='.$id.$erN.$erP.$erQ.$erJ.$erS.$erI);
        die;
    }

    $sql = "select * from products where product_name = '$name' and id <> '$id'";
    $rs = getSimpleQuery($sql);
    if($rs != false){
        header('location: '.$ADMIN_URL.'san-pham/edit.php?id='.$id.'&&errName=Tên danh mục đã tồn tại, vui lòng chọn tên khác!');
        die;
    }*/

    $sql = "update web_settings set logo = '$logo', hl = '$hotline', map = '$map', email = '$email', fb = '$fb' where id = '$id'";
    getSimpleQuery($sql);
    header('location: '.$ADMIN_URL.'cau-hinh?success=true');
    die;
}
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

        <form action="save-edit.php" method="post" enctype="multipart/form-data">
        <div class="row">
                 <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="exampleFormControlSelect1">Hotline</label>
                                                <input type="text" class="form-control" name="hotline" value="<?= $web['hl']; ?>">
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
                                                <textarea rows="6" class="form-control" name="map"><?= $web['map']; ?></textarea>
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
                                                <input type="text" class="form-control" name="email" value="<?= $web['email']; ?>">
                                                <?php  
                                                    if(isset($_GET['e'])){
                                                ?>
                                                    <span class="text-danger"><?= $_GET['e']; ?></span>
                                                <?php        
                                                    }
                                                ?>
                                            </div> 
                                            <div class="form-group">
                                                <label for="">TimeWork</label>
                                                <input type="text" class="form-control" name="timework" value="<?= $web['timework']; ?>">
                                                <?php  
                                                    if(isset($_GET['t'])){
                                                ?>
                                                    <span class="text-danger"><?= $_GET['t']; ?></span>
                                                <?php        
                                                    }
                                                ?>
                                            </div> 
                                                <?php /* 
                                                        if(isset($_GET['errPrice'])){
                                                    ?>
                                                        <span class="text-danger"><?= $_GET['errPrice']; ?></span>
                                                    <?php        
                                                        } */
                                                    ?>
                                            </div>
               
                <div class="col-md-6">
                                <img src="<?= SITE_URL.$web['logo']; ?>" alt="" style="max-width:130px;" id="showImage">
                                <div class="form-group">
                                    <label for="exampleFormControlFile1">Logo</label>
                                    <input type="file" class="form-control" id="exampleFormControlFile1" name="logo">
                                    <?php      
                                        if(isset($_GET['erI'])){
                                    ?> 
                                        <span class="text-danger"><?= $_GET['erI']; ?></span>
                                    <?php        
                                        } 
                                        ?>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Facebook</label>
                                        <textarea rows="6" class="form-control" name="fb"><?= $web['fb']; ?></textarea>
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
                    <button type="submit" name="sua" class="btn btn-xs btn-primary">Sửa</button>
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
        document.querySelector('#showImage').src = "<?= SITE_URL.$web['logo']?>";
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