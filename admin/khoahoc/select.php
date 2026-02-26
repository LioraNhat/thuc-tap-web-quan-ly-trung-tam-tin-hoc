<?php  
    $path = "../";
    require_once $path.$path.'commons/utils.php';

    $output = '';  
    $course = $_POST['id'];
    $today = date("Y-m-d"); 

    // Truy vấn lấy danh sách các lớp học thuộc khóa học này
    $listRoomQuery = "SELECT * FROM classes WHERE course_id = $course";
    $cates = getSimpleQuery($listRoomQuery, true);

    $output .= '  
    <div class="table-responsive">  
        <table class="table table-bordered table-striped">
            <thead>
                <tr style="background-color: #3c8dbc; color: white;">  
                    <th width="10%">ID</th>  
                    <th width="40%">Tên lớp</th>  
                    <th width="20%" class="text-center">Sĩ số</th>  
                    <th width="30%">Trạng thái hiện tại</th>  
                </tr>
            </thead>
            <tbody>';  

    if (!empty($cates)) {
        foreach($cates as $row) {  
            $current_class_id = $row['id'];
            $ended_at = $row['ended_at'];

            // 1. Đếm số lượng học viên đã đăng ký (status = 1 là đã duyệt)
            $sqlCount = "SELECT COUNT(*) as total FROM dangky WHERE class_id = $current_class_id";
            $countRes = getSimpleQuery($sqlCount);
            $siso = isset($countRes['total']) ? $countRes['total'] : 0;

            // 2. Kiểm tra xem lớp này đã được xếp lịch dạy chưa trong bảng timetable
            $checkLich = getSimpleQuery("SELECT id FROM timetable WHERE class_id = $current_class_id LIMIT 1");

            // 3. Phân loại trạng thái (logic đồng bộ với trang index)
            if (!$checkLich) {
                $status = "<span class='label label-default'>Đang chờ lịch</span>";
            } else if ($ended_at != "0000-00-00" && $today > $ended_at) {
                $status = "<span class='label label-danger'>Đã kết thúc</span>";
            } else {
                $status = "<span class='label label-success'>Đang học</span>";
            }

            $output .= ' 
                <tr>  
                    <td>'.$row["id"].'</td>  
                    <td><strong>'.$row["name"].'</strong></td>  
                    <td class="text-center"><span class="badge bg-blue">'.$siso.' học viên</span></td>  
                    <td>'.$status.'</td>  
                </tr>';  
        }
    } else {
        $output .= '<tr><td colspan="4" class="text-center text-muted">Khóa học này hiện chưa có lớp học nào.</td></tr>';
    }

    $output .= '  
            </tbody>
        </table>  
    </div>';  
    
    echo $output;  
?>