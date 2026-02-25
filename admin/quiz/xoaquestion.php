<?php 
     require_once '../../commons/utils.php';
     $id = $_GET['id'];
     $quiz_id = $_GET['quiz_id'];

     $sql = "delete from options where question_id = $id";
     getSimpleQuery($sql);

     $sql = "delete from question where id = $id";
     getSimpleQuery($sql);
     
     header("Location:".$ADMIN_URL."quiz/question.php?id=".$id."&&remove=true");
     die;
?>