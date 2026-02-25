<?php 
$path = "../";
require_once $path.$path.'commons/utils.php';

$record_per_page = 10;  
$page = isset($_POST["page"]) ? $_POST["page"] : 1;  
$start_from = ($page - 1) * $record_per_page;  

// 1. Tối ưu câu lệnh SQL bằng JOIN để lấy sạch tên từ các bảng liên quan
// Chúng ta sẽ lấy SQL gốc từ POST và thay thế phần SELECT để JOIN dữ liệu
$original_sql = $_POST['sql'];

// Tách lấy phần điều kiện WHERE của câu lệnh gốc
$where_clause = "";
if (stripos($original_sql, "where") !== false) {
    $where_clause = substr($original_sql, stripos($original_sql, "where"));
}

// Xây dựng câu lệnh SQL mới cực mạnh
$new_sql = "SELECT t.*, 
                   c.name as course_name, 
                   cl.name as class_name, 
                   r.name as room_name, 
                   tea.fullname as teacher_name, 
                   s.name as session_name, s.time as session_time
            FROM timetable t
            LEFT JOIN courses c ON t.course_id = c.id
            LEFT JOIN classes cl ON t.class_id = cl.id
            LEFT JOIN rooms r ON t.room_id = r.id
            LEFT JOIN teachers tea ON t.teacher_id = tea.id
            LEFT JOIN session s ON t.session_id = s.id
            $where_clause
            LIMIT $start_from, $record_per_page";

$users = getSimpleQuery($new_sql, true);

$output = '  
<table class="table table-bordered">
<thead>
    <tr>
        <th>Ngày</th>
        <th>Khóa học</th>
        <th>Lớp học</th>
        <th>Phòng học</th>
        <th>Giáo viên</th>
        <th>Ca học</th>';

if($_SESSION['login']['role'] == 500 || $_SESSION['login']['role'] == 1){
    $output .= '<th style="width: 190px">Điểm danh & Nhập điểm</th>';
}

$output .= '<th style="width: 110px">Chức năng</th></tr></thead><tbody>';

if (is_array($users) && count($users) > 0) {
    foreach($users as $u){
        $output .= '<tr>  
            <td>'.tinhthu($u['day']).", ".$u['day'].'</td>  
            <td>'.($u['course_name'] ?? '<span class="text-danger">N/A</span>').'</td>  
            <td>'.($u['class_name'] ?? '<span class="text-danger">N/A</span>').'</td>
            <td>'.($u['room_name'] ?? '<span class="text-danger">N/A</span>').'</td>
            <td>'.($u['teacher_name'] ?? '<span class="text-danger">N/A</span>').'</td>
            <td>'.($u['session_name'] ? $u['session_name'].' ('.$u['session_time'].')' : 'N/A').'</td>';

        if($_SESSION['login']['role'] == 500 || $_SESSION['login']['role'] == 1){
            $output .= '<td>';
            if($_SESSION['login']['role'] == 500){
                $output .= '<a href="'.$ADMIN_URL.'lop/check.php?class_id='.$u['class_id'].'&&day='.$u['day'].'" class="btn btn-xs btn-link"><i class="fa fa-check-square-o"></i> Điểm danh</a>
                            <a href="'.$ADMIN_URL.'lop/mark.php?id='.$u['class_id'].'" class="btn btn-xs btn-link"><i class="fa fa-pencil-square-o"></i> Nhập điểm</a>';
            } else {
                $output .= '<span class="text-muted">Chỉ xem</span>';
            }
            $output .= '</td>';
        }

        $output .= '<td>
            <a href="'.$ADMIN_URL.'thoikhoabieu/edit1.php?id='.$u['id'].'" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Sửa</a>
            <a href="javascript:void(0);" linkurl="'.$ADMIN_URL.'thoikhoabieu/xoa.php?id='.$u['id'].'" class="btn btn-xs btn-danger btn-remove"><i class="fa fa-trash"></i> Xóa</a>
        </td></tr>';  
    }
} else {
    $output .= '<tr><td colspan="7" class="text-center">Không tìm thấy lịch học nào.</td></tr>';
}

$output .= '</tbody></table>';

// Tính toán phân trang dựa trên SQL gốc (không LIMIT)
$total_sql = "SELECT count(*) as total FROM timetable t $where_clause";
$total_result = getSimpleQuery($total_sql);
$total_records = $total_result['total'];
$total_pages = ceil($total_records / $record_per_page);  

echo $output;  

// Phần hiển thị nút phân trang
echo '<div class="text-center" style="margin-top: 10px;">';
if($page > 1){
    echo "<span class='pagination_link' style='cursor:pointer; margin:2px; padding:6px 12px; border:1px solid #ccc; display:inline-block;' id='".($page-1)."'>Previous</span>";  
}
for($i=1; $i<=$total_pages; $i++){  
    $active_style = ($i == $page) ? "color:white; background:#3c8dbc; border:1px solid #3c8dbc;" : "border:1px solid #ccc;";
    echo "<span class='pagination_link' style='cursor:pointer; margin:2px; padding:6px 12px; display:inline-block; $active_style' id='".$i."'>".$i."</span>";  
}  
if($page < $total_pages){
    echo "<span class='pagination_link' style='cursor:pointer; margin:2px; padding:6px 12px; border:1px solid #ccc; display:inline-block;' id='".($page+1)."'>Next</span>";  
}
echo '</div>'; 

function tinhthu($ngay){
    $chuoi = explode("-",$ngay);
    $jd = cal_to_jd(CAL_GREGORIAN, (int)$chuoi[1], (int)$chuoi[2], (int)$chuoi[0]);
    $day = jddayofweek($jd,0);
    $thu = ["Chủ nhật", "Thứ hai", "Thứ ba", "Thứ tư", "Thứ năm", "Thứ sáu", "Thứ bảy"];
    return $thu[$day];
}
?>