<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>ویرایش تراکنش</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <!-- jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>

    <!-- Persian Datepicker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/css/persian-datepicker.min.css">
    <script src="https://cdn.jsdelivr.net/npm/persian-date@1.1.0/dist/persian-date.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/js/persian-datepicker.min.js"></script>
    <style>
        body { direction: rtl; text-align: right; }
    </style>
</head>
<body class="p-4">
<div class="container">
    <h2 class="mb-4">ویرایش تراکنش</h2>
    <?= validation_errors('<div class="alert alert-danger">', '</div>'); ?>

    <form action="<?= site_url('transactions/edit/'.$transaction->id) ?>" method="post" class="ajax-link">
        <div class="form-group">
            <label>عنوان</label>
            <input type="text" name="title" class="form-control" value="<?= set_value('title', $transaction->title) ?>">
        </div>

        <div class="form-group">
            <label>مبلغ</label>
            <input type="number" step="0.01" name="amount" class="form-control" value="<?= set_value('amount', $transaction->amount) ?>">
        </div>

        <div class="form-group">
            <label>نوع</label>
            <select name="type" class="form-control">
                <option value="income" <?= set_select('type', 'income', $transaction->type=='income') ?>>درآمد</option>
                <option value="expense" <?= set_select('type', 'expense', $transaction->type=='expense') ?>>هزینه</option>
            </select>
        </div>

        <div class="form-group">
            <label>دسته‌بندی</label>
            <select name="category_id" class="form-control">
                <?php foreach($categories as $c): ?>
                    <option value="<?= $c->id ?>" <?= set_select('category_id', $c->id, $transaction->category_id==$c->id) ?>><?= $c->title ?></option>
                <?php endforeach; ?>
            </select>
        </div>

    <?php
    $transaction_date_miladi = set_value(
        'transaction_date',
        $transaction->transaction_date
    );

    $transaction_date_display = '';

    if(!empty($transaction_date_miladi)){
        $parts = explode('-', $transaction_date_miladi);
        if(count($parts) === 3){
            $gy = (int)$parts[0];
            $gm = (int)$parts[1];
            $gd = (int)$parts[2];

            list($jy, $jm, $jd) = gregorian_to_jalali($gy, $gm, $gd);

            $transaction_date_display = sprintf("%04d/%02d/%02d", $jy, $jm, $jd);
        }
    }
    ?>
    <div class="form-group">
        <label>تاریخ</label>
        <input type="text" name="transaction_date_display" class="form-control transaction-date-display" value="<?= $transaction_date_display ?>" autocomplete="off">
        <input type="hidden" name="transaction_date" class="transaction-date" value="<?= $transaction_date_miladi ?>">
    </div>


        <button type="submit" class="btn btn-success">ذخیره تغییرات</button>
        <a href="<?= site_url('transactions') ?>" class="btn btn-secondary ajax-link">بازگشت</a>
    </form>
</div>
<script>
$(document).ready(function() {
    $(".transaction-date-display").each(function() {
        var $display = $(this);
        var $hidden = $display.siblings(".transaction-date");

        // مقدار شمسی که PHP تبدیل کرده
        var existingShamsi = $display.val();

        $display.persianDatepicker({
            format: 'YYYY/MM/DD',
            autoClose: true,
            observer: true,
            //initialValue: !!existingShamsi,  // فعال فقط وقتی مقدار موجود باشد
            initialValueType: 'persian',
			initialValue: true,
            calendarType: 'persian',
            altField: $hidden,
            altFormat: 'YYYY-MM-DD',
            onInit: function() {
                if(existingShamsi){
                    $display.val(existingShamsi);
                }
            }
        });
    });
});

</script>
</body>
</html>