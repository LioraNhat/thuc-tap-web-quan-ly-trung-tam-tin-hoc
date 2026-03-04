<?php
    // Đảm bảo đường dẫn require chính xác theo cấu trúc thư mục của bạn
    require_once $path.'../commons/utils.php';
    
    $id = $_SESSION['login']['role'];
    
    // Lấy danh sách menu dựa trên role của người dùng
    $sql = "select * from role where user_id = '$id'";
    $user = getSimpleQuery($sql, true);
?>
<aside class="main-sidebar">
    <section class="sidebar">
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= SITE_URL . $_SESSION['login']['avatar'] ?>" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p><?= $_SESSION['login']['fullname']; ?></p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">THANH ĐIỀU HƯỚNG</li>
            <li class="<?= (strpos($_SERVER['REQUEST_URI'], 'dashboard') !== false) ? 'active' : '' ?>">
                <a href="<?= $ADMIN_URL; ?>">
                    <i class="fa fa-dashboard"></i> <span>Trang chủ / Thống kê</span>
                </a>
            </li>

            <?php if($user): ?>
                <?php foreach($user as $row): 
                    // Kiểm tra trang hiện tại để hiển thị trạng thái active
                    $activeClass = (strpos($_SERVER['REQUEST_URI'], $row['link']) !== false) ? 'active' : '';
                ?>
                <li class="<?= $activeClass ?>">
                    <a href="<?= $ADMIN_URL . $row['link']; ?>">
                        <i class="<?= $row['icons']; ?>"></i> 
                        <span><?= $row['name']; ?></span>
                    </a>
                </li>
                <?php endforeach; ?>
            <?php endif; ?>

            <li class="header">HỆ THỐNG</li>
            <li>
                <a href="<?= SITE_URL ?>logout.php">
                    <i class="fa fa-sign-out"></i> <span>Đăng xuất</span>
                </a>
            </li>
        </ul>
    </section>
</aside>