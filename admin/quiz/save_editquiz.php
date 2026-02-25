<?php 
require_once '../../commons/utils.php';
if($_SERVER['REQUEST_METHOD'] != 'POST'){
	header('location: '. $ADMIN_URL .'quiz');
	die;
}
$id =$_POST['id'];
$name = trim($_POST['name']);
$course = $_POST['course_id'];
$created = date("Y/m/d");

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
	header('location: '.$ADMIN_URL.'quiz/editquiz.php?id='.$id.'&&'.$n.$c);
	die;
}

$sql = "select * 
		from quiz 
		where name = '$name' and course_id = '$course'";
$rs = getSimpleQuery($sql);
if($rs != false){
	header('location: '. $ADMIN_URL .'quiz/editquiz.php?id='.$id.'&&n=Tên bài quiz đã tồn tại, vui lòng chọn tên khác');
	die;
}

$sql = $conn->prepare("update quiz set name = '$name' , course_id = '$course' where id = $id");
$sql->execute();

// Kết thúc lưu thời khóa biểu

header('location: '. $ADMIN_URL . 'quiz?edit=true');
die;
 ?>