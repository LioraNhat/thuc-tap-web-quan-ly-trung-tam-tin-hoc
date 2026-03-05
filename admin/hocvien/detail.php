<?php
require_once '../../commons/utils.php';

if(!isset($_GET['id'])){
    die("Không tìm thấy học viên");
}

$id = (int)$_GET['id'];

$sql = "select * from student where id = $id";
$user = getSimpleQuery($sql);

if(!$user){
    die("Học viên không tồn tại");
}

$sql = "select * from student"
?>

<h2>Chi tiết học viên</h2>

<table class='table table-bordered'>
    <tbody>
        <tr>
            <th style='width: 10px'>Mã học viên</th>
            <th>Tên học viên</th>
            <th>Email</th>
            <th>Số điện thoại</th>
            <th>Địa chỉ</th>
            <th>Giới tính</th>
        </tr>
        <tr>
            <td><?= $user['id'] ?></td>
            <td><?= $user['fullname'] ?></td>
            <td><?= $user['email'] ?></td>
            <td><?= $user['phone'] ?></td>
            <td><?= $user['address'] ?></td>
            <td><?= $user['gender'] == 1 ? 'Nam' : 'Nữ' ?></td>
        </tr>
    </tbody>
</table>

<br>

<h3>Thông tin học phí</h3>
<table>
    <tr>
        <th>Khóa học</th>
        <th>Lớp học</th>
        <th>Học phí</th>
        <th>Đã đóng</th>
        <th>Nợ</th>
    </tr>

    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
</table>