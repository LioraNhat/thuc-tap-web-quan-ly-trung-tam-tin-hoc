<?php 
    $path = "../";
    require_once $path.$path.'commons/utils.php';
    $quiz_id = $_GET['id'];
    $listRoomQuery = "select * from question where quiz_id = $quiz_id";
    $cates = getSimpleQuery($listRoomQuery,true);
    $student_id = $_SESSION['login']['id'];

    if(!isset($_GET['ques'])){
        $cate = getSimpleQuery($listRoomQuery);
        $ques = $cate['id'];

        $listAnQuery = "select * from answers where question_id = $ques and student_id = $student_id";
        $an = getSimpleQuery($listAnQuery);
    }else{
        $ques = $_GET['ques'];
        $listRoomQuery = "select * from question where id = $ques";
        $cate = getSimpleQuery($listRoomQuery);

        $listAnQuery = "select * from answers where question_id = $ques and student_id = $student_id";
        $an = getSimpleQuery($listAnQuery);
    }
    $url;
    $sl;
    $link = array();
    foreach($cates as $key => $row){ 
        $link[$key] = $row['id'];
        if(isset($_GET['ques'])){
            $ques_id = $_GET['ques'];
        }else{
            $ques_id = $ques;
        }
        if($link[$key] == $ques_id){
           $url = $key;
        }
        $sl = $key;
    }

    if(isset($_POST['next'])){   
        $cr = $_POST['isCorrect'];
        $listSQuery = "select * from answers where question_id = $ques and student_id = $student_id";
        $stu = getSimpleQuery($listSQuery);
        if($stu){
            $sql = $conn->prepare("update answers set answer = '$cr' where student_id = '$student_id' and question_id = '$ques'");
            $sql->execute();
        }else{
            $sql = $conn->prepare("insert into answers values ('',?, ?,?)");
            $data = array($ques,$student_id,$cr);
            $sql->execute($data);
        }
        if($url != $sl){
            header("Location:".$ADMIN_URL."quiz/lamquiz.php?id=".$_GET['id']."&&ques=".($link[$url+1]));
        }
    }

    if(isset($_POST['back'])){
        $cr = $_POST['isCorrect'];
        $listSQuery = "select * from answers where question_id = $ques and student_id = $student_id";
        $stu = getSimpleQuery($listSQuery);
        if($stu){
            $sql = $conn->prepare("update answers set answer = '$cr' where student_id = '$student_id' and question_id = '$ques'");
            $sql->execute();
        }else{
            $sql = $conn->prepare("insert into answers values ('',?, ?,?)");
            $data = array($ques,$student_id,$cr);
            $sql->execute($data);
        }
        if($url != 0){
            header("Location:".$ADMIN_URL."quiz/lamquiz.php?id=".$_GET['id']."&&ques=".($link[$url-1]));
        }
    }

    if(isset($_POST['finish'])){
        $cr = $_POST['isCorrect'];
        $listSQuery = "select * from answers where question_id = $ques and student_id = $student_id";
        $stu = getSimpleQuery($listSQuery);
        if($stu){
            $sql = $conn->prepare("update answers set answer = '$cr' where student_id = '$student_id' and question_id = '$ques'");
            $sql->execute();
        }else{
            $sql = $conn->prepare("insert into answers values ('',?, ?,?)");
            $data = array($ques,$student_id,$cr);
            $sql->execute($data);
        }
        header("Location:".$ADMIN_URL."quiz/xacnhan.php?id=".$_GET['id']);
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
        <li class="active">Danh sách bài quiz</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
            <div class="row">
                <div class="col-xs-4">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">Danh sách bài quiz</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <?php 
                            foreach($cates as $key => $row){ $id = $row['id'];  ?>
                                <h5><a <?php 
                                $listAQuery = "select * from answers where question_id = $id and student_id = $student_id";
                                $an1 = getSimpleQuery($listAQuery);
                                if(!$an1){
                                    echo "class='text-muted'";
                                }
                                ?>  href="<?php echo $ADMIN_URL."quiz/lamquiz.php?id=".$quiz_id."&&ques=".$row['id']; ?>">Câu hỏi số <?= $key+1 ?></a></h5>
                            <?php }  ?>
                        </div>
                        <!-- /.box-body -->
                    </div>
                </div>
                <div class="col-xs-8">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">Câu hỏi số <?= $url+1 ?></h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                        <form action="" method="post">
                            <h4><?php echo $cate['question']; ?></h4>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input <?php if($an['answer'] == $cate['A']){
                                            echo "checked";
                                        }else{ 
                                            echo "" ;
                                        } ?> type="radio" name="isCorrect" value="<?php echo $cate['A']; ?>" style="float:left">
                                    <p><?php echo "A. ".$cate['A']; ?></p>
                                </div>
                                <div class="form-group">
                                    <input <?php if($an['answer'] == $cate['C']){
                                            echo "checked";
                                        }else{ 
                                            echo "" ;
                                        } ?> type="radio" name="isCorrect" value="<?php echo $cate['C']; ?>" style="float:left">
                                    <p><?php echo "C. ".$cate['C']; ?></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input <?php if($an['answer'] == $cate['B']){
                                            echo "checked";
                                        }else{ 
                                            echo "" ;
                                        }?> type="radio" name="isCorrect" value="<?php echo $cate['B']; ?>" style="float:left">
                                    <p><?php echo "B. ".$cate['B']; ?></p>
                                </div>
                                <div class="form-group">
                                    <input <?php if($an['answer'] == $cate['D']){
                                            echo "checked";
                                        }else{ 
                                            echo "" ;
                                        }?> type="radio" name="isCorrect" value="<?php echo $cate['D']; ?>" style="float:left">
                                    <p><?php echo "D. ".$cate['D']; ?></p>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <button type="submit" name="back" id="" class="btn btn-primary"><<</button>
                                <button type="submit" name="next" id="" class="btn btn-primary">>></button>
                                <button type="submit" name="finish" id="" class="btn btn-primary">kết thúc kiểm tra</button>
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
       swal('Tạo mới bài quiz thành công!');
    <?php }else if(isset($_GET['edit']) && $_GET['edit'] == true){ ?>
      swal('Sửa bài quiz thành công!');
    <?php }else if(isset($_GET['remove']) && $_GET['remove'] == true){ ?>
      swal('Xóa bài quiz thành công!');
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