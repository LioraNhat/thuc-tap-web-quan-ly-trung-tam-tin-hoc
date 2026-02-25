<?php 
$path = "../";
require_once $path.$path.'commons/utils.php';
$listBrandQuery = "select * from slideshows order by id";
    $brands = getSimpleQuery($listBrandQuery,true);
 ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>POLY | Đối tác</title>
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
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      
    <div class="row">
                <div class="col-xs-12">
                <div class="box">
                <div class="box-header">
                <h3 class="box-title">Phản hồi</h3>
            </div>

           <script type="text/javascript">
                $(document).ready(function(){
                    $('#search1').keyup(function(){
                        var txt1 = $('#search1').val();
                        $.ajax({
                            url:"xuly.php",
                            method:"post",
                            data: 'search='+txt1,
                            dataType:"text",
                            success: function(kq){
                                $('tbody').html(kq);
                            }
                        }); 
                    })
                })
                function xoa(id){
                      swal({
                        title: "Cảnh báo!",
                        text: "Bạn có chắc chắn muốn xoá danh mục này ?",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                      })
                      .then((willDelete) => {
                        if (willDelete) {
                                      $.ajax({
                                          url:"xuly.php",
                                          method:"post",
                                          data: {id:id},
                                          dataType:"text",
                                          success: function(kq){
                                              $('tbody').html(kq);
                                          }
                                    })
                                  }
                        });
                    }
            </script> 

            <!-- /.box-header -->
            <div class="box-body">
            <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th style="width: 10px">#</th>
                  <th>Image</th>
                  <th>URL</th>  
                  <th>Tính trạng</th>  
                  <th>Số thứ tự</th>               
                  <th style="width: 150px">
                  <a href="<?= $ADMIN_URL?>anh/add.php" class="btn btn-xs btn-success">Thêm</a>
                  </th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($brands as $row) { ?>
                <tr>
                  <td><?php echo $row['id']; ?></td>
                  <td><img style="width:100px;" src="<?php echo SITE_URL.$row['image']; ?>" alt=""></td>
                  <td><?php echo $row['url']; ?></td>
                  <td><?php echo $row['status'] == 1 ? "Active" : "Inactive"; ?></td>
                  <td><?php echo $row['order_number']; ?></td>
                  <td>
                  <a name="" id="" class="btn btn-primary btn-xs" href="<?= $ADMIN_URL ?>anh/edit.php?id=<?= $row['id']; ?>" role="button">Sửa</a>
                  <a name="" id="" class="btn btn-danger btn-xs" href="<?= $ADMIN_URL ?>anh/remove.php?id=<?= $row['id']; ?>" role="button">Xóa</a>
                  </td>
                <?php } ?>
              </tbody></table>
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
<script>
                <?php 
                  if(isset($_GET['success']) && $_GET['success'] == true){
                ?> 
                  swal('Tạo mới ảnh thành công!');
                <?php }else if(isset($_GET['editsuccess']) && $_GET['editsuccess'] == true){ ?>
                  swal('Sửa ảnh thành công!');
                <?php } ?>
                </script>
</body>
</html>