<!DOCTYPE html>
<html lang="fa">
<head>
<meta charset="UTF-8">
<title>داشبورد کاربر - داینامیک</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-rtl/3.4.0/css/bootstrap-rtl.min.css" rel="stylesheet">

<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- لینک فونت فارسی Vazir -->
<link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazir-font@30.1.0/dist/font-face.css" rel="stylesheet" type="text/css" />

    
<style>
body {
    margin: 0;
    font-family: 'Vazir', IRANSans, sans-serif !important;
    direction: rtl;
    background: #f5f6fa;
}

/* ----- Sidebar ----- */
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
    cursor: pointer;
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

/* ----- Main Content ----- */
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

    <a class="menu-item active" data-url="<?= site_url('dashboard/home_ajax'); ?>">🏠 داشبورد</a>
	<a class="menu-item" data-url="<?= site_url('dashboard/profile_ajax'); ?>">👤 پروفایل</a>
	<a class="menu-item" data-url="<?= site_url('transactions'); ?>">⚙️ تراکنش ها</a>
	<a class="menu-item" data-url="<?= site_url('transactionscategories'); ?>">✉️ دسته بندی ها</a>

    <a class="logout" href="<?php echo site_url('Auth/logout'); ?>">🚪 خروج</a>
</div>

<!-- Main Content -->
<div class="main">

    <!-- Header -->
    <div class="header">
        خوش آمدی، <span style="color:#273c75;">
            <?= $this->session->userdata('username'); ?>
        </span> 👋
    </div>

    <!-- Dynamic Content Box -->
    <div id="content-area">
        <!-- محتوای اولیه -->
        <!-- <h2>داشبورد</h2>
        <p>در این بخش می‌تونی گزارش‌ها و بخش‌های مختلف را ببینی.</p> -->
        <div class="container">

            <h2 class="mb-4">خلاصه وضعیت مالی</h2>

            <div class="row">

                <!-- درآمد -->
                <div class="col-md-4">
                    <div class="p-3 text-center bg-success text-white rounded shadow-sm">
                        <h4>کل درآمد</h4>
                        <h2><?= number_format($total_income); ?> تومان</h2>
                    </div>
                </div>

                <!-- هزینه -->
                <div class="col-md-4">
                    <div class="p-3 text-center bg-danger text-white rounded shadow-sm">
                        <h4>کل هزینه</h4>
                        <h2><?= number_format($total_expense); ?> تومان</h2>
                    </div>
                </div>

                <!-- موجودی -->
                <div class="col-md-4">
                    <div class="p-3 text-center bg-primary text-white rounded shadow-sm">
                        <h4>موجودی فعلی</h4>
                        <h2><?= number_format($balance); ?> تومان</h2>
                    </div>
                </div>

            </div>

        </div>

        <br><br>

        <h3 class="mt-4">نمودار میله‌ای درآمد و هزینه ماهانه</h3>

        <canvas id="incomeExpenseChart" style="max-height:350px;"></canvas>

    </div>

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function(){

    /** =======================
     * بارگذاری صفحات منو با AJAX
     * ======================= */
    $(".menu-item").click(function() {
    $(".menu-item").removeClass("active");
    $(this).addClass("active");

    let url = $(this).data("url");
    loadPage(url);
});


    /** =======================
     * تابع loadPage
     * ======================= */
    function loadPage(url, method = 'GET', data = null){
        $("#content-area").html("<p class='p-3'>در حال بارگذاری...</p>");

        $.ajax({
            url: url,
            method: method,
            data: data,
            processData: (method === 'POST') ? false : true,
            contentType: (method === 'POST') ? false : 'application/x-www-form-urlencoded; charset=UTF-8',
            success: function(response){
                $("#content-area").html(response);
            },
            error: function(){
                $("#content-area").html("<p class='text-danger p-3'>خطا در بارگذاری</p>");
            }
        });
    }

    /** =======================
     * لینک‌های داخلی AJAX
     * ======================= */
    $(document).on("click", "#content-area a.ajax-link", function(e){
        e.preventDefault();
        let url = $(this).attr("href");
        if(!url) return;
        loadPage(url);
    });

    /** =======================
     * Pagination AJAX
     * ======================= */
    $(document).on("click", "#content-area .pagination a", function(e){
        e.preventDefault();
        let url = $(this).attr("href");
        if(!url) return;
        loadPage(url);
    });

    /** =======================
     * ارسال فرم‌ها AJAX (GET و POST)
     * ======================= */
    $(document).on("submit", "#content-area form.ajax-form, #content-area form", function(e){
        e.preventDefault();
        let form = $(this);
        let action = form.attr("action") || window.location.href;
        let method = (form.attr("method") || 'POST').toUpperCase();

        if(method === "GET"){
            // فرم جستجو یا GET
            let query = form.serialize();
            loadPage(action + "?" + query, 'GET', null);
        } else {
            // فرم POST
            let formData = new FormData(this);
            loadPage(action, 'POST', formData);
        }
    });

});


</script>

<!-- Bootstrap JS -->
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

<script>
function toPersianDigits(num) {
    return num.toString().replace(/\d/g, d => "۰۱۲۳۴۵۶۷۸۹"[d]);
}

$(document).ready(function(){

    $.get("<?= site_url('dashboard/chart_data'); ?>", function(data){

        let chartData = JSON.parse(data);

        const ctx = document.getElementById('incomeExpenseChart').getContext('2d');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [
                    {
                        label: 'درآمد',
                        data: chartData.income,
                        borderColor: 'green',
                        fill: false,
                        tension: 0.3
                    },
                    {
                        label: 'هزینه',
                        data: chartData.expense,
                        borderColor: 'red',
                        fill: false,
                        tension: 0.3
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        labels: { font: { size: 14 } }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return toPersianDigits(value);
                            }
                        }
                    }
                }
            }
        });

    });

});
</script>

<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
</body>
</html>
