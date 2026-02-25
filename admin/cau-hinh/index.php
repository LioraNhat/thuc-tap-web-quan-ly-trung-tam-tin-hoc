<?php 
$path = "../";
require_once $path.$path.'commons/utils.php';
$listSettingQuery = "select * from web_settings order by id";
    $setting = getSimpleQuery($listSettingQuery,true);
 ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>POLY | Cấu hình</title>
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
        <small>Cấu hình</small>
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
                <h3 class="box-title">Cấu hình website</h3>

            <!-- <div class="box-tools">
                <div class="input-group input-group-sm" style="width: 150px;">
                <input type="text" id="search1" name="search" class="form-control pull-right" placeholder="Search"> 
                </div>
              </div> -->
            </div>

            <?php /*<select class="form-control" style="max-width:120px" id="select">
                    <option value="0">Tất cả</option>
                    <option value="5">Hiện thị 5</option>
                    <option value="10">Hiện thị 10</option>
                    <option value="20">Hiện thị 20</option>
                    <option value="30">Hiện thị 30</option>
            </select> */?>

           <script type="text/javascript">
                // $(document).ready(function(){
                //     $('#search1').keyup(function(){
                //         var txt1 = $('#search1').val();
                //         $.ajax({
                //             url:"xuly.php",
                //             method:"post",
                //             data: 'search='+txt1,
                //             dataType:"text",
                //             success: function(kq){
                //                 $('tbody').html(kq);
                //             }
                //         }); 
                //     })
                //     <?php /*
                //     $('#select').change(function(){
                //       var val = $('#select').val();
                //       $.ajax({
                //       url:"xuly.php",
                //       method:"post",
                //       data: {val:val},
                //       dataType:"text",
                //       success: function(kq){
                //           $('tbody').html(kq);
                //       }
                //         }); 
                //     })
                // });*/?>

                // function xoa(id){
                //   swal({
                //     title: "Cảnh báo!",
                //     text: "Bạn có chắc chắn muốn xoá danh mục này ?",
                //     icon: "warning",
                //     buttons: true,
                //     dangerMode: true,
                //   })
                //   .then((willDelete) => {
                //     if (willDelete) {
                //                   $.ajax({
                //                       url:"xuly.php",
                //                       method:"post",
                //                       data: {id:id},
                //                       dataType:"text",
                //                       success: function(kq){
                //                           $('tbody').html(kq);
                //                       }
                //                 })
                //               }
                //     });
                // }
                // })
                

                // function sua(id){
                //   var name = $('#name_'+id).val();
                //  // var cate_id = $('#cate_'+id).val();
                //   //var price = $('#price_'+id).val();
                //   var url = $('#url_'+id).val();
                  
                //   var txt1 = $('#search1').val();
                //   $.ajax({
                //             url:"xuly.php",
                //             method:"post",
                //             data: {'id_edit' : id,
                //               'name': name,
                //               'url': url,
                //               'search': txt1},
                //             dataType:"text",
                //             success: function(kq){
                //                 $('tbody').html(kq);
                //             }
                //         }); 
                // }
                
            </script> 

            <!-- /.box-header -->
            <div class="box-body">
              <table class="table table-bordered">
                <tbody>
                <tr>
                  <th style="width: 10px">#</th>
                  <th>Logo</th>
                  <th>Hotline</th>
                  <th>Maps</th>     
                  <th>Email</th>
                  <th>Facebook</th>            
                  <th style="width: 150px">
                  <a href="<?= $ADMIN_URL?>cau-hinh/add.php" class="btn btn-xs btn-success">Thêm</a>
                  </th>
                </tr>
                <?php foreach($setting as $row) { ?>
                <tr>
                  <td><?php echo $row['id']; ?></td>
                  <td><img style="width:100px;" src="<?php echo SITE_URL.$row['logo']; ?>" alt=""></td>
                  <td><?php echo $row['hl']; ?></td>
                  <td><?php echo $row['map']; ?></td>
                  <td><?php echo $row['email']; ?></td>
                  <td><?php echo $row['fb']; ?></td>
                  <td>
                  <a name="" id="" class="btn btn-primary btn-xs" href="<?= $ADMIN_URL ?>cau-hinh/edit.php?id=<?= $row['id']; ?>" role="button">Sửa</a>
                  <a name="" id="" class="btn btn-danger btn-xs" href="<?= $ADMIN_URL ?>cau-hinh/remove.php?id=<?= $row['id']; ?>" role="button">Xóa</a>
                  </td>
                </tr>
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
       swal('Tạo mới cấu hình thành công!');
    <?php }else if(isset($_GET['editsuccess']) && $_GET['editsuccess'] == true){ ?>
      swal('Sửa cấu hình thành công!');
    <?php }?>
                </script>
</body>
</html>