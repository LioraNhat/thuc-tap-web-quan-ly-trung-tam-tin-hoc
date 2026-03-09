<?php 
$path = "../";
require_once $path.$path.'commons/utils.php';
$id = $_GET['id'];
$listTimeQuery = "select * from timetable where id = $id";
$time = getSimpleQuery($listTimeQuery);
$class_t   = $time['class_id'];
$room_t    = $time['room_id'];
$session_t = $time['session_id'];
$teacher_t = $time['teacher_id'];
$course_t  = $time['course_id'];

$listClassQuery = "select * from classes";
$class = getSimpleQuery($listClassQuery, true);

$listRoomQuery = "select * from rooms";
$room = getSimpleQuery($listRoomQuery, true);

$listSessionQuery = "select * from session";
$session = getSimpleQuery($listSessionQuery, true);

$listTeaQuery = "select * from teachers";
$teacher = getSimpleQuery($listTeaQuery, true);

// Tính thứ của ngày hiện tại để tích sẵn checkbox
$jd     = cal_to_jd(CAL_GREGORIAN,
    (int)date('m', strtotime($time['day'])),
    (int)date('d', strtotime($time['day'])),
    (int)date('Y', strtotime($time['day']))
);
$thu_cu    = jddayofweek($jd, 0); // 0=CN, 1=T2, ..., 6=T7
$thu_check = ($thu_cu == 0) ? 7 : $thu_cu; // mapping: T2=1,...,T7=6, CN=7
?>
 
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>POLY | Sửa thời khóa biểu</title>
  <?php include_once $path.'_share/style_assets.php'; ?>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  <?php include_once $path.'_share/header.php'; ?>
  <?php include_once $path.'_share/sidebar.php'; ?>

  <div class="content-wrapper">
    <section class="content-header">
      <h1>Sửa thời khóa biểu</h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Sửa thời khóa biểu</li>
      </ol>
    </section>

    <section class="content">
      <?php if(isset($_GET['err'])): ?>
        <div class="alert alert-danger alert-dismissible">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
          <h4><i class="icon fa fa-ban"></i> Lỗi!</h4>
          <?php echo $_GET['err']; ?>
        </div>
      <?php endif; ?>

      <form action="<?= $ADMIN_URL ?>thoikhoabieu/save-edit.php" method="post">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">Sửa thời khóa biểu</h3>
          </div>
          <div class="box-body">
            <div class="col-md-6">

              <!-- Lớp học: disabled để hiển thị, hidden để gửi giá trị -->
              <div class="form-group">
                <label>Lớp học</label>
                <select class="form-control" disabled>
                  <option><?php
                    $listClaQuery = "select * from classes where id = $class_t";
                    $clas = getSimpleQuery($listClaQuery);
                    echo $clas['name'];
                  ?></option>
                </select>
                <!-- FIX: hidden input để đảm bảo class_id luôn được gửi -->
                <input type="hidden" name="class_id" value="<?= $class_t ?>">
              </div>

              <!-- Ngày bắt đầu: giữ ngày cũ -->
              <div class="form-group">
                <label>Ngày bắt đầu</label>
                <input type="date" name="created" class="form-control" id="date"
                       value="<?= $time['day'] ?>">
              </div>

              <div class="form-check">
                <input class="form-check-input" type="checkbox" value="1"
                       id="chosen_end" name="chosen_end">
                <label class="form-check-label">Ngày kết thúc</label>
              </div>
              <div class="form-group">
                <input type="date" name="ended" class="form-control" id="ended" disabled>
              </div>
              <script>
                document.getElementById('chosen_end').onchange = function() {
                  document.getElementById('ended').disabled = !this.checked;
                };
              </script>

              <!-- Checkbox thứ: tích sẵn thứ của lịch cũ -->
              <div class="form-group" style="margin-bottom:0px;">
                <label>Chọn thứ trong tuần</label>
              </div>
              <div class="row">
                <div class="col-md-3">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="1" name="check[]"
                           <?= ($thu_check == 1) ? 'checked' : '' ?>>
                    <label class="form-check-label">Thứ hai</label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="2" name="check[]"
                           <?= ($thu_check == 2) ? 'checked' : '' ?>>
                    <label class="form-check-label">Thứ ba</label>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="3" name="check[]"
                           <?= ($thu_check == 3) ? 'checked' : '' ?>>
                    <label class="form-check-label">Thứ tư</label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="4" name="check[]"
                           <?= ($thu_check == 4) ? 'checked' : '' ?>>
                    <label class="form-check-label">Thứ năm</label>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="5" name="check[]"
                           <?= ($thu_check == 5) ? 'checked' : '' ?>>
                    <label class="form-check-label">Thứ sáu</label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="6" name="check[]"
                           <?= ($thu_check == 6) ? 'checked' : '' ?>>
                    <label class="form-check-label">Thứ bảy</label>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="7" name="check[]"
                           <?= ($thu_check == 7) ? 'checked' : '' ?>>
                    <label class="form-check-label">Chủ nhật</label>
                  </div>
                </div>
              </div>
              <?php if(isset($_GET['th'])): ?>
                <span class="text-danger"><?= $_GET['th'] ?></span>
              <?php endif; ?>

            </div><!-- /col-md-6 -->

            <div class="col-md-6">

              <!-- Ca học: giữ ca cũ -->
              <div class="form-group">
                <label>Ca học</label>
                <select class="form-control" name="session_id" id="session_id">
                  <?php foreach($session as $row): ?>
                    <option value="<?= $row['id'] ?>"
                      <?= ($session_t == $row['id']) ? 'selected' : '' ?>>
                      <?= $row['name'] ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <input type="hidden" name="soTiet" id="soTiet" value="<?php
                $listCourQuery = "select * from courses where id = $course_t";
                $cour = getSimpleQuery($listCourQuery);
                echo $cour['soTiet'];
              ?>">

              <!-- Phòng học: giữ phòng cũ, KHÔNG dùng Ajax ghi đè khi load -->
              <div class="form-group">
                <label>Phòng học</label>
                <select class="form-control" name="room_id" id="room_id">
                  <?php foreach($room as $row): ?>
                    <option value="<?= $row['id'] ?>"
                      <?= ($room_t == $row['id']) ? 'selected' : '' ?>>
                      <?= $row['name'] ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <!-- Giáo viên: giữ giáo viên cũ, KHÔNG dùng Ajax ghi đè khi load -->
              <div class="form-group">
                <label>Giáo viên</label>
                <select class="form-control" name="teacher_id" id="teacher_id">
                  <?php foreach($teacher as $row): ?>
                    <option value="<?= $row['id'] ?>"
                      <?= ($teacher_t == $row['id']) ? 'selected' : '' ?>>
                      <?= $row['fullname'] ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

            </div><!-- /col-md-6 -->

            <div class="col-md-12">
              <input type="hidden" name="course_id" value="<?= $course_t ?>">
              <input type="hidden" id="roo"  name="roo" value="<?= $room_t ?>">
              <input type="hidden" name="ses" value="<?= $session_t ?>">
              <input type="hidden" id="tea"  name="tea" value="<?= $teacher_t ?>">
              <input type="hidden" name="id"  value="<?= $_GET['id'] ?>">
              <a href="<?= $ADMIN_URL ?>thoikhoabieu" class="btn btn-danger btn-xs">Huỷ</a>
              <button type="submit" class="btn btn-xs btn-primary">Cập nhật</button>
            </div>

          </div><!-- /box-body -->
        </div><!-- /box -->

        <script type="text/javascript">
          $(document).ready(function(){
            var date    = $('#date').val();
            var session = $('#session_id').val();
            var roo     = $('#roo').val();
            var tea     = $('#tea').val();

            // Khi đổi ca học → reload phòng còn trống (nhưng ưu tiên giữ phòng cũ)
            $('#session_id').change(function(){
              date    = $('#date').val();
              session = $(this).val();
              $.ajax({
                url: "xulyroom.1.php",
                method: "post",
                data: { date: date, session: session, roo: roo },
                dataType: "text",
                success: function(kq){ $('#room_id').html(kq); }
              });
            });

            // Khi đổi phòng → reload giáo viên còn trống (nhưng ưu tiên giữ giáo viên cũ)
            $('#room_id').change(function(){
              date    = $('#date').val();
              session = $('#session_id').val();
              $.ajax({
                url: "xulyteacher.1.php",
                method: "post",
                data: { date: date, session: session, tea: tea },
                dataType: "text",
                success: function(kq){ $('#teacher_id').html(kq); }
              });
            });
          });

          <?php if(isset($_GET['editsuccess'])): ?>
            swal('Sửa lịch học thành công!', '', 'success');
          <?php endif; ?>
        </script>
      </form>
    </section>
  </div>

  <?php include_once $path.'_share/footer.php'; ?>
</div>

<?php include_once $path.'_share/script_assets.php'; ?>
</body>
</html>