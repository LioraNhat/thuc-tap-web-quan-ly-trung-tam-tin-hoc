<?php 
require_once '../../commons/utils.php';
if($_SERVER['REQUEST_METHOD'] != 'POST'){
	header('location: '. $ADMIN_URL .'khoahoc');
	die;
}
$name = trim($_POST['name']);
$des = $_POST['des'];
$hocphi = $_POST['hocphi'];
$soTiet = $_POST['soTiet'];
$tomtat = $_POST['tomtat'];

	$n = $s = $h = $t= $d = $i= "";
    if($name == ""){
        $n = "n=Không để trống tên khóa học&&";
    }else{
        $n = "";
    }

    if($soTiet == ""){
        $s = "s=Nhập số buổi học&&";
    }else if(is_numeric($soTiet) == false){
		$s = "s=Số buổi học phải là số&&";
	}
	else{
        $s = "";
	}
	
    if($hocphi == ""){
        $h = "h=Nhập học phí khóa học&&";
    }else if(is_numeric($hocphi) == false){
		$h = "h=Học phí phải là số&&";
	}
	else{
        $h = "";
    }


	$file = $_FILES['image'];
    $path = $file['name'];
    $size = $file['size'];
    $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
	$allowed = array("jpeg","jpg","png");
	
    $filename = "img/" . uniqid() . "." . $ext;

  
    
    
	if($n !="" || $s != "" || $h !="" || $t !="" || $d !="" || $i !=""){
        header('location: '.$ADMIN_URL.'khoahoc/add.php?'.$n.$s.$h.$t.$i.$d);
        die;
    }
// kiem tra xem ten co bi trong hay khong
// if($name == ""){
// 	header('location: '. $ADMIN_URL .'khoahoc/add.php?errName=Vui lòng không để trống tên khóa học');
// 	die;
// }
// Kiem tra ten co bi trung hay khong
$sql = "select * from courses where name = '$name'";
$rs = getSimpleQuery($sql);
if($rs != false){
	header('location: '. $ADMIN_URL .'khoahoc/add.php?n=Tên khóa học đã tồn tại, vui lòng chọn tên khác');
	die;
}
$sql = $conn->prepare("insert into courses (name, image, tomtat, soTiet, hocphi, des) values (?, ?,?,?,?,?)");
$data = array($name,$filename,$tomtat, $soTiet,$hocphi,$des);
$sql->execute($data);
header('location: '. $ADMIN_URL . 'khoahoc?success=true');
die;
 ?>