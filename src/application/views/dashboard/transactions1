<div class="container py-4" id="app">
        <h2 class="mb-4 text-primary">مدیریت تراکنش‌ها</h2>

        <button class="btn btn-success mb-4" @click="openCreateModal">
                افزودن تراکنش جدید
        </button>

        <!-- فرم جستجو -->
        <div class="card mb-4 shadow-sm">
                <div class="card-body">
                        <div class="row g-3 align-items-end">
                                <div class="col-md-3">
                                        <input type="text" v-model="filters.title" class="form-control"
                                                placeholder="جستجوی عنوان...">
                                </div>
                                <div class="col-md-2">
                                        <select v-model="filters.type" class="form-select">
                                                <option value="">همه نوع‌ها</option>
                                                <option value="income">درآمد</option>
                                                <option value="expense">هزینه</option>
                                        </select>
                                </div>
                                <div class="col-md-2">
                                        <input type="text" class="form-control" placeholder="از تاریخ" readonly
                                                @click="$refs.startPicker.showPanel()"
                                                v-model="filters.start_date_shamsi">
                                </div>
                                <div class="col-md-2">
                                        <input type="text" class="form-control" placeholder="تا تاریخ" readonly
                                                @click="$refs.endPicker.showPanel()" v-model="filters.end_date_shamsi">
                                </div>
                                <div class="col-md-3">
                                        <button class="btn btn-primary me-2" @click="getData(1)">جستجو</button>
                                        <button class="btn btn-secondary" @click="clearFilters">پاک کردن</button>
                                </div>
                        </div>
                </div>
        </div>

        <!-- جدول -->
        <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                        <thead class="table-dark">
                                <tr>
                                        <th>عنوان</th>
                                        <th>مبلغ</th>
                                        <th>نوع</th>
                                        <th>دسته‌بندی</th>
                                        <th>تاریخ</th>
                                        <th class="text-center">عملیات</th>
                                </tr>
                        </thead>
                        <tbody>
                                <tr v-for="t in transactions" :key="t.id">
                                        <td>{{ t.title }}</td>
                                        <td class="text-start font-monospace">{{
                                                Number(t.amount).toLocaleString('fa-IR') }}</td>
                                        <td>
                                                <span class="badge fs-6"
                                                        :class="t.type === 'income' ? 'bg-success' : 'bg-danger'">
                                                        {{ t.type === 'income' ? 'درآمد' : 'هزینه' }}
                                                </span>
                                        </td>
                                        <td>{{ t.category_title || '-' }}</td>
                                        <td>{{ t.transaction_date_shamsi }}</td>
                                        <td class="text-center">
                                                <button @click="openEditModal(t)"
                                                        class="btn btn-warning btn-sm">ویرایش</button>
                                                <button @click="remove(t.id)"
                                                        class="btn btn-danger btn-sm ms-1">حذف</button>
                                        </td>
                                </tr>
                                <tr v-if="!transactions.length">
                                        <td colspan="6" class="text-center py-5 text-muted">تراکنشی یافت نشد</td>
                                </tr>
                        </tbody>
                </table>
        </div>

        <!-- صفحه‌بندی -->
        <nav v-if="pagesCount > 1" class="mt-5">
                <ul class="pagination justify-content-center">
                        <li class="page-item" :class="{disabled: page===1}"><a class="page-link"
                                        @click="goPage(1)">اول</a></li>
                        <li class="page-item" :class="{disabled: page===1}"><a class="page-link"
                                        @click="goPage(page-1)">قبلی</a></li>
                        <template v-for="p in paginationPages" :key="p">
                                <li class="page-item" :class="{active: p===page, disabled: p==='...'}">
                                        <a v-if="p!=='...'" class="page-link" @click="goPage(p)">{{ p }}</a>
                                        <span v-else class="page-link">{{ p }}</span>
                                </li>
                        </template>
                        <li class="page-item" :class="{disabled: page===pagesCount}"><a class="page-link"
                                        @click="goPage(page+1)">بعدی</a></li>
                        <li class="page-item" :class="{disabled: page===pagesCount}"><a class="page-link"
                                        @click="goPage(pagesCount)">آخر</a></li>
                </ul>
        </nav>

        <!-- مودال افزودن / ویرایش -->
        <div v-if="showModal" class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
                <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                                <div class="modal-header">
                                        <h5 class="modal-title">{{ isEdit ? 'ویرایش تراکنش' : 'افزودن تراکنش جدید' }}
                                        </h5>
                                        <button type="button" class="btn-close" @click="closeModal"></button>
                                </div>
                                <div class="modal-body">
                                        <div class="row g-3">
                                                <div class="col-md-6">
                                                        <label>عنوان</label>
                                                        <input type="text" v-model="form.title" class="form-control"
                                                                placeholder="عنوان">
                                                </div>
                                                <div class="col-md-6">
                                                        <label>مبلغ (تومان)</label>
                                                        <input type="number" v-model.number="form.amount"
                                                                class="form-control">
                                                </div>
                                                <div class="col-md-6">
                                                        <label>نوع تراکنش</label>
                                                        <select v-model="form.type" class="form-select">
                                                                <option value="income">درآمد</option>
                                                                <option value="expense">هزینه</option>
                                                        </select>
                                                </div>
                                                <div class="col-md-6">
                                                        <label>دسته‌بندی</label>
                                                        <select v-model="form.category_id" class="form-select">
                                                                <option value="">انتخاب کنید</option>
                                                                <option v-for="cat in categories" :value="cat.id">{{
                                                                        cat.title }}</option>
                                                        </select>
                                                </div>
                                                <div class="col-12">
                                                        <label>تاریخ تراکنش (شمسی)</label>
                                                        <input type="text" class="form-control" readonly
                                                                @click="$refs.datePicker.showPanel()"
                                                                v-model="form.transaction_date_shamsi">
                                                </div>
                                        </div>
                                </div>
                                <div class="modal-footer">
                                        <button class="btn btn-success" @click="isEdit ? update() : create()">
                                                {{ isEdit ? 'ذخیره تغییرات' : 'افزودن تراکنش' }}
                                        </button>
                                        <button class="btn btn-secondary" @click="closeModal">بستن</button>
                                </div>
                        </div>
                </div>
        </div>

        <!-- Datepicker ها -->
        <vue3-persian-datetime-picker ref="startPicker" v-model="filters.start_date_shamsi" format="YYYY/MM/DD"
                display-format="jYYYY/jMM/jDD" auto-submit />
        <vue3-persian-datetime-picker ref="endPicker" v-model="filters.end_date_shamsi" format="YYYY/MM/DD"
                display-format="jYYYY/jMM/jDD" auto-submit />
        <vue3-persian-datetime-picker ref="datePicker" v-model="form.transaction_date_shamsi" format="YYYY/MM/DD"
                display-format="jYYYY/jMM/jDD" auto-submit />
</div>

<!-- اسکریپت‌ها -->
<script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>
<script src="https://unpkg.com/vue3-persian-datetime-picker@1.2.3/dist/vue3-persian-datetime-picker.umd.js"></script>
<link rel="stylesheet"
        href="https://unpkg.com/vue3-persian-datetime-picker@1.2.3/dist/vue3-persian-datetime-picker.css">

<script>
        const { createApp } = Vue;

        createApp({
                components: {
                        Vue3PersianDatetimePicker: window.Vue3PersianDatetimePicker
                },
                data() {
                        return {
                                transactions: [],
                                categories: [],
                                page: 1,
                                total_rows: 0,
                                page_size: 10,

                                filters: {
                                        title: "",
                                        type: "",
                                        start_date_shamsi: "",
                                        end_date_shamsi: ""
                                },

                                showModal: false,
                                isEdit: false,
                                form: {
                                        id: null,
                                        title: "",
                                        amount: "",
                                        type: "expense",
                                        category_id: "",
                                        transaction_date_shamsi: ""
                                }
                        }
                },
                computed: {
                        pagesCount() {
                                return Math.ceil(this.total_rows / this.page_size);
                        },
                        paginationPages() {
                                const delta = 2;
                                const range = [];
                                const rangeWithDots = [];

                                for (let i = Math.max(2, this.page - delta); i <= Math.min(this.pagesCount - 1, this.page + delta); i++) {
                                        range.push(i);
                                }

                                if (this.page - delta > 2) rangeWithDots.push(1, '...');
                                else for (let i = 2; i < this.page - delta + 1; i++) rangeWithDots.push(i);

                                rangeWithDots.push(...range);

                                if (this.page + delta < this.pagesCount - 1) rangeWithDots.push('...', this.pagesCount);
                                else for (let i = this.page + delta + 1; i < this.pagesCount; i++) rangeWithDots.push(i);

                                return [1, ...rangeWithDots, this.pagesCount].filter((v, i, a) => a.indexOf(v) === i);
                        }
                },
                mounted() {
                        this.loadCategories();
                        this.getData(1);
                },
                methods: {
                        async loadCategories() {
                                const res = await fetch("<?= site_url('transactions/api_list_categories') ?>");
                                const data = await res.json();
                                this.categories = data.categories || [];
                        },

                        async getData(page = 1) {
                                this.page = page;
                                const params = new URLSearchParams({
                                        page: this.page,
                                        title: this.filters.title,
                                        type: this.filters.type,
                                        start_date: this.filters.start_date_shamsi,
                                        end_date: this.filters.end_date_shamsi
                                });

                                const res = await fetch("<?= site_url('transactions/api_search') ?>?" + params);
                                const json = await res.json();

                                this.transactions = json.transactions;
                                this.total_rows = json.total_rows;
                                this.page = json.page;
                        },

                        goPage(p) {
                                if (p === '...' || p === this.page) return;
                                this.getData(p);
                        },

                        clearFilters() {
                                this.filters = { title: "", type: "", start_date_shamsi: "", end_date_shamsi: "" };
                                this.getData(1);
                        },

                        openCreateModal() {
                                this.isEdit = false;
                                this.form = {
                                        id: null,
                                        title: "",
                                        amount: "",
                                        type: "expense",
                                        category_id: "",
                                        transaction_date_shamsi: new Date().toLocaleDateString('fa-IR').replace(/[/]/g, '/')
                                };
                                this.showModal = true;
                        },

                        openEditModal(t) {
                                this.isEdit = true;
                                this.form = {
                                        id: t.id,
                                        title: t.title,
                                        amount: t.amount,
                                        type: t.type,
                                        category_id: t.category_id,
                                        transaction_date_shamsi: t.transaction_date_shamsi
                                };
                                this.showModal = true;
                        },

                        closeModal() {
                                this.showModal = false;
                        },

                        async create() {
                                if (!this.validateForm()) return;

                                const formData = new FormData();
                                Object.keys(this.form).forEach(k => {
                                        if (k !== 'id') formData.append(k, this.form[k] || '');
                                });

                                await fetch("<?= site_url('transactions/api_create') ?>", {
                                        method: "POST",
                                        body: formData
                                });

                                this.closeModal();
                                this.getData(this.page);
                        },

                        async update() {
                                if (!this.validateForm()) return;

                                const formData = new FormData();
                                Object.keys(this.form).forEach(k => formData.append(k, this.form[k] || ''));

                                await fetch("<?= site_url('transactions/api_update/') ?>" + this.form.id, {
                                        method: "POST",
                                        body: formData
                                });

                                this.closeModal();
                                this.getData(this.page);
                        },

                        async remove(id) {
                                if (!confirm("آیا از حذف این تراکنش مطمئن هستید؟")) return;
                                await fetch("<?= site_url('transactions/api_delete/') ?>" + id, { method: "DELETE" });
                                this.getData(this.page);
                        },

                        validateForm() {
                                if (!this.form.title.trim()) return alert("عنوان را وارد کنید"), false;
                                if (!this.form.amount || this.form.amount <= 0) return alert("مبلغ معتبر نیست"), false;
                                if (!this.form.category_id) return alert("دسته‌بندی را انتخاب کنید"), false;
                                if (!this.form.transaction_date_shamsi) return alert("تاریخ را انتخاب کنید"), false;
                                return true;
                        }
                }
        }).mount("#app");
</script>