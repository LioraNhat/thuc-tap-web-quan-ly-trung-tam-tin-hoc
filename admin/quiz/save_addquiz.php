<?php 
require_once '../../commons/utils.php';
if($_SERVER['REQUEST_METHOD'] != 'POST'){
	header('location: '. $ADMIN_URL .'quiz');
	die;
}
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
	header('location: '.$ADMIN_URL.'quiz/addquiz.php?'.$n.$c);
	die;
}

$sql = "select * 
		from quiz 
		where name = '$name' and course_id = '$course'";
$rs = getSimpleQuery($sql);
if($rs != false){
	header('location: '. $ADMIN_URL .'quiz/addquiz.php?n=Tên bài quiz đã tồn tại, vui lòng chọn tên khác');
	die;
}


$sql = $conn->prepare("insert into quiz values ('',?, ?,?)");
$data = array($name,$course,$created);
$sql->execute($data);

$sql = "select * 
		from quiz order by id desc limit 1";
$rs = getSimpleQuery($sql);
$quiz_id = $rs['id'];

$sql = "select * from courses join classes on courses.id = classes.course_id join dangky on classes.id = dangky.class_id join student on dangky.student_id = student.id WHERE status = 1 and courses.id = '$course'";
$mark = getSimpleQuery($sql,true);

foreach ($mark as $row) {
	$student_id = $row['student_id'];
	$sql = $conn->prepare("insert into student_mark values ('',?, ?,?,null)");
	$data = array($student_id,$course,$quiz_id);
	$sql->execute($data);
}

// Kết thúc lưu thời khóa biểu

header('location: '. $ADMIN_URL . 'quiz?success=true');
die;
 ?>