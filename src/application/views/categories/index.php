<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>مدیریت دسته‌بندی‌ها</title>

    <script src="https://unpkg.com/vue@3"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
        body { direction: rtl; text-align: right; }
        .pointer { cursor: pointer; }
        .modal-backdrop { opacity: .3 !important; }
    </style>
</head>

<body class="p-4">

<div class="container" id="app">

    <h2 class="mb-4">مدیریت دسته‌بندی‌ها</h2>

    <!-- جستجو -->
    <div class="mb-3">
        <input type="text" v-model="filters.title" class="form-control w-25 d-inline-block"
               placeholder="جستجوی عنوان...">
        <button class="btn btn-primary" @click="getData(1)">جستجو</button>
        <button class="btn btn-secondary" @click="clearSearch()">پاک کردن</button>
    </div>

    <!-- افزودن -->
    <button class="btn btn-success mb-3" @click="showAddModal = true">افزودن</button>

    <!-- جدول -->
    <table class="table table-bordered table-striped">
        <thead class="thead-dark">
            <tr>
                <th>عنوان</th>
                <th width="130">عملیات</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="item in categories" :key="item.id">
                <td>{{ item.title }}</td>
                <td>
                    <button class="btn btn-warning btn-sm" @click="startEdit(item)">ویرایش</button>
                    <button class="btn btn-danger btn-sm" @click="remove(item.id)">حذف</button>
                </td>
            </tr>
        </tbody>
    </table>

    <!-- صفحه‌بندی -->
    <nav v-if="pagesCount > 1">
    <ul class="pagination">

        <!-- اولین صفحه -->
        <li class="page-item" :class="{ disabled: page === 1 }">
            <a class="page-link pointer" @click="goPage(1)">« اول</a>
        </li>

        <!-- قبلی -->
        <li class="page-item" :class="{ disabled: page === 1 }">
            <a class="page-link pointer" @click="goPage(page - 1)">‹ قبلی</a>
        </li>

        <!-- شماره صفحات -->
        <li v-for="p in paginationPages" :key="p"
            :class="['page-item', { active: p === page, disabled: p === '...' }]">

            <a v-if="p !== '...'" class="page-link pointer" @click="goPage(p)">
                {{ p }}
            </a>

            <span v-else class="page-link">
                {{ p }}
            </span>
        </li>

        <!-- بعدی -->
        <li class="page-item" :class="{ disabled: page === pagesCount }">
            <a class="page-link pointer" @click="goPage(page + 1)">بعدی ›</a>
        </li>

        <!-- آخرین صفحه -->
        <li class="page-item" :class="{ disabled: page === pagesCount }">
            <a class="page-link pointer" @click="goPage(pagesCount)">آخر »</a>
        </li>

    </ul>
    </nav>


    <!-- Modal افزودن -->
    <div v-if="showAddModal" class="modal-backdrop fade show" style="display:block"></div>
    <div v-if="showAddModal" class="modal fade show" style="display:block;">
        <div class="modal-dialog">
            <div class="modal-content p-3">
                <h4 class="mb-3">افزودن دسته‌بندی</h4>
                <input class="form-control mb-3" v-model="newTitle" placeholder="عنوان دسته‌بندی">
                <button class="btn btn-success" @click="create()">ذخیره</button>
                <button class="btn btn-secondary" @click="showAddModal = false">بستن</button>
            </div>
        </div>
    </div>

    <!-- Modal ویرایش -->
    <div v-if="showEditModal" class="modal-backdrop fade show" style="display:block"></div>
    <div v-if="showEditModal" class="modal fade show" style="display:block;">
        <div class="modal-dialog">
            <div class="modal-content p-3">
                <h4 class="mb-3">ویرایش دسته‌بندی</h4>
                <input class="form-control mb-3" v-model="editTitle">
                <button class="btn btn-success" @click="update()">ذخیره تغییرات</button>
                <button class="btn btn-secondary" @click="showEditModal = false">بستن</button>
            </div>
        </div>
    </div>

</div>

<script>
const app = Vue.createApp({
    data(){
        return {
            categories: [],
            page: 1,
            total_rows: 0,
            page_size: 10,
            filters: { title: "" },

            // افزودن
            showAddModal: false,
            newTitle: "",

            // ویرایش
            showEditModal: false,
            editId: null,
            editTitle: "",
        }
    },

    computed:{
        pagesCount(){
            return Math.ceil(this.total_rows / this.page_size);
        },
        paginationPages(){
            const total = this.pagesCount;
            const current = this.page;
            let pages = [];

            if (total <= 7) {
                // تمام صفحات
                for (let i = 1; i <= total; i++) pages.push(i);
                return pages;
            }

            // صفحات همیشه
            pages.push(1);
            pages.push(2);

            // اگر صفحه بزرگتر از 4 است → سه نقطه اول
            if (current > 4) pages.push("...");

            // نمایش صفحات اطراف صفحه فعلی
            const start = Math.max(3, current - 1);
            const end   = Math.min(total - 2, current + 1);

            for (let i = start; i <= end; i++) pages.push(i);

            // سه نقطه دوم
            if (current < total - 3) pages.push("...");

            // صفحات آخر
            pages.push(total - 1);
            pages.push(total);

            return pages;
}


    },

    mounted(){
        this.getData(1);
    },

    methods:{

        async getData(page=1){
            this.page = page;

            const params = new URLSearchParams({
                page: this.page,
                title: this.filters.title
            });

            const res = await fetch("<?= site_url('Transactionscategories/search') ?>?" + params);
            const data = await res.json();

            this.categories  = data.cats;
            this.total_rows  = data.total_rows;
            this.page        = data.page;
            this.page_size   = data.page_size;
        },
        goPage(p){
            if (p < 1 || p > this.pagesCount || p === this.page) return;
            this.getData(p);
        },

        clearSearch(){
            this.filters.title = "";
            this.getData(1);
        },

        async create(){
            if (!this.newTitle.trim()) return alert("عنوان وارد نشده");

            const form = new FormData();
            form.append("title", this.newTitle);

            await fetch("<?= site_url('Transactionscategories/api_create') ?>", {
                method: "POST",
                body: form
            });

            this.newTitle = "";
            this.showAddModal = false;
            this.getData(this.page);
        },

        startEdit(item){
            this.editId = item.id;
            this.editTitle = item.title;
            this.showEditModal = true;
        },

        async update(){
            const form = new FormData();
            form.append("title", this.editTitle);

            await fetch(`<?= site_url('Transactionscategories/api_update/') ?>${this.editId}`, {
                method: "POST",
                body: form
            });

            this.showEditModal = false;
            this.getData(this.page);
        },

        async remove(id){
            if (!confirm("آیا مطمئن هستید؟")) return;

            await fetch(`<?= site_url('Transactionscategories/api_delete/') ?>${id}`);
            this.getData(this.page);
        }

    }
}).mount("#app");
</script>

</body>
</html>
