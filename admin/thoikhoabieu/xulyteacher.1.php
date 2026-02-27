<?php 
    $path = "../";
    require_once $path.$path.'commons/utils.php';
    
    $date = $_POST['date'];
    $session = $_POST['session'];
    $teacher_id_old = $_POST['tea']; // ID giáo viên hiện tại của buổi học

    // Tìm tất cả các lịch học trùng ngày và ca
    $sql = "SELECT teacher_id FROM timetable WHERE day = '$date' AND session_id = '$session'";
    $query = getSimpleQuery($sql, true);

    $exclude_ids = [];
    if(is_array($query)){
        foreach($query as $row){
            // Chỉ loại bỏ những giáo viên KHÔNG PHẢI là giáo viên hiện tại của buổi học này
            if($row['teacher_id'] != $teacher_id_old){
                $exclude_ids[] = $row['teacher_id'];
            }
        }
    }

    // Xây dựng câu lệnh WHERE
    $noi = "";
    if(!empty($exclude_ids)){
        $noi = " AND id NOT IN (" . implode(',', $exclude_ids) . ")";
    }

    // Lấy danh sách giáo viên đang hoạt động và không bị trùng lịch (ngoại trừ chính họ)
    $sql1 = "SELECT * FROM teachers WHERE status = 1" . $noi;
    $query1 = getSimpleQuery($sql1, true);
    
    foreach($query1 as $row1){
        $selected = ($row1['id'] == $teacher_id_old) ? "selected" : "";
        echo "<option value='".$row1['id']."' $selected>".$row1['fullname']."</option>";
    }
?>