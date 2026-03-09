<?php 
$path = "../";
require_once $path.$path.'commons/utils.php';

$record_per_page = 8;  
$page = isset($_POST["page"]) ? $_POST["page"] : 1;
$start_from = ($page - 1) * $record_per_page;  

$sql   = $_POST['sql'] . " LIMIT $start_from, $record_per_page";
$users = getSimpleQuery($sql, true);

$output = "
<tr>
  <th style='width:10px'>#</th>
  <th>Email</th>
  <th>Tên học viên</th>
  <th>Địa chỉ</th>
  <th>Số điện thoại</th>
  <th style='width:110px'>
    <a href='add.php' class='btn btn-xs btn-success'>
      <i class='fa fa-plus'></i> Thêm
    </a>
  </th>
</tr>
";

foreach($users as $u){
    $output .= "
    <tr class='hv-row-click' data-id='{$u['id']}' style='cursor:pointer;'>
        <td>{$u['id']}</td>
        <td>{$u['email']}</td>
        <td style='color:#3c8dbc; font-weight:600;'>{$u['fullname']}</td>
        <td>{$u['address']}</td>
        <td>{$u['phone']}</td>
        <td onclick='event.stopPropagation();'>
            <a href='{$ADMIN_URL}hocvien/edit.php?id={$u['id']}'
               class='btn btn-xs btn-primary'>
                <i class='fa fa-cog'></i> Sửa
            </a>
            <a href='javascript:;'
               linkurl='{$ADMIN_URL}hocvien/remove.php?id={$u['id']}'
               class='btn btn-xs btn-danger btn-remove'>
                <i class='fa fa-trash-o'></i> Xoá
            </a>
        </td>
    </tr>
    ";
}

// Pagination
$allUsers   = getSimpleQuery($_POST['sql'], true);
$total_pages = ceil(count($allUsers) / $record_per_page);

echo $output;
echo "<tr><td colspan='6' style='padding:10px 6px;'>";

if($page != 1){
    echo "<span class='pagination_link' style='border-radius:2px; cursor:pointer; margin:2px; padding:6px 12px; border:1px solid #ccc;' id='".($page-1)."'>« Trước</span>";
}

for($i = 1; $i <= $total_pages; $i++){
    if($i == $page){
        echo "<span class='pagination_link' style='color:#fff; background:#3c8dbc; margin:2px; cursor:pointer; padding:6px 12px; border-radius:2px; border:1px solid #3c8dbc;' id='$i'>$i</span>";
    } else {
        echo "<span class='pagination_link' style='cursor:pointer; margin:2px; padding:6px 12px; border-radius:2px; border:1px solid #ccc;' id='$i'>$i</span>";
    }
}

if($page != $total_pages){
    echo "<span class='pagination_link' style='border-radius:2px; margin:2px; cursor:pointer; padding:6px 12px; border:1px solid #ccc;' id='".($page+1)."'>Tiếp »</span>";
}

echo "</td></tr>";
?>