<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>دسته بندی ها</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body { direction: rtl; text-align: right; }
        table th, table td { vertical-align: middle; }
    </style>
</head>
<body class="p-4">

<div class="container" id="my-vue-app">
    <h2 class="mb-4">دسته بندی ها</h2>
    <a href="<?= site_url('transactionscategories/create') ?>" class="btn btn-primary mb-3 ajax-link">افزودن دسته بندی جدید</a>

    <!-- فرم جستجو -->
    <form method="GET" class="mb-4 ajax-form">
        <div class="form-row">
            <div class="col-md-3 mb-2">
                <input type="text" name="title" class="form-control" placeholder="عنوان">
            </div>
            <div class="col-md-3 mb-2 d-flex">
                <button type="button" class="btn btn-primary mr-2" @click="getData()">جستجو</button>
                <a class="btn btn-secondary ajax-link">پاک کردن</a>
            </div>
        </div>
    </form>

    <!-- جدول دسته‌بندی‌ها -->
    <table class="table table-bordered table-striped text-right">
        <thead class="thead-dark">
            <tr>
                <th>عنوان</th>
                <th>عملیات</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="category in categories">
                <td v-html="category.title"></td>
                <td>
                    <a href="" class="btn btn-sm btn-warning ajax-link">ویرایش</a>
                    <a href="" onclick="return confirm('آیا مطمئن هستید می‌خواهید این دسته‌بندی را حذف کنید؟');" 
                        class="btn btn-sm btn-danger ajax-link">حذف</a>
                </td>
            </tr>
        </tbody>
    </table>

    <!-- Pagination -->
    <div>
        
    </div>

    <!-- <ul>
        <li v-for="category in categories" v-html="category.title"></li>
    </ul> -->


</div>

<script>
    const app = Vue.createApp({
        data() {
            return {
                categories: [],
            }
        },

        mounted(){
            this.getData();
        },

        methods: {

            async getData(){
                const url = "<?php echo site_url('Transactionscategories/search') ?>";

                try {
                    const response = await fetch(url);

                    if(!response.ok) {
                        alert('error, response is not ok');
                    }

                    const result = await response.json();

                    this.categories = result.cats;

                } catch (error) {
                    console.log(error.message)
                }
            },

        },
    }).mount("#my-vue-app");
</script>

</body>
</html>

