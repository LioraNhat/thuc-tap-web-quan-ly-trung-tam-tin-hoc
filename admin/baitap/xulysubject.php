<?php 
    // $path = "../";
    // require_once $path.$path.'commons/utils.php';
    // $course = $_POST['course'];

    // $sql1 = "select * from classes where course_id = $course";
    // $query1 = getSimpleQuery($sql1,true);
    
    // echo "<option value='0'>--Chọn lớp học--</option>";
    // foreach($query1 as $row1){
    //     echo "<option value='".$row1['id']."'>".$row1['name']."</option>";
    // }

    // Code mới
    $path = "../";
    require_once $path.$path.'commons/utils.php';

    // Ép kiểu về số nguyên để bảo mật và tránh lỗi cú pháp
    $course = isset($_POST['course']) ? intval($_POST['course']) : 0;

    echo "<option value='0'>--Chọn lớp học--</option>";

    if($course > 0){
        // Truy vấn lấy danh sách lớp thuộc khóa học đó
        $sql1 = "select * from classes where course_id = $course";
        $query1 = getSimpleQuery($sql1, true);
        
        // Kiểm tra xem có dữ liệu trả về không trước khi foreach
        if(is_array($query1) || is_object($query1)){
            foreach($query1 as $row1){
                echo "<option value='".$row1['id']."'>".$row1['name']."</option>";
            }
        }
    }
    //Code mới
?>