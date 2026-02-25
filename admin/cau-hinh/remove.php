<?php 
     require_once '../../commons/utils.php';
     $webId = $_GET['id'];

     $sql = "select * from web_settings where id = $webId";
     $web = getSimpleQuery($sql);
     if(!$web){
         header("Location:".$ADMIN_URL."cau-hinh");
         die;
     }

     $sql = "delete from web_settings where id = $webId";
     getSimpleQuery($sql);
     
     header("Location:".$ADMIN_URL."cau-hinh");
     die;
?>