<?php 
    $path = "../";
    require_once $path.$path.'commons/utils.php';
    if($_SERVER['REQUEST_METHOD'] != 'POST'){
        header('location: '.$ADMIN_URL.'anh');
    }

    $url = $_POST['url'];
    $order = $_POST['order'];
    $status = $_POST['status'];

    $size = $_FILES['image']['size'];
    $image='img/'.$_FILES['image']['name'];
    $image_tmp= $_FILES['image']['tmp_name'];
    $duoianh=strtolower(end(explode('.',$_FILES['image']['name'])));

    $dang= array("jpeg","jpg","png");

    if($order == ""){
        $erO ='erO=Nhập số thứ tự&&';
    }else if(is_numeric($order)==false){
        $erO ='erO=Số thự tự phải là số&&';
    }else{
        $erO ='';
    }

    if($status == ""){
        $erS ='erS=Chọn tính trạng&&';
    }else{
        $erS ='';
    }

    if($image == "img/"){
        $erI ='erI=Chọn ảnh';
    }
    else if(in_array($duoianh,$dang)=== false){
        $erI ='erI=Chỉ hỗ trợ upload file jpeg, jpg hoặc png';
    }else{
        $erI ='';
    }

    if($erL != "" || $erO != "" || $erI !="" || $erS !=""){
        header('location: '.$ADMIN_URL.'anh/add.php?'.$erO.$erS.$erI);
        die;
    }


    $sql = "insert into slideshows values (null,'$image','','$url','$status','$order',null)";
    getSimpleQuery($sql);
    header('location: '.$ADMIN_URL.'anh?success=true');
    die;
 ?>