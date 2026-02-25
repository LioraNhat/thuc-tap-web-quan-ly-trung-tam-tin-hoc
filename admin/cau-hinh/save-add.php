<?php 
    $path = "../";
    require_once $path.$path.'commons/utils.php';
    if($_SERVER['REQUEST_METHOD'] != 'POST'){
        header('location: '.$ADMIN_URL.'doi-tac');
    }

    $hotline= $_POST['hotline'];
    $map = $_POST['map'];
    $email= $_POST['email'];
    $fb = $_POST['fb'];
    $timework = $_POST['timework'];

    $size = $_FILES['logo']['size'];
    $logo='img/'.$_FILES['logo']['name'];
    $logo_tmp= $_FILES['logo']['tmp_name'];
    $duoianh=strtolower(end(explode('.',$_FILES['logo']['name'])));

    $hl = $m = $e = $f = $t = "";
    if($hotline == ""){
        $hl = "HL=Nhập sdt&&";
    }else if(is_numeric($hotline)==false){
        $hl ='HL=Số điện thoại phải là dãy số 10 - 11 chữ số&&';
    }else{
        $hl = "";
    }

    if($map == ""){
        $m = "m=Nhập địa chỉ map&&";
    }else{
        $m = "";
    }

    if($timework == ""){
        $t = "t=Nhập thời gian làm việc&&";
    }else{
        $t = "";
    }

    if($email == ""){
        $e = "e=Nhập email&&";
    }else{
        $e = "";
    }
    if($fb == ""){
        $f = "f=Nhập fanpage facebook";
    }else{
        $f = "";
    }

    if($logo == "img/"){
        $erI ='erI=Chọn ảnh&&';
    }
    else if(in_array($duoianh,$dang)=== false){
        $erI ='erI=Chỉ hỗ trợ upload file jpeg, jpg hoặc png&&';
    }else{
        $erI ='';
    }

    if($hl !="" || $m != "" || $e !="" || $f="" || $t="" || $erI=""){
        header('location: '.$ADMIN_URL.'cau-hinh/add.php?'.$hl.$m.$t.$e.$erI.$f);
        die;
    }

    /*$dang= array("jpeg","jpg","png");
    if($logo == ""){
        $erI ='&&erI=Chọn ảnh.';
    }
    else if(in_array($duoianh,$dang)=== false){
        $erJ ='&&erJ=Chỉ hỗ trợ upload file JPEG hoặc PNG.';
    }else if($size > 2097152) {
        $erS ='&&erQ=Kích thước file không được lớn hơn 2MB';
    }

    if($name==""){
        header('location: '.$ADMIN_URL.'doi-tac/add.php?errName=Vui lòng không để trống tên đối tác');
        die;
    }

    $sql = "select * from brands where name = '$name'";
    $rs = getSimpleQuery($sql);
    if($rs != false){
        header('location: '.$ADMIN_URL.'doi-tac/add.php?errName=Tên đối tác đã tồn tại, vui lòng chọn tên khác!');
        die;
    } */

    $sql = "insert into web_settings values (null,'$logo','$map','$email','$fb','$hotline','$timework')";
    getSimpleQuery($sql);
    header('location: '.$ADMIN_URL.'cau-hinh?success=true');
    die;
 ?>