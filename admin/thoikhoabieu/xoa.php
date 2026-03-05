<?php 
require_once '../../commons/utils.php';

if(!isset($_GET['id'])){
    http_response_code(400);
    exit;
}

$id = intval($_GET['id']);
$sql = "SELECT id FROM timetable WHERE id = $id";
$check = getSimpleQuery($sql);

if(empty($check)){
    http_response_code(404);
    exit;
}

// Thực hiện xóa
getSimpleQuery("DELETE FROM timetable WHERE id = $id");

// Trả về text để JavaScript biết là thành công
echo "success";
exit;
?>