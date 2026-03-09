<?php 
$path = "../";
require_once $path.$path.'commons/utils.php';

if(!isset($_GET['id'])){
    die("Không tìm thấy học viên");
}

$id = (int)$_GET['id'];

/* Lấy thông tin học viên */
$sql = "SELECT * FROM student WHERE id = $id";
$user = getSimpleQuery($sql);

if(!$user){
    die("Học viên không tồn tại");
}

/* Lấy thông tin học phí */
$sql = "SELECT 
            c.name AS course_name,
            cl.name AS class_name,
            dk.total_amount,
            c.hocphi,
            IFNULL(SUM(r.amount),0) AS paid
        FROM dangky dk
        JOIN courses c ON dk.course_id = c.id
        JOIN classes cl ON dk.class_id = cl.id
        LEFT JOIN receipts r ON dk.id = r.dangky_id
        WHERE dk.student_id = $id
        GROUP BY dk.id";

$hocphi = getSimpleQuery($sql, true);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>POLY | Chi tiết học viên</title>

<?php include_once $path.'_share/style_assets.php'; ?>

</head>

<body class="hold-transition skin-blue sidebar-mini">

<div class="wrapper">

<?php include_once $path.'_share/header.php'; ?>
<?php include_once $path.'_share/sidebar.php'; ?>

<div class="content-wrapper">

<section class="content-header">
<h1>
Chi tiết học viên
<small>Thông tin học viên</small>
</h1>

<ol class="breadcrumb">
<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
<li><a href="index.php">Học viên</a></li>
<li class="active">Chi tiết</li>
</ol>
</section>


<section class="content">

<div class="row">
<div class="col-md-12">

<!-- BOX THÔNG TIN HỌC VIÊN -->

<div class="box box-primary">

<div class="box-header with-border">
<h3 class="box-title">Thông tin học viên</h3>
</div>

<div class="box-body">

<table class="table table-bordered">

<tr>
<th>Mã học viên</th>
<td><?= $user['id'] ?></td>
</tr>

<tr>
<th>Tên học viên</th>
<td><?= $user['fullname'] ?></td>
</tr>

<tr>
<th>Email</th>
<td><?= $user['email'] ?></td>
</tr>

<tr>
<th>Số điện thoại</th>
<td><?= $user['phone'] ?></td>
</tr>

<tr>
<th>Địa chỉ</th>
<td><?= $user['address'] ?></td>
</tr>

<tr>
<th>Giới tính</th>
<td>
<?php
$gender = $user['gender'] ?? null;

if($gender == 1){
    echo "Nam";
}elseif($gender == 0){
    echo "Nữ";
}else{
    echo "Chưa cập nhật";
}
?>
</td>
</tr>

</table>

</div>
</div>


<!-- BOX HỌC PHÍ -->

<div class="box box-success">

<div class="box-header with-border">
<h3 class="box-title">Thông tin học phí</h3>
</div>

<div class="box-body">

<table class="table table-bordered table-striped">

<tr>
<th>Khóa học</th>
<th>Lớp học</th>
<th>Học phí</th>
<th>Đã đóng</th>
<th>Còn nợ</th>
</tr>

<?php foreach($hocphi as $hp): 
$no = $hp['hocphi'] - $hp['paid'];
?>

<tr>

<td><?= $hp['course_name'] ?></td>

<td><?= $hp['class_name'] ?></td>

<td><?= number_format($hp['hocphi']) ?> VNĐ</td>

<td><?= number_format($hp['paid']) ?> VNĐ</td>

<td style="color:red;font-weight:bold">
<?= number_format($no) ?> VNĐ
</td>

</tr>

<?php endforeach; ?>

</table>

</div>
</div>


<a href="index.php" class="btn btn-primary">
<i class="fa fa-arrow-left"></i> Quay lại
</a>


</div>
</div>

</section>

</div>

<?php include_once $path.'_share/footer.php'; ?>

</div>

<?php include_once $path.'_share/script_assets.php'; ?>

</body>
</html>