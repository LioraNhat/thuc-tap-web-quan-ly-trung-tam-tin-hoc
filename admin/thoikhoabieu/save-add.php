<?php 
require_once '../../commons/utils.php';

if($_SERVER['REQUEST_METHOD'] != 'POST'){
    header('location: '. $ADMIN_URL .'thoikhoabieu');
    die;
}

$created = $_POST['created']; // Ngày bắt đầu
$check = isset($_POST['check']) ? $_POST['check'] : []; // Mảng thứ trong tuần (1: Thứ 2, ..., 7: CN)
$sotiet = (int)$_POST['soTiet']; // Tổng số buổi cần tạo

$room = $_POST['room_id'];
$teacher = $_POST['teacher_id'];
$session = $_POST['session_id'];
$class = $_POST['class_id'];
$course = $_POST['course_id'];

/* ================= VALIDATE ================= */
$c = ($class == "0") ? "c=Chọn lớp&&" : "";
$k = ($course == "0") ? "k=Chọn khóa học&&" : "";
$cr = ($created == "") ? "cr=Chọn ngày bắt đầu&&" : "";
$s = ($session == "0") ? "s=Chọn ca học&&" : "";
$r = ($room == "0") ? "r=Chọn phòng học&&" : "";
$t = ($teacher == "0") ? "t=Chọn giáo viên&&" : "";
$th = (count($check) == 0) ? "th=Chọn thứ trong tuần" : "";

if($c || $k || $cr || $s || $r || $t || $th){
    header('location: '.$ADMIN_URL.'thoikhoabieu/add.php?'.$c.$k.$cr.$s.$r.$t.$th);
    die;
}

// Sắp xếp mảng thứ để tính toán chính xác
sort($check);

/* ================= THUẬT TOÁN SINH LỊCH CHÍNH XÁC ================= */

$insertedCount = 0;
$currentDate = new DateTime($created);

// Lặp cho đến khi đủ số tiết (số buổi học)
while($insertedCount < $sotiet){
    
    // Lấy thứ hiện tại của ngày đang xét (1 cho Thứ 2, ..., 7 cho Chủ Nhật)
    // N trong PHP date format: 1 (Mon) -> 7 (Sun)
    $currentDayOfWeek = (int)$currentDate->format('N');

    // Kiểm tra xem thứ hiện tại có nằm trong danh sách được chọn không
    if(in_array($currentDayOfWeek, $check)){
        
        $name = $currentDate->format('Y-m-d');

        // CHỈ CHẶN: Nếu Lớp này đã có lịch vào ngày/ca này
        $sqlCheckClass = "SELECT id FROM timetable 
                  WHERE day = '$name' 
                  AND session_id = '$session' 
                  AND class_id = '$class'";
        
        if(!getSimpleQuery($sqlCheckClass)){
            // Thực hiện INSERT vào timetable
            $sqlInsert = $conn->prepare("INSERT INTO timetable (day, course_id, class_id, room_id, teacher_id, session_id) VALUES (?, ?, ?, ?, ?, ?)");
            $sqlInsert->execute([$name, $course, $class, $room, $teacher, $session]);

            // Sử dụng JOIN để lấy student_id từ bảng dangky NHƯNG kiểm tra status từ bảng student
            $sqlGetStudents = "SELECT d.student_id 
                            FROM dangky d 
                            JOIN student s ON d.student_id = s.id 
                            WHERE d.class_id = '$class' AND s.status = 1";
            
            $listStu = getSimpleQuery($sqlGetStudents, true);

            if($listStu){
                foreach($listStu as $row){
                    $st_id = $row['student_id'];
                    // Insert vào student_check (status = 0 là chưa điểm danh, num_check = -1 là mặc định)
                    $sqlCheckIn = $conn->prepare("INSERT INTO student_check (student_id, teacher_id, day, class_id, status, num_check) VALUES (?, ?, ?, ?, 0, -1)");
                    $sqlCheckIn->execute([$st_id, $teacher, $name, $class]);
                }
            }
            $insertedCount++;
            $lastDayCreated = $name;
        }
    }

    // Nhảy sang ngày tiếp theo để kiểm tra
    $currentDate->modify('+1 day');
    
    // Tránh vòng lặp vô tận nếu có lỗi (giới hạn tìm kiếm trong 2 năm)
    if($currentDate > (new DateTime($created))->modify('+2 years')) break;
}

/* ================= CẬP NHẬT THÔNG TIN LỚP HỌC ================= */

if(isset($lastDayCreated)){
    $sqlUpdateClass = $conn->prepare("UPDATE classes SET created_at = ?, ended_at = ? WHERE id = ?");
    $sqlUpdateClass->execute([$created, $lastDayCreated, $class]);
}

header('location: '. $ADMIN_URL . 'thoikhoabieu?success=true');
die;