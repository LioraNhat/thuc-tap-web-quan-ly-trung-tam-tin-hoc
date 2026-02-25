<?php  
    $path = "../";
    require_once $path.$path.'commons/utils.php';
    $o = array();
    $quiz_id = $_POST['quiz_id']; 
    $id = $_POST['id'];
    $ern = $era =  $erb = $erc = $erd = $err = "";
    if($_POST['question'] != ""){
        $question = $_POST['question'];
    }else{
        $ern = "ern=Nhập tên câu hỏi&&";
    }

    if($_POST['des'][0] != ""){
        $A = $_POST['des'][0];
    }else{
        $era = "era=Nhập đáp án A&&";
    }

    if($_POST['des'][1] != ""){
        $B = $_POST['des'][1];
    }else{
        $erb = "erb=Nhập đáp án B&&";
    }

    if($_POST['des'][2] != ""){
        $C = $_POST['des'][2];
    }else{
        $erc = "erc=Nhập đáp án C&&";
    }

    if($_POST['des'][3] != ""){
        $D = $_POST['des'][3];
    }else{
        $erd = "erd=Nhập đáp án D&&";
    }

    $cr = $_POST['isCorrect'];

    if($era != "" || $erb != "" || $erc != "" || $erd != "" || $ern != "" ){
        header("Location:".$ADMIN_URL."quiz/editquestion.php?id=".$id."&&quiz_id=".$quiz_id."&&".$ern.$era.$erb.$erc.$erd);
        die();
    }

    $isCorrect = $_POST['des'][$cr];

    $sql = $conn->prepare("update question set question = '$question', A =  '$A', B = '$B', C = '$C' , D = '$D', isCorrect = '$isCorrect' where id = '$id'");
    $sql->execute();

        header("Location:".$ADMIN_URL."quiz/question.php?id=".$quiz_id."&&edit=true");
        die();
?>