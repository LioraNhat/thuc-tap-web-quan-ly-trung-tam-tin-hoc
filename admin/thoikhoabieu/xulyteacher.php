<?php 
    $path = "../";
    require_once $path.$path.'commons/utils.php';
    
    $date = $_POST['date'];
    $session = $_POST['session'];

    // 1. Lấy danh sách các buổi học đã có vào ngày và ca này
    $sql = "select teacher_id from timetable where day = '$date' and session_id = '$session'";
    $query = getSimpleQuery($sql, true);

    $noi = "where status = 1 "; // Mặc định lấy GV đang hoạt động
    
    if (!empty($query)) {
        foreach($query as $row){
            $busy_id = $row['teacher_id'];
            // Thêm điều kiện loại trừ những giáo viên đã có lịch
            $noi .= " and id <> $busy_id "; 
        }
    }

    // 2. Truy vấn danh sách giáo viên còn trống lịch
    $sql1 = "select * from teachers " . $noi;
    $query1 = getSimpleQuery($sql1, true);
    
    echo "<option value='0'>--Chọn giáo viên trống lịch--</option>";
    if ($query1) {
        foreach($query1 as $row1){
            echo "<option value='".$row1['id']."'>".$row1['fullname']."</option>";
        }
    } else {
        echo "<option value='0'>Không có giáo viên nào trống ca này</option>";
    }
?>