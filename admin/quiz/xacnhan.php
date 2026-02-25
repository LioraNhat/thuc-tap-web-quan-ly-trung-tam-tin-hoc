<?php  
    $path = "../";
    require_once $path.$path.'commons/utils.php';
    $id = $_GET['id'];
    $listRoomQuery = "select * from question where quiz_id = $id";
    $cates = getSimpleQuery($listRoomQuery,true);

    $i = 0;
    $student_id = $_SESSION['login']['id'];
    foreach($cates as $row){
        $ques = $row['id'];
        $listAQuery = "select * from answers where question_id = $ques and student_id = $student_id";
        $an = getSimpleQuery($listAQuery);
        // foreach($an as $row1){
            if($row['isCorrect']== $an['answer']){
                $i +=1;
            }
        // }
    }
    $mark = round(($i/count($cates)*10),1);

    $sql = $conn->prepare("update student_mark set point = '$mark' where student_id = '$student_id' and quiz_id = $id");
    $sql->execute();
    header("Location:".$ADMIN_URL."quiz");
?>