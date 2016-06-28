<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Xenon Boostrap Admin Panel" />
    <meta name="author" content="" />
    <title>欣阅图书管理系统</title>
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
</head>
<body class="page-body">

<div class="settings-pane">

    <a href="#" data-toggle="settings-pane" data-animate="true">
        &times;
    </a>
    <div class="settings-pane-inner">

        <div class="row">

            <div class="col-md-4">

                <div class="user-info">

                    <div class="user-image">
                        <a href="extra-profile.html">
                            <img src="/Public/Index/images/user-2.png" class="img-responsive img-circle" />
                        </a>
                    </div>
                    <div class="user-details">
                        <h3>
                            <a href="extra-profile.html">John Smith</a>

                            <!-- Available statuses: is-online, is-idle, is-busy and is-offline -->
                            <span class="user-status is-online"></span>
                        </h3>

                        <p class="user-title">Web Developer</p>

                        <div class="user-links">
                            <a href="extra-profile.html" class="btn btn-primary">Edit Profile</a>
                            <a href="extra-profile.html" class="btn btn-success">Upgrade</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8 link-blocks-env">

                <div class="links-block left-sep">
                    <h4>
                        <span>Notifications</span>
                    </h4>

                    <ul class="list-unstyled">
                        <li>
                            <input type="checkbox" class="cbr cbr-primary" checked="checked" id="sp-chk1" />
                            <label for="sp-chk1">Messages</label>
                        </li>
                        <li>
                            <input type="checkbox" class="cbr cbr-primary" checked="checked" id="sp-chk2" />
                            <label for="sp-chk2">Events</label>
                        </li>
                        <li>
                            <input type="checkbox" class="cbr cbr-primary" checked="checked" id="sp-chk3" />
                            <label for="sp-chk3">Updates</label>
                        </li>
                        <li>
                            <input type="checkbox" class="cbr cbr-primary" checked="checked" id="sp-chk4" />
                            <label for="sp-chk4">Server Uptime</label>
                        </li>
                    </ul>
                </div>

                <div class="links-block left-sep">
                    <h4>
                        <a href="#">
                            <span>Help Desk</span>
                        </a>
                    </h4>

                    <ul class="list-unstyled">
                        <li>
                            <a href="#">
                                <i class="fa-angle-right"></i>
                                Support Center
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <i class="fa-angle-right"></i>
                                Submit a Ticket
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <i class="fa-angle-right"></i>
                                Domains Protocol
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <i class="fa-angle-right"></i>
                                Terms of Service
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

        </div>

    </div>
</div>
<div class="page-container">

    <div class="sidebar-menu toggle-others fixed">

        <div class="sidebar-menu-inner">

            <header class="logo-env">

                <!-- logo -->
                <div class="logo">
                    <a href="/Index/index" class="logo-expanded">
                        <img src="/Public/Index/images/logo@2x.png" width="80" alt="" />
                         &nbsp;&nbsp;&nbsp;<span style="color:#ccc;">欣阅图书管理系统</span>
                    </a>

                    <a href="dashboard-1.html" class="logo-collapsed">
                        <img src="/Public/Index/images/logo-collapsed@2x.png" width="40" alt="" />
                    </a>
                </div>

                <!-- This will toggle the mobile menu and will be visible only on mobile devices -->
                <div class="mobile-menu-toggle visible-xs">
                    <a href="#" data-toggle="user-info-menu">
                        <i class="fa-bell-o"></i>
                        <span class="badge badge-success">7</span>
                    </a>

                    <a href="#" data-toggle="mobile-menu">
                        <i class="fa-bars"></i>
                    </a>
                </div>

                <!-- This will open the popup with user profile settings, you can use for any purpose, just be creative -->
                <div class="settings-icon">
                    <a href="#" data-toggle="settings-pane" data-animate="true">
                        <i class="linecons-cog"></i>
                    </a>
                </div>
            </header>



            <ul id="main-menu" class="main-menu">
                <!-- add class "multiple-expanded" to allow multiple submenus to open -->
                <!-- class "auto-inherit-active-class" will automatically add "active" class for parent elements who are marked already with class "active" -->
               <?php if(is_array($menu_list_nav)): $i = 0; $__LIST__ = $menu_list_nav;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><li  <?php if($now_farent_id == $v['id']): ?>class="active opened active"<?php endif; ?>>
                    <a href="dashboard-1.html">
                        <i class="<?php echo ($v['icon']); ?>"></i>
                        <span class="title"><?php echo ($v['title']); ?></span>
                    </a>

                        <ul>
                            <?php if(is_array($v['child'])): $i = 0; $__LIST__ = $v['child'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li  <?php if($now_nav == $vo['id']): ?>class="active"<?php endif; ?>>
                                <a href="<?php echo ($vo['path']); ?>?nav=<?php echo ($vo['id']); ?>">
                                    <span class="title"><?php echo ($vo['title']); ?></span>
                                </a>
                            </li><?php endforeach; endif; else: echo "" ;endif; ?>
                        </ul>
                </li><?php endforeach; endif; else: echo "" ;endif; ?>

            </ul>

        </div>

    </div>

<div class="main-content">
    <!-- User Info, Notifications and Menu Bar -->
    <nav class="navbar user-info-navbar" role="navigation">
        <!-- Left links for user info navbar -->
        <ul class="user-info-menu left-links list-inline list-unstyled">

            <li class="hidden-sm hidden-xs">
                <a href="#" data-toggle="sidebar">
                    <i class="fa-bars"></i>
                </a>
            </li>

            <li class="dropdown hover-line">
                <a href="#" data-toggle="dropdown">
                    <i class="fa-envelope-o"></i>
                    <span class="badge badge-green">15</span>
                </a>

                <ul class="dropdown-menu messages">
                    <li>

                    </li>

                    <li class="external">
                        <a href="blank-sidebar.html">
                            <span>All Messages</span>
                            <i class="fa-link-ext"></i>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>

        <!-- Right links for user info navbar -->
        <ul class="user-info-menu right-links list-inline list-unstyled">


            <li class="dropdown user-profile">
                <a href="#" data-toggle="dropdown">
                    <img src="/Public/Index/images/user-4.png" alt="user-image" class="img-circle img-inline userpic-32" width="28" />
							<span>
								欢迎你：<?php echo ($user_info[1]); ?>
								<i class="fa-angle-down"></i>
							</span>
                </a>

                <ul class="dropdown-menu user-profile-menu list-unstyled">
                    <li>
                        <a href="#edit-profile">
                            <i class="fa-edit"></i>
                            New Post
                        </a>
                    </li>
                    <li>
                        <a href="#settings">
                            <i class="fa-wrench"></i>
                            Settings
                        </a>
                    </li>
                    <li>
                        <a href="#profile">
                            <i class="fa-user"></i>
                            Profile
                        </a>
                    </li>
                    <li>
                        <a href="#help">
                            <i class="fa-info"></i>
                            Help
                        </a>
                    </li>
                    <li class="last">
                        <a href="<?php echo U('Login/login_out');?>">
                            <i class="fa-lock"></i>
                            Logout
                        </a>
                    </li>
                </ul>
            </li>

        </ul>
    </nav>

    <!--面包屑区域-->
    <div class="page-title">
        <div class="title-env">
            <h1 class="title"><?php echo ($parent); ?></h1>
            <p class="description"><i class="linecons-attach"></i>每一步做到极致，下一步美好自然呈现！Each step to achieve the ultimate, the next step is a natural show!</p>
        </div>

        <div class="breadcrumb-env">

            <ol class="breadcrumb bc-1">
                <li>
                    <a href="/Index/index"><i class="fa-home"></i>主页</a>
                </li>
                <?php if($parent != null): ?><li>
                    <a ><?php echo ($parent); ?></a>
                </li>
                <li class="active">

                    <strong><?php echo ($child); ?></strong>
                </li><?php endif; ?>
            </ol>

        </div>

    </div>

<div class="row">
    <div class="col-sm-12">

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">类型 <?php if($type_info['id'] != null): ?>修改<?php else: ?>添加<?php endif; ?></h3>
                <div class="panel-options">
                    <a href="#" data-toggle="panel">
                        <span class="collapse-icon">–</span>
                        <span class="expand-icon">+</span>
                    </a>
                    <a href="#" data-toggle="remove">
                        ×
                    </a>
                </div>
            </div>
            <div class="panel-body">

                <form role="form" class="form-horizontal" id="addtype_form" method="post" action="">

                    <input type="hidden" name="id" value="<?php echo ($type_info['id']); ?>"/>


                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="field-1">名称 <span style="color:red;">*</span></label>

                        <div class="col-sm-10">
                            <input type="text" name="name" class="form-control" value="<?php echo ($type_info['name']); ?>" id="field-1" placeholder="请输入类型名称">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="field-5">备注</label>

                        <div class="col-sm-10">
                            <textarea name="remark" class="form-control" cols="5" id="field-5" placeholder="请输入备注信息"><?php echo ($type_info['remark']); ?></textarea>
                        </div>
                    </div>


                    <div class="form-group-separator"></div>
                         <span style="float: right;">
                           <a type="button" class="btn btn-black btn-sm" onclick="submit_addtype()">提交</a>
                        </span>
                </form>

            </div>
        </div>

    </div>
</div>
<script type="text/javascript">
    function submit_addtype(){

        $.post('addTypeData',$('#addtype_form').serialize(),function(e){
            show_loading_bar({
                delay: .5,
                pct: 100,
                finish: function(){

                    // Redirect after successful login page (when progress bar reaches 100%)
                    if(e.state==1)
                    {
                        toastr.success(e.msg, "操作状态!");

                        window.setTimeout('window.location="typeList?nav=25"',1000);
                    }
                    else
                    {
                        toastr.error(e.msg, "操作状态!");

                    }
                }
            });

        },'json');
    }
</script>

<!-- Main Footer -->
<!-- Choose between footer styles: "footer-type-1" or "footer-type-2" -->
<!-- Add class "sticky" to  always stick the footer to the end of page (if page contents is small) -->
<!-- Or class "fixed" to  always fix the footer to the end of page -->
<footer class="main-footer sticky footer-type-1">

    <div class="footer-inner">

        <!-- Add your copyright text here -->
        <div class="footer-text">
            &copy; 2016
            <strong>xiexiang</strong>
            开发出品
        </div>


        <!-- Go to Top Link, just add rel="go-top" to any link to add this functionality -->
        <div class="go-up">

           <i class="fa-binoculars"></i> 地址：重庆市渝北区大竹林互联网产业园

        </div>

    </div>

</footer>
</div>

</div>

<div class="page-loading-overlay">
    <div class="loader-2"></div>
</div>


<!-- Bottom Scripts -->
<script src="/Public/Index/js/bootstrap.min.js"></script>
<script src="/Public/Index/js/TweenMax.min.js"></script>
<script src="/Public/Index/js/resizeable.js"></script>
<script src="/Public/Index/js/joinable.js"></script>
<script src="/Public/Index/js/xenon-api.js"></script>
<script src="/Public/Index/js/xenon-toggles.js"></script>


<!-- Imported scripts on this page -->
<script src="/Public/Index/js/xenon-widgets.js"></script>
<script src="/Public/Index/js/devexpress-web-14.1/js/globalize.min.js"></script>
<script src="/Public/Index/js/devexpress-web-14.1/js/dx.chartjs.js"></script>
<script src="/Public/Index/js/toastr/toastr.min.js"></script>


<!-- JavaScripts initializations and stuff -->
<script src="/Public/Index/js/xenon-custom.js"></script>

</body>
</html>