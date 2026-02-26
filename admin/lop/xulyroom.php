<?php 
    $path = "../";
    require_once $path.$path.'commons/utils.php';

    // Lấy dữ liệu từ AJAX gửi sang
    $date = isset($_POST['date']) ? $_POST['date'] : '';
    $session = isset($_POST['session']) ? $_POST['session'] : '';

    // 1. Tìm các phòng đã có lịch (timetable) vào đúng ngày và ca này
    $sql = "SELECT room_id FROM timetable WHERE day = '$date' AND session_id = '$session'";
    $query = getSimpleQuery($sql, true);

    // 2. Xây dựng điều kiện loại trừ (WHERE)
    $condition = "WHERE status = 1"; // Chỉ lấy các phòng đang hoạt động
    
    if (!empty($query)) {
        foreach($query as $row) {
            $busy_room_id = $row['room_id'];
            // Thêm điều kiện loại trừ những phòng đã bị chiếm chỗ
            $condition .= " AND id <> $busy_room_id"; 
        }
    }

    // 3. Truy vấn danh sách phòng học thỏa mãn điều kiện
    $sql_rooms = "SELECT * FROM rooms " . $condition;
    $available_rooms = getSimpleQuery($sql_rooms, true);

    // 4. Trả về kết quả cho AJAX
    echo "<option value='0'>--Chọn phòng học còn trống--</option>";
    if (!empty($available_rooms)) {
        foreach($available_rooms as $room) {
            echo "<option value='".$room['id']."'>".$room['name']."</option>";
        }
    } else {
        echo "<option value='0'>Hết phòng trống cho ca này</option>";
    }
?>