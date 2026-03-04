<?php 
require_once '../../commons/utils.php';

if(!isset($_GET['id'])){
    header("Location:".$ADMIN_URL."thoikhoabieu");
    die;
}

$id = intval($_GET['id']); // bảo mật hơn

$sql = "select * from timetable where id = $id";
$cate = getSimpleQuery($sql);

if(!$cate){
    header("Location:".$ADMIN_URL."thoikhoabieu");
    die;
}

$sql = "delete from timetable where id = $id";
getSimpleQuery($sql);

header("Location:".$ADMIN_URL."thoikhoabieu?deletesuccess=true");
die;
?>