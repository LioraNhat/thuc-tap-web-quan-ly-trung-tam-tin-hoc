<?php 
require_once '../../commons/utils.php';

if($_SERVER['REQUEST_METHOD'] != 'POST'){
    header('location: '. $ADMIN_URL .'thoikhoabieu');
    die;
}

$id = $_POST['id'];
$created = $_POST['created']; 
$room = $_POST['room_id'];
$teacher = $_POST['teacher_id'];
$session = $_POST['session_id'];
$class_id = $_POST['class_id'];

/* ================= RÀNG BUỘC DUY NHẤT ================= */
// Kiểm tra giáo viên có đang dạy ở PHÒNG KHÁC vào Ngày/Ca này hay không.
// Cho phép: Cùng phòng đó có nhiều giáo viên, nhiều lớp, nhiều ca.
$sqlCheckTeacher = "SELECT r.name as room_name 
                    FROM timetable t 
                    JOIN rooms r ON t.room_id = r.id
                    WHERE t.day = '$created' 
                    AND t.session_id = '$session' 
                    AND t.teacher_id = '$teacher'
                    AND t.room_id != '$room'
                    AND t.id != '$id'"; 

$teacherBusy = getSimpleQuery($sqlCheckTeacher);

if($teacherBusy){
    $roomName = $teacherBusy['room_name'];
    header("location: " . $ADMIN_URL . "thoikhoabieu/edit1.php?id=$id&err=Giáo viên này đang bận dạy tại phòng $roomName vào ca này. Không thể dạy ở 2 phòng khác nhau cùng lúc.");
    die;
}

/* ================= CẬP NHẬT ================= */
$sql = "UPDATE timetable SET 
            day = '$created', 
            room_id = '$room', 
            teacher_id = '$teacher', 
            session_id = '$session' 
        WHERE id = $id";

getSimpleQuery($sql);

header('location: '. $ADMIN_URL . 'thoikhoabieu?editsuccess=true');
die;
?>