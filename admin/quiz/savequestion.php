<?php  
    $path = "../";
    require_once $path.$path.'commons/utils.php';
    $o = array();
    $quiz_id = $_POST['id']; 
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

    if(isset($_POST['isCorrect'])){
        $cr = $_POST['isCorrect'];
    }else{
        $err = "err=Chọn đáp án đúng!";
    }

    if($era != "" || $erb != "" || $erc != "" || $erd != "" || $ern != "" || $err != "" ){
        header("Location:".$ADMIN_URL."quiz/question.php?id=".$quiz_id."&&".$ern.$era.$erb.$erc.$erd.$err);
        die();
    }
    
    $isCorrect = $_POST['des'][$cr];
    $sql = $conn->prepare("insert into question values ('', '$question', '$quiz_id','$A','$B','$C','$D','$isCorrect')");
    $sql->execute();

    header("Location:".$ADMIN_URL."quiz/question.php?id=".$quiz_id."&&success=true");
    die();
?>