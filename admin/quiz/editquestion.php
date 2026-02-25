<?php 
    $path = "../";
    require_once $path.$path.'commons/utils.php';
    $id = $_GET['quiz_id'];
    $listRoomQuery = "select * from question where quiz_id = $id";
    $cates = getSimpleQuery($listRoomQuery,true);

    $sl = 0;
    $isCorrect;

    $sid = $_GET['id'];
    $listQueQuery = "select * from question where id = $sid";
    $que = getSimpleQuery($listQueQuery);
       
    $isCorrect = $que['isCorrect'];
    $sl = 1;
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
        <li class="active">Danh sách câu hỏi</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      
            <div class="row">
                <div class="col-xs-6">
                <div class="box">
                <div class="box-header">
              <h3 class="box-title">Danh sách câu hỏi</h3>
                  </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table class="table table-bordered">
                <tbody id="oday">
                <tr>
                  <th style="width: 10px">#</th>
                  <th>Tên câu hỏi</th>
                  <th style="width: 120px">
                  Chức năng
                  </th>
                </tr>
                <?php foreach($cates as $key => $row) { ?>
                <tr>
                  <td><?php echo $key +1 ?></td>
                  <td><?php echo $row['question']; ?></td>
                  <td>
                  <a href="<?= $ADMIN_URL?>quiz/editquestion.php?id=<?= $row['id']?>&&quiz_id=<?php echo $_GET['quiz_id']; ?>"
                      class="btn btn-xs btn-primary btn-remove"
                      >
                      <i class="fa fa-pencil-square-o"></i> Sửa</button>
                      </a>
                      <a href="javascript:;"
                        linkurl="<?= $ADMIN_URL?>quiz/xoaquestion.php?id=<?= $row['id']?>&&quiz_id=<?php echo $_GET['id']; ?>"
                      class="btn btn-xs btn-danger btn-remove"
                      >
                      <i class="fa fa-trash-o"></i> Xóa
                      </a>
                 </td>
                </tr>
                <?php } ?>
              </tbody>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
                </div>
                <div class="col-xs-6">
                <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Sửa câu hỏi</h3>
                  </div>
            <!-- /.box-header -->
                    <div class="box-body">
                    <form action="editsavequestion.php" method="post">
                        <div class="form-group">
                          <label for="">Tên câu hỏi</label>
                          <input type="text" name="question" id="" class="form-control" value="<?php echo $que['question']; ?>">
                          <span class="text-danger"><?php if(isset($_GET['ern'])){echo $_GET['ern'];} ?></span>
                        </div>
                        <label for="">Đáp án và chọn đáp án đúng</label>
                        <div class="form-group row">
                            <div class="col-sm-1">
                                <label for="">A</label>
                            </div>
                            <div class="col-sm-1">
                                <input <?php 
                                if(isset($isCorrect))
                                echo $isCorrect == $que['A'] ? "checked" : ""  ?> type="radio" name="isCorrect" value="0" style="float:left">
                            </div>
                            <div class="col-sm-10">
                                <input type="text" name="des[]" id="" class="form-control" value="<?php echo $que['A']; ?>">
                            </div>
                        </div>
                            <span class="text-danger"><?php if(isset($_GET['era'])){echo $_GET['era'];} ?></span>
                        <div class="form-group row">
                            <div class="col-sm-1">
                                <label for="">B</label>
                            </div>
                            <div class="col-sm-1">
                                <input <?php if(isset($isCorrect))
                                echo $isCorrect == $que['B'] ? "checked" : ""  ?> name="isCorrect" type="radio" value="1" style="float:left">
                            </div>
                            <div class="col-sm-10">
                                <input type="text" name="des[]" id="" class="form-control" value="<?php echo $que['B']; ?>">
                            </div>
                        </div>
                        <span class="text-danger"><?php if(isset($_GET['erb'])){echo $_GET['erb'];} ?></span>
                        <div class="form-group row">
                            <div class="col-sm-1">
                                <label for="">C</label>
                            </div>
                            <div class="col-sm-1">
                                <input <?php if(isset($isCorrect))
                                 echo $isCorrect == $que['C'] ? "checked" : ""  ?>  name="isCorrect" type="radio" value="2" style="float:left">
                            </div>
                            <div class="col-sm-10">
                                <input type="text" name="des[]" id="" class="form-control" value="<?php echo $que['C']; ?>">
                            </div>
                        </div>
                        <span class="text-danger"><?php if(isset($_GET['erc'])){echo $_GET['erc'];} ?></span>
                        <div class="form-group row">
                            <div class="col-sm-1">
                                <label for="">D</label>
                            </div>
                            <div class="col-sm-1">
                                <input <?php if(isset($isCorrect))
                                  echo $isCorrect == $que['D'] ? "checked" : ""  ?>  name="isCorrect" type="radio" value="3" style="float:left">
                            </div>
                            <div class="col-sm-10">
                                <input type="text" name="des[]" id="" class="form-control" value="<?php echo $que['D']; ?>">
                            </div>
                        </div>
                        <span class="text-danger"><?php if(isset($_GET['erd'])){echo $_GET['erd']."</br>";} ?></span>  
                        <span class="text-danger"><?php if(isset($_GET['err'])){echo $_GET['err'];} ?></span>
                        <div class="form-group">
                        <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
                        <input type="hidden" name="quiz_id" value="<?php echo $_GET['quiz_id']; ?>">
                            <input name="crud" id="" class="btn btn-primary" type="submit" value="Sửa">
                        </div>
                    </form>
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
<script type="text/javascript">
    <?php 
      if(isset($_GET['success']) && $_GET['success'] == true){
    ?> 
       swal('Tạo mới câu hỏi thành công!');
    <?php }else if(isset($_GET['editsuccess']) && $_GET['editsuccess'] == true){ ?>
      swal('Sửa câu hỏi thành công!');
    <?php }else if(isset($_GET['remove']) && $_GET['remove'] == true){?>
        swal('Xóa câu hỏi thành công!');
    <?php } ?>
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