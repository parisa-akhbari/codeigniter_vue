<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>تراکنش‌ها</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>

    <!-- Persian Datepicker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/css/persian-datepicker.min.css">
    <script src="https://cdn.jsdelivr.net/npm/persian-date@1.1.0/dist/persian-date.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/js/persian-datepicker.min.js"></script>

    <style>
        body { direction: rtl; text-align: right; }
        table th, table td { vertical-align: middle; }
    </style>
</head>
<body class="p-4">

<div class="container">
    <h2 class="mb-4">تراکنش‌ها</h2>
    <a href="<?= site_url('transactions/create') ?>" class="btn btn-primary mb-3 ajax-link">افزودن تراکنش جدید</a>

    <!-- فرم جستجو -->
    <form method="GET" class="mb-4 ajax-form" action="<?= site_url('transactions/index'); ?>">
        <div class="form-row">
    <div class="col-md-3 mb-2">
        <input type="text" name="title" class="form-control" placeholder="عنوان" value="<?= $filters['title'] ?? '' ?>">
    </div>
    <div class="col-md-2 mb-2">
        <select name="type" class="form-control">
            <option value="">همه نوع‌ها</option>
            <option value="income" <?= isset($filters['type']) && $filters['type']=='income'?'selected':'' ?>>درآمد</option>
            <option value="expense" <?= isset($filters['type']) && $filters['type']=='expense'?'selected':'' ?>>هزینه</option>
        </select>
    </div>
    <div class="col-md-2 mb-2">
    <input type="text" id="start_date_display"
           class="form-control"
           placeholder="از تاریخ"
           value="<?= $filters['start_date_shamsi'] ?? '' ?>">
    <input type="hidden" name="start_date" id="start_date"
           value="<?= $filters['start_date_shamsi'] ?? '' ?>">
	</div>

	<div class="col-md-2 mb-2">
		<input type="text" id="end_date_display"
			   class="form-control"
			   placeholder="تا تاریخ"
			   value="<?= $filters['end_date_shamsi'] ?? '' ?>">
		<input type="hidden" name="end_date" id="end_date"
			   value="<?= $filters['end_date_shamsi'] ?? '' ?>">
	</div>


    <div class="col-md-3 mb-2 d-flex">
        <button type="submit" class="btn btn-primary mr-2">جستجو</button>
        <a href="<?= site_url('transactions/index') ?>" class="btn btn-secondary ajax-link">پاک کردن</a>
    </div>
</div>

    </form>

    <!-- جدول تراکنش‌ها -->
    <table class="table table-bordered table-striped text-right">
        <thead class="thead-dark">
            <tr>
                <th>عنوان</th>
                <th>مبلغ</th>
                <th>نوع</th>
                <th>دسته‌بندی</th>
                <th>تاریخ</th>
                <th>عملیات</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($transactions)): ?>
                <?php foreach($transactions as $t): ?>
                    <tr>
                        <td><?= $t->title ?></td>
                        <td><?= number_format($t->amount) ?></td>
                        <td><?= $t->type == 'income' ? 'درآمد' : 'هزینه' ?></td>
                        <td><?= $t->category_title ?></td>
                        <td>
                            <?php
                                if(!empty($t->transaction_date)) {
                                    list($gy, $gm, $gd) = explode('-', $t->transaction_date);
                                    list($jy, $jm, $jd) = gregorian_to_jalali((int)$gy, (int)$gm, (int)$gd);
                                    echo sprintf("%04d/%02d/%02d", $jy, $jm, $jd);
                                }
                            ?>
                        </td>
                        <td>
                            <a href="<?= site_url('transactions/edit/'.$t->id) ?>" class="btn btn-sm btn-warning ajax-link">ویرایش</a>
                            <a href="<?= site_url('transactions/delete/'.$t->id) ?>" 
                               onclick="return confirm('آیا مطمئن هستید؟');" 
                               class="btn btn-sm btn-danger ajax-link">حذف</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="6" class="text-center">هیچ تراکنشی یافت نشد</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Pagination -->
    <div>
        <?= $pagination ?? '' ?>
    </div>
</div>

<script>
$(document).ready(function() {

    function initDatePicker(displayId, hiddenId, value) {

        $(displayId).persianDatepicker({
            format: 'YYYY/MM/DD',
            altField: hiddenId,
            altFormat: 'YYYY/MM/DD',
            initialValue: false,
            autoClose: true
        });

        // اگر مقدار قبلی وجود داشت → تنظیم کن
        if (value && value !== "") {
            $(displayId).val(value);
            $(hiddenId).val(value);
        } else {
            $(displayId).val("");
            $(hiddenId).val("");
        }
    }

    initDatePicker("#start_date_display", "#start_date", "<?= $filters['start_date_shamsi'] ?? '' ?>");
    initDatePicker("#end_date_display", "#end_date", "<?= $filters['end_date_shamsi'] ?? '' ?>");
});

</script>

</body>
</html>
