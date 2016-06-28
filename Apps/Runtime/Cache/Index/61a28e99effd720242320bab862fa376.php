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

    <link rel="shortcut icon" href="/Public/Index/images/icon.ico" type="image/x-icon"/>
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
                    <a href="/" class="logo-expanded">
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


<link rel="stylesheet" href="/Public/Index/js/webuploader/webuploader.css"/>

<div class="row">
    <div class="col-sm-12">

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?php echo ($child); ?></h3>
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

                <form action="" role="form" class="form-horizontal" method="post" id="addInfoForm" enctype="multipart/form-data">


                    <div class="form-group">
                        <label class="col-sm-2 control-label">类型选择</label>

                        <div class="col-sm-10">
                            <select class="form-control" name="type_id">
                                <?php if(is_array($booType)): $i = 0; $__LIST__ = $booType;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value="<?php echo ($v['id']); ?>"><?php echo ($v['name']); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" >图书编号 <span style="color:red;">*</span></label>
                        <div class="col-sm-10">
                            <input type="text" name="num" class="form-control"  placeholder="请输入图书编号">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" >书名 <span style="color:red;">*</span></label>
                        <div class="col-sm-10">
                            <input type="text" name="name" class="form-control"  placeholder="请输入图书名称">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" >出版社名称 <span style="color:red;">*</span> </label>
                        <div class="col-sm-10">
                            <input type="text" name="publisher" class="form-control"  placeholder="图书出版社">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" >作者 <span style="color:red;">*</span></label>
                        <div class="col-sm-10">
                            <input type="text" name="author" class="form-control"  placeholder="请输入图书作者">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" >译者 </label>
                        <div class="col-sm-10">
                            <input type="text" name="translator" class="form-control"  placeholder="请输入译者">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" >出版时间 <span style="color:red;">*</span></label>
                        <div class="col-sm-10">
                            <input type="text" name="outtime" id="outtime" class="form-control"  placeholder="请输入出版时间">
                        </div>
                    </div>

                    <!--<script src="/Public/Index/js/uploadPreview.js"></script>-->

                    <!--<div class="form-group" >-->
                        <!--<label class="col-sm-2 control-label" >图书封面 <span style="color:red;">*</span></label>-->

                        <!--<div class="col-sm-10" id="warp">-->
                            <!--<li style="list-style: none;">-->
                            <!--<input type="file" name="outface" class="form-control" id="up_img_WU_FILE_0" style="padding-bottom: 35px;" placeholder="请上传1:2比例的图片" >-->
                            <!--</li>-->
                        <!--</div>-->

                    <!--</div>-->

                   <!--&lt;!&ndash;图片预览&ndash;&gt;-->
                    <!--<div class="form-group" >-->
                        <!--<label class="col-sm-2 control-label" ></label>-->
                        <!--<div class="col-sm-10">-->
                            <!--<li style="list-style: none;">-->
                                <!--<img id="imgShow_WU_FILE_0" width="160" height="120" />-->
                            <!--</li>-->
                        <!--</div>-->
                    <!--</div>-->

                    <!--dom结构部分-->
                    <link rel="stylesheet" type="text/css" href="/Public/Index/webuploader/webuploader.css">

                    <div class="form-group">
                        <label class="col-sm-2 control-label" >图书封面</label>
                        <div class="col-sm-10" id="show"> <!--图片预览区-->
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" ></label>
                        <div class="col-sm-10">
                            

                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label" ></label>
                        <div class="col-sm-10">
                            <div id="fileList" class="uploader-list"></div>
                            <div id="filePicker">选择图片</div>
                        </div>
                    </div>

                    <div class="form-group-separator"></div>
                         <span style="float: right;">
                           <a type="button" class="btn btn-black btn-sm" onclick="submit_addinfo()" >提交</a>
                        </span>
                </form>
            </div>
        </div>

    </div>

</div>

<!--引入JS-->
<!--<script type="text/javascript" src="/jquery-1.11.1.min.js"></script>-->
<script type="text/javascript" src="/Public/Index/webuploader/webuploader.js"></script>
<script type="text/javascript">
    var outtime = $('#outtime').val();

    // 初始化Web Uploader
    var uploader = WebUploader.create({

        // 选完文件后，是否自动上传。
        auto: true,

        // swf文件路径
        swf: '/Uploader.swf',

        // 文件接收服务端。
        server: 'uploadPic',

        // 选择文件的按钮。可选。
        // 内部根据当前运行是创建，可能是input元素，也可能是flash.
        pick: '#filePicker',

        // 只允许选择图片文件。
        accept: {
            title: 'Images',
            extensions: 'gif,jpg,jpeg,bmp,png',
            mimeTypes: 'image/*'
        }
    });

    // 当有文件添加进来的时候
    uploader.on( 'fileQueued', function( file ) {
        var $li = $(
                        '<div id="' + file.id + '" class="file-item thumbnail" style="float:left;margin-left:5px;">' +
                        '<img>' +
                        '<div class="info">' + file.name + '</div>' +
                        '</div>'
                ),

                $img = $li.find('img');


        // $list为容器jQuery实例
        $('#show').append( $li );

        // 创建缩略图
        // 如果为非图片文件，可以不用调用此方法。
        // thumbnailWidth x thumbnailHeight 为 100 x 100
        var thumbnailWidth  = 200;
        var thumbnailHeight = 160;
        uploader.makeThumb( file, function( error, src ) {
            if ( error ) {
                $img.replaceWith('<span>不能预览</span>');
                return;
            }

            $img.attr( 'src', src );
        }, thumbnailWidth, thumbnailHeight );
    });

    // 文件上传过程中创建进度条实时显示。
    uploader.on( 'uploadProgress', function( file, percentage ) {
        var $li = $( '#'+file.id ),
                $percent = $li.find('.progress span');

        // 避免重复创建
        if ( !$percent.length ) {
            $percent = $('<p class="progress"><span></span></p>')
                    .appendTo( $li )
                    .find('span');
        }

        $percent.css( 'width', percentage * 100 + '%' );
    });

    // 文件上传成功，给item添加成功class, 用样式标记上传成功。  response回调参数
    uploader.on( 'uploadSuccess', function( file,response ) {
        $( '#'+file.id ).addClass('upload-state-done');
        $('#pic_path').html(response.path);

    });

    // 文件上传失败，显示上传出错。
    uploader.on( 'uploadError', function( file ) {
        var $li = $( '#'+file.id ),
                $error = $li.find('div.error');

        // 避免重复创建
        if ( !$error.length ) {
            $error = $('<div class="error"></div>').appendTo( $li );
        }

        $error.text('上传失败');
    });

    // 完成上传完了，成功或者失败，先删除进度条。
    uploader.on( 'uploadComplete', function( file) {
        $( '#'+file.id ).find('.progress').remove();
    });


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