<?php 
    $path = "../";
    require_once $path.$path.'commons/utils.php';
    if($_SERVER['REQUEST_METHOD'] != 'POST'){
        header('location: '.$ADMIN_URL.'anh');
    }

    echo $id = $_POST['id'];
    echo $url = $_POST['url'];
    echo $order = $_POST['order'];
    echo $status = $_POST['status'];

    $erI = $erS = $erO = "";

    if($order == ""){
        $erO ='erO=Nhập số thứ tự&&';
    }else if(is_numeric($order)==false){
        $erO ='erO=Số thự tự phải là số&&';
    }else{
        $erO ='';
    }

    if($status == ""){
        $erS ='erS=Chọn tính trạng';
    }else{
        $erS ='';
    }

    if($erO != ""){
        header('location: '.$ADMIN_URL.'anh/edit.php?id='.$id.'&&'.$erO.$erS);
        die;
    }


    $image='img/'.$_FILES['image']['name'];
    if($image == 'img/'){
        $image = $_POST['old'];
    }else{
        $size = $_FILES['image']['size'];
        $image_tmp= $_FILES['image']['tmp_name'];
        $duoianh=strtolower(end(explode('.',$_FILES['image']['name'])));

        $dang= array("jpeg","jpg","png");
        if($image == "img/"){
            $erI ='erI=Chọn ảnh';
        }
        else if(in_array($duoianh,$dang)=== false){
            $erI ='erI=Chỉ hỗ trợ upload file jpeg, jpg hoặc png';
        }else{
            $erI ='';
        }

        if($erI != ""){
            header('location: '.$ADMIN_URL.'anh/edit.php?id='.$id.'&&'.$erI);
            die;
        }

        move_uploaded_file($image_tmp,'../../'.$image);
    }


    $sql = "update slideshows set image='$image', url = '$url', status = '$status' ,order_number= '$order' where id = '$id'";
    getSimpleQuery($sql);
    header('location: '.$ADMIN_URL.'anh?editsuccess=true');
    die;
 ?>