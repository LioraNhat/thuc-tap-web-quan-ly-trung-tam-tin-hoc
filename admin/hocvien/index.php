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
$sql1 = "SELECT * FROM student where ".$search." status = 1";
$users = getSimpleQuery($sql1, true);
  ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="==edge">  <title>POLY | Tài khoản</title>
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
        <small>Danh sách học viên</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Danh sách học viên</li>
      </ol>
    </section>
     <!-- Main content -->
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
                <button style="margin-top:5px;" type="submit" name="tk" id="" class="btn btn-primary btn-sm">Tìm kiếm</button>
              </div>
            </form>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
            <?php if($i == 1  && $_POST['search'] != "" ){ ?>
            <p>Kết quả tím kiếm cho <strong><em><?php echo $_POST['search'] ?></em></strong> </p>
            <?php } ?>
              <table class="table table-bordered">
                <tbody>
                </tbody>
              </table>
              <input type="hidden" id="sql1" value="<?php echo $sql1 ?>">
              <script>  
                  $(document).ready(function(){  
                        load_data();  
                        function load_data(page)  
                        {  
                        var sql = $('#sql1').val();
                            $.ajax({  
                                  url:"pagination.php",  
                                  method:"POST",  
                                  data:{page:page,
                                  sql:sql},  
                                  success:function(data){  
                                      $('tbody').html(data);  
                                  }  
                            })  
                        }  
                        $(document).on('click', '.pagination_link', function(){  
                            var page = $(this).attr("id");  
                            load_data(page);  
                        });  
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
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script type="text/javascript">
  <?php 
      if(isset($_GET['success']) && $_GET['success'] == true){
    ?> 
       swal('Tạo mới học viên thành công!');
    <?php }else if(isset($_GET['editsuccess']) && $_GET['editsuccess'] == true){ ?>
      swal('Sửa học viên thành công!');
    <?php }?>
   $('.btn-remove').on('click', function(){
    swal({
      title: "Cảnh báo!",
      text: "Bạn có chắc chắn muốn xoá danh mục này ?",
      icon: "warning",
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        window.location.href = $(this).attr('linkurl');
      }
    });
    // var conf = confirm('Bạn có xác nhận muốn xoá danh này hay không?');
    // if(conf){
    //   window.location.href = $(this).attr('linkurl');
    // }
  });
</script>
 </body>
</html>