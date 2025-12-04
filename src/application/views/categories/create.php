<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>افزودن دسته بندی</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            direction: rtl;
            text-align: right;
        }
    </style>
</head>
<body class="p-4">

<div class="container">
    <h2 class="mb-4">افزودن دسته بندی جدید</h2>

    <?= validation_errors('<div class="alert alert-danger">', '</div>'); ?>

    <form action="<?= site_url('transactionscategories/create') ?>" method="post" class="ajax-link">
        <div class="form-group">
            <label>عنوان</label>
            <input type="text" name="title" class="form-control" value="<?= set_value('title') ?>">
        </div>

        <button type="submit" class="btn btn-success">ذخیره</button>
        <a href="<?= site_url('transactionscategories') ?>" class="btn btn-secondary ajax-link">بازگشت</a>
    </form>
</div>

</body>
</html>
