<?php 
     require_once '../../commons/utils.php';
     $id = $_GET['id'];

     $sql = "delete from slideshows where id = $id";
     getSimpleQuery($sql);
     
     header("Location:".$ADMIN_URL."anh");
     die;
?>