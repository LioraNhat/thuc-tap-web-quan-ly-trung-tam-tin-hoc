<?php 
require_once '../../commons/utils.php';
if($_SERVER['REQUEST_METHOD'] != 'POST'){
	header('location: '. $ADMIN_URL .'lop');
	die;
}
$name = trim($_POST['name']);
$course = $_POST['course_id'];
// $created = $_POST['created'];
// $ended= $_POST['ended'];

if($name == ""){
	$n = "n=Nhập tên lớp học&&";
}else{
	$n = "";
}


if($course == "0"){
	$c = "c=Chọn khóa học";
}else{
	$c = "";
}


if($n !="" || $c != ""){
	header('location: '.$ADMIN_URL.'lop/add.php?'.$n.$c);
	die;
}

$sql = "select * 
		from classes 
		where name = '$name'";
$rs = getSimpleQuery($sql);
if($rs != false){
	header('location: '. $ADMIN_URL .'lop/add.php?n=Tên lớp học đã tồn tại, vui lòng chọn tên khác');
	die;
}


$sql = $conn->prepare("insert into classes (name,course_id) values (?, ?)");
$data = array($name,$course);
$sql->execute($data);

			

// Kết thúc lưu thời khóa biểu

header('location: '. $ADMIN_URL . 'lop?success=true');
die;
 ?>