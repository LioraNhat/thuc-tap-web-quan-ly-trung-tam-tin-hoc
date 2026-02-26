<?php 
require_once '../../commons/utils.php';
if($_SERVER['REQUEST_METHOD'] != 'POST'){
	header('location: '. $ADMIN_URL .'thoikhoabieu');
	die;
}
$id = $_POST['id'];
$created = $_POST['created']; // Ngày bắt đầu
$check = isset($_POST['check']) ? $_POST['check'] : []; // Kiểm tra mảng "thứ trong tuần"

// --- BƯỚC KIỂM TRA AN TOÀN ---
$cr = (empty($created)) ? "cr=Vui lòng chọn ngày bắt đầu" : "";
$th = (empty($check)) ? "th=Chọn thứ trong tuần" : "";

if($cr != "" || $th != ""){
    // Nếu thiếu ngày hoặc thứ, đẩy về trang sửa kèm thông báo lỗi
    header('location: '.$ADMIN_URL.'thoikhoabieu/edit.php?id='.$id.'&'.$cr.'&'.$th);
    die;
}
// -----------------------------

$solan = count($check); // Đếm số thứ được chọn trực tiếp, không cần dùng vòng lặp foreach ở đây
$sotiet = $_POST['soTiet'];
$room = $_POST['room_id'];
$teacher = $_POST['teacher_id'];
$session = $_POST['session_id'];
$class = $_POST['class_id'];
$course = $_POST['course_id'];

$roo = $_POST['roo'];
$ses = $_POST['ses'];

// Xóa lịch cũ
$sql = "delete from timetable where room_id = $roo and session_id = $ses and class_id = $class";
getSimpleQuery($sql);

// Lưu thời khóa biểu mới
$ngay = $created;
$sl = $sotiet;

// Tách ngày và ép kiểu số nguyên (int) để cal_to_jd không bị Fatal error
$chuoi = explode("-", $ngay);
$year = (int)$chuoi[0];
$month = (int)$chuoi[1];
$day = (int)$chuoi[2];

$jd = cal_to_jd(CAL_GREGORIAN, $month, $day, $year);
$day1 = jddayofweek($jd, 0);
$check[0];
$l = 1;
$n = 1;
for($i = 0; $i<$sl; $i++){
    $date = date_create($ngay);

    if($n == 1){
        date_modify($date,"+".(($check[0]-$day1) >= 0 ? ($check[0]-$day1) : 7 + ($check[0]-$day1))." days");
        $n = 2;
    }else{

            if($solan == 1){
                    date_modify($date,"+7 days");
            }else if($solan == 2){
                if($l == 1){
                    date_modify($date,"+".($check[1]-$check[0])." days");
                    $l = 2;
                }else{
                    date_modify($date,"+".(7-($check[1]-$check[0]))." days");
                    $l = 1;
                }
            }else if($solan == 3){
                if($l == 1){
                    date_modify($date,"+".($check[1]-$check[0])." days");
                    $l = 2;
                }else if($l == 2){
                    date_modify($date,"+".($check[2]-$check[1])." days");
                    $l = 3;
                }else{
                    date_modify($date,"+".(7-(($check[1]-$check[0])+($check[2]-$check[1])))." days");
                    $l = 1;
                }
            }else if($solan == 4){
                if($l == 1){
                    date_modify($date,"+".($check[1]-$check[0])." days");
                    $l = 2;
                }else if($l == 2){
                    date_modify($date,"+".($check[2]-$check[1])." days");
                    $l = 3;
                }else if($l == 3){
                    date_modify($date,"+".($check[3]-$check[2])." days");
                    $l = 4;
                }else{
                    date_modify($date,"+".(7-(($check[1]-$check[0])+($check[2]-$check[1])+($check[3]-$check[2])))." days");
                    $l = 1;
                }
            }else if($solan == 5){
                if($l == 1){
                    date_modify($date,"+".($check[1]-$check[0])." days");
                    $l = 2;
                }else if($l == 2){
                    date_modify($date,"+".($check[2]-$check[1])." days");
                    $l = 3;
                }else if($l == 3){
                    date_modify($date,"+".($check[3]-$check[2])." days");
                    $l = 4;
                }else if($l == 4){
                    date_modify($date,"+".($check[4]-$check[3])." days");
                    $l = 5;
                }else{
                    date_modify($date,"+".(7-(($check[1]-$check[0])+($check[2]-$check[1])+($check[3]-$check[2])+($check[4]-$check[3])))." days");
                    $l = 1;
                }
            }else if($solan == 6){
                if($l == 1){
                    date_modify($date,"+".($check[1]-$check[0])." days");
                    $l = 2;
                }else if($l == 2){
                    date_modify($date,"+".($check[2]-$check[1])." days");
                    $l = 3;
                }else if($l == 3){
                    date_modify($date,"+".($check[3]-$check[2])." days");
                    $l = 4;
                }else if($l == 4){
                    date_modify($date,"+".($check[4]-$check[3])." days");
                    $l = 5;
                }else if($l == 5){
                    date_modify($date,"+".($check[5]-$check[4])." days");
                    $l = 6;
                }else{
                    date_modify($date,"+".(7-(($check[1]-$check[0])+($check[2]-$check[1])+($check[3]-$check[2])+($check[4]-$check[3])+($check[5]-$check[4])))." days");
                    $l = 1;
                }
            }else{
                date_modify($date,"+1 days");
            }
    }

    echo $name = date_format($date,"Y-m-d");
    $chuoi = explode("-",$name);
    $year = $chuoi[0];
    $month = $chuoi[1];
    $day = $chuoi[2];
    $jd = cal_to_jd(CAL_GREGORIAN,$month,$day,$year);
    $day1 = jddayofweek($jd,0);

    switch($day1){
        case 1:
        $thu = "thu 2";
        break;
        case 2:
        $thu = "thu 3";
        break;
        case 3:
        $thu = "thu 4";
        break;
        case 4:
        $thu = "thu 5";
        break;
        case 5:
        $thu = "thu 6";
        break;
        case 6:
        $thu = "thu 7";
        break;
        default:
        $thu = "cn";
    }
    echo $thu."<br>";
    $ngay = $name;
    
    if(isset($_POST['ended'])){
        if($ngay < $_POST['ended']){
            $sql = $conn->prepare("insert into timetable values ('', ?, ?,?,?,?,?)");
            $data = array($name,$course,$class,$room,$teacher,$session);
            $sql->execute($data);
        }
    }else{
            $sql = $conn->prepare("insert into timetable values ('', ?, ?,?,?,?,?)");
            $data = array($name,$course,$class,$room,$teacher,$session);
            $sql->execute($data);
    }
}

$sql = "select * from timetable order by id desc";
$rs = getSimpleQuery($sql);
echo $ended = $rs['day'];

$sql = $conn->prepare("update classes set ended_at = '$ended' where id = '$class'");
$data = array();
$sql->execute($data);

// Kết thúc lưu thời khóa biểu

header('location: '. $ADMIN_URL . 'thoikhoabieu?editsuccess=true');
die;
?>
 