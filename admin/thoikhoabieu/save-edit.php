<?php 
require_once '../../commons/utils.php';

if($_SERVER['REQUEST_METHOD'] != 'POST'){
    header('location: '. $ADMIN_URL .'thoikhoabieu');
    die;
}

$id      = $_POST['id'];
$created = $_POST['created']; 
$check   = isset($_POST['check']) ? $_POST['check'] : [];

// --- KIỂM TRA AN TOÀN ---
$cr = (empty($created)) ? "cr=Vui lòng chọn ngày bắt đầu" : "";
$th = (empty($check))   ? "th=Chọn thứ trong tuần" : "";

if($cr != "" || $th != ""){
    header('location: '.$ADMIN_URL.'thoikhoabieu/edit.php?id='.$id.'&'.$cr.'&'.$th);
    die;
}

$solan   = count($check);
$sotiet  = (int)$_POST['soTiet'];
$room    = (int)$_POST['room_id'];
$teacher = (int)$_POST['teacher_id'];
$session = (int)$_POST['session_id'];
$class   = (int)$_POST['class_id'];
$course  = (int)$_POST['course_id'];

// Sắp xếp $check để đảm bảo thứ tự đúng
sort($check);

// --- TÍNH NGÀY ĐẦU TIÊN ---
$chuoi = explode("-", $created);
$year  = (int)$chuoi[0]; $month = (int)$chuoi[1]; $day = (int)$chuoi[2];
$jd    = cal_to_jd(CAL_GREGORIAN, $month, $day, $year);
$day1  = jddayofweek($jd, 0); // 0=CN,1=T2,...,6=T7

// Tìm ngày đầu tiên hợp lệ (ngày gần nhất khớp với $check[0])
$diff = $check[0] - $day1;
if($diff < 0) $diff += 7;
$startDate = date('Y-m-d', strtotime($created . " +$diff days"));

// --- XÓA LỊCH CŨ ---
getSimpleQuery("DELETE FROM timetable WHERE class_id = '$class'");

// --- SINH DANH SÁCH NGÀY HỌC ---
$danhSachNgay = [];
$currentDate  = $startDate;
$buoiHienTai  = 0; // index trong $check (0..solan-1)

for($i = 0; $i < $sotiet; $i++){
    $danhSachNgay[] = $currentDate;

    // Tính ngày kế tiếp
    $buoiHienTai++;
    if($buoiHienTai >= $solan){
        // Hết 1 tuần → quay lại thứ đầu tiên của tuần sau
        $buoiHienTai = 0;
        $ngayDauTuan = date('Y-m-d', strtotime($currentDate . ' +' . (7 - $check[$solan-1] + $check[0]) . ' days'));
        // Tính khoảng cách từ cuối tuần này đến thứ đầu tuần sau
        $jdCurrent = cal_to_jd(CAL_GREGORIAN,
            (int)date('m', strtotime($currentDate)),
            (int)date('d', strtotime($currentDate)),
            (int)date('Y', strtotime($currentDate))
        );
        $thuCurrent = jddayofweek($jdCurrent, 0);
        $diffNext   = ($check[0] - $thuCurrent + 7) % 7;
        if($diffNext == 0) $diffNext = 7;
        $currentDate = date('Y-m-d', strtotime($currentDate . " +$diffNext days"));
    } else {
        // Cùng tuần → nhảy đến thứ tiếp trong $check
        $diffNext    = $check[$buoiHienTai] - $check[$buoiHienTai - 1];
        $currentDate = date('Y-m-d', strtotime($currentDate . " +$diffNext days"));
    }
}

// --- KIỂM TRA XUNG ĐỘT VÀ LƯU ---
foreach($danhSachNgay as $name){
    // Chặn trùng lịch giáo viên
    $sqlCheckTea = "SELECT * FROM timetable 
                    WHERE day = '$name' AND session_id = '$session' AND teacher_id = '$teacher'";
    if(getSimpleQuery($sqlCheckTea)){
        // Khôi phục: không thể rollback dễ dàng nên thông báo lỗi
        header('location: '.$ADMIN_URL.'thoikhoabieu/edit.php?id='.$id.'&err='.urlencode('Giáo viên đã có lịch dạy vào ngày '.$name.', vui lòng chọn giáo viên khác'));
        die;
    }

    $sqlInsert = $conn->prepare("INSERT INTO timetable (day, course_id, class_id, room_id, teacher_id, session_id) VALUES (?, ?, ?, ?, ?, ?)");
    $sqlInsert->execute([$name, $course, $class, $room, $teacher, $session]);
}

// --- CẬP NHẬT NGÀY KẾT THÚC LỚP HỌC ---
$last   = getSimpleQuery("SELECT day FROM timetable WHERE class_id = '$class' ORDER BY day DESC LIMIT 1");
$ended  = $last ? $last['day'] : $created;
$first  = getSimpleQuery("SELECT day FROM timetable WHERE class_id = '$class' ORDER BY day ASC LIMIT 1");
$started = $first ? $first['day'] : $created;

$sqlUpdateClass = $conn->prepare("UPDATE classes SET created_at = ?, ended_at = ? WHERE id = ?");
$sqlUpdateClass->execute([$started, $ended, $class]);

header('location: '. $ADMIN_URL . 'thoikhoabieu?editsuccess=true');
die;
?>