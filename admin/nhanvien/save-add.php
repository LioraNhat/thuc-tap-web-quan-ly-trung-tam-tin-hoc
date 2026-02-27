<?php 
require_once '../../commons/utils.php';
 if($_SERVER['REQUEST_METHOD'] != 'POST'){
	header('location: '. $ADMIN_URL .'nhanvien');
	die;
}
$email = trim($_POST['email']);
$fullname = trim($_POST['fullname']);
$address = trim($_POST['address']);
$phone_number = trim($_POST['phone_number']);
$gender= trim($_POST['gender']);
$password = $_POST['password'];
$cfpassword = $_POST['cfpassword'];
$role = $_POST['role'];

$e = $n = $ph = $a = $p = $r = $cp=  "";
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

    if($phone_number == ""){
        $ph = "ph=Nhập số điện thoại";
    }else if(is_numeric($phone_number)==false || strlen($phone_number) != 10){
        $ph = "ph=Số điện thoại phải là số và 10 kí tự&&";
    }else{
        $ph = "";
	}

    if($address == ""){
        $a = "a=Nhập địa chỉ&&";
    }else{
        $a = "";
    }

    if($password == ""){
        $p = "p=Nhập passwword&&";
    }else{
        $p = "";
	}

	if($cfpassword == ""){
        $cp = "cp=Nhập lại passwword&&";
    }else if($cfpassword != $password){
        $cp = "cp=Nhập trùng passwword&&";
	}else{
		$cp = "";
	}
	

    if($role == ""){
        $r = "r=Nhập role";
    }else{
        $r = "";
    }

    if($e !="" || $n != "" || $ph !="" || $a !="" || $p !="" || $cp !="" || $r=""){
        header('location: '.$ADMIN_URL.'nhanvien/add.php?'.$e.$n.$ph.$a.$p.$cp.$r);
        die;
    }

 $password = password_hash($password, PASSWORD_DEFAULT);
 $sql = "insert into users 
			(email, fullname, phone_number, address, gender, password, role)
		values 
			('$email', '$fullname', '$phone_number', '$address', '$gender', '$password', '$role')";
 getSimpleQuery($sql);
header('location: '. $ADMIN_URL . 'nhanvien?success=true');
die;