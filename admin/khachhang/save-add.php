<?php 
require_once '../../commons/utils.php';
 if($_SERVER['REQUEST_METHOD'] != 'POST'){
	header('location: '. $ADMIN_URL .'khachhang');
	die;
}
$email = trim($_POST['email']);
$fullname = trim($_POST['fullname']);
$class_id = $_POST['class_id'];
$course_id = $_POST['course_id'];
$phone = $_POST['phone'];
$created_at = date("Y/m/d");
$sql = "select *
        from classes where id = $class_id";
$users = getSimpleQuery($sql);
$course = $users['course_id'];
$teacher = 0;
$diemTB = 0;

$e = $n = $c = $ph = "";
    if($email == ""){
        $e = "e=Nhập email&&";
    }else if(filter_var($email, FILTER_VALIDATE_EMAIL) == false ){
        $e = "e=Nhập đúng dạng email&&";
    }else{
        $e = "";
    }

    if($fullname == ""){
        $n = "n=Nhập tên&&";
    }else{
        $n = "";
    }

    if($class_id == "0"){
        $c = "c=Chọn lớp&&";
    }else{
        $c = "";
    }

    if($course_id == "0"){
        $k = "k=Chọn khóa học&&";
    }else{
        $k = "";
    }
    
    if($phone == ""){
        $ph = "ph=Nhập số điện thoại";
    }else if(is_numeric($phone)==false || strlen($phone) != 10){
        $ph = "ph=Số điện thoại phải là số và 10 kí tự&&";
    }else{
        $ph = "";
	}
    

	

    if($e !="" || $n != "" || $c !="" || $k !="" || $ph !=""){
        header('location: '.$ADMIN_URL.'khachhang/add.php?'.$e.$n.$k.$c.$ph);
        die;
    }

 $password = password_hash('123', PASSWORD_DEFAULT);

 $sql = "insert into student 
			(email, fullname, password, phone, status)
		values 
			('$email', '$fullname', '$password', '$phone','0')";
 getSimpleQuery($sql);
 
$sql = "select *
        from student order by id desc limit 1";
$users = getSimpleQuery($sql);
$user = $users['id'];

$sql = "insert into dangky(student_id, course_id, class_id, created_at) values ('$user', '$course', '$class_id','$created_at')";
 getSimpleQuery($sql);

// $sql = "insert into orders 
// 			(student_id, course_id)
// 		values 
// 			('$user', '$course')";
//  getSimpleQuery($sql);

 $sql = "insert into scores 
			(student_id, course_id, teacher_id, diemTB)
		values 
			('$user', '$course', '$teacher', '$diemTB')";
 getSimpleQuery($sql);

header('location: '. $ADMIN_URL . 'khachhang?success=true');
die;