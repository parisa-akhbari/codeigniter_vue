<div id="app">
<div class="container">
    <h2 class="mb-4">پروفایل کاربری</h2>

    <!-- پیام‌ها -->
    <div v-if="msg" class="alert alert-success">{{ msg }}</div>
    <div v-if="error" class="alert alert-danger">{{ error }}</div>

    <!-- اطلاعات کاربر -->
    <div class="card shadow-sm mb-4">
        <div class="card-body text-center">
            <img :src="profile.profile_image || placeholder" class="avatar img-thumbnail mb-3" 
                 style="width:140px; height:140px; border-radius:50%; object-fit:cover;">

            <h4>{{ profile.username }}</h4>
        </div>
    </div>

    <!-- تغییر نام -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">تغییر نام کاربری</div>
        <div class="card-body">
            <input class="form-control mb-2" v-model="editName">
            <button class="btn btn-primary" @click="updateName">ذخیره</button>
        </div>
    </div>

    <!-- تغییر رمز -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-warning">تغییر رمز عبور</div>
        <div class="card-body">

            <input type="password" class="form-control mb-2"
                   v-model="pass.current_password" placeholder="رمز فعلی">

            <input type="password" class="form-control mb-2"
                   v-model="pass.new_password" placeholder="رمز جدید">

            <input type="password" class="form-control mb-2"
                   v-model="pass.confirm_password" placeholder="تکرار رمز جدید">

            <button class="btn btn-warning" @click="updatePassword">تغییر رمز</button>
        </div>
    </div>

    <!-- آپلود تصویر -->
    <div class="card shadow-sm">
        <div class="card-header bg-success text-white">آپلود تصویر پروفایل</div>
        <div class="card-body">

            <input type="file" ref="file" class="form-control mb-2">

            <button class="btn btn-success" @click="uploadImage">آپلود</button>

        </div>
    </div>
</div>
</div>

<!-- Vue 3 -->
<script src="https://unpkg.com/vue@3"></script>

<script>
const app = Vue.createApp({
    data(){
        return {
            profile: {},
            editName: "",
            msg: "",
            error: "",
            placeholder: "https://via.placeholder.com/150",

            pass: {
                current_password: "",
                new_password: "",
                confirm_password: "",
            }
        }
    },

    mounted(){
        this.loadProfile();
    },

    methods: {

        async loadProfile(){
            const res = await fetch("<?= site_url('profile/api_get'); ?>");
            const data = await res.json();

            this.profile = data;
            this.editName = data.username;
        },

        showMessage(msg){
            this.msg = msg;
            this.error = "";
            setTimeout(() => this.msg = "", 3000);
        },

        showError(err){
            this.error = err;
            this.msg = "";
            setTimeout(() => this.error = "", 3000);
        },

        async updateName(){
            const form = new FormData();
            form.append("username", this.editName);

            const res = await fetch("<?= site_url('profile/api_update_name'); ?>", {
                method: "POST",
                body: form
            });

            const data = await res.json();

            if (data.status === "success"){
                this.showMessage("نام با موفقیت تغییر کرد");
                this.loadProfile();
            } else {
                this.showError(data.message);
            }
        },

        async updatePassword(){
            const form = new FormData();
            form.append("current_password", this.pass.current_password);
            form.append("new_password", this.pass.new_password);
            form.append("confirm_password", this.pass.confirm_password);

            const res = await fetch("<?= site_url('profile/api_update_password'); ?>", {
                method: "POST",
                body: form
            });

            const data = await res.json();

            if(data.status === "success"){
                this.showMessage("رمز عبور تغییر کرد");
                this.pass = { current_password:"", new_password:"", confirm_password:"" };
            } else {
                this.showError(data.message);
            }
        },

        async uploadImage(){
            const file = this.$refs.file.files[0];
            if (!file) return this.showError("فایل انتخاب نشده");

            const form = new FormData();
            form.append("profile_image", file);

            const res = await fetch("<?= site_url('profile/api_upload_image'); ?>", {
                method: "POST",
                body: form
            });

            const data = await res.json();

            if(data.status === "success"){
                this.showMessage("تصویر با موفقیت آپلود شد");
                this.loadProfile();
            } else {
                this.showError(data.message);
            }
        }

    }
}).mount("#app");
</script>
