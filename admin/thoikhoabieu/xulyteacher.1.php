<?php 
$path = "../";
require_once $path.$path.'commons/utils.php';

$date = $_POST['date'];
$session = $_POST['session'];
$roo = $_POST['roo'];
$teacher_id_old = $_POST['tea'];

$sql = "SELECT teacher_id FROM timetable 
        WHERE day = '$date'
        AND session_id = '$session'
        AND room_id != '$roo'";

$query = getSimpleQuery($sql, true);

$exclude_ids = [];

if(is_array($query)){
    foreach($query as $row){
        if($row['teacher_id'] != $teacher_id_old){
            $exclude_ids[] = $row['teacher_id'];
        }
    }
}

$exclude_ids = array_unique($exclude_ids);

$noi = "";
if(!empty($exclude_ids)){
    $noi = " AND id NOT IN (" . implode(',', $exclude_ids) . ")";
}

$sql1 = "SELECT * FROM teachers WHERE status = 1 $noi";
$query1 = getSimpleQuery($sql1, true);

foreach($query1 as $row1){
    $selected = ($row1['id'] == $teacher_id_old) ? "selected" : "";
    echo "<option value='".$row1['id']."' $selected>".$row1['fullname']."</option>";
}
?>