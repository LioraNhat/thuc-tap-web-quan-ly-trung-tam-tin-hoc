<?php 
require_once '../../commons/utils.php';
 if($_SERVER['REQUEST_METHOD'] != 'POST'){
	header('location: '. $ADMIN_URL .'hocvien');
	die;
}
$email = trim($_POST['email']);
$fullname = trim($_POST['fullname']);
// $password = $_POST['password'];
// $cfpassword = $_POST['cfpassword'];
// $class_id = $_POST['class_id'];
// $course_id = $_POST['course_id'];
$address = trim($_POST['address']);
$phone = trim($_POST['phone']);
$ngaysinh = trim($_POST['ngaysinh']);
$gender= trim($_POST['gender']);

$e = $n = $a = $ph = $ns = $ge ="";
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

    // if($password == ""){
    //     $p = "p=Nhập passwword&&";
    // }else{
    //     $p = "";
	// }

	// if($cfpassword == ""){
    //     $cp = "cp=Nhập lại passwword&&";
    // }else if($cfpassword != $password){
    //     $cp = "cp=Nhập trùng passwword&&";
	// }else{
	// 	$cp = "";
    // }

    $phone = trim($_POST['phone']);

    if($phone == ""){
        $ph = "ph=Vui lòng nhập số điện thoại&&";
    }else if(!preg_match('/^0[0-9]{9}$/', $phone)){
        $ph = "ph=Số điện thoại phải gồm 10 số và bắt đầu bằng 0&&";
    }else{
        $ph = "";
    }

    if($address == ""){
        $a = "a=Nhập địa chỉ&&";
    }else{
        $a = "";
    }

    if($ngaysinh == ""){
        $ns = "ns=Vui lòng nhập ngày sinh&&";
    }else{
        $ns = "";
    }

    if($gender === ""){
        $ge = "ge=Vui lòng chọn giới tính&&";
    }else{
        $ge = "";
    }

    if($e !="" || $n != "" || $a !="" || $ph !="" || $ns !="" || $ge !=""){
        header('location: '.$ADMIN_URL.'hocvien/add.php?'.$e.$n.$a.$ph.$ns.$ge);
        die;
    }

// Kiểm tra trùng phone
$sql = "select * from student 
        where phone = '$phone'";

$existUser = getSimpleQuery($sql);

if($existUser){
    header('location: '.$ADMIN_URL.'hocvien/add.php?e=Học viên đã tồn tại (trùng SĐT)');
    die;
}
$sql = "insert into student 
			(email, fullname, phone, address, avatar, gender, date, password, status, role)
		values 
			('$email', '$fullname', '$phone', '$address', NULL, '$gender', '$ngaysinh', NULL, 1, 0)";
getSimpleQuery($sql);
header('location: '. $ADMIN_URL . 'hocvien?success=true');
die;

?>