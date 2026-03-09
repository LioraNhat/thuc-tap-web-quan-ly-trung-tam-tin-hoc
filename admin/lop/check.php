<?php 
    $path = "../";
    require_once $path.$path.'commons/utils.php';

    // Dùng $class_id xuyên suốt, KHÔNG dùng $id để tránh bị ghi đè
    $class_id = (int)$_GET['class_id'];
    $day      = $_GET['day'];
    $today    = date("Y-m-d");

    // Chỉ admin (role 500) mới được điểm danh ngày khác hôm nay
    if(strtotime($day) != strtotime($today) && $_SESSION['login']['role'] != 500){
        header('location: '. $ADMIN_URL . 'thoikhoabieu/');
        die;
    }

    // Kiểm tra lớp này có ca học ngày đó không
    $checkSession = getSimpleQuery(
        "SELECT t.*, s.name as session_name, s.time as session_time 
         FROM timetable t 
         JOIN session s ON t.session_id = s.id
         WHERE t.class_id = '$class_id' AND t.day = '$day'
         LIMIT 1"
    );
    $coLich = (is_array($checkSession) && isset($checkSession['session_id']));

    // Lấy tên lớp (tách ra PHP, KHÔNG dùng $id)
    $classInfo = getSimpleQuery("SELECT * FROM classes WHERE id = $class_id");

    // Lấy danh sách học viên của lớp
    $cates = getSimpleQuery(
        "SELECT * FROM dangky 
         INNER JOIN student ON dangky.student_id = student.id 
         WHERE dangky.class_id = $class_id AND student.status = 1",
        true
    );
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>POLY | Điểm danh</title>
  <?php include_once $path.'_share/style_assets.php'; ?>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  <?php include_once $path.'_share/header.php'; ?>
  <?php include_once $path.'_share/sidebar.php'; ?>

  <div class="content-wrapper">
    <section class="content-header">
      <h1>Điểm danh <small>Control panel</small></h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Điểm danh</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">
                Điểm danh lớp 
                <strong class="text-primary">
                  <?= ($classInfo && isset($classInfo['name'])) ? $classInfo['name'] : 'Không tìm thấy lớp' ?>
                </strong>
                &nbsp;
                <?php if($coLich): ?>
                  <span class="label label-success">
                    <i class="fa fa-clock-o"></i>
                    <?= $checkSession['session_name'] ?> — <?= $checkSession['session_time'] ?>
                  </span>
                <?php else: ?>
                  <span class="label label-danger">
                    <i class="fa fa-times"></i> Ngày này lớp không có lịch học
                  </span>
                <?php endif; ?>
              </h3>
            </div>

            <div class="box-body">
              <?php if(!$coLich && $_SESSION['login']['role'] != 500): ?>
                <div class="alert alert-warning">
                  <i class="fa fa-exclamation-triangle"></i>
                  <strong>Không thể điểm danh!</strong> Ngày này lớp không có ca học.
                </div>
              <?php else: ?>

                <form action="save-check.php" method="post">
                  <table class="table table-bordered">
                    <tbody>
                      <tr>
                        <th style="width:10px">#</th>
                        <th>Ảnh đại diện</th>
                        <th>Mã sinh viên</th>
                        <th>Tên sinh viên</th>
                        <th style="width:160px">Tình trạng</th>
                      </tr>

                      <?php foreach($cates as $key => $row):
                        $student_id = $row['student_id'];

                        $stuCheck = getSimpleQuery(
                            "SELECT * FROM student_check 
                             WHERE student_id = '$student_id' 
                               AND day = '$day' 
                               AND class_id = '$class_id'
                             LIMIT 1"
                        );

                        $daVang       = (is_array($stuCheck) && $stuCheck['status'] == 0);
                        $daCóMặt     = (is_array($stuCheck) && $stuCheck['status'] == 1);
                        $chuaDiemDanh = !is_array($stuCheck);
                      ?>
                      <tr>
                        <td><?= $key + 1 ?></td>
                        <td><img src="<?= SITE_URL . $row['avatar'] ?>" style="width:60px; border-radius:4px;"></td>
                        <td><?= $row['student_id'] ?></td>
                        <td><?= $row['fullname'] ?></td>
                        <td>
                          <select class="form-control" name="check[]">
                            <option value="1" <?= ($daCóMặt || $chuaDiemDanh) ? 'selected' : '' ?>>
                              ✅ Có mặt
                            </option>
                            <option value="0" <?= $daVang ? 'selected' : '' ?>>
                              ❌ Vắng mặt
                            </option>
                          </select>
                        </td>
                      </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>

                  <div class="form-group">
                    <input type="hidden" name="day"   value="<?= $day ?>">
                    <input type="hidden" name="class" value="<?= $class_id ?>">

                    <?php
                      $role     = $_SESSION['login']['role'];
                      $disabled = "";
                      if($role != 500){
                          $stuCheck2 = getSimpleQuery(
                              "SELECT * FROM student_check 
                               WHERE day = '$day' AND class_id = '$class_id'
                               LIMIT 1"
                          );
                          if(is_array($stuCheck2) && $stuCheck2['num_check'] == 1){
                              $disabled = "disabled";
                          }
                          if(!$coLich){
                              $disabled = "disabled";
                          }
                          if($role == 0){
                              $disabled = "disabled";
                          }
                      }
                    ?>
                    <button type="submit" name="update" class="btn btn-primary" <?= $disabled ?>>
                      <i class="fa fa-save"></i> Cập nhật điểm danh
                    </button>
                  </div>
                </form>

              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <?php include_once $path.'_share/footer.php'; ?>
</div>

<?php include_once $path.'_share/script_assets.php'; ?>
<script>
  <?php if(isset($_GET['success'])): ?>
    swal('Điểm danh thành công!');
  <?php elseif(isset($_GET['editsuccess'])): ?>
    swal('Cập nhật điểm danh thành công!');
  <?php endif; ?>
</script>
</body>
</html>