<?php 
    $path = "../";
    require_once $path.$path.'commons/utils.php';
    $listRoomQuery = "select c.*, (select count(*) from classes where course_id = c.id) as total, (select count(*) from lesson where course_id = c.id) as less from courses c";
    $cates = getSimpleQuery($listRoomQuery,true);
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
        <li class="active">Danh mục sản phẩm</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      
            <div class="row">
                <div class="col-xs-12">
                <div class="box">
                <div class="box-header">
              <h3 class="box-title">Danh sách khóa học</h3>

            <!-- /.box-header -->
            <div class="box-body">
            <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th style="width: 10px">#</th>
                  <th>Tên khóa học</th>
                  <th>Ảnh</th>
                  <th>Số buổi</th>
                  <th>Học phí</th>
                  <th>Số lớp học</th>
                  <th>Số bài học</th>
                  <th>
                  <a href="<?= $ADMIN_URL ?>khoahoc/add.php"
                      class="btn btn-xs btn-success"
                      >
                      <i class="fa fa-plus"></i> Thêm
                    </a>
                  </th>
                </tr>
                </thead>
                <tbody id="oday">
                <?php foreach($cates as $row) { ?>
                <tr id="<?= $row['id']; ?>">
                  <td><?php echo $row['id']; ?></td>
                  <td><a href="<?php echo SITE_URL."/course.php?id=".$row['id']; ?>"><?php echo $row['name']; ?></a> </td>
                  <td><img src="<?php echo SITE_URL.$row['image']; ?>" alt="" style="width:200px;"></td>
                  <td><?php echo $row['soTiet']; ?></td>
                  <td><?php echo $row['hocphi']." VNĐ" ?></td>
                  <td><input type="button" name="view" value="<?php echo $row['total']." lớp học"; ?>" id="<?php echo $row["id"]; ?>" class="btn btn-link view_data" /></td>
                  </td>
                  <td><a href="<?php echo $ADMIN_URL."khoahoc/lesson.php?id=".$row['id']; ?>"><?php echo $row['less']." bài học"; ?></a> </td>
                  <td>
                    <a href="<?= $ADMIN_URL?>khoahoc/edit.php?id=<?= $row['id']?>" class="btn btn-xs btn-primary">
                        <i class="fa fa-cog"></i> Sửa
                    </a>

                    <?php 
                        $today = date("Y-m-d");
                        $course_id = $row['id'];

                        // KIỂM TRA: Tìm xem có lớp nào thuộc khóa học này đang học hay không
                        // Điều kiện: Lớp thuộc khóa học này VÀ (chưa có ngày kết thúc HOẶC ngày kết thúc >= hôm nay)
                        // VÀ lớp đó phải có lịch trong timetable (đã xếp lịch)
                        $checkClassActive = getSimpleQuery("SELECT c.id FROM classes c 
                                                            JOIN timetable t ON c.id = t.class_id 
                                                            WHERE c.course_id = $course_id 
                                                            AND (c.ended_at = '0000-00-00' OR c.ended_at >= '$today') 
                                                            LIMIT 1");

                        if ($checkClassActive) { 
                    ?>
                        <button class="btn btn-xs btn-default" disabled title="Khóa học có lớp đang diễn ra, không thể xóa">
                            <i class="fa fa-lock"></i> Xóa
                        </button>
                    <?php } else { ?>
                        <a href="javascript:;"
                          linkurl="<?= $ADMIN_URL?>khoahoc/remove.php?id=<?= $row['id']?>"
                          class="btn btn-xs btn-danger btn-remove">
                            <i class="fa fa-trash-o"></i> Xoá
                        </a>
                    <?php } ?>
                  </td>
                </tr>
                <?php } ?>
              </tbody>
              </table>

              <div id="dataModal" class="modal fade">  
                  <div class="modal-dialog" >  
                      <div class="modal-content">  
                            <div class="modal-header">  
                                <button type="button" class="close" data-dismiss="modal">&times;</button>  
                                <h4 class="modal-title">Danh sách lớp</h4>  
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
    </section>
    <script>  
 $(document).ready(function(){  
      $(document).on('click', '.view_data', function(){  
           var id = $(this).attr("id");  
                $.ajax({  
                     url:"select.php",  
                     method:"POST",  
                     data:{id:id},  
                     success:function(data){  
                          $('#employee_detail').html(data);  
                          $('#dataModal').modal('show');  
                     }  
                });         
      });  
 });  
 </script>
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
       swal('Tạo mới khóa học thành công!');
    <?php }else if(isset($_GET['editsuccess']) && $_GET['editsuccess'] == true){ ?>
      swal('Sửa khóa học thành công!');
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