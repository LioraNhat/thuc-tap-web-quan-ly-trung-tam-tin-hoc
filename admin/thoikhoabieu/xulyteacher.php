<?php 
$path = "../";
require_once $path.$path.'commons/utils.php';

$date = $_POST['date'];
$session = $_POST['session'];
$room = $_POST['room']; // ID phòng đang được chọn ở combobox trước đó

$teachers = getSimpleQuery("SELECT * FROM teachers WHERE status = 1", true);

// Tìm lịch của giáo viên trong ca này
$sql_busy = "SELECT teacher_id, room_id FROM timetable WHERE day = '$date' AND session_id = '$session'";
$busy_list = getSimpleQuery($sql_busy, true);
$busy_map = [];
foreach($busy_list as $b) {
    $busy_map[$b['teacher_id']] = $b['room_id'];
}

echo "<option value='0'>-- Chọn giáo viên --</option>";
foreach($teachers as $row){
    $t_id = $row['id'];
    $note = "";
    $style = "";

    if(isset($busy_map[$t_id])){
        if($busy_map[$t_id] == $room){
            $note = " (Đang dạy lớp khác tại phòng này)";
            $style = "style='color: blue;'";
        } else {
            $note = " (Đang dạy ở phòng khác!)";
            $style = "style='color: red;'";
        }
    }
    echo "<option value='$t_id' $style>".$row['fullname']."$note</option>";
}
?>