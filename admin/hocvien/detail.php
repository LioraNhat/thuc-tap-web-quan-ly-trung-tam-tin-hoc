<?php
require_once '../../commons/utils.php';

if(!isset($_GET['id'])){
    die("Không tìm thấy học viên");
}

$id = (int)$_GET['id'];
$user = getSimpleQuery("SELECT * FROM student WHERE id = $id");
if(!$user){
    die("Học viên không tồn tại");
}

$dangkyList = getSimpleQuery(
    "SELECT dk.*, c.name as course_name, c.hocphi, cl.name as class_name
     FROM dangky dk
     JOIN courses c  ON dk.course_id = c.id
     JOIN classes cl ON dk.class_id  = cl.id
     WHERE dk.student_id = $id",
    true
);

$tongHocPhi = 0;
$tongDaDong = 0;
$rows = [];
if(!empty($dangkyList)){
    foreach($dangkyList as $k => $dk){
        $tongThu = getSimpleQuery("SELECT COALESCE(SUM(amount),0) as tong FROM receipts WHERE dangky_id = {$dk['id']}");
        $daDong  = $tongThu ? (int)$tongThu['tong'] : 0;
        $hocPhi  = (int)$dk['total_amount'] > 0 ? (int)$dk['total_amount'] : (int)$dk['hocphi'];
        $conNo   = max(0, $hocPhi - $daDong);
        $tongHocPhi += $hocPhi;
        $tongDaDong += $daDong;
        $rows[] = ['dk' => $dk, 'daDong' => $daDong, 'hocPhi' => $hocPhi, 'conNo' => $conNo];
    }
}
$tongNo = max(0, $tongHocPhi - $tongDaDong);

$genderLabel = $user['gender'] == 1 ? 'Nam' : ($user['gender'] == -1 ? 'Nữ' : '—');
$avatarUrl   = SITE_URL . ($user['avatar'] ?: 'img/29541772703_6ed8b50c47_b.jpg');
?>
<style>
@import url('https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700&display=swap');

.hv-detail {
    font-family: 'Be Vietnam Pro', sans-serif;
    background: #f0f4f8;
    padding: 0;
    color: #1a2332;
}

/* Hero banner */
.hv-hero {
    background: linear-gradient(135deg, #0f2544 0%, #1a3a6b 50%, #0e4d8a 100%);
    padding: 28px 28px 48px;
    position: relative;
    overflow: hidden;
}
.hv-hero::before {
    content: '';
    position: absolute;
    top: -40px; right: -40px;
    width: 200px; height: 200px;
    border-radius: 50%;
    background: rgba(255,255,255,0.04);
}
.hv-hero::after {
    content: '';
    position: absolute;
    bottom: -60px; left: 30%;
    width: 300px; height: 300px;
    border-radius: 50%;
    background: rgba(255,255,255,0.03);
}
.hv-hero-inner {
    display: flex;
    align-items: center;
    gap: 20px;
    position: relative;
    z-index: 1;
}
.hv-avatar {
    width: 72px; height: 72px;
    border-radius: 50%;
    border: 3px solid rgba(255,255,255,0.3);
    object-fit: cover;
    flex-shrink: 0;
    box-shadow: 0 8px 24px rgba(0,0,0,0.3);
}
.hv-name {
    color: #fff;
    font-size: 20px;
    font-weight: 700;
    margin: 0 0 4px;
    letter-spacing: -0.3px;
}
.hv-meta {
    color: rgba(255,255,255,0.65);
    font-size: 12.5px;
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
}
.hv-meta span {
    display: flex;
    align-items: center;
    gap: 5px;
}
.hv-meta i { font-size: 11px; }
.hv-id-badge {
    margin-left: auto;
    background: rgba(255,255,255,0.12);
    border: 1px solid rgba(255,255,255,0.2);
    color: #fff;
    border-radius: 20px;
    padding: 4px 14px;
    font-size: 12px;
    font-weight: 600;
    white-space: nowrap;
    align-self: flex-start;
}

/* Summary cards */
.hv-cards {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
    padding: 0 20px;
    margin-top: -24px;
    position: relative;
    z-index: 2;
}
.hv-card {
    background: #fff;
    border-radius: 12px;
    padding: 16px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.08);
    text-align: center;
}
.hv-card-label {
    font-size: 11px;
    color: #7a8fa6;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 6px;
}
.hv-card-value {
    font-size: 17px;
    font-weight: 700;
    color: #0f2544;
}
.hv-card-value.danger  { color: #e53e3e; }
.hv-card-value.success { color: #38a169; }
.hv-card-value.accent  { color: #2b6cb0; }

/* Info grid */
.hv-section {
    padding: 20px 20px 0;
}
.hv-section-title {
    font-size: 13px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    color: #7a8fa6;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.hv-section-title::after {
    content: '';
    flex: 1;
    height: 1px;
    background: #e2e8f0;
}
.hv-info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
}
.hv-info-item {
    background: #fff;
    border-radius: 10px;
    padding: 12px 14px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}
.hv-info-item label {
    display: block;
    font-size: 11px;
    color: #7a8fa6;
    font-weight: 600;
    margin-bottom: 3px;
}
.hv-info-item span {
    font-size: 13.5px;
    font-weight: 500;
    color: #1a2332;
}

/* Course table */
.hv-table-wrap {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}
.hv-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
}
.hv-table thead tr {
    background: #f7fafc;
}
.hv-table thead th {
    padding: 11px 14px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #7a8fa6;
    border-bottom: 2px solid #e2e8f0;
    white-space: nowrap;
}
.hv-table tbody tr {
    border-bottom: 1px solid #f0f4f8;
    transition: background 0.15s;
}
.hv-table tbody tr:hover { background: #f7fafc; }
.hv-table tbody tr:last-child { border-bottom: none; }
.hv-table td {
    padding: 11px 14px;
    color: #2d3748;
    vertical-align: middle;
}
.hv-table tfoot tr {
    background: #eef2f7;
}
.hv-table tfoot td {
    padding: 11px 14px;
    font-weight: 700;
    font-size: 13px;
    color: #1a2332;
}
.hv-table .text-right { text-align: right; }
.hv-table .text-center { text-align: center; }

.badge-status {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
}
.badge-done    { background: #c6f6d5; color: #276749; }
.badge-partial { background: #fefcbf; color: #975a16; }
.badge-none    { background: #fed7d7; color: #9b2c2c; }

.amount-neg { color: #e53e3e; font-weight: 600; }
.amount-ok  { color: #38a169; font-weight: 600; }

.hv-empty {
    text-align: center;
    padding: 32px;
    color: #a0aec0;
    font-size: 13px;
}
.hv-empty i { font-size: 32px; display: block; margin-bottom: 8px; }

.hv-footer { padding: 16px 20px 20px; }
</style>

<div class="hv-detail">

  <!-- Hero -->
  <div class="hv-hero">
    <div class="hv-hero-inner">
      <img class="hv-avatar" src="<?= $avatarUrl ?>" alt="avatar">
      <div style="flex:1; min-width:0;">
        <div class="hv-name"><?= htmlspecialchars($user['fullname']) ?></div>
        <div class="hv-meta">
          <?php if($user['email']): ?>
            <span><i class="fa fa-envelope-o"></i><?= htmlspecialchars($user['email']) ?></span>
          <?php endif; ?>
          <?php if($user['phone']): ?>
            <span><i class="fa fa-phone"></i><?= htmlspecialchars($user['phone']) ?></span>
          <?php endif; ?>
          <span><i class="fa fa-venus-mars"></i><?= $genderLabel ?></span>
        </div>
      </div>
      <div class="hv-id-badge">#<?= str_pad($user['id'], 4, '0', STR_PAD_LEFT) ?></div>
    </div>
  </div>

  <!-- Summary cards -->
  <div class="hv-cards">
    <div class="hv-card">
      <div class="hv-card-label">Khóa đã học</div>
      <div class="hv-card-value accent"><?= count($rows) ?></div>
    </div>
    <div class="hv-card">
      <div class="hv-card-label">Đã thanh toán</div>
      <div class="hv-card-value success"><?= $tongHocPhi > 0 ? number_format($tongDaDong) : '—' ?></div>
    </div>
    <div class="hv-card">
      <div class="hv-card-label">Còn nợ</div>
      <div class="hv-card-value <?= $tongNo > 0 ? 'danger' : 'success' ?>">
        <?= $tongHocPhi > 0 ? number_format($tongNo) : '—' ?>
      </div>
    </div>
  </div>

  <!-- Thông tin cá nhân -->
  <div class="hv-section" style="margin-top:20px;">
    <div class="hv-section-title"><i class="fa fa-user-o"></i> Thông tin cá nhân</div>
    <div class="hv-info-grid">
      <div class="hv-info-item">
        <label>Địa chỉ</label>
        <span><?= $user['address'] ? htmlspecialchars($user['address']) : '—' ?></span>
      </div>
      <div class="hv-info-item">
        <label>Ngày sinh</label>
        <span><?= $user['date'] && $user['date'] != '0000-00-00' ? $user['date'] : '—' ?></span>
      </div>
    </div>
  </div>

  <!-- Học phí -->
  <div class="hv-section" style="margin-top:20px;">
    <div class="hv-section-title"><i class="fa fa-credit-card"></i> Thông tin học phí</div>
    <div class="hv-table-wrap">
      <?php if(empty($rows)): ?>
        <div class="hv-empty">
          <i class="fa fa-folder-open-o"></i>
          Học viên chưa đăng ký khóa học nào.
        </div>
      <?php else: ?>
      <table class="hv-table">
        <thead>
          <tr>
            <th>#</th>
            <th>Khóa học</th>
            <th>Lớp</th>
            <th class="text-right">Học phí</th>
            <th class="text-right">Đã đóng</th>
            <th class="text-right">Còn nợ</th>
            <th class="text-center">Trạng thái</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($rows as $k => $r):
            $dk = $r['dk'];
            if($dk['payment_status'] == 2){
                $badge = "<span class='badge-status badge-done'>Hoàn tất</span>";
            } elseif($dk['payment_status'] == 1){
                $badge = "<span class='badge-status badge-partial'>Đang đóng</span>";
            } else {
                $badge = "<span class='badge-status badge-none'>Chưa đóng</span>";
            }
          ?>
          <tr>
            <td style="color:#a0aec0; font-weight:600;"><?= $k+1 ?></td>
            <td style="font-weight:500;"><?= htmlspecialchars($dk['course_name']) ?></td>
            <td style="color:#718096;"><?= htmlspecialchars($dk['class_name']) ?></td>
            <td class="text-right"><?= number_format($r['hocPhi']) ?> đ</td>
            <td class="text-right amount-ok"><?= number_format($r['daDong']) ?> đ</td>
            <td class="text-right <?= $r['conNo'] > 0 ? 'amount-neg' : 'amount-ok' ?>">
              <?= number_format($r['conNo']) ?> đ
            </td>
            <td class="text-center"><?= $badge ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="3">Tổng cộng</td>
            <td class="text-right"><?= number_format($tongHocPhi) ?> đ</td>
            <td class="text-right amount-ok"><?= number_format($tongDaDong) ?> đ</td>
            <td class="text-right <?= $tongNo > 0 ? 'amount-neg' : 'amount-ok' ?>">
              <?= number_format($tongNo) ?> đ
            </td>
            <td></td>
          </tr>
        </tfoot>
      </table>
      <?php endif; ?>
    </div>
  </div>

  <div class="hv-footer"></div>
</div>