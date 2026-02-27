<?php 
    $path = "../";
    require_once $path.$path.'commons/utils.php';
    
    $date = $_POST['date'];
    $session = $_POST['session'];
    $room_id_old = $_POST['roo']; // ID phòng hiện tại

    $sql = "SELECT room_id FROM timetable WHERE day = '$date' AND session_id = '$session'";
    $query = getSimpleQuery($sql, true);

    $exclude_ids = [];
    if(is_array($query)){
        foreach($query as $row){
            // Chỉ loại bỏ những phòng KHÔNG PHẢI là phòng hiện tại của buổi học này
            if($row['room_id'] != $room_id_old){
                $exclude_ids[] = $row['room_id'];
            }
        }
    }

    $noi = "";
    if(!empty($exclude_ids)){
        $noi = " AND id NOT IN (" . implode(',', $exclude_ids) . ")";
    }

    $sql1 = "SELECT * FROM rooms WHERE status = 1" . $noi;
    $query1 = getSimpleQuery($sql1, true);

    foreach($query1 as $row1){
        $selected = ($row1['id'] == $room_id_old) ? "selected" : "";
        echo "<option value='".$row1['id']."' $selected>".$row1['name']."</option>";
    }
?>