<?php 
    $path = "../";
    require_once $path.$path.'commons/utils.php';
    $id = $_SESSION['login']['id'];
    $role = $_SESSION['login']['role'];

    if($role == 0){
        // Nếu là học viên, chuyển hướng xem chuyên mục riêng
        header("Location:xemcheck.php");
        exit();
    } else if($role == 1){
        // Nếu là giáo viên, chỉ lấy các lớp mà giáo viên đó dạy
        $listRoomQuery = "SELECT cl.* FROM classes cl 
                          JOIN timetable tt ON cl.id = tt.class_id 
                          WHERE tt.teacher_id = $id 
                          GROUP BY cl.id";
        $cates = getSimpleQuery($listRoomQuery, true);
    } else {
        // Nếu là Admin, lấy toàn bộ lớp học
        $listRoomQuery = "select * from classes";
        $cates = getSimpleQuery($listRoomQuery, true);
    }
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
        <li class="active">Danh sách lớp học</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      
            <div class="row">
                <div class="col-xs-12">
                <div class="box">
                <div class="box-header">
              <h3 class="box-title">Danh sách lớp học</h3>
                  </div>
            <!-- /.box-header -->
            <div class="box-body">
            <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th style="width: 10px">#</th>
                  <th>Tên lớp</th>
                  <th>Khóa học</th>
                  <th>Số tiết học</th>
                  <th>Bắt đầu</th>
                  <th>Kết thúc</th>
                  <th>Số học viên</th>
                  <th>Tình trạng</th>
                  <?php if($_SESSION['login']['role']==500){ ?>
                  <th  style="width: 110px">
                  <a href="<?= $ADMIN_URL ?>lop/add.php"
                      class="btn btn-xs btn-success"
                      >
                      <i class="fa fa-plus"></i> Thêm
                    </a>
                  </th>
                  <?php } ?>
                </tr>
                  </thead>
                  <tbody>
                <?php foreach($cates as $row) { ?>
                <tr>
                  <td><?php echo $row['id']; ?></td>
                  <td><?php echo $row['name']; ?></td>
                  <td>
                    <?php 
                      // $course = $row['course_id'];
                      // $listCourQuery = "select * from courses where id = $course";
                      // $cour = getSimpleQuery($listCourQuery);
                      // echo $cour['name'];
                      $course = $row['course_id'];
                      $listCourQuery = "select * from courses where id = $course";
                      $cour = getSimpleQuery($listCourQuery);
                      echo ($cour) ? $cour['name'] : '<span class="text-danger">Khóa học đã bị xóa</span>';
                    ?>
                  </td>
                  <td><?php echo $cour['soTiet']; ?></td>
                  <td><?php echo $row['created_at']; ?></td>
                  <td><?php echo $row['ended_at']; ?></td>
                  <td>
                    <?php 
                    $current_class_id = $row['id'];
                    $sqlCount = "SELECT COUNT(*) as total FROM dangky WHERE class_id = $current_class_id";
                    $countRes = getSimpleQuery($sqlCount);
                    ?>
                    <input type="button" 
                          value="<?= ($countRes['total'] > 0) ? $countRes['total'] : '0'; ?>" 
                          id="<?= $row['id'] ?>" 
                          alt="<?= $row['course_id'] ?>" 
                          class="btn btn-link view_data" />
                  </td>

                  <td>
                    <?php 
                        $today = date("Y-m-d");
                        $ended_at = $row['ended_at'];
                        
                        // Kiểm tra trực tiếp xem lớp này đã được xếp lịch trong timetable chưa
                        $checkLich = getSimpleQuery("SELECT id FROM timetable WHERE class_id = $current_class_id LIMIT 1");

                        if (!$checkLich) {
                            // Trường hợp 1: Hoàn toàn chưa có dòng nào trong bảng timetable
                            echo "<span class='label label-default'>Đang chờ lịch</span>";
                        } else if ($ended_at != "0000-00-00" && $today > $ended_at) {
                            // Trường hợp 2: Có lịch nhưng ngày hiện tại đã qua ngày kết thúc
                            echo "<span class='label label-danger'>Đã kết thúc</span>";
                        } else {
                            // Trường hợp 3: Có lịch và vẫn đang trong thời gian học
                            echo "<span class='label label-success'>Đang học</span>";
                        }
                    ?>
                  </td>
                  <?php if($_SESSION['login']['role']==500){ ?>
                  <td>
                    <a href="<?= $ADMIN_URL?>lop/edit.php?id=<?= $row['id']?>" class="btn btn-xs btn-primary">
                        <i class="fa fa-cog"></i> Sửa
                    </a>

                    <?php 
                        $today = date("Y-m-d");
                        $ended_at = $row['ended_at'];
                        // Sử dụng lại biến $checkLich chúng ta đã tạo ở cột Tình trạng trước đó
                        $checkLich = getSimpleQuery("SELECT id FROM timetable WHERE class_id = ".$row['id']." LIMIT 1");

                        // ĐIỀU KIỆN KHÓA NÚT XÓA: 
                        // Lớp có lịch dạy VÀ (Ngày kết thúc chưa tới HOẶC chưa có ngày kết thúc cụ thể)
                        if ($checkLich && ($ended_at == "0000-00-00" || $today <= $ended_at)) { 
                    ?>
                        <button class="btn btn-xs btn-default" disabled title="Lớp đang học không thể xóa">
                            <i class="fa fa-lock"></i> Xóa
                        </button>
                    <?php } else { ?>
                        <a href="javascript:;"
                          linkurl="<?= $ADMIN_URL?>lop/remove.php?id=<?= $row['id']?>"
                          class="btn btn-xs btn-danger btn-remove">
                            <i class="fa fa-trash-o"></i> Xoá
                        </a>
                    <?php } ?>
                  </td>
                  <?php } ?>
                </tr>
                <?php } ?>
              </tbody>
              </table>
              <div id="dataModal" class="modal fade">  
                  <div class="modal-dialog" >  
                      <div class="modal-content">  
                            <div class="modal-header">  
                                <button type="button" class="close" data-dismiss="modal">&times;</button>  
                                <h4 class="modal-title">Danh sách học viên</h4>  
                            </div>  
                            <div class="modal-body" id="employee_detail">  
                            </div>  
                            <div class="modal-footer">  
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>  
                            </div>  
                      </div>  
                  </div>  
            </div> 
            </div>
            <!-- /.box-body -->
          </div>
                </div>
            </div>
            <script>  
                $(document).ready(function(){  
                      $(document).on('click', '.view_data', function(){  
                          var id = $(this).attr("id");  
                          var course = $(this).attr("alt");  
                                $.ajax({  
                                    url:"select.php",  
                                    method:"POST",  
                                    data:{id:id,
                                    course:course},  
                                    success:function(data){  
                                          $('#employee_detail').html(data);  
                                          $('#dataModal').modal('show');  
                                    }  
                                });         
                      });  
                });  
                </script>
            
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
  <?php include_once $path.'_share/footer.php'; ?>
</div>
<!-- ./wrapper -->

<?php include_once $path.'_share/script_assets.php'; ?>
<script type="text/javascript">
    <?php 
      if(isset($_GET['success']) && $_GET['success'] == true){
    ?> 
       swal('Tạo mới lớp học thành công!');
    <?php }else if(isset($_GET['editsuccess']) && $_GET['editsuccess'] == true){ ?>
      swal('Sửa lớp học thành công!');
    <?php }?>
    $('.btn-remove').on('click',function(){
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
    })
</script>
</body>
</html>