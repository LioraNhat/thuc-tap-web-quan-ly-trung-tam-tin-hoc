<?php 
require_once '../../commons/utils.php';

if($_SERVER['REQUEST_METHOD'] != 'POST'){
    header('location: '. $ADMIN_URL .'khachhang');
    die;
}

// Lấy dữ liệu
$email      = trim($_POST['email']);
$fullname   = trim($_POST['fullname']);
$class_id   = (int)$_POST['class_id'];
$course_id  = (int)$_POST['course_id'];
$phone      = trim($_POST['phone']);
$created_at = date("Y-m-d");

// Lấy course từ class
$sql = "select * from classes where id = $class_id";
$class = getSimpleQuery($sql);

if(!$class){
    header('location: '.$ADMIN_URL.'khachhang/add.php?c=Lớp không tồn tại');
    die;
}

$course  = $class['course_id'];
$teacher = 0;
$diemTB  = 0;

// ===== VALIDATE =====
$e = $n = $c = $k = $ph = "";

if($email == ""){
    $e = "e=Nhập email&&";
}else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
    $e = "e=Nhập đúng dạng email&&";
}

if($fullname == ""){
    $n = "n=Nhập tên&&";
}

if($class_id == 0){
    $c = "c=Chọn lớp&&";
}

if($course_id == 0){
    $k = "k=Chọn khóa học&&";
}

if($phone == ""){
    $ph = "ph=Nhập số điện thoại";
}else if(!is_numeric($phone) || strlen($phone) != 10){
    $ph = "ph=Số điện thoại phải là số và 10 kí tự&&";
}

if($e != "" || $n != "" || $c != "" || $k != "" || $ph != ""){
    header('location: '.$ADMIN_URL.'khachhang/add.php?'.$e.$n.$k.$c.$ph);
    die;
}

// ===== KIỂM TRA EMAIL =====
$sql = "select * from student where email = '$email'";
$existUser = getSimpleQuery($sql);

if($existUser){
    $user_id = $existUser['id'];
}else{
    // Tạo tài khoản mới
    $password = password_hash('123', PASSWORD_DEFAULT);

    $sql = "insert into student 
            (email, fullname, password, phone, status)
            values 
            ('$email', '$fullname', '$password', '$phone','0')";
    getSimpleQuery($sql);

    // Lấy id vừa tạo
    $sql = "select * from student order by id desc limit 1";
    $newUser = getSimpleQuery($sql);
    $user_id = $newUser['id'];
}

// ===== KIỂM TRA ĐĂNG KÝ TRÙNG =====
$sql = "select * from dangky 
        where student_id = $user_id 
        and course_id = $course 
        and class_id = $class_id";

$checkDangKy = getSimpleQuery($sql);

if($checkDangKy){
    header('location: '.$ADMIN_URL.'khachhang/add.php?e=Học viên đã đăng ký lớp này');
    die;
}

// ===== INSERT ĐĂNG KÝ =====
$sql = "insert into dangky(student_id, course_id, class_id, created_at) 
        values ('$user_id', '$course', '$class_id','$created_at')";
getSimpleQuery($sql);

// ===== INSERT ĐIỂM =====
$sql = "insert into scores 
        (student_id, course_id, teacher_id, diemTB)
        values 
        ('$user_id', '$course', '$teacher', '$diemTB')";
getSimpleQuery($sql);

// Thành công
header('location: '. $ADMIN_URL . 'khachhang?success=true');
die;