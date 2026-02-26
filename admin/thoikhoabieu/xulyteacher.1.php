<?php 
    $path = "../";
    require_once $path.$path.'commons/utils.php';
    $date = $_POST['date'];
    $lop = $_POST['lop'];
    $session = $_POST['session'];
    $roo = $_POST['tea']; // Đây là ID giáo viên hiện tại của buổi học

    $sql = "select * from timetable where day = '$date' and session_id = '$session'";
    $query = getSimpleQuery($sql,true);

    $noi = "where ";
    if(is_array($query)){
        foreach($query as $i => $row){
            // Nếu giáo viên đang xét trong lịch trùng với giáo viên hiện tại của buổi học, ta không loại bỏ họ khỏi danh sách
            if($row['teacher_id'] != $roo){
                $room[$i] = $row['teacher_id'];
                $noi .= " id <> ".$room[$i]." and "; 
            }
        }
    }

    $sql1 = "select * from teachers ".$noi." status = 1";
    $query1 = getSimpleQuery($sql1,true);
    
    foreach($query1 as $row1){
        // KIỂM TRA ĐỂ GIỮ LẠI TRẠNG THÁI SELECTED
        $selected = ($row1['id'] == $roo) ? "selected" : "";
        echo "<option value='".$row1['id']."' $selected>".$row1['fullname']."</option>";
    }
?>