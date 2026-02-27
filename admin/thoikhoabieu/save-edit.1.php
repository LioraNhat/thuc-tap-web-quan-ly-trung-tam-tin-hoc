<?php 
require_once '../../commons/utils.php';

if($_SERVER['REQUEST_METHOD'] != 'POST'){
    header('location: '. $ADMIN_URL .'thoikhoabieu');
    die;
}

$id = $_POST['id'];
$created = $_POST['created']; // Ngày sửa
$room = $_POST['room_id'];
$teacher = $_POST['teacher_id'];
$session = $_POST['session_id'];

/* ================= KIỂM TRA TRÙNG LỊCH GIÁO VIÊN ================= */
// Kiểm tra giáo viên dạy ở PHÒNG KHÁC vào Ngày/Ca này, loại trừ chính bản ghi đang sửa (id != $id)
$sqlCheckTeacher = "SELECT r.name as room_name 
                    FROM timetable t 
                    JOIN rooms r ON t.room_id = r.id
                    WHERE t.day = '$created' 
                    AND t.session_id = '$session' 
                    AND t.teacher_id = '$teacher'
                    AND t.room_id != '$room'
                    AND t.id != '$id'"; // Rất quan trọng để không tự báo lỗi với chính nó

$teacherBusy = getSimpleQuery($sqlCheckTeacher);

if($teacherBusy){
    $roomName = $teacherBusy['room_name'];
    header("location: " . $ADMIN_URL . "thoikhoabieu/edit1.php?id=$id&err=Giáo viên đã có lịch dạy tại phòng $roomName vào ngày $created");
    die;
}

/* ================= CẬP NHẬT ================= */
// Sử dụng Bind Parameter để bảo mật và tránh lỗi SQL injection
$sql = $conn->prepare("UPDATE timetable SET day = ?, room_id = ?, teacher_id = ?, session_id = ? WHERE id = ?");
$sql->execute([$created, $room, $teacher, $session, $id]);

header('location: '. $ADMIN_URL . 'thoikhoabieu?editsuccess=true');
die;
?>