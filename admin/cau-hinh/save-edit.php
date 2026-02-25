<?php 
    $path = "../";
    require_once $path.$path.'commons/utils.php';
    if($_SERVER['REQUEST_METHOD'] != 'POST'){
        header('location: '.$ADMIN_URL.'cau-hinh');
    }
     
    $id = $_POST['id'];
    $hotline = $_POST['hotline'];
    $map = $_POST['map'];
    $email = $_POST['email'];
    $fb = $_POST['fb'];
    $timework = $_POST['timework'];

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

    if($hl !="" || $m != "" || $e !="" || $f="" || $t=""){
        header('location: '.$ADMIN_URL.'cau-hinh/edit.php?id='.$id.'&&'.$hl.$m.$t.$e.$f);
        die;
    }


    $logo='img/'.$_FILES['logo']['name'];
    if($logo == "img/"){
        $logo = $_POST['old_logo'];
    }else{
    $size = $_FILES['logo']['size'];
    $logo_tmp= $_FILES['logo']['tmp_name'];
    $duoianh=strtolower(end(explode('.',$_FILES['logo']['name'])));

    $dang= array("jpeg","jpg","png");
    if($logo == "img/"){
       echo $erI ='erI=Chọn ảnh.';
    }
    else if(in_array($duoianh,$dang)=== false){
      echo  $erI ='erI=Chỉ hỗ trợ upload file JPEG hoặc PNG.';
    }else if($size > 2097152) {
      echo  $erI ='erI=Kích thước file không được lớn hơn 2MB';
    } 
    if($erI != ""){
        header('location: '.$ADMIN_URL.'cau-hinh/edit.php?id='.$id.'&&'.$erI);
        die;
    }
    move_uploaded_file($image_tmp,'../../'.$image);
    }

   /* if($name=="" || $image == "" || $sell_price=="" || $quantity=="" || $erJ !="" || $erS !=""){
        $erN = 'errName=Vui lòng không để trống tên sản phẩm';
        $erP = '&&errPrice=Vui lòng nhập giá sản phẩm';
        $erQ = '&&errQty=Vui lòng nhập số lượng sản phẩm';
        header('location: '.$ADMIN_URL.'san-pham/edit.php?id='.$id.$erN.$erP.$erQ.$erJ.$erS.$erI);
        die;
    }

    $sql = "select * from products where product_name = '$name' and id <> '$id'";
    $rs = getSimpleQuery($sql);
    if($rs != false){
        header('location: '.$ADMIN_URL.'san-pham/edit.php?id='.$id.'&&errName=Tên danh mục đã tồn tại, vui lòng chọn tên khác!');
        die;
    }*/

    $sql = "update web_settings set logo = '$logo', hl = '$hotline', map = '$map', email = '$email', fb = '$fb', timework = '$timework' where id = '$id'";
    getSimpleQuery($sql);
    header('location: '.$ADMIN_URL.'cau-hinh?editsuccess=true');
    die;
 ?>