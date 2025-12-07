
<div class="container py-4" id="app">
    <h2 class="mb-4 text-primary">مدیریت دسته‌بندی‌ها</h2>

    <!-- جستجو -->
    <div class="mb-4">
        <input type="text" v-model="filters.title" class="form-control w-25 d-inline-block"
               placeholder="جستجوی عنوان...">
        <button class="btn btn-primary me-2" @click="getData(1)">جستجو</button>
        <button class="btn btn-secondary" @click="clearSearch()">پاک کردن</button>
    </div>

    <!-- افزودن -->
    <button class="btn btn-success mb-4" @click="showAddModal = true">
        <i class="fas fa-plus"></i> افزودن دسته‌بندی
    </button>

    <!-- جدول -->
    <div class="table-responsive">
        <table class="table table-hover table-bordered align-middle">
            <thead class="table-dark">
                <tr>
                    <th>عنوان</th>
                    <th class="text-center" width="180">عملیات</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="item in categories" :key="item.id">
                    <td>{{ item.title }}</td>
                    <td class="text-center">
                        <button class="btn btn-warning btn-sm me-2" @click="startEdit(item)">ویرایش</button>
                        <button class="btn btn-danger btn-sm" @click="remove(item.id)">حذف</button>
                    </td>
                </tr>
                <tr v-if="categories.length === 0">
                    <td colspan="2" class="text-center py-4 text-muted">دسته‌بندی یافت نشد</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- صفحه‌بندی -->
    <nav v-if="pagesCount > 1" class="mt-4">
        <ul class="pagination justify-content-center">
            <li class="page-item" :class="{ disabled: page === 1 }">
                <a class="page-link" @click="goPage(1)">« اول</a>
            </li>
            <li class="page-item" :class="{ disabled: page === 1 }">
                <a class="page-link" @click="goPage(page - 1)">‹ قبلی</a>
            </li>

            <li v-for="p in paginationPages" :key="p"
                :class="['page-item', { active: p === page, disabled: p === '...' }]">
                <a v-if="p !== '...'" class="page-link" @click="goPage(p)">{{ p }}</a>
                <span v-else class="page-link">{{ p }}</span>
            </li>

            <li class="page-item" :class="{ disabled: page === pagesCount }">
                <a class="page-link" @click="goPage(page + 1)">بعدی ›</a>
            </li>
            <li class="page-item" :class="{ disabled: page === pagesCount }">
                <a class="page-link" @click="goPage(pagesCount)">آخر »</a>
            </li>
        </ul>
    </nav>

    <!-- Modal افزودن -->
    <div v-if="showAddModal" class="modal fade show" style="display: block;" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">افزودن دسته‌بندی جدید</h5>
                    <button type="button" class="btn-close" @click="showAddModal = false"></button>
                </div>
                <div class="modal-body">
                    <input type="text" class="form-control" v-model="newTitle" placeholder="عنوان دسته‌بندی">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success" @click="create()">ذخیره</button>
                    <button class="btn btn-secondary" @click="showAddModal = false">بستن</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal ویرایش -->
    <div v-if="showEditModal" class="modal fade show" style="display: block;" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">ویرایش دسته‌بندی</h5>
                    <button type="button" class="btn-close" @click="showEditModal = false"></button>
                </div>
                <div class="modal-body">
                    <input type="text" class="form-control" v-model="editTitle">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success" @click="update()">ذخیره تغییرات</button>
                    <button class="btn btn-secondary" @click="showEditModal = false">بستن</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Backdrop برای مودال‌ها -->
    <div v-if="showAddModal || showEditModal" class="modal-backdrop fade show"></div>
</div>

<!-- Vue 3 + اسکریپت مدیریت دسته‌بندی‌ها -->
<script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>
<script>
const { createApp } = Vue;

createApp({
    data() {
        return {
            categories: [],
            page: 1,
            total_rows: 0,
            page_size: 10,
            filters: { title: "" },

            // مودال افزودن
            showAddModal: false,
            newTitle: "",

            // مودال ویرایش
            showEditModal: false,
            editId: null,
            editTitle: "",
        }
    },
    computed: {
        pagesCount() {
            return Math.ceil(this.total_rows / this.page_size);
        },
        paginationPages() {
            const total = this.pagesCount;
            const current = this.page;
            let pages = [];

            if (total <= 7) {
                for (let i = 1; i <= total; i++) pages.push(i);
                return pages;
            }

            pages.push(1, 2);
            if (current > 4) pages.push("...");
            const start = Math.max(3, current - 1);
            const end = Math.min(total - 2, current + 1);
            for (let i = start; i <= end; i++) pages.push(i);
            if (current < total - 3) pages.push("...");
            pages.push(total - 1, total);

            return pages;
        }
    },
    mounted() {
        this.getData(1);
    },
    methods: {
        async getData(page = 1) {
            this.page = page;
            const params = new URLSearchParams({
                page: this.page,
                title: this.filters.title
            });

            const res = await fetch("<?= site_url('Transactionscategories/search') ?>?" + params);
            const data = await res.json();

            this.categories = data.cats;
            this.total_rows = data.total_rows;
            this.page = data.page;
            this.page_size = data.page_size;
        },

        goPage(p) {
            if (p < 1 || p > this.pagesCount || p === this.page) return;
            this.getData(p);
        },

        clearSearch() {
            this.filters.title = "";
            this.getData(1);
        },

        async create() {
            if (!this.newTitle.trim()) return alert("عنوان را وارد کنید");
            const form = new FormData();
            form.append("title", this.newTitle.trim());

            await fetch("<?= site_url('Transactionscategories/api_create') ?>", {
                method: "POST",
                body: form
            });

            this.newTitle = "";
            this.showAddModal = false;
            this.getData(this.page);
        },

        startEdit(item) {
            this.editId = item.id;
            this.editTitle = item.title;
            this.showEditModal = true;
        },

        async update() {
            if (!this.editTitle.trim()) return alert("عنوان نمی‌تواند خالی باشد");
            const form = new FormData();
            form.append("title", this.editTitle.trim());

            await fetch(`<?= site_url('Transactionscategories/api_update/') ?>${this.editId}`, {
                method: "POST",
                body: form
            });

            this.showEditModal = false;
            this.getData(this.page);
        },

        async remove(id) {
            if (!confirm("آیا از حذف این دسته‌بندی مطمئن هستید؟")) return;

            await fetch(`<?= site_url('Transactionscategories/api_delete/') ?>${id}`, {
                method: "DELETE"
            });

            this.getData(this.page);
        }
    }
}).mount("#app");
</script>