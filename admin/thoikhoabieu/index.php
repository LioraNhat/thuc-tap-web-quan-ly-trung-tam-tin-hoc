<?php 
    $path = "../";
    require_once $path.$path.'commons/utils.php';
    $day = date("Y-m-d");

    // 1. Load dữ liệu cho các dropdown
    $teachers = getSimpleQuery("SELECT * FROM teachers", true);
    $courses  = getSimpleQuery("SELECT * FROM courses", true);
    $classes  = getSimpleQuery("SELECT * FROM classes", true);
    $rooms    = getSimpleQuery("SELECT * FROM rooms", true);
    $sessions = getSimpleQuery("SELECT * FROM session", true);

    // 2. Định nghĩa câu truy vấn gốc
    $baseSelect = "SELECT t.*, c.name as course_name, cl.name as class_name, 
                          r.name as room_name, te.fullname as teacher_name, 
                          s.name as session_name, s.time as session_time
                   FROM timetable t
                   JOIN courses c ON t.course_id = c.id
                   JOIN classes cl ON t.class_id = cl.id
                   JOIN rooms r ON t.room_id = r.id
                   JOIN teachers te ON t.teacher_id = te.id
                   JOIN session s ON t.session_id = s.id ";

    // 3. XỬ LÝ LỌC ĐA NĂNG
    $where = ["1=1"]; 

    if(isset($_POST['filter'])){
        if(!empty($_POST['f_date']))    $where[] = "t.day = '" . $_POST['f_date'] . "'";
        if($_POST['f_course'] > 0)      $where[] = "t.course_id = " . (int)$_POST['f_course'];
        // Lưu ý: f_class cần được xử lý qua Ajax nếu bạn muốn lọc theo lớp cụ thể của khóa học
        if(isset($_POST['f_class']) && $_POST['f_class'] > 0) $where[] = "t.class_id = " . (int)$_POST['f_class'];
        if($_POST['f_teacher'] > 0)     $where[] = "t.teacher_id = " . (int)$_POST['f_teacher'];
        if($_POST['f_room'] > 0)        $where[] = "t.room_id = " . (int)$_POST['f_room'];
        if($_POST['f_session'] > 0)     $where[] = "t.session_id = " . (int)$_POST['f_session'];
        
        $sqlFilter = $baseSelect . " WHERE " . implode(" AND ", $where) . " ORDER BY t.day ASC";
        $cates = getSimpleQuery($sqlFilter, true);
    } else {
        $cates = getSimpleQuery($baseSelect . " WHERE t.day >= '$day' ORDER BY t.day ASC", true);
    }

    $todayList = getSimpleQuery($baseSelect . " WHERE t.day = '$day'", true);

    // Hàm tính thứ
    function tinhthu($ngay) {
        $date = strtotime($ngay);
        $days = ["Chủ nhật", "Thứ hai", "Thứ ba", "Thứ tư", "Thứ năm", "Thứ sáu", "Thứ bảy"];
        return $days[date('w', $date)];
    }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Quản lý Thời khóa biểu</title>
    <?php include_once $path.'_share/style_assets.php'; ?>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
    <?php include_once $path.'_share/header.php'; ?>
    <?php include_once $path.'_share/sidebar.php'; ?>

    <div class="content-wrapper">
        <section class="content-header">
            <h1>DANH SÁCH <small>Thời khóa biểu</small></h1>
        </section>

        <section class="content">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Thời khóa biểu 
                        <a href="<?= $ADMIN_URL ?>thoikhoabieu/add.php" class="btn btn-xs btn-success"><i class="fa fa-plus"></i> Thêm</a>
                    </h3>
                </div>
                <div class="box-body">
                    <h3>Lịch hôm nay</h3>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Ngày</th>
                                <th>Khóa học</th>
                                <th>Lớp học</th>
                                <th>Phòng</th>
                                <th>Giáo viên</th>
                                <th>Ca học</th>
                                <th>Điểm danh</th>
                                <th>Chức năng</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($todayList as $row): ?>
                            <tr>
                                <td><?= tinhthu($row['day']).", ".$row['day'] ?></td>
                                <td><?= $row['course_name'] ?></td>
                                <td><?= $row['class_name'] ?></td>
                                <td><?= $row['room_name'] ?></td>
                                <td><?= $row['teacher_name'] ?></td>
                                <td><?= $row['session_name'].' ('.$row['session_time'].')' ?></td>
                                <td>
                                    <a href="<?= $ADMIN_URL?>lop/check.php?class_id=<?= $row['class_id']?>&day=<?= $row['day'] ?>" class="btn btn-xs btn-link">Điểm danh</a>
                                </td>
                                <td>
                                    <a href="<?= $ADMIN_URL?>thoikhoabieu/edit1.php?id=<?= $row['id']?>" class="btn btn-xs btn-primary">Sửa</a>
                                    <a href="javascript:;" linkurl="<?= $ADMIN_URL?>thoikhoabieu/xoa.php?id=<?= $row['id']?>" class="btn btn-xs btn-danger btn-remove">Xóa</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <hr>
                    <div class="box box-solid bg-gray-light" style="border: 1px solid #d2d6de;">
                      <div class="box-body">
                          <form method="post" class="row">
                              <div class="col-md-2 col-sm-4">
                                  <label small>Ngày học</label>
                                  <input type="date" name="f_date" class="form-control input-sm" value="<?= $_POST['f_date'] ?? '' ?>">
                              </div>
                              <div class="col-md-2 col-sm-4">
                                  <label small>Khóa học</label>
                                  <select name="f_course" id="course_id" class="form-control input-sm">
                                      <option value="0">-- Tất cả --</option>
                                      <?php foreach($courses as $r) {
                                          $selected = (isset($_POST['f_course']) && $_POST['f_course'] == $r['id']) ? 'selected' : '';
                                          echo "<option value='{$r['id']}' $selected>{$r['name']}</option>";
                                      } ?>
                                  </select>
                              </div>
                              <div class="col-md-2 col-sm-4">
                                <label small>Lớp học</label>
                                <select name="f_class" id="class_id" class="form-control input-sm">
                                    <option value="0">-- Tất cả --</option>
                                    <?php foreach($classes as $r) {
                                        $selected = (isset($_POST['f_class']) && $_POST['f_class'] == $r['id']) ? 'selected' : '';
                                        echo "<option value='{$r['id']}' $selected>{$r['name']}</option>";
                                    } ?>
                                </select>
                                </div>
                              <div class="col-md-2 col-sm-4">
                                  <label small>Giáo viên</label>
                                  <select name="f_teacher" class="form-control input-sm">
                                      <option value="0">-- Tất cả --</option>
                                      <?php foreach($teachers as $r) {
                                          $selected = (isset($_POST['f_teacher']) && $_POST['f_teacher'] == $r['id']) ? 'selected' : '';
                                          echo "<option value='{$r['id']}' $selected>{$r['fullname']}</option>";
                                      } ?>
                                  </select>
                              </div>
                              <div class="col-md-2 col-sm-4">
                                  <label small>Phòng học</label>
                                  <select name="f_room" class="form-control input-sm">
                                      <option value="0">-- Tất cả --</option>
                                      <?php foreach($rooms as $r) {
                                          $selected = (isset($_POST['f_room']) && $_POST['f_room'] == $r['id']) ? 'selected' : '';
                                          echo "<option value='{$r['id']}' $selected>{$r['name']}</option>";
                                      } ?>
                                  </select>
                              </div>
                              <div class="col-md-2 col-sm-4">
                                  <label small>Ca học</label>
                                  <select name="f_session" class="form-control input-sm">
                                      <option value="0">-- Tất cả --</option>
                                      <?php foreach($sessions as $r) {
                                          $selected = (isset($_POST['f_session']) && $_POST['f_session'] == $r['id']) ? 'selected' : '';
                                          echo "<option value='{$r['id']}' $selected>{$r['name']}</option>";
                                      } ?>
                                  </select>
                              </div>
                              <div class="col-md-2 col-sm-4">
                                  <label>&nbsp;</label>
                                  <button type="submit" name="filter" class="btn btn-sm btn-primary btn-block">
                                      <i class="fa fa-filter"></i> Lọc lịch
                                  </button>
                              </div>
                          </form>
                      </div>
                  </div>
                  <div class="box-body"></div>
                    <h3>Lịch tiếp theo</h3>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Ngày</th>
                                <th>Khóa học</th>
                                <th>Lớp học</th>
                                <th>Phòng</th>
                                <th>Giáo viên</th>
                                <th>Ca học</th>
                                <th>Chức năng</th>
                            </tr>
                        </thead>
                        <tbody id="oday1">
                            <?php 
                              $i = 0;
                              foreach($cates as $row): 
                              $classHidden = ($i >= 10) ? 'hidden-row' : '';
                              ?>
                            <tr class="<?= $classHidden ?>" <?= ($i >= 10) ? 'style="display:none"' : '' ?>>
                                <td><?= tinhthu($row['day']).", ".$row['day'] ?></td>
                                <td><?= $row['course_name'] ?></td>
                                <td><?= $row['class_name'] ?></td>
                                <td><?= $row['room_name'] ?></td>
                                <td><?= $row['teacher_name'] ?></td>
                                <td><?= $row['session_name'].' ('.$row['session_time'].')' ?></td>
                                <td>
                                    <a href="<?= $ADMIN_URL?>thoikhoabieu/edit1.php?id=<?= $row['id']?>" class="btn btn-xs btn-primary">Sửa</a>
                                    <a href="javascript:;" linkurl="<?= $ADMIN_URL?>thoikhoabieu/xoa.php?id=<?= $row['id']?>" class="btn btn-xs btn-danger btn-remove">Xóa</a>
                                </td>
                            </tr>
                            <?php $i++; endforeach; ?>
                        </tbody>
                    </table>
                    <?php if (count($cates) > 10): ?>
                        <div class="text-center" style="margin-top: 10px;">
                            <button id="btn-show-more" class="btn btn-default btn-sm">
                                <i class="fa fa-angle-double-down"></i> Xem thêm
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </div>
    <?php include_once $path.'_share/footer.php'; ?>
</div>

<?php include_once $path.'_share/script_assets.php'; ?>
<script>
    $(document).ready(function() {
        // Xử lý XÓA bằng AJAX (Giữ nguyên giao diện)
        $(document).on('click', '.btn-remove', function() {
            let btn = $(this);
            let url = btn.attr('linkurl');
            swal({
                title: "Cảnh báo!",
                text: "Bạn có chắc chắn muốn xoá?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: url,
                        type: 'GET',
                        success: function() {
                            swal("Xóa thành công!", { icon: "success" });
                            btn.closest('tr').fadeOut(300); // Ẩn dòng vừa xóa
                        },
                        error: function() {
                            swal("Lỗi!", "Không thể xóa dữ liệu.", "error");
                        }
                    });
                }
            });
        });

        // Tự động load lớp học theo khóa học (Ajax dropdown)
        $('#course_id').change(function(){
            var course = $(this).val();
            $.post("../baitap/xulysubject.php", {course: course}, function(kq){
                $('#class_id').html(kq);
            });
        });

        var itemsToShow = 10;
        $('#btn-show-more').on('click', function() {
            // Lấy danh sách các dòng đang bị ẩn
            var hiddenRows = $('.hidden-row:hidden');

            // Lấy ra 10 dòng tiếp theo và hiển thị chúng
            hiddenRows.slice(0, itemsToShow).fadeIn(300);

            // Kiểm tra lại: Nếu không còn dòng nào bị ẩn nữa thì đổi nút thành "Thu gọn"
            if ($('.hidden-row:hidden').length === 0) {
                $(this).html('<i class="fa fa-angle-double-up"></i> Thu gọn');
                $(this).data('all-shown', true);
            }
        });

        // Logic bổ sung: Nếu đã hiện hết mà bấm lần nữa thì thu gọn về 10 dòng đầu
        $(document).on('click', '#btn-show-more', function() {
            if ($(this).data('all-shown') === true) {
                $('.hidden-row').hide();
                $(this).html('<i class="fa fa-angle-double-down"></i> Xem thêm');
                $(this).data('all-shown', false);
                
                // Cuộn lên đầu bảng
                $('html, body').animate({
                    scrollTop: $("#oday1").offset().top - 150
                }, 500);
            }
        
      });
    });
</script>
</body>
</html>