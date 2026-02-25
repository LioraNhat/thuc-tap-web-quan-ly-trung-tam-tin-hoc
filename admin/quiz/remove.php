<?php 
     require_once '../../commons/utils.php';
     $id = $_GET['id'];

     $sql = "delete from quiz where id = $id";
     getSimpleQuery($sql);
     
     header("Location:".$ADMIN_URL."quiz?remove=true");
     die;
?>