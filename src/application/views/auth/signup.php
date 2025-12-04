<!DOCTYPE html>
<html lang="fa">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>فرم ورود / ثبت‌نام</title>
<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,700' rel='stylesheet' type='text/css'>
<!-- لینک فونت فارسی Vazir -->
<link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazir-font@30.1.0/dist/font-face.css" rel="stylesheet" type="text/css" />
<style>
html, body * { box-sizing: border-box; font-family: 'Vazir', IRANSans, sans-serif !important; direction: rtl; }

body {
  background:
    linear-gradient(rgba(246,247,249,0.8), rgba(246,247,249,0.8)),
    url(https://dl.dropboxusercontent.com/u/22006283/preview/codepen/sky-clouds-cloudy-mountain.jpg) no-repeat center center fixed;
  background-size: cover;
}

.container { width: 100%; padding-top: 60px; padding-bottom: 100px; }

.frame {
  height: 575px;
  width: 430px;
  background: linear-gradient(rgba(35,43,85,0.75), rgba(35,43,85,0.95)),
              url(https://dl.dropboxusercontent.com/u/22006283/preview/codepen/clouds-cloudy-forest-mountain.jpg) no-repeat center center;
  background-size: cover;
  margin: 0 auto;
  border-top: solid 1px rgba(255,255,255,.5);
  border-radius: 5px;
  box-shadow: 0px 2px 7px rgba(0,0,0,0.2);
  overflow: hidden;
  transition: all .5s ease;
}

.frame-long { height: 615px; }
.frame-short { height: 400px; margin-top:50px; box-shadow: 0px 2px 7px rgba(0,0,0,0.1); }

.nav { width: 100%; height: 100px; padding-top: 40px; opacity: 1; transition: all .5s ease; text-align:center; }
.nav-up { transform: translateY(-100px); opacity: 0; }

li { padding-left: 10px; font-size: 18px; display: inline; text-align: left; text-transform: uppercase; padding-right: 10px; color: #ffffff; }
.signin-active a, .signup-active a { color: #ffffff; text-decoration: none; border-bottom: solid 2px #1059FF; padding-bottom: 10px; cursor:pointer;}
.signin-inactive a, .signup-inactive a { color: rgba(255,255,255,.3); cursor:pointer; text-decoration:none; }

.form-signin, .form-signup {
  width: 430px;
  font-size: 16px;
  font-weight: 300;
  padding-left: 37px;
  padding-right: 37px;
  padding-top: 55px;
  transition: all .5s ease;
  color: white;
}

.form-signin { height: 375px; }
.form-signup { height: 375px; position: relative; top: -375px; left: 400px; opacity:0; }

.form-signin-left { transform: translateX(-400px); opacity:0; }
.form-signup-left { transform: translateX(-399px); opacity:1; }
.form-signup-down { top:0px; opacity:1; }

input.form-styling {
  width: 100%;
  height: 35px;
  padding-left: 15px;
  border: none;
  border-radius: 20px;
  margin-bottom: 20px;
  background: white
  color: #fff;
  font-size: 18px;
}

input.form-styling:focus {
  outline:none;
  background: rgba(255,255,255,.3);
}

label {
  font-weight: 400;
  text-transform: uppercase;
  font-size: 18px;
  padding-left: 15px;
  padding-bottom: 10px;
  color: white;
  display:block;
}

button {
  width: 100%;
  height: 35px;
  border-radius: 20px;
  font-weight: 700;
  text-transform: uppercase;
  font-size: 18px;
  color: #fff;
  border: none;
  cursor:pointer;
}

.btn-signup { background-color: #1059FF; margin-top:23px; }
.btn-signin { background-color: rgba(16,89,255,1); margin-top:23px; }

.success { width: 80%; height: 150px; text-align:center; position: relative; top:-890px; left:450px; opacity:0; transition: all .8s .4s ease; }
.success-left { transform: translateX(-406px); opacity:1; }

</style>
</head>
<body>

<div class="container">
  <div class="frame">
    <?php if (validation_errors()): ?>
      <div class="error" style="color:red; margin-bottom:15px;">
        <?php echo validation_errors(); ?>
      </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('signup_success')): ?>
      <div class="alert alert-success">
        <?= $this->session->flashdata('signup_success'); ?>
      </div>
    <?php endif; ?>

    <div class="nav">
      <ul class="links">
        <li class="signin-active"><a class="btn">ورود</a></li>
        <li class="signup-inactive"><a class="btn">ثبت‌نام</a></li>
      </ul>
    </div>
    
    <!-- فرم ورود -->
    <form class="form-signin" action="<?php echo site_url('Auth/login'); ?>" method="post">
        <label>نام کاربری</label>
        <input class="form-styling" type="text" name="username">

        <label>رمز عبور</label>
        <input class="form-styling" type="password" name="password">

        <button type="submit" class="btn-signin">ورود</button>
    </form>
     
    <!-- فرم ثبت‌نام -->
    <form class="form-signup" action="<?php echo site_url('Auth/signup'); ?>" method="post">
        <label>نام کاربری</label>
        <input class="form-styling" type="text" name="username">

        <label>رمز عبور</label>
        <input class="form-styling" type="password" name="password">

        <label>تکرار رمز عبور</label>
        <input class="form-styling" type="password" name="confirmpassword">

        <button type="submit" class="btn-signup">ثبت‌نام</button>
    </form>

    <!-- پیام موفقیت -->
    <div class="success">
      <div class="successtext">
        <p>ثبت‌نام با موفقیت انجام شد! لطفاً ایمیل خود را بررسی کنید.</p>
      </div>
    </div>

  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function() {
    $(".btn").click(function() {
        $(".error").remove();
        $(".success").removeClass("success-left");

        // سوئیچ فرم‌ها با کلیک
        $(".form-signin").toggleClass("form-signin-left");
        $(".form-signup").toggleClass("form-signup-left");
        $(".frame").toggleClass("frame-long");
        $(".signup-inactive").toggleClass("signup-active");
        $(".signin-active").toggleClass("signin-inactive");
    });

    // --- فرم فعال بر اساس PHP ---
    <?php if (isset($active_form) && $active_form == 'signup') : ?>
        $(".form-signin").addClass("form-signin-left"); // فرم ورود مخفی
        $(".form-signup").addClass("form-signup-left"); // فرم ثبت‌نام فعال
        $(".frame").addClass("frame-long");
        $(".signup-inactive").addClass("signup-active");
        $(".signin-active").addClass("signin-inactive");
    <?php else: ?>
        // فرم ورود فعال، فرم ثبت‌نام مخفی
        $(".form-signin").removeClass("form-signin-left");
        $(".form-signup").removeClass("form-signup-left");
        $(".frame").removeClass("frame-long");
        $(".signup-inactive").removeClass("signup-active");
        $(".signin-active").removeClass("signin-inactive");
    <?php endif; ?>
});

</script>


</body>
</html>
