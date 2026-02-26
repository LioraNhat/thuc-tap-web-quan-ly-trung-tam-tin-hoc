<?php 
require_once '../../commons/utils.php';
 if($_SERVER['REQUEST_METHOD'] != 'POST'){
	header('location: '. $ADMIN_URL .'giaovien/edit.php');
	die;
}
$id = $_POST['id'];
$fullname = trim($_POST['fullname']);
$address = $_POST['address'];
$phone = $_POST['phone'];
$gender= $_POST['gender'];

	$n = $a = $ph = "";

    if($fullname == ""){
        $n = "n=Nhập tên&&";
    }else{
        $n = "";
	}
	
	if($address == ""){
        $a = "a=Nhập địa chỉ&&";
    }else{
        $a = "";
    }
    
    if($phone == ""){
        $ph = "ph=Nhập số điện thoại";
    }else if(is_numeric($phone)==false || strlen($phone) != 10){
        $ph = "ph=Số điện thoại phải là số và 10 kí tự&&";
    }else{
        $ph = "";
	}
    

    if($n != "" || $a !="" || $ph !=""){
        header('location: '.$ADMIN_URL.'giaovien/edit.php?id='.$id.'&&'.$n.$a.$ph);
        die;
    }

    // Lấy ảnh cũ
    $sqlOld = "select avatar from teachers where id = $id";
    $oldData = getSimpleQuery($sqlOld);
    $oldAvatar = $oldData['avatar'];
    $file = $_FILES['avatar'];
    $allowed = ["jpg","jpeg","png"];
    $avatar = $oldAvatar; // mặc định giữ ảnh cũ

    if($file['size'] > 0){

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if(in_array($ext, $allowed)){

            $filename = "img/" . uniqid() . "." . $ext;

            move_uploaded_file($file['tmp_name'], "../../" . $filename);

            $avatar = $filename;

            // Xóa ảnh cũ nếu không phải default
            if($oldAvatar != "img/default.jpg" && file_exists("../../".$oldAvatar)){
                unlink("../../".$oldAvatar);
            }

        }else{
            header('location: '.$ADMIN_URL.'giaovien/edit.php?id='.$id.'&errImage=Chỉ cho phép jpg, jpeg, png');
            die;
        }
    }

 $sql = "update teachers set fullname = '$fullname', address = '$address' , gender = '$gender', phone = '$phone', avatar = '$avatar' where id = '$id'";
 getSimpleQuery($sql);
header('location: '. $ADMIN_URL. 'giaovien?editsuccess=true');
die;