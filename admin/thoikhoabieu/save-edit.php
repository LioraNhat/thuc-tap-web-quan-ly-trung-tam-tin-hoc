<?php 
require_once '../../commons/utils.php';

if($_SERVER['REQUEST_METHOD'] != 'POST'){
    header('location: '. $ADMIN_URL .'thoikhoabieu');
    die;
}

$id = $_POST['id'];
$created = $_POST['created']; 
$check = isset($_POST['check']) ? $_POST['check'] : []; 

// --- BƯỚC KIỂM TRA AN TOÀN ---
$cr = (empty($created)) ? "cr=Vui lòng chọn ngày bắt đầu" : "";
$th = (empty($check)) ? "th=Chọn thứ trong tuần" : "";

if($cr != "" || $th != ""){
    header('location: '.$ADMIN_URL.'thoikhoabieu/edit.php?id='.$id.'&'.$cr.'&'.$th);
    die;
}

$solan = count($check); 
$sotiet = $_POST['soTiet'];
$room = $_POST['room_id'];
$teacher = $_POST['teacher_id'];
$session = $_POST['session_id'];
$class = $_POST['class_id'];
$course = $_POST['course_id'];

$roo_old = $_POST['roo']; // Phòng cũ
$ses_old = $_POST['ses']; // Ca cũ

// 1. Lấy thông tin loại phòng mới xem có phải Online không
$roomInfo = getSimpleQuery("SELECT type FROM rooms WHERE id = '$room'");

// 2. XÓA LỊCH CŨ CỦA LỚP NÀY (Để dọn chỗ cho lịch mới)
$sqlDelete = "DELETE FROM timetable WHERE class_id = '$class'";
getSimpleQuery($sqlDelete);

// 3. SINH LỊCH MỚI
$ngay = $created;
$sl = $sotiet;

$chuoi = explode("-", $ngay);
$year = (int)$chuoi[0]; $month = (int)$chuoi[1]; $day = (int)$chuoi[2];
$jd = cal_to_jd(CAL_GREGORIAN, $month, $day, $year);
$day1 = jddayofweek($jd, 0);

$l = 1; $n = 1;
for($i = 0; $i<$sl; $i++){
    $date = date_create($ngay);

    if($n == 1){
        date_modify($date,"+".(($check[0]-$day1) >= 0 ? ($check[0]-$day1) : 7 + ($check[0]-$day1))." days");
        $n = 2;
    }else{
        // ... (Giữ nguyên logic if/else của $solan 1-6 giống như save-add)
        if($solan == 1) { date_modify($date,"+7 days"); }
        // ... (Tương tự cho các trường hợp khác)
    }

    $name = date_format($date,"Y-m-d");

    // --- KIỂM TRA XUNG ĐỘT TRƯỚC KHI LƯU ---
    
    // A. Chặn trùng phòng Online
    if($roomInfo && $roomInfo['type'] == 1){ 
        $sqlCheckRoom = "SELECT * FROM timetable WHERE day = '$name' AND session_id = '$session' AND room_id = '$room'";
        if(getSimpleQuery($sqlCheckRoom)){
            header('location: '.$ADMIN_URL.'thoikhoabieu/edit.php?id='.$id.'&err=Phòng Online đã bận vào ngày '.$name);
            die; 
        }
    }

    // B. Chặn trùng lịch Giáo viên
    $sqlCheckTea = "SELECT * FROM timetable WHERE day = '$name' AND session_id = '$session' AND teacher_id = '$teacher'";
    if(getSimpleQuery($sqlCheckTea)){
        header('location: '.$ADMIN_URL.'thoikhoabieu/edit.php?id='.$id.'&err=Giáo viên đã có lịch dạy vào ngày '.$name);
        die;
    }

    // LƯU VÀO DATABASE
    $sqlInsert = $conn->prepare("INSERT INTO timetable VALUES ('', ?, ?, ?, ?, ?, ?)");
    $dataInsert = array($name, $course, $class, $room, $teacher, $session);
    $sqlInsert->execute($dataInsert);

    $ngay = $name; 
}

// Cập nhật lại ngày kết thúc cho bảng lớp học
$sqlLast = "SELECT day FROM timetable WHERE class_id = '$class' ORDER BY day DESC LIMIT 1";
$last = getSimpleQuery($sqlLast);
$ended = $last['day'];

$sqlUpdateClass = $conn->prepare("UPDATE classes SET ended_at = ? WHERE id = ?");
$sqlUpdateClass->execute(array($ended, $class));

header('location: '. $ADMIN_URL . 'thoikhoabieu?editsuccess=true');
die;
?>