<!DOCTYPE html>
<html lang="fa">
<head>
<meta charset="UTF-8">
<title>داشبورد کاربر</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-rtl/3.4.0/css/bootstrap-rtl.min.css" rel="stylesheet">

<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazir-font@30.1.0/dist/font-face.css" rel="stylesheet" />
<link rel="stylesheet" href="https://unpkg.com/vue3-persian-datepicker@1.2.3/dist/vue3-persian-datepicker.css">
    
<style>
body {
    margin: 0;
    font-family: 'Vazir', IRANSans, sans-serif !important;
    direction: rtl;
    background: #f5f6fa;
}

/* Sidebar */
.sidebar {
    width: 240px;
    height: 100vh;
    background: #2f3640;
    position: fixed;
    top: 0;
    right: 0;
    padding-top: 40px;
    color: #fff;
}
.sidebar h3 {
    text-align: center;
    margin-bottom: 30px;
    font-weight: 600;
}

.sidebar a {
    display: block;
    padding: 14px 25px;
    color: #dcdde1;
    text-decoration: none;
    font-size: 16px;
    margin-bottom: 5px;
    transition: 0.2s;
}
.sidebar a:hover,
.sidebar a.active {
    background: #40739e;
    color: #fff;
}
.logout {
    color: #ff6b6b !important;
}
.logout:hover {
    background: #ff6b6b;
    color: white !important;
}

/* Main */
.main {
    margin-right: 240px;
    padding: 20px;
}

#content-area {
    margin-top: 25px;
    padding: 25px;
    background: white;
    border-radius: 10px;
    min-height: 400px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.06);
}
</style>
</head>

<body>

<!-- Sidebar -->
<div class="sidebar">
    <h3>پنل کاربری</h3>

    <a href="<?= site_url('dashboard'); ?>" class="menu-item <?= ($active_menu == 'home' ? 'active' : ''); ?>">🏠 داشبورد</a>
     <a href="<?= site_url('dashboard/profile'); ?>" class="menu-item <?= ($active_menu == 'profile' ? 'active' : ''); ?>">👤 پروفایل</a>
    <a href="<?= site_url('dashboard/transactions'); ?>" class="menu-item <?= ($active_menu == 'transactions' ? 'active' : ''); ?>">⚙️ تراکنش ها</a>
    <a href="<?= site_url('dashboard/categories'); ?>" class="menu-item <?= ($active_menu == 'categories' ? 'active' : ''); ?>">✉️ دسته بندی ها</a>

    <a class="logout" href="<?= site_url('Auth/logout'); ?>">🚪 خروج</a>
</div>

<!-- Main Content -->
<div class="main">

    <div class="header">
        خوش آمدید،
        <span style="color:#273c75;">
            <?= $this->session->userdata('username'); ?>
        </span> 👋
    </div>

    <!--  این باکس در تمام صفحه‌ها ثابت است -->
    <div id="content-area">
        <!-- محتوای اختصاصی هر صفحه باید از کنترلر داخل این باکس نمایش داده شود -->
        <?= $content; ?>
    </div>

</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


</body>
</html>