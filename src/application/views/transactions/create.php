<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>افزودن تراکنش</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <!-- jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>

    <!-- Persian Datepicker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/css/persian-datepicker.min.css">
    <script src="https://cdn.jsdelivr.net/npm/persian-date@1.1.0/dist/persian-date.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/js/persian-datepicker.min.js"></script>


    <style>
        body {
            direction: rtl;
            text-align: right;
        }
    </style>
</head>
<body class="p-4">

<div class="container">
    <h2 class="mb-4">افزودن تراکنش جدید</h2>

    <?= validation_errors('<div class="alert alert-danger">', '</div>'); ?>

    <form action="<?= site_url('transactions/create') ?>" method="post" class="ajax-link">
        <div class="form-group">
            <label>عنوان</label>
            <input type="text" name="title" class="form-control" value="<?= set_value('title') ?>">
        </div>

        <div class="form-group">
            <label>مبلغ</label>
            <input type="number" step="0.01" name="amount" class="form-control" value="<?= set_value('amount') ?>">
        </div>

        <div class="form-group">
            <label>نوع</label>
            <select name="type" class="form-control">
                <option value="income" <?= set_select('type', 'income') ?>>دریافت</option>
                <option value="expense" <?= set_select('type', 'expense') ?>>پرداخت</option>
            </select>
        </div>

        <div class="form-group">
            <label>دسته‌بندی</label>
            <select name="category_id" class="form-control">
                <?php foreach($categories as $c): ?>
                    <option value="<?= $c->id ?>" <?= set_select('category_id', $c->id) ?>><?= $c->title ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <?php
        $transaction_date_miladi = set_value('transaction_date');
        $transaction_date_display = '';
        if(!empty($transaction_date_miladi)){
            // تبدیل میلادی به شمسی برای نمایش
            $parts = explode('-', $transaction_date_miladi);
            $gy = (int)$parts[0]; $gm = (int)$parts[1]; $gd = (int)$parts[2];
            $shamsi = gregorian_to_jalali($gy, $gm, $gd); // از jdf helper یا فانکشن خودت
            $transaction_date_display = implode('/', $shamsi); // YYYY/MM/DD
        }
        ?>
        <div class="form-group">
            <label>تاریخ</label>
            <input type="text" id="transaction_date_display" class="form-control" value="<?= $transaction_date_display ?>">
            <input type="hidden" name="transaction_date" id="transaction_date" value="<?= $transaction_date_miladi ?>">
        </div>

        <button type="submit" class="btn btn-success">ذخیره</button>
        <a href="<?= site_url('transactions') ?>" class="btn btn-secondary ajax-link">بازگشت</a>
    </form>
</div>

<script>
$(document).ready(function() {
    $("#transaction_date_display").persianDatepicker({
        format: 'YYYY/MM/DD',         // نمایش شمسی
        autoClose: true,
        observer: true,
        initialValue: false,
        calendarType: 'persian',
        altField: '#transaction_date', // فیلد مخفی که به سرور می‌رود
        altFormat: 'YYYY-MM-DD'       // فرمت میلادی برای دیتابیس
    });

    // اگر می‌خوای هنگام ویرایش مقدار قبلی را نمایش دهی
    var existingDate = $("#transaction_date").val();
    if(existingDate) {
        // تبدیل میلادی به شمسی
        var parts = existingDate.split('-'); // YYYY-MM-DD
        var gy = parseInt(parts[0]), gm = parseInt(parts[1]), gd = parseInt(parts[2]);

        // استفاده از PersianDate برای تبدیل میلادی به شمسی
        var date = new persianDate([gy, gm, gd]);
        var shamsiStr = date.format("YYYY/MM/DD");
        $("#transaction_date_display").val(shamsiStr);
    }
});


</script>

</body>
</html>
