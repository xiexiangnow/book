<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Xenon Boostrap Admin Panel" />
    <meta name="author" content="" />

    <title>欣阅图书管理系统-欢迎您！</title>

    <link rel="stylesheet" href="/Public/Index/css/fonts/font_style.css">
    <link rel="stylesheet" href="/Public/Index/css/fonts/linecons/css/linecons.css">
    <link rel="stylesheet" href="/Public/Index/css/fonts/fontawesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="/Public/Index/css/bootstrap.css">
    <link rel="stylesheet" href="/Public/Index/css/xenon-core.css">
    <link rel="stylesheet" href="/Public/Index/css/xenon-forms.css">
    <link rel="stylesheet" href="/Public/Index/css/xenon-components.css">
    <link rel="stylesheet" href="/Public/Index/css/xenon-skins.css">
    <link rel="stylesheet" href="/Public/Index/css/custom.css">

    <script src="/Public/Index/js/jquery-1.11.1.min.js"></script>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->


</head>
<body class="page-body login-page">


<div class="login-container">

    <div class="row">

        <div class="col-sm-6">

            <script type="text/javascript">
                jQuery(document).ready(function($)
                {
                    // Reveal Login form
                    setTimeout(function(){ $(".fade-in-effect").addClass('in'); }, 1);


                    // Validation and Ajax action
                    $("form#login").validate({
                        rules: {
                            username: {
                                required: true
                            },

                            passwd: {
                                required: true
                            },
                            verify:{
                                required:true
                            }

                        },

                        messages: {
                            username: {
                                required: 'Please enter your username.'
                            },

                            passwd: {
                                required: 'Please enter your password.'
                            },
                            verify:{
                                required: 'Please enter your verify.'
                            }

                        },

                        // Form Processing via AJAX
                        submitHandler: function(form)
                        {
                            show_loading_bar(70); // Fill progress bar to 70% (just a given value)

                            var opts = {
                                "closeButton": true,
                                "debug": false,
                                "positionClass": "toast-top-full-width",
                                "onclick": null,
                                "showDuration": "300",
                                "hideDuration": "1000",
                                "timeOut": "5000",
                                "extendedTimeOut": "1000",
                                "showEasing": "swing",
                                "hideEasing": "linear",
                                "showMethod": "fadeIn",
                                "hideMethod": "fadeOut"
                            };

                            $.ajax({
                                url: "<?php echo U('Login/login_check');?>",
                                method: 'POST',
                                dataType: 'json',
                                data: {
                                    do_login: true,
                                    username: $(form).find('#username').val(),
                                    passwd: $(form).find('#passwd').val(),
                                    verify: $(form).find('#verify').val()
                                },
                                success: function(resp)
                                {

                                    show_loading_bar({
                                        delay: .5,
                                        pct: 100,
                                        finish: function(){

                                            // Redirect after successful login page (when progress bar reaches 100%)
                                            if(resp.state==1)
                                            {
                                                toastr.success(resp.msg, "登录成功!", opts);

                                                window.setTimeout('window.location="<?php echo U('Index/index');?>"',1500);
                                                //window.location.href = "<?php echo U('Index/index');?>";
                                            }
                                            else
                                            {
                                                toastr.error(resp.msg, "登录失败!", opts);

                                            }
                                        }
                                    });

                                }

                            });

                        }
                    });

                    // Set Form focus
                    $("form#login .form-group:has(.form-control):first .form-control").focus();
                });
            </script>

            <!-- Errors container -->
            <div class="errors-container">


            </div>

            <!-- Add class "fade-in-effect" for login form effect -->
            <form method="post" role="form" id="login" class="login-form fade-in-effect">

                <div class="login-header">
                    <a href="#" class="logo">
                        <img src="/Public/Index/images/logo@2x.png" alt="" width="80" />
                        <span>欣阅图书管理系统</span>
                    </a>

                    <p>Dear user, log in to access the admin area!</p>
                </div>


                <div class="form-group">
                    <label class="control-label" for="username">Username</label>
                    <input type="text" class="form-control input-dark" name="username" id="username" autocomplete="off" />
                </div>

                <div class="form-group">
                    <label class="control-label" for="passwd">Password</label>
                    <input type="password" class="form-control input-dark" name="passwd" id="passwd" autocomplete="off" />
                </div>

                <div class="form-group">
                    <label class="control-label" for="verify">verifty</label>
                    <input type="text" class="form-control input-dark" name="verify" id="verify" autocomplete="off"  />
                    <img src="<?php echo U('Login/verify');?>" alt="验证码" style="margin: 10px 0;float:right;cursor:pointer;" title="看不清可单击图片刷新"  onclick="this.src=this.src+'?'+Math.random()" id="auth_code_img"/>
                </div>
                 <span style="margin-top: 10px;">看不清，点击验证码换一张</span>

                <div class="form-group">
                    <button type="botton" class="btn btn-dark  btn-block text-left" >
                        <i class="fa-lock"></i>
                        Log In
                    </button>
                </div>

                <div class="login-footer">
                    <a href="#">Forgot your password?</a>

                    <div class="info-links">
                        <a href="#">ToS</a> -
                        <a href="#">Privacy Policy</a>
                    </div>

                </div>

            </form>

            <!-- External login -->
            <div class="external-login">
                <a href="#" class="facebook">
                    <i class="fa-facebook"></i>
                    Facebook Login
                </a>

                <!--
                <a href="#" class="twitter">
                    <i class="fa-twitter"></i>
                    Login with Twitter
                </a>

                <a href="#" class="gplus">
                    <i class="fa-google-plus"></i>
                    Login with Google Plus
                </a>
                 -->
            </div>

        </div>

    </div>

</div>


<!-- Bottom Scripts -->
<script src="/Public/Index/js/bootstrap.min.js"></script>
<script src="/Public/Index/js/TweenMax.min.js"></script>
<script src="/Public/Index/js/resizeable.js"></script>
<script src="/Public/Index/js/joinable.js"></script>
<script src="/Public/Index/js/xenon-api.js"></script>
<script src="/Public/Index/js/xenon-toggles.js"></script>
<script src="/Public/Index/js/jquery-validate/jquery.validate.min.js"></script>
<script src="/Public/Index/js/toastr/toastr.min.js"></script>


<!-- JavaScripts initializations and stuff -->
<script src="/Public/Index/js/xenon-custom.js"></script>

</body>
</html>