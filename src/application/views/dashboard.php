<!DOCTYPE html>
<html lang="fa">

<head>
    <meta charset="UTF-8">
    <title>داشبورد کاربر</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-rtl/3.4.0/css/bootstrap-rtl.min.css" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- لینک فونت فارسی Vazir -->
    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazir-font@30.1.0/dist/font-face.css" rel="stylesheet"
        type="text/css" />

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

        /* Main Content */
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
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.06);
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h3>پنل کاربری</h3>

        <a class="<?= ($active_page == 'home') ? 'active' : '' ?>" href="<?= site_url('dashboard/home'); ?>">🏠
            داشبورد</a>
        <a class="<?= ($active_page == 'profile') ? 'active' : '' ?>" href="<?= site_url('dashboard/profile'); ?>">👤
            پروفایل</a>
        <a class="<?= ($active_page == 'transactions') ? 'active' : '' ?>" href="<?= site_url('transactions'); ?>">⚙️
            تراکنش ها</a>
        <a class="<?= ($active_page == 'categories') ? 'active' : '' ?>"
            href="<?= site_url('transactionscategories'); ?>">✉️ دسته بندی ها</a>

        <a class="logout" href="<?= site_url('Auth/logout'); ?>">🚪 خروج</a>
    </div>

    <!-- Main Content -->
    <div class="main">

        <!-- Header -->
        <div class="header">
            خوش آمدی، <span style="color:#273c75;"><?= $this->session->userdata('username'); ?></span> 👋
        </div>

        <!-- Content Area -->
        <div id="content-area">
            <?php
            // محتوای هر صفحه
            if (isset($content)) {
                echo $content;
            } else {
                echo "<h2>داشبورد</h2><p>در این بخش می‌تونی گزارش‌ها و بخش‌های مختلف را ببینی.</p>";
            }
            ?>
        </div>

    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <script>
        function toPersianDigits(num) {
            return num.toString().replace(/\d/g, d => "۰۱۲۳۴۵۶۷۸۹"[d]);
        }

        // فقط اگر عنصر canvas موجود بود چارت ساخته شود
        <?php if (isset($chart_data)): ?>
            document.addEventListener("DOMContentLoaded", function () {
                const canvas = document.getElementById('incomeExpenseChart');
                if (canvas) {
                    const ctx = canvas.getContext('2d');
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: <?= json_encode($chart_data['labels']); ?>,
                            datasets: [
                                { label: 'درآمد', data: <?= json_encode($chart_data['income']); ?>, borderColor: 'green', fill: false, tension: 0.3 },
                                { label: 'هزینه', data: <?= json_encode($chart_data['expense']); ?>, borderColor: 'red', fill: false, tension: 0.3 }
                            ]
                        },
                        options: {
                            responsive: true,
                            plugins: { legend: { labels: { font: { size: 14 } } } },
                            scales: { y: { beginAtZero: true, ticks: { callback: function (v) { return toPersianDigits(v); } } } }
                        }
                    });
                }
            });
        <?php endif; ?>
    </script>

</body>

</html>