<?php 
$path = "../";
require_once $path.$path.'commons/utils.php';
$i = 0;
$search = "";
if(isset($_POST['tk'])){
  if($_POST['search'] != ""){
    $search = " and email like '%".$_POST['search']."%'";
    $i = 1;
  }
}
$sql = "SELECT * FROM dangky INNER join student on dangky.student_id = student.id  where status = 0".$search;
$users = getSimpleQuery($sql, true);
  ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>POLY | Tài khoản</title>
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
        <li class="active">Danh sách khách hàng</li>
      </ol>
    </section>
     <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
            <h3 class="box-title">Danh sách khách hàng</h3>
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
            <?php if($i == 1 && $_POST['search'] != "" ){ ?>
            <p>Kết quả tím kiếm cho <strong><em><?php echo $_POST['search'] ?></em></strong> </p>
            <?php } ?>
              <table class="table table-bordered">
                <tbody>
                <tr>
                  <th style="width: 10px">#</th>
                  <th>Email</th>
                  <th>Tên khách hàng</th>
                  <th>Số điện thoại</th>
                  <!-- <th style="width: 100px">Ảnh đại diện</th>
                  <th>Ngày sinh</th>
                  <th>Giới tính</th>
                  <th>Địa chỉ</th>
                  <th>Liên hệ</th> -->
                  <th>Đăng ký khóa học</th>
                  <th>Đăng ký lớp</th>
                  <!-- <th>Học phí</th> -->
                  <th>Trạng thái</th>
                  <th style="width: 120px">
                    <a href="<?= $ADMIN_URL?>khachhang/add.php"
                      class="btn btn-xs btn-success"
                      >
                      Thêm
                    </a>
                    <!-- Chức năng -->
                  </th>
                </tr>
                <?php foreach($users as $u): ?>
                  <tr>
                    <td><?= $u['student_id']?></td>
                    <td><?= $u['email']?></td>
                    <td><?= $u['fullname']?></td>
                    <td><?= $u['phone']?></td>
                    <td>
                      <?php $id =  $u['course_id'];
                      $sql = "SELECT * FROM courses where id = $id";
                      $course = getSimpleQuery($sql);
                      echo $course['name'];
                      ?>
                    </td>
                    <td>
                      <?php $id = $u['class_id'];
                      $sql = "SELECT * FROM classes where id = $id";
                      $class = getSimpleQuery($sql);
                      echo $class['name'];?>
                    </td>
                    <!-- <td><?= $u['hocphi']?></td> -->
                    <td>
                      <?= $u['status'] == 1 ? "<p class='text-primary'>Đang học</p>" : "<p class='text-primary'>Đang xử lý</p>" ?>
                    </td>
                    <!-- <td>
                      <img src="<?= SITE_URL.$u['avatar'] ?>" width="50">
                    </td>
                    <td>
                      <?= $u['date']?>
                    </td>
                    <td>
                      <?= $u['gender']== 0 ? "Nam" : "Nữ";?>
                    </td>
                    <td>
                      <?= $u['address']?>
                    </td>
                    <td>
                      <?= $u['phone']?>
                    </td>
                    <td>
                      <?php $class = $u['class_id'];
                      $sql = "select *
                      from classes where id = $class";
              $users = getSimpleQuery($sql);
              echo $users['name'];
                      ?>
                    </td> -->
                    <td>
                      <a href="<?= $ADMIN_URL?>khachhang/change.php?id=<?= $u['id']?>"
                      class="btn btn-xs btn-primary"
                      >
                        Xác nhận 
                      </a>
                      <a href="javascript:;"
                        linkurl="<?= $ADMIN_URL?>khachhang/remove.php?id=<?= $u['id']?>"
                      class="btn btn-xs btn-danger btn-remove"
                      >
                        Xoá
                      </a>
                    </td>
                  </tr>
                <?php endforeach ?>
                </tbody>
              </table>
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
       swal('Tạo mới khách hàng thành công!');
    <?php }else if(isset($_GET['editsuccess']) && $_GET['editsuccess'] == true){ ?>
      swal('Sửa khách hàng thành công!');
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