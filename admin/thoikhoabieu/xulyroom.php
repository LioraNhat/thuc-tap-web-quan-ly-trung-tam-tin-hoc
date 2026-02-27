<?php 
$path = "../";
require_once $path.$path.'commons/utils.php';

$startDate = $_POST['date'];
$endDate = $_POST['ended'];
$session = $_POST['session'];
$daysOfWeek = isset($_POST['days']) ? $_POST['days'] : [];

if (empty($daysOfWeek) || !$startDate) {
    echo "<option value='0'>-- Vui lòng chọn ngày và thứ --</option>";
    exit;
}

// Lấy tất cả phòng đang hoạt động
$allRooms = getSimpleQuery("SELECT * FROM rooms WHERE status = 1", true);

// Tìm các phòng đã bận trong các buổi này
$sql_busy = "SELECT DISTINCT room_id FROM timetable 
             WHERE session_id = '$session' 
             AND day BETWEEN '$startDate' AND '$endDate'
             AND (DAYOFWEEK(day) - 1) IN (" . implode(',', $daysOfWeek) . ")";
$busy_rooms = array_column(getSimpleQuery($sql_busy, true), 'room_id');

echo "<option value='0'>-- Chọn phòng học --</option>";
foreach($allRooms as $row){
    $note = in_array($row['id'], $busy_rooms) ? " (Đang có lớp)" : "";
    echo "<option value='".$row['id']."'>".$row['name'] . $note . "</option>";
}
?>