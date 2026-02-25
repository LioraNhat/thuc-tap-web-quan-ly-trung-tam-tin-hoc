<?php
  $path = "./";
    require_once $path.'../commons/utils.php';
    $listRoomQuery = "select count(*) as total from courses";
    $cates = getSimpleQuery($listRoomQuery);

    $listClassQuery = "select count(*) as total from classes";
    $classes = getSimpleQuery($listClassQuery);

    $listUserQuery = "select count(*) as total from users";
    $users = getSimpleQuery($listUserQuery);

    $listTeaQuery = "select count(*) as total from teachers";
    $teas = getSimpleQuery($listTeaQuery);

    $listStuQuery = "select count(*) as total from student";
    $stu = getSimpleQuery($listStuQuery);

    $listCusQuery = "select count(*) as total from student where status = 0";
    $cus = getSimpleQuery($listCusQuery);

    if($_SESSION['login']['role']==1){ 
      header('location: '. $ADMIN_URL . 'thoikhoabieu');
    }

    // if($_SESSION['login']['role']==0){ 
    //   header('location: '. $AP_URL);
    // }
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>HỆ THỐNG QUẢN LÝ ĐÀO TẠO</title>
  <!-- Tell the browser to be responsive to screen width -->
  <?php include_once $path.'./_share/style_assets.php' ?>
  <style>
  #alert_popover
  {
   display:block;
   position:absolute;
   bottom:300px;
   left:250px;
  }
  .wrapper1 {
    display: table-cell;
    vertical-align: bottom;
    height: auto;
    width:450px;
  }
  .alert_default
  {
   color: #333333;
   background-color: white;
   border-top: 2px solid #00c0ef;
  }
  </style>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include_once $path.'./_share/header.php';
  include_once $path.'./_share/sidebar.php'; ?>

  <!-- Left side column. contains the logo and sidebar -->
  

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        HỆ THỐNG QUẢN LÝ ĐÀO TẠO
        <small>NextTech Acadamy</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Trang chủ</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
    <div class="row">
    <?php if($_SESSION['login']['role']==500){ ?>
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3><?= $cates['total']; ?></h3>

              <p>Khoá học</p>
            </div>
            <div class="icon">
              <i class="ion ion-bag"></i>
            </div>
            <a href="<?= $ADMIN_URL; ?>khoahoc" class="small-box-footer">Xem thêm <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3><?= $classes['total']; ?></h3>

              <p>Lớp học</p>
            </div>
            <div class="icon">
              <i class="ion ion-stats-bars"></i>
            </div>
            <a href="<?= $ADMIN_URL; ?>lop" class="small-box-footer">Xem thêm <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3><?= $users['total']; ?></h3>

              <p>Nhân viên</p>
            </div>
            <div class="icon">
              <i class="ion ion-person-add"></i>
            </div>
            <a href="<?= $ADMIN_URL; ?>nhanvien" class="small-box-footer">Xem thêm <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3><?= $teas['total']; ?></h3>

              <p>Giáo viên</p>
            </div>
            <div class="icon">
              <i class="ion ion-pie-graph"></i>
            </div>
            <a href="<?php echo $ADMIN_URL ?>giaovien" class="small-box-footer">Xem thêm <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
  <?php } ?>

        <!-- ./col -->
      </div>
    </section>
      <div id="alert_popover">
      <div class="wrapper1">
      <div class="content12">
  
      </div>
      </div>
    </div>
    <!-- /.content -->
  </div>
  <script>
$(document).ready(function(){
  <?php 
    $day = date("Y/m/d");
    $student_role= $_SESSION['login']['role'];
    $student_id = $_SESSION['login']['id'];
    $listRoomQuery = "SELECT * FROM feedback_details WHERE created_at = '$day' and student_id = '$student_id'";
    $cates = getSimpleQuery($listRoomQuery);

    
    $listFeedQuery = "SELECT * FROM feedback WHERE created_at = '$day'";
    $feed = getSimpleQuery($listFeedQuery);
    if($student_role==0 && $feed && !$cates){ ?>
      $.ajax({
      url:"fetch.php",
      method:"POST",
      success:function(data)
      {
        $('.content12').html(data);
      }
    })
<?php } ?>
});
</script>
  <!-- /.content-wrapper -->
  <?php include_once $path.'./_share/footer.php';
        include_once $path.'./_share/script_assets.php';
  ?>

  <!-- Control Sidebar -->
  

  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->

</body>
</html>
