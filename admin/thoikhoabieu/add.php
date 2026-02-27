<?php 
$path = "../";
require_once $path.$path.'commons/utils.php';

// ====== LOAD DATA ======
$listCateQuery = "SELECT * FROM courses";
$cates = getSimpleQuery($listCateQuery, true);

$listClassQuery = "SELECT * FROM classes";
$class = getSimpleQuery($listClassQuery, true);

$listRoomQuery = "SELECT * FROM rooms";
$room = getSimpleQuery($listRoomQuery, true);

$listSessionQuery = "SELECT * FROM session";
$session = getSimpleQuery($listSessionQuery, true);

$listTeaQuery = "SELECT * FROM teachers";
$teacher = getSimpleQuery($listTeaQuery, true);
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>POLY | Thêm lịch học</title>
  <?php include_once $path.'_share/style_assets.php'; ?>
</head>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

<?php include_once $path.'_share/header.php'; ?>
<?php include_once $path.'_share/sidebar.php'; ?>

<div class="content-wrapper">

<section class="content-header">
  <?php if(isset($_GET['err'])): ?>
  <div class="alert alert-danger alert-dismissible" style="margin: 15px;">
      <button type="button" class="close" data-dismissible="alert" aria-hidden="true">×</button>
      <h4><i class="icon fa fa-ban"></i> Lỗi trùng lịch!</h4>
      <?= $_GET['err'] ?>
  </div>
  <?php endif; ?>
  <h1>Thêm lịch học</h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Thêm lịch học</li>
  </ol>
</section>

<section class="content">
<form action="<?= $ADMIN_URL ?>thoikhoabieu/save-add.php" method="post">

<div class="row">
<div class="col-md-12">

<div class="box box-primary">
<div class="box-header with-border">
  <h3 class="box-title">Thông tin lịch học</h3>
</div>

<div class="box-body">

<div class="row">

<!-- LEFT COLUMN -->
<div class="col-md-6">

<!-- Khóa học -->
<div class="form-group">
  <label>Khóa học</label>
  <select class="form-control" name="course_id" id="course_id">
    <option value="0">-- Chọn khóa học --</option>
    <?php foreach($cates as $row){ ?>
      <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
    <?php } ?>
  </select>
  <?php if(isset($_GET['k'])): ?>
    <span class="text-danger"><?= $_GET['k'] ?></span>
  <?php endif; ?>
</div>

<!-- Lớp học -->
<div class="form-group">
  <label>Lớp học</label>
  <select class="form-control" name="class_id" id="class_id">
    <option value="0">-- Chọn lớp học --</option>
    <?php foreach($class as $row){ ?>
      <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
    <?php } ?>
  </select>
  <?php if(isset($_GET['c'])): ?>
    <span class="text-danger"><?= $_GET['c'] ?></span>
  <?php endif; ?>
</div>

<!-- Ngày bắt đầu -->
<div class="form-group">
  <label>Ngày bắt đầu</label>
  <input type="date" name="created" id="date" class="form-control">
  <?php if(isset($_GET['cr'])): ?>
    <span class="text-danger"><?= $_GET['cr'] ?></span>
  <?php endif; ?>
</div>

<!-- Ngày kết thúc -->
<div class="checkbox">
  <label>
    <input type="checkbox" id="chosen_end" name="chosen_end" value="1">
    Có ngày kết thúc
  </label>
</div>

<div class="form-group">
  <input type="date" name="ended" id="ended" class="form-control" disabled>
</div>

<!-- Thứ trong tuần -->
<div class="form-group">
  <label>Chọn thứ trong tuần</label>
  <div class="row">

  <?php 
  $days = [
    1=>"Thứ hai",2=>"Thứ ba",3=>"Thứ tư",
    4=>"Thứ năm",5=>"Thứ sáu",6=>"Thứ bảy",7=>"Chủ nhật"
  ];
  foreach($days as $value=>$label): ?>
    <div class="col-md-3">
      <div class="checkbox">
        <label>
          <input type="checkbox" name="check[]" value="<?= $value ?>">
          <?= $label ?>
        </label>
      </div>
    </div>
  <?php endforeach; ?>

  </div>
  <?php if(isset($_GET['th'])): ?>
    <span class="text-danger"><?= $_GET['th'] ?></span>
  <?php endif; ?>
</div>

</div>


<!-- RIGHT COLUMN -->
<div class="col-md-6">

<!-- Ca học -->
<div class="form-group">
  <label>Ca học</label>
  <select class="form-control" name="session_id" id="session_id">
    <option value="0">-- Chọn ca học --</option>
    <?php foreach($session as $row){ ?>
      <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
    <?php } ?>
  </select>
  <?php if(isset($_GET['s'])): ?>
    <span class="text-danger"><?= $_GET['s'] ?></span>
  <?php endif; ?>
</div>

<input type="hidden" name="soTiet" id="soTiet">

<!-- Phòng -->
<div class="form-group">
  <label>Phòng học</label>
  <select class="form-control" name="room_id" id="room_id">
    <option value="0">-- Vui lòng chọn Ngày & Ca trước --</option>
  </select>
</div>

<!-- Giáo viên -->
<div class="form-group">
  <label>Giáo viên</label>
  <select class="form-control" name="teacher_id" id="teacher_id">
    <option value="0">-- Vui lòng chọn Phòng học trước --</option>
  </select>
</div>

</div>

</div>
</div>

<div class="box-footer">
  <a href="<?= $ADMIN_URL?>thoikhoabieu" class="btn btn-danger">Huỷ</a>
  <button type="submit" class="btn btn-primary">Tạo mới</button>
</div>

</div>
</div>
</div>

</form>
</section>
</div>

<?php include_once $path.'_share/footer.php'; ?>
</div>

<?php include_once $path.'_share/script_assets.php'; ?>

<script>
$(document).ready(function(){

  $('#chosen_end').change(function(){
      $('#ended').prop('disabled', !this.checked);
  });

  function resetRoom(){
      $('#room_id').html("<option value='0'>-- Vui lòng chọn Ngày & Ca trước --</option>");
      resetTeacher();
  }

  function resetTeacher(){
      $('#teacher_id').html("<option value='0'>-- Vui lòng chọn Phòng học trước --</option>");
  }

  function getSelectionData() {
    // Lấy mảng các thứ đã chọn (ví dụ: [1, 3, 5])
    var selectedDays = [];
    $('input[name="check[]"]:checked').each(function() {
        selectedDays.push($(this).val());
    });

    return {
        date: $('#date').val(),
        ended: $('#chosen_end').is(':checked') ? $('#ended').val() : $('#date').val(),
        session: $('#session_id').val(),
        days: selectedDays
    };
}

function loadRoom() {
    var data = getSelectionData();

    if (data.date !== "" && data.session !== "0" && data.days.length > 0) {
        $.post("xulyroom.php", data, function(res) {
            $('#room_id').html(res);
        });
    } else {
        resetRoom();
    }
}

function loadTeacher() {
    var data = getSelectionData();
    data.room = $('#room_id').val();

    if (data.room !== "0") {
        $.post("xulyteacher.php", data, function(res) {
            $('#teacher_id').html(res);
        });
    } else {
        resetTeacher();
    }
}

// Lắng nghe thêm sự kiện khi click vào các checkbox "Thứ"
$('#date, #session_id, #ended, #chosen_end, input[name="check[]"]').change(loadRoom);
$('#room_id').change(loadTeacher);

  $('#course_id').change(function(){
      $.post("../baitap/xulysubject.php",
        {course:$(this).val()},
        function(res){ $('#class_id').html(res); }
      );
  });

  $('#class_id').change(function(){
      $.post("../lop/xulycourse.php",
        {course:$('#course_id').val()},
        function(res){ $('#soTiet').val(res); }
      );
  });

});
</script>

</body>
</html>