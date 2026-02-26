<?php 
require_once '../../commons/utils.php';
 if($_SERVER['REQUEST_METHOD'] != 'POST'){
	header('location: '. $ADMIN_URL .'giaovien');
	die;
}
$email = trim($_POST['email']);
$fullname = trim($_POST['fullname']);
$address = trim($_POST['address']);
$phone = trim($_POST['phone']);
$gender= trim($_POST['gender']);
$password = $_POST['password'];
$cfpassword = $_POST['cfpassword'];
$status = 1; 
$role = 1; 

$e = $n = $ph = $a = $p = $cp=  "";
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

    if($phone == ""){
        $ph = "ph=Nhập số điện thoại";
    }else if(is_numeric($phone)==false || strlen($phone) != 10){
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
	
   
    $file = $_FILES['avatar'];
    $allowed = ["jpeg","jpg","png"];
    $i = "";

    if($file['size'] > 0){

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if(!in_array($ext, $allowed)){
            $i = "i=Chỉ cho phép jpeg, jpg, png&&";
        }else{
            $filename = "img/" . uniqid() . "." . $ext;
            move_uploaded_file($file['tmp_name'], '../../' . $filename);
            $avatar = $filename;
        }

    }else{
        $avatar = "img/default.jpg";
    }

    if($e !="" || $n != "" || $ph !="" || $a !="" || $i !=""|| $p !="" || $cp !=""){
        header('location: '.$ADMIN_URL.'giaovien/add.php?'.$e.$n.$ph.$a.$i.$p.$cp);
        die;
    }

 $password = password_hash($password, PASSWORD_DEFAULT);
 $sql = "insert into teachers 
			(email, fullname, phone, address, avatar, gender, password, status, role)
		values 
			('$email', '$fullname', '$phone', '$address', '$avatar', '$gender', '$password', $status, $role)";
 getSimpleQuery($sql);
header('location: '. $ADMIN_URL . 'giaovien?success=true');
die;